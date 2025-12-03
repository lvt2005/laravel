// ============================================
// API MODULE FOR ADMIN PROFILE
// ============================================

const API_BASE = '/api';

// ============================================
// HELPER FUNCTIONS
// ============================================

// Normalize avatar URL - remove localhost prefix if present
function normalizeAvatarUrl(url) {
    if (!url) return null;
    // Remove http://localhost:port or http://127.0.0.1:port prefix
    if (url.includes('localhost') || url.includes('127.0.0.1')) {
        try {
            const parsed = new URL(url);
            return parsed.pathname; // Return just the path
        } catch (e) {
            // If URL parsing fails, try regex
            return url.replace(/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?/, '');
        }
    }
    return url;
}

// ============================================
// TOKEN & AUTH MANAGEMENT
// ============================================

function getAccessToken() {
    return localStorage.getItem('access_token');
}

function getRefreshToken() {
    return localStorage.getItem('refresh_token');
}

function getSessionId() {
    return localStorage.getItem('session_id');
}

function saveAuth(tokens) {
    if (tokens.access_token) localStorage.setItem('access_token', tokens.access_token);
    if (tokens.refresh_token) localStorage.setItem('refresh_token', tokens.refresh_token);
    if (tokens.session_id) localStorage.setItem('session_id', tokens.session_id);
}

function clearAuth() {
    localStorage.removeItem('access_token');
    localStorage.removeItem('refresh_token');
    localStorage.removeItem('session_id');
}

// Refresh access token
async function refreshAccessToken() {
    const refreshToken = getRefreshToken();
    const sessionId = getSessionId();

    if (!refreshToken || !sessionId) {
        console.warn('No refresh token or session id available');
        return false;
    }

    try {
        const response = await fetch(API_BASE + '/auth/refresh', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                refresh_token: refreshToken,
                session_id: sessionId
            })
        });

        if (!response.ok) {
            console.error('Failed to refresh token:', response.status);
            return false;
        }

        const data = await response.json();
        saveAuth(data);
        return true;
    } catch (error) {
        console.error('Error refreshing token:', error);
        return false;
    }
}

// ============================================
// UNIFIED API REQUEST FUNCTION
// ============================================

async function apiRequest(path, method = 'GET', body = null, retryCount = 0) {
    const url = API_BASE + path;
    const headers = {
        'Accept': 'application/json'
    };

    // Get current token
    const token = getAccessToken();

    // Only add Authorization header if token exists and is not null/undefined/'null'
    if (token && token !== 'null' && token !== 'undefined') {
        headers['Authorization'] = `Bearer ${token}`;
    } else {
        console.warn('No valid access token available for request:', path);
    }

    // Store original body for potential retry (before JSON.stringify)
    const originalBody = body;
    const isFormData = body instanceof FormData;

    // Set Content-Type for JSON body (not for FormData)
    if (body && !isFormData) {
        headers['Content-Type'] = 'application/json';
        body = JSON.stringify(body);
    }

    try {
        const response = await fetch(url, {
            method,
            headers,
            body: method !== 'GET' ? body : undefined
        });

        // Handle 401 Unauthorized - try to refresh token once
        if (response.status === 401 && retryCount === 0) {
            const refreshed = await refreshAccessToken();

            if (refreshed) {
                // Retry the request with new token (use original body, not stringified)
                return apiRequest(path, method, originalBody, 1);
            } else {
                console.error('Token refresh failed, redirecting to login...');
                // Optionally redirect to login
                // window.location.href = '../login/login.html';
            }
        }

        return response;
    } catch (error) {
        console.error(`[API Error] ${method} ${path}:`, error);
        throw error;
    }
}

// ============================================
// ADMIN SESSION & PROFILE
// ============================================

// Admin data storage
const adminData = {
    id: null,
    username: '',
    fullName: 'Người quản trị',
    email: '',
    phone: '',
    address: '',
    role: 'Quản trị viên',
    avatar: 'AD',
    avatar_url: null,
    createdDate: '',
    lastLogin: new Date().toLocaleString('vi-VN')
};

// Initialize admin session
async function initAdminSession() {
    const token = getAccessToken();

    if (!token || token === 'null' || token === 'undefined') {
        console.warn('No valid access token found');
        // Redirect to login
        window.location.href = '/dang-nhap';
        return false;
    }

    try {
        const response = await apiRequest('/profile/me');

        if (response.ok) {
            const data = await response.json();

            // Check user type - redirect if not ADMIN
            if (data.type !== 'ADMIN') {
                if (data.type === 'DOCTOR') {
                    window.location.href = '/bac-si/ho-so';
                } else {
                    window.location.href = '/ho-so';
                }
                return false;
            }

            // Map API fields to adminData
            adminData.id = data.id;
            adminData.username = data.email || data.username || '';
            adminData.fullName = data.full_name || adminData.fullName;
            adminData.email = data.email || '';
            adminData.phone = data.phone || '';
            adminData.address = data.address || '';
            adminData.role = data.type === 'ADMIN' ? 'Quản trị viên' : (data.type || adminData.role);
            adminData.avatar_url = normalizeAvatarUrl(data.avatar_url) || null;
            adminData.createdDate = data.created_at || adminData.createdDate;
            adminData.lastLogin = data.last_login || new Date().toLocaleString('vi-VN');

            updateAdminUI();
            return true;
        } else {
            console.warn('Failed to fetch profile:', response.status);
            // Token invalid, clear and redirect to login
            clearAuth();
            window.location.href = '/dang-nhap';
            return false;
        }
    } catch (error) {
        console.error('Error initializing admin session:', error);
        updateAdminUI();
        return false;
    }
}

// Update admin UI elements
function updateAdminUI() {
    // Helper to set avatar element
    function setAvatar(elId, url, initials) {
        const el = document.getElementById(elId);
        if (!el) return;

        if (url) {
            el.innerHTML = `<img src="${url}" alt="avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover">`;
        } else {
            el.textContent = (initials || adminData.avatar || 'AD').toUpperCase();
        }
    }

    const initials = adminData.fullName
        ? adminData.fullName.split(' ').map(s => s[0]).slice(0, 2).join('')
        : adminData.avatar;

    // Update avatar elements
    setAvatar('adminAvatar', adminData.avatar_url, initials);
    setAvatar('menuAvatar', adminData.avatar_url, initials);
    setAvatar('profileAvatar', adminData.avatar_url, initials);

    // Update name and role elements
    const adminNameEl = document.getElementById('adminName');
    if (adminNameEl) adminNameEl.textContent = adminData.fullName || 'Đang cập nhật';

    const adminRoleEl = document.getElementById('adminRole');
    if (adminRoleEl) adminRoleEl.textContent = adminData.role || 'Đang cập nhật';

    const menuNameEl = document.getElementById('menuName');
    if (menuNameEl) menuNameEl.textContent = adminData.fullName || 'Đang cập nhật';

    const menuEmailEl = document.getElementById('menuEmail');
    if (menuEmailEl) menuEmailEl.textContent = adminData.email || '—';

    const profileNameEl = document.getElementById('profileName');
    if (profileNameEl) profileNameEl.textContent = adminData.fullName || '';
}

// Admin logout
async function adminLogout() {
    if (!confirm('⚠️ Bạn có chắc chắn muốn đăng xuất?\n\nPhiên làm việc hiện tại sẽ kết thúc.')) {
        return;
    }

    const sessionId = getSessionId();

    try {
        if (sessionId) {
            await apiRequest('/auth/logout', 'POST', { session_id: sessionId });
        }
    } catch (error) {
        console.warn('Logout request failed:', error);
    }

    clearAuth();
    alert('✅ Đã đăng xuất thành công!\n\nHẹn gặp lại bạn.');
    window.location.href = '/dang-nhap';
}

// Toggle admin dropdown menu
function toggleAdminMenu() {
    const menu = document.getElementById('adminMenu');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// ============================================
// USER MANAGEMENT API
// ============================================

async function fetchUsers() {
    try {
        const response = await apiRequest('/users');

        if (response.ok) {
            const data = await response.json();
            // Store all users data globally for later use
            const allUsers = data.data || data;
            window.allUsersData = allUsers;
            
            // Filter to show only patients (type USER) - exclude ADMIN and DOCTOR
            window.usersData = allUsers.filter(u => 
                u.type && u.type.toUpperCase() === 'USER'
            );

            if (typeof renderUsers === 'function') {
                renderUsers(window.usersData);
            }
            return data;
        } else {
            console.error('Failed to fetch users:', response.status);
            return null;
        }
    } catch (error) {
        console.error('Error fetching users:', error);
        return null;
    }
}

// Get user by ID from stored data
function getUserById(id) {
    if (!window.usersData) return null;
    return window.usersData.find(u => u.id === parseInt(id));
}

// Format date for input field (YYYY-MM-DD)
function formatDateForInput(dateStr) {
    if (!dateStr) return '';
    // Handle different date formats
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return '';
    return date.toISOString().split('T')[0];
}

// Edit user - populate form with user data
function editUser(id) {
    const user = getUserById(id);
    if (!user) {
        alert('Không tìm thấy thông tin người dùng!');
        return;
    }

    // Set modal title
    document.getElementById('userModalTitle').textContent = 'Chỉnh sửa thông tin bệnh nhân';
    document.getElementById('userSubmitBtn').textContent = 'Cập nhật';

    // Populate form fields
    document.getElementById('userId').value = user.id;
    document.getElementById('userName').value = user.full_name || '';
    document.getElementById('userEmail').value = user.email || '';
    document.getElementById('userPhone').value = user.phone || '';
    document.getElementById('userDob').value = formatDateForInput(user.dob);

    // Set avatar
    const normalizedUserAvatar = normalizeAvatarUrl(user.avatar_url);
    document.getElementById('userAvatarUrl').value = normalizedUserAvatar || '';
    const avatarPreview = document.getElementById('userAvatarPreview');
    if (avatarPreview) {
        if (normalizedUserAvatar) {
            avatarPreview.innerHTML = `<img src="${normalizedUserAvatar}" style="width:100%;height:100%;object-fit:cover;">`;
            avatarPreview.style.background = 'none';
        } else {
            const initials = (user.full_name || 'U').split(' ').map(s => s[0]).slice(0, 2).join('').toUpperCase();
            avatarPreview.innerHTML = `<span style="color: white; font-size: 32px; font-weight: 600;">${initials}</span>`;
            avatarPreview.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        }
    }

    // Reset file input
    const avatarInput = document.getElementById('userAvatarInput');
    if (avatarInput) avatarInput.value = '';
    window.userAvatarFile = null;

    // Set gender - map database ENUM values to form values
    const genderSelect = document.getElementById('userGender');
    if (genderSelect) {
        const gender = (user.gender || '').toUpperCase();
        if (gender === 'MALE' || gender === 'NAM') {
            genderSelect.value = 'male';
        } else if (gender === 'FEMALE' || gender === 'NỮ') {
            genderSelect.value = 'female';
        } else if (gender === 'OTHER' || gender === 'KHÁC') {
            genderSelect.value = 'other';
        } else {
            genderSelect.value = '';
        }
    }

    // Set role/type
    const roleSelect = document.getElementById('userRole');
    if (roleSelect) {
        const type = (user.type || 'USER').toLowerCase();
        roleSelect.value = type === 'admin' ? 'admin' : type === 'doctor' ? 'doctor' : 'user';
    }

    // Set status
    const statusSelect = document.getElementById('userStatus');
    if (statusSelect) {
        statusSelect.value = (user.status || 'ACTIVE').toLowerCase();
    }

    // Clear password field (don't show existing password)
    document.getElementById('userPassword').value = '';

    // Password not required when editing
    document.getElementById('userPassword').required = false;
    document.getElementById('userPassword').placeholder = 'Để trống nếu không đổi mật khẩu';

    // Open modal
    openModal('addUserModal');
}

// Toggle user status (lock/unlock)
async function toggleUserStatus(id) {
    const user = getUserById(id);
    if (!user) {
        alert('Không tìm thấy thông tin người dùng!');
        return;
    }

    const currentStatus = (user.status || 'ACTIVE').toUpperCase();
    const newStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
    const action = newStatus === 'ACTIVE' ? 'mở khóa' : 'khóa';

    if (!confirm(`Bạn có chắc chắn muốn ${action} tài khoản "${user.full_name}"?`)) {
        return;
    }

    try {
        const response = await apiRequest(`/users/${id}`, 'PUT', { status: newStatus });

        if (response.ok) {
            alert(`✅ Đã ${action} tài khoản thành công!`);
            await fetchUsers(); // Refresh list
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || `Không thể ${action} tài khoản`));
        }
    } catch (error) {
        console.error('Error toggling user status:', error);
        alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Alias for lockUser
function lockUser(id) {
    toggleUserStatus(id);
}

// Toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = input.nextElementSibling;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('ri-eye-off-line');
        icon.classList.add('ri-eye-line');
    } else {
        input.type = 'password';
        icon.classList.remove('ri-eye-line');
        icon.classList.add('ri-eye-off-line');
    }
}

// Open add user modal (reset form completely)
function openAddUserModal() {
    // Set modal title and button text
    document.getElementById('userModalTitle').textContent = 'Thêm bệnh nhân mới';
    document.getElementById('userSubmitBtn').textContent = 'Thêm bệnh nhân';

    // Reset form
    document.getElementById('userForm').reset();

    // Clear hidden ID and avatar
    document.getElementById('userId').value = '';
    document.getElementById('userAvatarUrl').value = '';

    // Reset avatar preview
    const avatarPreview = document.getElementById('userAvatarPreview');
    if (avatarPreview) {
        avatarPreview.innerHTML = '<span id="userAvatarInitials" style="color: white; font-size: 32px; font-weight: 600;">U</span>';
        avatarPreview.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
    }

    // Clear all form fields explicitly
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPhone').value = '';
    document.getElementById('userPassword').value = '';

    // Set default values
    document.getElementById('userGender').value = 'NAM';
    document.getElementById('userDob').value = '';
    document.getElementById('userRole').value = 'user';
    document.getElementById('userStatus').value = 'active';

    // Make password required for new user
    document.getElementById('userPassword').required = true;
    document.getElementById('userPassword').placeholder = 'Nhập mật khẩu *';

    // Reset file input
    const avatarInput = document.getElementById('userAvatarInput');
    if (avatarInput) avatarInput.value = '';

    openModal('addUserModal');
}

// Preview user avatar
function previewUserAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('userAvatarPreview');
            if (preview) {
                preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
                preview.style.background = 'none';
            }
        };

        reader.readAsDataURL(file);

        // Store file for upload
        window.userAvatarFile = file;
    }
}

// Upload user avatar
async function uploadUserAvatar() {
    if (!window.userAvatarFile) {
        return null;
    }

    const formData = new FormData();
    formData.append('avatar', window.userAvatarFile);
    
    try {
        const response = await apiRequest('/upload/avatar', 'POST', formData);
        if (response.ok) {
            const result = await response.json();
            window.userAvatarFile = null;
            const avatarUrl = result.url || result.avatar_url;
            return avatarUrl;
        } else {
            const error = await response.json();
            console.error('Avatar upload failed:', error);
            alert('⚠️ Không thể tải ảnh đại diện: ' + (error.message || 'Lỗi không xác định'));
        }
    } catch (error) {
        console.error('Error uploading avatar:', error);
        alert('⚠️ Lỗi khi tải ảnh đại diện: ' + error.message);
    }
    return null;
}

function renderUsers(users) {
    const tbody = document.getElementById('userTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (!users || users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" style="text-align:center;">Không có dữ liệu</td></tr>';
        return;
    }

    users.forEach(user => {
        const isActive = (user.status || 'ACTIVE').toUpperCase() === 'ACTIVE';
        const initials = (user.full_name || user.name || 'U').split(' ').map(s => s[0]).slice(0, 2).join('').toUpperCase();
        const normalizedAvatar = normalizeAvatarUrl(user.avatar_url);
        const avatarHtml = normalizedAvatar
            ? `<img src="${normalizedAvatar}" alt="Avatar" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">`
            : `<div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);display:flex;align-items:center;justify-content:center;color:white;font-weight:600;font-size:14px;">${initials}</div>`;

        // Format gender
        const genderMap = { 'male': 'Nam', 'female': 'Nữ', 'other': 'Khác', 'nam': 'Nam', 'nữ': 'Nữ' };
        const gender = user.gender ? (genderMap[user.gender.toLowerCase()] || user.gender) : '—';

        // Format date of birth
        const dob = user.dob ? new Date(user.dob).toLocaleDateString('vi-VN') : '—';

        // Format created date
        const createdAt = user.created_at ? new Date(user.created_at).toLocaleDateString('vi-VN') : '—';

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${user.id}</td>
            <td>${avatarHtml}</td>
            <td>${user.full_name || user.name || '—'}</td>
            <td>${gender}</td>
            <td>${dob}</td>
            <td>${user.email || '—'}</td>
            <td>${user.phone || '—'}</td>
            <td>${createdAt}</td>
            <td>
                <span class="status-badge ${isActive ? 'active' : 'inactive'}"
                      style="cursor: pointer;"
                      onclick="toggleUserStatus(${user.id})"
                      title="Click để ${isActive ? 'khóa' : 'mở khóa'} tài khoản">
                    ${isActive ? 'Hoạt động' : 'Đã khóa'}
                </span>
            </td>
            <td>
                <button class="btn-action btn-edit" onclick="editUser(${user.id})" title="Chỉnh sửa"><i class="ri-edit-line"></i></button>
                <button class="btn-action btn-delete" onclick="deleteUserById(${user.id})" title="Xóa"><i class="ri-delete-bin-line"></i></button>
                <button class="btn-action btn-lock" onclick="toggleUserStatus(${user.id})" title="${isActive ? 'Khóa' : 'Mở khóa'}">
                    <i class="ri-${isActive ? 'lock' : 'lock-unlock'}-line"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

async function createUserApi(userData) {
    try {
        const response = await apiRequest('/users', 'POST', userData);

        if (response.ok) {
            const data = await response.json();
            await fetchUsers(); // Refresh list
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to create user:', error);
            alert('Lỗi: ' + (error.message || 'Không thể tạo người dùng'));
            return null;
        }
    } catch (error) {
        console.error('Error creating user:', error);
        return null;
    }
}

async function updateUserApi(id, userData) {
    try {
        const response = await apiRequest(`/users/${id}`, 'PUT', userData);

        if (response.ok) {
            const data = await response.json();
            await fetchUsers(); // Refresh list
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to update user:', error);
            alert('Lỗi: ' + (error.message || 'Không thể cập nhật người dùng'));
            return null;
        }
    } catch (error) {
        console.error('Error updating user:', error);
        return null;
    }
}

async function deleteUserById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
        return;
    }

    try {
        const response = await apiRequest(`/users/${id}`, 'DELETE');

        if (response.ok) {
            await fetchUsers(); // Refresh list
            alert('Đã xóa người dùng thành công!');
        } else {
            const error = await response.json();
            console.error('Failed to delete user:', error);
            alert('Lỗi: ' + (error.message || 'Không thể xóa người dùng'));
        }
    } catch (error) {
        console.error('Error deleting user:', error);
    }
}

// ============================================
// PROFILE UPDATE API
// ============================================

async function updateProfile(profileData) {
    try {
        const response = await apiRequest('/profile/me', 'PATCH', profileData);

        if (response.ok) {
            const data = await response.json();
            // Update local adminData
            if (profileData.full_name) adminData.fullName = profileData.full_name;
            if (profileData.phone) adminData.phone = profileData.phone;
            if (profileData.address) adminData.address = profileData.address;

            updateAdminUI();
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to update profile:', error);
            return null;
        }
    } catch (error) {
        console.error('Error updating profile:', error);
        return null;
    }
}

async function uploadAvatar(file) {
    try {
        const formData = new FormData();
        formData.append('avatar', file);

        const response = await apiRequest('/profile/avatar', 'POST', formData);

        if (response.ok) {
            const data = await response.json();
            adminData.avatar_url = data.avatar_url;
            updateAdminUI();
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to upload avatar:', error);
            return null;
        }
    } catch (error) {
        console.error('Error uploading avatar:', error);
        return null;
    }
}

// ============================================
// INITIALIZE APP
// ============================================

async function initializeApp() {
    // First, initialize admin session (load profile)
    const sessionOk = await initAdminSession();

    if (sessionOk) {
        // Fetch all data in parallel
        await Promise.all([
            fetchUsers(),
            fetchDoctors(),
            fetchAllClinics(),
            fetchAllSpecializations(),
            fetchAllServices(),
            fetchReviews(),
            fetchPayments('refund_pending'),
            fetchAppointments('all'),
            fetchDashboardStats(),
            fetchReportStats('month')
        ]);
        
        // Render payments table after data is fetched
        renderPayments();
    }

    // Initialize form handlers
    initAppointmentFormHandler();

    }

// ============================================
// FORM HANDLERS
// ============================================

// Helper function to close modal and optionally refresh data
function closeModal(modalId, shouldRefresh = true) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
    }
    
    // Refresh data based on modal type (default true for better UX)
    if (shouldRefresh) {
        refreshDataAfterModalClose(modalId);
    }
}

