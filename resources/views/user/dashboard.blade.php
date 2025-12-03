<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('frontend/css/header.css') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /*overflow-x: hidden ;*/
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

            .user-dropdown {
                right: auto;
                left: 0;
            }
        }

        /* Header banner styles from header.html */
        .header-banner {
            position: relative;
            width: 100%;
            min-height: 420px;
            overflow: hidden;
            background-color: #ffffff;
            background-image: url("/frontend/img/Banner-Website-MIEN-PHI-KHAM-VA-SIEU-AM-TUYEN-VU.png");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
        }

        .header-container {
            position: relative;
            z-index: 3;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
            width: 100%;
        }

        /* Left section */
        .header-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .header-boxes {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .header-box-main {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
        }

        .header-box-main h1 {
            color: #003d99;
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .header-box-main p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
            margin-top: 10px;
        }

        .btn-primary:hover {
            background: #f4c430;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 196, 48, 0.4);
        }

        .btn-icon::before {
            content: "🔍";
            display: inline-block;
            font-size: 16px;
        }

        .header-boxes-secondary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .header-box-small.compact {
            padding: 12px;
        }

        .header-box-small {
            background: rgba(255, 255, 255, 0.75);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(6px) saturate(120%);
            -webkit-backdrop-filter: blur(6px) saturate(120%);
        }

        .header-box-small img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .header-box-small h3 {
            color: #003d99;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header-box-small p {
            color: #555;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .btn-secondary {
            background: #ffd700;
            color: #003d99;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: fit-content;
        }

        .btn-secondary:hover {
            background: #ffed4e;
        }

        .hotline {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #003d99;
            font-weight: 700;
            font-size: 15px;
        }

        .hotline-btn {
            background: #ffd700;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.25);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .hotline-btn::before {
            content: "☎";
            font-size: 16px;
        }

        .hotline-btn:hover {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
        }

        /* Right section */
        .header-right {
            position: relative;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 10px;
        }

        .header-right img {
            width: 100%;
            height: 100%;
            max-width: 30px;
            object-fit: contain;
            display: block;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .header-banner::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                    rgba(0, 0, 0, 0.1) 0%,
                    rgba(0, 0, 0, 0.18) 100%);
            z-index: 2;
            pointer-events: none;
        }

        .header-banner::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.12);
            mix-blend-mode: screen;
            z-index: 2;
            pointer-events: none;
        }

        .header-left,
        .header-boxes,
        .header-box-main,
        .header-boxes-secondary {
            position: relative;
            z-index: 4;
        }

        .header-boxes-secondary .header-box-small:first-child {
            padding: 0;
            overflow: hidden;
            background: none;
            box-shadow: none;
            border-radius: 12px;
        }

        .header-boxes-secondary .header-box-small:first-child img {
            width: 100%;
            height: 100%;
            min-height: 220px;
            object-fit: cover;
            border-radius: 12px;
            margin: 0;
            display: block;
        }

        /* Responsive for header */
        @media (max-width: 768px) {
            .header-container {
                grid-template-columns: 1fr;
                padding: 30px 20px;
            }

            .header-box-main h1 {
                font-size: 24px;
            }

            .header-boxes-secondary {
                grid-template-columns: 1fr;
            }
        }

        /* Lienhe styles from lienhe.html */
        .steps-wrapper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 10px;
            padding: 10px 0;
        }

        .step-card {
            width: 100%;
            height: 350px;
            border-radius: 20px;
            padding: 30px;
            position: relative;
            color: white;
            display: flex;
            align-items: stretch;
            overflow: hidden;
        }

        .step-card.blue {
            background: linear-gradient(135deg, #2d5aa6 0%, #1e3f7a 100%);
            height: 300px;
        }

        .step-card.yellow {
            background: linear-gradient(135deg, #f4c430 0%, #e6b800 100%);
            color: #333;
            height: 300px;
        }

        .step-card.light-blue {
            background: linear-gradient(135deg, #5ba3d0 0%, #3d88b8 100%);
            height: 300px;
        }

        .step-badge {
            position: absolute;
            top: 20px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 28px;
            z-index: 10;
        }

        .step-card.blue .step-badge {
            color: #2d5aa6;
        }

        .step-card.yellow .step-badge {
            color: #f4c430;
        }

        .step-card.light-blue .step-badge {
            color: #5ba3d0;
        }

        .step-content-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-right: 30px;
            z-index: 2;
        }

        .step-title {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .step-card.yellow .step-title {
            border-bottom-color: rgba(0, 0, 0, 0.2);
        }

        .step-title:hover {
            color: inherit;
        }

        .step-card.blue .step-title:hover,
        .step-card.light-blue .step-title:hover {
            color: #f4c430;
        }

        .step-card.yellow .step-title:hover {
            color: white;
        }

        .step-description {
            font-size: 18px;
            line-height: 1.5;
        }

        .step-card.blue .step-description {
            font-size: 16px;
        }

        .step-icon-right {
            flex: 0 0 auto;
            width: 100px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.8;
        }

        .step-icon-right svg {
            width: 80px;
            height: 80px;
            margin-top: 50px;
        }

        /* Responsive for steps */
        @media (max-width: 768px) {
            .steps-wrapper {
                grid-template-columns: 1fr;
            }

            .step-card {
                width: 100%;
            }

            .step-title {
                font-size: 24px;
            }

            .step-description {
                font-size: 14px;
            }
        }

        /* Taisao styles from taisao.html */
        .why-choose {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 40px;
            align-items: center;
            padding: 60px 0;
        }

        .why-choose__left {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .why-choose__label {
            color: #4a9eff;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .why-choose__label::before {
            content: "+";
            font-size: 18px;
            font-weight: 300;
        }

        .why-choose__title {
            font-size: 42px;
            font-weight: 700;
            line-height: 1.3;
            color: #1a1a1a;
        }

        .why-choose__description {
            font-size: 15px;
            line-height: 1.6;
            color: #666;
        }

        .why-choose__button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 28px;
            background: linear-gradient(135deg, #5b6ef5 0%, #4a5fd9 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: fit-content;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .why-choose__button:hover {
            background: #f4c430;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 196, 48, 0.3);
        }

        .why-choose__button::before {
            content: "🔍";
            font-size: 16px;
        }

        .why-choose__center {
            position: relative;
            height: 500px;
        }

        .why-choose__image {
            position: relative;
            height: 100%;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(135deg, #e0f2ff 0%, #f0f8ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .why-choose__image::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 120px;
            background: linear-gradient(to bottom,
                    rgba(255, 255, 255, 0),
                    rgba(144, 210, 175, 0.15));
        }

        .why-choose__badge {
            position: absolute;
            bottom: 30px;
            left: 30px;
            background: rgba(220, 255, 240, 0.9);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 15px;
            text-align: center;
            z-index: 10;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .why-choose__badge-number {
            font-size: 36px;
            font-weight: 700;
            color: #2d5a4a;
            line-height: 1;
        }

        .why-choose__badge-text {
            font-size: 13px;
            color: #4a7c6a;
            margin-top: 8px;
        }

        .why-choose__right {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .why-choose__image-top {
            height: 150px;
            border-radius: 15px;
            background: linear-gradient(135deg, #fff0f5 0%, #ffe0f0 100%);
            overflow: hidden;
            margin-bottom: 10px;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            border-left: 4px solid #4a9eff;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            box-shadow: 0 4px 12px rgba(74, 158, 255, 0.15);
            transform: translateX(4px);
        }

        .feature-item__icon {
            font-size: 20px;
            min-width: 20px;
            color: #4a9eff;
        }

        .feature-item__content {
            flex: 1;
        }

        .feature-item__title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .feature-item__description {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }

        /* Responsive for taisao */
        @media (max-width: 1024px) {
            .why-choose {
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }

            .why-choose__title {
                font-size: 32px;
            }

            .why-choose__center {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            .why-choose {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .why-choose__title {
                font-size: 28px;
            }

            .why-choose__center {
                height: 300px;
            }
        }

        /* Grid styles */
        .grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 24px auto;
            padding: 0 12px;
        }

        .card {
            background: #f8f8f8;
            border: 1px solid #e3e3e3;
            padding: 16px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            height: 230px;
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .icon {
            width: 60px;
            height: 60px;
            margin-right: 12px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .ck-title {
            font-weight: 800;
            font-size: 1.15rem;
            margin-bottom: 0;
            text-align: left;
            width: auto;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ck-desc {
            color: #444;
            font-size: 0.95rem;
            line-height: 1.3;
            margin-bottom: 8px;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }

        .ck-link {
            margin-top: auto;
            display: flex;
            justify-content: center;
        }

        /* Styles for Xem chi tiết button (restyled) */
        .detail-btn {
            display: inline-block;
            background: #f4c430;
            color: #333;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(244, 196, 48, 0.3);
            margin-top: 8px;
            transition: all 0.3s ease;
        }

        .detail-btn:hover {
            background: #e8b81a;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(232, 184, 26, 0.4);
        }

        /* Full image cards */
        .card.full-img {
            padding: 0;
        }

        .card.full-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Promo banner */
        .promo-banner {
            grid-column: 1 / -1;
            border-radius: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 10px;
        }

        .promo-btn {
            margin-top: 8px;
        }

        /* Responsive for grid - Specializations */
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }

            /* On mobile: reorder cards to alternate image-content pattern per row */
            .grid .card:nth-child(4n+3),
            .grid .card:nth-child(4n+4) {
                order: 1;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .icon {
                margin-right: 0;
                margin-bottom: 4px;
            }

            .promo-banner {
                padding: 20px;
                min-height: 100px;
            }
        }

        /* Additional spacing */
        h1 {
            padding: 30px 0 20px;
            text-align: center;
            font-size: 28px;
        }

        .grid {
            margin-bottom: 30px;
        }

        /* Specialty section background */
        section:has(.grid) {
            background: #f5fcffd5;
            padding: 10px 0;
            margin: 0 -9999px;
            padding-left: 9999px;
            padding-right: 9999px;
        }

        /* Timbacsi styles from timbacsi.html */
        .doctors {
            padding: 20px 0;
        }

        .section-title {
            font-size: 32px;
            margin-bottom: 10px;
            text-align: center;
            color: #2c3e50;
            font-weight: 700;
        }

        .doctor-cards {
            display: flex;
            gap: 20px;
            margin-top: 30px;
            overflow: hidden;
            position: relative;
            width: 100%;
        }

        .doctor-cards-wrapper {
            display: flex;
            gap: 20px;
        }

        .doctor-cards .doctor-card {
            flex: 0 0 calc((100% - 20px) / 2);
            width: calc((100% - 20px) / 2);
            max-width: calc((100% - 20px) / 2);
            opacity: 1;
            transform: translateX(0);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }

        .doctor-cards .doctor-card.sliding-out {
            opacity: 0;
            transform: translateX(-50px);
        }

        .doctor-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.5s ease;
            min-height: 420px;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .img-wrap {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
            background: #e0e0e0;
        }

        .doctor-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .doctor-info {
            padding: 15px;
        }

        .doctor-info h3 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 600;
        }

        .doctor-info p {
            font-size: 12px;
            color: #666;
        }

        .overlay {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 12px;
            height: 0;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            pointer-events: none;
        }

        .doctor-card .learn-btn {
            pointer-events: auto;
            transform: translateY(calc(120% + 10px));
            transition: transform 320ms cubic-bezier(0.2, 0.8, 0.2, 1);
            margin: 0;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(53, 122, 189, 0.25);
        }

        .doctor-card:hover .learn-btn {
            background: linear-gradient(135deg, #f4c430 0%, #e0b020 100%);
            transform: translateY(0%);
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 20px;
            padding: 20px 0;
        }

        .pagination-btn {
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(53, 122, 189, 0.2);
        }

        .pagination-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #357abd 0%, #2a5f94 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(53, 122, 189, 0.3);
        }

        .pagination-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            opacity: 0.6;
            box-shadow: none;
        }

        .page-info {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            padding: 0 10px;
            min-width: 120px;
            text-align: center;
        }

        .page-info .current-page {
            font-weight: 700;
            color: #4a90e2;
            font-size: 18px;
        }

        /* Loading placeholder */
        .loading-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #666;
            grid-column: 1 / -1;
        }

        .loading-placeholder i {
            font-size: 48px;
            color: #3498db;
            margin-bottom: 15px;
        }

        .loading-placeholder p {
            font-size: 16px;
        }

        /* Services pagination */
        .services-pagination,
        .specializations-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-top: 30px;
            padding: 20px 0;
        }

        @media (max-width: 992px) {
            .doctor-cards .doctor-card {
                flex: 0 0 calc(50% - 10px);
                min-width: calc(50% - 10px);
            }
        }

        @media (max-width: 768px) {
            .doctor-cards {
                overflow: hidden;
            }

            .doctor-cards .doctor-card {
                flex: 0 0 100%;
                min-width: 100%;
            }

            .section-title {
                font-size: 24px;
            }

            .pagination {
                flex-direction: column;
                gap: 15px;
            }

            .pagination-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Services section styles from services.html */
        .services-section {
            max-width: 1200px;
            margin: 0px auto;
            padding: 0 20px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header h2 {
            font-size: 36px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .section-header p {
            font-size: 16px;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Service Cards Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .service-image {
            width: 100%;
            height: 100px;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-image i {
            font-size: 50px;
            color: white;
            opacity: 0.3;
        }

        .service-avatar {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 60px;
            height: 60px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .service-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .service-content {
            padding: 30px;
        }

        .service-content h3 {
            font-size: 22px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .service-content p {
            font-size: 15px;
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .service-features {
            list-style: none;
            margin-bottom: 25px;
        }

        .service-features li {
            padding: 8px 0;
            color: #555;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .service-features li i {
            color: #27ae60;
            font-size: 16px;
        }

        .service-price {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }

        .price-tag {
            font-size: 24px;
            font-weight: 700;
            color: #2563eb;
        }

        .price-tag small {
            font-size: 14px;
            color: #999;
            font-weight: 400;
        }

        .btn-book {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
        }

        .btn-book:hover {
            background: #f4c430;
            color: #333;
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(244, 196, 48, 0.4);
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            position: relative;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-content h3 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .section-header h2 {
                font-size: 28px;
            }

            .features-grid {
                grid-template-columns: 1fr;
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

    <!-- banner -->
    <section class="header-banner">
        <div class="header-container">
            <!-- Left Section -->
            <div class="header-left">
                <div class="header-boxes">
                    <div class="header-box-main">
                        <h1>
                            Hệ thống Đặt lịch Hẹn Bác sĩ - GIÚP BẠN KẾT NỐI VỚI BÁC SĨ DỄ
                            DÀNG HƠN
                        </h1>
                        <p>
                            Hệ thống đặt lịch hẹn giúp bạn dễ dàng kết nối với đội ngũ
                            chuyên gia hàng đầu. Đặt lịch nhanh chóng theo chuyên khoa, nhận
                            thông báo kịp thời và quản lý lịch khám hiệu quả.
                        </p>
                        <!-- <button class="btn-primary btn-icon">Tìm hiểu thêm</button> -->
                    </div>
                </div>

                <div class="header-boxes-secondary">
                    <div class="header-box-small">
                        <img src="{{ asset('frontend/img/supply2.jpg') }}" alt="Đội ngũ bác sĩ" />
                    </div>
                    <div class="header-box-small compact">
                        <h3>Đặt lịch nhanh chóng</h3>
                        <p>
                            Chọn bác sĩ phù hợp, đặt lịch hẹn online chỉ trong vài phút.
                        </p>
                        <div class="hotline">
                            <a href="/dat-lich/bieu-mau" class="hotline-btn" style="background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);">
                                <i class="fas fa-calendar-plus" style="margin-right: 5px;"></i> Đặt lịch ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- guide -->
    <div class="container">
        <div class="steps-wrapper">
            <!-- Step 1: Call -->
            <div class="step-card blue">
                <div class="step-badge">01</div>
                <div class="step-content-left">
                    <a href="tel:18006767" class="step-title">Gọi tổng đài</a>
                    <div class="step-description">
                        Để được tư vấn về triệu chứng bệnh, chuyên khoa cần thăm khám, đội
                        ngũ bác sĩ và phương pháp điều trị của bệnh viện.
                    </div>
                </div>
                <div class="step-icon-right">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                            fill="white" stroke="white" stroke-width="2" />
                    </svg>
                </div>
            </div>

            <!-- Step 2: Book Appointment -->
            <div class="step-card yellow">
                <div class="step-badge">02</div>
                <div class="step-content-left">
                    <a href="/dat-lich/bieu-mau" class="step-title">Đặt lịch hẹn</a>
                    <div class="step-description">
                        Để đặt hẹn trực tuyến hoặc cần dịch vụ khám cấp vào bất kỳ lúc
                        nào. Bệnh viện hỗ trợ khách hàng 24/7.
                    </div>
                </div>
                <div class="step-icon-right">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" stroke="#333" stroke-width="2"
                            fill="none" />
                        <path d="M16 2v4M8 2v4M3 10h18" stroke="#333" stroke-width="2" />
                        <circle cx="9" cy="15" r="1.5" fill="#333" />
                        <circle cx="15" cy="15" r="1.5" fill="#333" />
                    </svg>
                </div>
            </div>

            <!-- Step 3: Find Doctor -->
            <div class="step-card light-blue">
                <div class="step-badge">03</div>
                <div class="step-content-left">
                    <a href="/tim-bac-si" class="step-title">Tìm bác sĩ</a>
                    <div class="step-description">
                        Để trực tiếp thăm khám và điều trị bệnh với bác sĩ mà bạn tin
                        tưởng nhất.
                    </div>
                </div>
                <div class="step-icon-right">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="8" r="4" fill="white" stroke="white" stroke-width="2" />
                        <path d="M4 20c0-4 3.5-7 8-7s8 3 8 7" fill="white" stroke="white" stroke-width="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- why choose -->
    <div class="container">
        <section class="why-choose">
            <!-- LEFT -->
            <div class="why-choose__left">
                <div class="why-choose__label">LÝ DO CHỌN NÊN CHỌN CHÚNG TÔI</div>
                <h2 class="why-choose__title">
                    Tại sao nên chọn Hệ thống Đặt lịch Hẹn Bác sĩ của chúng tôi?
                </h2>
                <p class="why-choose__description">
                    Hệ thống giúp bệnh nhân dễ dàng đặt lịch khám với bác sĩ phù hợp
                    theo chuyên khoa, thời gian và địa điểm. Giảm thời gian chờ đợi, hỗ
                    trợ quản lý lịch hiệu quả cho bác sĩ và bệnh viện và phục vụ trên
                    toàn quốc.
                </p>
                <!-- <button class="why-choose__button">Tìm hiểu thêm</button> -->
            </div>

            <!-- CENTER -->
            <div class="why-choose__center">
                <div class="why-choose__image">
                    <img src="{{ asset('frontend/img/why.jpg') }}" alt="Doctor" style="width: 100%; height: 100%; object-fit: cover" />
                    <div class="why-choose__badge">
                        <div class="why-choose__badge-number">1000+</div>
                        <div class="why-choose__badge-text">Lịch hẹn/tháng</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="why-choose__right">
                <div class="why-choose__image-top">
                    <img src="{{ asset('frontend/img/checkup-img.jpg') }}" alt="Đặt lịch hẹn" style="
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 15px;
              " />
                </div>

                <div class="feature-item">
                    <span class="feature-item__icon">−</span>
                    <div class="feature-item__content">
                        <div class="feature-item__title">Đặt lịch dễ dàng</div>
                        <div class="feature-item__description">
                            Bệnh nhân chọn bác sĩ theo chuyên khoa, thời gian rảnh, và xác
                            nhận nhanh chóng qua ứng dụng.
                        </div>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-item__icon">+</span>
                    <div class="feature-item__content">
                        <div class="feature-item__title">Thông báo & Nhắc lịch</div>
                        <div class="feature-item__description">
                            Gửi email/push notification xác nhận, nhắc lịch trước 24h, và
                            cập nhật trạng thái tự động.
                        </div>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-item__icon">+</span>
                    <div class="feature-item__content">
                        <div class="feature-item__title">
                            Thanh toán an toàn & Đánh giá
                        </div>
                        <div class="feature-item__description">
                            Tích hợp VNPay, Momo; Bệnh nhân đánh giá bác sĩ sau khám để cải
                            thiện dịch vụ.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- chuyên khoa - Dynamic from API -->
    <section class="specializations-section" id="specializations-section">
        <h1>Chọn Chuyên Khoa Để Đặt Lịch Hẹn Với Bác Sĩ Hàng Đầu</h1>
        <div class="grid" id="specializations-grid">
            <!-- Loading placeholder -->
            <div class="loading-placeholder">
                <i class='bx bx-loader-alt bx-spin'></i>
                <p>Đang tải chuyên khoa...</p>
            </div>
        </div>
        <!-- Pagination for specializations -->
        <div class="specializations-pagination" id="specializations-pagination" style="display:none;">
            <button class="pagination-btn" id="specsPrevBtn" onclick="changeSpecializationsPage(-1)" disabled>
                <span>←</span> Trang trước
            </button>
            <div class="page-info">
                <span>Trang <span class="current-page" id="specsCurrentPage">1</span> /
                    <span id="specsTotalPages">1</span></span>
            </div>
            <button class="pagination-btn" id="specsNextBtn" onclick="changeSpecializationsPage(1)">
                Trang sau <span>→</span>
            </button>
        </div>
        <div class="promo-banner">
            <a href="/chuyen-khoa" class="promo-btn"><span class="arrow-icon"> → </span> Xem Tất Cả</a>
        </div>
    </section>

    <!-- dịch vụ - Dynamic from API with Pagination -->
    <section class="services-section" id="services-section">
        <div class="section-header">
            <h2>Các Dịch Vụ Của Chúng Tôi</h2>
            <p>Lựa chọn dịch vụ phù hợp với nhu cầu sức khỏe của bạn</p>
        </div>

        <div class="services-grid" id="services-grid">
            <!-- Loading placeholder -->
            <div class="loading-placeholder">
                <i class='bx bx-loader-alt bx-spin'></i>
                <p>Đang tải dịch vụ...</p>
            </div>
        </div>

        <!-- Pagination for services -->
        <div class="services-pagination" id="services-pagination">
            <button class="pagination-btn" id="servicesPrevBtn" onclick="changeServicesPage(-1)" disabled>
                <span>←</span> Trang trước
            </button>
            <div class="page-info">
                <span>Trang <span class="current-page" id="servicesCurrentPage">1</span> /
                    <span id="servicesTotalPages">1</span></span>
            </div>
            <button class="pagination-btn" id="servicesNextBtn" onclick="changeServicesPage(1)">
                Trang sau <span>→</span>
            </button>
        </div>
    </section>

    <section class="doctors" id="doctors">
        <div class="container">
            <h2 class="section-title">Đội ngũ bác sĩ chất lượng cao</h2>
            <div class="doctor-cards" id="doctorCards">
                <div class="doctor-cards-wrapper" id="doctorCardsWrapper">
                    <!-- Doctor cards will be rendered here by JavaScript -->
                </div>
            </div>
            <div class="pagination" style="display:none;">
                <button class="pagination-btn" id="prevBtn" onclick="changePage(-1)">
                    <span>←</span> Trang trước
                </button>
                <div class="page-info">
                    <span>Trang <span class="current-page" id="currentPage">1</span> /
                        <span id="totalPages">1</span></span>
                </div>
                <button class="pagination-btn" id="nextBtn" onclick="changePage(1)">
                    Trang sau <span>→</span>
                </button>
            </div>
        </div>
    </section>

    @include('partials.footer')

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
            loginBtn.style.display = 'inline-block';
            userMenu.classList.remove('active');
        }

        // Logout function
        document.getElementById('logoutBtn').addEventListener('click', async function (e) {
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

        // Navigate to profile
        document.getElementById('profileLink')?.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = '/ho-so';
        });

        // ============ DOCTORS SECTION - Lấy từ API với Auto-slide mượt ============
        let allDoctors = [];
        let doctorSlideInterval = null;
        let isInteracting = false;
        const DOCTORS_DISPLAY_COUNT = 2;
        const SLIDE_INTERVAL = 3000; // 3 giây

        // Fetch all doctors từ API
        async function fetchDoctors() {
            const wrapper = document.getElementById('doctorCardsWrapper');
            const container = document.getElementById('doctorCards');
            if (!wrapper || !container) {
                console.error('Doctor cards container not found');
                return;
            }
            container.innerHTML = '<div class="doctor-cards-wrapper" id="doctorCardsWrapper"><div style="text-align:center;padding:40px;color:#666;width:100%;"><i class="fas fa-spinner fa-spin" style="font-size:24px;margin-bottom:10px;display:block;"></i>Đang tải danh sách bác sĩ...</div></div>';

            try {
                // Fetch all doctors (no pagination needed for carousel)
                const url = `${API_BASE}/public/doctors?per_page=20`;
                const response = await fetch(url);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                if (data.data && data.data.length > 0) {
                    allDoctors = data.data;
                    renderDoctorsCarousel();
                    initDoctorCarousel();
                } else {
                    container.innerHTML = '<div class="doctor-cards-wrapper" id="doctorCardsWrapper"><div style="text-align:center;padding:40px;color:#666;width:100%;"><i class="fas fa-user-md" style="font-size:40px;margin-bottom:15px;display:block;color:#ccc;"></i>Hiện chưa có bác sĩ nào trong hệ thống</div></div>';
                }
            } catch (error) {
                console.error('Error fetching doctors:', error);
                container.innerHTML = '<div class="doctor-cards-wrapper" id="doctorCardsWrapper"><div style="text-align:center;padding:40px;color:#e74c3c;width:100%;"><i class="fas fa-exclamation-circle" style="font-size:40px;margin-bottom:15px;display:block;"></i>Không thể tải danh sách bác sĩ. Vui lòng thử lại sau.</div></div>';
            }
        }

        function renderDoctorsCarousel() {
            const container = document.getElementById('doctorCards');
            if (!container) return;

            if (allDoctors.length === 0) {
                container.innerHTML = '<div class="doctor-cards-wrapper" id="doctorCardsWrapper"><div style="text-align:center;padding:40px;color:#666;width:100%;">Không có bác sĩ nào</div></div>';
                return;
            }

            const cardsHtml = allDoctors.map((doctor, idx) => {
                const avatarUrl = doctor.avatar_url || '/frontend/img/Screenshot 2025-10-17 201418.png';
                const specialization = doctor.specialization?.name || 'Đa khoa';
                const experience = doctor.experience || 0;
                const degree = doctor.degree || '';
                const displayName = degree ? `${degree} ${doctor.full_name}` : doctor.full_name;
                const rating = doctor.rating_avg || 0;

                // Render stars
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;
                let starsHtml = '';
                for (let i = 0; i < fullStars; i++) starsHtml += '<i class="fas fa-star"></i>';
                if (halfStar) starsHtml += '<i class="fas fa-star-half-alt"></i>';
                for (let i = 0; i < emptyStars; i++) starsHtml += '<i class="far fa-star"></i>';

                return `
                    <div class="doctor-card" data-id="${doctor.id}" data-index="${idx}" onclick="viewDoctorDetail(${doctor.id})" style="cursor:pointer;">
                        <div class="img-wrap">
                            <img class="doctor-img" alt="${doctor.full_name}"
                                 src="${avatarUrl}"
                                 onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'" />
                        </div>
                        <div class="doctor-info">
                            <h3>${displayName}</h3>
                            <p class="doctor-specialty" style="color:#3498db;font-weight:500;">${specialization}</p>
                            <p>${experience} năm kinh nghiệm</p>
                            <div class="doctor-rating" style="color:#f4c430;margin-top:5px;">
                                ${starsHtml}
                                <span style="color:#666;font-size:12px;margin-left:5px;">(${rating.toFixed(1)})</span>
                            </div>
                        </div>
                        <div class="overlay"><button class="learn-btn">Xem chi tiết</button></div>
                    </div>
                `;
            }).join('');

            container.innerHTML = `<div class="doctor-cards-wrapper" id="doctorCardsWrapper" style="display:flex;gap:20px;transition:transform 0.5s ease-in-out;">${cardsHtml}</div>`;
            
            // Set initial card widths
            updateCardWidths();
        }

        function updateCardWidths() {
            const container = document.getElementById('doctorCards');
            const wrapper = document.getElementById('doctorCardsWrapper');
            if (!container || !wrapper) return;
            
            const containerWidth = container.offsetWidth || 800;
            const cardWidth = (containerWidth - 20) / 2; // 2 cards with 20px gap
            
            const cards = wrapper.querySelectorAll('.doctor-card');
            cards.forEach(card => {
                card.style.flex = `0 0 ${cardWidth}px`;
                card.style.width = `${cardWidth}px`;
                card.style.minWidth = `${cardWidth}px`;
            });
        }

        function initDoctorCarousel() {
            const wrapper = document.getElementById('doctorCardsWrapper');
            const container = document.getElementById('doctorCards');
            if (!wrapper || !container || allDoctors.length <= DOCTORS_DISPLAY_COUNT) return;

            // Add event listeners to pause on interaction
            container.addEventListener('mouseenter', () => {
                isInteracting = true;
                stopDoctorSlide();
            });
            container.addEventListener('mouseleave', () => {
                isInteracting = false;
                startDoctorSlide();
            });
            container.addEventListener('touchstart', () => {
                isInteracting = true;
                stopDoctorSlide();
            }, { passive: true });
            container.addEventListener('touchend', () => {
                // Resume after 3 seconds of no interaction
                setTimeout(() => {
                    if (!isInteracting) return;
                    isInteracting = false;
                    startDoctorSlide();
                }, 3000);
            });

            // Start auto-slide
            startDoctorSlide();
        }

        function startDoctorSlide() {
            if (doctorSlideInterval || allDoctors.length <= DOCTORS_DISPLAY_COUNT) return;
            doctorSlideInterval = setInterval(() => {
                if (!isInteracting) {
                    slideDoctors();
                }
            }, SLIDE_INTERVAL);
        }

        function stopDoctorSlide() {
            if (doctorSlideInterval) {
                clearInterval(doctorSlideInterval);
                doctorSlideInterval = null;
            }
        }

        function slideDoctors() {
            const wrapper = document.getElementById('doctorCardsWrapper');
            const container = document.getElementById('doctorCards');
            if (!wrapper || !container) return;

            const cards = wrapper.querySelectorAll('.doctor-card');
            if (cards.length <= DOCTORS_DISPLAY_COUNT) return;

            const containerWidth = container.offsetWidth || 800;
            const cardWidth = (containerWidth - 20) / 2;
            const slideDistance = cardWidth + 20; // card width + gap

            // Add transition and slide left
            wrapper.style.transition = 'transform 0.5s ease-in-out';
            wrapper.style.transform = `translateX(-${slideDistance}px)`;

            // After animation completes, move first card to end and reset position
            setTimeout(() => {
                wrapper.style.transition = 'none';
                wrapper.style.transform = 'translateX(0)';
                
                // Move first card to end
                const firstCard = wrapper.firstElementChild;
                if (firstCard) {
                    wrapper.appendChild(firstCard);
                }
                
                // Force reflow
                wrapper.offsetHeight;
            }, 500);
        }

        // Xem chi tiết bác sĩ - chuyển sang URL sạch
        function viewDoctorDetail(doctorId) {
            window.location.href = `/bac-si/${doctorId}`;
        }

        // ============ BOOKING MODAL ============
        function openBookingModal(serviceName) {
            const modal = document.getElementById("bookingModal");
            if (!modal) return;
            const serviceSelect = document.getElementById("serviceSelect");
            modal.classList.add("active");
            if (serviceName && serviceSelect) {
                serviceSelect.value = serviceName;
            }
            document.body.style.overflow = "hidden";
        }

        function closeBookingModal() {
            const modal = document.getElementById("bookingModal");
            if (!modal) return;
            modal.classList.remove("active");
            document.body.style.overflow = "auto";
        }

        // Close modal when clicking outside
        document.getElementById("bookingModal")?.addEventListener("click", function (e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Handle form submission
        document.getElementById("bookingForm")?.addEventListener("submit", function (e) {
            e.preventDefault();
            alert("Đặt lịch thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.");
            closeBookingModal();
            this.reset();
        });

        // ============ LOAD CHUYÊN KHOA TỪ API - BỐ CỤC XEN KẼ VỚI PHÂN TRANG ============
        const specializationIcons = [
            'bx-plus-medical', 'bx-brain', 'bx-heart', 'bx-bone', 'bx-injection',
            'bx-pulse', 'bx-capsule', 'bx-test-tube', 'bx-shield-plus', 'bx-first-aid',
            'bx-clinic', 'bx-dna'
        ];

        const specializationImages = [
            '{{ asset("frontend/img/shutterstock_2270906883-2048x1366.jpg") }}',
            '{{ asset("frontend/img/supply4.jpg") }}',
            '{{ asset("frontend/img/supply3.jpg") }}',
            '{{ asset("frontend/img/supply2.jpg") }}'
        ];

        let allSpecializations = [];
        let specsCurrentPage = 1;
        let specsTotalPages = 1;
        const specsPerPage = 8; // 4 hàng x 2 chuyên khoa/hàng = 8 chuyên khoa mỗi trang

        async function loadSpecializations() {
            const grid = document.getElementById('specializations-grid');
            if (!grid) return;

            try {
                const response = await fetch('/api/public/specializations');
                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    allSpecializations = result.data;
                    specsTotalPages = Math.ceil(allSpecializations.length / specsPerPage);
                    renderSpecializations();

                    // Cập nhật menu header
                    loadSpecializationsToMenu(result.data);
                } else {
                    grid.innerHTML = '<p style="text-align:center;padding:20px;">Không có chuyên khoa nào.</p>';
                }
            } catch (error) {
                console.error('Error loading specializations:', error);
                grid.innerHTML = '<p style="text-align:center;padding:20px;color:red;">Lỗi tải chuyên khoa.</p>';
            }
        }

        function renderSpecializations() {
            const grid = document.getElementById('specializations-grid');
            if (!grid) return;

            const defaultImages = [
                '{{ asset("frontend/img/shutterstock_2270906883-2048x1366.jpg") }}',
                '{{ asset("frontend/img/supply4.jpg") }}',
                '{{ asset("frontend/img/supply3.jpg") }}',
                '{{ asset("frontend/img/supply2.jpg") }}'
            ];

            const start = (specsCurrentPage - 1) * specsPerPage;
            const end = start + specsPerPage;
            const pageSpecs = allSpecializations.slice(start, end);

            let html = '';
            // Mỗi hàng có 4 phần tử: xen kẽ 1 ảnh - 1 nội dung
            // Hàng chẵn (0, 2): [ảnh, nội dung, ảnh, nội dung]
            // Hàng lẻ (1, 3): [nội dung, ảnh, nội dung, ảnh]
            let specIndex = 0;
            const maxRows = 4;
            
            for (let row = 0; row < maxRows && specIndex < pageSpecs.length; row++) {
                const isEvenRow = row % 2 === 0;
                
                // Mỗi hàng hiển thị 2 chuyên khoa (mỗi cặp ảnh-nội dung là 1 chuyên khoa)
                const spec1 = pageSpecs[specIndex];
                const spec2 = pageSpecs[specIndex + 1];
                const globalIndex1 = start + specIndex;
                const globalIndex2 = start + specIndex + 1;
                
                const img1 = spec1?.image_url || defaultImages[globalIndex1 % defaultImages.length];
                const img2 = spec2?.image_url || defaultImages[globalIndex2 % defaultImages.length];
                
                if (isEvenRow) {
                    // Pattern: [ảnh, nội dung, ảnh, nội dung]
                    if (spec1) {
                        html += renderSpecImage(img1, spec1.name);
                        html += renderSpecCard(spec1, globalIndex1);
                    }
                    if (spec2) {
                        html += renderSpecImage(img2, spec2.name);
                        html += renderSpecCard(spec2, globalIndex2);
                    }
                } else {
                    // Pattern: [nội dung, ảnh, nội dung, ảnh]
                    if (spec1) {
                        html += renderSpecCard(spec1, globalIndex1);
                        html += renderSpecImage(img1, spec1.name);
                    }
                    if (spec2) {
                        html += renderSpecCard(spec2, globalIndex2);
                        html += renderSpecImage(img2, spec2.name);
                    }
                }
                
                specIndex += 2;
            }

            grid.innerHTML = html;
            updateSpecializationsPagination();
        }

        function renderSpecImage(imageUrl, altText) {
            return `
                <div class="card full-img">
                    <img src="${imageUrl}" alt="${altText}" />
                </div>
            `;
        }

        function renderSpecCard(spec, index) {
            const iconClass = specializationIcons[index % specializationIcons.length];
            return `
                <div class="card">
                    <div class="card-header">
                        ${spec.image_url
                            ? `<img class="icon" src="${spec.image_url}" alt="${spec.name}" style="width:48px;height:48px;object-fit:cover;border-radius:8px;" />`
                            : `<i class='bx ${iconClass}' style="font-size:48px;color:#3498db;"></i>`
                        }
                        <div class="ck-title">${spec.name}</div>
                    </div>
                    <div class="ck-desc">
                        ${spec.description || 'Khoa chuyên môn hàng đầu của bệnh viện. Tìm bác sĩ phù hợp và đặt lịch hẹn nhanh chóng qua hệ thống.'}
                    </div>
                    <div class="ck-link">
                        <a href="/chuyen-khoa/${spec.id}"><button class="learn-btn detail-btn">Tìm hiểu thêm</button></a>
                    </div>
                </div>
            `;
        }

        function updateSpecializationsPagination() {
            const currentPageEl = document.getElementById('specsCurrentPage');
            const totalPagesEl = document.getElementById('specsTotalPages');
            const prevBtn = document.getElementById('specsPrevBtn');
            const nextBtn = document.getElementById('specsNextBtn');
            const paginationEl = document.getElementById('specializations-pagination');

            if (currentPageEl) currentPageEl.textContent = specsCurrentPage;
            if (totalPagesEl) totalPagesEl.textContent = specsTotalPages;
            if (prevBtn) prevBtn.disabled = specsCurrentPage === 1;
            if (nextBtn) nextBtn.disabled = specsCurrentPage === specsTotalPages;

            // Hiển thị pagination nếu có nhiều hơn 1 trang
            if (paginationEl) {
                paginationEl.style.display = specsTotalPages <= 1 ? 'none' : 'flex';
            }
        }

        function changeSpecializationsPage(direction) {
            const newPage = specsCurrentPage + direction;
            if (newPage >= 1 && newPage <= specsTotalPages) {
                specsCurrentPage = newPage;
                renderSpecializations();
                document.getElementById('specializations-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function loadSpecializationsToMenu(specializations) {
            const col1 = document.getElementById('menuSpecCol1');
            const col2 = document.getElementById('menuSpecCol2');
            if (!col1 || !col2) return;

            const half = Math.ceil(specializations.length / 2);
            const firstHalf = specializations.slice(0, half);
            const secondHalf = specializations.slice(half);

            col1.innerHTML = firstHalf.map(spec => {
                return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
            }).join('');

            col2.innerHTML = secondHalf.map(spec => {
                return `<li><a class="menu-link" href="/chuyen-khoa/${spec.id}">${spec.name}</a></li>`;
            }).join('');
        }

        // ============ LOAD DỊCH VỤ TỪ API ============
        let servicesCurrentPage = 1;
        let servicesTotalPages = 1;
        const servicesPerPage = 6;
        let allServices = [];

        const serviceIcons = [
            'bx-plus-medical', 'bx-search-alt', 'bx-heart', 'bx-injection',
            'bx-pulse', 'bx-capsule', 'bx-test-tube', 'bx-shield-plus',
            'bx-first-aid', 'bx-clinic', 'bx-dna', 'bx-brain'
        ];

        async function loadServices() {
            const grid = document.getElementById('services-grid');
            if (!grid) return;

            try {
                const response = await fetch('/api/public/services');
                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    allServices = result.data;
                    servicesTotalPages = Math.ceil(allServices.length / servicesPerPage);
                    renderServices();
                } else {
                    grid.innerHTML = '<p style="text-align:center;padding:20px;">Không có dịch vụ nào.</p>';
                }
            } catch (error) {
                console.error('Error loading services:', error);
                grid.innerHTML = '<p style="text-align:center;padding:20px;color:red;">Lỗi tải dịch vụ.</p>';
            }
        }

        function renderServices() {
            const grid = document.getElementById('services-grid');
            if (!grid) return;

            const start = (servicesCurrentPage - 1) * servicesPerPage;
            const end = start + servicesPerPage;
            const pageServices = allServices.slice(start, end);

            const html = pageServices.map((service, index) => {
                const iconClass = serviceIcons[(start + index) % serviceIcons.length];
                const price = service.formatted_price || service.price?.toLocaleString('vi-VN') + 'đ' || 'Liên hệ';
                const duration = service.duration_minutes ? `${service.duration_minutes} phút` : '';

                // Build booking URL with service and specialization params
                const bookingUrl = `/dat-lich/bieu-mau?service=${service.id}&specialization=${service.specialization_id || ''}`;

                const detailUrl = `/dich-vu/chi-tiet/${service.id}`;
                const avatarHtml = service.avatar_url 
                    ? `<div class="service-avatar"><img src="${service.avatar_url}" alt="${service.name}"></div>`
                    : '';

                // Build benefits list from database fields
                const benefits = [];
                if (service.benefit1) benefits.push(service.benefit1);
                if (service.benefit2) benefits.push(service.benefit2);
                if (service.benefit3) benefits.push(service.benefit3);
                if (service.benefit4) benefits.push(service.benefit4);
                
                // Fallback to default if no benefits
                if (benefits.length === 0) {
                    benefits.push('Đội ngũ bác sĩ chuyên nghiệp');
                    benefits.push('Thiết bị hiện đại');
                    benefits.push(duration || 'Thời gian linh hoạt');
                }

                const benefitsHtml = benefits.slice(0, 4).map(benefit => 
                    `<li><i class="bx bx-check-circle"></i> ${benefit}</li>`
                ).join('');
                
                return `
                    <div class="service-card" style="cursor:pointer;" onclick="window.location='${detailUrl}'">
                        <div class="service-image">
                            <i class="bx ${iconClass}"></i>
                            ${avatarHtml}
                        </div>
                        <div class="service-content">
                            <h3><a href="${detailUrl}" style="color:inherit;text-decoration:none;">${service.name}</a></h3>
                            <p>${service.description || 'Dịch vụ y tế chất lượng cao với đội ngũ bác sĩ chuyên môn hàng đầu.'}</p>
                            <ul class="service-features">
                                ${benefitsHtml}
                            </ul>
                            <div class="service-price">
                                <div class="price-tag">
                                    ${price}
                                    <small>/lần</small>
                                </div>
                                <a href="${bookingUrl}" class="btn-book" onclick="event.stopPropagation();">Đặt Lịch</a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            grid.innerHTML = html;
            updateServicesPagination();
        }

        function updateServicesPagination() {
            const currentPageEl = document.getElementById('servicesCurrentPage');
            const totalPagesEl = document.getElementById('servicesTotalPages');
            const prevBtn = document.getElementById('servicesPrevBtn');
            const nextBtn = document.getElementById('servicesNextBtn');
            const paginationEl = document.getElementById('services-pagination');

            if (currentPageEl) currentPageEl.textContent = servicesCurrentPage;
            if (totalPagesEl) totalPagesEl.textContent = servicesTotalPages;
            if (prevBtn) prevBtn.disabled = servicesCurrentPage === 1;
            if (nextBtn) nextBtn.disabled = servicesCurrentPage === servicesTotalPages;

            // Ẩn pagination nếu chỉ có 1 trang
            if (paginationEl) {
                paginationEl.style.display = servicesTotalPages <= 1 ? 'none' : 'flex';
            }
        }

        function changeServicesPage(direction) {
            const newPage = servicesCurrentPage + direction;
            if (newPage >= 1 && newPage <= servicesTotalPages) {
                servicesCurrentPage = newPage;
                renderServices();
                document.getElementById('services-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // ============ KHỞI TẠO KHI TRANG LOAD ============
        document.addEventListener('DOMContentLoaded', function () {
            checkLoginStatus();
            fetchDoctors();
            loadSpecializations();
            loadServices();
        });

        // Recalculate doctor card widths on resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (allDoctors.length > 0) {
                    updateCardWidths();
                }
            }, 250);
        });

        // Cleanup when leaving page
        window.addEventListener('beforeunload', function() {
            stopDoctorSlide();
        });
    </script>
    <script src="{{ asset('frontend/js/header.js') }}"></script>
</body>

</html>