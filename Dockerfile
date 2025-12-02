# =============================================================================
# Dockerfile cho Laravel Medical Clinic Application
# =============================================================================
# Multi-stage build để tối ưu kích thước image

# Stage 1: Build frontend assets với Node.js
FROM node:20-alpine AS frontend-builder

WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./

# Install dependencies
RUN npm ci --silent || npm install --silent

# Copy source files cần thiết cho build
COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

# Build frontend assets
RUN npm run build

# =============================================================================
# Stage 2: PHP base image với các extensions cần thiết
FROM php:8.2-fpm-alpine AS php-base

# Install system dependencies
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis extension (optional)
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Copy PHP configuration cho Docker (KHÔNG dùng file php.ini gốc của XAMPP Windows)
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configure OPcache for production
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# =============================================================================
# Stage 3: Composer dependencies - Build với đầy đủ source code
FROM composer:2.7 AS composer-builder

WORKDIR /app

# Copy toàn bộ source code để composer có thể chạy autoload
COPY . .

# Install dependencies và generate optimized autoloader
RUN composer install \
    --no-dev \
    --no-interaction \
    --ignore-platform-reqs \
    --prefer-dist \
    --optimize-autoloader

# =============================================================================
# Stage 4: Final production image
FROM php-base AS production

WORKDIR /var/www/html

# Create non-root user trước khi copy files
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

# Copy vendor từ composer-builder (đã có optimized autoloader)
COPY --from=composer-builder /app/vendor ./vendor

# Copy application source code
COPY --chown=www:www . .

# Copy built frontend assets từ frontend-builder
COPY --from=frontend-builder /app/public/build ./public/build

# Create storage directories và set permissions đúng cách
# Chạy với quyền root trước khi switch user
RUN mkdir -p storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www:www /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 755 /var/www/html/public

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

# Entrypoint script - copy và set permissions trước khi switch user
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Switch to non-root user
USER www

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]

