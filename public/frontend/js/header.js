// Header JavaScript - Navigation and User Menu
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const menuList = document.querySelector('.menu-list');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

    if (mobileMenuToggle && menuList) {
        mobileMenuToggle.addEventListener('click', function() {
            menuList.classList.toggle('active');
            mobileMenuOverlay?.classList.toggle('active');
        });
        
        mobileMenuOverlay?.addEventListener('click', function() {
            menuList.classList.remove('active');
            this.classList.remove('active');
        });
    }

    // User dropdown toggle
    const dropdownToggle = document.querySelector('.header-dropdown-toggle');
    const dropdownMenu = document.querySelector('.header-dropdown-menu');

    if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }

    // Check login status and update header
    checkHeaderLoginStatus();
    
    // Load specializations to menu
    loadSpecializationsToHeaderMenu();
    
    // Load services to menu
    loadServicesToHeaderMenu();
});

// Check login status for header
async function checkHeaderLoginStatus() {
    const loginBtn = document.getElementById('loginBtn');
    const userMenu = document.getElementById('userMenu');
    const userNameEl = document.getElementById('userName');
    const userNameFullEl = document.getElementById('userNameFull');
    const userRoleEl = document.getElementById('userRole');
    const userAvatarEl = document.getElementById('userAvatar');
    const userIconEl = document.getElementById('userIcon');
    const profileLink = document.getElementById('profileLink');
    
    if (!loginBtn || !userMenu) return;
    
    const accessToken = localStorage.getItem('access_token');
    
    if (accessToken && window.AuthAPI) {
        try {
            const profile = await AuthAPI.getProfile();
            
            if (profile && profile.id) {
                // User is logged in
                loginBtn.style.display = 'none';
                userMenu.classList.add('active');
                
                const displayName = profile.full_name || profile.email?.split('@')[0] || 'Người dùng';
                if (userNameEl) userNameEl.textContent = displayName;
                if (userNameFullEl) userNameFullEl.textContent = displayName;
                
                // Update avatar
                if (profile.avatar_url && userAvatarEl) {
                    userAvatarEl.src = profile.avatar_url;
                    userAvatarEl.style.display = 'block';
                    if (userIconEl) userIconEl.style.display = 'none';
                }
                
                // Update role and profile link
                const roleMap = {
                    'USER': { name: 'Bệnh nhân', link: '/ho-so' },
                    'DOCTOR': { name: 'Bác sĩ', link: '/bac-si/ho-so' },
                    'ADMIN': { name: 'Quản trị viên', link: '/quan-tri' }
                };
                const roleInfo = roleMap[profile.type] || { name: 'Người dùng', link: '/ho-so' };
                if (userRoleEl) userRoleEl.textContent = roleInfo.name;
                if (profileLink) profileLink.href = roleInfo.link;
                
                localStorage.setItem('user_profile', JSON.stringify(profile));
            } else {
                showHeaderLoginButton();
            }
        } catch (e) {
            console.error('Header auth error:', e);
            showHeaderLoginButton();
        }
    } else {
        showHeaderLoginButton();
    }
}

function showHeaderLoginButton() {
    const loginBtn = document.getElementById('loginBtn');
    const userMenu = document.getElementById('userMenu');
    if (loginBtn) loginBtn.style.display = 'inline-block';
    if (userMenu) userMenu.classList.remove('active');
}

// Logout handler
document.getElementById('logoutBtn')?.addEventListener('click', async function(e) {
    e.preventDefault();
    if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
        try {
            if (window.AuthAPI) await AuthAPI.logout();
        } catch (e) {
            console.error('Logout error:', e);
        }
        // Clear storage
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
        localStorage.removeItem('session_id');
        localStorage.removeItem('user_profile');
        sessionStorage.clear();
        
        window.location.href = '/dang-nhap';
    }
});

// Load specializations to header menu
async function loadSpecializationsToHeaderMenu() {
    const col1 = document.getElementById('menuSpecCol1');
    const col2 = document.getElementById('menuSpecCol2');
    
    if (!col1 || !col2) return;
    
    try {
        const response = await fetch('/api/public/specializations');
        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
            const specializations = result.data;
            const half = Math.ceil(specializations.length / 2);
            const firstHalf = specializations.slice(0, half);
            const secondHalf = specializations.slice(half);
            
            col1.innerHTML = firstHalf.map(spec => 
                `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`
            ).join('');
            
            col2.innerHTML = secondHalf.map(spec => 
                `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`
            ).join('');
        }
    } catch (error) {
        console.error('Error loading specializations to menu:', error);
    }
}

// Load services to header menu
async function loadServicesToHeaderMenu() {
    const menuServices = document.getElementById('menuServices');
    
    if (!menuServices) return;
    
    try {
        const response = await fetch('/api/public/services');
        const result = await response.json();
        
        if (result.success && result.data && result.data.length > 0) {
            const services = result.data.slice(0, 8); // Limit to 8 services
            
            let html = '<li><a class="menu-link" href="/bang-dieu-khien#services-section">Xem tất cả dịch vụ</a></li>';
            html += services.map(service => 
                `<li><a class="menu-link" href="/dich-vu/chi-tiet/${service.id}">${service.name}</a></li>`
            ).join('');
            
            menuServices.innerHTML = html;
        }
    } catch (error) {
        console.error('Error loading services to menu:', error);
    }
}
