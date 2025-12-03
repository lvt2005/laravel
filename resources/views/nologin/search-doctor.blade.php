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
            <input type="text" class="search-input" placeholder="Nhập tên bác sĩ..." id="nameSearch" />
            <button class="search-btn" id="searchBtn">Tìm kiếm</button>
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
                <div class="filter-label">Sắp xếp theo đánh giá</div>
                <select class="search-select" id="ratingFilter">
                    <option value="">Mặc định</option>
                    <option value="desc">Cao đến thấp</option>
                    <option value="asc">Thấp đến cao</option>
                </select>
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
    const ITEMS_PER_PAGE = 6;
    let currentPage = 1;
    let allDoctors = [];
    let filteredDoctors = [];
    let specializations = [];


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
        grid.innerHTML = '<div class="loading-spinner"></div>';

        try {
            const response = await fetch(`${API_BASE}/public/doctors?per_page=100`);
            const result = await response.json();
            allDoctors = result.data || result || [];
            filteredDoctors = [...allDoctors];

            // Sắp xếp theo đánh giá cao nhất
            sortDoctors('desc');
            renderDoctors();
        } catch (error) {
            console.error('Error loading doctors:', error);
            grid.innerHTML = `
                <div class="no-results">
                    <div class="icon">❌</div>
                    <h3>Không thể tải dữ liệu</h3>
                    <p>Vui lòng thử lại sau</p>
                </div>
            `;
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        document.getElementById('searchBtn').addEventListener('click', filterDoctors);
        document.getElementById('nameSearch').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') filterDoctors();
        });
        document.getElementById('specialtyFilter').addEventListener('change', filterDoctors);
        document.getElementById('experienceFilter').addEventListener('change', filterDoctors);
        document.getElementById('ratingFilter').addEventListener('change', filterDoctors);
    }

    // Lọc bác sĩ
    function filterDoctors() {
        const nameSearch = document.getElementById('nameSearch').value.toLowerCase().trim();
        const specialtyFilter = document.getElementById('specialtyFilter').value;
        const experienceFilter = document.getElementById('experienceFilter').value;
        const ratingFilter = document.getElementById('ratingFilter').value;

        filteredDoctors = allDoctors.filter(doctor => {
            // Lọc theo tên
            if (nameSearch) {
                const fullName = (doctor.full_name || '').toLowerCase();
                const degree = (doctor.degree || '').toLowerCase();
                if (!fullName.includes(nameSearch) && !degree.includes(nameSearch)) {
                    return false;
                }
            }

            // Lọc theo chuyên khoa
            if (specialtyFilter) {
                if (doctor.specialization_id != specialtyFilter) {
                    return false;
                }
            }

            // Lọc theo kinh nghiệm
            if (experienceFilter) {
                const exp = parseInt(doctor.experience) || 0;
                if (experienceFilter === '0-5' && !(exp >= 0 && exp <= 5)) return false;
                if (experienceFilter === '5-10' && !(exp > 5 && exp <= 10)) return false;
                if (experienceFilter === '10-20' && !(exp > 10 && exp <= 20)) return false;
                if (experienceFilter === '20+' && exp <= 20) return false;
            }

            return true;
        });

        // Sắp xếp theo đánh giá
        if (ratingFilter) {
            sortDoctors(ratingFilter);
        }

        currentPage = 1;
        renderDoctors();
    }

    // Sắp xếp bác sĩ theo đánh giá
    function sortDoctors(order) {
        filteredDoctors.sort((a, b) => {
            const ratingA = parseFloat(a.rating_avg) || 0;
            const ratingB = parseFloat(b.rating_avg) || 0;
            return order === 'desc' ? ratingB - ratingA : ratingA - ratingB;
        });
    }

    // Render danh sách bác sĩ
    function renderDoctors() {
        const grid = document.getElementById('doctorsGrid');

        if (filteredDoctors.length === 0) {
            grid.innerHTML = `
                <div class="no-results">
                    <div class="icon">🔍</div>
                    <h3>Không tìm thấy bác sĩ</h3>
                    <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác</p>
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

        // Render cards
        grid.innerHTML = currentDoctors.map(doctor => createDoctorCard(doctor)).join('');

        // Render pagination
        renderPagination(totalPages);
    }

    // Tạo card bác sĩ
    function createDoctorCard(doctor) {
        const avatarUrl = doctor.avatar_url || '/frontend/img/Screenshot 2025-10-17 201418.png';
        const degree = doctor.degree || 'BS';
        const displayName = `${degree} ${doctor.full_name}`.toUpperCase();
        const specialization = doctor.specialization?.name || 'Đa khoa';
        const experience = doctor.experience || 0;
        const rating = parseFloat(doctor.rating_avg) || 0;
        const ratingCount = doctor.rating_count || 0;
        const clinicAddress = doctor.clinic?.address || 'Chưa cập nhật';
        const phone = doctor.phone || 'Chưa cập nhật';
        const email = doctor.email || 'Chưa cập nhật';

        // Tạo sao đánh giá
        const stars = '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));

        return `
            <div class="doctor-card" data-doctor-id="${doctor.id}">
                <div class="doctor-image">
                    <img src="${avatarUrl}" alt="${doctor.full_name}"
                         onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'" />
                    <button class="book-btn" onclick="bookDoctor(${doctor.id})">Đặt lịch hẹn</button>
                </div>
                <div class="doctor-info">
                    <h2 class="doctor-name">${displayName}</h2>
                    <div class="rating">
                        <span class="rating-score">${rating.toFixed(1)}</span>
                        <span class="rating-stars">${stars}</span>
                        <span style="color: #95a5a6; font-size: 14px">(${ratingCount} đánh giá)</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🎓</span>
                        <span>${degree}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">🏥</span>
                        <span>${experience} năm kinh nghiệm</span>
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
