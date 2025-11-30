@extends('layouts.public')

@section('title', 'Bảng Giá Tiền Giường - Hệ thống đặt lịch hẹn')

@section('styles')
<style>
    /* Banner Section */
    .banner-section {
        position: relative;
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(30, 91, 168, 0.9), rgba(13, 58, 110, 0.9)), url('/frontend/img/img1.jpg');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
        padding: 40px 20px;
    }
    .banner-section h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    .banner-section p {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    .banner-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }
    .banner-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    .banner-btn.primary {
        background: #ff9800;
        color: white;
    }
    .banner-btn.primary:hover {
        background: #f57c00;
        transform: translateY(-2px);
    }
    .banner-btn.secondary {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid white;
    }
    .banner-btn.secondary:hover {
        background: white;
        color: #1e5ba8;
    }

    /* Content Container */
    .content-container {
        max-width: 1100px;
        margin: -50px auto 50px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        padding: 40px;
    }

    /* Title */
    .page-title {
        text-align: center;
        color: #1e5ba8;
        margin-bottom: 30px;
        font-size: 2rem;
    }
    .page-title span {
        font-weight: bold;
        color: #0d3a6e;
    }

    /* Intro Text */
    .intro-text {
        color: #666;
        margin-bottom: 25px;
        line-height: 1.8;
    }
    .currency-note {
        text-align: right;
        font-weight: bold;
        margin-bottom: 20px;
        color: #555;
    }

    /* Table Styles */
    .table-wrapper {
        overflow-x: auto;
        margin-bottom: 30px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }
    table thead {
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        color: white;
    }
    table th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 0.95rem;
    }
    table td {
        padding: 14px 12px;
        border-bottom: 1px solid #eee;
        color: #555;
    }
    table tbody tr:nth-child(even) {
        background: #f8fafc;
    }
    table tbody tr:hover {
        background: #e8f4fc;
    }
    table tbody tr:last-child td {
        border-bottom: none;
    }
    .number {
        text-align: center;
        font-weight: 600;
        color: #1e5ba8;
    }
    .price {
        text-align: right;
        font-weight: 600;
    }

    /* Notes Box */
    .notes-box {
        background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        border-left: 5px solid #ff9800;
        padding: 20px 25px;
        margin: 30px 0;
        border-radius: 0 12px 12px 0;
    }
    .notes-box p {
        margin-bottom: 10px;
        color: #666;
        line-height: 1.7;
    }
    .notes-box p:last-child {
        margin-bottom: 0;
    }
    .notes-box strong {
        color: #e65100;
    }

    /* Announcement */
    .announcement {
        margin-top: 40px;
        text-align: center;
    }
    .announcement img {
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .banner-section {
            height: 300px;
        }
        .banner-section h1 {
            font-size: 1.8rem;
        }
        .banner-buttons {
            flex-direction: column;
        }
        .content-card {
            padding: 25px;
        }
        .page-title {
            font-size: 1.5rem;
        }
        table th, table td {
            padding: 10px 8px;
            font-size: 0.85rem;
        }
    }
</style>
@endsection

@section('content')
<div class="banner-section">
    <h1>💰 Bảng Giá Dịch Vụ</h1>
    <p>Minh bạch - Rõ ràng - Chất lượng</p>
    <div class="banner-buttons">
        <a href="/dat-lich" class="banner-btn primary">
            <i class="fas fa-calendar-check"></i> Đặt lịch hẹn
        </a>
        <a href="/tim-bac-si" class="banner-btn secondary">
            <i class="fas fa-user-md"></i> Tìm bác sĩ
        </a>
    </div>
</div>

