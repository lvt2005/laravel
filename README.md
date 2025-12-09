# HỆ THỐNG ĐẶT LỊCH KHÁM BỆNH (DOCTOR APPOINTMENT SYSTEM)

## 📋 MÔ TẢ DỰ ÁN

Hệ thống đặt lịch khám bệnh trực tuyến được xây dựng bằng Laravel Framework, cho phép bệnh nhân đặt lịch khám với bác sĩ, quản lý hồ sơ bệnh án, thanh toán trực tuyến và nhiều tính năng khác.

### 🎯 Các tính năng chính:
- **Quản lý người dùng**: Đăng ký, đăng nhập, xác thực 2FA, đăng nhập Google
- **Đặt lịch khám**: Đặt lịch với bác sĩ, xem lịch làm việc, kiểm tra slot trống
- **Quản lý bác sĩ**: Thông tin bác sĩ, chuyên khoa, dịch vụ, lịch làm việc
- **Hồ sơ bệnh án**: Quản lý ghi chú y tế, đơn thuốc, tiến trình điều trị
- **Thanh toán**: Tích hợp thanh toán trực tuyến, quản lý hoàn tiền
- **Diễn đàn**: Trao đổi, thảo luận giữa bệnh nhân và bác sĩ
- **Đánh giá**: Đánh giá và nhận xét về bác sĩ
- **Thông báo**: Hệ thống thông báo qua email và trong app
- **Phân quyền**: Hệ thống role-permission với Admin, Doctor, Patient

## 🔧 YÊU CẦU HỆ THỐNG

### Phiên bản phần mềm:
- **PHP**: >= 8.2
- **Laravel Framework**: 12.x
- **Composer**: 2.x trở lên
- **MySQL**: >= 8.0 hoặc **MariaDB**: >= 10.5
- **Vite**: 7.x 

### Các thư viện PHP chính:
- `firebase/php-jwt`: ^6.10 - Xử lý JWT token
- `tymon/jwt-auth`: ^2.2 - Authentication với JWT
- `laravel/tinker`: ^2.10.1 - Laravel REPL
- PHPMailer - Gửi email tùy chỉnh

### Các thư viện JavaScript chính:
- `@tailwindcss/vite`: ^4.0.0 - TailwindCSS framework
- `axios`: ^1.11.0 - HTTP client
- `vite`: ^7.0.7 - Build tool

## 📥 HƯỚNG DẪN CÀI ĐẶT

### Bước 1: Clone dự án

```bash
# Clone repository từ Git
git clone https://github.com/lvt2005/laravel.git

# Di chuyển vào thư mục dự án
cd laravel
```

### Bước 2: Cài đặt Composer Dependencies

```bash
# Cài đặt tất cả các package PHP
composer install

# Hoặc nếu gặp lỗi, sử dụng:
composer install --ignore-platform-reqs
```

> **Lưu ý**: Nếu chưa có, hãy download tại: https://getcomposer.org/download/

### Bước 3: Tạo file .env

```bash
# Windows (PowerShell)
copy .env.example .env

# Linux/MacOS
cp .env.example .env
```

### Bước 4: Cấu hình file .env

Mở file `.env` và cấu hình các thông số sau:

```env
# Thông tin ứng dụng
APP_NAME=doctor_appointment
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Ho_Chi_Minh

# Kết nối Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=doctor_appointment
DB_USERNAME=root
DB_PASSWORD= "password của bạn"

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_STORE=database

# Mail Configuration (nếu cần gửi email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME= "your_email@gmail.com"
MAIL_PASSWORD= "password của bạn"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS= "your_email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Bước 5: Tạo Application Key

```bash
# Tạo APP_KEY cho Laravel
php artisan key:generate
```

### Bước 6: Tạo JWT Secret

```bash
# Tạo JWT_SECRET cho xác thực
php artisan jwt:secret
```


### Bước 7: Tạo Database

Tạo database trong MySQL/MariaDB:

```sql
-- Mở MySQL Command Line hoặc phpMyAdmin và chạy:
CREATE DATABASE doctor_appointment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```


### Bước 8: Chạy Migration

```bash
# Chạy tất cả migrations để tạo bảng trong database
php artisan migrate

# Nếu muốn reset và chạy lại từ đầu
php artisan migrate:fresh
```

### Bước 9: Seed dữ liệu mẫu (Optional)

```bash
# Chạy seeder để tạo dữ liệu mẫu
php artisan db:seed

# Hoặc chỉ định seeder cụ thể
php artisan db:seed --class=DatabaseSeeder
php artisan db:seed --class=SpecializationServiceSeeder
php artisan db:seed --class=WorkScheduleSeeder
```

**Dữ liệu mẫu sau khi seed**:
- **Admin**: admin@doctor-appointment.com / Admin@123
- **Doctor**: doctor@doctor-appointment.com / Doctor@123
- **User**: test@example.com / Test1234


### Bước 10: Cài đặt NPM Dependencies

```bash
# Cài đặt các package JavaScript
npm install

