@extends('layouts.public')

@section('title', $service->name . ' - Bệnh viện Nam Sài Gòn')

@section('content')
<style>
    .service-hero {
        background: linear-gradient(135deg, #1e5ba8 0%, #2980b9 50%, #3498db 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        position: relative;
    }
    .service-hero-content {
        max-width: 800px;
        margin: 0 auto;
    }
    .service-icon {
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        border: 3px solid rgba(255,255,255,0.3);
    }
    .service-icon i {
        font-size: 50px;
    }
    .service-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    .service-hero p {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
    }
    .service-meta {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-top: 30px;
        flex-wrap: wrap;
    }
    .service-meta-item {
        text-align: center;
    }
    .service-meta-item .value {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
    }
    .service-meta-item .label {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .content-section {
        padding: 40px 0;
    }
    .info-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    .info-card h2 {
        color: #1e5ba8;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .info-card h2 i {
        color: #3498db;
    }
    .info-card p {
        line-height: 1.8;
        color: #555;
    }
    
    .price-box {
        background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 30px;
    }
    .price-box .price {
        font-size: 2.5rem;
        font-weight: bold;
    }
    .price-box .label {
        opacity: 0.9;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .feature-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .feature-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .feature-item i {
        color: #27ae60;
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 3px;
    }
    
    .specialization-badge {
        display: inline-block;
        background: #e8f4fc;
        color: #1e5ba8;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 500;
        margin-top: 15px;
    }
    .specialization-badge i {
        margin-right: 5px;
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        padding: 40px 20px;
        flex-wrap: wrap;
    }
    .action-btn {
        padding: 15px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .action-btn.primary {
        background: linear-gradient(135deg, #1e5ba8 0%, #164a8a 100%);
        color: white;
    }
    .action-btn.secondary {
        background: white;
        color: #1e5ba8;
        border: 2px solid #1e5ba8;
    }
    .action-btn.success {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }
    
    @media (max-width: 768px) {
        .service-hero h1 {
            font-size: 1.8rem;
        }
        .service-meta {
            gap: 20px;
        }
        .price-box .price {
            font-size: 2rem;
        }
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        .action-btn {
            justify-content: center;
        }
    }
</style>

<!-- Hero Section -->
<section class="service-hero">
    <div class="service-hero-content">
        @if($service->avatar_url)
        <div class="service-image">
            <img src="{{ $service->avatar_url }}" alt="{{ $service->name }}" 
                 style="width: 180px; height: 180px; object-fit: cover; border-radius: 20px; border: 4px solid rgba(255,255,255,0.3); margin-bottom: 20px;"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="service-icon" style="display: none;">
                <i class="fas fa-stethoscope"></i>
            </div>
        </div>
        @else
        <div class="service-icon">
            <i class="fas fa-stethoscope"></i>
        </div>
        @endif
        <h1>{{ $service->name }}</h1>
        <p>{{ Str::limit($service->description, 150) }}</p>
        
        <div class="service-meta">
            <div class="service-meta-item">
                <span class="value">{{ number_format($service->price, 0, ',', '.') }}đ</span>
                <span class="label">Giá dịch vụ</span>
            </div>
            <div class="service-meta-item">
                <span class="value">{{ $service->duration_minutes }}</span>
                <span class="label">Phút</span>
            </div>
            @if($service->specialization)
            <div class="service-meta-item">
                <span class="value">{{ $service->specialization->name }}</span>
                <span class="label">Chuyên khoa</span>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<div class="container">
    <div class="breadcrumb">
        <a href="/">Trang chủ</a> / 
        <span>Dịch vụ</span> / 
        <span>{{ $service->name }}</span>
    </div>
</div>

<!-- Content Section -->
<section class="content-section">
    <div class="container">
        <!-- Price Box -->

        
        <!-- Description -->
        <div class="info-card">
            <h2><i class="fas fa-info-circle"></i> Mô tả dịch vụ</h2>
            <p>{{ $service->description }}</p>
            
            @if($service->specialization)
            <a href="/chuyen-khoa/{{ $service->specialization->id }}" class="specialization-badge">
                <i class="fas fa-hospital"></i> {{ $service->specialization->name }}
            </a>
            @endif
        </div>
        
        <!-- Benefits -->
        <div class="info-card">
            <h2><i class="fas fa-check-circle"></i> Lợi ích khi sử dụng dịch vụ</h2>
            <div class="features-grid">
                @php
                    $benefits = [
                        $service->benefit1 ?? 'Đội ngũ chuyên gia - Bác sĩ chuyên khoa giàu kinh nghiệm, tận tâm với bệnh nhân',
                        $service->benefit2 ?? 'Trang thiết bị hiện đại - Máy móc, thiết bị y tế đạt chuẩn quốc tế',
                        $service->benefit3 ?? 'Quy trình nhanh chóng - Tiết kiệm thời gian, giảm thời gian chờ đợi',
                        $service->benefit4 ?? 'Chăm sóc chu đáo - Dịch vụ chăm sóc khách hàng tận tình, chu đáo',
                    ];
                @endphp
                @foreach($benefits as $benefit)
                @if($benefit)
                @php
                    $parts = explode(' - ', $benefit, 2);
                    $title = $parts[0] ?? '';
                    $desc = $parts[1] ?? $benefit;
                @endphp
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>{{ $title }}</strong>
                        @if(count($parts) > 1)
                        <p>{{ $desc }}</p>
                        @endif
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        
        
        <!-- Doctors Section -->
        @if(isset($doctors) && count($doctors) > 0)
        <div class="info-card">
            <h2><i class="fas fa-user-md"></i> Bác sĩ thực hiện dịch vụ này</h2>
            <div class="doctors-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 20px;">
                @foreach($doctors as $doctor)
                <a href="/bac-si/{{ $doctor->id }}" class="doctor-card-link" style="text-decoration: none; color: inherit;">
                    <div class="doctor-mini-card" style="background: #f8f9fa; border-radius: 15px; padding: 20px; display: flex; gap: 15px; align-items: center; transition: all 0.3s; border: 2px solid transparent;">
                        <div class="doctor-avatar" style="flex-shrink: 0;">
                            @if($doctor->user && $doctor->user->avatar_url)
                            <img src="{{ $doctor->user->avatar_url }}" alt="{{ $doctor->user->full_name }}" 
                                 style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 3px solid #1e5ba8;"
                                 onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'">
                            @else
                            <div style="width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, #1e5ba8 0%, #3498db 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold;">
                                {{ $doctor->user ? substr($doctor->user->full_name, 0, 1) : 'B' }}
                            </div>
                            @endif
                        </div>
                        <div class="doctor-info" style="flex: 1; min-width: 0;">
                            <h4 style="margin: 0 0 5px 0; color: #1e5ba8; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $doctor->degree ? $doctor->degree . ' ' : '' }}{{ $doctor->user ? $doctor->user->full_name : 'Bác sĩ' }}
                            </h4>
                            <p style="margin: 0 0 5px 0; color: #666; font-size: 0.9rem;">
                                {{ $doctor->specialization ? $doctor->specialization->name : 'Đa khoa' }}
                            </p>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <span style="color: #f4c430; font-size: 0.85rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($doctor->rating_avg))
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $doctor->rating_avg)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span style="color: #999; font-size: 0.8rem;">{{ $doctor->experience ?? 0 }} năm KN</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            @if($service->specialization)
            <div style="text-align: center; margin-top: 25px;">
                <a href="/tim-bac-si?specialization={{ $service->specialization_id }}" 
                   style="display: inline-flex; align-items: center; gap: 8px; color: #1e5ba8; font-weight: 600; text-decoration: none;">
                    <span>Xem tất cả bác sĩ {{ $service->specialization->name }}</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>

<style>
    .doctor-card-link:hover .doctor-mini-card {
        border-color: #1e5ba8;
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(30, 91, 168, 0.15);
    }
</style>

<!-- Action Buttons -->
<div class="action-buttons">
    <a href="/dat-lich/bieu-mau?specialization={{ $service->specialization_id }}&service={{ $service->id }}" class="action-btn primary">
        <i class="fas fa-calendar-check"></i> Đặt lịch khám ngay
    </a>
    <a href="tel:19001234" class="action-btn success">
        <i class="fas fa-phone"></i> Gọi tư vấn: 1900 1234
    </a>
    @if($service->specialization)
    <a href="/chuyen-khoa/{{ $service->specialization->id }}" class="action-btn secondary">
        <i class="fas fa-hospital"></i> Xem chuyên khoa
    </a>
    @endif
</div>
@endsection
