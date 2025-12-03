<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
  <title>Đặt lịch nhanh - Nam Sài Gòn</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <style>
    /* Header Menu Styles */
    .header-wrap {
      background-color: #ffffff;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 0;
      z-index: 1000;
      width: 100%;
      margin: 0;
      padding: 0;
    }

    .header-nav {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 80px;
    }

    .logo-section {
      display: flex;
      align-items: center;
      gap: 10px;
      min-width: 280px;
      margin-right: 30px;
    }

    .logo-img {
      width: 280px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
    }

    .logo-text h1 {
      font-size: 16px;
      color: #333;
      font-weight: 600;
      line-height: 1.2;
    }

    .logo-text h1 small {
      display: block;
      font-size: 13px;
      color: #666;
      font-weight: 400;
    }

    .search-box {
      flex: 0 0 200px;
      margin-right: 30px;
    }

    .search-box input {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 20px;
      font-size: 13px;
      color: #999;
      background-color: #f9f9f9;
    }

    .search-box input::placeholder {
      color: #bbb;
    }

    .menu-list {
      display: flex;
      align-items: center;
      list-style: none;
      flex: 1;
      gap: 0;
      margin: 0;
    }

    .menu-item {
      position: relative;
    }

    .menu-item>a {
      display: flex;
      align-items: center;
      padding: 25px 15px;
      color: #333;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      white-space: nowrap;
      transition: color 0.3s ease;
    }

    .menu-item>a:hover {
      color: #3498db;
    }

    .menu-item.menu-item-has-children>a {
      display: flex;
      gap: 5px;
    }

    .menu-item i {
      font-size: 14px;
      transition: transform 0.3s ease;
    }

    .menu-item.menu-dropdown:hover>i {
      transform: rotate(180deg);
    }

    .menu-item.menu-dropdown:hover .child {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    /* Dropdown Menu */
    .child {
      position: absolute;
      top: 100%;
      left: 0;
      background-color: #ffffff;
      list-style: none;
      min-width: 200px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
    }

    .child li {
      list-style: none;
    }

    .child li a {
      display: block;
      padding: 12px 20px;
      color: #666;
      text-decoration: none;
      font-size: 13px;
      transition: all 0.3s ease;
      white-space: normal;
    }

    .child li a:hover {
      background-color: #f0f8ff;
      color: #3498db;
      padding-left: 25px;
    }

    /* Mega Menu */
    .menu-mega {
      position: absolute;
      top: 100%;
      left: 0;
      background-color: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 4px;
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
      display: flex;
      min-width: 600px;
    }

    .menu-mega ul {
      flex: 1;
      list-style: none;
      padding: 15px 0;
      border-right: 1px solid #f0f0f0;
    }

    .menu-mega ul:last-child {
      border-right: none;
    }

    .menu-mega li {
      list-style: none;
    }

    .menu-mega .menu-link {
      display: block;
      padding: 10px 20px;
      color: #666;
      text-decoration: none;
      font-size: 13px;
      transition: all 0.3s ease;
    }

    .menu-mega .menu-link:hover {
      background-color: #f0f8ff;
      color: #3498db;
      padding-left: 25px;
    }

    .menu-chuyen-khoa:hover .menu-mega {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    /* Right Section */
    .header-right {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-left: auto;
      position: relative;
    }

    .language-selector {
      background-color: transparent;
      border: none;
      color: #333;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      padding: 8px 10px;
      transition: color 0.3s ease;
    }

    .language-selector:hover {
      color: #3498db;
    }

    .btn-register {
      background-color: #f4c430;
      color: #fff;
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
      text-decoration: none;
      display: inline-block;
    }

    .btn-register:hover {
      background-color: #e8b81a;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(244, 196, 48, 0.3);
    }

    /* Header Button Styles */
    .btn-header-outline {
      background-color: transparent;
      color: #333;
      border: 1px solid #ddd;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
      text-decoration: none;
      display: inline-block;
    }

    .btn-header-outline:hover {
      border-color: #3498db;
      color: #3498db;
    }

    .btn-header-primary {
      background-color: #28a745;
      color: #fff;
      border: none;
      padding: 8px 20px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
      text-decoration: none;
      display: inline-block;
    }

    .btn-header-primary:hover {
      background-color: #218838;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }

    /* User Menu Styles */
    .user-menu {
      position: relative;
      display: none;
    }

    .user-menu.active {
      display: block;
    }

    .user-button {
      display: flex;
      align-items: center;
      gap: 8px;
      background-color: #f0f8ff;
      color: #3498db;
      border: 2px solid #3498db;
      padding: 8px 20px;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .user-button:hover {
      background-color: #3498db;
      color: #fff;
    }

    .user-button i {
      font-size: 18px;
    }

    .user-dropdown {
      position: absolute;
      top: calc(100% + 10px);
      right: 0;
      background-color: #ffffff;
      min-width: 200px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      border-radius: 8px;
      overflow: hidden;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
    }

    .user-menu:hover .user-dropdown {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .user-dropdown-header {
      padding: 15px 20px;
      background-color: #f0f8ff;
      border-bottom: 1px solid #e0e0e0;
    }

    .user-dropdown-header .user-name {
      font-size: 14px;
      font-weight: 600;
      color: #333;
      margin-bottom: 3px;
    }

    .user-dropdown-header .user-role {
      font-size: 12px;
      color: #666;
    }

    .user-dropdown-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 20px;
      color: #666;
      text-decoration: none;
      font-size: 13px;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .user-dropdown-item:hover {
      background-color: #f0f8ff;
      color: #3498db;
      padding-left: 25px;
    }

    .user-dropdown-item i {
      font-size: 16px;
    }

    .user-dropdown-divider {
      height: 1px;
      background-color: #e0e0e0;
      margin: 5px 0;
    }

    .user-dropdown-item.logout {
      color: #e74c3c;
    }

    .user-dropdown-item.logout:hover {
      background-color: #ffebee;
      color: #c0392b;
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .menu-list {
        gap: 0;
      }

      .menu-item>a {
        padding: 20px 12px;
        font-size: 12px;
      }
    }

    @media (max-width: 768px) {
      .header-nav {
        height: auto;
        padding: 15px 20px;
        flex-wrap: wrap;
      }

      .menu-list {
        flex-direction: column;
        width: 100%;
        margin-top: 15px;
      }

      .user-dropdown {
        right: auto;
        left: 0;
      }
    }

    /* Footer styles */
    footer {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: #ffffff;
      padding: 50px 0 30px;
      margin-top: auto;
    }

    .footer-content {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 50px;
      margin-bottom: 40px;
    }

    .footer-logo-section {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .footer-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 10px;
    }

    .footer-logo img {
      width: 170px;
      height: 100px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.1);
      padding: 8px;
    }

    .footer-logo h3 {
      font-size: 16px;
      font-weight: 700;
      line-height: 1.2;
    }

    .footer-logo-section p {
      font-size: 13px;
      line-height: 1.7;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 8px;
    }

    footer h4 {
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 18px;
      color: #ffffff;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .footer-section ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .footer-section ul li {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.8);
      transition: all 0.3s ease;
    }

    .footer-section ul li a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .footer-section ul li a:hover {
      color: #fbbf24;
      padding-left: 8px;
    }

    .working-hours {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.85);
      line-height: 1.8;
      margin-bottom: 15px;
    }

    .working-hours strong {
      color: #ffffff;
      font-weight: 700;
    }

    .hotline-btn {
      display: inline-block;
      background-color: #fbbf24;
      color: #1e40af;
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 700;
      text-decoration: none;
      margin-top: 10px;
      transition: all 0.3s ease;
      margin-left: 30px;
    }

    .hotline-btn:hover {
      background-color: #fcd34d;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
    }

    .contact-section {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .social-icons {
      display: flex;
      gap: 12px;
      align-items: center;
    }

    .social-icons a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      background-color: #fbbf24;
      color: #1e40af;
      border-radius: 50%;
      text-decoration: none;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .social-icons a:hover {
      background-color: #fcd34d;
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
    }

    .newsletter-section p {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 12px;
      font-weight: 500;
    }

    .newsletter-form {
      display: flex;
      gap: 8px;
      margin-bottom: 15px;
    }

    .newsletter-form input {
      flex: 1;
      padding: 10px 15px;
      border: none;
      border-radius: 25px;
      font-size: 13px;
      color: #333;
      background-color: #ffffff;
    }

    .newsletter-form input::placeholder {
      color: #999;
    }

    .newsletter-form input:focus {
      outline: none;
      box-shadow: 0 0 8px rgba(255, 255, 255, 0.3);
    }

    .newsletter-form button {
      padding: 10px 20px;
      background-color: #1e3a8a;
      color: #ffffff;
      border: none;
      border-radius: 25px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
    }

    .newsletter-form button:hover {
      background-color: #1e40af;
      transform: translateY(-2px);
    }

    .dmca-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      color: rgba(255, 255, 255, 0.8);
    }

    .dmca-badge img {
      width: 160px;
      height: auto;
    }

    .footer-divider {
      border: none;
      border-top: 1px solid rgba(255, 255, 255, 0.2);
      margin: 30px 0;
    }

    .footer-bottom {
      text-align: center;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .footer-bottom p {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.7);
    }

    .footer-links {
      display: flex;
      justify-content: center;
      gap: 0;
      list-style: none;
      flex-wrap: wrap;
    }

    .footer-links li {
      font-size: 12px;
    }

    .footer-links li a {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      padding: 0 15px;
      border-right: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    .footer-links li:last-child a {
      border-right: none;
    }

    .footer-links li a:hover {
      color: #fbbf24;
    }

    /* Responsive for footer */
    @media (max-width: 1024px) {
      .footer-content {
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
      }
    }

    @media (max-width: 768px) {
      footer {
        padding: 40px 0 20px;
      }

      .footer-content {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      footer h4 {
        font-size: 14px;
        margin-bottom: 12px;
      }

      .newsletter-form {
        flex-direction: column;
      }

      .newsletter-form input,
      .newsletter-form button {
        width: 100%;
      }

      .social-icons {
        justify-content: flex-start;
      }

      .footer-links {
        gap: 10px;
        justify-content: center;
      }

      .footer-links li a {
        padding: 0 10px;
      }
    }

    @media (max-width: 480px) {
      .footer-content {
        gap: 20px;
      }

      footer h4 {
        font-size: 13px;
      }

      footer p {
        font-size: 12px;
      }

      .social-icons {
        gap: 10px;
      }

      .social-icons a {
        width: 35px;
        height: 35px;
        font-size: 16px;
      }
    }
  </style>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Inter", sans-serif;
    }

    body {
      min-height: 100vh;
      background: linear-gradient(135deg,
          #e8f0ff 0%,
          #fdfdfd 60%,
          #eef7ff 100%);
      color: #1f2a37;
    }

    header {
      padding: 24px 48px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand img {
      width: 280px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
    }

    nav {
      display: flex;
      gap: 12px;
    }

    .cta-btn {
      padding: 10px 20px;
      border-radius: 999px;
      border: none;
      cursor: pointer;
      font-weight: 600;
    }

    .cta-btn.primary {
      background: linear-gradient(120deg, #2563eb, #1d4ed8);
      color: #fff;
    }

    .cta-btn.secondary {
      background: transparent;
      border: 1px solid #d0d7e3;
      color: #1f2a37;
    }

    .hero {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 40px;
      padding: 40px 48px 24px;
      align-items: center;
    }

    .hero h2 {
      font-size: 42px;
      line-height: 1.2;
      margin-bottom: 16px;
    }

    .hero p {
      font-size: 17px;
      color: #4b5563;
      margin-bottom: 24px;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      border-radius: 999px;
      background: rgba(37, 99, 235, 0.1);
      color: #1d4ed8;
      font-size: 14px;
      font-weight: 600;
    }

    .hero-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 24px;
      padding: 28px;
      box-shadow: 0 20px 80px rgba(15, 23, 42, 0.08);
      border: 1px solid rgba(37, 99, 235, 0.1);
    }

    .hero-card h3 {
      font-size: 18px;
      color: #1d4ed8;
      margin-bottom: 12px;
    }

    .hero-card ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .hero-card li {
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .hero-card strong {
      display: block;
      font-size: 16px;
      margin-bottom: 4px;
    }

    .timeline {
      padding: 24px 48px 48px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 20px;
    }

    .timeline-step {
      background: #fff;
      border-radius: 18px;
      padding: 24px;
      border: 1px solid #e5e7eb;
      position: relative;
      overflow: hidden;
    }

    .timeline-step span {
      font-size: 14px;
      color: #6b7280;
      font-weight: 600;
    }

    .timeline-step h4 {
      font-size: 20px;
      margin: 12px 0;
    }

    .timeline-step p {
      color: #4b5563;
      line-height: 1.6;
    }

    .timeline-step::after {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: 18px;
      background: linear-gradient(135deg,
          rgba(37, 99, 235, 0.08),
          transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: -1;
    }

    .timeline-step:hover::after {
      opacity: 1;
    }

    footer {
      text-align: center;
      padding: 24px 0 40px;
      color: #6b7280;
      font-size: 14px;
    }

    @media (max-width: 768px) {
      header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
      }

      nav {
        flex-wrap: wrap;
        justify-content: center;
      }

      .hero {
        padding: 40px 24px;
      }

      .timeline {
        padding: 24px;
      }
    }
  </style>
</head>

<body>
  <script src="{{ asset('frontend/js/auth.js') }}"></script>
  @include('partials.header')

  <script>
    // Check if user is logged in
    document.addEventListener('DOMContentLoaded', function() {
      const token = localStorage.getItem('access_token');
      const authBtn = document.getElementById('authBtn');

      if (token && token !== 'null' && token !== 'undefined') {
        // User is logged in - show profile link
        authBtn.textContent = 'Tài khoản';
        authBtn.href = '/ho-so';
      }
    });
  </script>

  <section class="hero">
    <div>
      <div class="badge">
        <i class="ri-flashlight-line"></i>
        2 bước đến phòng khám
      </div>
      <h2>Chọn bác sĩ - Xác nhận giờ - Nhận lịch ngay trong 60 giây</h2>
      <p>
        Giao diện mới giúp người bệnh đi thẳng vào đặt lịch mà không cần qua
        nhiều trang trung gian. Bạn chỉ cần chọn chuyên khoa, bác sĩ, khung
        giờ phù hợp và xác nhận thông tin liên hệ.
      </p>
      <div style="display: flex; gap: 12px; flex-wrap: wrap">
        <a href="#timeline" class="cta-btn secondary">Xem quy trình</a>
        <a
          href="{{ route('dat-lich.bieu-mau') }}"
          class="cta-btn primary"
          style="text-decoration: none; text-align: center">Mở biểu mẫu đặt lịch</a>
      </div>
    </div>
    <div class="hero-card">
      <h3>Đường tắt đi thẳng đến đặt lịch</h3>
      <ul>
        <li>
          <div>
            <strong>Bước 1 - Chọn nhu cầu</strong>
            Chuyên khoa, triệu chứng nhanh, hoặc bác sĩ thường khám.
          </div>
        </li>
        <li>
          <div>
            <strong>Bước 2 - Điền thông tin</strong>
            Họ tên, số CCCD, số điện thoại, phương thức thanh toán.
          </div>
        </li>
        <li>
          <div>
            <strong>Bước 3 - Nhận xác nhận</strong>
            Xác thực OTP (nếu bật) và nhận mã lịch qua email/SMS.
          </div>
        </li>
      </ul>
    </div>
  </section>

  <section id="timeline" class="timeline">
    <article class="timeline-step">
      <span>01</span>
      <h4>Cá nhân hóa ngay tại trang chủ</h4>
      <p>
        Hệ thống ghi nhớ hồ sơ gần nhất và gợi ý bác sĩ phù hợp trong hộp
        "Tiếp tục đặt lịch".
      </p>
    </article>
    <article class="timeline-step">
      <span>02</span>
      <h4>Đặt lịch không cần đăng nhập</h4>
      <p>
        Khi admin bật cho phép, biểu mẫu đón bệnh mới sẽ hiển thị ngay trên
        landing page.
      </p>
    </article>
    <article class="timeline-step">
      <span>03</span>
      <h4>Theo dõi & yêu cầu hoàn tiền</h4>
      <p>
        Liên kết trực tiếp đến trang tra cứu lịch đã đặt, giúp bệnh nhân yêu
        cầu hoàn tiền khi cần.
      </p>
    </article>
    <article class="timeline-step">
      <span>04</span>
      <h4>Góp ý và đánh giá tức thời</h4>
      <p>
        Form góp ý mới gửi song song cho bộ phận CSKH và bác sĩ, đảm bảo phản
        hồi trong 2 giờ.
      </p>
    </article>
  </section>

  <footer>
    <div class="container">
      <div class="footer-content">
        <!-- Logo & Info -->
        <div class="footer-logo-section">
          <div class="footer-logo">
            <img src="{{ asset('frontend/img/logomau.jpg') }}" alt="Logo" />
          </div>
          <p>
            Địa chỉ: 70 Đ. Tô Ký, Tân Chánh Hiệp, Quận 12, Thành phố Hồ Chí
            Minh, Việt Nam
          </p>
          <p>Email: nhom5@gmail.com</p>
          <p>
            GPDKKD: 0312088602 cấp ngày 14/12/2012 bởi Sở Kế hoạch và Đầu tư
            TPHCM. Giấy phép hoạt động khám bệnh, chữa bệnh số 230/BYT-GPHD do
            Bộ Y Tế cấp.
          </p>
        </div>

        <!-- About -->
        <div class="footer-section">
          <h4>Về chúng tôi</h4>
          <ul>
            <li><a href="/tim-bac-si">Đội ngũ bác sĩ</a></li>
            <li><a href="#">Cơ sở vật chất</a></li>
            <li><a href="#">Câu chuyện khách hàng</a></li>
            <li><a href="#">Tuyên dụng</a></li>
            <li><a href="#">Cảm nang bệnh</a></li>
            <li><a href="#">Chính sách bảo mật</a></li>
          </ul>
        </div>

        <!-- Working Hours -->
        <div class="footer-section">
          <h4>Giờ làm việc</h4>
          <div class="working-hours">
            <p><strong>Từ thứ 2 đến thứ 7</strong></p>
            <p>Buổi sáng:<br />7:00 - 12:00</p>
            <p>Buổi chiều:<br />13:30 - 17:00</p>
          </div>
          <a href="tel:18006767" class="hotline-btn">Hotline: 1800 6767</a>
        </div>

        <!-- Contact -->
        <div class="contact-section">
          <h4>Liên hệ</h4>
          <div class="social-icons">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
            <a href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
          </div>
          <div class="newsletter-section">
            <p><strong>Theo dõi bản tin chúng tôi</strong></p>
            <form class="newsletter-form">
              <input type="email" placeholder="Email" required />
              <button type="submit">Đăng ký</button>
            </form>
          </div>
          <div class="dmca-badge">
            <img src="{{ asset('frontend/img/dmca_protected_16_120.png') }}" alt="" />
          </div>
        </div>
      </div>

      <hr class="footer-divider" />

      <div class="footer-bottom">
        <p>&copy; Hệ thống đặt lịch hẹn. Tất cả các quyền được bảo vệ.</p>
        <ul class="footer-links">
          <li><a href="#">Chính sách bảo mật</a></li>
          <li><a href="#">Điều khoản sử dụng</a></li>
          <li><a href="#">Liên hệ</a></li>
        </ul>
      </div>
    </div>
  </footer>
  <script>
    // API Base URL
    const API_BASE = '/api';

    // Check login status from localStorage/sessionStorage
    async function checkLoginStatus() {
      const accessToken = localStorage.getItem('access_token') || sessionStorage.getItem('access_token');

      const loginBtn = document.getElementById('loginBtn');
      const userMenu = document.getElementById('userMenu');
      const userNameEl = document.getElementById('userName');
      const userNameFullEl = document.getElementById('userNameFull');
      const userAvatarEl = document.getElementById('userAvatar');
      const userIconEl = document.getElementById('userIcon');
      const userRoleEl = document.getElementById('userRole');

      if (accessToken && window.AuthAPI) {
        try {
          // Fetch user profile from API
          const profile = await AuthAPI.getProfile();

          if (profile && profile.id) {
            // User is logged in - show user menu
            loginBtn.style.display = 'none';
            userMenu.classList.add('active');

            // Update user info
            const displayName = profile.full_name || profile.email.split('@')[0];
            userNameEl.textContent = displayName;
            userNameFullEl.textContent = displayName;

            // Update avatar
            if (profile.avatar_url) {
              userAvatarEl.src = profile.avatar_url;
              userAvatarEl.style.display = 'block';
              if (userIconEl) userIconEl.style.display = 'none';
            }

            // Update role
            const roleMap = {
              'USER': 'Bệnh nhân',
              'DOCTOR': 'Bác sĩ',
              'ADMIN': 'Quản trị viên'
            };
            userRoleEl.textContent = roleMap[profile.type] || 'Người dùng';

            // Store for later use
            localStorage.setItem('user_profile', JSON.stringify(profile));
          } else {
            showLoginButton();
          }
        } catch (e) {
          console.error('Error fetching profile:', e);
          showLoginButton();
        }
      } else {
        showLoginButton();
      }
    }

    function showLoginButton() {
      const loginBtn = document.getElementById('loginBtn');
      const userMenu = document.getElementById('userMenu');
      if (loginBtn) loginBtn.style.display = 'inline-block';
      if (userMenu) userMenu.classList.remove('active');
    }

    // Logout function
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
      logoutBtn.addEventListener('click', async function(e) {
        e.preventDefault();
        if (confirm('Bạn có chắc chắn muốn đăng xuất?')) {
          try {
            if (window.AuthAPI) await AuthAPI.logout();
          } catch (e) {
            console.error('Logout error:', e);
          }
          // Clear all storage
          localStorage.removeItem('access_token');
          localStorage.removeItem('refresh_token');
          localStorage.removeItem('session_id');
          localStorage.removeItem('user_profile');
          sessionStorage.removeItem('access_token');
          sessionStorage.removeItem('refresh_token');
          sessionStorage.removeItem('session_id');

          // Redirect to login
          window.location.href = '/dang-nhap';
        }
      });
    }

    // Navigate to profile
    const profileLink = document.getElementById('profileLink');
    if (profileLink) {
      profileLink.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = '/ho-so';
      });
    }

    // Load specializations to menu
    async function loadSpecializationsToMenu() {
      const col1 = document.getElementById('menuSpecCol1');
      const col2 = document.getElementById('menuSpecCol2');
      if (!col1 || !col2) return;

      try {
        const response = await fetch('/api/public/specializations');
        const result = await response.json();

        if (result.success && result.data && result.data.length > 0) {
          const half = Math.ceil(result.data.length / 2);
          const firstHalf = result.data.slice(0, half);
          const secondHalf = result.data.slice(half);

          col1.innerHTML = firstHalf.map(spec => {
            return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
          }).join('');

          col2.innerHTML = secondHalf.map(spec => {
            return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
          }).join('');
        }
      } catch (error) {
        console.error('Error loading specializations:', error);
      }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      checkLoginStatus();
      loadSpecializationsToMenu();
    });
  </script>
</body>

</html>