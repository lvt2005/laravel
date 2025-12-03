@extends('layouts.public')

@section('title', 'Hướng dẫn BHYT - Hệ thống đặt lịch hẹn')

@section('styles')
<style>
    /* Banner Section */
    .banner-section {
        position: relative;
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(30, 91, 168, 0.9), rgba(13, 58, 110, 0.9)), url('/frontend/img/image-4.jpg');
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
        max-width: 1000px;
        margin: -50px auto 50px;
        padding: 0 20px;
        position: relative;
        z-index: 10;
    }
    .content-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .content-header {
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        color: white;
        padding: 30px 40px;
        text-align: center;
    }
    .content-header h1 {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }
    .content-header p {
        opacity: 0.9;
    }
    .content-body {
        padding: 40px;
    }
    .intro-text {
        color: #666;
        margin-bottom: 30px;
        line-height: 1.8;
    }

    /* Section Styles */
    .section {
        margin-bottom: 40px;
    }
    .section h2 {
        color: #1e5ba8;
        font-size: 1.5rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #1e5ba8;
        display: inline-block;
    }
    .section h3 {
        color: #0d3a6e;
        font-size: 1.15rem;
        margin: 25px 0 15px;
    }
    .section p {
        color: #555;
        margin-bottom: 15px;
        line-height: 1.8;
    }
    .section ul {
        margin: 15px 0 20px 25px;
    }
    .section ul li {
        margin-bottom: 12px;
        color: #555;
        line-height: 1.7;
        padding-left: 10px;
    }
    .section ul li::marker {
        color: #1e5ba8;
        font-weight: bold;
    }

    /* Image Container */
    .image-container {
        text-align: center;
        margin: 30px 0;
        padding: 25px;
        background: #f8fafc;
        border-radius: 12px;
    }
    .image-container img {
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }
    .image-caption {
        font-style: italic;
        color: #666;
        margin-top: 15px;
        font-size: 0.9rem;
    }

    /* Table Styles */
    .table-wrapper {
        overflow-x: auto;
        margin: 25px 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }
    table thead {
        background: linear-gradient(135deg, #1e5ba8 0%, #0d3a6e 100%);
        color: white;
    }
    table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }
    table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #555;
    }
    table tbody tr:hover {
        background: #f8fafc;
    }
    table tbody tr:last-child td {
        border-bottom: none;
    }
    table ul {
        margin: 0;
        padding-left: 18px;
    }
    table ul li {
        margin-bottom: 5px;
    }

    /* Note Box */
    .note-box {
        background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        border-left: 5px solid #ff9800;
        padding: 20px 25px;
        margin: 25px 0;
        border-radius: 0 12px 12px 0;
    }
    .note-box strong {
        color: #e65100;
        display: block;
        margin-bottom: 8px;
        font-size: 1rem;
    }
    .note-box p {
        margin: 0;
        color: #666;
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
        .content-body {
            padding: 25px;
        }
        .section h2 {
            font-size: 1.3rem;
        }
        table th, table td {
            padding: 10px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="banner-section">
    <h1>🏥 Hướng Dẫn Khám Chữa Bệnh BHYT</h1>
    <p>Đảm bảo quyền lợi chăm sóc sức khỏe của bạn</p>
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
        <div class="content-body">
            <p class="intro-text">
                Để quá trình khám chữa bệnh diễn ra nhanh chóng và đảm bảo đầy đủ quyền lợi theo quy định, 
                quý khách vui lòng chuẩn bị các giấy tờ sau và xuất trình tại quầy tiếp nhận:
            </p>

            <div class="section">
                <h2>1. Giấy tờ chứng minh thông tin BHYT</h2>
                <p><strong>Thẻ BHYT bản gốc hoặc mã số thẻ BHYT</strong></p>
                <p>Một trong các hình thức thay thế nếu không mang thẻ giấy:</p>
                <ul>
                    <li>Xuất trình thẻ bảo hiểm y tế qua ứng dụng VssID</li>
                    <li>Căn cước công dân (CCCD) đã tích hợp thông tin BHYT</li>
                    <li>Ứng dụng VNeID có thông tin thẻ BHYT</li>
                </ul>

                <h3>Trường hợp trẻ em dưới 6 tuổi chưa có thẻ BHYT:</h3>
                <p>Phụ huynh có thể xuất trình một trong các giấy tờ sau:</p>
                <ul>
                    <li>Giấy khai sinh (bản gốc hoặc bản sao)</li>
                    <li>Trích lục khai sinh</li>
                    <li>Giấy chứng sinh (bản gốc hoặc bản sao)</li>
                    <li>Căn cước công dân (nếu có)</li>
                </ul>

                <h3>Trường hợp đang chờ cấp lại hoặc đổi thẻ BHYT</h3>
                <p>Xuất trình <strong>Giấy tiếp nhận hồ sơ và hẹn trả kết quả</strong> do cơ quan Bảo hiểm Xã hội hoặc tổ chức được ủy quyền cấp.</p>
            </div>

            <div class="image-container">
                <img src="/frontend/img/Le-tan-Quay-tiep-nhan-duoi-1MB.jpg" alt="Quầy tiếp nhận BHYT">
                <p class="image-caption">Người bệnh xuất trình thẻ bảo hiểm y tế tại quầy tiếp nhận khi làm hồ sơ thăm khám</p>
            </div>

            <div class="section">
                <h2>2. Giấy tờ tùy thân có ảnh</h2>
                <p>Trong trường hợp thẻ BHYT không có ảnh, quý khách vui lòng mang theo một trong các loại giấy tờ tùy thân sau để xác minh thông tin:</p>
                <ul>
                    <li>Căn cước công dân (CCCD)</li>
                    <li>Hộ chiếu</li>
                    <li>Ứng dụng VNeID có tích hợp thông tin cá nhân</li>
                    <li>Giấy tờ chứng minh nhân thân khác do cơ quan có thẩm quyền cấp</li>
                    <li>Đối với học sinh, sinh viên: Có thể sử dụng giấy xác nhận của Công an địa phương hoặc cơ sở giáo dục đang theo học</li>
                </ul>

                <div class="note-box">
                    <strong>📌 Lưu ý:</strong>
                    <p>Trẻ em dưới 6 tuổi được miễn xuất trình giấy tờ tùy thân có ảnh.</p>
                </div>
            </div>

            <div class="section">
                <h2>3. Giấy chuyển tuyến/Phiếu chuyển cơ sở khám bệnh, chữa bệnh BHYT (nếu có)</h2>
                <p>Phiếu chuyển cơ sở khám bệnh, chữa bệnh BHYT từ cơ sở y tế khác chuyển đúng tuyến đến Hệ thống đặt lịch hẹn.</p>

                <h3>Mức thanh toán BHYT:</h3>
                <p>Hệ thống đặt lịch hẹn áp dụng mức thanh toán chi phí BHYT theo quy định hiện hành, cụ thể như sau:</p>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>GIẤY TỜ XUẤT TRÌNH</th>
                                <th>PHÂN LOẠI</th>
                                <th>MỨC THANH TOÁN NGOẠI TRÚ</th>
                                <th>MỨC THANH TOÁN NỘI TRÚ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <ul>
                                        <li>Thông tin thẻ BHYT</li>
                                        <li>Giấy tờ tùy thân</li>
                                        <li>Phiếu chuyển cơ sở khám bệnh, chữa bệnh BHYT</li>
                                    </ul>
                                </td>
                                <td>Đúng tuyến</td>
                                <td colspan="2" style="text-align: center;">100% chi phí BHYT theo tỷ lệ mã thẻ<br>(100%/ 95%/ 80%)</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <ul>
                                        <li>Thông tin thẻ BHYT</li>
                                        <li>Giấy tờ tùy thân</li>
                                    </ul>
                                </td>
                                <td>Trái tuyến</td>
                                <td colspan="2" style="text-align: center;">Theo quy định hiện hành</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="note-box">
                    <strong>📌 Lưu ý:</strong>
                    <p>Mức hưởng BHYT còn phụ thuộc vào mã quyền lợi ghi trên thẻ BHYT của từng cá nhân.</p>
                </div>
            </div>

            <div class="section">
                <h2>📌 Một vài lưu ý quan trọng khi thăm khám bằng Bảo hiểm Y tế</h2>
                <ul>
                    <li>Quý khách vui lòng kiểm tra kỹ thông tin thẻ BHYT trước khi đến khám để đảm bảo thẻ còn hiệu lực.</li>
                    <li>Nếu có bất kỳ thắc mắc nào về thủ tục hay cần hỗ trợ thông tin về Bảo hiểm y tế, đừng ngần ngại gọi ngay đến <strong>Hotline 1800 6767</strong> (nhấn số 3) – đội ngũ nhân viên luôn sẵn sàng tư vấn và hướng dẫn chi tiết.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
