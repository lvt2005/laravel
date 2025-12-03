<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Các Chuyên Khoa - Hệ thống đặt lịch hẹn bác sĩ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; line-height: 1.6; }
        
        /* Header Menu Styles */
        .header-wrap {
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .header-nav {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 280px;
            margin-right: 30px;
        }

        .logo-section a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo-img {
            width: 280px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .logo-text h1 {
            font-size: 16px;
            color: #333;
            font-weight: 600;
            line-height: 1.2;
        }

        .logo-text h1 small {
            display: block;
            font-size: 13px;
            color: #666;
            font-weight: 400;
        }

        .search-box {
            flex: 0 0 200px;
            margin-right: 30px;
        }

        .search-box input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 13px;
            color: #999;
            background-color: #f9f9f9;
        }

        .search-box input::placeholder {
            color: #bbb;
        }

        .menu-list {
            display: flex;
            align-items: center;
            list-style: none;
            flex: 1;
            gap: 0;
            margin: 0;
        }

        .menu-item {
            position: relative;
        }

        .menu-item > a {
            display: flex;
            align-items: center;
            padding: 25px 15px;
            color: #333;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: color 0.3s ease;
        }

        .menu-item > a:hover {
            color: #3498db;
        }

        .menu-item.menu-item-has-children > a {
            display: flex;
            gap: 5px;
        }

        .menu-item i {
            font-size: 14px;
            transition: transform 0.3s ease;
        }

        .menu-item.menu-dropdown:hover > i {
            transform: rotate(180deg);
        }

        .menu-item.menu-dropdown:hover .child {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Dropdown Menu */
        .child {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            list-style: none;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .child li {
            list-style: none;
        }

        .child li a {
            display: block;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            white-space: normal;
        }

        .child li a:hover {
            background-color: #f0f8ff;
            color: #3498db;
            padding-left: 25px;
        }

        /* Mega Menu */
        .menu-mega {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 100;
            display: flex;
            min-width: 600px;
        }

        .menu-mega ul {
            flex: 1;
            list-style: none;
            padding: 15px 0;
            border-right: 1px solid #f0f0f0;
        }

        .menu-mega ul:last-child {
            border-right: none;
        }

        .menu-mega li {
            list-style: none;
        }

        .menu-mega .menu-link {
            display: block;
            padding: 10px 20px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .menu-mega .menu-link:hover {
            background-color: #f0f8ff;
            color: #3498db;
            padding-left: 25px;
        }

        .menu-chuyen-khoa:hover .menu-mega {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Right Section */
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: auto;
            position: relative;
        }

        .language-selector {
            background-color: transparent;
            border: none;
            color: #333;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 8px 10px;
            transition: color 0.3s ease;
        }

        .language-selector:hover {
            color: #3498db;
        }

        .btn-register {
            background-color: #f4c430;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
        }

        .btn-register:hover {
            background-color: #e8b81a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 196, 48, 0.3);
        }

        /* Header Button Styles */
        .btn-header-outline {
            background-color: transparent;
            color: #333;
            border: 1px solid #ddd;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
        }

        .btn-header-outline:hover {
            border-color: #3498db;
            color: #3498db;
        }

        .btn-header-primary {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-decoration: none;
            display: inline-block;
        }

        .btn-header-primary:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        /* User Menu Styles */
        .user-menu {
            position: relative;
            display: none;
        }

        .user-menu.active {
            display: block;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: #f0f8ff;
            color: #3498db;
            border: 2px solid #3498db;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .user-button:hover {
            background-color: #3498db;
            color: #fff;
        }

        .user-button i {
            font-size: 18px;
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background-color: #ffffff;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 100;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-menu:hover .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 15px 20px;
            background-color: #f0f8ff;
            border-bottom: 1px solid #e0e0e0;
        }

        .user-dropdown-header .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .user-dropdown-header .user-role {
            font-size: 12px;
            color: #666;
        }

        .user-dropdown a,
        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-dropdown a:hover,
        .user-dropdown-item:hover {
            background-color: #f0f8ff;
            color: #3498db;
            padding-left: 25px;
        }

        .user-dropdown a i,
        .user-dropdown-item i {
            width: 20px;
            font-size: 16px;
            color: #1e5ba8;
        }

        .user-dropdown-divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 5px 0;
        }

        .user-dropdown .logout-btn,
        .user-dropdown-item.logout {
            color: #e74c3c;
        }

        .user-dropdown .logout-btn i,
        .user-dropdown-item.logout i {
            color: #e74c3c;
        }

        .user-dropdown-item.logout:hover {
            background-color: #ffebee;
            color: #c0392b;
        }

        .login-btn-wrap {
            display: block;
        }

        .login-btn-wrap.hidden {
            display: none;
        }

        /* Page Header & Main Content */
        .page-header {
            background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
            color: white;
            padding: 40px 20px;
        }

        .page-header .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .page-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .breadcrumb {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #1e5ba8;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #666;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .specialty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        /* Footer styles */
        footer {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: #ffffff;
            padding: 50px 0 30px;
            margin-top: auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 50px;
            margin-bottom: 40px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer-logo-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .footer-logo img {
            width: 170px;
            height: 100px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 8px;
        }

        .footer-logo h3 {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .footer-logo-section p {
            font-size: 13px;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 8px;
        }

        footer h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .footer-section ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-section ul li {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #fbbf24;
            padding-left: 8px;
        }

        .footer a:hover {
            color: #fbbf24;
        }

        .working-hours {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .working-hours strong {
            color: #ffffff;
            font-weight: 700;
        }

        .hotline-btn {
            display: inline-block;
            background-color: #fbbf24;
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            margin-top: 10px;
            transition: all 0.3s ease;
            margin-left: 30px;
        }

        .hotline-btn:hover {
            background-color: #fcd34d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .contact-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            background-color: #fbbf24;
            color: #1e40af;
            border-radius: 50%;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: #fcd34d;
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        }

        .newsletter-section p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 12px;
            font-weight: 500;
        }

        .newsletter-form {
            display: flex;
            gap: 8px;
            margin-bottom: 15px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 25px;
            font-size: 13px;
            color: #333;
            background-color: #ffffff;
        }

        .newsletter-form input::placeholder {
            color: #999;
        }

        .newsletter-form input:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
        }

        .newsletter-form button {
            padding: 10px 20px;
            background-color: #1e3a8a;
            color: #ffffff;
            border: none;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .newsletter-form button:hover {
            background-color: #1e40af;
            transform: translateY(-2px);
        }

        .dmca-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .dmca-badge img {
            width: 160px;
            height: auto;
        }

        .footer-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin: 30px 0;
        }

        .footer-bottom {
            text-align: center;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-bottom p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 0;
            list-style: none;
            flex-wrap: wrap;
        }

        .footer-links li {
            font-size: 12px;
        }

        .footer-links li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 0 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .footer-links li:last-child a {
            border-right: none;
        }

        .footer-links li a:hover {
            color: #fbbf24;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .menu-list {
                gap: 0;
            }

            .menu-item > a {
                padding: 20px 12px;
                font-size: 12px;
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
                gap: 40px;
            }
        }

        @media (max-width: 768px) {
            .header-nav {
                height: auto;
                padding: 15px 20px;
                flex-wrap: wrap;
            }

            .menu-list {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
                display: none;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .user-dropdown {
                right: auto;
                left: 0;
            }

            footer {
                padding: 40px 0 20px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            footer h4 {
                font-size: 14px;
                margin-bottom: 12px;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form input,
            .newsletter-form button {
                width: 100%;
            }

            .social-icons {
                justify-content: flex-start;
            }

            .footer-links {
                gap: 10px;
                justify-content: center;
            }

            .footer-links li a {
                padding: 0 10px;
            }
        }

        @media (max-width: 480px) {
            .footer-content {
                gap: 20px;
            }

            footer h4 {
                font-size: 13px;
            }

            footer p {
                font-size: 12px;
            }

            .social-icons {
                gap: 10px;
            }

            .social-icons a {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
        }
    </head>
<body>
    <script src="{{ asset('frontend/js/auth.js') }}"></script>
    @include('partials.header')

    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-hospital"></i> Các Chuyên Khoa</h1>
            <p>Khám phá các chuyên khoa của Bệnh viện Nam Sài Gòn</p>
        </div>
    </div>

    <div class="main-content">
        <div class="breadcrumb">
            <a href="/">Trang chủ</a>
            <span>/</span>
            <span>Chuyên khoa</span>
        </div>

        <div class="specialties-grid" id="specialtiesGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <!-- Specialties will be loaded dynamically -->
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <!-- Logo & Info -->
                <div class="footer-logo-section">
                    <div class="footer-logo">
                        <img src="{{ asset('frontend/img/logomau.jpg') }}" alt="Logo" />
                    </div>
                    <p>
                        Địa chỉ: 70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, Thành phố Hồ Chí
                        Minh, Việt Nam
                    </p>
                    <p>Email: nhom5@gmail.com</p>
                    <p>
                        GPDKKD: 0312088602 cấp ngày 14/12/2012 bởi Sở Kế hoạch và Đầu tư
                        TPHCM. Giấy phép hoạt động khám bệnh, chữa bệnh số 230/BYT-GPHD do
                        Bộ Y Tế cấp.
                    </p>
                </div>

                <!-- About -->
                <div class="footer-section">
                    <h4>Về chúng tôi</h4>
                    <ul>
                        <li><a href="/tim-bac-si">Đội ngũ bác sĩ</a></li>
                        <li><a href="#">Cơ sở vật chất</a></li>
                        <li><a href="#">Câu chuyện khách hàng</a></li>
                        <li><a href="#">Tuyên dụng</a></li>
                        <li><a href="#">Cảm nang bệnh</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <!-- Working Hours -->
                <div class="footer-section">
                    <h4>Giờ làm việc</h4>
                    <div class="working-hours">
                        <p><strong>Từ thứ 2 đến thứ 7</strong></p>
                        <p>Buổi sáng:<br />7:00 - 12:00</p>
                        <p>Buổi chiều:<br />13:30 - 17:00</p>
                    </div>
                    <a href="tel:18006767" class="hotline-btn">Hotline: 1800 6767</a>
                </div>

                <!-- Contact -->
                <div class="contact-section">
                    <h4>Liên hệ</h4>
                    <div class="social-icons">
                        <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                    <div class="newsletter-section">
                        <p><strong>Theo dõi bản tin chúng tôi</strong></p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Email" required />
                            <button type="submit">Đăng ký</button>
                        </form>
                    </div>
                    <div class="dmca-badge">
                        <img src="{{ asset('frontend/img/dmca_protected_16_120.png') }}" alt="" />
                    </div>
                </div>
            </div>

            <hr class="footer-divider" />

            <div class="footer-bottom">
                <p>&copy; Hệ thống đặt lịch hẹn. Tất cả các quyền được bảo vệ.</p>
                <ul class="footer-links">
                    <li><a href="#">Chính sách bảo mật</a></li>
                    <li><a href="#">Điều khoản sử dụng</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        // API Base URL
        const API_BASE = '/api';

        // Check login status from localStorage/sessionStorage
        async function checkLoginStatus() {
            const accessToken = localStorage.getItem('access_token') || sessionStorage.getItem('access_token');

            const loginBtn = document.getElementById('loginBtn');
            const userMenu = document.getElementById('userMenu');
            const userNameEl = document.getElementById('userName');
            const userNameFullEl = document.getElementById('userNameFull');
            const userAvatarEl = document.getElementById('userAvatar');
            const userIconEl = document.getElementById('userIcon');
            const userRoleEl = document.getElementById('userRole');

            if (accessToken && window.AuthAPI) {
                try {
                    // Fetch user profile from API
                    const profile = await AuthAPI.getProfile();

                    if (profile && profile.id) {
                        // User is logged in - show user menu
                        loginBtn.style.display = 'none';
                        userMenu.classList.add('active');

                        // Update user info
                        const displayName = profile.full_name || profile.email.split('@')[0];
                        userNameEl.textContent = displayName;
                        userNameFullEl.textContent = displayName;

                        // Update avatar
                        if (profile.avatar_url) {
                            userAvatarEl.src = profile.avatar_url;
                            userAvatarEl.style.display = 'block';
                            if (userIconEl) userIconEl.style.display = 'none';
                        }

                        // Update role
                        const roleMap = {
                            'USER': 'Bệnh nhân',
                            'DOCTOR': 'Bác sĩ',
                            'ADMIN': 'Quản trị viên'
                        };
                        userRoleEl.textContent = roleMap[profile.type] || 'Người dùng';

                        // Store for later use
                        localStorage.setItem('user_profile', JSON.stringify(profile));
                    } else {
                        showLoginButton();
                    }
                } catch (e) {
                    console.error('Error fetching profile:', e);
                    showLoginButton();
                }
            } else {
                showLoginButton();
            }
        }

        function showLoginButton() {
            const loginBtn = document.getElementById('loginBtn');
            const userMenu = document.getElementById('userMenu');
            if (loginBtn) loginBtn.style.display = 'inline-block';
            if (userMenu) userMenu.classList.remove('active');
        }

        // Logout function
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', async function (e) {
                e.preventDefault();
                if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                    try {
                        if (window.AuthAPI) await AuthAPI.logout();
                    } catch (e) {
                        console.error('Logout error:', e);
                    }
                    // Clear all storage
                    localStorage.removeItem('access_token');
                    localStorage.removeItem('refresh_token');
                    localStorage.removeItem('session_id');
                    localStorage.removeItem('user_profile');
                    sessionStorage.removeItem('access_token');
                    sessionStorage.removeItem('refresh_token');
                    sessionStorage.removeItem('session_id');

                    // Redirect to login
                    window.location.href = '/dang-nhap';
                }
            });
        }

        // Navigate to profile
        const profileLink = document.getElementById('profileLink');
        if (profileLink) {
            profileLink.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '/ho-so';
            });
        }

        // Load specializations to menu
        async function loadSpecializationsToMenu() {
            const col1 = document.getElementById('menuSpecCol1');
            const col2 = document.getElementById('menuSpecCol2');
            if (!col1 || !col2) return;

            try {
                const response = await fetch('/api/public/specializations');
                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    const half = Math.ceil(result.data.length / 2);
                    const firstHalf = result.data.slice(0, half);
                    const secondHalf = result.data.slice(half);

                    col1.innerHTML = firstHalf.map(spec => {
                        return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
                    }).join('');

                    col2.innerHTML = secondHalf.map(spec => {
                        return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
                    }).join('');
                }
            } catch (error) {
                console.error('Error loading specializations:', error);
            }
        }

        // Load specializations from API
        async function loadSpecializations() {
            const grid = document.getElementById('specialtiesGrid');
            
            try {
                const response = await fetch(`${API_BASE}/public/specializations`);
                const result = await response.json();
                const specializations = result.data || result || [];
                
                // Define colors for specialties
                const colors = [
                    ['#1e5ba8', '#0d3a6e'],
                    ['#27ae60', '#1e8449'],
                    ['#e74c3c', '#c0392b'],
                    ['#9b59b6', '#8e44ad'],
                    ['#f39c12', '#d68910'],
                    ['#00bcd4', '#0097a7'],
                    ['#795548', '#5d4037'],
                    ['#e91e63', '#c2185b'],
                    ['#3f51b5', '#303f9f'],
                    ['#607d8b', '#455a64'],
                    ['#4caf50', '#388e3c'],
                    ['#ff5722', '#e64a19']
                ];
                
                // Define icons for specialties
                const icons = [
                    'fas fa-brain',
                    'fas fa-bone',
                    'fas fa-procedures',
                    'fas fa-heartbeat',
                    'fas fa-head-side-mask',
                    'fas fa-walking',
                    'fas fa-syringe',
                    'fas fa-ambulance',
                    'fas fa-vials',
                    'fas fa-x-ray',
                    'fas fa-pills',
                    'fas fa-stethoscope'
                ];
                
                grid.innerHTML = '';
                
                specializations.forEach((spec, index) => {
                    const colorPair = colors[index % colors.length];
                    const icon = icons[index % icons.length];
                    
                    const card = document.createElement('a');
                    card.href = `/chuyen-khoa/${spec.id}`;
                    card.className = 'specialty-card card';
                    card.style.textDecoration = 'none';
                    card.style.transition = 'transform 0.3s';
                    
                    card.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 20px;">
                            <div style="width: 70px; height: 70px; background: linear-gradient(135deg, ${colorPair[0]}, ${colorPair[1]}); border-radius: 15px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                ${spec.image_url ? 
                                    `<img src="${spec.image_url}" alt="${spec.name}" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;">` :
                                    `<i class="${icon}" style="font-size: 30px; color: white;"></i>`
                                }
                            </div>
                            <div>
                                <h3 style="color: #1e5ba8; margin-bottom: 5px;">${spec.name}</h3>
                                <p style="margin: 0; color: #666; font-size: 14px;">${spec.description || 'Chuyên khoa hàng đầu'}</p>
                            </div>
                        </div>
                    `;
                    
                    grid.appendChild(card);
                });
                
            } catch(error) {
                console.error('Error loading specializations:', error);
                grid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">Không thể tải danh sách chuyên khoa</p>';
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            checkLoginStatus();
            loadSpecializationsToMenu();
            loadSpecializations();
        });
    </script>
</body>
</html>
