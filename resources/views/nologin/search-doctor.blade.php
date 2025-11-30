<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Đội Ngũ Bác Sĩ Chất Lượng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header-title {
            color: #4a69bd;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header h1 .highlight {
            font-weight: 900;
        }

        .search-section {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        .search-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .search-row:last-child {
            margin-bottom: 0;
        }

        .search-input {
            flex: 1;
            padding: 18px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            outline: none;
            background: #f8f9fa;
        }

        .search-input::placeholder {
            color: #95a5a6;
        }

        .search-select {
            padding: 18px 30px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            outline: none;
            background: #f8f9fa;
            cursor: pointer;
            min-width: 180px;
            color: #2c3e50;
        }

        .search-input[type="datetime-local"],
        .search-input[type="number"] {
            min-width: 200px;
        }

        .search-btn {
            padding: 18px 40px;
            background: #4a69bd;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .search-btn:hover {
            background: #3c5aa6;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(74, 105, 189, 0.4);
        }

        .filter-label {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }

        .doctor-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            display: flex;
            gap: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .doctor-image {
            flex-shrink: 0;
            position: relative;
        }

        .doctor-image img {
            width: 200px;
            height: 280px;
            object-fit: cover;
            border-radius: 15px;
            background: #e0e0e0;
        }

        .book-btn {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            background: #4a69bd;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            border: none;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .book-btn:hover {
            background: #3c5aa6;
            transform: translateX(-50%) translateY(-2px);
        }

        .book-btn::before {
            content: "📅";
            font-size: 16px;
        }

        .doctor-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .doctor-name {
            color: #4a69bd;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            text-transform: uppercase;
            word-break: break-word;
            overflow-wrap: break-word;
            line-height: 1.3;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
            color: #5a6c7d;
            font-size: 15px;
            line-height: 1.6;
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .info-item span:last-child {
            flex: 1;
            min-width: 0;
        }

        .info-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a69bd;
            font-size: 18px;
        }

        .rating {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
        }

        .rating-score {
            color: #f39c12;
            font-size: 20px;
            font-weight: 700;
        }

        .rating-stars {
            color: #f39c12;
            font-size: 18px;
        }

        .contact-info {
            margin-top: 10px;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
            overflow: hidden;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 40px;
            padding: 20px;
        }

        .pagination-btn {
            min-width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: white;
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pagination-btn:hover:not(.active):not(:disabled) {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .pagination-btn.active {
            background: #4a69bd;
            color: white;
            box-shadow: 0 4px 12px rgba(74, 105, 189, 0.3);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-dots {
            color: #7f8c8d;
            font-weight: 600;
            padding: 0 8px;
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
            color: #4a69bd;
        }

        .loading-spinner::after {
            content: '';
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #4a69bd;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .no-results .icon {
            font-size: 64px;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .no-results p {
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 28px;
            }

            .search-row {
                flex-direction: column;
            }

            .search-input,
            .search-select,
            .search-btn {
                border-radius: 15px;
                width: 100%;
            }

            .search-input[type="datetime-local"],
            .search-input[type="number"] {
                width: 100%;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
            }

            .doctor-card {
                flex-direction: column;
                align-items: center;
            }

            .doctor-image img {
                width: 100%;
                max-width: 300px;
            }

            .pagination {
                gap: 4px;
            }

            .pagination-btn {
                min-width: 36px;
                height: 36px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
<div class="container">
    <div class="header">
        <div class="header-title">ĐỘI NGŨ CHÚNG TÔI</div>
        <h1>
            Đội ngũ bác sĩ chất lượng<br /><span class="highlight">chuyên môn cao</span>
        </h1>
    </div>

    <div class="search-section">
        <div class="search-row">
            <input type="text" class="search-input" placeholder="Nhập tên bác sĩ..." id="nameSearch" />
            <button class="search-btn" id="searchBtn">Tìm kiếm</button>
        </div>
        <div class="search-row">
            <div class="filter-group">
                <div class="filter-label">Chuyên khoa</div>
                <select class="search-select" id="specialtyFilter">
                    <option value="">Tất cả chuyên khoa</option>
                    <!-- Options will be loaded from API -->
                </select>
            </div>
            <div class="filter-group">
                <div class="filter-label">Kinh nghiệm làm việc</div>
                <select class="search-select" id="experienceFilter">
                    <option value="">Tất cả</option>
                    <option value="0-5">0-5 năm</option>
                    <option value="5-10">5-10 năm</option>
                    <option value="10-20">10-20 năm</option>
                    <option value="20+">Trên 20 năm</option>
                </select>
            </div>
            <div class="filter-group">
                <div class="filter-label">Sắp xếp theo đánh giá</div>
                <select class="search-select" id="ratingFilter">
                    <option value="">Mặc định</option>
                    <option value="desc">Cao đến thấp</option>
                    <option value="asc">Thấp đến cao</option>
                </select>
            </div>
        </div>
    </div>

    <div class="doctors-grid" id="doctorsGrid">
        <div class="loading-spinner"></div>
    </div>

</div>
<div class="pagination" id="pagination">
    <!-- Phân trang sẽ được tạo động bằng JavaScript -->
</div>
<script>
    const API_BASE = '/api';
    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;
    let allDoctors = [];
    let filteredDoctors = [];
    let specializations = [];

    // Load data khi trang được tải
    document.addEventListener('DOMContentLoaded', async function() {
        await loadSpecializations();
        await loadDoctors();
        setupEventListeners();
    });

    // Load danh sách chuyên khoa từ API
    async function loadSpecializations() {
        try {
            const response = await fetch(`${API_BASE}/public/specializations`);
            specializations = await response.json();
            
            const select = document.getElementById('specialtyFilter');
            select.innerHTML = '<option value="">Tất cả chuyên khoa</option>';
            
            specializations.forEach(spec => {
                const option = document.createElement('option');
                option.value = spec.id;
                option.textContent = spec.name;
                select.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading specializations:', error);
        }
    }

    // Load danh sách bác sĩ từ API
    async function loadDoctors() {
        const grid = document.getElementById('doctorsGrid');
        grid.innerHTML = '<div class="loading-spinner"></div>';

        try {
            const response = await fetch(`${API_BASE}/public/doctors?per_page=100`);
            const result = await response.json();
            allDoctors = result.data || result || [];
            filteredDoctors = [...allDoctors];
            
            // Sắp xếp theo đánh giá cao nhất
            sortDoctors('desc');
            renderDoctors();
        } catch (error) {
            console.error('Error loading doctors:', error);
            grid.innerHTML = `
                <div class="no-results">
                    <div class="icon">❌</div>
                    <h3>Không thể tải dữ liệu</h3>
                    <p>Vui lòng thử lại sau</p>
                </div>
            `;
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        document.getElementById('searchBtn').addEventListener('click', filterDoctors);
        document.getElementById('nameSearch').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') filterDoctors();
        });
        document.getElementById('specialtyFilter').addEventListener('change', filterDoctors);
        document.getElementById('experienceFilter').addEventListener('change', filterDoctors);
        document.getElementById('ratingFilter').addEventListener('change', filterDoctors);
    }

    // Lọc bác sĩ
    function filterDoctors() {
        const nameSearch = document.getElementById('nameSearch').value.toLowerCase().trim();
        const specialtyFilter = document.getElementById('specialtyFilter').value;
        const experienceFilter = document.getElementById('experienceFilter').value;
        const ratingFilter = document.getElementById('ratingFilter').value;

        filteredDoctors = allDoctors.filter(doctor => {
            // Lọc theo tên
            if (nameSearch) {
                const fullName = (doctor.full_name || '').toLowerCase();
                const degree = (doctor.degree || '').toLowerCase();
                if (!fullName.includes(nameSearch) && !degree.includes(nameSearch)) {
                    return false;
                }
            }

            // Lọc theo chuyên khoa
            if (specialtyFilter) {
                if (doctor.specialization_id != specialtyFilter) {
                    return false;
                }
            }

            // Lọc theo kinh nghiệm
            if (experienceFilter) {
                const exp = parseInt(doctor.experience) || 0;
                if (experienceFilter === '0-5' && !(exp >= 0 && exp <= 5)) return false;
                if (experienceFilter === '5-10' && !(exp > 5 && exp <= 10)) return false;
                if (experienceFilter === '10-20' && !(exp > 10 && exp <= 20)) return false;
                if (experienceFilter === '20+' && exp <= 20) return false;
            }

            return true;
        });

        // Sắp xếp theo đánh giá
        if (ratingFilter) {
            sortDoctors(ratingFilter);
        }

        currentPage = 1;
        renderDoctors();
    }

    // Sắp xếp bác sĩ theo đánh giá
    function sortDoctors(order) {
        filteredDoctors.sort((a, b) => {
            const ratingA = parseFloat(a.rating_avg) || 0;
            const ratingB = parseFloat(b.rating_avg) || 0;
            return order === 'desc' ? ratingB - ratingA : ratingA - ratingB;
        });
    }

    // Render danh sách bác sĩ
    function renderDoctors() {
        const grid = document.getElementById('doctorsGrid');
        
        if (filteredDoctors.length === 0) {
            grid.innerHTML = `
                <div class="no-results">
                    <div class="icon">🔍</div>
                    <h3>Không tìm thấy bác sĩ</h3>
                    <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác</p>
                </div>
            `;
            document.getElementById('pagination').style.display = 'none';
            return;
        }

        // Tính toán phân trang
        const totalPages = Math.ceil(filteredDoctors.length / ITEMS_PER_PAGE);
        const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
        const endIndex = Math.min(startIndex + ITEMS_PER_PAGE, filteredDoctors.length);
        const currentDoctors = filteredDoctors.slice(startIndex, endIndex);

        // Render cards
        grid.innerHTML = currentDoctors.map(doctor => createDoctorCard(doctor)).join('');

        // Render pagination
        renderPagination(totalPages);
    }

    // Tạo card bác sĩ
    function createDoctorCard(doctor) {
        const avatarUrl = doctor.avatar_url || '/frontend/img/Screenshot 2025-10-17 201418.png';
        const degree = doctor.degree || 'BS';
        const displayName = `${degree} ${doctor.full_name}`.toUpperCase();
        const specialization = doctor.specialization?.name || 'Đa khoa';
        const experience = doctor.experience || 0;
        const rating = parseFloat(doctor.rating_avg) || 0;
        const ratingCount = doctor.rating_count || 0;
        const clinicAddress = doctor.clinic?.address || 'Chưa cập nhật';
        const phone = doctor.phone || 'Chưa cập nhật';
        const email = doctor.email || 'Chưa cập nhật';

        // Tạo sao đánh giá
        const stars = '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));

        return `
            <div class="doctor-card" data-doctor-id="${doctor.id}">
                <div class="doctor-image">
                    <img src="${avatarUrl}" alt="${doctor.full_name}" 
                         onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'" />
                    <button class="book-btn" onclick="bookDoctor(${doctor.id})">Đặt lịch hẹn</button>
                </div>
                <div class="doctor-info">
                    <h2 class="doctor-name">${displayName}</h2>
                    <div class="rating">
                        <span class="rating-score">${rating.toFixed(1)}</span>
                        <span class="rating-stars">${stars}</span>
                        <span style="color: #95a5a6; font-size: 14px">(${ratingCount} đánh giá)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🎓</span>
                        <span>${degree}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🏥</span>
                        <span>${experience} năm kinh nghiệm</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">💼</span>
                        <span>${specialization}</span>
                    </div>
                    <div class="contact-info">
                        <div class="info-item">
                            <span class="info-icon">📍</span>
                            <span>${clinicAddress}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-icon">📞</span>
                            <span>${phone}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-icon">✉️</span>
                            <span>${email}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    // Đặt lịch với bác sĩ - chuyển sang trang booking với URL sạch
    function bookDoctor(doctorId) {
        // Chuyển sang trang đặt lịch với query parameter doctor
        window.location.href = `/dat-lich/bieu-mau?doctor=${doctorId}`;
    }

    // Render pagination
    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        
        if (totalPages <= 1) {
            pagination.style.display = 'none';
            return;
        }

        pagination.style.display = 'flex';
        pagination.innerHTML = '';

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'pagination-btn';
        prevBtn.innerHTML = '←';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => goToPage(currentPage - 1);
        pagination.appendChild(prevBtn);

        // Page numbers
        const maxVisible = 7;
        if (totalPages <= maxVisible) {
            for (let i = 1; i <= totalPages; i++) {
                pagination.appendChild(createPageButton(i));
            }
        } else {
            pagination.appendChild(createPageButton(1));

            if (currentPage > 3) {
                const dots = document.createElement('span');
                dots.className = 'pagination-dots';
                dots.textContent = '...';
                pagination.appendChild(dots);
            }

            const start = Math.max(2, currentPage - 1);
            const end = Math.min(totalPages - 1, currentPage + 1);

            for (let i = start; i <= end; i++) {
                pagination.appendChild(createPageButton(i));
            }

            if (currentPage < totalPages - 2) {
                const dots = document.createElement('span');
                dots.className = 'pagination-dots';
                dots.textContent = '...';
                pagination.appendChild(dots);
            }

            pagination.appendChild(createPageButton(totalPages));
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'pagination-btn';
        nextBtn.innerHTML = '→';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => goToPage(currentPage + 1);
        pagination.appendChild(nextBtn);
    }

    // Create page button
    function createPageButton(pageNum) {
        const btn = document.createElement('button');
        btn.className = 'pagination-btn' + (pageNum === currentPage ? ' active' : '');
        btn.textContent = pageNum;
        btn.onclick = () => goToPage(pageNum);
        return btn;
    }

    // Go to page
    function goToPage(pageNum) {
        const totalPages = Math.ceil(filteredDoctors.length / ITEMS_PER_PAGE);
        if (pageNum < 1 || pageNum > totalPages) return;

        currentPage = pageNum;
        renderDoctors();

        // Scroll to top of grid
        document.getElementById('doctorsGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
</script>
</body>

</html>
