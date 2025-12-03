<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>{{ $specialization->name }} - Bệnh viện Nam Sài Gòn</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
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

        .login-btn-wrap {
            display: block;
        }

        .login-btn-wrap.hidden {
            display: none;
        }

        /* Content Section Styles */
        .breadcrumb {
            padding: 15px 0;
            color: #666;
        }
        .breadcrumb a {
            color: #1e5ba8;
            text-decoration: none;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e5ba8 0%, #2980b9 50%, #3498db 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            position: relative;
        }
        .hero-content {
            max-width: 800px;
            margin: 0 auto;
        }
        .hero-image {
            width: 150px;
            height: 150px;
            border-radius: 20px;
            object-fit: cover;
            margin-bottom: 20px;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .hero-icon {
            width: 150px;
            height: 150px;
            background: rgba(255,255,255,0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 4px solid rgba(255,255,255,0.3);
        }
        .hero-icon i {
            font-size: 60px;
        }
        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .hero-section p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 50px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        .stat-item {
            text-align: center;
        }
        .stat-item .number {
            font-size: 2rem;
            font-weight: bold;
        }
        .stat-item .label {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* Content Section */
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

        /* Services Section */
        .services-section {
            padding: 40px 0;
            background: #f8f9fa;
        }
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            color: #1e5ba8;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .section-title p {
            color: #666;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .service-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border: 3px solid transparent;
            position: relative;
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .service-card.selected {
            border-color: #27ae60;
            background: #f0fff4;
        }
        .service-card.selected::before {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #27ae60;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: bold;
        }
        .service-card h3 {
            color: #1e5ba8;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .service-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        .service-price {
            color: #27ae60;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .service-duration {
            color: #888;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Doctors Section */
        .doctors-section {
            padding: 40px 0;
            background: white;
        }
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
        .doctor-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
            border: 3px solid transparent;
        }
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }
        .doctor-card.selected {
            border-color: #27ae60;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.3);
        }
        .doctor-card.selected::after {
            content: '✓ Đã chọn';
            position: absolute;
            top: 10px;
            right: 10px;
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .doctor-avatar {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .doctor-avatar-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 60px;
            font-weight: bold;
        }
        .doctor-info {
            padding: 20px;
        }
        .doctor-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .doctor-degree {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .doctor-meta {
            display: flex;
            gap: 15px;
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
        .doctor-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .doctor-meta i {
            color: #f39c12;
        }
        .btn-view-detail {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            transition: background 0.3s;
            background: #f0f0f0;
            color: #333;
            font-size: 14px;
        }
        .btn-view-detail:hover {
            background: #e0e0e0;
        }
        .no-doctors {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        .no-doctors i {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }
        .pagination button {
            padding: 10px 16px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .pagination button:hover:not(:disabled) {
            background: #1e5ba8;
            color: white;
            border-color: #1e5ba8;
        }
        .pagination button.active {
            background: #1e5ba8;
            color: white;
            border-color: #1e5ba8;
        }
        .pagination button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Floating Book Button */
        .floating-book-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4);
            z-index: 100;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }
        .floating-book-btn.show {
            display: flex;
        }
        .floating-book-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 50px rgba(39, 174, 96, 0.5);
        }
        @keyframes pulse {
            0% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4); }
            50% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.6); }
            100% { box-shadow: 0 10px 40px rgba(39, 174, 96, 0.4); }
        }

        /* Action Buttons */
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
        .action-btn.secondary {
            background: white;
            color: #1e5ba8;
            border: 2px solid #1e5ba8;
        }

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
    </head>
