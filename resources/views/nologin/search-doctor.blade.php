<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Đội Ngũ Bác Sĩ Chất Lượng</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            padding-top: 30px;
            padding-bottom: 30px;
        }

        /* Menu styles */
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

        /* User Menu Styles */
        .user-menu {
            position: relative;
            display: none;
            /* Hidden by default */
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

            .child {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                display: none;
                box-shadow: none;
                background-color: #f9f9f9;
            }

            .menu-item.menu-dropdown:hover .child {
                display: block;
            }

            .user-dropdown {
                right: auto;
                left: 0;
            }
        }


        /* Banner Section Styles */
        section {
            position: relative;
            width: 100%;
            height: 500px;
            background-image: url('../../img/banner1.png');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 5px;
            margin: 0 auto;
        }

        .logo {
            font-size: 2em;
            margin-right: 15px;
        }

        /* Logo biểu tượng y tế (stethoscope emoji) */
        .banner-text {
            color: white;
            font-size: 2.2em;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 0;
            /* Không gap để liền nhau */
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
            padding: 0;
            border-radius: 25px;
            /* Bo tròn toàn bộ thanh */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            width: fit-content;
            align-self: center;
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            overflow: hidden;
            /* Để bo tròn chỉ ở hai đầu */
        }

        .btn {
            padding: 15px 25px;
            border: none;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            min-width: 140px;
            text-align: center;
        }

        .btn::before {
            content: '';
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .btn:first-child {
            background-color: #007bff;
            /* Xanh dương */
            border-top-left-radius: 25px;
            border-bottom-left-radius: 25px;
        }

        .btn:first-child::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z'/%3E%3C/svg%3E");
            /* Icon điện thoại trắng */
        }

        .btn:nth-child(2) {
            background-color: #ffc107;
            /* Vàng */
            color: black;
        }

        .btn:nth-child(2)::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='black'%3E%3Cpath d='M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z'/%3E%3C/svg%3E");
            /* Icon lịch đen */
        }

        .btn:last-child {
            background-color: #007bff;
            /* Xanh dương */
            border-top-right-radius: 25px;
            border-bottom-right-radius: 25px;
        }

        .btn:last-child::before {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'/%3E%3C/svg%3E");
            /* Icon người trắng */
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            section {
                padding: 20px;
                height: 500px;
            }

            .banner-text {
                font-size: 1.8em;
            }

            .buttons {
                flex-direction: column;
                border-radius: 15px;
                bottom: 20px;
            }

            .btn {
                border-radius: 0;
                min-width: auto;
                width: 100%;
            }

            .btn:first-child {
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;
            }

            .btn:last-child {
                border-bottom-left-radius: 15px;
                border-bottom-right-radius: 15px;
            }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            margin-top: 40px;
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
            margin-top: 30px;
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
            margin-bottom: 50px;
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
            background: #e8cb37;
            transform: translateX(-50%) translateY(-2px);
            color: #000;
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
            font-size: 20px;
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

        /* Footer styles from footer.html */
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

        h4 {
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

            h4 {
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
            .container {
                padding: 0 15px;
            }

            .footer-content {
                gap: 20px;
            }

            h4 {
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
</head>

<body>
<script src="{{ asset('frontend/js/auth.js') }}"></script>
@include('partials.header')
<div class="container">
    <div class="header">
        <div class="header-title">ĐỘI NGŨ CHÚNG TÔI</div>
        <h1>
            Đội ngũ bác sĩ chất lượng<br /><span class="highlight">chuyên môn cao</span>
        </h1>
    </div>

    <div class="search-section">
        <div class="search-row">
            <input type="text" class="search-input" placeholder="Tìm theo tên, học vị, chuyên môn..." id="nameSearch" />
            <button class="search-btn" id="searchBtn">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
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
                <div class="filter-label">Đánh giá tối thiểu</div>
                <select class="search-select" id="minRatingFilter">
                    <option value="">Tất cả</option>
                    <option value="4.5">⭐ 4.5+ Xuất sắc</option>
                    <option value="4">⭐ 4.0+ Rất tốt</option>
                    <option value="3.5">⭐ 3.5+ Tốt</option>
                    <option value="3">⭐ 3.0+ Khá</option>
                </select>
            </div>
            <div class="filter-group">
                <div class="filter-label">Sắp xếp theo</div>
                <select class="search-select" id="sortFilter">
                    <option value="rating-desc">Đánh giá cao nhất</option>
                    <option value="rating-asc">Đánh giá thấp nhất</option>
                    <option value="exp-desc">Kinh nghiệm nhiều nhất</option>
                    <option value="exp-asc">Kinh nghiệm ít nhất</option>
                    <option value="name-asc">Tên A-Z</option>
                    <option value="name-desc">Tên Z-A</option>
                    <option value="reviews-desc">Nhiều đánh giá nhất</option>
                </select>
            </div>
        </div>
        <div class="search-row" style="justify-content: space-between; align-items: center; margin-top: 15px;">
            <div class="filter-tags" id="filterTags" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <!-- Active filter tags will appear here -->
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
                <span id="resultCount" style="color: #7f8c8d; font-size: 14px;"></span>
                <button class="reset-btn" id="resetBtn" style="padding: 8px 16px; background: #e74c3c; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 13px; display: none;">
                    <i class="fas fa-times"></i> Xóa bộ lọc
                </button>
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

@include('partials.footer')

<script>
    const API_BASE = '/api';
    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;
    let allDoctors = [];
    let filteredDoctors = [];
    let specializations = [];
    let searchTimeout = null;
    let activeFilters = {};

    // ============================================
    // ADVANCED SEARCH & FILTER ENGINE
    // ============================================

    // Normalize Vietnamese text for search (remove diacritics)
    function normalizeVietnamese(str) {
        if (!str) return '';
        return str
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D');
    }

    // Fuzzy search - find similar strings
    function fuzzyMatch(text, query) {
        if (!text || !query) return false;
        
        const normalizedText = normalizeVietnamese(text);
        const normalizedQuery = normalizeVietnamese(query);
        
        // Exact match
        if (normalizedText.includes(normalizedQuery)) return true;
        
        // Word-by-word match
        const queryWords = normalizedQuery.split(/\s+/).filter(w => w.length > 1);
        const textWords = normalizedText.split(/\s+/);
        
        // All query words must be found
        return queryWords.every(qWord => 
            textWords.some(tWord => tWord.includes(qWord) || qWord.includes(tWord))
        );
    }

    // Calculate search relevance score
    function calculateRelevanceScore(doctor, query) {
        if (!query) return 0;
        
        let score = 0;
        const normalizedQuery = normalizeVietnamese(query);
        
        // Name match (highest priority)
        const fullName = normalizeVietnamese(doctor.full_name || '');
        if (fullName === normalizedQuery) score += 100;
        else if (fullName.startsWith(normalizedQuery)) score += 80;
        else if (fullName.includes(normalizedQuery)) score += 60;
        
        // Degree match
        const degree = normalizeVietnamese(doctor.degree || '');
        if (degree.includes(normalizedQuery)) score += 40;
        
        // Specialization match
        const specName = normalizeVietnamese(doctor.specialization?.name || '');
        if (specName.includes(normalizedQuery)) score += 50;
        
        // Bio/description match
        const bio = normalizeVietnamese(doctor.bio || '');
        if (bio.includes(normalizedQuery)) score += 20;
        
        // Clinic name match
        const clinicName = normalizeVietnamese(doctor.clinic?.name || '');
        if (clinicName.includes(normalizedQuery)) score += 30;
        
        // Clinic address match
        const clinicAddress = normalizeVietnamese(doctor.clinic?.address || '');
        if (clinicAddress.includes(normalizedQuery)) score += 15;
        
        return score;
    }

    // Advanced filter function
    function advancedFilter(doctors, filters) {
        return doctors.filter(doctor => {
            // Text search filter
            if (filters.search) {
                const searchTerms = filters.search.toLowerCase().split(/\s+/).filter(t => t.length > 0);
                const searchableText = [
                    doctor.full_name,
                    doctor.degree,
                    doctor.specialization?.name,
                    doctor.bio,
                    doctor.clinic?.name,
                    doctor.clinic?.address,
                    doctor.phone,
                    doctor.email
                ].filter(Boolean).join(' ').toLowerCase();
                
                const normalizedSearchable = normalizeVietnamese(searchableText);
                
                // All search terms must match
                const allTermsMatch = searchTerms.every(term => {
                    const normalizedTerm = normalizeVietnamese(term);
                    return normalizedSearchable.includes(normalizedTerm) || 
                           fuzzyMatch(searchableText, term);
                });
                
                if (!allTermsMatch) return false;
            }
            
            // Specialization filter
            if (filters.specialization && doctor.specialization_id != filters.specialization) {
                return false;
            }
            
            // Experience filter
            if (filters.experience) {
                const exp = parseInt(doctor.experience) || 0;
                switch (filters.experience) {
                    case '0-5': if (exp > 5) return false; break;
                    case '5-10': if (exp < 5 || exp > 10) return false; break;
                    case '10-20': if (exp < 10 || exp > 20) return false; break;
                    case '20+': if (exp < 20) return false; break;
                }
            }
            
            // Minimum rating filter
            if (filters.minRating) {
                const rating = parseFloat(doctor.rating_avg) || 0;
                if (rating < parseFloat(filters.minRating)) return false;
            }
            
            return true;
        });
    }

    // Advanced sort function
    function advancedSort(doctors, sortBy, searchQuery = '') {
        return [...doctors].sort((a, b) => {
            // If there's a search query, prioritize by relevance first
            if (searchQuery) {
                const scoreA = calculateRelevanceScore(a, searchQuery);
                const scoreB = calculateRelevanceScore(b, searchQuery);
                if (scoreA !== scoreB) return scoreB - scoreA;
            }
            
            switch (sortBy) {
                case 'rating-desc':
                    return (parseFloat(b.rating_avg) || 0) - (parseFloat(a.rating_avg) || 0);
                case 'rating-asc':
                    return (parseFloat(a.rating_avg) || 0) - (parseFloat(b.rating_avg) || 0);
                case 'exp-desc':
                    return (parseInt(b.experience) || 0) - (parseInt(a.experience) || 0);
                case 'exp-asc':
                    return (parseInt(a.experience) || 0) - (parseInt(b.experience) || 0);
                case 'name-asc':
                    return (a.full_name || '').localeCompare(b.full_name || '', 'vi');
                case 'name-desc':
                    return (b.full_name || '').localeCompare(a.full_name || '', 'vi');
                case 'reviews-desc':
                    return (parseInt(b.rating_count) || 0) - (parseInt(a.rating_count) || 0);
                default:
                    return (parseFloat(b.rating_avg) || 0) - (parseFloat(a.rating_avg) || 0);
            }
        });
    }

    // Apply all filters and sort
    function applyFiltersAndSort() {
        const searchValue = document.getElementById('nameSearch').value.trim();
        const specialtyValue = document.getElementById('specialtyFilter').value;
        const experienceValue = document.getElementById('experienceFilter').value;
        const minRatingValue = document.getElementById('minRatingFilter').value;
        const sortValue = document.getElementById('sortFilter').value;
        
        // Build filters object
        activeFilters = {};
        if (searchValue) activeFilters.search = searchValue;
        if (specialtyValue) activeFilters.specialization = specialtyValue;
        if (experienceValue) activeFilters.experience = experienceValue;
        if (minRatingValue) activeFilters.minRating = minRatingValue;
        
        // Apply filters
        filteredDoctors = advancedFilter(allDoctors, activeFilters);
        
        // Apply sort
        filteredDoctors = advancedSort(filteredDoctors, sortValue, searchValue);
        
        // Update UI
        currentPage = 1;
        renderDoctors();
        updateFilterTags();
        updateResultCount();
        updateResetButton();
        
        // Save filters to URL
        updateURLParams();
    }

    // Update filter tags display
    function updateFilterTags() {
        const container = document.getElementById('filterTags');
        container.innerHTML = '';
        
        if (activeFilters.search) {
            container.appendChild(createFilterTag('Tìm kiếm', activeFilters.search, 'search'));
        }
        
        if (activeFilters.specialization) {
            const specName = specializations.find(s => s.id == activeFilters.specialization)?.name || '';
            container.appendChild(createFilterTag('Chuyên khoa', specName, 'specialization'));
        }
        
        if (activeFilters.experience) {
            const expLabels = {
                '0-5': '0-5 năm',
                '5-10': '5-10 năm',
                '10-20': '10-20 năm',
                '20+': 'Trên 20 năm'
            };
            container.appendChild(createFilterTag('Kinh nghiệm', expLabels[activeFilters.experience], 'experience'));
        }
        
        if (activeFilters.minRating) {
            container.appendChild(createFilterTag('Đánh giá', `≥ ${activeFilters.minRating}⭐`, 'minRating'));
        }
    }

    // Create filter tag element
    function createFilterTag(label, value, filterKey) {
        const tag = document.createElement('span');
        tag.className = 'filter-tag';
        tag.style.cssText = 'background: #e3f2fd; color: #1976d2; padding: 6px 12px; border-radius: 20px; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;';
        tag.innerHTML = `
            <span>${label}: <strong>${value}</strong></span>
            <button onclick="removeFilter('${filterKey}')" style="background: none; border: none; color: #1976d2; cursor: pointer; font-size: 16px; padding: 0; line-height: 1;">×</button>
        `;
        return tag;
    }

    // Remove individual filter
    function removeFilter(filterKey) {
        switch (filterKey) {
            case 'search':
                document.getElementById('nameSearch').value = '';
                break;
            case 'specialization':
                document.getElementById('specialtyFilter').value = '';
                break;
            case 'experience':
                document.getElementById('experienceFilter').value = '';
                break;
            case 'minRating':
                document.getElementById('minRatingFilter').value = '';
                break;
        }
        applyFiltersAndSort();
    }

    // Update result count
    function updateResultCount() {
        const countEl = document.getElementById('resultCount');
        const total = filteredDoctors.length;
        const allTotal = allDoctors.length;
        
        if (Object.keys(activeFilters).length > 0) {
            countEl.textContent = `Tìm thấy ${total} / ${allTotal} bác sĩ`;
        } else {
            countEl.textContent = `Tổng cộng ${allTotal} bác sĩ`;
        }
    }

    // Update reset button visibility
    function updateResetButton() {
        const resetBtn = document.getElementById('resetBtn');
        resetBtn.style.display = Object.keys(activeFilters).length > 0 ? 'inline-block' : 'none';
    }

    // Reset all filters
    function resetAllFilters() {
        document.getElementById('nameSearch').value = '';
        document.getElementById('specialtyFilter').value = '';
        document.getElementById('experienceFilter').value = '';
        document.getElementById('minRatingFilter').value = '';
        document.getElementById('sortFilter').value = 'rating-desc';
        applyFiltersAndSort();
    }

    // Update URL params for sharing/bookmarking
    function updateURLParams() {
        const params = new URLSearchParams();
        
        if (activeFilters.search) params.set('q', activeFilters.search);
        if (activeFilters.specialization) params.set('spec', activeFilters.specialization);
        if (activeFilters.experience) params.set('exp', activeFilters.experience);
        if (activeFilters.minRating) params.set('rating', activeFilters.minRating);
        
        const sortValue = document.getElementById('sortFilter').value;
        if (sortValue !== 'rating-desc') params.set('sort', sortValue);
        
        const newURL = params.toString() ? `${window.location.pathname}?${params.toString()}` : window.location.pathname;
        window.history.replaceState({}, '', newURL);
    }

    // Load filters from URL params
    function loadFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);
        
        if (params.has('q')) document.getElementById('nameSearch').value = params.get('q');
        if (params.has('spec')) document.getElementById('specialtyFilter').value = params.get('spec');
        if (params.has('exp')) document.getElementById('experienceFilter').value = params.get('exp');
        if (params.has('rating')) document.getElementById('minRatingFilter').value = params.get('rating');
        if (params.has('sort')) document.getElementById('sortFilter').value = params.get('sort');
    }

    // Debounced search for real-time filtering
    function debouncedSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            applyFiltersAndSort();
        }, 300);
    }

    // Highlight search terms in text
    function highlightSearchTerms(text, query) {
        if (!query || !text) return text;
        
        const terms = query.split(/\s+/).filter(t => t.length > 1);
        let result = text;
        
        terms.forEach(term => {
            const regex = new RegExp(`(${term})`, 'gi');
            result = result.replace(regex, '<mark style="background: #fff59d; padding: 0 2px; border-radius: 2px;">$1</mark>');
        });
        
        return result;
    }


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
});

    
    // Load data khi trang được tải
    document.addEventListener('DOMContentLoaded', async function() {
        await loadSpecializations();
        loadFiltersFromURL();
        await loadDoctors();
        setupEventListeners();
    });

    // Load danh sách chuyên khoa từ API
    async function loadSpecializations() {
        try {
            const response = await fetch(`${API_BASE}/public/specializations`);
            const result = await response.json();
            // API returns {success: true, data: [...]} format
            specializations = result.data || result || [];

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
        grid.innerHTML = '<div class="loading-spinner" style="text-align: center; padding: 60px;"><i class="fas fa-spinner fa-spin fa-3x" style="color: #4a69bd;"></i><p style="margin-top: 15px; color: #7f8c8d;">Đang tải danh sách bác sĩ...</p></div>';

        try {
            const response = await fetch(`${API_BASE}/public/doctors?per_page=100`);
            const result = await response.json();
            allDoctors = result.data || result || [];
            
            // Apply initial filters from URL
            applyFiltersAndSort();
        } catch (error) {
            console.error('Error loading doctors:', error);
            grid.innerHTML = `
                <div class="no-results" style="text-align: center; padding: 60px; grid-column: 1/-1;">
                    <div style="font-size: 60px; margin-bottom: 20px;">❌</div>
                    <h3 style="color: #e74c3c; margin-bottom: 10px;">Không thể tải dữ liệu</h3>
                    <p style="color: #7f8c8d;">Vui lòng thử lại sau</p>
                    <button onclick="loadDoctors()" style="margin-top: 20px; padding: 12px 24px; background: #4a69bd; color: white; border: none; border-radius: 25px; cursor: pointer;">
                        <i class="fas fa-redo"></i> Thử lại
                    </button>
                </div>
            `;
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        // Search button click
        document.getElementById('searchBtn').addEventListener('click', applyFiltersAndSort);
        
        // Real-time search on typing
        document.getElementById('nameSearch').addEventListener('input', debouncedSearch);
        
        // Enter key to search
        document.getElementById('nameSearch').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                clearTimeout(searchTimeout);
                applyFiltersAndSort();
            }
        });
        
        // Filter changes - instant apply
        document.getElementById('specialtyFilter').addEventListener('change', applyFiltersAndSort);
        document.getElementById('experienceFilter').addEventListener('change', applyFiltersAndSort);
        document.getElementById('minRatingFilter').addEventListener('change', applyFiltersAndSort);
        document.getElementById('sortFilter').addEventListener('change', applyFiltersAndSort);
        
        // Reset button
        document.getElementById('resetBtn').addEventListener('click', resetAllFilters);
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('nameSearch').focus();
            }
            // Escape to clear search
            if (e.key === 'Escape' && document.activeElement.id === 'nameSearch') {
                document.getElementById('nameSearch').value = '';
                applyFiltersAndSort();
            }
        });
    }

    // Render danh sách bác sĩ
    function renderDoctors() {
        const grid = document.getElementById('doctorsGrid');
        const searchQuery = document.getElementById('nameSearch').value.trim();

        if (filteredDoctors.length === 0) {
            grid.innerHTML = `
                <div class="no-results" style="text-align: center; padding: 60px; grid-column: 1/-1;">
                    <div style="font-size: 60px; margin-bottom: 20px;">🔍</div>
                    <h3 style="color: #2c3e50; margin-bottom: 10px;">Không tìm thấy bác sĩ</h3>
                    <p style="color: #7f8c8d; margin-bottom: 20px;">Vui lòng thử lại với từ khóa hoặc bộ lọc khác</p>
                    <button onclick="resetAllFilters()" style="padding: 12px 24px; background: #4a69bd; color: white; border: none; border-radius: 25px; cursor: pointer;">
                        <i class="fas fa-undo"></i> Xóa bộ lọc
                    </button>
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

        // Render cards with search highlighting
        grid.innerHTML = currentDoctors.map(doctor => createDoctorCard(doctor, searchQuery)).join('');

        // Render pagination
        renderPagination(totalPages);
    }

    // Tạo card bác sĩ với highlight
    function createDoctorCard(doctor, searchQuery = '') {
        const avatarUrl = doctor.avatar_url || '/frontend/img/Screenshot 2025-10-17 201418.png';
        const degree = doctor.degree || 'BS';
        let displayName = `${degree} ${doctor.full_name}`.toUpperCase();
        let specialization = doctor.specialization?.name || 'Đa khoa';
        const experience = doctor.experience || 0;
        const rating = parseFloat(doctor.rating_avg) || 0;
        const ratingCount = doctor.rating_count || 0;
        let clinicAddress = doctor.clinic?.address || 'Chưa cập nhật';
        const phone = doctor.phone || 'Chưa cập nhật';
        const email = doctor.email || 'Chưa cập nhật';

        // Highlight search terms
        if (searchQuery) {
            displayName = highlightSearchTerms(displayName, searchQuery);
            specialization = highlightSearchTerms(specialization, searchQuery);
            clinicAddress = highlightSearchTerms(clinicAddress, searchQuery);
        }

        // Tạo sao đánh giá
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
        const stars = '★'.repeat(fullStars) + (hasHalfStar ? '½' : '') + '☆'.repeat(emptyStars);

        // Rating badge color
        let ratingBadgeColor = '#95a5a6';
        if (rating >= 4.5) ratingBadgeColor = '#27ae60';
        else if (rating >= 4) ratingBadgeColor = '#2ecc71';
        else if (rating >= 3.5) ratingBadgeColor = '#f39c12';
        else if (rating >= 3) ratingBadgeColor = '#e67e22';

        return `
            <div class="doctor-card" data-doctor-id="${doctor.id}">
                <div class="doctor-image">
                    <img src="${avatarUrl}" alt="${doctor.full_name}"
                         onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'" 
                         loading="lazy" />
                    <button class="book-btn" onclick="bookDoctor(${doctor.id})">Đặt lịch hẹn</button>
                </div>
                <div class="doctor-info">
                    <h2 class="doctor-name">${displayName}</h2>
                    <div class="rating">
                        <span class="rating-score" style="background: ${ratingBadgeColor}; color: white; padding: 4px 10px; border-radius: 12px; font-size: 14px;">${rating.toFixed(1)}</span>
                        <span class="rating-stars" style="color: #f39c12;">${stars}</span>
                        <span style="color: #95a5a6; font-size: 13px">(${ratingCount} đánh giá)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🎓</span>
                        <span>${degree}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🏥</span>
                        <span><strong>${experience}</strong> năm kinh nghiệm</span>
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
