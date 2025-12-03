{{-- 
    Header Partial - DoctorHub
    Include này vào tất cả các trang để có header thống nhất
    Yêu cầu: 
    - Boxicons CSS
    - Font Awesome CSS
    - auth.js
--}}

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<div class="header-wrap">
    <nav class="header-nav">
        <!-- Logo Section -->
        <div class="logo-section">
            <a href="/" title="Về trang chủ">
                <img src="{{ asset('frontend/img/logomau.jpg') }}" alt="DoctorHub Logo" class="logo-img" />
            </a>
        </div>

        <!-- Search Box with Suggestions -->
        <div class="search-box" id="searchContainer">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="globalSearchInput" placeholder="Tìm bác sĩ, chuyên khoa, dịch vụ..." autocomplete="off" />
                <button type="button" class="search-clear-btn" id="searchClearBtn" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- Search Suggestions Dropdown -->
            <div class="search-suggestions" id="searchSuggestions">
                <div class="suggestions-loading" id="suggestionsLoading">
                    <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
                </div>
                <div class="suggestions-content" id="suggestionsContent"></div>
            </div>
        </div>
        
        <!-- Main Menu -->
        <ul id="menu-menu-main" class="menu-list">
            <li class="menu-item">
                <a class="menu-link" href="/" title="Trang Chủ">Trang chủ</a>
            </li>

            <li class="menu-item">
                <a class="menu-link" href="/tim-bac-si" title="Đội Ngũ Bác Sĩ">Đội ngũ bác sĩ</a>
            </li>

            <li class="menu-item menu-item-has-children menu-chuyen-khoa">
                <a class="menu-link" href="/chuyen-khoa" title="Chuyên Khoa">Chuyên Khoa</a>
                <i class="bx bxs-chevron-down"></i>
                <div class="menu-mega" id="menuSpecializations">
                    <ul id="menuSpecCol1"></ul>
                    <ul id="menuSpecCol2"></ul>
                </div>
            </li>

            <li class="menu-item menu-item-has-children menu-dropdown">
                <a class="menu-link" href="/bang-dieu-khien#services-section" title="Dịch Vụ">Dịch Vụ</a>
                <i class="bx bxs-chevron-down"></i>
                <ul class="child" id="menuServices">
                    <li><a class="menu-link" href="/bang-dieu-khien#services-section">Xem tất cả dịch vụ</a></li>
                </ul>
            </li>

            <li class="menu-item menu-item-has-children menu-dropdown">
                <a class="menu-link" href="#" title="Hướng Dẫn">Hướng Dẫn</a>
                <i class="bx bxs-chevron-down"></i>
                <ul class="child">
                    <li><a class="menu-link" href="/huong-dan/bang-gia-tien-giuong">Bảng Giá Tiền Giường</a></li>
                    <li><a class="menu-link" href="/huong-dan/bao-hiem-y-te">Bảo Hiểm Y Tế</a></li>
                    <li><a class="menu-link" href="/huong-dan/quyen-va-nghia-vu">Quyền và Nghĩa Vụ Của Người Bệnh</a></li>
                    <li><a class="menu-link" href="/lien-he">Liên Hệ</a></li>
                </ul>
            </li>
        </ul>

        <!-- Right Section -->
        <div class="header-right">
            <!-- Đặt lịch ngay Button -->
            <a href="/dat-lich/bieu-mau" class="btn-booking-primary" id="bookingBtn">
                <i class="fas fa-calendar-plus"></i>
                <span>Đặt lịch ngay</span>
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <!-- Login Button (shown when not logged in) -->
            <a href="/dang-nhap" class="btn-header-outline" id="loginBtn" style="display:none;">Tài khoản</a>

            <!-- Profile Button with Avatar (shown when logged in) -->
            <a href="/ho-so" class="btn-profile-header" id="profileBtn" style="display:none;">
                <img id="profileAvatar" src="{{ asset('frontend/img/logocanhan.jpg') }}" alt="Avatar" class="profile-avatar-small">
                <span id="profileName">Hồ sơ</span>
            </a>

            <!-- User Menu (shown when logged in) -->
            <div class="user-menu" id="userMenu" style="display:none;">
                <button class="user-button">
                    <i class="bx bxs-user-circle" id="userIcon"></i>
                    <img id="userAvatar" src="" alt="Avatar" style="display:none; width:32px; height:32px; border-radius:50%; object-fit:cover;">
                    <span id="userName">Người dùng</span>
                </button>
                <div class="user-dropdown">
                    <div class="user-dropdown-header">
                        <div class="user-name" id="userNameFull">Người dùng</div>
                        <div class="user-role" id="userRole">Bệnh nhân</div>
                    </div>
                    <a href="/ho-so" class="user-dropdown-item" id="profileLink">
                        <i class="bx bxs-user"></i>
                        <span>Trang cá nhân</span>
                    </a>
                    <div class="user-dropdown-divider"></div>
                    <a href="#" class="user-dropdown-item logout" id="logoutBtn">
                        <i class="bx bx-log-out"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</div>