// Refresh data after modal close
function refreshDataAfterModalClose(modalId) {
    switch(modalId) {
        case 'addUserModal':
            if (typeof loadUsers === 'function') loadUsers();
            break;
        case 'addDoctorModal':
            if (typeof loadDoctors === 'function') loadDoctors();
            break;
        case 'addClinicModal':
            if (typeof loadClinics === 'function') loadClinics();
            break;
        case 'addSpecialtyModal':
            if (typeof loadSpecializations === 'function') loadSpecializations();
            break;
        case 'addServiceModal':
            if (typeof loadServices === 'function') loadServices();
            break;
        case 'addAppointmentModal':
            if (typeof loadAppointments === 'function') loadAppointments();
            break;
        case 'paymentActionModal':
            if (typeof loadPayments === 'function') loadPayments();
            break;
        case 'viewReviewsModal':
            if (typeof loadReviews === 'function') loadReviews();
            break;
        case 'logDetailModal':
            // Don't refresh logs on detail close
            break;
        case 'adminProfileModal':
            // Don't refresh on profile modal close
            break;
    }
}

// Close modal without refresh (for specific cases)
function closeModalNoRefresh(modalId) {
    closeModal(modalId, false);
}

// Helper function to open modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        // Remove any stale state first
        modal.classList.remove('active');
        modal.style.display = 'none';
        
        // Force a reflow to ensure CSS transition works
        void modal.offsetWidth;
        
        // Now show the modal
        modal.classList.add('active');
        modal.style.display = 'flex';
    }
}

// View all logs by type - wrapper function to ensure modal opens properly
async function viewAllLogsModal(type) {
    const modal = document.getElementById('logDetailModal');
    if (modal) {
        // First close if already open, then reopen
        modal.classList.remove('active');
        modal.style.display = 'none';
        void modal.offsetWidth;
    }
    await viewAllLogs(type);
}

