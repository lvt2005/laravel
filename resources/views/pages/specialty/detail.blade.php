<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>{{ $specialization->name }} - Bệnh viện Nam Sài Gòn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; line-height: 1.6; }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header .container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }
        .header .logo {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header .logo img {
            height: 50px;
            border-radius: 8px;
        }
        .header nav {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .header nav a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .header nav a:hover, .header nav a.active {
            background: rgba(255,255,255,0.2);
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* User Avatar Section */
        .user-avatar-section {
            position: relative;
            display: none;
        }
        .user-avatar-section.logged-in {
            display: block;
        }
        .user-avatar-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            padding: 6px 12px 6px 6px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .user-avatar-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        .user-avatar-btn img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        .user-avatar-btn .user-name {
            color: white;
            font-weight: 500;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 200px;
            display: none;
            overflow: hidden;
            z-index: 1001;
        }
        .user-dropdown.show {
            display: block;
        }
        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #333 !important;
            text-decoration: none;
            transition: all 0.2s;
            background: white !important;
        }
        .user-dropdown a:hover {
            background: #f5f5f5 !important;
        }
        .user-dropdown a i {
            width: 20px;
            color: #1e5ba8;
        }
        .user-dropdown .logout-btn {
            color: #dc3545 !important;
            border-top: 1px solid #eee;
        }
        .user-dropdown .logout-btn i {
            color: #dc3545;
        }
        .login-btn-wrap {
            display: block;
        }
        .login-btn-wrap.hidden {
            display: none;
        }
        .btn-register {
            background: #ff9800;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-register:hover {
            background: #f57c00;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e5ba8 0%, #2980b9 50%, #3498db 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            position: relative;
        }
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .hero-image {
            width: 150px;
            height: 150px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .hero-icon {
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 4px solid rgba(255,255,255,0.3);
        }
        .hero-icon i {
            font-size: 60px;
        }
        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .hero-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-item .label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .breadcrumb {
            padding: 15px 0;
            color: #666;
        }
        .breadcrumb a {
            color: #1e5ba8;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Content Section */
        .content-section {
            padding: 40px 0;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .info-card h2 {
            color: #1e5ba8;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-card h2 i {
            color: #3498db;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .feature-item i {
            color: #27ae60;
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 3px;
        }

        /* Services Section */
        .services-section {
            padding: 40px 0;
            background: #f8f9fa;
        }
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            color: #1e5ba8;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .section-title p {
            color: #666;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .service-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: 3px solid transparent;
            position: relative;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .service-card.selected {
            border-color: #27ae60;
            background: #f0fff4;
        }
        .service-card.selected::before {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #27ae60;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
        .service-card h3 {
            color: #1e5ba8;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .service-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .service-price {
            color: #27ae60;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .service-duration {
            color: #888;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Doctors Section */
        .doctors-section {
            padding: 40px 0;
            background: white;
        }
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .doctor-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
            border: 3px solid transparent;
        }
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .doctor-card.selected {
            border-color: #27ae60;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.3);
        }
        .doctor-card.selected::after {
            content: '✓ Đã chọn';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .doctor-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .doctor-avatar-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 60px;
            font-weight: bold;
        }
        .doctor-info {
            padding: 20px;
        }
        .doctor-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .doctor-degree {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .doctor-meta {
            display: flex;
            gap: 15px;
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
        .doctor-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .doctor-meta i {
            color: #f39c12;
        }
        .btn-view-detail {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            background: #f0f0f0;
            color: #333;
            font-size: 14px;
        }
        .btn-view-detail:hover {
            background: #e0e0e0;
        }
        .no-doctors {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .no-doctors i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }
        .pagination button {
            padding: 10px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .pagination button:hover:not(:disabled) {
            background: #1e5ba8;
            color: white;
            border-color: #1e5ba8;
        }
        .pagination button.active {
            background: #1e5ba8;
            color: white;
            border-color: #1e5ba8;
        }
        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Floating Book Button */
        .floating-book-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4);
            z-index: 100;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }
        .floating-book-btn.show {
            display: flex;
        }
        .floating-book-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 50px rgba(39, 174, 96, 0.5);
        }
        @keyframes pulse {
            0% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4); }
            50% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.6); }
            100% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4); }
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 40px 20px;
            flex-wrap: wrap;
        }
        .action-btn {
            padding: 15px 30px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .action-btn.secondary {
            background: white;
            color: #1e5ba8;
            border: 2px solid #1e5ba8;
        }

        /* Footer */
        .footer {
            background: #1e5ba8;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .footer a {
            color: white;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 1.8rem;
            }
            .stats-bar {
                gap: 30px;
            }
            .header .container {
                flex-direction: column;
                gap: 15px;
                height: auto;
                padding: 15px 0;
            }
            .header nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            .header nav a {
                margin: 0;
                font-size: 14px;
            }
            .floating-book-btn {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <a href="/" class="logo">
                <img src="{{ asset('frontend/img/logomau.jpg') }}" alt="Logo">
            </a>
            <nav>
                <a href="/">Trang chủ</a>
                <a href="/tim-bac-si">Đội ngũ bác sĩ</a>
                <a href="/chuyen-khoa" class="active">Chuyên khoa</a>
                <a href="/dat-lich">Đặt lịch</a>
            </nav>
            <div class="header-right">
                <div class="login-btn-wrap" id="loginBtnWrap">
                    <a href="/dang-nhap" class="btn-register">Đăng nhập</a>
                </div>
                <div class="user-avatar-section" id="userAvatarSection">
                    <div class="user-avatar-btn" onclick="toggleUserDropdown()">
                        <img src="/frontend/img/Screenshot 2025-10-17 201418.png" alt="Avatar" id="headerAvatar">
                        <span class="user-name" id="headerUserName">User</span>
                        <i class="fas fa-chevron-down" style="color: white; font-size: 12px;"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="/ho-so"><i class="fas fa-user"></i> Hồ sơ cá nhân</a>
                        <a href="/ho-so#appointments"><i class="fas fa-calendar-check"></i> Lịch hẹn của tôi</a>
                        <a href="/ho-so#settings"><i class="fas fa-cog"></i> Cài đặt</a>
                        <a href="javascript:void(0)" class="logout-btn" onclick="handleLogout()"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            @if($specialization->image_url)
                <img src="{{ $specialization->image_url }}" alt="{{ $specialization->name }}" class="hero-image">
            @else
                <div class="hero-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
            @endif
            <h1>{{ $specialization->name }}</h1>
            <p>{{ $specialization->description ?? 'Chuyên khoa hàng đầu của Bệnh viện Nam Sài Gòn với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại.' }}</p>
            
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="number" id="doctorCount">{{ $specialization->doctors_count ?? 0 }}</div>
                    <div class="label">Bác sĩ</div>
                </div>
                <div class="stat-item">
                    <div class="number" id="serviceCount">{{ $specialization->services->count() ?? 0 }}</div>
                    <div class="label">Dịch vụ</div>
                </div>
                <div class="stat-item">
                    <div class="number">24/7</div>
                    <div class="label">Hỗ trợ</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Trang chủ</a> / 
            <a href="/chuyen-khoa">Chuyên khoa</a> / 
            <span>{{ $specialization->name }}</span>
        </div>
    </div>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="info-card">
                <h2><i class="fas fa-info-circle"></i> Giới thiệu về {{ $specialization->name }}</h2>
                <p>{{ $specialization->description ?? 'Là một trong những chuyên khoa mũi nhọn của Bệnh viện Nam Sài Gòn. Với đội ngũ y bác sĩ giàu kinh nghiệm và hệ thống trang thiết bị hiện đại, chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất cho quý khách hàng.' }}</p>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Đội ngũ chuyên gia</strong>
                            <p>Bác sĩ giàu kinh nghiệm, được đào tạo chuyên sâu</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Thiết bị hiện đại</strong>
                            <p>Trang thiết bị y tế tiên tiến nhất</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Dịch vụ tận tâm</strong>
                            <p>Chăm sóc bệnh nhân chu đáo, tận tình</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Đặt lịch dễ dàng</strong>
                            <p>Hệ thống đặt lịch trực tuyến tiện lợi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    @if($specialization->services && $specialization->services->count() > 0)
    <section class="services-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-clipboard-list"></i> Dịch vụ khám</h2>
                <p>Chọn dịch vụ bạn muốn khám tại {{ $specialization->name }}</p>
            </div>
            <div class="services-grid" id="servicesGrid">
                @foreach($specialization->services as $service)
                <div class="service-card" data-service-id="{{ $service->id }}" data-service-name="{{ $service->name }}" data-service-price="{{ $service->price ?? 0 }}" onclick="toggleService(this)">
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description ?? 'Dịch vụ ' . $service->name . ' tại ' . $specialization->name }}</p>
                    <div class="service-price">{{ number_format($service->price ?? 0, 0, ',', '.') }} VNĐ</div>
                    <div class="service-duration">
                        <i class="fas fa-clock"></i> {{ $service->duration_minutes ?? 30 }} phút
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Selected services summary -->
            <div id="selectedServicesSummary" style="display: none; margin-top: 20px; padding: 20px; background: #e8f5e9; border-radius: 12px; text-align: center;">
                <p style="margin: 0; color: #2e7d32; font-weight: 500;">
                    <i class="fas fa-check-circle"></i> 
                    Đã chọn <span id="selectedCount">0</span> dịch vụ - Tổng: <span id="totalPrice">0</span> VNĐ
                </p>
            </div>
        </div>
    </section>
    @endif

    <!-- Doctors Section -->
    <section class="doctors-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-user-md"></i> Đội ngũ bác sĩ</h2>
                <p>Chọn bác sĩ để đặt lịch khám tại {{ $specialization->name }}</p>
            </div>

            <div class="doctors-grid" id="doctorsGrid">
                <!-- Doctors will be loaded here -->
            </div>

            <div class="no-doctors" id="noDoctors" style="display: none;">
                <i class="fas fa-user-md"></i>
                <h3>Chưa có bác sĩ</h3>
                <p>Hiện tại chưa có bác sĩ nào trong chuyên khoa này. Vui lòng liên hệ để được hỗ trợ.</p>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination"></div>
        </div>
    </section>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="/chuyen-khoa" class="action-btn secondary">
            <i class="fas fa-arrow-left"></i> Xem tất cả chuyên khoa
        </a>
    </div>

    <!-- Floating Book Button -->
    <a href="#" class="floating-book-btn" id="floatingBookBtn">
        <i class="fas fa-calendar-check"></i>
        <span>Đặt lịch khám ngay</span>
    </a>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; {{ date('Y') }} Bệnh viện Nam Sài Gòn. All rights reserved.</p>
            <p style="margin-top: 10px;">
                <a href="/">Trang chủ</a> | 
                <a href="/chuyen-khoa">Chuyên khoa</a> | 
                <a href="/dat-lich">Đặt lịch</a> | 
                <a href="/tim-bac-si">Tìm bác sĩ</a>
            </p>
        </div>
    </footer>

    <script>
        const API_BASE = '/api';
        const SPECIALIZATION_ID = {{ $specialization->id }};
        let allDoctors = [];
        let currentPage = 1;
        const perPage = 8;
        let selectedDoctor = null;
        let selectedServices = []; // Array of selected service objects
        let userData = null;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            checkLoginStatus();
            loadDoctors();
            updateBookingButton();
        });

        // Toggle service selection
        function toggleService(card) {
            const serviceId = parseInt(card.dataset.serviceId);
            const serviceName = card.dataset.serviceName;
            const servicePrice = parseInt(card.dataset.servicePrice) || 0;
            
            const existingIndex = selectedServices.findIndex(s => s.id === serviceId);
            
            if (existingIndex >= 0) {
                // Remove from selection
                selectedServices.splice(existingIndex, 1);
                card.classList.remove('selected');
            } else {
                // Add to selection
                selectedServices.push({
                    id: serviceId,
                    name: serviceName,
                    price: servicePrice
                });
                card.classList.add('selected');
            }
            
            updateSelectedServicesSummary();
            updateBookingButton();
        }

        // Update selected services summary
        function updateSelectedServicesSummary() {
            const summary = document.getElementById('selectedServicesSummary');
            const countEl = document.getElementById('selectedCount');
            const priceEl = document.getElementById('totalPrice');
            
            if (!summary) return;
            
            if (selectedServices.length > 0) {
                summary.style.display = 'block';
                countEl.textContent = selectedServices.length;
                const total = selectedServices.reduce((sum, s) => sum + s.price, 0);
                priceEl.textContent = total.toLocaleString('vi-VN');
            } else {
                summary.style.display = 'none';
            }
        }

        // Update booking button URL with selected services
        function updateBookingButton() {
            const btn = document.getElementById('floatingBookBtn');
            if (!btn) return;
            
            // Show button if doctor OR services selected
            if (selectedDoctor || selectedServices.length > 0) {
                btn.classList.add('show');
                
                // Build URL with params
                let params = new URLSearchParams();
                params.set('specialization', SPECIALIZATION_ID);
                
                if (selectedDoctor) {
                    params.set('doctor', selectedDoctor.id);
                }
                
                if (selectedServices.length > 0) {
                    params.set('services', selectedServices.map(s => s.id).join(','));
                }
                
                btn.href = `/dat-lich/bieu-mau?${params.toString()}`;
                
                // Update button text
                let text = 'Đặt lịch khám ngay';
                if (selectedServices.length > 0) {
                    text += ` (${selectedServices.length} dịch vụ)`;
                }
                btn.querySelector('span').textContent = text;
            } else {
                btn.classList.remove('show');
            }
        }

        // Check login status
        function checkLoginStatus() {
            const token = localStorage.getItem('access_token');
            // Try both user_profile and user_data keys
            const userDataStr = localStorage.getItem('user_profile') || localStorage.getItem('user_data') || localStorage.getItem('userData');
            
            if (token) {
                document.getElementById('loginBtnWrap').classList.add('hidden');
                document.getElementById('userAvatarSection').classList.add('logged-in');
                
                if (userDataStr) {
                    try {
                        userData = JSON.parse(userDataStr);
                        document.getElementById('headerUserName').textContent = userData.full_name || 'User';
                        if (userData.avatar_url) {
                            document.getElementById('headerAvatar').src = userData.avatar_url;
                        }
                    } catch(e) {
                        console.error('Error parsing user data:', e);
                    }
                }
            }
        }

        function toggleUserDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }

        function handleLogout() {
            localStorage.removeItem('access_token');
            localStorage.removeItem('user_data');
            window.location.reload();
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const avatarBtn = document.querySelector('.user-avatar-btn');
            if (dropdown && avatarBtn && !dropdown.contains(e.target) && !avatarBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Load doctors with pagination
        async function loadDoctors() {
            const grid = document.getElementById('doctorsGrid');
            const noDoctors = document.getElementById('noDoctors');
            
            try {
                const response = await fetch(`${API_BASE}/public/doctors?specialization_id=${SPECIALIZATION_ID}&per_page=100`);
                const result = await response.json();
                allDoctors = result.data || [];
                
                // Update doctor count
                document.getElementById('doctorCount').textContent = allDoctors.length;
                
                if (allDoctors.length === 0) {
                    grid.style.display = 'none';
                    noDoctors.style.display = 'block';
                    document.getElementById('pagination').style.display = 'none';
                    return;
                }
                
                grid.style.display = 'grid';
                noDoctors.style.display = 'none';
                renderDoctors();
                renderPagination();
                
            } catch(error) {
                console.error('Error loading doctors:', error);
                grid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">Không thể tải danh sách bác sĩ</p>';
            }
        }

        // Render doctors for current page
        function renderDoctors() {
            const grid = document.getElementById('doctorsGrid');
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const pageDoctors = allDoctors.slice(start, end);
            
            grid.innerHTML = '';
            
            pageDoctors.forEach(doctor => {
                const card = document.createElement('div');
                card.className = 'doctor-card';
                card.dataset.doctorId = doctor.id;
                
                if (selectedDoctor && selectedDoctor.id === doctor.id) {
                    card.classList.add('selected');
                }
                
                const avatarUrl = doctor.avatar_url || '/frontend/img/default-avatar.png';
                const displayName = doctor.degree ? `${doctor.degree} ${doctor.full_name}` : doctor.full_name;
                const rating = doctor.rating_avg ? parseFloat(doctor.rating_avg).toFixed(1) : '0.0';
                const experience = doctor.experience || 0;
                
                card.innerHTML = `
                    ${doctor.avatar_url ? 
                        `<img src="${avatarUrl}" alt="${doctor.full_name}" class="doctor-avatar" onerror="this.parentElement.innerHTML = this.parentElement.innerHTML.replace(this.outerHTML, '<div class=\\'doctor-avatar-placeholder\\'>${doctor.full_name.charAt(0)}</div>')">` :
                        `<div class="doctor-avatar-placeholder">${doctor.full_name.charAt(0)}</div>`
                    }
                    <div class="doctor-info">
                        <div class="doctor-name">${displayName}</div>
                        <div class="doctor-degree">${doctor.degree || 'Bác sĩ chuyên khoa'}</div>
                        <div class="doctor-meta">
                            <span><i class="fas fa-star"></i> ${rating}</span>
                            <span><i class="fas fa-briefcase"></i> ${experience} năm KN</span>
                        </div>
                        <a href="/bac-si/${doctor.id}" class="btn-view-detail" onclick="event.stopPropagation();">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                `;
                
                card.addEventListener('click', () => selectDoctor(doctor));
                grid.appendChild(card);
            });
        }

        // Render pagination
        function renderPagination() {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(allDoctors.length / perPage);
            
            if (totalPages <= 1) {
                pagination.style.display = 'none';
                return;
            }
            
            pagination.style.display = 'flex';
            pagination.innerHTML = '';
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderDoctors();
                    renderPagination();
                }
            };
            pagination.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (totalPages > 7) {
                    // Show limited pages for large datasets
                    if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                        const pageBtn = document.createElement('button');
                        pageBtn.textContent = i;
                        pageBtn.className = i === currentPage ? 'active' : '';
                        pageBtn.onclick = () => {
                            currentPage = i;
                            renderDoctors();
                            renderPagination();
                        };
                        pagination.appendChild(pageBtn);
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        const dots = document.createElement('span');
                        dots.textContent = '...';
                        dots.style.padding = '10px';
                        pagination.appendChild(dots);
                    }
                } else {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = i === currentPage ? 'active' : '';
                    pageBtn.onclick = () => {
                        currentPage = i;
                        renderDoctors();
                        renderPagination();
                    };
                    pagination.appendChild(pageBtn);
                }
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderDoctors();
                    renderPagination();
                }
            };
            pagination.appendChild(nextBtn);
        }

        // Select doctor
        function selectDoctor(doctor) {
            // Toggle selection
            if (selectedDoctor && selectedDoctor.id === doctor.id) {
                selectedDoctor = null;
            } else {
                selectedDoctor = doctor;
            }
            
            // Update UI
            document.querySelectorAll('.doctor-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            if (selectedDoctor) {
                const selectedCard = document.querySelector(`.doctor-card[data-doctor-id="${selectedDoctor.id}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }
            }
            
            // Update booking button
            updateBookingButton();
        }
    </script>
</body>
</html>
