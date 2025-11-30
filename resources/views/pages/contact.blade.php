@extends('layouts.public')

@section('title', 'Liên Hệ - Hệ thống đặt lịch hẹn')

@section('styles')
<style>
    /* Contact Banner */
    .contact-banner {
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        padding: 60px 20px;
        text-align: center;
        color: white;
    }
    .contact-banner h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 10px;
    }
    .contact-banner p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Contact Content */
    .contact-content {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        margin-bottom: 50px;
    }
    .contact-info-section {
        background: white;
        padding: 35px;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }
    .contact-info-section h2 {
        color: #1e5ba8;
        font-size: 1.5rem;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 20px;
        padding: 18px;
        background: #f8fafc;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .info-box:hover {
        transform: translateX(5px);
        background: #e8f4fc;
    }
    .info-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 20px;
        flex-shrink: 0;
    }
    .info-text h3 {
        color: #2c3e50;
        font-size: 1rem;
        margin-bottom: 6px;
    }
    .info-text p {
        color: #666;
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.6;
    }

    /* Team Section */
    .team-section {
        background: white;
        padding: 50px 35px;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 50px;
    }
    .team-section h2 {
        color: #1e5ba8;
        font-size: 1.8rem;
        text-align: center;
        margin-bottom: 15px;
    }
    .team-subtitle {
        text-align: center;
        color: #7f8c8d;
        font-size: 1rem;
        margin-bottom: 40px;
    }
    .team-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
    }
    .team-member {
        background: linear-gradient(135deg, #f8fafc 0%, #e8f4fc 100%);
        padding: 25px;
        border-radius: 15px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .team-member:hover {
        transform: translateY(-8px);
        border-color: #1e5ba8;
        box-shadow: 0 12px 35px rgba(30, 91, 168, 0.15);
    }
    .member-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        font-size: 40px;
        color: white;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(30, 91, 168, 0.2);
    }
    .member-name {
        color: #2c3e50;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .member-role {
        color: #1e5ba8;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .member-contact {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }
    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 0.85rem;
    }
    .contact-item i {
        color: #1e5ba8;
        width: 16px;
    }

    /* Map Section */
    .map-section {
        background: white;
        padding: 35px;
        border-radius: 16px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        margin-bottom: 50px;
    }
    .map-section h2 {
        color: #1e5ba8;
        font-size: 1.5rem;
        margin-bottom: 25px;
        text-align: center;
    }
    .map-container {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .contact-content {
            grid-template-columns: 1fr;
        }
        .team-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 768px) {
        .contact-banner h1 {
            font-size: 1.8rem;
        }
        .contact-info-section {
            padding: 25px;
        }
    }
</style>
@endsection

@section('content')
<div class="contact-banner">
    <h1>Liên Hệ Với Chúng Tôi</h1>
    <p>Nhóm 5 - Hệ Thống Đặt Lịch Hẹn Khám Bệnh</p>
</div>

<div class="main-content">
    <div class="contact-content">
        <div class="contact-info-section">
            <h2><i class="fas fa-info-circle"></i> Thông Tin Liên Hệ</h2>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-text">
                    <h3>Địa Chỉ</h3>
                    <p>70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, Thành phố Hồ Chí Minh, Việt Nam</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-text">
                    <h3>Email</h3>
                    <p>nhom5@gmail.com</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="info-text">
                    <h3>Điện Thoại</h3>
                    <p>Hotline: 1800 6767</p>
                    <p>Hỗ trợ: 0563006830</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-text">
                    <h3>Giờ Làm Việc</h3>
                    <p>Thứ 2 - Thứ 7: 7:00 - 17:00</p>
                    <p>Chủ nhật: Nghỉ</p>
                </div>
            </div>
        </div>

        <div class="contact-info-section">
            <h2><i class="fas fa-building"></i> Về Dự Án</h2>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="info-text">
                    <h3>Tên Dự Án</h3>
                    <p>Hệ Thống Đặt Lịch Hẹn Khám Bệnh Online</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="info-text">
                    <h3>Nhóm Thực Hiện</h3>
                    <p>Nhóm 5 - Gồm 4 thành viên</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="info-text">
                    <h3>Trường</h3>
                    <p>Trường Đại Học Giao Thông Vận Tải TP.HCM</p>
                    <p>Môn Đồ Án Thực Tế Công nghệ Phần Mềm</p>
                </div>
            </div>

            <div class="info-box">
                <div class="info-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-text">
                    <h3>Thời Gian</h3>
                    <p>Năm học 2024-2025</p>
                </div>
            </div>
        </div>
    </div>

    <div class="team-section">
        <h2>Thành Viên Nhóm 5</h2>
        <p class="team-subtitle">Đội ngũ phát triển hệ thống đặt lịch hẹn khám bệnh</p>

        <div class="team-grid">
            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="member-name">Lê Văn Trí</div>
                <div class="member-role">Trưởng Nhóm - Backend</div>
                <div class="member-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>trilv2706@ut.edu.vn</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>0901 234 567</span>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="member-name">Nguyễn Ngự Đăng</div>
                <div class="member-role">Backend Developer</div>
                <div class="member-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>nguyennudang@student.edu.vn</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>0902 345 678</span>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-cog"></i>
                </div>
                <div class="member-name">Nguyễn Tiến Đức</div>
                <div class="member-role">Frontend Developer</div>
                <div class="member-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>nguyentienduc@student.edu.vn</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>0903 456 789</span>
                    </div>
                </div>
            </div>

            <div class="team-member">
                <div class="member-avatar">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="member-name">Bùi Văn Ý</div>
                <div class="member-role">UI/UX Designer & Tester</div>
                <div class="member-contact">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>buivany@student.edu.vn</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>0904 567 890</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="map-section">
        <h2><i class="fas fa-map-marked-alt"></i> Vị Trí Của Chúng Tôi</h2>
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.434604314362!2d106.6155376!3d10.8657455!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752b2a11844fb9%3A0xbed3d5f0a6d6e0fe!2zVHJ1bmcgxJDhu6ljIMSQ4bqhaSDEkOG6oW4gR2lhdSBUaGFuZyBWxINuIFRBWSDhuq1uICgtIGPDs2UgMykg!5e0!3m2!1svi!2s!4v1700000000000!5m2!1svi!2s"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </div>
</div>
@endsection
