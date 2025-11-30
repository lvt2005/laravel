<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Các Chuyên Khoa - Bệnh viện Nam Sài Gòn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fa; color: #333; line-height: 1.6; }
        
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
        .menu-item a:hover, .menu-item.active a {
            background: rgba(255,255,255,0.2);
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
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
        
        /* User Avatar Dropdown */
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
        }
        .user-dropdown.show {
            display: block;
        }
        .user-dropdown a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s;
        }
        .user-dropdown a:hover {
            background: #f5f5f5;
        }
        .user-dropdown a i {
            width: 20px;
            color: #1e5ba8;
        }
        .user-dropdown .logout-btn {
            color: #dc3545;
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
            .menu-list { display: none; }
            .page-header h1 { font-size: 1.8rem; }
        }
    </style>
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
                <li class="menu-item active"><a href="/chuyen-khoa">Chuyên khoa</a></li>
                <li class="menu-item"><a href="/dat-lich">Đặt lịch khám</a></li>
            </ul>
            <div class="header-right">
                <div class="login-btn-wrap" id="loginBtnWrap">
                    <a href="/dang-nhap" class="btn-register">Đăng nhập</a>
                </div>
                <div class="user-avatar-section" id="userAvatarSection">
                    <div class="user-avatar-btn" onclick="toggleUserDropdown()">
                        <img src="/frontend/img/default-avatar.png" alt="Avatar" id="headerAvatar">
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
        </nav>
    </div>

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
            </div>
            <div>
                <h4>Dịch vụ</h4>
                <a href="/dich-vu/tam-soat-ung-thu">Tầm soát ung thư</a>
                <a href="/dich-vu/noi-soi">Nội soi</a>
                <a href="/dich-vu/kham-suc-khoe-dinh-ky">Khám sức khỏe định kỳ</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Bệnh viện Nam Sài Gòn. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const API_BASE = '/api';
        
        // Check login status and update UI
        function checkLoginStatus() {
            const token = localStorage.getItem('access_token');
            const userDataStr = localStorage.getItem('user_data');
            
            if (token && userDataStr) {
                try {
                    const userData = JSON.parse(userDataStr);
                    document.getElementById('loginBtnWrap').classList.add('hidden');
                    document.getElementById('userAvatarSection').classList.add('logged-in');
                    document.getElementById('headerUserName').textContent = userData.full_name || 'User';
                    if (userData.avatar_url) {
                        document.getElementById('headerAvatar').src = userData.avatar_url;
                    }
                } catch(e) {
                    console.error('Error parsing user data:', e);
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
            if (dropdown && !dropdown.contains(e.target) && !avatarBtn.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
        
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
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            checkLoginStatus();
            loadSpecializations();
        });
    </script>
</body>
</html>