# Hoặc sử dụng Yarn
yarn install
```

### Bước 12: Tạo Symbolic Link cho Storage

```bash
# Tạo symbolic link từ public/storage -> storage/app/public
php artisan storage:link
```

### Bước 13: Set quyền cho thư mục (Linux/MacOS)

```bash
# Set quyền cho storage và bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

> **Windows users**: Không cần thiết lập quyền này.

## 🚀 KHỞI CHẠY SERVER

### Phương pháp 1: Sử dụng Laravel Development Server

```bash
# Khởi chạy server tại http://localhost:8000
php artisan serve

# Hoặc chỉ định host và port khác
php artisan serve --host=127.0.0.1 --port=8080
```

### Phương pháp 2: Sử dụng Composer Script (Khuyến nghị)

```bash
# Chạy development với tất cả services (server, queue, logs, vite)
composer run dev
```

Script này sẽ chạy đồng thời:
- Laravel development server (port 8000)
- Queue worker
- Laravel Pail (logs)
- Vite dev server (HMR)


### Chạy Queue Worker (Cho xử lý background jobs)

```bash
# Chạy queue worker
php artisan queue:work

# Hoặc chạy queue với retry
php artisan queue:listen --tries=3
```

## 📦 CẤU TRÚC THỦ MỤC

```
doctor-appointment/
├── app/                      # Application logic
│   ├── Console/              # Artisan commands
│   ├── Http/                 # Controllers, Middleware, Requests
│   │   ├── Controllers/      # API Controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Kernel.php        # HTTP Kernel
│   ├── Models/               # Eloquent Models (User, Doctor, Payment, etc.)
│   ├── Providers/            # Service Providers
│   └── Services/             # Business logic services
├── bootstrap/                # Bootstrap framework
├── config/                   # Configuration files
│   ├── app.php              # App configuration
│   ├── database.php         # Database configuration
│   ├── jwt.php              # JWT configuration
│   └── ...
├── database/
│   ├── factories/           # Model factories
│   ├── migrations/          # Database migrations (79+ files)
│   └── seeders/             # Database seeders
├── public/                  # Public assets
│   ├── index.php           # Entry point
│   └── frontend/           # Frontend assets
├── resources/
│   ├── css/                # CSS source files
│   ├── js/                 # JavaScript source files
│   └── views/              # Blade templates
├── routes/
│   ├── api.php             # API routes
│   ├── web.php             # Web routes
│   └── console.php         # Console routes
├── storage/                # Storage (logs, cache, uploads)
├── tests/                  # Tests (Unit, Feature)
├── tools/                  # Custom tools & scripts
├── PHPMailer/              # PHPMailer library
├── .env.example            # Environment example
├── composer.json           # PHP dependencies
├── package.json            # JavaScript dependencies
└── vite.config.js          # Vite configuration
```


## 🔒 BẢO MẬT

- **JWT Authentication**: Xác thực bằng JSON Web Token
- **2FA**: Xác thực 2 yếu tố
- **Password Hashing**: Bcrypt với 12 rounds
- **CSRF Protection**: Laravel CSRF token
- **Rate Limiting**: API throttling
- **SQL Injection Prevention**: Eloquent ORM
- **XSS Prevention**: Blade templating escape

## 🐛 XỬ LÝ LỖI THƯỜNG GẶP

### Lỗi: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Lỗi: "SQLSTATE[HY000] [1045] Access denied for user"
- Kiểm tra lại thông tin DB_USERNAME, DB_PASSWORD trong .env
- Đảm bảo MySQL service đang chạy

### Lỗi: "Class 'JWT' not found"
```bash
composer require tymon/jwt-auth
php artisan jwt:secret
```

### Lỗi: "npm ERR! code ENOENT"
```bash
# Xóa node_modules và package-lock.json
rm -rf node_modules package-lock.json
npm install
```

### Lỗi: Storage permission denied (Linux)
```bash
chmod -R 775 storage bootstrap/cache
```

### Lỗi: "Vite manifest not found"
```bash
npm run build
```

## 📚 TÀI LIỆU THAM KHẢO

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [JWT Auth Documentation](https://jwt-auth.readthedocs.io/)
- [Vite Documentation](https://vitejs.dev/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)

## 🛠️ CÔNG CỤ HỖ TRỢ

Project có sẵn các tools trong thư mục `tools/`:
- `generate_migrations.py` - Generate migration files
- `seed_roles.php` - Seed roles data
- `seed_services.php` - Seed services data
- `seed_specializations.php` - Seed specializations data

## 📞 HỖ TRỢ

Nếu gặp vấn đề trong quá trình cài đặt, vui lòng:
1. Kiểm tra logs trong `storage/logs/laravel.log`
2. Chạy `php artisan config:clear` và `php artisan cache:clear`
3. Đảm bảo tất cả requirements đã được cài đặt đúng phiên bản

## 📄 LICENSE

This project is open-sourced software licensed under the MIT license.

                                                           ~~~~ _Thank you!_~~~~
