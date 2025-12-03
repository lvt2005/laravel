@extends('layouts.public')

@section('title', $title . ' - Bệnh viện Nam Sài Gòn')

@section('content')
<div class="page-header">
    <div class="container">
        <h1><i class="{{ $icon }}"></i> {{ $title }}</h1>
        <p>{{ $subtitle }}</p>
    </div>
</div>

<div class="main-content">
    <div class="breadcrumb">
        <a href="/">Trang chủ</a>
        <span>/</span>
        <a href="/chuyen-khoa">Chuyên khoa</a>
        <span>/</span>
        <span>{{ $title }}</span>
    </div>

    <div class="card">
        <h2><i class="fas fa-info-circle"></i> Giới thiệu</h2>
        <p>{{ $description }}</p>
        
        @if(isset($features) && count($features) > 0)
        <h3 style="color: #1e5ba8; margin-top: 25px; margin-bottom: 15px;">Các dịch vụ chính:</h3>
        <ul>
            @foreach($features as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="card">
        <h2><i class="fas fa-user-md"></i> Đội ngũ bác sĩ</h2>
        <p>Khoa {{ $title }} của Bệnh viện Nam Sài Gòn quy tụ đội ngũ bác sĩ giàu kinh nghiệm, được đào tạo chuyên sâu trong và ngoài nước.</p>
        
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <h4>Trình độ cao</h4>
                    <p>Đội ngũ bác sĩ với trình độ Tiến sĩ, Thạc sĩ và Bác sĩ chuyên khoa</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-award"></i>
                <div>
                    <h4>Kinh nghiệm</h4>
                    <p>Nhiều năm kinh nghiệm trong lĩnh vực chuyên môn</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-heartbeat"></i>
                <div>
                    <h4>Tận tâm</h4>
                    <p>Luôn đặt sức khỏe bệnh nhân lên hàng đầu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2><i class="fas fa-hospital"></i> Cơ sở vật chất</h2>
        <p>Khoa được trang bị hệ thống máy móc, thiết bị y tế hiện đại, đạt chuẩn quốc tế, đảm bảo chẩn đoán và điều trị chính xác.</p>
        
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-tools"></i>
                <div>
                    <h4>Thiết bị hiện đại</h4>
                    <p>Máy móc nhập khẩu từ các nước tiên tiến</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-bed"></i>
                <div>
                    <h4>Phòng khám tiện nghi</h4>
                    <p>Không gian sạch sẽ, thoáng mát và tiện nghi</p>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons">
        <a href="/dat-lich/bieu-mau" class="btn-primary"><i class="fas fa-calendar-check"></i> Đặt lịch khám ngay</a>
        <a href="/tim-bac-si" class="btn-secondary"><i class="fas fa-user-md"></i> Tìm bác sĩ chuyên khoa</a>
        <a href="/chuyen-khoa" class="btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
    </div>
</div>
@endsection
