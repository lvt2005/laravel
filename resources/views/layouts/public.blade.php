<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>@yield('title', 'Bệnh viện Nam Sài Gòn')</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        .header-wrap {
            background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-nav {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }
        .logo-section a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .logo-section img {
            height: 50px;
            border-radius: 8px;
        }
        .menu-list {
            display: flex;
            list-style: none;
            gap: 5px;
        }
        .menu-item a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }
        .menu-item a:hover {
            background: rgba(255,255,255,0.15);
        }
        .menu-item.active a {
            background: rgba(255,255,255,0.2);
        }
        /* Dropdown Menu */
        .menu-item.has-dropdown {
            position: relative;
        }
        .menu-item.has-dropdown > a {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .menu-item.has-dropdown > a i {
            font-size: 10px;
            transition: transform 0.3s;
        }
        .menu-item.has-dropdown:hover > a i {
            transform: rotate(180deg);
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-width: 250px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s;
            z-index: 1001;
            padding: 10px 0;
        }
        .menu-item.has-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 20px;
            color: #333 !important;
            font-size: 14px;
            background: white !important;
            border-radius: 0;
        }
        .dropdown-menu a:hover {
            background: #f0f7ff !important;
            color: #1e5ba8 !important;
        }
        .dropdown-menu a i {
            margin-right: 10px;
            width: 18px;
            color: #1e5ba8;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
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
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        .page-header {
            background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 30px;
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
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        }
        .card h2 {
            color: #1e5ba8;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        .card p {
            color: #555;
            margin-bottom: 15px;
        }
        .card ul {
            margin-left: 20px;
            color: #555;
        }
        .card li {
            margin-bottom: 8px;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(30, 91, 168, 0.4);
        }
        .btn-secondary {
            display: inline-block;
            background: #f5f7fa;
            color: #1e5ba8;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid #1e5ba8;
        }
        .btn-secondary:hover {
            background: #1e5ba8;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }
        .info-item i {
            font-size: 24px;
            color: #1e5ba8;
            min-width: 30px;
        }
        .info-item h4 {
            color: #1e5ba8;
            margin-bottom: 5px;
        }
        .info-item p {
            margin: 0;
            font-size: 14px;
        }
        .footer {
            background: #1a1a2e;
            color: #aaa;
            padding: 40px 20px;
            margin-top: 50px;
        }
        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        .footer h4 {
            color: white;
            margin-bottom: 15px;
        }
        .footer a {
            color: #aaa;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
        }
        .footer a:hover {
            color: #1e5ba8;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid #333;
        }
        @media (max-width: 768px) {
            .menu-list {
                display: none;
            }
            .page-header h1 {
                font-size: 1.8rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="header-wrap">
        <nav class="header-nav">
            <div class="logo-section">
                <a href="/" title="Về trang chủ">
                    <img src="/frontend/img/logomau.jpg" alt="Logo Nam Sài Gòn">
                </a>
            </div>
            <ul class="menu-list">
                <li class="menu-item"><a href="/">Trang chủ</a></li>
                <li class="menu-item"><a href="/tim-bac-si">Đội ngũ bác sĩ</a></li>
                <li class="menu-item has-dropdown">
                    <a href="/chuyen-khoa">Chuyên khoa <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu" id="specializationDropdown">
                        <a href="/chuyen-khoa"><i class="fas fa-hospital"></i> Tất cả chuyên khoa</a>
                        <!-- Chuyên khoa sẽ được load từ API -->
                    </div>
                </li>
                <li class="menu-item has-dropdown">
                    <a href="#">Hướng dẫn <i class="fas fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="/huong-dan/bang-gia-tien-giuong"><i class="fas fa-bed"></i> Bảng giá tiền giường</a>
                        <a href="/huong-dan/bao-hiem-y-te"><i class="fas fa-shield-alt"></i> Bảo hiểm y tế</a>
                        <a href="/huong-dan/quyen-va-nghia-vu"><i class="fas fa-gavel"></i> Quyền và nghĩa vụ người bệnh</a>
                        <a href="/lien-he"><i class="fas fa-phone-alt"></i> Liên hệ</a>
                    </div>
                </li>
                <li class="menu-item"><a href="/dat-lich">Đặt lịch khám</a></li>
            </ul>
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
                        <a href="javascript:void(0)" class="logout-btn" onclick="handleLogout()"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    @yield('content')

    <footer class="footer">
        <div class="footer-content">
            <div>
                <h4>Bệnh viện Nam Sài Gòn</h4>
                <p>Địa chỉ: 315 Nguyễn Văn Linh, Phường Bình Thuận, Quận 7, TP.HCM</p>
                <p>Hotline: 1900 1234</p>
                <p>Email: contact@benhviennamsaigon.vn</p>
            </div>
            <div>
                <h4>Liên kết nhanh</h4>
                <a href="/dat-lich">Đặt lịch khám</a>
                <a href="/tim-bac-si">Tìm bác sĩ</a>
                <a href="/chuyen-khoa">Chuyên khoa</a>
                <a href="/dang-nhap">Đăng nhập</a>
            </div>
            <div>
                <h4>Hướng dẫn khách hàng</h4>
                <a href="/huong-dan/bang-gia-tien-giuong">Bảng giá tiền giường</a>
                <a href="/huong-dan/bao-hiem-y-te">Bảo hiểm y tế</a>
                <a href="/huong-dan/quyen-va-nghia-vu">Quyền và nghĩa vụ</a>
                <a href="/lien-he">Liên hệ</a>
            </div>
            <div>
                <h4>Chuyên khoa</h4>
                <div id="footerSpecializations">
                    <a href="/chuyen-khoa">Tất cả chuyên khoa</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Bệnh viện Nam Sài Gòn. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
    
    <script>
        // Check login status
        function checkLoginStatus() {
            const token = localStorage.getItem('access_token');
            const userDataStr = localStorage.getItem('user_profile') || localStorage.getItem('user_data') || localStorage.getItem('userData');
            
            if (token) {
                const loginWrap = document.getElementById('loginBtnWrap');
                const avatarSection = document.getElementById('userAvatarSection');
                
                if (loginWrap) loginWrap.classList.add('hidden');
                if (avatarSection) avatarSection.classList.add('logged-in');
                
                if (userDataStr) {
                    try {
                        const userData = JSON.parse(userDataStr);
                        const nameEl = document.getElementById('headerUserName');
                        const avatarEl = document.getElementById('headerAvatar');
                        
                        if (nameEl) nameEl.textContent = userData.full_name || 'User';
                        if (avatarEl && userData.avatar_url) {
                            avatarEl.src = userData.avatar_url;
                        }
                    } catch(e) {
                        console.error('Error parsing user data:', e);
                    }
                }
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) dropdown.classList.toggle('show');
        }

        function handleLogout() {
            localStorage.removeItem('access_token');
            localStorage.removeItem('refresh_token');
            localStorage.removeItem('user_profile');
            localStorage.removeItem('user_data');
            localStorage.removeItem('userData');
            window.location.href = '/dang-nhap';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const avatarBtn = document.querySelector('.user-avatar-btn');
            if (dropdown && avatarBtn && !dropdown.contains(e.target) && !avatarBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', checkLoginStatus);

        // Load specializations from database
        async function loadSpecializations() {
            try {
                const response = await fetch('/api/public/specializations');
                const result = await response.json();
                
                if (result.success && result.data) {
                    const specializations = result.data;
                    
                    // Update header dropdown
                    const headerDropdown = document.getElementById('specializationDropdown');
                    if (headerDropdown && specializations.length > 0) {
                        // Keep the "Tất cả chuyên khoa" link
                        let headerHTML = '<a href="/chuyen-khoa"><i class="fas fa-hospital"></i> Tất cả chuyên khoa</a>';
                        
                        // Add max 8 specializations to header
                        specializations.slice(0, 8).forEach(spec => {
                            const slug = spec.slug || spec.name.toLowerCase()
                                .replace(/đ/g, 'd')
                                .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
                                .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
                                .replace(/[ìíịỉĩ]/g, 'i')
                                .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
                                .replace(/[ùúụủũưừứựửữ]/g, 'u')
                                .replace(/[ỳýỵỷỹ]/g, 'y')
                                .replace(/\s+/g, '-')
                                .replace(/[^a-z0-9-]/g, '');
                            headerHTML += `<a href="/chuyen-khoa/${slug}"><i class="fas fa-stethoscope"></i> ${spec.name}</a>`;
                        });
                        
                        headerDropdown.innerHTML = headerHTML;
                    }
                    
                    // Update footer
                    const footerContainer = document.getElementById('footerSpecializations');
                    if (footerContainer && specializations.length > 0) {
                        let footerHTML = '<a href="/chuyen-khoa">Tất cả chuyên khoa</a>';
                        
                        // Add max 5 specializations to footer
                        specializations.slice(0, 5).forEach(spec => {
                            const slug = spec.slug || spec.name.toLowerCase()
                                .replace(/đ/g, 'd')
                                .replace(/[àáạảãâầấậẩẫăằắặẳẵ]/g, 'a')
                                .replace(/[èéẹẻẽêềếệểễ]/g, 'e')
                                .replace(/[ìíịỉĩ]/g, 'i')
                                .replace(/[òóọỏõôồốộổỗơờớợởỡ]/g, 'o')
                                .replace(/[ùúụủũưừứựửữ]/g, 'u')
                                .replace(/[ỳýỵỷỹ]/g, 'y')
                                .replace(/\s+/g, '-')
                                .replace(/[^a-z0-9-]/g, '');
                            footerHTML += `<a href="/chuyen-khoa/${slug}">${spec.name}</a>`;
                        });
                        
                        footerContainer.innerHTML = footerHTML;
                    }
                }
            } catch (error) {
                console.error('Error loading specializations:', error);
            }
        }

        // Load specializations when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            checkLoginStatus();
            loadSpecializations();
        });
    </script>
</body>
</html>