<div class="content-container">
    <div class="content-card">
        <h1 class="page-title">Bảng giá <span>tiền giường</span></h1>
        
        <div class="intro-text">
            <p>Hệ thống đặt lịch hẹn thông báo đến Quý khách hàng và toàn thể nhân viên về việc phát hành giá dịch vụ tiền giường của Bệnh viện như sau:</p>
        </div>
        
        <div class="currency-note">ĐVT: VNĐ</div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="number">STT</th>
                        <th>TÊN DỊCH VỤ KỸ THUẬT</th>
                        <th>ĐƠN VỊ</th>
                        <th class="price">GIÁ DỊCH VỤ</th>
                        <th class="price">GIÁ BHYT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="number">1</td>
                        <td>Dịch vụ phòng đơn Loại 1</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">8,000,000</td>
                        <td class="price">171,600</td>
                    </tr>
                    <tr>
                        <td class="number">2</td>
                        <td>Dịch vụ phòng đơn Loại 2</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">6,000,000</td>
                        <td class="price">171,600</td>
                    </tr>
                    <tr>
                        <td class="number">3</td>
                        <td>Dịch vụ phòng đôi</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">5,000,000</td>
                        <td class="price">168,100</td>
                    </tr>
                    <tr>
                        <td class="number">4</td>
                        <td>Dịch vụ phòng 2 giường</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">1,200,000</td>
                        <td class="price">168,100</td>
                    </tr>
                    <tr>
                        <td class="number">5</td>
                        <td>Dịch vụ phòng trên 2 giường Loại 1</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">600,000</td>
                        <td class="price">168,100</td>
                    </tr>
                    <tr>
                        <td class="number">6</td>
                        <td>Dịch vụ phòng trên 2 giường Loại 2 (lầu 11)</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">520,000</td>
                        <td class="price">168,100</td>
                    </tr>
                    <tr>
                        <td class="number">7</td>
                        <td>Dịch vụ phòng hồi sức ICU (ngày đêm)</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">2,150,000</td>
                        <td class="price">312,200</td>
                    </tr>
                    <tr>
                        <td class="number">8</td>
                        <td>Dịch vụ phòng hồi sức ICU Lầu 5 (ngày đêm)</td>
                        <td>Phòng/ Ngày đêm</td>
                        <td class="price">1,250,000</td>
                        <td class="price">171,600</td>
                    </tr>
                    <tr>
                        <td class="number">9</td>
                        <td>Dịch vụ phòng hồi sức ICU (giờ)</td>
                        <td>Phòng/ Giờ</td>
                        <td class="price">90,000</td>
                        <td class="price">–</td>
                    </tr>
                    <tr>
                        <td class="number">10</td>
                        <td>Dịch vụ phòng hồi sức ICU Lầu 5 (giờ)</td>
                        <td>Phòng/ Giờ</td>
                        <td class="price">50,000</td>
                        <td class="price">–</td>
                    </tr>
                    <tr>
                        <td class="number">11</td>
                        <td>Dịch vụ phòng HSCC – sau phẫu thuật (giờ)</td>
                        <td>Phòng/ Giờ</td>
                        <td class="price">80,000</td>
                        <td class="price">–</td>
                    </tr>
                    <tr>
                        <td class="number">12</td>
                        <td>Dịch vụ giường cấp cứu dưới 8 giờ</td>
                        <td>Lượt</td>
                        <td class="price">500,000</td>
                        <td class="price">–</td>
                    </tr>
                    <tr>
                        <td class="number">13</td>
                        <td>Dịch vụ giường cấp cứu từ 8 đến 24 giờ</td>
                        <td>Lượt</td>
                        <td class="price">1,000,000</td>
                        <td class="price">–</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="notes-box">
            <p><strong>📌 Lưu ý:</strong> Riêng dịch vụ phòng đơn Loại 1,2 đã bao gồm 03 suất ăn chính, 01 suất ăn phụ (trái cây), nước uống theo tiêu chuẩn của bệnh viện.</p>
            <p>Bảng giá áp dụng từ ngày <strong>29/03/2024</strong> đến khi có thông báo mới.</p>
            <p>Giá áp dụng cho người nước ngoài tăng thêm 25%.</p>
        </div>

        <div class="announcement">
            <img src="/frontend/img/fb_1.jpg" alt="Thông báo dịch vụ">
        </div>
    </div>
</div>
@endsection