<body>
    <script src="{{ asset('frontend/js/auth.js') }}"></script>
    @include('partials.header')

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            @if($specialization->image_url)
                <img src="{{ $specialization->image_url }}" alt="{{ $specialization->name }}" class="hero-image">
            @else
                <div class="hero-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
            @endif
            <h1>{{ $specialization->name }}</h1>
            <p>{{ $specialization->description ?? 'Chuyên khoa hàng đầu của Bệnh viện Nam Sài Gòn với đội ngũ bác sĩ giàu kinh nghiệm và trang thiết bị hiện đại.' }}</p>
            
            <div class="stats-bar">
                <div class="stat-item">
                    <div class="number" id="doctorCount">{{ $specialization->doctors_count ?? 0 }}</div>
                    <div class="label">Bác sĩ</div>
                </div>
                <div class="stat-item">
                    <div class="number" id="serviceCount">{{ $specialization->services->count() ?? 0 }}</div>
                    <div class="label">Dịch vụ</div>
                </div>
                <div class="stat-item">
                    <div class="number">24/7</div>
                    <div class="label">Hỗ trợ</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <div class="breadcrumb">
            <a href="/">Trang chủ</a> / 
            <a href="/chuyen-khoa">Chuyên khoa</a> / 
            <span>{{ $specialization->name }}</span>
        </div>
    </div>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="info-card">
                <h2><i class="fas fa-info-circle"></i> Giới thiệu về {{ $specialization->name }}</h2>
                <p>{{ $specialization->description ?? 'Là một trong những chuyên khoa mũi nhọn của Bệnh viện Nam Sài Gòn. Với đội ngũ y bác sĩ giàu kinh nghiệm và hệ thống trang thiết bị hiện đại, chúng tôi cam kết mang đến dịch vụ chăm sóc sức khỏe tốt nhất cho quý khách hàng.' }}</p>
                
                <div class="features-grid">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Đội ngũ chuyên gia</strong>
                            <p>Bác sĩ giàu kinh nghiệm, được đào tạo chuyên sâu</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Thiết bị hiện đại</strong>
                            <p>Trang thiết bị y tế tiên tiến nhất</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Dịch vụ tận tâm</strong>
                            <p>Chăm sóc bệnh nhân chu đáo, tận tình</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Đặt lịch dễ dàng</strong>
                            <p>Hệ thống đặt lịch trực tuyến tiện lợi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section class="doctors-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-user-md"></i> Đội ngũ bác sĩ</h2>
                <p>Chọn bác sĩ để đặt lịch khám tại {{ $specialization->name }}</p>
            </div>

            <div class="doctors-grid" id="doctorsGrid">
                <!-- Doctors will be loaded here -->
            </div>

            <div class="no-doctors" id="noDoctors" style="display: none;">
                <i class="fas fa-user-md"></i>
                <h3>Chưa có bác sĩ</h3>
                <p>Hiện tại chưa có bác sĩ nào trong chuyên khoa này. Vui lòng liên hệ để được hỗ trợ.</p>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="pagination"></div>
        </div>
    </section>

    <!-- Services Section -->
    @if($specialization->services && $specialization->services->count() > 0)
    <section class="services-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-clipboard-list"></i> Dịch vụ khám</h2>
                <p>Chọn dịch vụ bạn muốn khám tại {{ $specialization->name }}</p>
            </div>
            <div class="services-grid" id="servicesGrid">
                @foreach($specialization->services as $service)
                <div class="service-card" data-service-id="{{ $service->id }}" data-service-name="{{ $service->name }}" data-service-price="{{ $service->price ?? 0 }}" onclick="toggleService(this)">
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->description ?? 'Dịch vụ ' . $service->name . ' tại ' . $specialization->name }}</p>
                    <div class="service-price">{{ number_format($service->price ?? 0, 0, ',', '.') }} VNĐ</div>
                    <div class="service-duration">
                        <i class="fas fa-clock"></i> {{ $service->duration_minutes ?? 30 }} phút
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Selected services summary -->
            <div id="selectedServicesSummary" style="display: none; margin-top: 20px; padding: 20px; background: #e8f5e9; border-radius: 12px; text-align: center;">
                <p style="margin: 0; color: #2e7d32; font-weight: 500;">
                    <i class="fas fa-check-circle"></i> 
                    Đã chọn <span id="selectedCount">0</span> dịch vụ - Tổng: <span id="totalPrice">0</span> VNĐ
                </p>
            </div>
        </div>
    </section>
    @endif

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="/chuyen-khoa" class="action-btn secondary">
            <i class="fas fa-arrow-left"></i> Xem tất cả chuyên khoa
        </a>
    </div>

    <!-- Floating Book Button -->
    <a href="#" class="floating-book-btn" id="floatingBookBtn">
        <i class="fas fa-calendar-check"></i>
        <span>Đặt lịch khám ngay</span>
    </a>

    <!-- Footer -->
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
        const API_BASE = '/api';
        const SPECIALIZATION_ID = {{ $specialization->id }};
        let allDoctors = [];
        let currentPage = 1;
        const perPage = 8;
        let selectedDoctor = null;
        let selectedServices = []; // Array of selected service objects
        let userData = null;

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
            logoutBtn.addEventListener('click', async function (e) {
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
        document.addEventListener('DOMContentLoaded', function () {
            checkLoginStatus();
            loadSpecializationsToMenu();
            loadDoctorsFromAPI(); // Load doctors for this specialization
        });

        // Toggle service selection
        function toggleService(card) {
            const serviceId = parseInt(card.dataset.serviceId);
            const serviceName = card.dataset.serviceName;
            const servicePrice = parseInt(card.dataset.servicePrice) || 0;
            
            const existingIndex = selectedServices.findIndex(s => s.id === serviceId);
            
            if (existingIndex >= 0) {
                // Remove from selection
                selectedServices.splice(existingIndex, 1);
                card.classList.remove('selected');
            } else {
                // Add to selection
                selectedServices.push({
                    id: serviceId,
                    name: serviceName,
                    price: servicePrice
                });
                card.classList.add('selected');
            }
            
            updateSelectedServicesSummary();
            updateBookingButton();
        }

        // Update selected services summary
        function updateSelectedServicesSummary() {
            const summary = document.getElementById('selectedServicesSummary');
            const countEl = document.getElementById('selectedCount');
            const priceEl = document.getElementById('totalPrice');
            
            if (!summary) return;
            
            if (selectedServices.length > 0) {
                summary.style.display = 'block';
                countEl.textContent = selectedServices.length;
                const total = selectedServices.reduce((sum, s) => sum + s.price, 0);
                priceEl.textContent = total.toLocaleString('vi-VN');
            } else {
                summary.style.display = 'none';
            }
        }

        // Update booking button URL with selected services
        function updateBookingButton() {
            const btn = document.getElementById('floatingBookBtn');
            if (!btn) return;
            
            // Show button if doctor OR services selected
            if (selectedDoctor || selectedServices.length > 0) {
                btn.classList.add('show');
                
                // Build URL with params
                let params = new URLSearchParams();
                params.set('specialization', SPECIALIZATION_ID);
                
                if (selectedDoctor) {
                    params.set('doctor', selectedDoctor.id);
                }
                
                if (selectedServices.length > 0) {
                    params.set('services', selectedServices.map(s => s.id).join(','));
                }
                
                btn.href = `/dat-lich/bieu-mau?${params.toString()}`;
                
                // Update button text
                let text = 'Đặt lịch khám ngay';
                if (selectedServices.length > 0) {
                    text += ` (${selectedServices.length} dịch vụ)`;
                }
                btn.querySelector('span').textContent = text;
            } else {
                btn.classList.remove('show');
            }
        }

        // Load doctors with pagination
        async function loadDoctorsFromAPI() {
            const grid = document.getElementById('doctorsGrid');
            const noDoctors = document.getElementById('noDoctors');
            
            try {
                const response = await fetch(`${API_BASE}/public/doctors?specialization_id=${SPECIALIZATION_ID}&per_page=100`);
                const result = await response.json();
                allDoctors = result.data || [];
                
                // Update doctor count
                document.getElementById('doctorCount').textContent = allDoctors.length;
                
                if (allDoctors.length === 0) {
                    grid.style.display = 'none';
                    noDoctors.style.display = 'block';
                    document.getElementById('pagination').style.display = 'none';
                    return;
                }
                
                grid.style.display = 'grid';
                noDoctors.style.display = 'none';
                renderDoctors();
                renderPagination();
                
            } catch(error) {
                console.error('Error loading doctors:', error);
                grid.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">Không thể tải danh sách bác sĩ</p>';
            }
        }

        // Render doctors for current page
        function renderDoctors() {
            const grid = document.getElementById('doctorsGrid');
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const pageDoctors = allDoctors.slice(start, end);
            
            grid.innerHTML = '';
            
            pageDoctors.forEach(doctor => {
                const card = document.createElement('div');
                card.className = 'doctor-card';
                card.dataset.doctorId = doctor.id;
                
                if (selectedDoctor && selectedDoctor.id === doctor.id) {
                    card.classList.add('selected');
                }
                
                const avatarUrl = doctor.avatar_url || '/frontend/img/default-avatar.png';
                const displayName = doctor.degree ? `${doctor.degree} ${doctor.full_name}` : doctor.full_name;
                const rating = doctor.rating_avg ? parseFloat(doctor.rating_avg).toFixed(1) : '0.0';
                const experience = doctor.experience || 0;
                
                card.innerHTML = `
                    ${doctor.avatar_url ? 
                        `<img src="${avatarUrl}" alt="${doctor.full_name}" class="doctor-avatar" onerror="this.parentElement.innerHTML = this.parentElement.innerHTML.replace(this.outerHTML, '<div class=\\'doctor-avatar-placeholder\\'>${doctor.full_name.charAt(0)}</div>')">` :
                        `<div class="doctor-avatar-placeholder">${doctor.full_name.charAt(0)}</div>`
                    }
                    <div class="doctor-info">
                        <div class="doctor-name">${displayName}</div>
                        <div class="doctor-degree">${doctor.degree || 'Bác sĩ chuyên khoa'}</div>
                        <div class="doctor-meta">
                            <span><i class="fas fa-star"></i> ${rating}</span>
                            <span><i class="fas fa-briefcase"></i> ${experience} năm KN</span>
                        </div>
                        <a href="/bac-si/${doctor.id}" class="btn-view-detail" onclick="event.stopPropagation();">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                `;
                
                card.addEventListener('click', () => selectDoctor(doctor));
                grid.appendChild(card);
            });
        }

        // Render pagination
        function renderPagination() {
            const pagination = document.getElementById('pagination');
            const totalPages = Math.ceil(allDoctors.length / perPage);
            
            if (totalPages <= 1) {
                pagination.style.display = 'none';
                return;
            }
            
            pagination.style.display = 'flex';
            pagination.innerHTML = '';
            
            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderDoctors();
                    renderPagination();
                }
            };
            pagination.appendChild(prevBtn);
            
            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                if (totalPages > 7) {
                    // Show limited pages for large datasets
                    if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                        const pageBtn = document.createElement('button');
                        pageBtn.textContent = i;
                        pageBtn.className = i === currentPage ? 'active' : '';
                        pageBtn.onclick = () => {
                            currentPage = i;
                            renderDoctors();
                            renderPagination();
                        };
                        pagination.appendChild(pageBtn);
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        const dots = document.createElement('span');
                        dots.textContent = '...';
                        dots.style.padding = '10px';
                        pagination.appendChild(dots);
                    }
                } else {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    pageBtn.className = i === currentPage ? 'active' : '';
                    pageBtn.onclick = () => {
                        currentPage = i;
                        renderDoctors();
                        renderPagination();
                    };
                    pagination.appendChild(pageBtn);
                }
            }
            
            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderDoctors();
                    renderPagination();
                }
            };
            pagination.appendChild(nextBtn);
        }

        // Select doctor
        function selectDoctor(doctor) {
            // Toggle selection
            if (selectedDoctor && selectedDoctor.id === doctor.id) {
                selectedDoctor = null;
            } else {
                selectedDoctor = doctor;
            }
            
            // Update UI
            document.querySelectorAll('.doctor-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            if (selectedDoctor) {
                const selectedCard = document.querySelector(`.doctor-card[data-doctor-id="${selectedDoctor.id}"]`);
                if (selectedCard) {
                    selectedCard.classList.add('selected');
                }
            }
            
            // Update booking button
            updateBookingButton();
        }
    </script>
</body>
</html>
