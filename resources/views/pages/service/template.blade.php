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
        <span>Dịch vụ</span>
        <span>/</span>
        <span>{{ $title }}</span>
    </div>

    <div class="card">
        <h2><i class="fas fa-info-circle"></i> Giới thiệu dịch vụ</h2>
        <p>{{ $description }}</p>
        
        @if(isset($features) && count($features) > 0)
        <h3 style="color: #1e5ba8; margin-top: 25px; margin-bottom: 15px;">Các dịch vụ bao gồm:</h3>
        <ul>
            @foreach($features as $feature)
            <li>{{ $feature }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <div class="card">
        <h2><i class="fas fa-check-circle"></i> Lợi ích khi sử dụng dịch vụ</h2>
        <div class="info-grid">
            <div class="info-item">
                <i class="fas fa-user-md"></i>
                <div>
                    <h4>Đội ngũ chuyên gia</h4>
                    <p>Bác sĩ chuyên khoa giàu kinh nghiệm, tận tâm</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-hospital"></i>
                <div>
                    <h4>Cơ sở hiện đại</h4>
                    <p>Trang thiết bị y tế tiên tiến, đạt chuẩn quốc tế</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Nhanh chóng</h4>
                    <p>Quy trình khám chữa bệnh nhanh gọn, tiết kiệm thời gian</p>
                </div>
            </div>
            <div class="info-item">
                <i class="fas fa-hand-holding-heart"></i>
                <div>
                    <h4>Chăm sóc chu đáo</h4>
                    <p>Dịch vụ chăm sóc khách hàng tận tình, chu đáo</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h2><i class="fas fa-money-bill-wave"></i> Bảng giá dịch vụ</h2>
        <p>Vui lòng liên hệ hotline <strong style="color: #1e5ba8;">1900 1234</strong> hoặc đến trực tiếp bệnh viện để được tư vấn chi tiết về bảng giá và các gói dịch vụ phù hợp.</p>
    </div>

    <div class="action-buttons">
        <a href="/dat-lich/bieu-mau" class="btn-primary"><i class="fas fa-calendar-check"></i> Đặt lịch ngay</a>
        <a href="tel:19001234" class="btn-secondary"><i class="fas fa-phone"></i> Gọi tư vấn: 1900 1234</a>
    </div>
</div>
@endsection