// Handle user form submission
function initUserFormHandler() {
    const userForm = document.getElementById('userForm');
    if (!userForm) return;

    userForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const userId = document.getElementById('userId').value;
        const isEdit = !!userId;

        // Upload avatar if selected
        let avatarUrl = document.getElementById('userAvatarUrl').value || null;
        if (window.userAvatarFile) {
            const uploadedUrl = await uploadUserAvatar();
            if (uploadedUrl) {
                avatarUrl = uploadedUrl;
            }
        }

        // Collect form data
        // Map gender to database ENUM values (MALE, FEMALE, OTHER)
        const genderValue = document.getElementById('userGender').value;
        const genderMap = { 'male': 'MALE', 'female': 'FEMALE', 'other': 'OTHER' };
        const mappedGender = genderMap[genderValue] || null;
        
        const userData = {
            full_name: document.getElementById('userName').value.trim(),
            gender: mappedGender,
            dob: document.getElementById('userDob').value,
            email: document.getElementById('userEmail').value.trim(),
            phone: document.getElementById('userPhone').value.trim(),
            type: document.getElementById('userRole').value.toUpperCase(),
            status: document.getElementById('userStatus').value.toUpperCase(),
            avatar_url: avatarUrl
        };

        // Add password only if provided (for create or password change)
        const password = document.getElementById('userPassword').value;
        if (password) {
            userData.password = password;
        }

        // Validate required fields
        if (!userData.full_name || !userData.email || !userData.phone) {
            alert('⚠️ Vui lòng điền đầy đủ thông tin bắt buộc!');
            return;
        }

        try {
            let result;
            if (isEdit) {
                result = await updateUserApi(userId, userData);
            } else {
                if (!password) {
                    alert('⚠️ Vui lòng nhập mật khẩu cho người dùng mới!');
                    return;
                }
                result = await createUserApi(userData);
            }

            if (result) {
                alert(`✅ Đã ${isEdit ? 'cập nhật' : 'thêm'} người dùng thành công!`);
                closeModal('addUserModal');
                userForm.reset();
                document.getElementById('userId').value = '';
                window.userAvatarFile = null;
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form handlers
    initUserFormHandler();
    initDoctorFormHandler();
    initClinicFormHandler();
    initSpecialtyFormHandler();
    initServiceFormHandler();

    // Check if we have a token before initializing
    const token = getAccessToken();
    if (token && token !== 'null') {
        initializeApp();
    } else {
        console.warn('No access token, skipping API initialization');
        updateAdminUI();
    }
});

// Export functions for global use
window.apiRequest = apiRequest;
window.initAdminSession = initAdminSession;
window.adminLogout = adminLogout;
window.toggleAdminMenu = toggleAdminMenu;
window.fetchUsers = fetchUsers;
window.renderUsers = renderUsers;
window.createUserApi = createUserApi;
window.updateUserApi = updateUserApi;
window.deleteUserById = deleteUserById;
window.updateProfile = updateProfile;
window.uploadAvatar = uploadAvatar;
window.adminData = adminData;
window.updateAdminUI = updateAdminUI;
window.closeModal = closeModal;
window.closeModalNoRefresh = closeModalNoRefresh;
window.openModal = openModal;
window.editUser = editUser;
window.getUserById = getUserById;
window.toggleUserStatus = toggleUserStatus;
window.lockUser = lockUser;
window.togglePassword = togglePassword;
window.openAddUserModal = openAddUserModal;
window.previewUserAvatar = previewUserAvatar;
window.uploadUserAvatar = uploadUserAvatar;

// ============================================
// DOCTOR MANAGEMENT FUNCTIONS
// ============================================

// Global storage for doctors data
window.doctorsData = [];
window.specializationsData = [];
window.clinicsData = [];

// Fetch all doctors
async function fetchDoctors() {
    try {
        const response = await apiRequest('/doctors');
        if (response.ok) {
            const doctors = await response.json();
            window.doctorsData = doctors;
            renderDoctors(doctors);
            return doctors;
        } else {
            console.error('Failed to fetch doctors');
            return [];
        }
    } catch (error) {
        console.error('Error fetching doctors:', error);
        return [];
    }
}

// Fetch specializations for dropdown
async function fetchSpecializations() {
    try {
        const response = await apiRequest('/specializations');
        if (response.ok) {
            const data = await response.json();
            window.specializationsData = data;
            populateSpecializationDropdown(data);
            return data;
        }
    } catch (error) {
        console.error('Error fetching specializations:', error);
    }
    return [];
}

// Fetch clinics for dropdown
async function fetchClinics() {
    try {
        const response = await apiRequest('/clinics');
        if (response.ok) {
            const data = await response.json();
            window.clinicsData = data;
            populateClinicDropdown(data);
            return data;
        }
    } catch (error) {
        console.error('Error fetching clinics:', error);
    }
    return [];
}

// Populate specialization dropdown
function populateSpecializationDropdown(specializations) {
    const select = document.getElementById('doctorSpecialty');
    if (!select) return;

    select.innerHTML = '<option value="">Chọn chuyên specialty</option>';
    specializations.forEach(s => {
        select.innerHTML += `<option value="${s.id}">${s.name}</option>`;
    });
}

// Populate clinic dropdown
function populateClinicDropdown(clinics) {
    const select = document.getElementById('doctorClinic');
    if (!select) return;

    select.innerHTML = '<option value="">Chọn phòng khám</option>';
    clinics.forEach(c => {
        select.innerHTML += `<option value="${c.id}">${c.name}</option>`;
    });
}

// Render doctors table
function renderDoctors(doctors) {
    const tbody = document.getElementById('doctorTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    if (!doctors || doctors.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Không có dữ liệu</td></tr>';
        return;
    }

    doctors.forEach(doctor => {
        const isActive = (doctor.doctor_status || 'ACTIVE').toUpperCase() === 'ACTIVE';
        const normalizedAvatar = normalizeAvatarUrl(doctor.avatar_url);
        const avatarHtml = normalizedAvatar
            ? `<img src="${normalizedAvatar}" alt="" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:8px;">`
            : `<div style="width:32px;height:32px;border-radius:50%;background:#e74c3c;color:white;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;font-size:12px;">${(doctor.full_name || 'BS').charAt(0)}</div>`;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${doctor.id}</td>
            <td>
                <div style="display:flex;align-items:center;">
                    ${avatarHtml}
                    <div>
                        <div style="font-weight:500;">${doctor.full_name || '—'}</div>
                        <div style="font-size:12px;color:#666;">${doctor.email || ''}</div>
                    </div>
                </div>
            </td>
            <td>${doctor.specialization_name || '—'}</td>
            <td>${doctor.experience || 0} năm</td>
            <td>${doctor.clinic_name || '—'}</td>
            <td>
                <span class="status-badge ${isActive ? 'active' : 'inactive'}"
                      style="cursor: pointer;"
                      onclick="toggleDoctorStatus(${doctor.id})"
                      title="Click để ${isActive ? 'khóa' : 'mở khóa'}">
                    ${isActive ? 'Hoạt động' : 'Đã khóa'}
                </span>
            </td>
            <td>
                <button class="btn-action btn-edit" onclick="editDoctor(${doctor.id})" title="Chỉnh sửa"><i class="ri-edit-line"></i></button>
                <button class="btn-action btn-delete" onclick="deleteDoctorById(${doctor.id})" title="Xóa"><i class="ri-delete-bin-line"></i></button>
                <button class="btn-action btn-lock" onclick="toggleDoctorStatus(${doctor.id})" title="${isActive ? 'Khóa' : 'Mở khóa'}">
                    <i class="ri-${isActive ? 'lock' : 'lock-unlock'}-line"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Get doctor by ID
function getDoctorById(id) {
    return window.doctorsData.find(d => d.id === parseInt(id));
}

// Open add doctor modal
function openAddDoctorModal() {
    document.getElementById('doctorModalTitle').textContent = 'Thêm bác sĩ mới';
    document.getElementById('doctorSubmitBtn').textContent = 'Thêm bác sĩ';
    document.getElementById('doctorForm').reset();
    document.getElementById('doctorId').value = '';

    // Reset avatar
    document.getElementById('doctorAvatarPreview').style.display = 'none';
    document.getElementById('doctorAvatarIcon').style.display = 'block';
    document.getElementById('doctorAvatarUrl').value = '';

    // Password required for new doctor
    document.getElementById('doctorPassword').required = true;
    document.getElementById('doctorPassword').placeholder = 'Nhập mật khẩu *';

    // Set default status
    document.getElementById('doctorStatus').value = 'ACTIVE';

    // Load dropdowns
    fetchSpecializations();
    fetchClinics();
    loadDoctorServicesDropdown();

    openModal('addDoctorModal');
}

// Load services dropdown for doctor form
async function loadDoctorServicesDropdown(selectedIds = []) {
    const container = document.getElementById('doctorServicesContainer');
    if (!container) return;
    
    try {
        const response = await apiRequest('/services', 'GET');
        const data = await response.json();
        
        if (data.success && data.data) {
            const activeServices = data.data.filter(s => s.is_active);
            if (activeServices.length === 0) {
                container.innerHTML = '<p style="color: #999; font-size: 13px;">Không có dịch vụ nào</p>';
                return;
            }
            
            container.innerHTML = activeServices
                .map(s => `
                    <label style="display: flex; align-items: center; padding: 5px 0; cursor: pointer;">
                        <input type="checkbox" name="doctorServices" value="${s.id}" 
                               ${selectedIds.includes(s.id) ? 'checked' : ''}
                               style="margin-right: 8px; cursor: pointer;">
                        <span style="flex: 1; font-size: 13px;">${s.name}</span>
                        <span style="color: #27ae60; font-size: 12px;">${formatCurrencyVND(s.price)}</span>
                    </label>
                `)
                .join('');
        } else {
            container.innerHTML = '<p style="color: #999; font-size: 13px;">Không có dịch vụ nào</p>';
        }
    } catch (error) {
        console.error('Error loading services:', error);
        container.innerHTML = '<p style="color: #e74c3c; font-size: 13px;">Lỗi tải dịch vụ</p>';
    }
}

// Get selected doctor services
function getSelectedDoctorServices() {
    const checkboxes = document.querySelectorAll('input[name="doctorServices"]:checked');
    return Array.from(checkboxes).map(cb => parseInt(cb.value));
}

// Edit doctor
function editDoctor(id) {
    const doctor = getDoctorById(id);
    if (!doctor) {
        alert('Không tìm thấy thông tin bác sĩ!');
        return;
    }

    document.getElementById('doctorModalTitle').textContent = 'Chỉnh sửa thông tin bác sĩ';
    document.getElementById('doctorSubmitBtn').textContent = 'Cập nhật';

    // Load dropdowns first, then populate form
    Promise.all([fetchSpecializations(), fetchClinics(), loadDoctorServicesDropdown(doctor.service_ids || [])]).then(() => {
        document.getElementById('doctorId').value = doctor.id;
        document.getElementById('doctorName').value = doctor.full_name || '';
        document.getElementById('doctorEmail').value = doctor.email || '';
        document.getElementById('doctorPhone').value = doctor.phone || '';
        document.getElementById('doctorAddress').value = doctor.address || '';

        // Normalize gender value
        let genderValue = doctor.gender || '';
        if (genderValue.toUpperCase() === 'MALE' || genderValue === 'Nam') {
            genderValue = 'Nam';
        } else if (genderValue.toUpperCase() === 'FEMALE' || genderValue === 'Nữ') {
            genderValue = 'Nữ';
        } else if (genderValue) {
            genderValue = 'Khác';
        }
        document.getElementById('doctorGender').value = genderValue;

        document.getElementById('doctorDob').value = formatDateForInput(doctor.dob);
        document.getElementById('doctorDegree').value = doctor.degree || '';
        document.getElementById('doctorExperience').value = doctor.experience || 0;
        document.getElementById('doctorDescription').value = doctor.description || '';
        document.getElementById('doctorSpecialty').value = doctor.specialization_id || '';
        document.getElementById('doctorClinic').value = doctor.clinic_id || '';
        document.getElementById('doctorStatus').value = doctor.doctor_status || 'ACTIVE';

        // Set avatar
        const normalizedDoctorAvatar = normalizeAvatarUrl(doctor.avatar_url);
        if (normalizedDoctorAvatar) {
            document.getElementById('doctorAvatarPreview').src = normalizedDoctorAvatar;
            document.getElementById('doctorAvatarPreview').style.display = 'block';
            document.getElementById('doctorAvatarIcon').style.display = 'none';
            document.getElementById('doctorAvatarUrl').value = normalizedDoctorAvatar;
        } else {
            document.getElementById('doctorAvatarPreview').style.display = 'none';
            document.getElementById('doctorAvatarIcon').style.display = 'block';
        }

        // Password not required when editing
        document.getElementById('doctorPassword').required = false;
        document.getElementById('doctorPassword').placeholder = 'Để trống nếu không đổi';
        document.getElementById('doctorPassword').value = '';

        openModal('addDoctorModal');
    });
}

// Store the selected file for later upload
window.doctorAvatarFile = null;

// Preview doctor avatar
function previewDoctorAvatar(input) {
    if (input.files && input.files[0]) {
        // Store file for later upload
        window.doctorAvatarFile = input.files[0];

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('doctorAvatarPreview').src = e.target.result;
            document.getElementById('doctorAvatarPreview').style.display = 'block';
            document.getElementById('doctorAvatarIcon').style.display = 'none';
            // Mark that we have a new file to upload
            document.getElementById('doctorAvatarUrl').value = 'pending_upload';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Upload doctor avatar and return URL
async function uploadDoctorAvatar() {
    if (!window.doctorAvatarFile) {
        return null;
    }

    const formData = new FormData();
    formData.append('avatar', window.doctorAvatarFile);

    try {
        const token = getAccessToken();
        const response = await fetch('/api/upload/avatar', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });

        if (response.ok) {
            const data = await response.json();
            window.doctorAvatarFile = null; // Clear after upload
            return data.avatar_url;
        } else {
            console.error('Failed to upload avatar');
            return null;
        }
    } catch (error) {
        console.error('Error uploading avatar:', error);
        return null;
    }
}

// Toggle doctor status
async function toggleDoctorStatus(id) {
    const doctor = getDoctorById(id);
    if (!doctor) {
        alert('Không tìm thấy thông tin bác sĩ!');
        return;
    }

    const currentStatus = (doctor.doctor_status || 'ACTIVE').toUpperCase();
    const newStatus = currentStatus === 'ACTIVE' ? 'INACTIVE' : 'ACTIVE';
    const action = newStatus === 'ACTIVE' ? 'mở khóa' : 'khóa';

    if (!confirm(`Bạn có chắc chắn muốn ${action} bác sĩ "${doctor.full_name}"?`)) {
        return;
    }

    try {
        const response = await apiRequest(`/doctors/${id}`, 'PUT', { doctor_status: newStatus });

        if (response.ok) {
            alert(`✅ Đã ${action} bác sĩ thành công!`);
            await fetchDoctors();
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || 'Không thể cập nhật trạng thái'));
        }
    } catch (error) {
        console.error('Error toggling doctor status:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Create doctor API
async function createDoctorApi(doctorData) {
    try {
        const response = await apiRequest('/doctors', 'POST', doctorData);

        if (response.ok) {
            const data = await response.json();
            await fetchDoctors();
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to create doctor:', error);
            alert('Lỗi: ' + (error.message || error.error || 'Không thể tạo bác sĩ'));
            return null;
        }
    } catch (error) {
        console.error('Error creating doctor:', error);
        return null;
    }
}

// Update doctor API
async function updateDoctorApi(id, doctorData) {
    try {
        const response = await apiRequest(`/doctors/${id}`, 'PUT', doctorData);

        if (response.ok) {
            const data = await response.json();
            await fetchDoctors();
            return data;
        } else {
            const error = await response.json();
            console.error('Failed to update doctor:', error);
            alert('Lỗi: ' + (error.message || error.error || 'Không thể cập nhật bác sĩ'));
            return null;
        }
    } catch (error) {
        console.error('Error updating doctor:', error);
        return null;
    }
}

// Delete doctor
async function deleteDoctorById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa bác sĩ này?')) {
        return;
    }

    try {
        const response = await apiRequest(`/doctors/${id}`, 'DELETE');

        if (response.ok) {
            await fetchDoctors();
            alert('Đã xóa bác sĩ thành công!');
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || 'Không thể xóa bác sĩ'));
        }
    } catch (error) {
        console.error('Error deleting doctor:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Handle doctor form submission
function initDoctorFormHandler() {
    const doctorForm = document.getElementById('doctorForm');
    if (!doctorForm) return;

    // Helper function to convert gender UI value to database value
    function genderToDb(val) {
        if (!val) return null;
        const v = val.toLowerCase();
        if (v === 'nam' || v === 'male') return 'MALE';
        if (v === 'nữ' || v === 'female') return 'FEMALE';
        return 'OTHER';
    }

    doctorForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const doctorId = document.getElementById('doctorId').value;
        const isEdit = !!doctorId;

        // Get gender and convert to DB format
        const genderUi = document.getElementById('doctorGender').value;
        const genderDb = genderToDb(genderUi);

        // Get avatar URL - check if we need to upload first
        let avatarUrl = document.getElementById('doctorAvatarUrl').value || null;

        // If there's a pending file upload, upload it first
        if (window.doctorAvatarFile) {
            const uploadedUrl = await uploadDoctorAvatar();
            if (uploadedUrl) {
                avatarUrl = uploadedUrl;
                } else {
                console.warn('Avatar upload failed, continuing without avatar');
                avatarUrl = null;
            }
        }

        // Skip pending_upload marker
        if (avatarUrl === 'pending_upload') {
            avatarUrl = null;
        }

        // Collect form data
        const doctorData = {
            full_name: document.getElementById('doctorName').value.trim(),
            email: document.getElementById('doctorEmail').value.trim(),
            phone: document.getElementById('doctorPhone').value.trim(),
            address: document.getElementById('doctorAddress').value.trim(),
            gender: genderDb,
            dob: document.getElementById('doctorDob').value,
            degree: document.getElementById('doctorDegree').value.trim(),
            experience: parseInt(document.getElementById('doctorExperience').value) || 0,
            description: document.getElementById('doctorDescription').value.trim(),
            specialization_id: parseInt(document.getElementById('doctorSpecialty').value) || null,
            clinic_id: parseInt(document.getElementById('doctorClinic').value) || null,
            doctor_status: document.getElementById('doctorStatus').value,
            avatar_url: avatarUrl,
        };

        // Get selected services from checkboxes
        doctorData.service_ids = getSelectedDoctorServices();

        // Add password only if provided
        const password = document.getElementById('doctorPassword').value;
        if (password) {
            doctorData.password = password;
        }

        // Only validate email for new doctor (required for account creation)
        if (!isEdit && !doctorData.email) {
            alert('⚠️ Vui lòng nhập email cho bác sĩ mới!');
            return;
        }

        try {
            let result;
            if (isEdit) {
                result = await updateDoctorApi(doctorId, doctorData);
            } else {
                if (!password) {
                    alert('⚠️ Vui lòng nhập mật khẩu cho bác sĩ mới!');
                    return;
                }
                result = await createDoctorApi(doctorData);
            }

            if (result) {
                alert(`✅ Đã ${isEdit ? 'cập nhật' : 'thêm'} bác sĩ thành công!`);
                closeModal('addDoctorModal');
                doctorForm.reset();
                document.getElementById('doctorId').value = '';
                window.doctorAvatarFile = null; // Clear file reference

                // Reset avatar preview
                const preview = document.getElementById('doctorAvatarPreview');
                const icon = document.getElementById('doctorAvatarIcon');
                if (preview) preview.style.display = 'none';
                if (icon) icon.style.display = 'block';
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
}

// Export doctor functions
window.fetchDoctors = fetchDoctors;
window.fetchSpecializations = fetchSpecializations;
window.fetchClinics = fetchClinics;
window.renderDoctors = renderDoctors;
window.getDoctorById = getDoctorById;
window.openAddDoctorModal = openAddDoctorModal;
window.editDoctor = editDoctor;
window.previewDoctorAvatar = previewDoctorAvatar;
window.toggleDoctorStatus = toggleDoctorStatus;
window.createDoctorApi = createDoctorApi;
window.updateDoctorApi = updateDoctorApi;
window.deleteDoctorById = deleteDoctorById;
window.initDoctorFormHandler = initDoctorFormHandler;

// ============================================
// CLINIC MANAGEMENT SECTION
// ============================================

let allClinics = [];

// Fetch all clinics from API
async function fetchAllClinics() {
    try {
        const response = await apiRequest('/clinics/all');
        if (response.ok) {
            allClinics = await response.json();
            renderClinics(); // Render after fetching
            return allClinics;
        } else {
            console.error('Failed to fetch clinics');
            return [];
        }
    } catch (error) {
        console.error('Error fetching clinics:', error);
        return [];
    }
}

// Get clinic by ID from cache
function getClinicById(id) {
    return allClinics.find(c => c.id == id);
}

// Render clinics table
function renderClinics(clinics = null) {
    const tableBody = document.getElementById('clinicTableBody');
    if (!tableBody) return;

    const data = clinics || allClinics;

    if (!data || data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;">Không có dữ liệu phòng khám</td></tr>';
        return;
    }

    tableBody.innerHTML = data.map(clinic => {
        const statusClass = clinic.status == 1 ? 'status-active' : 'status-inactive';
        const statusText = clinic.status == 1 ? 'Hoạt động' : 'Ngừng hoạt động';

        return `
            <tr data-status="${clinic.status}">
                <td>#PK${String(clinic.id).padStart(3, '0')}</td>
                <td>${clinic.name || ''}</td>
                <td>${clinic.address || ''}</td>
                <td>${clinic.hotline || ''}</td>
                <td>${clinic.email || ''}</td>
                <td>${clinic.opening_hours || ''}</td>
                <td>
                    <span class="status-badge ${statusClass}"
                          style="cursor:pointer"
                          onclick="toggleClinicStatus(${clinic.id})"
                          title="Click để đổi trạng thái">
                        ${statusText}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <div class="btn-icon btn-edit" title="Chỉnh sửa" onclick="editClinic(${clinic.id})">
                            <i class="ri-edit-line"></i>
                        </div>
                        <div class="btn-icon btn-delete" title="Xóa" onclick="deleteClinicById(${clinic.id})">
                            <i class="ri-delete-bin-line"></i>
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Filter clinics by search and status
function filterClinics() {
    const searchInput = document.getElementById('clinicSearchInput');
    const statusFilter = document.getElementById('clinicStatusFilter');

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusFilter ? statusFilter.value : 'all';

    const filtered = allClinics.filter(clinic => {
        // Search filter
        const matchSearch = !searchTerm ||
            (clinic.name && clinic.name.toLowerCase().includes(searchTerm)) ||
            (clinic.address && clinic.address.toLowerCase().includes(searchTerm)) ||
            (clinic.hotline && clinic.hotline.toLowerCase().includes(searchTerm)) ||
            (clinic.email && clinic.email.toLowerCase().includes(searchTerm));

        // Status filter
        const matchStatus = statusValue === 'all' || clinic.status == statusValue;

        return matchSearch && matchStatus;
    });

    renderClinics(filtered);
}

// Open add clinic modal
function openAddClinicModal() {
    document.getElementById('clinicModalTitle').textContent = 'Thêm phòng khám mới';
    document.getElementById('clinicSubmitBtn').textContent = 'Thêm phòng khám';
    document.getElementById('clinicId').value = '';
    document.getElementById('clinicForm').reset();
    document.getElementById('clinicStatus').value = '1';
    openModal('addClinicModal');
}

// Edit clinic - load data into form
function editClinic(id) {
    const clinic = getClinicById(id);
    if (!clinic) {
        alert('Không tìm thấy thông tin phòng khám!');
        return;
    }

    document.getElementById('clinicModalTitle').textContent = 'Chỉnh sửa thông tin phòng khám';
    document.getElementById('clinicSubmitBtn').textContent = 'Cập nhật';
    document.getElementById('clinicId').value = clinic.id;

    // Fill form with clinic data
    document.getElementById('clinicName').value = clinic.name || '';
    document.getElementById('clinicAddress').value = clinic.address || '';
    document.getElementById('clinicHotline').value = clinic.hotline || '';
    document.getElementById('clinicEmail').value = clinic.email || '';
    document.getElementById('clinicOpeningHours').value = clinic.opening_hours || '';
    document.getElementById('clinicDescription').value = clinic.description || '';
    document.getElementById('clinicStatus').value = clinic.status != null ? clinic.status : 1;

    openModal('addClinicModal');
}

// Toggle clinic status
async function toggleClinicStatus(id) {
    const clinic = getClinicById(id);
    if (!clinic) {
        alert('Không tìm thấy thông tin phòng khám!');
        return;
    }

    const newStatus = clinic.status == 1 ? 0 : 1;
    const statusText = newStatus == 1 ? 'hoạt động' : 'ngừng hoạt động';

    if (!confirm(`Bạn có chắc muốn chuyển phòng khám sang trạng thái "${statusText}"?`)) {
        return;
    }

    try {
        const response = await apiRequest(`/clinics/${id}`, 'PUT', { status: newStatus });

        if (response.ok) {
            alert('✅ Đã cập nhật trạng thái thành công!');
            await fetchAllClinics();
            renderClinics();
        } else {
            const error = await response.json();
            alert('⚠️ Lỗi: ' + (error.message || 'Không thể cập nhật trạng thái'));
        }
    } catch (error) {
        console.error('Error toggling clinic status:', error);
        alert('⚠️ Có lỗi xảy ra khi cập nhật trạng thái.');
    }
}

// Create clinic API
async function createClinicApi(clinicData) {
    try {
        const response = await apiRequest('/clinics', 'POST', clinicData);

        if (response.ok) {
            const result = await response.json();
            await fetchAllClinics();
            renderClinics();
            return result;
        } else {
            const error = await response.json();
            alert('⚠️ Lỗi tạo phòng khám: ' + (error.message || 'Unknown error'));
            return null;
        }
    } catch (error) {
        console.error('Error creating clinic:', error);
        alert('⚠️ Có lỗi xảy ra khi tạo phòng khám.');
        return null;
    }
}

// Update clinic API
async function updateClinicApi(id, clinicData) {
    try {
        const response = await apiRequest(`/clinics/${id}`, 'PUT', clinicData);

        if (response.ok) {
            const result = await response.json();
            await fetchAllClinics();
            renderClinics();
            return result;
        } else {
            const error = await response.json();
            alert('⚠️ Lỗi cập nhật phòng khám: ' + (error.message || 'Unknown error'));
            return null;
        }
    } catch (error) {
        console.error('Error updating clinic:', error);
        alert('⚠️ Có lỗi xảy ra khi cập nhật phòng khám.');
        return null;
    }
}

// Delete clinic
async function deleteClinicById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa phòng khám này?')) {
        return;
    }

    try {
        const response = await apiRequest(`/clinics/${id}`, 'DELETE');

        if (response.ok) {
            alert('✅ Đã xóa phòng khám thành công!');
            await fetchAllClinics();
            renderClinics();
        } else {
            const error = await response.json();
            alert('⚠️ Lỗi xóa phòng khám: ' + (error.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error deleting clinic:', error);
        alert('⚠️ Có lỗi xảy ra khi xóa phòng khám.');
    }
}

// Initialize clinic form handler
function initClinicFormHandler() {
    const clinicForm = document.getElementById('clinicForm');
    if (!clinicForm) return;

    clinicForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const clinicId = document.getElementById('clinicId').value;
        const isEdit = !!clinicId;

        // Collect form data
        const clinicData = {
            name: document.getElementById('clinicName').value.trim(),
            address: document.getElementById('clinicAddress').value.trim(),
            hotline: document.getElementById('clinicHotline').value.trim(),
            email: document.getElementById('clinicEmail').value.trim(),
            opening_hours: document.getElementById('clinicOpeningHours').value.trim(),
            description: document.getElementById('clinicDescription').value.trim(),
            status: parseInt(document.getElementById('clinicStatus').value) || 1,
        };

        try {
            let result;
            if (isEdit) {
                result = await updateClinicApi(clinicId, clinicData);
            } else {
                result = await createClinicApi(clinicData);
            }

            if (result) {
                alert(`✅ Đã ${isEdit ? 'cập nhật' : 'thêm'} phòng khám thành công!`);
                closeModal('addClinicModal');
                clinicForm.reset();
                document.getElementById('clinicId').value = '';
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
}

// Export clinic functions
window.fetchAllClinics = fetchAllClinics;
window.renderClinics = renderClinics;
window.getClinicById = getClinicById;
window.openAddClinicModal = openAddClinicModal;
window.editClinic = editClinic;
window.toggleClinicStatus = toggleClinicStatus;
window.filterClinics = filterClinics;
window.createClinicApi = createClinicApi;
window.updateClinicApi = updateClinicApi;
window.deleteClinicById = deleteClinicById;
window.initClinicFormHandler = initClinicFormHandler;

// ============================================
// PAYMENT MANAGEMENT SECTION
// ============================================

let allPayments = [];
let paymentCounts = { pending: 0, refund: 0, success: 0, failed: 0 };
let currentPaymentTab = 'refund_pending';

// Fetch payments from API
async function fetchPayments(filter = 'refund_pending') {
    try {
        const response = await apiRequest(`/payments?filter=${filter}`);
        if (response.ok) {
            const data = await response.json();
            allPayments = data.payments || [];
            paymentCounts = data.counts || { pending: 0, refund: 0, success: 0, failed: 0 };
            updatePaymentCounts();
            return allPayments;
        } else {
            console.error('Failed to fetch payments');
            return [];
        }
    } catch (error) {
        console.error('Error fetching payments:', error);
        return [];
    }
}

// Update badge counts
function updatePaymentCounts() {
    const pending = document.getElementById('pendingPaymentCount');
    const refund = document.getElementById('refundPaymentCount');
    const success = document.getElementById('successPaymentCount');
    const failed = document.getElementById('failedPaymentCount');

    if (pending) pending.textContent = paymentCounts.pending || 0;
    if (refund) refund.textContent = paymentCounts.refund || 0;
    if (success) success.textContent = paymentCounts.success || 0;
    if (failed) failed.textContent = paymentCounts.failed || 0;
}

// Switch payment tab
async function switchPaymentTab(tab) {
    currentPaymentTab = tab;

    // Update active tab
    document.querySelectorAll('.filter-tabs [data-payment-tab]').forEach(btn => {
        btn.classList.remove('active');
    });
    const activeTab = document.querySelector(`[data-payment-tab="${tab}"]`);
    if (activeTab) activeTab.classList.add('active');

    // Fetch and render payments for this tab
    await fetchPayments(tab);
    renderPayments();
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount || 0);
}

// Format date
function formatDatePayment(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleDateString('vi-VN');
}

// Format datetime
function formatDateTimePayment(dateStr) {
    if (!dateStr) return '-';
    const date = new Date(dateStr);
    return date.toLocaleString('vi-VN');
}

// Get payment status badge
function getPaymentStatusBadge(payment) {
    const paymentStatus = payment.payment_status;
    const refundStatus = payment.refund_status;

    if (refundStatus === 'APPROVED' || paymentStatus === 'REFUNDED') {
        return '<span class="status-badge status-active">Đã hoàn tiền</span>';
    }
    if (refundStatus === 'REJECTED') {
        return '<span class="status-badge status-inactive">Từ chối</span>';
    }
    if (refundStatus === 'REQUESTED') {
        return '<span class="status-badge status-pending">Yêu cầu hoàn tiền</span>';
    }
    if (paymentStatus === 'PAID' && refundStatus === 'NONE') {
        return '<span class="status-badge status-pending">Chờ xác nhận</span>';
    }
    return '<span class="status-badge">' + paymentStatus + '</span>';
}

// Render payments table
function renderPayments(payments = null) {
    const tableBody = document.getElementById('paymentTableBody');
    if (!tableBody) return;

    const data = payments || allPayments;

    if (!data || data.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center;padding:20px;">Không có dữ liệu thanh toán</td></tr>';
        return;
    }

    tableBody.innerHTML = data.map(payment => {
        const isPending = payment.payment_status === 'PAID' && payment.refund_status === 'NONE';
        const isRefundRequest = payment.refund_status === 'REQUESTED';
        const showActions = isPending || isRefundRequest;

        return `
            <tr>
                <td>#TT${String(payment.id).padStart(4, '0')}</td>
                <td>
                    <div style="font-weight:500">${payment.patient_name || '-'}</div>
                    <div style="font-size:12px;color:#777">${payment.patient_phone || ''}</div>
                </td>
                <td>${payment.doctor_name || '-'}</td>
                <td>${formatDatePayment(payment.appointment_date)}</td>
                <td style="font-weight:bold;color:#e74c3c">${formatCurrency(payment.fee_amount)}</td>
                <td>${payment.payment_method || '-'}</td>
                <td>${getPaymentStatusBadge(payment)}</td>
                <td>${formatDateTimePayment(payment.paid_at)}</td>
                <td>
                    <div class="action-btns">
                        <div class="btn-icon btn-edit" title="Xem chi tiết" onclick="viewPaymentDetail(${payment.id})">
                            <i class="ri-eye-line"></i>
                        </div>
                        ${showActions ? `
                            <div class="btn-icon btn-edit" style="background:#27ae60;color:white" title="Duyệt" onclick="openPaymentAction(${payment.id}, 'approve')">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                            <div class="btn-icon btn-delete" title="Từ chối" onclick="openPaymentAction(${payment.id}, 'reject')">
                                <i class="ri-close-circle-line"></i>
                            </div>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Filter payments
function filterPayments() {
    const searchInput = document.getElementById('paymentSearchInput');
    const statusFilter = document.getElementById('paymentStatusFilter');

    const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
    const statusValue = statusFilter ? statusFilter.value : 'all';

    let filtered = allPayments;

    // Search filter
    if (searchTerm) {
        filtered = filtered.filter(p =>
            (p.patient_name && p.patient_name.toLowerCase().includes(searchTerm)) ||
            (p.patient_phone && p.patient_phone.toLowerCase().includes(searchTerm)) ||
            (p.doctor_name && p.doctor_name.toLowerCase().includes(searchTerm))
        );
    }

    renderPayments(filtered);
}

// Get payment by ID
function getPaymentById(id) {
    return allPayments.find(p => p.id == id);
}

// View payment detail
function viewPaymentDetail(id) {
    const payment = getPaymentById(id);
    if (!payment) {
        alert('Không tìm thấy thông tin thanh toán!');
        return;
    }

    // Fill detail modal
    document.getElementById('paymentDetailId').value = payment.id;

    // Patient info
    document.getElementById('pdPatientName').textContent = payment.patient_name || '-';
    document.getElementById('pdPatientPhone').textContent = payment.patient_phone || '-';
    document.getElementById('pdPatientEmail').textContent = payment.patient_email || '-';

    // Appointment info
    document.getElementById('pdAppointmentId').textContent = '#LH' + String(payment.id).padStart(4, '0');
    document.getElementById('pdDoctorName').textContent = payment.doctor_name || '-';
    document.getElementById('pdClinicName').textContent = payment.clinic_name || '-';
    document.getElementById('pdAppointmentDate').textContent = formatDatePayment(payment.appointment_date);
    document.getElementById('pdTimeSlot').textContent = payment.time_slot || (payment.start_time + ' - ' + payment.end_time);

    const statusMap = {
        'pending': 'Chưa xác nhận',
        'confirmed': 'Đã xác nhận',
        'paid_confirmed': 'Đã thanh toán + Đã xác nhận',
        'cancelled': 'Đã hủy',
        'completed': 'Đã hoàn thành',
        'ended': 'Đã kết thúc',
        // Legacy
        'available': 'Còn trống',
        'booked': 'Đã đặt'
    };
    document.getElementById('pdAppointmentStatus').textContent = statusMap[payment.appointment_status] || payment.appointment_status;

    // Payment info
    document.getElementById('pdAmount').textContent = formatCurrency(payment.fee_amount);
    document.getElementById('pdPaymentMethod').textContent = payment.payment_method || '-';

    const paymentStatusMap = {
        'UNPAID': 'Chưa thanh toán',
        'PAID': 'Đã thanh toán',
        'REFUND_PENDING': 'Đang hoàn tiền',
        'REFUNDED': 'Đã hoàn tiền'
    };
    document.getElementById('pdPaymentStatus').textContent = paymentStatusMap[payment.payment_status] || payment.payment_status;
    document.getElementById('pdPaidAt').textContent = formatDateTimePayment(payment.paid_at);

    // Refund info
    if (payment.refund_status && payment.refund_status !== 'NONE') {
        document.getElementById('pdRefundStatusRow').style.display = 'flex';
        document.getElementById('pdRefundDateRow').style.display = 'flex';

        const refundStatusMap = {
            'REQUESTED': 'Đang yêu cầu',
            'APPROVED': 'Đã duyệt',
            'REJECTED': 'Đã từ chối'
        };
        document.getElementById('pdRefundStatus').textContent = refundStatusMap[payment.refund_status] || payment.refund_status;
        document.getElementById('pdRefundRequestedAt').textContent = formatDateTimePayment(payment.refund_requested_at);
    } else {
        document.getElementById('pdRefundStatusRow').style.display = 'none';
        document.getElementById('pdRefundDateRow').style.display = 'none';
    }

    // Notes
    document.getElementById('pdNotes').textContent = payment.notes || 'Không có ghi chú';

    // Rejection section (show if rejected)
    if (payment.refund_status === 'REJECTED') {
        document.getElementById('pdRejectionSection').style.display = 'block';
        document.getElementById('pdRejectionReason').textContent = 'Yêu cầu đã bị từ chối bởi quản trị viên.';
    } else {
        document.getElementById('pdRejectionSection').style.display = 'none';
    }

    openModal('paymentDetailModal');
}

// Open payment action modal (approve/reject)
function openPaymentAction(id, action) {
    const payment = getPaymentById(id);
    if (!payment) {
        alert('Không tìm thấy thông tin thanh toán!');
        return;
    }

    const isRefund = payment.refund_status === 'REQUESTED';

    document.getElementById('actionPaymentId').value = payment.id;
    document.getElementById('actionType').value = isRefund ? 'refund' : 'payment';
    document.getElementById('actionPatientName').textContent = payment.patient_name || '-';
    document.getElementById('actionPatientEmail').textContent = payment.patient_email || '-';
    document.getElementById('actionPatientPhone').textContent = payment.patient_phone || '-';
    document.getElementById('actionAmount').textContent = formatCurrency(payment.fee_amount);
    document.getElementById('actionRequestType').textContent = isRefund ? 'Yêu cầu hoàn tiền' : 'Xác nhận thanh toán';
    
    // Show/hide refund-specific info
    const refundReasonRow = document.getElementById('actionRefundReasonRow');
    const refundMethodRow = document.getElementById('actionRefundMethodRow');
    
    if (isRefund) {
        refundReasonRow.style.display = 'block';
        refundMethodRow.style.display = 'block';
        document.getElementById('actionRefundReason').textContent = payment.refund_reason || '-';
        // Show refund method, fallback to original payment method if not set
        document.getElementById('actionRefundMethod').textContent = payment.refund_payment_method || payment.payment_method || '-';
    } else {
        refundReasonRow.style.display = 'none';
        refundMethodRow.style.display = 'none';
    }

    if (action === 'reject') {
        document.getElementById('paymentActionTitle').textContent = 'Từ chối yêu cầu';
        document.getElementById('rejectReasonGroup').style.display = 'block';
        document.getElementById('btnApprovePayment').style.display = 'none';
        document.getElementById('btnRejectPayment').style.display = 'inline-block';
    } else {
        document.getElementById('paymentActionTitle').textContent = isRefund ? 'Duyệt hoàn tiền' : 'Xác nhận thanh toán';
        document.getElementById('rejectReasonGroup').style.display = 'none';
        document.getElementById('btnApprovePayment').style.display = 'inline-block';
        document.getElementById('btnRejectPayment').style.display = 'none';
    }

    document.getElementById('rejectReason').value = '';
    openModal('paymentActionModal');
}

// Submit payment action
async function submitPaymentAction(action) {
    const paymentId = document.getElementById('actionPaymentId').value;
    const type = document.getElementById('actionType').value;
    const reason = document.getElementById('rejectReason').value.trim();

    if (action === 'reject' && !reason) {
        alert('⚠️ Vui lòng nhập lý do từ chối!');
        return;
    }

    try {
        let response;
        if (action === 'approve') {
            response = await apiRequest(`/payments/${paymentId}/approve`, 'POST', { type });
        } else {
            response = await apiRequest(`/payments/${paymentId}/reject`, 'POST', { type, reason });
        }

        if (response.ok) {
            alert(`✅ ${action === 'approve' ? 'Đã duyệt' : 'Đã từ chối'} thành công!`);
            closeModal('paymentActionModal');
            await fetchPayments(currentPaymentTab);
            renderPayments();
        } else {
            const error = await response.json();
            alert('⚠️ Lỗi: ' + (error.message || 'Không thể xử lý yêu cầu'));
        }
    } catch (error) {
        console.error('Error submitting payment action:', error);
        alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Export payment functions
window.fetchPayments = fetchPayments;
window.renderPayments = renderPayments;
window.switchPaymentTab = switchPaymentTab;
window.filterPayments = filterPayments;
window.viewPaymentDetail = viewPaymentDetail;
window.openPaymentAction = openPaymentAction;
window.submitPaymentAction = submitPaymentAction;

// ============================================
// SPECIALIZATION MANAGEMENT
// ============================================

let allSpecializations = [];

// Fetch all specializations
async function fetchAllSpecializations() {
    try {
        const response = await apiRequest('/specializations/all', 'GET');
        const data = await response.json();

        if (data.success) {
            allSpecializations = data.data;
            renderSpecialties();
        } else {
            console.error('Failed to fetch specializations:', data.message);
        }
    } catch (error) {
        console.error('Error fetching specializations:', error);
    }
}

// Render specializations table
function renderSpecialties() {
    const tbody = document.getElementById('specialtyTableBody');
    if (!tbody) return;

    const searchTerm = document.getElementById('specialtySearch')?.value?.toLowerCase() || '';

    const filtered = allSpecializations.filter(spec => {
        const name = (spec.name || '').toLowerCase();
        const description = (spec.description || '').toLowerCase();
        return name.includes(searchTerm) || description.includes(searchTerm);
    });

    if (filtered.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px; color: #999;">
                    ${searchTerm ? 'Không tìm thấy chuyên khoa phù hợp' : 'Chưa có chuyên khoa nào'}
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filtered.map(spec => `
        <tr>
            <td style="width: 80px;">
                ${spec.image_url 
                    ? `<img src="${spec.image_url}" alt="${spec.name}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">`
                    : `<div style="width:60px;height:60px;border-radius:8px;background:#f0f0f0;display:flex;align-items:center;justify-content:center;"><i class="ri-image-line" style="font-size:24px;color:#ccc;"></i></div>`
                }
            </td>
            <td>${spec.name || ''}</td>
            <td>${spec.description || '<em style="color: #999;">Chưa có mô tả</em>'}</td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editSpecialty(${spec.id})" title="Sửa">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteSpecialtyById(${spec.id})" title="Xóa">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Filter specializations
function filterSpecialties() {
    renderSpecialties();
}

// Edit specialty - load data into modal
async function editSpecialty(id) {
    try {
        const response = await apiRequest(`/specializations/${id}`, 'GET');
        const data = await response.json();

        if (data.success && data.data) {
            const spec = data.data;
            document.getElementById('specialtyId').value = spec.id;
            document.getElementById('specialtyName').value = spec.name || '';
            document.getElementById('specialtyDescription').value = spec.description || '';
            document.getElementById('specialtyImageUrl').value = spec.image_url || '';
            
            // Update image preview
            const previewImg = document.getElementById('specialtyPreviewImg');
            const noImage = document.getElementById('specialtyNoImage');
            const clearBtn = document.getElementById('clearSpecialtyImageBtn');
            
            if (spec.image_url) {
                previewImg.src = spec.image_url;
                previewImg.style.display = 'block';
                noImage.style.display = 'none';
                clearBtn.style.display = 'inline-block';
            } else {
                previewImg.style.display = 'none';
                noImage.style.display = 'flex';
                clearBtn.style.display = 'none';
            }

            document.getElementById('specialtyModalTitle').textContent = 'Chỉnh sửa chuyên khoa';
            openModal('addSpecialtyModal');
        } else {
            alert('⚠️ Không tìm thấy thông tin chuyên specialty');
        }
    } catch (error) {
        console.error('Error loading specialty:', error);
        alert('⚠️ Có lỗi xảy ra khi tải thông tin');
    }
}

// Open add specialty modal
function openAddSpecialtyModal() {
    document.getElementById('specialtyForm').reset();
    document.getElementById('specialtyId').value = '';
    document.getElementById('specialtyImageUrl').value = '';
    document.getElementById('specialtyModalTitle').textContent = 'Thêm chuyên khoa mới';
    
    // Reset image preview
    const previewImg = document.getElementById('specialtyPreviewImg');
    const noImage = document.getElementById('specialtyNoImage');
    const clearBtn = document.getElementById('clearSpecialtyImageBtn');
    const fileInput = document.getElementById('specialtyImage');
    
    if (previewImg) previewImg.style.display = 'none';
    if (noImage) noImage.style.display = 'flex';
    if (clearBtn) clearBtn.style.display = 'none';
    if (fileInput) fileInput.value = '';
    window.specialtyImageFile = null;
    
    openModal('addSpecialtyModal');
}

// Preview specialty image
function previewSpecialtyImage(input) {
    if (input.files && input.files[0]) {
        window.specialtyImageFile = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const previewImg = document.getElementById('specialtyPreviewImg');
            const noImage = document.getElementById('specialtyNoImage');
            const clearBtn = document.getElementById('clearSpecialtyImageBtn');
            
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            noImage.style.display = 'none';
            clearBtn.style.display = 'inline-block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Clear specialty image
function clearSpecialtyImage() {
    const previewImg = document.getElementById('specialtyPreviewImg');
    const noImage = document.getElementById('specialtyNoImage');
    const clearBtn = document.getElementById('clearSpecialtyImageBtn');
    const fileInput = document.getElementById('specialtyImage');
    
    previewImg.src = '';
    previewImg.style.display = 'none';
    noImage.style.display = 'flex';
    clearBtn.style.display = 'none';
    fileInput.value = '';
    document.getElementById('specialtyImageUrl').value = '';
    window.specialtyImageFile = null;
}

// Upload specialty image
async function uploadSpecialtyImage() {
    if (!window.specialtyImageFile) return null;
    
    const formData = new FormData();
    formData.append('image', window.specialtyImageFile);
    
    try {
        const token = getAccessToken();
        const response = await fetch('/api/upload/specialization-image', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`
            },
            body: formData
        });
        
        if (response.ok) {
            const data = await response.json();
            window.specialtyImageFile = null;
            return data.image_url || data.url;
        } else {
            console.error('Failed to upload specialty image');
            return null;
        }
    } catch (error) {
        console.error('Error uploading specialty image:', error);
        return null;
    }
}

// Delete specialty
async function deleteSpecialtyById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa chuyên khoa này?')) return;

    try {
        const response = await apiRequest(`/specializations/${id}`, 'DELETE');
        const data = await response.json();

        if (data.success) {
            alert('✅ Đã xóa chuyên khoa thành công!');
            await fetchAllSpecializations();
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể xóa chuyên khoa'));
        }
    } catch (error) {
        console.error('Error deleting specialty:', error);
        alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Create specialty
async function createSpecialtyApi(specData) {
    try {
        const response = await apiRequest('/specializations', 'POST', specData);
        const data = await response.json();

        if (data.success) {
            await fetchAllSpecializations();
            return true;
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể tạo chuyên khoa'));
            return false;
        }
    } catch (error) {
        console.error('Error creating specialty:', error);
        throw error;
    }
}

// Update specialty
async function updateSpecialtyApi(id, specData) {
    try {
        const response = await apiRequest(`/specializations/${id}`, 'PUT', specData);
        const data = await response.json();

        if (data.success) {
            await fetchAllSpecializations();
            return true;
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể cập nhật chuyên khoa'));
            return false;
        }
    } catch (error) {
        console.error('Error updating specialty:', error);
        throw error;
    }
}

// Initialize specialty form handler
function initSpecialtyFormHandler() {
    const specialtyForm = document.getElementById('specialtyForm');
    if (!specialtyForm) return;

    specialtyForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const specialtyId = document.getElementById('specialtyId').value;
        const isEdit = !!specialtyId;

        // Upload image if selected
        let imageUrl = document.getElementById('specialtyImageUrl').value || null;
        if (window.specialtyImageFile) {
            const uploadedUrl = await uploadSpecialtyImage();
            if (uploadedUrl) {
                imageUrl = uploadedUrl;
            }
        }

        const specData = {
            name: document.getElementById('specialtyName').value.trim(),
            description: document.getElementById('specialtyDescription').value.trim(),
            image_url: imageUrl
        };

        if (!specData.name) {
            alert('⚠️ Vui lòng nhập tên chuyên khoa!');
            return;
        }

        try {
            let result;
            if (isEdit) {
                result = await updateSpecialtyApi(specialtyId, specData);
            } else {
                result = await createSpecialtyApi(specData);
            }

            if (result) {
                alert(`✅ Đã ${isEdit ? 'cập nhật' : 'thêm'} chuyên khoa thành công!`);
                closeModal('addSpecialtyModal');
                specialtyForm.reset();
                document.getElementById('specialtyId').value = '';
                window.specialtyImageFile = null;
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
}

// Export specialty functions
window.fetchAllSpecializations = fetchAllSpecializations;
window.renderSpecialties = renderSpecialties;
window.filterSpecialties = filterSpecialties;
window.editSpecialty = editSpecialty;
window.openAddSpecialtyModal = openAddSpecialtyModal;
window.deleteSpecialtyById = deleteSpecialtyById;
window.initSpecialtyFormHandler = initSpecialtyFormHandler;
window.previewSpecialtyImage = previewSpecialtyImage;
window.clearSpecialtyImage = clearSpecialtyImage;
window.uploadSpecialtyImage = uploadSpecialtyImage;

// ============================================
// SERVICE MANAGEMENT
// ============================================

let allServices = [];

// Fetch all services
async function fetchAllServices() {
    try {
        const response = await apiRequest('/services', 'GET');
        const data = await response.json();

        if (data.success) {
            allServices = data.data;
            renderServices();
        } else {
            console.error('Failed to fetch services:', data.message);
        }
    } catch (error) {
        console.error('Error fetching services:', error);
    }
}

// Render services table
function renderServices() {
    const tbody = document.getElementById('serviceTableBody');
    if (!tbody) return;

    // Populate specialization filter dropdown
    populateServiceSpecFilter();

    const searchTerm = document.getElementById('serviceSearch')?.value?.toLowerCase() || '';
    const statusFilter = document.getElementById('serviceStatusFilter')?.value || 'all';
    const specFilter = document.getElementById('serviceSpecFilter')?.value || 'all';

    const filtered = allServices.filter(service => {
        const name = (service.name || '').toLowerCase();
        const description = (service.description || '').toLowerCase();
        const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
        
        let matchesStatus = true;
        if (statusFilter === 'active') {
            matchesStatus = service.is_active === 1 || service.is_active === true;
        } else if (statusFilter === 'inactive') {
            matchesStatus = service.is_active === 0 || service.is_active === false;
        }

        let matchesSpec = true;
        if (specFilter !== 'all') {
            matchesSpec = service.specialization_id == specFilter;
        }
        
        return matchesSearch && matchesStatus && matchesSpec;
    });

    if (filtered.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                    ${searchTerm || statusFilter !== 'all' || specFilter !== 'all' ? 'Không tìm thấy dịch vụ phù hợp' : 'Chưa có dịch vụ nào'}
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = filtered.map(service => {
        const specName = service.specialization ? service.specialization.name : (service.specialization_name || '<em style="color: #999;">Chưa phân loại</em>');
        const avatarUrl = service.avatar_url || '';
        const avatarHtml = avatarUrl 
            ? `<img src="${avatarUrl}" alt="${service.name || 'Service'}" 
                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div style="display: none; width: 50px; height: 50px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); align-items: center; justify-content: center; color: white; font-size: 20px;">
                   <i class="ri-service-line"></i>
               </div>`
            : `<div style="width: 50px; height: 50px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 20px;">
                   <i class="ri-service-line"></i>
               </div>`;
        return `
        <tr>
            <td>${service.id}</td>
            <td>${avatarHtml}</td>
            <td>${specName}</td>
            <td><strong>${service.name || ''}</strong></td>
            <td>${service.price ? formatCurrencyVND(service.price) : '<em style="color: #999;">Chưa đặt giá</em>'}</td>
            <td>${service.duration_minutes || 0} phút</td>
            <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${service.description || ''}">
                ${service.description || '<em style="color: #999;">Chưa có mô tả</em>'}
            </td>
            <td>
                <span class="status-badge ${service.is_active ? 'active' : 'inactive'}">
                    ${service.is_active ? 'Hoạt động' : 'Ngừng'}
                </span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="action-btn edit" onclick="editService(${service.id})" title="Sửa">
                        <i class="ri-edit-line"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteServiceById(${service.id})" title="Xóa">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
    }).join('');
}

// Populate service specialization filter dropdown
function populateServiceSpecFilter() {
    const select = document.getElementById('serviceSpecFilter');
    if (!select || select.options.length > 1) return; // Already populated

    allSpecializations.forEach(spec => {
        const option = document.createElement('option');
        option.value = spec.id;
        option.textContent = spec.name;
        select.appendChild(option);
    });
}

// Filter services
function filterServices() {
    renderServices();
}

// Edit service - load data into modal
async function editService(id) {
    try {
        const response = await apiRequest(`/services/${id}`, 'GET');
        const data = await response.json();

        if (data.success && data.data) {
            const service = data.data;
            document.getElementById('serviceId').value = service.id;

            // Populate specialization dropdown
            populateServiceModalSpecDropdown();

            document.getElementById('serviceSpecialization').value = service.specialization_id || '';
            document.getElementById('serviceName').value = service.name || '';
            document.getElementById('servicePrice').value = service.price || 0;
            document.getElementById('serviceDuration').value = service.duration_minutes || 30;
            document.getElementById('serviceDescription').value = service.description || '';
            document.getElementById('serviceIsActive').value = service.is_active ? '1' : '0';
            
            // Load benefits
            document.getElementById('serviceBenefit1').value = service.benefit1 || '';
            document.getElementById('serviceBenefit2').value = service.benefit2 || '';
            document.getElementById('serviceBenefit3').value = service.benefit3 || '';
            document.getElementById('serviceBenefit4').value = service.benefit4 || '';
            
            // Load avatar
            resetServiceAvatarPreview();
            const normalizedAvatar = normalizeAvatarUrl(service.avatar_url || service.image_url);
            if (normalizedAvatar) {
                const preview = document.getElementById('serviceAvatarPreview');
                if (preview) {
                    preview.innerHTML = `<img src="${normalizedAvatar}" style="width:100%;height:100%;object-fit:cover;">`;
                }
                document.getElementById('serviceAvatarUrl').value = normalizedAvatar;
            }

            document.getElementById('serviceModalTitle').textContent = 'Chỉnh sửa dịch vụ';
            openModal('addServiceModal');
        } else {
            alert('⚠️ Không tìm thấy thông tin dịch vụ');
        }
    } catch (error) {
        console.error('Error loading service:', error);
        alert('⚠️ Có lỗi xảy ra khi tải thông tin');
    }
}

// Populate specialization dropdown in service modal
function populateServiceModalSpecDropdown() {
    const select = document.getElementById('serviceSpecialization');
    if (!select) return;

    // Clear existing options except first
    select.innerHTML = '<option value="">-- Chọn chuyên khoa --</option>';

    allSpecializations.forEach(spec => {
        const option = document.createElement('option');
        option.value = spec.id;
        option.textContent = spec.name;
        select.appendChild(option);
    });
}

// Store service avatar file
let serviceAvatarFile = null;

// Preview service avatar
function previewServiceAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        serviceAvatarFile = file;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('serviceAvatarPreview');
            if (preview) {
                preview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            }
        };
        reader.readAsDataURL(file);
    }
}

// Upload service avatar
async function uploadServiceAvatar(file) {
    try {
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('type', 'service');
        
        const response = await apiRequest('/upload/avatar', 'POST', formData);
        const data = await response.json();
        
        // API returns {avatar_url: '...'} directly
        if (data.avatar_url) {
            return data.avatar_url;
        }
        console.error('Failed to upload service avatar:', data);
        return null;
    } catch (error) {
        console.error('Error uploading service avatar:', error);
        return null;
    }
}

// Reset service avatar preview
function resetServiceAvatarPreview() {
    serviceAvatarFile = null;
    const preview = document.getElementById('serviceAvatarPreview');
    if (preview) {
        preview.innerHTML = '<i class="ri-service-line" style="font-size: 48px; color: white;"></i>';
    }
    const avatarUrl = document.getElementById('serviceAvatarUrl');
    if (avatarUrl) avatarUrl.value = '';
    const avatarInput = document.getElementById('serviceAvatarInput');
    if (avatarInput) avatarInput.value = '';
}

window.previewServiceAvatar = previewServiceAvatar;
window.resetServiceAvatarPreview = resetServiceAvatarPreview;

// Open add service modal
function openAddServiceModal() {
    document.getElementById('serviceForm').reset();
    document.getElementById('serviceId').value = '';
    
    // Reset avatar preview
    resetServiceAvatarPreview();

    // Populate specialization dropdown
    populateServiceModalSpecDropdown();

    document.getElementById('serviceSpecialization').value = '';
    document.getElementById('servicePrice').value = '';
    document.getElementById('serviceDuration').value = '30';
    document.getElementById('serviceIsActive').value = '1';
    document.getElementById('serviceBenefit1').value = '';
    document.getElementById('serviceBenefit2').value = '';
    document.getElementById('serviceBenefit3').value = '';
    document.getElementById('serviceBenefit4').value = '';
    document.getElementById('serviceModalTitle').textContent = 'Thêm dịch vụ mới';
    openModal('addServiceModal');
}

// Delete service
async function deleteServiceById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')) return;

    try {
        const response = await apiRequest(`/services/${id}`, 'DELETE');
        const data = await response.json();

        if (data.success) {
            alert('✅ Đã xóa dịch vụ thành công!');
            await fetchAllServices();
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể xóa dịch vụ'));
        }
    } catch (error) {
        console.error('Error deleting service:', error);
        alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Create service
async function createServiceApi(serviceData) {
    try {
        // Upload avatar if present
        if (serviceAvatarFile) {
            const avatarUrl = await uploadServiceAvatar(serviceAvatarFile);
            if (avatarUrl) {
                serviceData.avatar_url = avatarUrl;
            }
        }
        
        const response = await apiRequest('/services', 'POST', serviceData);
        const data = await response.json();

        if (data.success) {
            serviceAvatarFile = null;
            await fetchAllServices();
            return true;
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể tạo dịch vụ'));
            return false;
        }
    } catch (error) {
        console.error('Error creating service:', error);
        throw error;
    }
}

// Update service
async function updateServiceApi(id, serviceData) {
    try {
        // Upload avatar if present
        if (serviceAvatarFile) {
            const avatarUrl = await uploadServiceAvatar(serviceAvatarFile);
            if (avatarUrl) {
                serviceData.avatar_url = avatarUrl;
            }
        }
        
        const response = await apiRequest(`/services/${id}`, 'PUT', serviceData);
        const data = await response.json();

        if (data.success) {
            serviceAvatarFile = null;
            await fetchAllServices();
            return true;
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể cập nhật dịch vụ'));
            return false;
        }
    } catch (error) {
        console.error('Error updating service:', error);
        throw error;
    }
}

// Initialize service form handler
function initServiceFormHandler() {
    const serviceForm = document.getElementById('serviceForm');
    if (!serviceForm) return;

    serviceForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const serviceId = document.getElementById('serviceId').value;
        const isEdit = !!serviceId;

        const specializationId = document.getElementById('serviceSpecialization').value;
        if (!specializationId) {
            alert('⚠️ Vui lòng chọn chuyên khoa!');
            return;
        }

        const serviceData = {
            specialization_id: parseInt(specializationId),
            name: document.getElementById('serviceName').value.trim(),
            price: parseInt(document.getElementById('servicePrice').value) || 0,
            duration_minutes: parseInt(document.getElementById('serviceDuration').value) || 30,
            description: document.getElementById('serviceDescription').value.trim(),
            is_active: document.getElementById('serviceIsActive').value === '1',
            benefit1: document.getElementById('serviceBenefit1').value.trim() || null,
            benefit2: document.getElementById('serviceBenefit2').value.trim() || null,
            benefit3: document.getElementById('serviceBenefit3').value.trim() || null,
            benefit4: document.getElementById('serviceBenefit4').value.trim() || null
        };

        if (!serviceData.name) {
            alert('⚠️ Vui lòng nhập tên dịch vụ!');
            return;
        }

        if (!serviceData.price || serviceData.price <= 0) {
            alert('⚠️ Vui lòng nhập giá dịch vụ!');
            return;
        }

        if (!serviceData.duration_minutes || serviceData.duration_minutes <= 0) {
            alert('⚠️ Vui lòng nhập thời gian thực hiện!');
            return;
        }

        try {
            let result;
            if (isEdit) {
                result = await updateServiceApi(serviceId, serviceData);
            } else {
                result = await createServiceApi(serviceData);
            }

            if (result) {
                alert(`✅ Đã ${isEdit ? 'cập nhật' : 'thêm'} dịch vụ thành công!`);
                closeModal('addServiceModal');
                serviceForm.reset();
                document.getElementById('serviceId').value = '';
            }
        } catch (error) {
            console.error('Form submission error:', error);
            alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
}

// Export service functions
window.fetchAllServices = fetchAllServices;
window.renderServices = renderServices;
window.filterServices = filterServices;
window.editService = editService;
window.openAddServiceModal = openAddServiceModal;
window.deleteServiceById = deleteServiceById;
window.initServiceFormHandler = initServiceFormHandler;
window.populateServiceSpecFilter = populateServiceSpecFilter;
window.populateServiceModalSpecDropdown = populateServiceModalSpecDropdown;

// ============================================
// REVIEW MANAGEMENT
// ============================================

let allReviews = [];
let currentAverageRating = null;
let doctorSuggestionsCache = [];
let selectedDoctorForFilter = { id: 'all', name: '' };

// Fetch reviews with filters
async function fetchReviews() {
    try {
        const rating = document.getElementById('reviewRatingFilter')?.value || 'all';
        const doctorId = document.getElementById('selectedDoctorId')?.value || 'all';
        const search = document.getElementById('reviewSearchInput')?.value || '';

        let url = '/reviews?';
        if (rating !== 'all') url += `rating=${rating}&`;
        if (doctorId !== 'all') url += `doctor_id=${doctorId}&`;
        if (search) url += `search=${encodeURIComponent(search)}&`;

        const response = await apiRequest(url, 'GET');
        const data = await response.json();

        if (data.success) {
            allReviews = data.data;
            currentAverageRating = data.average_rating;
            renderReviews();
            updateAverageRatingDisplay();
        } else {
            console.error('Failed to fetch reviews:', data.message);
        }
    } catch (error) {
        console.error('Error fetching reviews:', error);
    }
}

// Update average rating display
function updateAverageRatingDisplay() {
    const display = document.getElementById('averageRatingValue');
    const rating = document.getElementById('reviewRatingFilter')?.value || 'all';

    if (!display) return;

    if (rating !== 'all') {
        // When filtering by specific rating, show "?"
        display.textContent = '?';
        display.title = 'Đang lọc theo số sao cụ thể';
    } else if (currentAverageRating !== null) {
        display.textContent = `${currentAverageRating} / 5`;
        display.title = `Điểm trung bình: ${currentAverageRating} sao`;
    } else {
        display.textContent = '--';
        display.title = 'Chưa có đánh giá';
    }
}

// Render reviews table
function renderReviews() {
    const tbody = document.getElementById('reviewTableBody');
    if (!tbody) return;

    if (allReviews.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #999;">
                    Không tìm thấy đánh giá nào
                </td>
            </tr>
        `;
        return;
    }

    // Calculate doctor average ratings from allReviews
    const doctorRatings = {};
    allReviews.forEach(review => {
        const doctorId = review.doctor_id;
        if (doctorId) {
            if (!doctorRatings[doctorId]) {
                doctorRatings[doctorId] = { total: 0, count: 0 };
            }
            doctorRatings[doctorId].total += review.rating;
            doctorRatings[doctorId].count += 1;
        }
    });

    tbody.innerHTML = allReviews.map(review => {
        const stars = renderStars(review.rating);
        const date = review.created_at ? new Date(review.created_at).toLocaleDateString('vi-VN') : '';
        
        // Calculate doctor's average rating
        let doctorAvg = '--';
        if (review.doctor_id && doctorRatings[review.doctor_id]) {
            const dr = doctorRatings[review.doctor_id];
            doctorAvg = (dr.total / dr.count).toFixed(1);
        }

        return `
            <tr>
                <td>${review.id}</td>
                <td>${review.appointment_id || '<em style="color:#999;">N/A</em>'}</td>
                <td>${review.patient_name || ''}</td>
                <td>${review.doctor_name || ''}</td>
                <td>
                    <span style="display: inline-flex; align-items: center; gap: 4px; background: linear-gradient(135deg, #ffd700, #ffb347); padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 12px;">
                        <i class="ri-star-fill" style="color: #ff6b35;"></i>
                        ${doctorAvg}
                    </span>
                </td>
                <td>${stars}</td>
                <td style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${review.comment || ''}">${review.comment || '<em style="color:#999;">Không có nội dung</em>'}</td>
                <td>${date}</td>
                <td>
                    <div class="action-buttons">
                        <button class="action-btn view" onclick="viewReviewDetail(${review.id})" title="Xem chi tiết">
                            <i class="ri-eye-line"></i>
                        </button>
                        <button class="action-btn delete" onclick="deleteReviewById(${review.id})" title="Xóa">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Render star rating
function renderStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="ri-star-fill" style="color: #ffc107;"></i>';
        } else {
            stars += '<i class="ri-star-line" style="color: #ddd;"></i>';
        }
    }
    return stars;
}

// Filter reviews
function filterReviews() {
    fetchReviews();
}

// Search doctor suggestions with debounce
let doctorSearchTimeout = null;
async function searchDoctorSuggestions(query) {
    clearTimeout(doctorSearchTimeout);

    doctorSearchTimeout = setTimeout(async () => {
        try {
            const response = await apiRequest(`/doctors/search?q=${encodeURIComponent(query)}`, 'GET');
            const data = await response.json();

            if (data.success) {
                doctorSuggestionsCache = data.data;
                renderDoctorSuggestions(query);
            }
        } catch (error) {
            console.error('Error searching doctors:', error);
        }
    }, 300);
}

// Show doctor suggestions dropdown
function showDoctorSuggestions() {
    const dropdown = document.getElementById('doctorSuggestions');
    if (dropdown && doctorSuggestionsCache.length > 0) {
        renderDoctorSuggestions(document.getElementById('doctorSearchInput')?.value || '');
        dropdown.style.display = 'block';
    } else {
        // Load all doctors initially
        searchDoctorSuggestions('');
    }
}

// Render doctor suggestions
function renderDoctorSuggestions(query) {
    const dropdown = document.getElementById('doctorSuggestions');
    if (!dropdown) return;

    // Add "All doctors" option at top
    let html = `
        <div class="suggestion-item ${selectedDoctorForFilter.id === 'all' ? 'selected' : ''}"
             onclick="selectDoctor('all', 'Tất cả bác sĩ')"
             style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px;">
            <i class="ri-group-line" style="color: #667eea;"></i>
            <span>Tất cả bác sĩ</span>
        </div>
    `;

    if (doctorSuggestionsCache.length === 0 && query) {
        html += `
            <div style="padding: 15px; text-align: center; color: #999;">
                <i class="ri-search-line"></i> Không tìm thấy bác sĩ
            </div>
        `;
    } else {
        doctorSuggestionsCache.forEach(doctor => {
            const isSelected = selectedDoctorForFilter.id === doctor.id;
            const highlightedName = query ?
                doctor.full_name.replace(new RegExp(`(${query})`, 'gi'), '<strong style="color: #667eea;">$1</strong>') :
                doctor.full_name;

            html += `
                <div class="suggestion-item ${isSelected ? 'selected' : ''}"
                     onclick="selectDoctor(${doctor.id}, '${doctor.full_name.replace(/'/g, "\\'")}')"
                     style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; ${isSelected ? 'background: #f0f4ff;' : ''}"
                     onmouseover="this.style.background='#f5f5f5'"
                     onmouseout="this.style.background='${isSelected ? '#f0f4ff' : 'white'}'">
                    <i class="ri-user-heart-line" style="color: #28a745;"></i>
                    <span>${highlightedName}</span>
                    ${isSelected ? '<i class="ri-check-line" style="margin-left: auto; color: #667eea;"></i>' : ''}
                </div>
            `;
        });
    }

    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
}

// Select doctor from suggestions
function selectDoctor(id, name) {
    selectedDoctorForFilter = { id, name };
    document.getElementById('selectedDoctorId').value = id;
    document.getElementById('doctorSearchInput').value = id === 'all' ? '' : name;
    document.getElementById('doctorSuggestions').style.display = 'none';

    // Fetch reviews with new filter
    fetchReviews();
}

// Hide suggestions when clicking outside
document.addEventListener('click', function(e) {
    const container = document.querySelector('.doctor-search-container');
    const dropdown = document.getElementById('doctorSuggestions');
    if (container && dropdown && !container.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// View review detail
async function viewReviewDetail(id) {
    try {
        const response = await apiRequest(`/reviews/${id}`, 'GET');
        const data = await response.json();

        if (data.success && data.data) {
            const review = data.data;
            const stars = renderStars(review.rating);
            const date = review.created_at ? new Date(review.created_at).toLocaleString('vi-VN') : '';

            alert(`📝 Chi tiết đánh giá #${review.id}\n\n` +
                  `👤 Bệnh nhân: ${review.patient_name}\n` +
                  `👨‍⚕️ Bác sĩ: ${review.doctor_name}\n` +
                  `⭐ Đánh giá: ${review.rating}/5 sao\n` +
                  `📅 Ngày: ${date}\n` +
                  `📋 ID Lịch khám: ${review.appointment_id || 'N/A'}\n\n` +
                  `💬 Nội dung:\n${review.comment || '(Không có nội dung)'}`);
        } else {
            alert('⚠️ Không tìm thấy thông tin đánh giá');
        }
    } catch (error) {
        console.error('Error loading review:', error);
        alert('⚠️ Có lỗi xảy ra khi tải thông tin');
    }
}

// Delete review
async function deleteReviewById(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) return;

    try {
        const response = await apiRequest(`/reviews/${id}`, 'DELETE');
        const data = await response.json();

        if (data.success) {
            alert('✅ Đã xóa đánh giá thành công!');
            await fetchReviews();
        } else {
            alert('⚠️ Lỗi: ' + (data.message || 'Không thể xóa đánh giá'));
        }
    } catch (error) {
        console.error('Error deleting review:', error);
        alert('⚠️ Có lỗi xảy ra. Vui lòng thử lại.');
    }
}

// Export review functions
window.fetchReviews = fetchReviews;
window.renderReviews = renderReviews;
window.filterReviews = filterReviews;
window.searchDoctorSuggestions = searchDoctorSuggestions;
window.showDoctorSuggestions = showDoctorSuggestions;
window.selectDoctor = selectDoctor;
window.viewReviewDetail = viewReviewDetail;
window.deleteReviewById = deleteReviewById;

// ============================================
// DASHBOARD & REPORTS
// ============================================

let dashboardStats = null;
let reportStats = null;
let currentReportPeriod = 'month';
let currentChartType = 'appointments';
let chartInstance = null;

// Format currency
function formatCurrencyVND(amount) {
    if (!amount) return '0đ';
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

// Format short currency (1.5M, 458M, etc)
function formatShortCurrency(amount) {
    if (!amount) return '0đ';
    if (amount >= 1000000000) {
        return (amount / 1000000000).toFixed(1) + 'Tỷ';
    }
    if (amount >= 1000000) {
        return Math.round(amount / 1000000) + 'M';
    }
    if (amount >= 1000) {
        return Math.round(amount / 1000) + 'K';
    }
    return amount + 'đ';
}

// Fetch dashboard stats
async function fetchDashboardStats() {
    try {
        const response = await apiRequest('/dashboard/stats', 'GET');
        const data = await response.json();

        if (data.success) {
            dashboardStats = data.data;
            renderDashboard();
        } else {
            console.error('Failed to fetch dashboard stats:', data.message);
        }
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
    }
}

// Render dashboard
function renderDashboard() {
    if (!dashboardStats) return;

    const d = dashboardStats;

    // Update stat cards
    updateStatCard('totalPatients', d.total_patients?.toLocaleString() || '0', `${d.patient_change >= 0 ? '+' : ''}${d.patient_change}% so với tháng trước`);
    updateStatCard('activeDoctors', d.active_doctors?.toString() || '0', `${d.doctor_change >= 0 ? '+' : ''}${d.doctor_change}% so với tháng trước`);
    updateStatCard('appointmentsThisMonth', d.appointments_this_month?.toLocaleString() || '0', `${d.appointment_change >= 0 ? '+' : ''}${d.appointment_change}% so với kỳ trước`);
    updateStatCard('revenueThisMonth', formatShortCurrency(d.revenue_this_month), `${d.revenue_change >= 0 ? '+' : ''}${d.revenue_change}% so với tháng trước`);

    // Render recent appointments
    renderRecentAppointments(d.recent_appointments);

    // Render recent activities
    renderRecentActivities(d.recent_activities);
}

// Update stat card helper
function updateStatCard(id, value, changeText) {
    const valueEl = document.getElementById(id);
    const changeEl = document.getElementById(id + 'Change');

    if (valueEl) valueEl.textContent = value;
    if (changeEl) {
        changeEl.innerHTML = `<i class="ri-arrow-up-line"></i> ${changeText}`;
        // Check if negative change
        if (changeText.includes('-')) {
            changeEl.innerHTML = `<i class="ri-arrow-down-line"></i> ${changeText}`;
            changeEl.style.color = '#e74c3c';
        } else {
            changeEl.style.color = '#27ae60';
        }
    }
}

// Render recent appointments table
function renderRecentAppointments(appointments) {
    const tbody = document.getElementById('recentAppointmentsBody');
    if (!tbody) return;

    if (!appointments || appointments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #999;">Chưa có dữ liệu</td></tr>';
        return;
    }

    tbody.innerHTML = appointments.map(apt => {
        const statusColors = {
            'pending': '#f39c12',
            'confirmed': '#3498db',
            'paid_confirmed': '#27ae60',
            'cancelled': '#e74c3c',
            'completed': '#2980b9',
            'ended': '#7f8c8d',
            // Legacy
            'available': '#f39c12',
            'booked': '#3498db'
        };
        const statusLabels = {
            'pending': 'Chưa xác nhận',
            'confirmed': 'Đã xác nhận',
            'paid_confirmed': 'Đã TT + Xác nhận',
            'cancelled': 'Đã hủy',
            'completed': 'Đã hoàn thành',
            'ended': 'Đã kết thúc',
            // Legacy
            'available': 'Còn trống',
            'booked': 'Đã đặt'
        };

        return `
            <tr>
                <td>${apt.patient_name || 'N/A'}</td>
                <td>${apt.doctor_name || 'N/A'}</td>
                <td>${apt.start_time || ''}</td>
                <td><span style="color: ${statusColors[apt.status] || '#999'}; font-weight: 500;">${statusLabels[apt.status] || apt.status}</span></td>
            </tr>
        `;
    }).join('');
}

// Render recent activities
function renderRecentActivities(activities) {
    const container = document.getElementById('recentActivitiesBody');
    if (!container) return;

    if (!activities || activities.length === 0) {
        container.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Chưa có dữ liệu</div>';
        return;
    }

    container.innerHTML = activities.map(activity => {
        const timeAgo = getTimeAgo(activity.created_at);
        const icon = activity.type === 'PAYMENT' ? 'ri-money-dollar-circle-line' :
                     activity.type === 'APPOINTMENT' ? 'ri-calendar-check-line' : 'ri-notification-3-line';

        return `
            <div class="activity-item" style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 0; border-bottom: 1px solid #eee;">
                <i class="${icon}" style="color: #667eea; font-size: 18px; margin-top: 2px;"></i>
                <div style="flex: 1;">
                    <div style="font-weight: 500; font-size: 13px;">${activity.title}</div>
                    <div style="color: #999; font-size: 12px;">${timeAgo}</div>
                </div>
            </div>
        `;
    }).join('');
}

// Get time ago string
function getTimeAgo(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'Vừa xong';
    if (diffMins < 60) return `${diffMins} phút trước`;
    if (diffHours < 24) return `${diffHours} giờ trước`;
    if (diffDays < 7) return `${diffDays} ngày trước`;
    return date.toLocaleDateString('vi-VN');
}

// ============================================
// REPORTS
// ============================================

// Fetch report stats
async function fetchReportStats(period = 'month') {
    try {
        currentReportPeriod = period;
        const response = await apiRequest(`/reports/stats?period=${period}`, 'GET');
        const data = await response.json();

        if (data.success) {
            reportStats = data.data;
            renderReports();
        } else {
            console.error('Failed to fetch report stats:', data.message);
        }
    } catch (error) {
        console.error('Error fetching report stats:', error);
    }
}

// Render reports page
function renderReports() {
    if (!reportStats) return;

    const r = reportStats;

    // Update period label
    const periodLabel = document.getElementById('reportPeriodLabel');
    if (periodLabel) periodLabel.textContent = r.period_label;

    // Update stat cards
    updateReportStatCard('reportAppointments', r.total_appointments?.toLocaleString() || '0', r.appointment_change);
    updateReportStatCard('reportRevenue', formatCurrencyVND(r.total_revenue), r.revenue_change);
    updateReportStatCard('reportNewUsers', r.new_users?.toLocaleString() || '0', r.user_change);
    updateReportStatCard('reportRating', r.avg_rating?.toString() || '0', null, `Từ ${r.total_reviews} đánh giá`);

    // Render appointments by status table
    renderAppointmentsByStatus(r.appointments_by_status);

    // Render chart
    renderReportChart(currentChartType);
}

// Update report stat card
function updateReportStatCard(id, value, change, subtext = null) {
    const valueEl = document.getElementById(id);
    const changeEl = document.getElementById(id + 'Change');
    const subtextEl = document.getElementById(id + 'Subtext');

    if (valueEl) valueEl.textContent = value;
    if (changeEl && change !== null) {
        const isPositive = change >= 0;
        changeEl.innerHTML = `<i class="ri-arrow-${isPositive ? 'up' : 'down'}-line"></i> ${isPositive ? '+' : ''}${change}% so với kỳ trước`;
        changeEl.style.color = isPositive ? '#27ae60' : '#e74c3c';
    }
    if (subtextEl && subtext) {
        subtextEl.innerHTML = `<i class="ri-star-fill" style="color: #ffc107;"></i> ${subtext}`;
    }
}

// Render appointments by status
function renderAppointmentsByStatus(statusData) {
    const tbody = document.getElementById('appointmentStatusBody');
    if (!tbody) return;

    const statusLabels = {
        'pending': 'Chưa xác nhận',
        'confirmed': 'Đã xác nhận',
        'paid_confirmed': 'Đã TT + Xác nhận',
        'cancelled': 'Đã hủy',
        'completed': 'Đã hoàn thành',
        'ended': 'Đã kết thúc',
        // Legacy
        'available': 'Còn trống',
        'booked': 'Đã đặt'
    };
    const statusColors = {
        'pending': '#f39c12',
        'confirmed': '#3498db',
        'paid_confirmed': '#27ae60',
        'cancelled': '#e74c3c',
        'completed': '#2980b9',
        'ended': '#7f8c8d',
        // Legacy
        'available': '#f39c12',
        'booked': '#3498db'
    };

    const total = Object.values(statusData || {}).reduce((a, b) => a + b, 0);

    if (total === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #999;">Chưa có dữ liệu</td></tr>';
        return;
    }

    tbody.innerHTML = Object.entries(statusLabels).map(([key, label]) => {
        const count = statusData[key] || 0;
        const percent = total > 0 ? ((count / total) * 100).toFixed(1) : 0;

        return `
            <tr>
                <td><span style="color: ${statusColors[key]}; font-weight: 500;">${label}</span></td>
                <td>${count.toLocaleString()}</td>
                <td>${percent}%</td>
                <td>
                    <div style="background: #eee; border-radius: 10px; height: 8px; width: 100px;">
                        <div style="background: ${statusColors[key]}; border-radius: 10px; height: 8px; width: ${percent}%;"></div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Switch chart type
function switchChartType(type) {
    currentChartType = type;

    // Update button states
    document.querySelectorAll('.chart-type-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.type === type) {
            btn.classList.add('active');
        }
    });

    renderReportChart(type);
}

// Render chart using Chart.js
function renderReportChart(type) {
    if (!reportStats) return;

    const canvas = document.getElementById('reportChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    // Destroy existing chart
    if (chartInstance) {
        chartInstance.destroy();
    }

    const data = reportStats.weekly_data || [];
    const labels = data.map(d => d.week);

    let chartData, chartLabel, chartColor;

    switch(type) {
        case 'appointments':
            chartData = data.map(d => d.appointments);
            chartLabel = 'Lịch hẹn';
            chartColor = '#667eea';
            break;
        case 'revenue':
            chartData = data.map(d => d.revenue / 1000000); // Convert to millions
            chartLabel = 'Doanh thu (triệu đồng)';
            chartColor = '#27ae60';
            break;
        case 'users':
            chartData = data.map(d => d.users);
            chartLabel = 'Người dùng mới';
            chartColor = '#e74c3c';
            break;
        case 'reviews':
            chartData = data.map(d => d.reviews);
            chartLabel = 'Đánh giá';
            chartColor = '#f39c12';
            break;
        default:
            chartData = data.map(d => d.appointments);
            chartLabel = 'Lịch hẹn';
            chartColor = '#667eea';
    }

    chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: chartLabel,
                data: chartData,
                borderColor: chartColor,
                backgroundColor: chartColor + '20',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: chartColor,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Biểu đồ ' + chartLabel.toLowerCase() + ' theo thời gian'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#eee'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Change report period
function changeReportPeriod(period) {
    const select = document.getElementById('reportPeriodSelect');
    if (select) select.value = period;
    fetchReportStats(period);
}

// Refresh reports
function refreshReports() {
    fetchReportStats(currentReportPeriod);
}

// Export report (placeholder)
function exportReport() {
    alert('📊 Tính năng xuất báo cáo đang được phát triển!');
}

// Export dashboard & report functions
window.fetchDashboardStats = fetchDashboardStats;
window.renderDashboard = renderDashboard;
window.fetchReportStats = fetchReportStats;
window.renderReports = renderReports;
window.switchChartType = switchChartType;
window.changeReportPeriod = changeReportPeriod;
window.refreshReports = refreshReports;
window.exportReport = exportReport;

// ============================================
// APPOINTMENTS MANAGEMENT
// ============================================

// Appointments data storage
let appointmentsData = [];
let currentAppointmentStatus = 'all';
let appointmentDoctorSuggestions = [];
let appointmentPatientSuggestions = [];

// Fetch all appointments
async function fetchAppointments(status = 'all') {
    currentAppointmentStatus = status;

    try {
        let url = '/appointments';
        const params = new URLSearchParams();

        if (status !== 'all') {
            params.append('status', status);
        }

        const searchInput = document.getElementById('appointmentSearchInput');
        if (searchInput && searchInput.value.trim()) {
            params.append('search', searchInput.value.trim());
        }

        const dateFilter = document.getElementById('appointmentDateFilter');
        if (dateFilter && dateFilter.value) {
            params.append('date', dateFilter.value);
        }

        if (params.toString()) {
            url += '?' + params.toString();
        }

        const response = await apiRequest(url);

        if (response.ok) {
            const result = await response.json();
            appointmentsData = result.data || result || [];
            renderAppointments(appointmentsData);
        } else {
            console.error('Failed to fetch appointments:', response.status);
            appointmentsData = [];
            renderAppointments([]);
        }
    } catch (error) {
        console.error('Error fetching appointments:', error);
        appointmentsData = [];
        renderAppointments([]);
    }
}

// Render appointments table
function renderAppointments(appointments) {
    const tbody = document.getElementById('appointmentTableBody');
    if (!tbody) return;

    if (!appointments || appointments.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align:center;padding:40px;color:#666;">
                    <i class="ri-calendar-line" style="font-size:48px;color:#ddd;display:block;margin-bottom:10px;"></i>
                    Không có lịch hẹn nào
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = appointments.map(apt => {
        const statusConfig = {
            'pending': { text: 'Chưa xác nhận', class: 'pending', icon: 'ri-time-line' },
            'confirmed': { text: 'Đã xác nhận', class: 'confirmed', icon: 'ri-checkbox-circle-line' },
            'paid_confirmed': { text: 'Đã TT + Xác nhận', class: 'paid-confirmed', icon: 'ri-check-double-line' },
            'cancelled': { text: 'Đã hủy', class: 'cancelled', icon: 'ri-close-circle-line' },
            'completed': { text: 'Đã hoàn thành', class: 'completed', icon: 'ri-checkbox-circle-fill' },
            'ended': { text: 'Đã kết thúc', class: 'ended', icon: 'ri-stop-circle-line' },
            // Keep legacy statuses for backward compatibility
            'available': { text: 'Còn trống', class: 'available', icon: 'ri-checkbox-blank-circle-line' },
            'booked': { text: 'Đã đặt', class: 'booked', icon: 'ri-checkbox-circle-line' }
        };

        const paymentConfig = {
            'UNPAID': { text: 'Chưa TT', class: 'unpaid' },
            'PAID': { text: 'Đã TT', class: 'paid' },
            'REFUND_PENDING': { text: 'Chờ hoàn', class: 'pending' },
            'REFUNDED': { text: 'Đã hoàn', class: 'refunded' }
        };

        const status = statusConfig[apt.status] || statusConfig['available'];
        const payment = paymentConfig[apt.payment_status] || paymentConfig['UNPAID'];

        const appointmentDate = apt.appointment_date ? new Date(apt.appointment_date).toLocaleDateString('vi-VN') : '-';
        const timeSlot = apt.time_slot || (apt.start_time && apt.end_time ? `${apt.start_time} - ${apt.end_time}` : '-');
        const doctorName = apt.doctor?.full_name || apt.doctor_name || '-';
        const patientName = apt.patient?.full_name || apt.patient_name || '-';
        const patientPhone = apt.patient?.phone || apt.patient_phone || '-';
        const clinicName = apt.clinic?.name || apt.clinic_name || '-';
        const feeAmount = apt.fee_amount ? Number(apt.fee_amount).toLocaleString('vi-VN') + ' đ' : '—';

        return `
            <tr>
                <td>${apt.id || '—'}</td>
                <td>
                    <div style="font-weight:500;">${patientName}</div>
                    <div style="font-size:12px;color:#666;">${patientPhone}</div>
                </td>
                <td>${doctorName}</td>
                <td>${appointmentDate}</td>
                <td>${timeSlot}</td>
                <td>${clinicName}</td>
                <td><span class="status-badge status-${status.class}"><i class="${status.icon}"></i> ${status.text}</span></td>
                <td>
                    <div class="action-buttons" style="display:flex;flex-direction:column;gap:4px;">
                        <button class="btn-action btn-view" onclick="viewAppointment(${apt.id})" title="Xem chi tiết">
                            <i class="ri-eye-line"></i>
                        </button>
                        <button class="btn-action btn-edit" onclick="editAppointment(${apt.id})" title="Chỉnh sửa">
                            <i class="ri-edit-line"></i>
                        </button>
                        <button class="btn-action btn-delete" onclick="deleteAppointmentById(${apt.id})" title="Xóa">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Filter appointments by status
function filterAppointments(status) {
    // Update active button
    document.querySelectorAll('.appointment-filter-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    fetchAppointments(status);
}

// Search appointments
function searchAppointments() {
    fetchAppointments(currentAppointmentStatus);
}

// Filter by date
function filterAppointmentsByDate() {
    fetchAppointments(currentAppointmentStatus);
}

// Clear appointment filters
function clearAppointmentFilters() {
    const searchInput = document.getElementById('appointmentSearchInput');
    const dateFilter = document.getElementById('appointmentDateFilter');

    if (searchInput) searchInput.value = '';
    if (dateFilter) dateFilter.value = '';

    // Reset status filter buttons
    document.querySelectorAll('.appointment-filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.includes('Tất cả')) {
            btn.classList.add('active');
        }
    });

    fetchAppointments('all');
}

// Store selected services and their prices
let selectedAppointmentServices = [];
let appointmentDoctorServices = [];
let currentVatFee = 0;

// Open add appointment modal
function openAddAppointmentModal() {
    // Reset form
    const form = document.getElementById('appointmentForm');
    if (form) form.reset();

    const appointmentIdEl = document.getElementById('appointmentId');
    if (appointmentIdEl) appointmentIdEl.value = '';

    const titleEl = document.getElementById('appointmentModalTitle');
    if (titleEl) titleEl.textContent = 'Thêm lịch hẹn mới';

    // Reset services
    selectedAppointmentServices = [];
    appointmentDoctorServices = [];
    currentVatFee = 0;
    
    // Reset service container
    const servicesContainer = document.getElementById('appointmentServicesContainer');
    if (servicesContainer) {
        servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;">Chọn bác sĩ trước để hiển thị dịch vụ</span>';
    }
    
    // Reset cost fields
    const serviceCost = document.getElementById('appointmentServiceCost');
    const vatFee = document.getElementById('appointmentVatFee');
    const totalCost = document.getElementById('appointmentTotalCost');
    const specField = document.getElementById('appointmentSpecialization');
    const specIdField = document.getElementById('appointmentSpecializationId');
    const addressField = document.getElementById('appointmentAddress');
    
    if (serviceCost) serviceCost.value = '';
    if (vatFee) vatFee.value = '';
    if (totalCost) totalCost.value = '';
    if (specField) specField.value = '';
    if (specIdField) specIdField.value = '';
    if (addressField) addressField.value = '';
    
    // Reset patient fields
    const patientName = document.getElementById('patientName');
    const patientPhone = document.getElementById('patientPhone');
    const patientEmail = document.getElementById('patientEmail');
    const appointmentPatient = document.getElementById('appointmentPatient');
    
    if (patientName) patientName.value = '';
    if (patientPhone) patientPhone.value = '';
    if (patientEmail) patientEmail.value = '';
    if (appointmentPatient) appointmentPatient.value = '';

    // Load doctors and clinics for dropdowns
    loadDoctorsForAppointment();
    loadClinicsForAppointment();

    openModal('addAppointmentModal');
}

// Cache for patient data to avoid repeated API calls
let cachedPatients = [];

// Load and cache all patients
async function loadAndCachePatients() {
    if (cachedPatients.length > 0) return cachedPatients;
    
    try {
        const response = await apiRequest('/users?role=patient');
        if (response.ok) {
            const result = await response.json();
            cachedPatients = result.data || result || [];
        }
    } catch (error) {
        console.error('Error loading patients:', error);
    }
    return cachedPatients;
}

// Auto fill patient info by name
async function autoFillPatientByName(name) {
    if (!name || name.trim().length < 2) return;
    
    const patients = await loadAndCachePatients();
    const normalizedName = name.trim().toLowerCase();
    
    // Find exact or close match
    const patient = patients.find(p => {
        const fullName = (p.full_name || p.name || '').toLowerCase();
        return fullName === normalizedName || fullName.includes(normalizedName);
    });
    
    if (patient) {
        fillPatientFields(patient);
    }
}

// Auto fill patient info by phone
async function autoFillPatientByPhone(phone) {
    if (!phone || phone.trim().length < 8) return;
    
    const patients = await loadAndCachePatients();
    const normalizedPhone = phone.trim().replace(/\D/g, '');
    
    const patient = patients.find(p => {
        const patientPhone = (p.phone || '').replace(/\D/g, '');
        return patientPhone === normalizedPhone;
    });
    
    if (patient) {
        fillPatientFields(patient);
    }
}

// Auto fill patient info by email
async function autoFillPatientByEmail(email) {
    if (!email || email.trim().length < 5) return;
    
    const patients = await loadAndCachePatients();
    const normalizedEmail = email.trim().toLowerCase();
    
    const patient = patients.find(p => {
        const patientEmail = (p.email || '').toLowerCase();
        return patientEmail === normalizedEmail;
    });
    
    if (patient) {
        fillPatientFields(patient);
    }
}

// Fill all patient fields with found patient data
function fillPatientFields(patient) {
    const nameField = document.getElementById('patientName');
    const phoneField = document.getElementById('patientPhone');
    const emailField = document.getElementById('patientEmail');
    const patientIdField = document.getElementById('appointmentPatient');
    
    if (nameField && !nameField.value) {
        nameField.value = patient.full_name || patient.name || '';
    }
    if (phoneField && !phoneField.value) {
        phoneField.value = patient.phone || '';
    }
    if (emailField && !emailField.value) {
        emailField.value = patient.email || '';
    }
    if (patientIdField) {
        patientIdField.value = patient.id || '';
    }
}

// Cache for clinic data
let cachedClinics = [];

// Load and cache all clinics
async function loadAndCacheClinics() {
    if (cachedClinics.length > 0) return cachedClinics;
    
    try {
        const response = await apiRequest('/clinics');
        if (response.ok) {
            const result = await response.json();
            cachedClinics = result.data || result || [];
        }
    } catch (error) {
        console.error('Error loading clinics:', error);
    }
    return cachedClinics;
}

// Handle clinic selection - auto fill address
async function onClinicChange(clinicId) {
    const addressField = document.getElementById('appointmentAddress');
    
    if (!clinicId) {
        if (addressField) addressField.value = '';
        return;
    }
    
    const clinics = await loadAndCacheClinics();
    const clinic = clinics.find(c => c.id == clinicId);
    
    if (clinic && addressField) {
        addressField.value = clinic.address || '';
    }
}

// Handle doctor selection change
async function onDoctorChange(doctorId) {
    const specField = document.getElementById('appointmentSpecialization');
    const specIdField = document.getElementById('appointmentSpecializationId');
    const servicesContainer = document.getElementById('appointmentServicesContainer');
    
    if (!doctorId) {
        if (specField) specField.value = '';
        if (specIdField) specIdField.value = '';
        if (servicesContainer) {
            servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;">Chọn bác sĩ trước để hiển thị dịch vụ</span>';
        }
        selectedAppointmentServices = [];
        updateAppointmentCost();
        return;
    }
    
    try {
        // Fetch doctor details to get specialization and services
        const response = await apiRequest(`/doctors/${doctorId}`);
        if (response.ok) {
            const doctor = await response.json();
            
            // Set specialization
            if (specField && doctor.specialization) {
                specField.value = doctor.specialization.name || doctor.specialization_name || '';
            }
            if (specIdField) {
                specIdField.value = doctor.specialization_id || doctor.specialization?.id || '';
            }
            
            // Get doctor's services
            appointmentDoctorServices = doctor.services || [];
            
            // Render services as checkboxes
            if (servicesContainer) {
                if (appointmentDoctorServices.length > 0) {
                    servicesContainer.innerHTML = appointmentDoctorServices.map(s => `
                        <label style="display: flex; align-items: center; gap: 6px; padding: 8px 12px; background: #fff; border: 1px solid #ddd; border-radius: 6px; cursor: pointer; transition: all 0.2s;">
                            <input type="checkbox" name="appointmentService" value="${s.id}" data-price="${s.price || 0}" data-name="${s.name}" onchange="toggleAppointmentService(this)">
                            <span>${s.name} - ${formatCurrency(s.price || 0)}</span>
                        </label>
                    `).join('');
                } else {
                    servicesContainer.innerHTML = '<span style="color: #999; font-size: 14px;">Bác sĩ này chưa có dịch vụ nào</span>';
                }
            }
            
            // Reset selected services
            selectedAppointmentServices = [];
            updateAppointmentCost();
        }
    } catch (error) {
        console.error('Error loading doctor details:', error);
    }
}

// Toggle service selection
function toggleAppointmentService(checkbox) {
    const serviceId = parseInt(checkbox.value);
    const price = parseFloat(checkbox.dataset.price) || 0;
    const name = checkbox.dataset.name;
    
    if (checkbox.checked) {
        selectedAppointmentServices.push({ id: serviceId, price, name });
        checkbox.parentElement.style.background = '#e3f2fd';
        checkbox.parentElement.style.borderColor = '#2196f3';
    } else {
        selectedAppointmentServices = selectedAppointmentServices.filter(s => s.id !== serviceId);
        checkbox.parentElement.style.background = '#fff';
        checkbox.parentElement.style.borderColor = '#ddd';
    }
    
    updateAppointmentCost();
}

// Update appointment cost calculation
function updateAppointmentCost() {
    const serviceCostField = document.getElementById('appointmentServiceCost');
    const vatFeeField = document.getElementById('appointmentVatFee');
    const totalCostField = document.getElementById('appointmentTotalCost');
    const feeField = document.getElementById('appointmentFee');
    
    // Calculate service cost
    const serviceCost = selectedAppointmentServices.reduce((sum, s) => sum + s.price, 0);
    
    // Generate random VAT fee (100k - 200k) only if there are services selected
    if (selectedAppointmentServices.length > 0) {
        currentVatFee = Math.floor(Math.random() * (200000 - 100000 + 1)) + 100000;
    } else {
        currentVatFee = 0;
    }
    
    const totalCost = serviceCost + currentVatFee;
    
    if (serviceCostField) serviceCostField.value = formatCurrency(serviceCost);
    if (vatFeeField) vatFeeField.value = currentVatFee > 0 ? formatCurrency(currentVatFee) : '';
    if (totalCostField) totalCostField.value = formatCurrency(totalCost);
    if (feeField) feeField.value = totalCost;
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
}

// Make functions available globally
window.onDoctorChange = onDoctorChange;
window.onClinicChange = onClinicChange;
window.toggleAppointmentService = toggleAppointmentService;
window.autoFillPatientByName = autoFillPatientByName;
window.autoFillPatientByPhone = autoFillPatientByPhone;
window.autoFillPatientByEmail = autoFillPatientByEmail;

// Load doctors for appointment dropdown
async function loadDoctorsForAppointment() {
    const select = document.getElementById('appointmentDoctor');
    if (!select) return;

    try {
        const response = await apiRequest('/doctors');
        if (response.ok) {
            const result = await response.json();
            const doctors = result.data || result || [];

            select.innerHTML = '<option value="">Chọn bác sĩ</option>' +
                doctors.map(d => `<option value="${d.id}" data-spec-id="${d.specialization_id || ''}" data-spec-name="${d.specialization_name || ''}">${d.full_name || d.name} - ${d.specialization_name || 'Chưa có chuyên khoa'}</option>`).join('');
        }
    } catch (error) {
        console.error('Error loading doctors:', error);
    }
}

// Load patients for appointment dropdown
async function loadPatientsForAppointment() {
    const select = document.getElementById('appointmentPatient');
    if (!select) return;

    try {
        const response = await apiRequest('/users');
        if (response.ok) {
            const result = await response.json();
            let allUsers = [];
            
            // Handle different response formats
            if (Array.isArray(result)) {
                allUsers = result;
            } else if (result.data && Array.isArray(result.data)) {
                allUsers = result.data;
            } else if (result.users && Array.isArray(result.users)) {
                allUsers = result.users;
            }
            
            // Filter only patients (type USER)
            const patients = allUsers.filter(u => {
                const type = (u.type || '').toUpperCase();
                return type === 'USER';
            });

            select.innerHTML = '<option value="">Chọn bệnh nhân</option>' +
                patients.map(p => `<option value="${p.id}">${p.full_name || p.name || 'Không tên'} - ${p.phone || 'Không có SĐT'}</option>`).join('');
        } else {
            console.error('Failed to load patients, status:', response.status);
        }
    } catch (error) {
        console.error('Error loading patients:', error);
    }
}

// Load clinics for appointment dropdown
async function loadClinicsForAppointment() {
    const select = document.getElementById('appointmentClinic');
    if (!select) return;

    try {
        // Use cached clinics if available
        const clinics = await loadAndCacheClinics();

        select.innerHTML = '<option value="">Chọn phòng khám</option>' +
            clinics.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
    } catch (error) {
        console.error('Error loading clinics:', error);
    }
}

// Search doctors for appointment
let aptDoctorSearchTimeout = null;
function searchDoctorForAppointment(query) {
    clearTimeout(aptDoctorSearchTimeout);

    const suggestionsDiv = document.getElementById('appointmentDoctorSuggestions');

    if (!query || query.length < 2) {
        suggestionsDiv.style.display = 'none';
        return;
    }

    aptDoctorSearchTimeout = setTimeout(async () => {
        try {
            const response = await apiRequest(`/doctors/search?q=${encodeURIComponent(query)}`);

            if (response.ok) {
                const result = await response.json();
                appointmentDoctorSuggestions = result.data || result || [];

                if (appointmentDoctorSuggestions.length > 0) {
                    suggestionsDiv.innerHTML = appointmentDoctorSuggestions.map(d => `
                        <div class="suggestion-item" onclick="selectDoctorForAppointment(${d.id}, '${d.full_name}')">
                            <div class="suggestion-name">${d.full_name}</div>
                            <div class="suggestion-detail">${d.specialization_name || ''} - ${d.clinic_name || ''}</div>
                        </div>
                    `).join('');
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.innerHTML = '<div class="suggestion-item no-result">Không tìm thấy bác sĩ</div>';
                    suggestionsDiv.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error searching doctors:', error);
        }
    }, 300);
}

// Select doctor for appointment
function selectDoctorForAppointment(doctorId, doctorName) {
    document.getElementById('appointmentDoctorId').value = doctorId;
    document.getElementById('appointmentDoctorSearch').value = doctorName;
    document.getElementById('appointmentDoctorSuggestions').style.display = 'none';

    // Load available time slots if date is selected
    const date = document.getElementById('appointmentDate').value;
    if (date) {
        loadAvailableTimeSlots(doctorId, date);
    }
}

// Search patients for appointment
let patientSearchTimeout = null;
function searchPatientForAppointment(query) {
    clearTimeout(patientSearchTimeout);

    const suggestionsDiv = document.getElementById('appointmentPatientSuggestions');

    if (!query || query.length < 2) {
        suggestionsDiv.style.display = 'none';
        return;
    }

    patientSearchTimeout = setTimeout(async () => {
        try {
            const response = await apiRequest(`/patients/search?q=${encodeURIComponent(query)}`);

            if (response.ok) {
                const result = await response.json();
                appointmentPatientSuggestions = result.data || result || [];

                if (appointmentPatientSuggestions.length > 0) {
                    suggestionsDiv.innerHTML = appointmentPatientSuggestions.map(p => `
                        <div class="suggestion-item" onclick="selectPatientForAppointment(${p.id}, '${p.full_name}', '${p.phone || ''}')">
                            <div class="suggestion-name">${p.full_name}</div>
                            <div class="suggestion-detail">${p.phone || ''} - ${p.email || ''}</div>
                        </div>
                    `).join('');
                    suggestionsDiv.style.display = 'block';
                } else {
                    suggestionsDiv.innerHTML = '<div class="suggestion-item no-result">Không tìm thấy bệnh nhân</div>';
                    suggestionsDiv.style.display = 'block';
                }
            }
        } catch (error) {
            console.error('Error searching patients:', error);
        }
    }, 300);
}

// Select patient for appointment
function selectPatientForAppointment(patientId, patientName, patientPhone) {
    document.getElementById('appointmentPatientId').value = patientId;
    document.getElementById('appointmentPatientSearch').value = patientName;
    document.getElementById('appointmentPatientPhone').value = patientPhone;
    document.getElementById('appointmentPatientSuggestions').style.display = 'none';
}

// Load available time slots
async function loadAvailableTimeSlots(doctorId, date) {
    const container = document.getElementById('appointmentTimeSlots');
    if (!container) return;

    if (!doctorId || !date) {
        container.innerHTML = '<div class="no-slots">Vui lòng chọn bác sĩ và ngày khám</div>';
        return;
    }

    container.innerHTML = '<div class="loading-slots"><i class="ri-loader-4-line ri-spin"></i> Đang tải...</div>';

    try {
        const response = await apiRequest(`/appointments/available-slots?doctor_id=${doctorId}&date=${date}`);

        if (response.ok) {
            const result = await response.json();
            const slots = result.data || result || [];

            if (slots.length > 0) {
                container.innerHTML = slots.map(slot => `
                    <div class="time-slot ${slot.available ? 'available' : 'unavailable'}"
                         onclick="${slot.available ? `selectTimeSlot('${slot.time}')` : ''}"
                         data-time="${slot.time}">
                        <i class="ri-time-line"></i> ${slot.time}
                        ${!slot.available ? '<span class="slot-booked">Đã đặt</span>' : ''}
                    </div>
                `).join('');
            } else {
                container.innerHTML = '<div class="no-slots">Không có khung giờ nào cho ngày này</div>';
            }
        } else {
            container.innerHTML = '<div class="no-slots">Lỗi tải khung giờ</div>';
        }
    } catch (error) {
        console.error('Error loading time slots:', error);
        container.innerHTML = '<div class="no-slots">Lỗi tải khung giờ</div>';
    }
}

// Select time slot
function selectTimeSlot(time) {
    // Remove previous selection
    document.querySelectorAll('.time-slot').forEach(slot => {
        slot.classList.remove('selected');
    });

    // Add selection to clicked slot
    const selectedSlot = document.querySelector(`.time-slot[data-time="${time}"]`);
    if (selectedSlot) {
        selectedSlot.classList.add('selected');
    }

    // Store selected time
    document.getElementById('appointmentTimeSlot').value = time;
}

// On date change, reload time slots
function onAppointmentDateChange() {
    const doctorId = document.getElementById('appointmentDoctorId').value;
    const date = document.getElementById('appointmentDate').value;

    if (doctorId && date) {
        loadAvailableTimeSlots(doctorId, date);
    }
}

// View appointment details
async function viewAppointment(id) {
    try {
        const response = await apiRequest(`/appointments/${id}`);

        if (response.ok) {
            const result = await response.json();
            const apt = result.data || result; // Handle both {data: {...}} and direct object

            const statusConfig = {
                'pending': 'Chưa xác nhận',
                'confirmed': 'Đã xác nhận',
                'paid_confirmed': 'Đã thanh toán + Đã xác nhận',
                'cancelled': 'Đã hủy',
                'completed': 'Đã hoàn thành',
                'ended': 'Đã kết thúc',
                // Legacy statuses for backward compatibility
                'available': 'Còn trống',
                'booked': 'Đã đặt'
            };

            const paymentConfig = {
                'UNPAID': 'Chưa thanh toán',
                'PAID': 'Đã thanh toán',
                'REFUND_PENDING': 'Chờ hoàn tiền',
                'REFUNDED': 'Đã hoàn tiền'
            };

            // Format date
            const appointmentDate = apt.appointment_date ? new Date(apt.appointment_date).toLocaleDateString('vi-VN') : '—';

            // Format time slot
            const timeSlot = apt.time_slot || (apt.start_time && apt.end_time ? `${apt.start_time} - ${apt.end_time}` : '—');

            const html = `
                <div class="appointment-detail">
                    <div class="detail-row">
                        <span class="detail-label">Ngày khám:</span>
                        <span class="detail-value">${appointmentDate}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Giờ khám:</span>
                        <span class="detail-value">${timeSlot}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bác sĩ:</span>
                        <span class="detail-value">${apt.doctor_name || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Bệnh nhân:</span>
                        <span class="detail-value">${apt.patient_full_name || apt.patient_name || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">SĐT bệnh nhân:</span>
                        <span class="detail-value">${apt.patient_phone || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phòng khám:</span>
                        <span class="detail-value">${apt.clinic_name || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Địa chỉ:</span>
                        <span class="detail-value">${apt.clinic_address || apt.clinic?.address || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Trạng thái:</span>
                        <span class="detail-value">${statusConfig[apt.status] || apt.status || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Phí khám:</span>
                        <span class="detail-value">${apt.fee_amount ? Number(apt.fee_amount).toLocaleString('vi-VN') + ' đ' : '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Thanh toán:</span>
                        <span class="detail-value">${paymentConfig[apt.payment_status] || apt.payment_status || '—'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Ghi chú:</span>
                        <span class="detail-value">${apt.notes || '—'}</span>
                    </div>
                </div>
            `;

            // Show in a modal
            // Remove existing view modal if any
            const existingModal = document.getElementById('appointmentViewModal');
            if (existingModal) existingModal.remove();

            const viewModal = document.createElement('div');
            viewModal.className = 'modal';
            viewModal.id = 'appointmentViewModal';
            viewModal.style.display = 'flex';
            viewModal.innerHTML = `
                <div class="modal-content" style="max-width:500px;">
                    <div class="modal-header">
                        <h2><i class="ri-calendar-check-line"></i> Chi tiết lịch hẹn</h2>
                        <div class="close-modal" onclick="document.getElementById('appointmentViewModal').remove()">
                            <i class="ri-close-line"></i>
                        </div>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        ${html}
                    </div>
                    <div class="modal-actions">
                        <button class="btn-cancel" onclick="document.getElementById('appointmentViewModal').remove()">Đóng</button>
                        <button class="btn-primary" onclick="document.getElementById('appointmentViewModal').remove(); editAppointment(${id})">
                            <i class="ri-edit-line"></i> Chỉnh sửa
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(viewModal);
        }
    } catch (error) {
        console.error('Error viewing appointment:', error);
        alert('Lỗi khi tải thông tin lịch hẹn!');
    }
}

// Edit appointment
async function editAppointment(id) {
    try {
        const response = await apiRequest(`/appointments/${id}`);

        if (response.ok) {
            const result = await response.json();
            const apt = result.data || result;
            // Load dropdowns first and wait for all to complete
            await Promise.all([
                loadDoctorsForAppointment(),
                loadClinicsForAppointment()
            ]);

            // Longer delay to ensure DOM is fully updated
            await new Promise(resolve => setTimeout(resolve, 200));

            // Set form values
            const setVal = (id, value) => {
                const el = document.getElementById(id);
                if (el) {
                    el.value = value || '';
                    }
            };

            setVal('appointmentId', apt.id);
            setVal('appointmentDoctor', apt.doctor_id);
            
            // Fill patient info fields
            setVal('patientName', apt.patient_full_name || apt.patient_name || '');
            setVal('patientPhone', apt.patient_phone || '');
            setVal('patientEmail', apt.patient_email || '');
            setVal('appointmentPatient', apt.patient_id || '');
            
            setVal('appointmentDate', apt.appointment_date);
            setVal('appointmentTimeSlot', apt.time_slot);
            setVal('appointmentClinic', apt.clinic_id);
            setVal('appointmentStatus', apt.status || 'available');
            setVal('appointmentFee', apt.fee_amount);
            setVal('appointmentNotes', apt.notes);
            
            // Load address for selected clinic
            if (apt.clinic_id) {
                await onClinicChange(apt.clinic_id);
            }

            // Load specialization and services for the selected doctor
            if (apt.doctor_id) {
                await onDoctorChange(apt.doctor_id);
                
                // Wait for services to load then check saved services
                await new Promise(resolve => setTimeout(resolve, 200));
                
                // If appointment has saved service_id, check it
                if (apt.service_id) {
                    const checkbox = document.querySelector(`input[name="appointmentService"][value="${apt.service_id}"]`);
                    if (checkbox && !checkbox.checked) {
                        checkbox.checked = true;
                        toggleAppointmentService(checkbox);
                    }
                }
                
                // If appointment has saved services array, check them
                if (apt.service_ids && Array.isArray(apt.service_ids)) {
                    apt.service_ids.forEach(serviceId => {
                        const checkbox = document.querySelector(`input[name="appointmentService"][value="${serviceId}"]`);
                        if (checkbox && !checkbox.checked) {
                            checkbox.checked = true;
                            toggleAppointmentService(checkbox);
                        }
                    });
                }
            }

            const titleEl = document.getElementById('appointmentModalTitle');
            if (titleEl) titleEl.textContent = 'Chỉnh sửa lịch hẹn';

            openModal('addAppointmentModal');
        }
    } catch (error) {
        console.error('Error loading appointment:', error);
        alert('Lỗi khi tải thông tin lịch hẹn!');
    }
}

// Delete appointment
async function deleteAppointmentById(id) {
    if (!confirm('Bạn có chắc muốn hủy lịch hẹn này?')) return;

    try {
        const response = await apiRequest(`/appointments/${id}`, 'DELETE');

        if (response.ok) {
            alert('✅ Đã hủy lịch hẹn thành công!');
            fetchAppointments(currentAppointmentStatus);
        } else {
            const error = await response.json();
            alert('❌ Lỗi: ' + (error.message || 'Không thể hủy lịch hẹn'));
        }
    } catch (error) {
        console.error('Error deleting appointment:', error);
        alert('Lỗi khi hủy lịch hẹn!');
    }
}

// Initialize appointment form handler
function initAppointmentFormHandler() {
    const form = document.getElementById('appointmentForm');
    if (!form) return;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const appointmentIdEl = document.getElementById('appointmentId');
        const appointmentId = appointmentIdEl ? appointmentIdEl.value : '';
        const isEdit = !!appointmentId;

        // Get element values safely
        const getVal = (id) => {
            const el = document.getElementById(id);
            return el ? el.value : '';
        };

        // Get patient info from the dropdown or manual fields (if they exist)
        const patientSelect = document.getElementById('appointmentPatient');
        const patientId = patientSelect && patientSelect.value ? patientSelect.value : null;
        
        // Get patient name/phone from dropdown option text or manual fields
        let patientName = null;
        let patientPhone = null;
        let patientEmail = null;
        
        // Check if manual input fields exist
        const nameField = document.getElementById('patientName');
        const phoneField = document.getElementById('patientPhone');
        const emailField = document.getElementById('patientEmail');
        
        if (nameField && nameField.value) {
            patientName = nameField.value.trim();
            patientPhone = phoneField ? phoneField.value.trim() : null;
            patientEmail = emailField ? emailField.value.trim() : null;
        } else if (patientId && patientSelect) {
            // Extract name/phone from selected option text (format: "Name - Phone")
            const optionText = patientSelect.options[patientSelect.selectedIndex]?.text || '';
            const parts = optionText.split(' - ');
            patientName = parts[0]?.trim() || null;
            patientPhone = parts[1]?.trim() || null;
        }

        // Collect data - use actual element IDs from HTML
        const data = {
            doctor_id: getVal('appointmentDoctor'),
            patient_id: patientId,
            patient_name: patientName,
            patient_phone: patientPhone,
            patient_email: patientEmail,
            appointment_date: getVal('appointmentDate'),
            time_slot: getVal('appointmentTimeSlot'),
            clinic_id: getVal('appointmentClinic') || null,
            status: getVal('appointmentStatus'),
            fee_amount: getVal('appointmentFee') || null,
            notes: getVal('appointmentNotes') || null,
            service_ids: selectedAppointmentServices.map(s => s.id)
        };

        // Validate required
        if (!data.doctor_id) {
            alert('⚠️ Vui lòng chọn bác sĩ!');
            return;
        }
        if (!data.appointment_date) {
            alert('⚠️ Vui lòng chọn ngày khám!');
            return;
        }
        if (!data.time_slot) {
            alert('⚠️ Vui lòng chọn giờ khám!');
            return;
        }

        try {
            let response;
            if (isEdit) {
                response = await apiRequest(`/appointments/${appointmentId}`, 'PUT', data);
            } else {
                response = await apiRequest('/appointments', 'POST', data);
            }

            if (response.ok) {
                alert(isEdit ? '✅ Cập nhật lịch hẹn thành công!' : '✅ Thêm lịch hẹn thành công!');
                closeModal('addAppointmentModal');
                fetchAppointments(currentAppointmentStatus);
            } else {
                const error = await response.json();
                alert('❌ Lỗi: ' + (error.message || 'Không thể lưu lịch hẹn'));
            }
        } catch (error) {
            console.error('Error saving appointment:', error);
            alert('Lỗi khi lưu lịch hẹn!');
        }
    });
}

// Export appointments functions
window.fetchAppointments = fetchAppointments;
window.renderAppointments = renderAppointments;
window.filterAppointments = filterAppointments;
window.searchAppointments = searchAppointments;
window.filterAppointmentsByDate = filterAppointmentsByDate;
window.clearAppointmentFilters = clearAppointmentFilters;
window.openAddAppointmentModal = openAddAppointmentModal;
window.searchDoctorForAppointment = searchDoctorForAppointment;
window.selectDoctorForAppointment = selectDoctorForAppointment;
window.searchPatientForAppointment = searchPatientForAppointment;
window.selectPatientForAppointment = selectPatientForAppointment;
window.loadAvailableTimeSlots = loadAvailableTimeSlots;
window.selectTimeSlot = selectTimeSlot;
window.onAppointmentDateChange = onAppointmentDateChange;
window.viewAppointment = viewAppointment;
window.editAppointment = editAppointment;
window.deleteAppointmentById = deleteAppointmentById;

// ============================================
// SYSTEM SETTINGS MANAGEMENT
// ============================================

// Storage for current settings
let currentSystemSettings = {};

// Load system settings from API
async function loadSystemSettings() {
    const statusEl = document.getElementById('settingsSyncStatus');
    if (statusEl) statusEl.textContent = 'Đang tải...';

    try {
        const response = await apiRequest('/admin/settings');
        
        if (response.ok) {
            const result = await response.json();
            currentSystemSettings = result.data || {};
            
            // Update UI toggles
            applySettingsToUI(currentSystemSettings);
            
            if (statusEl) statusEl.textContent = 'Đã đồng bộ';
            } else {
            console.error('Failed to load settings:', response.status);
            if (statusEl) statusEl.textContent = 'Lỗi tải cấu hình';
        }
    } catch (error) {
        console.error('Error loading system settings:', error);
        if (statusEl) statusEl.textContent = 'Lỗi kết nối';
    }
}

// Apply settings values to UI elements
function applySettingsToUI(settings) {
    const booleanSettings = [
        'payment_enabled',
        'email_enabled',
        'email_user_enabled',
        'email_doctor_enabled',
        'access_user_enabled',
        'access_doctor_enabled',
        'guest_booking_enabled',
        'maintenance_mode',
        'auto_block_failed_login'
    ];
    
    booleanSettings.forEach(key => {
        const el = document.getElementById('setting_' + key);
        if (el) {
            el.checked = settings[key] === true || settings[key] === 1 || settings[key] === '1';
        }
    });
    
    // String/Number settings
    const messageEl = document.getElementById('setting_maintenance_message');
    if (messageEl) messageEl.value = settings.maintenance_message || '';
    
    const maxAttemptsEl = document.getElementById('setting_max_failed_login_attempts');
    if (maxAttemptsEl) maxAttemptsEl.value = settings.max_failed_login_attempts || 5;
}

// Save a single setting
async function saveSystemSetting(key, value) {
    const statusEl = document.getElementById('settingsSyncStatus');
    if (statusEl) statusEl.textContent = 'Đang lưu...';
    
    try {
        const data = {};
        data[key] = value;
        
        const response = await apiRequest('/admin/settings', 'PUT', data);
        
        if (response.ok) {
            currentSystemSettings[key] = value;
            if (statusEl) statusEl.textContent = 'Đã lưu ✓';
            
            // Reset status after 2 seconds
            setTimeout(() => {
                if (statusEl) statusEl.textContent = 'Đã đồng bộ';
            }, 2000);
            
            } else {
            const err = await response.json();
            console.error('Failed to save setting:', err);
            if (statusEl) statusEl.textContent = 'Lỗi lưu!';
            alert('Lỗi: ' + (err.message || 'Không thể lưu cấu hình'));
        }
    } catch (error) {
        console.error('Error saving setting:', error);
        if (statusEl) statusEl.textContent = 'Lỗi kết nối';
    }
}

// Load blocked IPs
async function loadBlockedIps() {
    const container = document.getElementById('blockedIpsList');
    if (!container) return;
    
    container.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Đang tải...</div>';
    
    try {
        const response = await apiRequest('/admin/blocked-ips');
        
        if (response.ok) {
            const result = await response.json();
            const ips = result.data || [];
            
            if (ips.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">Không có IP nào bị chặn</div>';
                return;
            }
            
            container.innerHTML = ips.map(item => `
                <div class="blocked-ip-card" style="background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-family: monospace; color: #e74c3c;">${item.ip}</strong>
                        <br>
                        <small style="color: #999;">
                            ${item.last_activity ? 'Lần cuối: ' + item.last_activity : 'Không có hoạt động'}
                        </small>
                    </div>
                    <button onclick="unblockIp('${item.ip}')" class="btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                        <i class="ri-close-line"></i> Bỏ chặn
                    </button>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading blocked IPs:', error);
        container.innerHTML = '<div style="text-align: center; color: #e74c3c; padding: 20px;">Lỗi tải danh sách</div>';
    }
}

// Block IP modal
function openBlockIpModal() {
    const ip = prompt('Nhập địa chỉ IP cần chặn:');
    if (!ip) return;
    
    // Validate IP format (basic)
    const ipRegex = /^(\d{1,3}\.){3}\d{1,3}$/;
    if (!ipRegex.test(ip)) {
        alert('Địa chỉ IP không hợp lệ!');
        return;
    }
    
    blockIp(ip);
}

// Block an IP
async function blockIp(ip) {
    try {
        const response = await apiRequest('/admin/block-ip', 'POST', { ip });
        
        if (response.ok) {
            alert('✅ Đã chặn IP: ' + ip);
            loadBlockedIps();
            refreshLogPreview();
        } else {
            const err = await response.json();
            alert('❌ Lỗi: ' + (err.message || 'Không thể chặn IP'));
        }
    } catch (error) {
        console.error('Error blocking IP:', error);
        alert('Lỗi khi chặn IP!');
    }
}

// Unblock an IP
async function unblockIp(ip) {
    if (!confirm('Bạn có chắc muốn bỏ chặn IP: ' + ip + '?')) return;
    
    try {
        const response = await apiRequest('/admin/unblock-ip', 'POST', { ip });
        
        if (response.ok) {
            alert('✅ Đã bỏ chặn IP: ' + ip);
            loadBlockedIps();
            refreshLogPreview();
        } else {
            const err = await response.json();
            alert('❌ Lỗi: ' + (err.message || 'Không thể bỏ chặn IP'));
        }
    } catch (error) {
        console.error('Error unblocking IP:', error);
        alert('Lỗi khi bỏ chặn IP!');
    }
}

// Refresh log preview boxes
async function refreshLogPreview() {
    // Load negative reviews
    loadNegativeReviews();
    
    // Load feedbacks/reports
    loadFeedbacks();
    
    // Load blocked IPs for log box
    loadBlockedIpsLog();
    
    // Load system logs
    loadSystemLogs();
}

// Load negative reviews
async function loadNegativeReviews() {
    const container = document.getElementById('ratingLogBox');
    if (!container) return;
    
    try {
        const response = await apiRequest('/admin/negative-reviews?limit=10');
        
        if (response.ok) {
            const result = await response.json();
            const reviews = result.data || [];
            
            if (reviews.length === 0) {
                container.innerHTML = '<div class="log-entry" style="text-align: center; color: #999;">Không có đánh giá tiêu cực</div>';
                return;
            }
            
            container.innerHTML = reviews.map(r => `
                <div class="log-entry">
                    <strong>${r.rating}★</strong> - ${r.user_name || 'Ẩn danh'}
                    <br>"${(r.comment || r.content || 'Không có nội dung').substring(0, 80)}..."
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading negative reviews:', error);
    }
}

// Load feedbacks/reports
async function loadFeedbacks() {
    const container = document.getElementById('feedbackLogBox');
    if (!container) return;
    
    try {
        const response = await apiRequest('/admin/feedbacks?limit=10');
        
        if (response.ok) {
            const result = await response.json();
            const reports = result.data || [];
            
            if (reports.length === 0) {
                container.innerHTML = '<div class="log-entry" style="text-align: center; color: #999;">Không có báo cáo mới</div>';
                return;
            }
            
            container.innerHTML = reports.map(r => `
                <div class="log-entry" style="cursor: pointer; padding: 8px; margin-bottom: 5px; background: #f8f9fa; border-radius: 6px;" onclick="viewAllLogs('feedback')">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: ${r.status === 'PENDING' ? '#f39c12' : '#27ae60'};">[${r.status || 'PENDING'}]</strong>
                        <small style="color: #999;">${r.reporter_name || 'Ẩn danh'}</small>
                    </div>
                    <div style="color: #e74c3c; font-size: 12px; margin-top: 3px;">Lý do: ${(r.reason || 'Không rõ').substring(0, 40)}${(r.reason || '').length > 40 ? '...' : ''}</div>
                    ${r.detail ? `<div style="color: #666; font-size: 11px; margin-top: 3px;">${r.detail.substring(0, 50)}${r.detail.length > 50 ? '...' : ''}</div>` : ''}
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading feedbacks:', error);
    }
}

// Load blocked IPs for log box
async function loadBlockedIpsLog() {
    const container = document.getElementById('ipBlockLog');
    if (!container) return;
    
    try {
        const response = await apiRequest('/admin/blocked-ips');
        
        if (response.ok) {
            const result = await response.json();
            const ips = result.data || [];
            
            if (ips.length === 0) {
                container.innerHTML = '<div class="log-entry" style="text-align: center; color: #999;">Không có IP bị chặn</div>';
                return;
            }
            
            container.innerHTML = ips.slice(0, 5).map(item => `
                <div class="log-entry">
                    <strong>${item.ip}</strong>
                    ${item.last_activity ? '<br><small>Hoạt động: ' + item.last_activity + '</small>' : ''}
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading blocked IPs log:', error);
    }
}

// Load system logs
async function loadSystemLogs() {
    const container = document.getElementById('systemLogBox');
    if (!container) return;
    
    try {
        const response = await apiRequest('/admin/logs?limit=20');
        
        if (response.ok) {
            const result = await response.json();
            const logs = result.data || [];
            
            if (logs.length === 0) {
                container.innerHTML = '<div class="log-entry" style="text-align: center; color: #999;">Chưa có log</div>';
                return;
            }
            
            const actionNames = {
                'SETTING_CHANGED': 'Thay đổi cấu hình',
                'ADMIN_UPDATE_SETTINGS': 'Cập nhật cấu hình',
                'ADMIN_BLOCK_IP': 'Chặn IP',
                'ADMIN_UNBLOCK_IP': 'Bỏ chặn IP',
                'ADMIN_CLEAR_CACHE': 'Xóa cache',
                'LOGIN_SUCCESS': 'Đăng nhập thành công',
                'LOGIN_FAILED': 'Đăng nhập thất bại'
            };

            const actionColors = {
                'SETTING_CHANGED': '#9b59b6',
                'ADMIN_UPDATE_SETTINGS': '#3498db',
                'ADMIN_BLOCK_IP': '#e74c3c',
                'ADMIN_UNBLOCK_IP': '#27ae60',
                'ADMIN_CLEAR_CACHE': '#f39c12',
                'LOGIN_SUCCESS': '#27ae60',
                'LOGIN_FAILED': '#e74c3c'
            };
            
            container.innerHTML = logs.slice(0, 10).map(log => `
                <div class="log-entry" onclick="viewLogDetail(${log.id}, 'system')" style="cursor: pointer; padding: 10px; border-radius: 8px; margin-bottom: 8px; background: #f8f9fa; transition: all 0.2s;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="background: ${actionColors[log.action] || '#666'}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                            ${actionNames[log.action] || log.action}
                        </span>
                        <small style="color: #999;">${log.created_at}</small>
                    </div>
                    <div style="margin-top: 5px; font-size: 13px;">
                        <strong>${log.user ? log.user.name : 'System'}</strong>
                        ${log.description ? '<br><span style="color: #666;">' + log.description.substring(0, 50) + (log.description.length > 50 ? '...' : '') + '</span>' : ''}
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error loading system logs:', error);
    }
}

// View all logs by type
async function viewAllLogs(type) {
    const modal = document.getElementById('logDetailModal');
    const title = document.getElementById('logDetailTitle');
    const content = document.getElementById('logDetailContent');
    
    // Ensure modal is properly reset before opening
    if (modal) {
        modal.classList.remove('active');
        modal.style.display = 'none';
        void modal.offsetWidth; // Force reflow
    }
    
    const titles = {
        'rating': '<i class="ri-star-smile-line"></i> Tất cả đánh giá tiêu cực',
        'feedback': '<i class="ri-chat-1-line"></i> Tất cả báo cáo/phản hồi',
        'blocked-ip': '<i class="ri-shield-keyhole-line"></i> Tất cả IP bị chặn',
        'system': '<i class="ri-terminal-window-line"></i> Tất cả log hệ thống'
    };
    
    title.innerHTML = titles[type] || 'Chi tiết Log';
    content.innerHTML = '<div style="text-align: center; padding: 20px;"><i class="ri-loader-4-line" style="animation: spin 1s linear infinite;"></i> Đang tải...</div>';
    
    // Now open the modal
    modal.classList.add('active');
    modal.style.display = 'flex';
    
    try {
        let data = [];
        
        if (type === 'rating') {
            // Load negative reviews
            const response = await apiRequest('/reviews?rating_max=2&limit=50');
            if (response.ok) {
                const result = await response.json();
                data = result.data || [];
                content.innerHTML = data.length === 0 
                    ? '<div style="text-align: center; color: #999; padding: 20px;">Không có đánh giá tiêu cực</div>'
                    : data.map(review => `
                        <div style="padding: 15px; border-bottom: 1px solid #eee; display: flex; gap: 15px;">
                            <div style="flex-shrink: 0;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: #e74c3c; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    ${review.rating}★
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; margin-bottom: 5px;">${review.patient_name || 'Ẩn danh'}</div>
                                <div style="color: #666; margin-bottom: 5px;">Bác sĩ: ${review.doctor_name || 'N/A'}</div>
                                <div style="color: #333;">${review.comment || 'Không có nội dung'}</div>
                                <small style="color: #999;">${review.created_at}</small>
                            </div>
                        </div>
                    `).join('');
            }
        } else if (type === 'feedback') {
            // Load forum reports
            const response = await apiRequest('/admin/forum-reports?limit=50');
            if (response.ok) {
                const result = await response.json();
                data = result.data || [];
                content.innerHTML = data.length === 0 
                    ? '<div style="text-align: center; color: #999; padding: 20px;">Không có báo cáo/phản hồi</div>'
                    : data.map(report => `
                        <div style="padding: 15px; border-bottom: 1px solid #eee;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="font-weight: 600;">${report.reporter_name || 'Người dùng'}</span>
                                <span style="background: ${report.status === 'PENDING' ? '#f39c12' : '#27ae60'}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px;">
                                    ${report.status === 'PENDING' ? 'Chờ xử lý' : 'Đã xử lý'}
                                </span>
                            </div>
                            ${report.post_title ? `<div style="color: #3498db; margin-bottom: 5px; font-size: 13px;">Bài viết: ${report.post_title}</div>` : ''}
                            <div style="color: #e74c3c; margin-bottom: 5px;"><strong>Lý do:</strong> ${report.reason || 'Không có'}</div>
                            <div style="color: #333; background: #f8f9fa; padding: 10px; border-radius: 6px; margin-top: 5px;">${report.detail || '<em style="color: #999;">Không có nội dung chi tiết</em>'}</div>
                            <small style="color: #999; display: block; margin-top: 8px;">${report.created_at}</small>
                        </div>
                    `).join('');
            }
        } else if (type === 'blocked-ip') {
            // Load blocked IPs
            const response = await apiRequest('/admin/blocked-ips');
            if (response.ok) {
                const result = await response.json();
                data = result.data || [];
                content.innerHTML = data.length === 0 
                    ? '<div style="text-align: center; color: #999; padding: 20px;">Không có IP bị chặn</div>'
                    : `<table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">IP</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Lý do</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Ngày chặn</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #eee;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(ip => `
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;"><code style="background: #f0f0f0; padding: 2px 6px; border-radius: 4px;">${ip.ip}</code></td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">${ip.reason || 'Không có lý do'}</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">${ip.created_at || 'N/A'}</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee; text-align: center;">
                                        <button onclick="unblockIp('${ip.ip}'); closeModal('logDetailModal');" style="background: #27ae60; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                                            <i class="ri-lock-unlock-line"></i> Bỏ chặn
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>`;
            }
        } else if (type === 'system') {
            // Load system logs
            const response = await apiRequest('/admin/logs?limit=100');
            if (response.ok) {
                const result = await response.json();
                data = result.data || [];
                
                const actionNames = {
                    'SETTING_CHANGED': 'Thay đổi cấu hình',
                    'ADMIN_UPDATE_SETTINGS': 'Cập nhật cấu hình',
                    'ADMIN_BLOCK_IP': 'Chặn IP',
                    'ADMIN_UNBLOCK_IP': 'Bỏ chặn IP',
                    'ADMIN_CLEAR_CACHE': 'Xóa cache',
                    'LOGIN_SUCCESS': 'Đăng nhập thành công',
                    'LOGIN_FAILED': 'Đăng nhập thất bại'
                };

                const actionColors = {
                    'SETTING_CHANGED': '#9b59b6',
                    'ADMIN_UPDATE_SETTINGS': '#3498db',
                    'ADMIN_BLOCK_IP': '#e74c3c',
                    'ADMIN_UNBLOCK_IP': '#27ae60',
                    'ADMIN_CLEAR_CACHE': '#f39c12',
                    'LOGIN_SUCCESS': '#27ae60',
                    'LOGIN_FAILED': '#e74c3c'
                };
                
                content.innerHTML = data.length === 0 
                    ? '<div style="text-align: center; color: #999; padding: 20px;">Chưa có log hệ thống</div>'
                    : `<table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Thời gian</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Hành động</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Người thực hiện</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #eee;">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.map(log => `
                                <tr>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee; white-space: nowrap;">${log.created_at}</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                                        <span style="background: ${actionColors[log.action] || '#666'}; color: white; padding: 3px 10px; border-radius: 4px; font-size: 12px;">
                                            ${actionNames[log.action] || log.action}
                                        </span>
                                    </td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee;">${log.user ? log.user.name : 'System'}</td>
                                    <td style="padding: 12px; border-bottom: 1px solid #eee; max-width: 300px; overflow: hidden; text-overflow: ellipsis;" title="${log.description || ''}">${log.description || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>`;
            }
        }
    } catch (error) {
        console.error('Error loading logs:', error);
        content.innerHTML = '<div style="text-align: center; color: #e74c3c; padding: 20px;">Lỗi khi tải dữ liệu</div>';
    }
}

// View single log detail
async function viewLogDetail(id, type) {
    // For now, just open the full view
    viewAllLogs(type);
}

// Initialize settings section when shown
function initSettingsSection() {
    loadSystemSettings();
    loadBlockedIps();
    refreshLogPreview();
}

// Export system settings functions
window.loadSystemSettings = loadSystemSettings;
window.saveSystemSetting = saveSystemSetting;
window.loadBlockedIps = loadBlockedIps;
window.openBlockIpModal = openBlockIpModal;
window.blockIp = blockIp;
window.unblockIp = unblockIp;
window.refreshLogPreview = refreshLogPreview;
window.initSettingsSection = initSettingsSection;
window.viewAllLogs = viewAllLogs;
window.viewLogDetail = viewLogDetail;

// Auto-initialize settings when navigating to settings section
document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            if (section === 'settings') {
                setTimeout(initSettingsSection, 100);
            }
        });
    });
    
    // Handle admin profile form submission
    const adminProfileForm = document.getElementById('adminProfileForm');
    if (adminProfileForm) {
        adminProfileForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const profileData = {
                full_name: document.getElementById('profileFullName').value.trim(),
                phone: document.getElementById('profilePhone').value.trim(),
                address: document.getElementById('profileAddress').value.trim()
            };
            
            if (!profileData.full_name) {
                alert('Vui lòng nhập họ và tên!');
                return;
            }
            
            if (!profileData.phone) {
                alert('Vui lòng nhập số điện thoại!');
                return;
            }
            
            const result = await updateProfile(profileData);
            if (result) {
                alert('✅ Đã cập nhật hồ sơ thành công!');
                closeModal('adminProfileModal');
            } else {
                alert('⚠️ Lỗi khi cập nhật hồ sơ. Vui lòng thử lại.');
            }
        });
    }
});
