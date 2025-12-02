#!/bin/sh
# =============================================================================
# Docker Entrypoint Script cho Laravel Application
# =============================================================================

set -e

echo "Starting Laravel application..."

# Đợi MySQL sẵn sàng (dùng PHP PDO để test connection)
echo "Waiting for MySQL to be ready..."
max_tries=30
counter=0

# Sử dụng PHP để test MySQL connection
while ! php -r "
try {
    new PDO('mysql:host=${DB_HOST:-mysql};port=3306', '${DB_USERNAME:-laravel}', '${DB_PASSWORD:-secret}');
    exit(0);
} catch (PDOException \$e) {
    exit(1);
}
" 2>/dev/null; do
    counter=$((counter + 1))
    if [ $counter -gt $max_tries ]; then
        echo "Warning: MySQL connection timeout after ${max_tries} attempts. Continuing anyway..."
        break
    fi
    echo "Attempt $counter/$max_tries - MySQL not ready yet, waiting..."
    sleep 2
done

if [ $counter -le $max_tries ]; then
    echo "MySQL is ready!"
fi

# Kiểm tra và tạo APP_KEY nếu chưa có
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Warning: APP_KEY is not set or invalid. Please set APP_KEY in environment!"
    echo "You can generate one with: php artisan key:generate --show"
fi

# Cache configuration cho production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration for production..."
    php artisan config:cache || echo "Warning: config:cache failed"
    php artisan route:cache || echo "Warning: route:cache failed"
    php artisan view:cache || echo "Warning: view:cache failed"
else
    # Clear cache for non-production environments
    php artisan config:clear 2>/dev/null || true
    php artisan route:clear 2>/dev/null || true
    php artisan view:clear 2>/dev/null || true
fi

# Chạy migrations (với error handling)
echo "Running database migrations..."
if php artisan migrate --force; then
    echo "Migrations completed successfully!"
else
    echo "Warning: Migrations failed. Check database connection and try again."
fi

# Tạo storage link nếu chưa có
if [ ! -L "/var/www/html/public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link 2>/dev/null || echo "Warning: Could not create storage link"
fi

echo "Laravel application is ready!"
echo "PHP-FPM is starting on port 9000..."

# Chạy command được truyền vào
exec "$@"