<!-- Header CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/header.css') }}">

<style>
/* Additional Header Styles */
.search-box {
    flex: 0 0 320px;
    margin-right: 30px;
    position: relative;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-box .search-icon {
    position: absolute;
    left: 14px;
    color: #999;
    font-size: 14px;
    pointer-events: none;
}

.search-box input {
    width: 100%;
    padding: 10px 35px 10px 40px;
    border: 2px solid #e0e0e0;
    border-radius: 25px;
    font-size: 14px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

.search-box input:focus {
    outline: none;
    border-color: #3498db;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.15);
}

.search-box input::placeholder {
    color: #aaa;
}

.search-clear-btn {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    padding: 5px;
    font-size: 12px;
    transition: color 0.3s;
}

.search-clear-btn:hover {
    color: #e74c3c;
}

/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    max-height: 480px;
    overflow-y: auto;
    z-index: 1001;
    display: none;
    border: 1px solid #e0e0e0;
}

.search-suggestions.active {
    display: block;
    animation: slideDown 0.2s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.suggestions-loading {
    padding: 20px;
    text-align: center;
    color: #666;
    display: none;
}

.suggestions-loading.active {
    display: block;
}

.suggestions-content {
    padding: 8px 0;
}

.suggestion-category {
    padding: 8px 16px 4px;
    font-size: 11px;
    font-weight: 600;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.suggestion-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    cursor: pointer;
    transition: background-color 0.2s;
    gap: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover, .suggestion-item.active {
    background-color: #f0f8ff;
}

.suggestion-item .item-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}

.suggestion-item .item-icon.doctor {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: #fff;
}

.suggestion-item .item-icon.doctor img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
}

.suggestion-item .item-icon.specialization {
    background: linear-gradient(135deg, #27ae60, #1e8449);
    color: #fff;
}

.suggestion-item .item-icon.service {
    background: linear-gradient(135deg, #9b59b6, #8e44ad);
    color: #fff;
}

.suggestion-item .item-info {
    flex: 1;
    min-width: 0;
}

.suggestion-item .item-name {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.suggestion-item .item-name .highlight {
    background-color: #fff3cd;
    padding: 0 2px;
    border-radius: 2px;
}

.suggestion-item .item-meta {
    font-size: 12px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.suggestion-item .item-badge {
    background: #e8f4fd;
    color: #3498db;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 500;
}

.suggestion-item .item-price {
    color: #e74c3c;
    font-weight: 600;
    font-size: 13px;
}

.suggestion-item .item-rating {
    color: #f39c12;
    font-size: 12px;
}

.no-results {
    padding: 30px 20px;
    text-align: center;
    color: #666;
}

.no-results i {
    font-size: 40px;
    color: #ddd;
    margin-bottom: 10px;
    display: block;
}

.quick-actions {
    display: flex;
    gap: 8px;
    padding: 12px 16px;
    border-top: 1px solid #eee;
    background: #f8f9fa;
}

.quick-action-btn {
    flex: 1;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background: #fff;
    color: #666;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}

.quick-action-btn:hover {
    border-color: #3498db;
    color: #3498db;
    background: #f0f8ff;
}

.recent-searches {
    padding: 12px 16px;
}

.recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.recent-header span {
    font-size: 12px;
    color: #999;
    font-weight: 500;
}

.clear-recent {
    font-size: 11px;
    color: #e74c3c;
    cursor: pointer;
    background: none;
    border: none;
}

.recent-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    cursor: pointer;
    color: #666;
    font-size: 13px;
    gap: 10px;
}

.recent-item:hover {
    color: #3498db;
}

/* Profile Button Styles */
.btn-profile-header {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px 4px 4px;
    background: #fff;
    color: #333;
    border: 2px solid #667eea;
    border-radius: 30px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.btn-profile-header:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    border-color: transparent;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.35);
}

.btn-profile-header:hover .profile-avatar-small {
    border-color: rgba(255, 255, 255, 0.9);
}

.profile-avatar-small {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #667eea;
    background: #f0f0f0;
}

@media (max-width: 768px) {
    .btn-profile-header span {
        display: none;
    }
    .btn-profile-header {
        padding: 4px;
    }
}

/* Responsive */
@media (max-width: 1024px) {
    .search-box {
        flex: 0 0 250px;
    }
}

@media (max-width: 768px) {
    .search-box {
        display: none;
    }
    
    .btn-booking-primary span {
        display: none;
    }
    
    .btn-booking-primary {
        padding: 10px 12px;
        margin-right: 10px;
    }
}
</style>

<!-- Header JavaScript -->
<script>
(function() {
    // ========================================
    // Search Suggestions
    // ========================================
    let searchTimeout = null;
    let cachedResults = {};
    let recentSearches = JSON.parse(localStorage.getItem('recentSearches') || '[]');
    let currentFocus = -1;
    
    const searchInput = document.getElementById('globalSearchInput');
    const searchContainer = document.getElementById('searchContainer');
    const searchSuggestions = document.getElementById('searchSuggestions');
    const suggestionsContent = document.getElementById('suggestionsContent');
    const suggestionsLoading = document.getElementById('suggestionsLoading');
    const searchClearBtn = document.getElementById('searchClearBtn');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            searchClearBtn.style.display = query ? 'block' : 'none';
            
            if (query.length < 2) {
                if (query.length === 0) showRecentSearches();
                else hideSuggestions();
                return;
            }
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => performSearch(query), 300);
        });
        
        searchClearBtn?.addEventListener('click', function() {
            searchInput.value = '';
            searchClearBtn.style.display = 'none';
            showRecentSearches();
            searchInput.focus();
        });
        
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length < 2) showRecentSearches();
            else if (suggestionsContent.innerHTML) showSuggestions();
        });
        
        searchInput.addEventListener('keydown', function(e) {
            const items = suggestionsContent.querySelectorAll('.suggestion-item, .recent-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentFocus++;
                if (currentFocus >= items.length) currentFocus = 0;
                setActiveItem(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentFocus--;
                if (currentFocus < 0) currentFocus = items.length - 1;
                setActiveItem(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentFocus > -1 && items[currentFocus]) {
                    items[currentFocus].click();
                } else if (this.value.trim()) {
                    saveRecentSearch(this.value.trim());
                    window.location.href = '/tim-bac-si?search=' + encodeURIComponent(this.value.trim());
                }
            } else if (e.key === 'Escape') {
                hideSuggestions();
                this.blur();
            }
        });
    }
    
    document.addEventListener('click', function(e) {
        if (searchContainer && !searchContainer.contains(e.target)) {
            hideSuggestions();
        }
    });
    
    function setActiveItem(items) {
        items.forEach(item => item.classList.remove('active'));
        if (items[currentFocus]) {
            items[currentFocus].classList.add('active');
            items[currentFocus].scrollIntoView({ block: 'nearest' });
        }
    }
    
    async function performSearch(query) {
        if (cachedResults[query]) {
            renderResults(cachedResults[query], query);
            return;
        }
        
        showLoading();
        
        try {
            const [doctorsRes, specsRes, servicesRes] = await Promise.all([
                fetch(`/api/public/doctors?search=${encodeURIComponent(query)}&per_page=5`).then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/api/public/specializations').then(r => r.json()).catch(() => ({ data: [] })),
                fetch('/api/public/services').then(r => r.json()).catch(() => ({ data: [] }))
            ]);
            
            const queryLower = query.toLowerCase();
            const specializations = (specsRes.data || []).filter(s => 
                s.name.toLowerCase().includes(queryLower)
            ).slice(0, 4);
            
            const services = (servicesRes.data || []).filter(s => 
                s.name.toLowerCase().includes(queryLower)
            ).slice(0, 4);
            
            const results = {
                doctors: doctorsRes.data || [],
                specializations: specializations,
                services: services
            };
            
            cachedResults[query] = results;
            renderResults(results, query);
        } catch (error) {
            hideLoading();
            suggestionsContent.innerHTML = `<div class="no-results"><i class="fas fa-exclamation-circle"></i><p>Lỗi khi tìm kiếm</p></div>`;
            showSuggestions();
        }
    }
    
    function renderResults(results, query) {
        hideLoading();
        currentFocus = -1;
        
        const { doctors, specializations, services } = results;
        const hasResults = doctors.length > 0 || specializations.length > 0 || services.length > 0;
        
        if (!hasResults) {
            suggestionsContent.innerHTML = `
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>Không tìm thấy kết quả cho "<strong>${escapeHtml(query)}</strong>"</p>
                </div>
                <div class="quick-actions">
                    <a href="/tim-bac-si" class="quick-action-btn"><i class="fas fa-user-md"></i> Xem tất cả bác sĩ</a>
                    <a href="/chuyen-khoa" class="quick-action-btn"><i class="fas fa-stethoscope"></i> Xem chuyên khoa</a>
                </div>
            `;
            showSuggestions();
            return;
        }
        
        let html = '';
        
        if (doctors.length > 0) {
            html += '<div class="suggestion-category"><i class="fas fa-user-md"></i> Bác sĩ</div>';
            doctors.forEach(doctor => {
                const displayName = doctor.degree ? `${doctor.degree} ${doctor.full_name}` : doctor.full_name;
                const rating = doctor.rating_avg ? doctor.rating_avg.toFixed(1) : '0';
                html += `
                    <div class="suggestion-item" data-type="doctor" data-id="${doctor.id}" data-name="${escapeHtml(displayName)}">
                        <div class="item-icon doctor">
                            ${doctor.avatar_url 
                                ? `<img src="${doctor.avatar_url}" alt="" onerror="this.parentElement.innerHTML='<i class=\\'fas fa-user-md\\'></i>'">` 
                                : '<i class="fas fa-user-md"></i>'}
                        </div>
                        <div class="item-info">
                            <div class="item-name">${highlightMatch(displayName, query)}</div>
                            <div class="item-meta">
                                <span class="item-badge">${doctor.specialization?.name || 'Đa khoa'}</span>
                                <span class="item-rating"><i class="fas fa-star"></i> ${rating}</span>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (specializations.length > 0) {
            html += '<div class="suggestion-category"><i class="fas fa-stethoscope"></i> Chuyên khoa</div>';
            specializations.forEach(spec => {
                html += `
                    <div class="suggestion-item" data-type="specialization" data-id="${spec.id}" data-name="${escapeHtml(spec.name)}">
                        <div class="item-icon specialization"><i class="fas fa-stethoscope"></i></div>
                        <div class="item-info">
                            <div class="item-name">${highlightMatch(spec.name, query)}</div>
                            <div class="item-meta"><span>${spec.doctor_count || 0} bác sĩ</span></div>
                        </div>
                    </div>
                `;
            });
        }
        
        if (services.length > 0) {
            html += '<div class="suggestion-category"><i class="fas fa-concierge-bell"></i> Dịch vụ</div>';
            services.forEach(service => {
                const price = service.price ? service.price.toLocaleString('vi-VN') + 'đ' : 'Liên hệ';
                html += `
                    <div class="suggestion-item" data-type="service" data-id="${service.id}" data-name="${escapeHtml(service.name)}">
                        <div class="item-icon service"><i class="fas fa-concierge-bell"></i></div>
                        <div class="item-info">
                            <div class="item-name">${highlightMatch(service.name, query)}</div>
                            <div class="item-meta"><span class="item-price">${price}</span></div>
                        </div>
                    </div>
                `;
            });
        }
        
        html += `<div class="quick-actions"><a href="/tim-bac-si?search=${encodeURIComponent(query)}" class="quick-action-btn"><i class="fas fa-search"></i> Xem tất cả kết quả</a></div>`;
        
        suggestionsContent.innerHTML = html;
        
        suggestionsContent.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                const type = this.dataset.type;
                const id = this.dataset.id;
                saveRecentSearch(this.dataset.name);
                
                if (type === 'doctor') window.location.href = `/bac-si/${id}`;
                else if (type === 'specialization') window.location.href = `/chuyen-khoa/${id}`;
                else if (type === 'service') window.location.href = `/dich-vu/chi-tiet/${id}`;
            });
        });
        
        showSuggestions();
    }
    
    function showRecentSearches() {
        if (recentSearches.length === 0) {
            suggestionsContent.innerHTML = `
                <div class="quick-actions" style="border-top:none;">
                    <a href="/tim-bac-si" class="quick-action-btn"><i class="fas fa-user-md"></i> Tìm bác sĩ</a>
                    <a href="/chuyen-khoa" class="quick-action-btn"><i class="fas fa-stethoscope"></i> Chuyên khoa</a>
                    <a href="/dat-lich/bieu-mau" class="quick-action-btn"><i class="fas fa-calendar-plus"></i> Đặt lịch</a>
                </div>
            `;
        } else {
            let html = `<div class="recent-searches"><div class="recent-header"><span><i class="fas fa-history"></i> Tìm kiếm gần đây</span><button class="clear-recent" onclick="clearRecentSearches()">Xóa</button></div>`;
            recentSearches.slice(0, 5).forEach(search => {
                html += `<div class="recent-item" data-query="${escapeHtml(search)}"><i class="fas fa-history"></i><span>${escapeHtml(search)}</span></div>`;
            });
            html += `</div><div class="quick-actions"><a href="/tim-bac-si" class="quick-action-btn"><i class="fas fa-user-md"></i> Tìm bác sĩ</a><a href="/chuyen-khoa" class="quick-action-btn"><i class="fas fa-stethoscope"></i> Chuyên khoa</a></div>`;
            
            suggestionsContent.innerHTML = html;
            
            suggestionsContent.querySelectorAll('.recent-item').forEach(item => {
                item.addEventListener('click', function() {
                    searchInput.value = this.dataset.query;
                    searchClearBtn.style.display = 'block';
                    performSearch(this.dataset.query);
                });
            });
        }
        showSuggestions();
    }
    
    function saveRecentSearch(query) {
        recentSearches = recentSearches.filter(s => s.toLowerCase() !== query.toLowerCase());
        recentSearches.unshift(query);
        recentSearches = recentSearches.slice(0, 10);
        localStorage.setItem('recentSearches', JSON.stringify(recentSearches));
    }
    
    window.clearRecentSearches = function() {
        recentSearches = [];
        localStorage.removeItem('recentSearches');
        showRecentSearches();
    };
    
    function showSuggestions() { searchSuggestions?.classList.add('active'); }
    function hideSuggestions() { searchSuggestions?.classList.remove('active'); currentFocus = -1; }
    function showLoading() { suggestionsLoading?.classList.add('active'); suggestionsContent.innerHTML = ''; showSuggestions(); }
    function hideLoading() { suggestionsLoading?.classList.remove('active'); }
    
    function highlightMatch(text, query) {
        if (!query) return escapeHtml(text);
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return escapeHtml(text).replace(regex, '<span class="highlight">$1</span>');
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // ========================================
    // Login Status Check
    // ========================================
    const loginBtn = document.getElementById('loginBtn');
    const profileBtn = document.getElementById('profileBtn');
    const profileAvatar = document.getElementById('profileAvatar');
    const profileName = document.getElementById('profileName');
    const userMenu = document.getElementById('userMenu');
    const userNameEl = document.getElementById('userName');
    const userNameFullEl = document.getElementById('userNameFull');
    const userAvatarEl = document.getElementById('userAvatar');
    const userIconEl = document.getElementById('userIcon');
    const userRoleEl = document.getElementById('userRole');
    const profileLink = document.getElementById('profileLink');
    const logoutBtn = document.getElementById('logoutBtn');
    
    async function checkLoginStatus() {
        const accessToken = localStorage.getItem('access_token') || sessionStorage.getItem('access_token');
        const cachedProfile = localStorage.getItem('user_profile');
        
        // If we have cached profile, show it immediately
        if (cachedProfile) {
            try {
                const profile = JSON.parse(cachedProfile);
                if (profile && profile.id) {
                    showProfileButton(profile);
                    
                    // If no token, still show profile from cache (user might have logged in elsewhere)
                    if (!accessToken) {
                        return;
                    }
                }
            } catch (e) {}
        }
        
        // No token and no cached profile - show login button
        if (!accessToken && !cachedProfile) {
            showLoginButton();
            return;
        }
        
        // If we have token, verify with API
        if (accessToken) {
            try {
                const response = await fetch('/api/profile/me', {
                    headers: {
                        'Authorization': `Bearer ${accessToken}`,
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const result = await response.json();
                    const profile = result.data || result;
                    
                    if (profile && profile.id) {
                        showProfileButton(profile);
                        return;
                    }
                }
                
                // Token invalid but we have cached profile - keep showing it
                if (!cachedProfile) {
                    clearTokens();
                    showLoginButton();
                }
            } catch (error) {
                // API error - keep cached profile if available
                if (!cachedProfile) {
                    showLoginButton();
                }
            }
        }
    }
    
    function showLoginButton() {
        if (loginBtn) loginBtn.style.display = 'inline-block';
        if (profileBtn) profileBtn.style.display = 'none';
        if (userMenu) userMenu.style.display = 'none';
    }
    
    function showProfileButton(profile) {
        if (loginBtn) loginBtn.style.display = 'none';
        if (profileBtn) profileBtn.style.display = 'flex';
        if (userMenu) userMenu.style.display = 'none';
        
        const displayName = profile.full_name || profile.email?.split('@')[0] || 'Hồ sơ';
        const shortName = displayName.split(' ').pop(); // Get last name only
        
        // Update profile button
        if (profileName) profileName.textContent = shortName;
        if (profileAvatar && profile.avatar_url) {
            profileAvatar.src = profile.avatar_url;
            profileAvatar.onerror = function() {
                this.src = '/frontend/img/logocanhan.jpg';
            };
        }
        
        // Set correct profile link based on user type
        const roleMap = {
            'USER': '/ho-so',
            'DOCTOR': '/bac-si/ho-so',
            'ADMIN': '/quan-tri'
        };
        if (profileBtn) profileBtn.href = roleMap[profile.type] || '/ho-so';
        
        // Also update user menu (for dropdown if needed)
        if (userNameEl) userNameEl.textContent = displayName;
        if (userNameFullEl) userNameFullEl.textContent = displayName;
        
        if (profile.avatar_url && userAvatarEl) {
            userAvatarEl.src = profile.avatar_url;
            userAvatarEl.style.display = 'block';
            userAvatarEl.onerror = function() {
                this.style.display = 'none';
                if (userIconEl) userIconEl.style.display = 'block';
            };
            if (userIconEl) userIconEl.style.display = 'none';
        }
        
        const roleNames = {
            'USER': 'Bệnh nhân',
            'DOCTOR': 'Bác sĩ',
            'ADMIN': 'Quản trị viên'
        };
        if (userRoleEl) userRoleEl.textContent = roleNames[profile.type] || 'Người dùng';
        if (profileLink) profileLink.href = roleMap[profile.type] || '/ho-so';
        
        localStorage.setItem('user_profile', JSON.stringify(profile));
    }
    
    // Legacy function for compatibility
    function showUserMenu(profile) {
        showProfileButton(profile);
    }
    
    function clearTokens() {
        ['access_token', 'refresh_token', 'session_id', 'user_profile'].forEach(key => {
            localStorage.removeItem(key);
            sessionStorage.removeItem(key);
        });
    }
    
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
                const accessToken = localStorage.getItem('access_token') || sessionStorage.getItem('access_token');
                
                try {
                    if (accessToken) {
                        await fetch('/api/auth/logout', {
                            method: 'POST',
                            headers: { 'Authorization': `Bearer ${accessToken}`, 'Accept': 'application/json' }
                        });
                    }
                } catch (e) {}
                
                clearTokens();
                window.location.href = '/dang-nhap';
            }
        });
    }
    
    // User menu toggle
    const userButton = userMenu?.querySelector('.user-button');
    const userDropdown = userMenu?.querySelector('.user-dropdown');
    
    if (userButton && userDropdown) {
        userButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        document.addEventListener('click', function(e) {
            if (userMenu && !userMenu.contains(e.target)) {
                userDropdown.classList.remove('active');
            }
        });
    }
    
    // ========================================
    // Load Menu Data
    // ========================================
    async function loadSpecializationsToMenu() {
        try {
            const response = await fetch('/api/public/specializations');
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
                const specs = result.data;
                const half = Math.ceil(specs.length / 2);
                const col1 = document.getElementById('menuSpecCol1');
                const col2 = document.getElementById('menuSpecCol2');
                
                if (col1 && col2) {
                    col1.innerHTML = specs.slice(0, half).map(spec => 
                        `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`
                    ).join('');
                    col2.innerHTML = specs.slice(half).map(spec => 
                        `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`
                    ).join('');
                }
            }
        } catch (error) {}
    }
    
    async function loadServicesToMenu() {
        try {
            const response = await fetch('/api/public/services');
            const result = await response.json();
            
            if (result.success && result.data && result.data.length > 0) {
                const menuServices = document.getElementById('menuServices');
                if (menuServices) {
                    menuServices.innerHTML = result.data.slice(0, 6).map(service => 
                        `<li><a class="menu-link" href="/dich-vu/chi-tiet/${service.id}">${service.name}</a></li>`
                    ).join('') + '<li><a class="menu-link" href="/bang-dieu-khien#services-section"><strong>Xem tất cả →</strong></a></li>';
                }
            }
        } catch (error) {}
    }
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        checkLoginStatus();
        loadSpecializationsToMenu();
        loadServicesToMenu();
    });
    
    if (document.readyState !== 'loading') {
        checkLoginStatus();
        loadSpecializationsToMenu();
        loadServicesToMenu();
    }
})();
</script>
