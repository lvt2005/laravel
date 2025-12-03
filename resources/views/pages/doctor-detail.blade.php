@extends('layouts.public')

@section('title', $doctor['name'] . ' - Bệnh viện Nam Sài Gòn')

@section('content')
<style>
    /* ========================================
   DOCTOR PROFILE PAGE STYLES
   ======================================== */

/* Container chính */
.main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Base Layout */
.doctor-profile-section {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

/* ========================================
   LEFT SIDEBAR - DOCTOR INFO
   ======================================== */
.doctor-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.doctor-info-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Avatar */
.doctor-avatar-large {
    width: 250px;
    height: 250px;
    border-radius: 12px;
    overflow: hidden;
    margin: 0 auto 20px;
    border: 3px solid #e8f0fe;
    box-shadow: 0 4px 12px rgba(30, 91, 168, 0.15);
}

.doctor-avatar-large img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Doctor Details */
.doctor-details h1 {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0 0 8px 0;
    line-height: 1.4;
}

.doctor-details .specialty {
    font-size: 14px;
    color: #1e5ba8;
    font-weight: 600;
    margin-bottom: 15px;
    display: block;
}

.doctor-details .experience {
    color: #555;
    font-size: 14px;
    margin-bottom: 20px;
    text-align: center;
}

/* Book Button */
.book-appointment-btn {
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(30, 91, 168, 0.2);
    text-decoration: none;
}

.book-appointment-btn:hover {
    background: linear-gradient(135deg, #164a8a 0%, #0f3a6f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
}

/* ========================================
   RATING SECTION
   ======================================== */
.doctor-rating-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.doctor-rating-card:hover {
    border-color: #f4c430;
    box-shadow: 0 4px 16px rgba(244, 196, 48, 0.3);
}

.rating-stars {
    display: flex;
    justify-content: center;
    gap: 4px;
    margin-bottom: 8px;
}

.rating-stars i {
    font-size: 24px;
    color: #f4c430;
}

.rating-stars i.empty {
    color: #ddd;
}

.rating-score {
    font-size: 28px;
    font-weight: 700;
    color: #1e5ba8;
    margin-bottom: 4px;
}

.rating-count {
    font-size: 14px;
    color: #666;
}

.rating-hint {
    font-size: 12px;
    color: #999;
    margin-top: 8px;
}

/* Reviews Modal */
.reviews-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.reviews-modal.active {
    display: flex;
}

.reviews-modal-content {
    background: white;
    border-radius: 16px;
    max-width: 700px;
    width: 100%;
    max-height: 80vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.reviews-modal-header {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    color: white;
}

.reviews-modal-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
}

.reviews-modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: white;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.reviews-modal-close:hover {
    opacity: 1;
}

.reviews-summary {
    padding: 20px 25px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 20px;
}

.reviews-summary-score {
    text-align: center;
}

.reviews-summary-score .score {
    font-size: 48px;
    font-weight: 700;
    color: #1e5ba8;
    line-height: 1;
}

.reviews-summary-score .max-score {
    font-size: 18px;
    color: #999;
}

.reviews-summary-stars {
    display: flex;
    gap: 4px;
    margin-top: 8px;
}

.reviews-summary-stars i {
    font-size: 20px;
    color: #f4c430;
}

.reviews-summary-count {
    font-size: 14px;
    color: #666;
    margin-top: 4px;
}

.reviews-modal-body {
    padding: 0;
    overflow-y: auto;
    flex: 1;
}

.reviews-list {
    padding: 0;
}

.review-item {
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
}

.review-item:last-child {
    border-bottom: none;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.review-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.review-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 16px;
}

.review-author-info h4 {
    margin: 0 0 4px 0;
    font-size: 15px;
    font-weight: 600;
    color: #333;
}

.review-date {
    font-size: 12px;
    color: #999;
}

.review-rating {
    display: flex;
    gap: 3px;
}

.review-rating i {
    font-size: 14px;
    color: #f4c430;
}

.review-rating i.empty {
    color: #ddd;
}

.review-content {
    font-size: 14px;
    line-height: 1.7;
    color: #555;
}

.reviews-empty {
    padding: 60px 25px;
    text-align: center;
    color: #999;
}

.reviews-empty i {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
    color: #ddd;
}

.reviews-loading {
    padding: 60px 25px;
    text-align: center;
    color: #666;
}

.reviews-loading i {
    font-size: 32px;
    margin-bottom: 10px;
    display: block;
    color: #1e5ba8;
}

/* Giới thiệu Section - dưới ảnh */
.doctor-bio-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.doctor-bio-card h2 {
    font-size: 18px;
    font-weight: 700;
    color: #1e5ba8;
    margin: 0 0 15px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.doctor-bio-card p {
    font-size: 14px;
    line-height: 1.8;
    color: #555;
    margin: 0;
    text-align: justify;
}

/* ========================================
   RIGHT CONTENT AREA
   ======================================== */
.doctor-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Accordion Sections */
.info-section {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    background: #4267b2;
    color: white;
    padding: 15px 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 16px;
    transition: background 0.3s ease;
}

.section-header:hover {
    background: #365899;
}

.section-header i.fa-chevron-down {
    transition: transform 0.3s ease;
}

.section-header.active i.fa-chevron-down {
    transform: rotate(180deg);
}

.section-content {
    padding: 20px;
    display: none;
    background: white;
}

.section-content.active {
    display: block;
}

.section-content p {
    margin-bottom: 10px;
    line-height: 1.6;
    color: #555;
}

.section-content ul {
    margin: 0;
    padding-left: 20px;
}

.section-content ul li {
    margin-bottom: 8px;
    color: #555;
    line-height: 1.6;
}

/* ========================================
   BOOKING SCHEDULE SECTION - FULL WIDTH
   ======================================== */
.booking-schedule-section {
    width: 100%;
    margin-top: 40px;
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.booking-schedule-section h2 {
    font-size: 24px;
    font-weight: 700;
    color: #1e5ba8;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.schedule-hint {
    color: #666;
    margin-bottom: 25px;
    font-size: 14px;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #1e5ba8;
}

.hint-available {
    background: #d4edda;
    color: #155724;
    padding: 2px 8px;
    border-radius: 4px;
    font-weight: 600;
}

/* ========================================
   WEEK NAVIGATION
   ======================================== */
.week-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding: 15px 0;
    gap: 15px;
}

.week-nav-btn {
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(30, 91, 168, 0.2);
}

.week-nav-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #164a8a 0%, #0f3a6f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
}

.week-nav-btn:active:not(:disabled) {
    transform: translateY(0);
}

.week-nav-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
    opacity: 0.6;
}

.week-label {
    font-weight: 700;
    color: #1e5ba8;
    font-size: 16px;
    text-align: center;
    flex: 1;
}

/* ========================================
   WEEKLY SCHEDULE TABLE
   ======================================== */
.weekly-schedule-container {
    overflow-x: auto;
    margin-bottom: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

.weekly-schedule-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    min-width: 900px;
}

.weekly-schedule-table th,
.weekly-schedule-table td {
    border: 1px solid #e0e0e0;
    padding: 12px 8px;
    text-align: center;
    vertical-align: middle;
}

/* Table Headers */
.time-header {
    width: 80px;
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    color: white;
    font-weight: 700;
    font-size: 14px;
}

.day-header {
    background: linear-gradient(135deg, #1e5ba8 0%, #2c6bb3 100%);
    color: white;
    min-width: 100px;
    padding: 12px 8px;
}

.day-header .day-name {
    font-weight: 700;
    font-size: 14px;
    margin-bottom: 4px;
}

.day-header .day-date {
    font-size: 12px;
    opacity: 0.95;
}

.day-header.today {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    box-shadow: inset 0 0 0 2px rgba(255, 255, 255, 0.3);
}

/* Session Headers */
.session-header {
    background: linear-gradient(135deg, #f0f7ff 0%, #e3f2ff 100%);
}

.session-header td {
    font-weight: 700;
    color: #1e5ba8;
    text-align: left;
    padding: 12px 16px;
    font-size: 15px;
}

.session-header i {
    margin-right: 10px;
    font-size: 16px;
}

/* Time Cells */
.time-cell {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

/* ========================================
   SLOT CELLS
   ======================================== */
.slot-cell {
    padding: 10px 8px;
    min-height: 55px;
    cursor: default;
    transition: all 0.2s ease;
    position: relative;
}

.slot-cell .slot-status {
    font-size: 13px;
    font-weight: 600;
    display: block;
}

/* Available Slots */
.slot-cell.available {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    cursor: pointer;
    border: 2px solid transparent;
}

.slot-cell.available:hover {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    border-color: #1e7e34;
    z-index: 10;
}

.slot-cell.available .slot-status {
    color: #155724;
}

.slot-cell.available:hover .slot-status {
    color: white;
}

/* Booked Slots */
.slot-cell.booked {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    cursor: not-allowed;
}

.slot-cell.booked .slot-status {
    color: #721c24;
}

/* Past Slots */
.slot-cell.past {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
    opacity: 0.7;
}

.slot-cell.past .slot-status {
    color: #6c757d;
}

/* Not Working Slots */
.slot-cell.not-working {
    background: #f5f5f5;
    color: #999;
    cursor: not-allowed;
}

.slot-cell.not-working .slot-status {
    color: #999;
}

/* ========================================
   LEGEND
   ======================================== */
.slots-legend {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding: 15px 0;
    border-top: 2px solid #dee2e6;
    margin-top: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #555;
    font-weight: 500;
}

.slot-indicator {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    flex-shrink: 0;
}

.slot-indicator.available {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: 2px solid #28a745;
}

.slot-indicator.booked {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    border: 2px solid #dc3545;
}

.slot-indicator.past {
    background: #e9ecef;
    border: 2px solid #adb5bd;
}

.slot-indicator.not-working {
    background: #f5f5f5;
    border: 2px solid #ddd;
}

/* ========================================
   ACTION BUTTONS
   ======================================== */
.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.action-buttons .btn-primary,
.action-buttons .btn-secondary {
    flex: 1;
    padding: 15px 24px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-buttons .btn-primary {
    background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
    box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
    color: white;
}

.action-buttons .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 91, 168, 0.4);
}

.action-buttons .btn-secondary {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
    color: white;
}

.action-buttons .btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
}

/* ========================================
   RESPONSIVE DESIGN
   ======================================== */
@media (max-width: 992px) {
    .doctor-profile-section {
        grid-template-columns: 1fr;
    }
    
    .booking-schedule-section {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .doctor-avatar-large {
        width: 150px;
        height: 150px;
    }

    .doctor-details h1 {
        font-size: 16px;
    }

    .booking-schedule-section h2 {
        font-size: 20px;
    }

    /* Week Navigation */
    .week-navigation {
        flex-direction: column;
        gap: 10px;
    }

    .week-nav-btn {
        width: 100%;
        justify-content: center;
    }

    .week-label {
        order: -1;
        font-size: 15px;
    }

    /* Legend */
    .slots-legend {
        justify-content: center;
        gap: 15px;
    }

    .legend-item {
        font-size: 13px;
    }

    /* Action Buttons */
    .action-buttons {
        flex-direction: column;
    }

    .action-buttons .btn-primary,
    .action-buttons .btn-secondary {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .main-content {
        padding: 10px;
    }

    .doctor-details h1 {
        font-size: 15px;
    }

    .doctor-details .specialty {
        font-size: 13px;
    }

    .booking-schedule-section {
        padding: 15px;
    }

    .weekly-schedule-table {
        font-size: 12px;
    }

    .session-header td {
        font-size: 13px;
        padding: 10px 12px;
    }

    .slot-cell {
        padding: 6px 4px;
        min-height: 45px;
    }

    .slot-cell .slot-status {
        font-size: 11px;
    }
}
</style>

<div class="main-content">
    <div class="breadcrumb">
        <a href="/">Trang chủ</a>
        <span>/</span>
        <a href="/tim-bac-si">Tìm bác sĩ</a>
        <span>/</span>
        <span>{{ $doctor['name'] }}</span>
    </div>

    <!-- DOCTOR PROFILE SECTION -->
    <div style="max-width: 1200px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 12px;">
        <div class="doctor-profile-section">
        <!-- LEFT SIDEBAR -->
        <div class="doctor-sidebar">
            <!-- Doctor Info Card -->
            <div class="doctor-info-card">
                <div class="doctor-avatar-large">
                    <img src="{{ $doctor['avatar'] ?? '/frontend/img/Screenshot 2025-10-17 201418.png' }}" alt="{{ $doctor['name'] }}" id="doctor-avatar" onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'">
                </div>
                <div class="doctor-details">
                    <h1 id="doctor-name">{{ $doctor['degree'] ? $doctor['degree'] . ' ' : '' }}{{ $doctor['name'] }}</h1>
                </div>
                <a href="/dat-lich/bieu-mau?doctor={{ $doctor['id'] }}" class="book-appointment-btn">
                    <i class="fas fa-calendar-check"></i> Đặt lịch hẹn
                </a>
            </div>

            <!-- Doctor Rating Card -->
            <div class="doctor-rating-card" onclick="openReviewsModal()" title="Click để xem đánh giá">
                <div class="rating-stars" id="ratingStars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star empty"></i>
                </div>
                <div class="rating-score" id="ratingScore">0.0</div>
                <div class="rating-count" id="ratingCount">0 đánh giá</div>
                <div class="rating-hint"><i class="fas fa-eye"></i> Nhấn để xem chi tiết</div>
            </div>

            <!-- Giới thiệu - dưới ảnh -->
            <div class="doctor-bio-card">
                <h2><i class="fas fa-info-circle"></i> Giới thiệu</h2>
                <div id="doctor-bio">
                    <p>{{ $doctor['bio'] ?? 'Bác sĩ có nhiều năm kinh nghiệm trong lĩnh vực y khoa, luôn tận tâm với bệnh nhân.' }}</p>
                </div>
            </div>
        </div>

        <!-- RIGHT CONTENT -->
        <div class="doctor-content">
            <!-- Chuyên môn Section -->
            <div class="info-section">
                <div class="section-header active" onclick="toggleSection(this)">
                    <span><i class="fas fa-stethoscope"></i> Chuyên môn</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content active">
                    <p id="doctor-specialty-text">Bác sĩ chuyên khoa {{ $doctor['specialty'] ?? 'Tai – Mũi – Họng' }}.</p>
                </div>
            </div>

            <!-- Nơi làm việc Section -->
            <div class="info-section">
                <div class="section-header active" onclick="toggleSection(this)">
                    <span><i class="fas fa-hospital"></i> Nơi làm việc</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content active">
                    <p id="doctor-clinic">{{ $doctor['clinic'] ?? 'Khoa Tai – Mũi – Họng – Bệnh viện Đa khoa Quốc tế Nam Sài Gòn.' }}</p>
                </div>
            </div>

            <!-- Lĩnh vực chuyên sâu Section -->
            <div class="info-section">
                <div class="section-header active" onclick="toggleSection(this)">
                    <span><i class="fas fa-microscope"></i> Lĩnh vực chuyên sâu</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content active">
                    <p>Bác sĩ chuyên về {{ $doctor['specialty'] ?? 'chuyên khoa' }}.</p>
                </div>
            </div>

            <!-- Kinh nghiệm Section -->
            <div class="info-section">
                <div class="section-header" onclick="toggleSection(this)">
                    <span><i class="fas fa-briefcase"></i> Kinh nghiệm</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content">
                    @if($doctor['experience'] && $doctor['experience'] > 0)
                    <p id="doctor-experience-text">Hơn <span id="doctor-experience-years">{{ $doctor['experience'] }}</span> năm kinh nghiệm trong lĩnh vực {{ $doctor['specialty'] ?? 'y khoa' }}.</p>
                    @else
                    <p id="doctor-experience-text">Bác sĩ có kinh nghiệm trong lĩnh vực {{ $doctor['specialty'] ?? 'y khoa' }}.</p>
                    @endif
                </div>
            </div>

            <!-- Giới tính Section -->
            @if($doctor['gender'])
            <div class="info-section">
                <div class="section-header" onclick="toggleSection(this)">
                    <span><i class="fas fa-user"></i> Giới tính</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content">
                    <p>{{ $doctor['gender'] }}</p>
                </div>
            </div>
            @endif

            <!-- Bằng cấp chuyên môn Section -->
            @if($doctor['degree'])
            <div class="info-section">
                <div class="section-header" onclick="toggleSection(this)">
                    <span><i class="fas fa-graduation-cap"></i> Bằng cấp chuyên môn</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="section-content">
                    <p>{{ $doctor['degree'] }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>

    <!-- BOOKING SCHEDULE SECTION - FULL WIDTH -->
    <div class="booking-schedule-section">
        <h2><i class="fas fa-clock"></i> Lịch khám của bác sĩ</h2>
        <p class="schedule-hint">Click vào ô <span class="hint-available">còn trống</span> để đặt lịch khám với bác sĩ</p>
        
        <!-- Week Navigation -->
        <div class="week-navigation">
            <button class="week-nav-btn" id="prevWeekBtn" onclick="changeWeek(-1)">
                <i class="fas fa-chevron-left"></i> Tuần trước
            </button>
            <span class="week-label" id="weekLabel">Tuần này</span>
            <button class="week-nav-btn" id="nextWeekBtn" onclick="changeWeek(1)">
                Tuần sau <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <!-- Weekly Schedule Table -->
        <div class="weekly-schedule-container">
            <table class="weekly-schedule-table">
                <thead>
                    <tr>
                        <th class="time-header">Giờ</th>
                        <th class="day-header" data-day="Monday">
                            <div class="day-name">Thứ 2</div>
                            <div class="day-date" id="date-Monday"></div>
                        </th>
                        <th class="day-header" data-day="Tuesday">
                            <div class="day-name">Thứ 3</div>
                            <div class="day-date" id="date-Tuesday"></div>
                        </th>
                        <th class="day-header" data-day="Wednesday">
                            <div class="day-name">Thứ 4</div>
                            <div class="day-date" id="date-Wednesday"></div>
                        </th>
                        <th class="day-header" data-day="Thursday">
                            <div class="day-name">Thứ 5</div>
                            <div class="day-date" id="date-Thursday"></div>
                        </th>
                        <th class="day-header" data-day="Friday">
                            <div class="day-name">Thứ 6</div>
                            <div class="day-date" id="date-Friday"></div>
                        </th>
                        <th class="day-header" data-day="Saturday">
                            <div class="day-name">Thứ 7</div>
                            <div class="day-date" id="date-Saturday"></div>
                        </th>
                        <th class="day-header" data-day="Sunday">
                            <div class="day-name">CN</div>
                            <div class="day-date" id="date-Sunday"></div>
                        </th>
                    </tr>
                </thead>
                <tbody id="schedule-body">
                    <tr><td colspan="8" class="loading-schedule">Đang tải lịch...</td></tr>
                </tbody>
            </table>
        </div>
        
        <div class="slots-legend">
            <div class="legend-item"><span class="slot-indicator available"></span> Còn trống</div>
            <div class="legend-item"><span class="slot-indicator booked"></span> Đã có lịch</div>
            <div class="legend-item"><span class="slot-indicator past"></span> Đã qua</div>
            <div class="legend-item"><span class="slot-indicator not-working"></span> Không làm việc</div>
        </div>

        <div class="action-buttons">
            <a href="/dat-lich/bieu-mau?doctor={{ $doctor['id'] }}" class="btn-primary"><i class="fas fa-calendar-check"></i> Đặt lịch khám</a>
            <a href="tel:19001234" class="btn-secondary"><i class="fas fa-phone"></i> Gọi tư vấn: 1900 1234</a>
        </div>
    </div>
</div>

<!-- Reviews Modal -->
<div class="reviews-modal" id="reviewsModal">
    <div class="reviews-modal-content">
        <div class="reviews-modal-header">
            <h3><i class="fas fa-star"></i> Đánh giá về bác sĩ</h3>
            <button class="reviews-modal-close" onclick="closeReviewsModal()">&times;</button>
        </div>
        <div class="reviews-summary" id="reviewsSummary">
            <div class="reviews-summary-score">
                <div><span class="score" id="modalRatingScore">0.0</span><span class="max-score">/5</span></div>
                <div class="reviews-summary-stars" id="modalRatingStars"></div>
                <div class="reviews-summary-count" id="modalRatingCount">0 đánh giá</div>
            </div>
        </div>
        <div class="reviews-modal-body">
            <div class="reviews-list" id="reviewsList">
                <div class="reviews-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    Đang tải đánh giá...
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const doctorId = {{ $doctor['id'] }};
        const API_BASE = '/api';
        
        // Current week offset (0 = this week, 1 = next week, etc.)
        let weekOffset = 0;
        
        // Store doctor data
        let doctorData = {
            id: doctorId,
            specialization_id: null,
            full_name: '{{ $doctor['name'] }}'
        };
        
        // Store work schedule
        let workSchedule = {};
        
        // Store booked slots
        let bookedSlots = {};
        
        // Time slots configuration - khoảng cách 1 tiếng
        const morningSlots = ['07:00', '08:00', '09:00', '10:00', '11:00'];
        const afternoonSlots = ['13:00', '14:00', '15:00', '16:00', '17:00'];
        
        const dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        // Fetch doctor data from API
        fetch(`${API_BASE}/public/doctors/${doctorId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.id) {
                    doctorData = data;
                    updateDoctorInfo(data);
                }
            })
            .catch(error => {
                });
        
        // Fetch work schedule
        fetch(`${API_BASE}/public/doctors/${doctorId}/work-schedule`)
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    // Convert to lookup object
                    result.data.forEach(schedule => {
                        workSchedule[schedule.day_of_week] = schedule;
                    });
                    loadWeekSchedule();
                }
            })
            .catch(error => {
                console.error('Error loading work schedule:', error);
                loadWeekSchedule(); // Still load week schedule with empty work schedule
            });
        
        function updateDoctorInfo(doctor) {
            if (doctor.full_name) {
                const degree = doctor.degree ? doctor.degree + ' ' : '';
                const nameEl = document.getElementById('doctor-name');
                if (nameEl) nameEl.textContent = degree + doctor.full_name;
            }
            if (doctor.avatar_url) {
                const avatarEl = document.getElementById('doctor-avatar');
                if (avatarEl) avatarEl.src = doctor.avatar_url;
            }
            if (doctor.specialization && doctor.specialization.name) {
                const specEl = document.getElementById('doctor-specialty');
                if (specEl) specEl.textContent = doctor.specialization.name;
            }
            if (doctor.description) {
                const bioEl = document.getElementById('doctor-bio');
                if (bioEl) bioEl.innerHTML = '<p>' + doctor.description + '</p>';
            }
            if (doctor.clinic) {
                const clinicEl = document.getElementById('doctor-clinic');
                if (clinicEl) clinicEl.textContent = doctor.clinic.name;
            }
        }
        
        function formatTime(timeStr) {
            if (!timeStr) return '';
            return timeStr.substring(0, 5);
        }
        
        function formatDayGroup(days, dayNames) {
            if (days.length === 1) {
                return dayNames[days[0]];
            }
            
            const indices = days.map(d => dayOrder.indexOf(d)).sort((a, b) => a - b);
            
            let isConsecutive = true;
            for (let i = 1; i < indices.length; i++) {
                if (indices[i] - indices[i-1] !== 1) {
                    isConsecutive = false;
                    break;
                }
            }
            
            if (isConsecutive && days.length > 2) {
                return dayNames[days[0]] + ' - ' + dayNames[days[days.length - 1]];
            } else {
                return days.map(d => dayNames[d]).join(', ');
            }
        }
        
        function getWeekDates(offset = 0) {
            const today = new Date();
            const currentDay = today.getDay(); // 0 = Sunday, 1 = Monday, etc.
            
            // Calculate Monday of current week
            const monday = new Date(today);
            const daysToMonday = currentDay === 0 ? -6 : 1 - currentDay;
            monday.setDate(today.getDate() + daysToMonday + (offset * 7));
            
            const dates = {};
            dayOrder.forEach((day, index) => {
                const date = new Date(monday);
                date.setDate(monday.getDate() + index);
                dates[day] = date;
            });
            
            return dates;
        }
        
        function formatDateShort(date) {
            return `${date.getDate()}/${date.getMonth() + 1}`;
        }
        
        function formatDateISO(date) {
            return date.toISOString().split('T')[0];
        }
        
        async function loadWeekSchedule() {
            const dates = getWeekDates(weekOffset);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Update week label
            const firstDate = dates['Monday'];
            const lastDate = dates['Sunday'];
            const weekLabel = document.getElementById('weekLabel');
            
            if (weekOffset === 0) {
                weekLabel.textContent = `Tuần này (${formatDateShort(firstDate)} - ${formatDateShort(lastDate)})`;
            } else if (weekOffset === 1) {
                weekLabel.textContent = `Tuần sau (${formatDateShort(firstDate)} - ${formatDateShort(lastDate)})`;
            } else {
                weekLabel.textContent = `${formatDateShort(firstDate)} - ${formatDateShort(lastDate)}`;
            }
            
            // Disable prev button if showing current week
            document.getElementById('prevWeekBtn').disabled = weekOffset <= 0;
            
            // Update header dates and highlight today
            dayOrder.forEach(day => {
                const dateEl = document.getElementById(`date-${day}`);
                const headerEl = dateEl.closest('.day-header');
                const date = dates[day];
                
                dateEl.textContent = formatDateShort(date);
                
                // Highlight today
                if (date.toDateString() === new Date().toDateString()) {
                    headerEl.classList.add('today');
                } else {
                    headerEl.classList.remove('today');
                }
            });
            
            // Fetch booked slots for the week
            const startDate = formatDateISO(dates['Monday']);
            const endDate = formatDateISO(dates['Sunday']);
            
            try {
                const response = await fetch(`${API_BASE}/public/doctors/${doctorId}/booked-slots?start_date=${startDate}&end_date=${endDate}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    // Convert to lookup object - handle multi-hour appointments
                    bookedSlots = {};
                    result.data.forEach(slot => {
                        const startTime = slot.start_time.substring(0, 5);
                        const endTime = slot.end_time.substring(0, 5);
                        
                        // Calculate duration in hours (round up to nearest hour)
                        const startHour = parseInt(startTime.split(':')[0]);
                        const startMin = parseInt(startTime.split(':')[1]);
                        const endHour = parseInt(endTime.split(':')[0]);
                        const endMin = parseInt(endTime.split(':')[1]);
                        
                        const durationMinutes = (endHour * 60 + endMin) - (startHour * 60 + startMin);
                        const durationHours = Math.ceil(durationMinutes / 60); // Round up
                        
                        // Mark all affected hour slots as booked
                        for (let i = 0; i < durationHours; i++) {
                            const hour = startHour + i;
                            const timeKey = hour.toString().padStart(2, '0') + ':00';
                            const key = `${slot.appointment_date}_${timeKey}`;
                            bookedSlots[key] = true;
                            }
                    });
                    }
            } catch (error) {
                console.error('Error loading booked slots:', error);
            }
            
            // Build schedule table
            renderScheduleTable(dates, today);
        }
        
        function renderScheduleTable(dates, today) {
            const tbody = document.getElementById('schedule-body');
            let html = '';
            
            // Morning header
            html += `<tr class="session-header"><td colspan="8"><i class="fas fa-sun"></i> Buổi sáng (07:00 - 12:00)</td></tr>`;
            
            // Morning slots
            morningSlots.forEach(time => {
                html += renderTimeRow(time, dates, today);
            });
            
            // Afternoon header
            html += `<tr class="session-header"><td colspan="8"><i class="fas fa-cloud-sun"></i> Buổi chiều (13:00 - 18:00)</td></tr>`;
            
            // Afternoon slots
            afternoonSlots.forEach(time => {
                html += renderTimeRow(time, dates, today);
            });
            
            tbody.innerHTML = html;
        }
        
        function renderTimeRow(time, dates, today) {
            const now = new Date();
            let html = `<tr><td class="time-cell">${time}</td>`;
            
            dayOrder.forEach(day => {
                const date = dates[day];
                const dateStr = formatDateISO(date);
                const schedule = workSchedule[day];
                
                let status = 'not-working';
                let statusText = 'Nghỉ';
                let clickable = false;
                
                // Check if doctor works this day
                if (schedule && schedule.is_available) {
                    const startTime = schedule.start_time.substring(0, 5);
                    const endTime = schedule.end_time.substring(0, 5);
                    
                    // Check if this time slot is within working hours
                    if (time >= startTime && time < endTime) {
                        // Check break time
                        const breakStart = schedule.break_start_time ? schedule.break_start_time.substring(0, 5) : null;
                        const breakEnd = schedule.break_end_time ? schedule.break_end_time.substring(0, 5) : null;
                        
                        if (breakStart && breakEnd && time >= breakStart && time < breakEnd) {
                            status = 'not-working';
                            statusText = 'Nghỉ trưa';
                        } else {
                            // Check if past
                            const slotDateTime = new Date(dateStr + 'T' + time + ':00');
                            if (slotDateTime < now) {
                                status = 'past';
                                statusText = 'Đã qua';
                            } else {
                                // Check if booked
                                const key = `${dateStr}_${time}`;
                                if (bookedSlots[key]) {
                                    status = 'booked';
                                    statusText = 'Đã đặt';
                                } else {
                                    status = 'available';
                                    statusText = 'Trống';
                                    clickable = true;
                                }
                            }
                        }
                    }
                }
                
                html += `<td class="slot-cell ${status}" 
                            ${clickable ? `onclick="bookSlot('${dateStr}', '${time}')" title="Click để đặt lịch"` : `title="${statusText}"`}>
                            <span class="slot-status">${statusText}</span>
                        </td>`;
            });
            
            html += '</tr>';
            return html;
        }
        
        // Make functions available globally
        window.changeWeek = function(direction) {
            weekOffset += direction;
            if (weekOffset < 0) weekOffset = 0;
            loadWeekSchedule();
        };
        
        window.bookSlot = function(date, time) {
            const specId = doctorData.specialization_id || '';
            const url = `/dat-lich/bieu-mau?doctor=${doctorId}&specialization=${specId}&date=${date}&time=${time}`;
            window.location.href = url;
        };
    });
// Toggle accordion sections
    window.toggleSection = function(header) {
        const content = header.nextElementSibling;
        const isActive = header.classList.contains('active');
        
        if (isActive) {
            header.classList.remove('active');
            content.classList.remove('active');
        } else {
            header.classList.add('active');
            content.classList.add('active');
        }
    };

    // ============ REVIEWS SECTION ============
    let doctorReviews = [];
    let doctorRatingAvg = 0;
    let doctorRatingCount = 0;

    // Load doctor reviews
    async function loadDoctorReviews() {
        const doctorId = {{ $doctor['id'] }};
        try {
            const response = await fetch(`/api/public/doctors/${doctorId}/reviews`);
            const result = await response.json();
            
            if (result.success && result.data) {
                doctorReviews = result.data.reviews || [];
                doctorRatingAvg = result.data.average_rating || 0;
                doctorRatingCount = result.data.total_reviews || 0;
                
                updateRatingDisplay();
            }
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    }

    function updateRatingDisplay() {
        // Update rating card
        const ratingStarsEl = document.getElementById('ratingStars');
        const ratingScoreEl = document.getElementById('ratingScore');
        const ratingCountEl = document.getElementById('ratingCount');

        ratingScoreEl.textContent = doctorRatingAvg.toFixed(1);
        ratingCountEl.textContent = `${doctorRatingCount} đánh giá`;
        ratingStarsEl.innerHTML = renderStars(doctorRatingAvg);
    }

    function renderStars(rating, size = 24) {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        
        let html = '';
        for (let i = 0; i < fullStars; i++) {
            html += `<i class="fas fa-star" style="font-size:${size}px;color:#f4c430;"></i>`;
        }
        if (hasHalfStar) {
            html += `<i class="fas fa-star-half-alt" style="font-size:${size}px;color:#f4c430;"></i>`;
        }
        for (let i = 0; i < emptyStars; i++) {
            html += `<i class="fas fa-star" style="font-size:${size}px;color:#ddd;"></i>`;
        }
        return html;
    }

    function renderReviewStars(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                html += '<i class="fas fa-star"></i>';
            } else {
                html += '<i class="fas fa-star empty"></i>';
            }
        }
        return html;
    }

    window.openReviewsModal = function() {
        const modal = document.getElementById('reviewsModal');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Update modal summary
        document.getElementById('modalRatingScore').textContent = doctorRatingAvg.toFixed(1);
        document.getElementById('modalRatingStars').innerHTML = renderStars(doctorRatingAvg, 20);
        document.getElementById('modalRatingCount').textContent = `${doctorRatingCount} đánh giá`;
        
        // Render reviews list
        renderReviewsList();
    };

    window.closeReviewsModal = function() {
        const modal = document.getElementById('reviewsModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    };

    // Close modal on backdrop click
    document.getElementById('reviewsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReviewsModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeReviewsModal();
        }
    });

    function renderReviewsList() {
        const listEl = document.getElementById('reviewsList');
        
        if (doctorReviews.length === 0) {
            listEl.innerHTML = `
                <div class="reviews-empty">
                    <i class="fas fa-comment-slash"></i>
                    <p>Chưa có đánh giá nào cho bác sĩ này</p>
                </div>
            `;
            return;
        }

        const html = doctorReviews.map(review => {
            const authorName = review.patient_name || 'Ẩn danh';
            const initial = authorName.charAt(0).toUpperCase();
            const date = new Date(review.created_at).toLocaleDateString('vi-VN');
            
            return `
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-author">
                            <div class="review-avatar">${initial}</div>
                            <div class="review-author-info">
                                <h4>${authorName}</h4>
                                <span class="review-date">${date}</span>
                            </div>
                        </div>
                        <div class="review-rating">
                            ${renderReviewStars(review.rating)}
                        </div>
                    </div>
                    <div class="review-content">
                        ${review.comment || 'Không có nhận xét'}
                    </div>
                </div>
            `;
        }).join('');

        listEl.innerHTML = html;
    }

    // Load reviews on page load
    loadDoctorReviews();
</script>
@endsection
