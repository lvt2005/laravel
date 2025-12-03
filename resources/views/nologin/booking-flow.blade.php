<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/boxicons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <title>Bệnh viện Nam Sài Gòn</title>
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

    .menu-item.menu-dropdown:hover .child {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
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

    /* footer  */
    /* Footer styles from footer.html */
    footer {
      background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
      color: #ffffff;
      padding: 50px 0 30px;
      margin-top: 40px;
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

    .wrapper {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
      min-height: 700px;
      margin-top: 50px;
    }

    .form-section {
      padding: 40px;
      display: flex;
      flex-direction: column;
    }

    .image-section {
      background-image: url("/frontend/img/why.jpg");
      background-size: cover;
      background-position: center;
      border-radius: 0;
    }

    .form-title {
      font-size: 28px;
      font-weight: 700;
      color: #1e5ba8;
      margin-bottom: 30px;
      text-align: center;
    }

    .form-content {
      flex: 1;
      overflow-y: auto;
    }

    .form-group {
      margin-bottom: 14px;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 0px;
    }

    .form-row-full {
      grid-column: 1 / -1;
    }

    label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
      width: 100%;
      padding: 12px 14px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="text"]:focus,
    input[type="tel"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: #1e5ba8;
      box-shadow: 0 0 0 3px rgba(30, 91, 168, 0.1);
    }

    input[type="text"]:read-only {
      background-color: #f5f5f5;
      cursor: not-allowed;
    }

    input[type="text"]::placeholder,
    input[type="tel"]::placeholder,
    input[type="email"]::placeholder,
    textarea::placeholder {
      color: #999;
    }

    textarea {
      resize: vertical;
      min-height: 100px;
      padding: 14px;
    }

    select {
      cursor: pointer;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 12px center;
      padding-right: 36px;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    .radio-group {
      display: flex;
      gap: 12px;
      margin-top: 8px;
      flex-wrap: wrap;
      width: 100%;
    }

    .radio-item {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 6px;
      background: white;
      transition: all 0.3s ease;
      flex: 1;
      justify-content: center;
    }

    .radio-item:hover {
      border-color: #1e5ba8;
      background: #f5f9fd;
    }

    input[type="radio"]:checked+label {
      color: #1e5ba8;
      font-weight: 600;
    }

    input[type="radio"],
    input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #1e5ba8;
      flex-shrink: 0;
    }

    .radio-item label,
    .checkbox-item label {
      margin: 0;
      cursor: pointer;
      font-weight: 500;
      font-size: 13px;
      color: #333;
    }

    .checkbox-item-simple {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .checkbox-item-simple label {
      margin: 0;
      cursor: pointer;
      font-weight: 500;
      font-size: 13px;
      color: #333;
    }

    .submit-btn {
      width: 100%;
      padding: 14px 24px;
      background: #1e5ba8;
      color: white;
      font-size: 14px;
      font-weight: 700;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 20px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .submit-btn:hover {
      background: #1a4a8a;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
    }

    .submit-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }

    /* Doctor selector input */
    .doctor-selector-input {
      cursor: pointer;
      background-color: white;
    }

    /* Doctor Overlay */
    .doctor-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .doctor-overlay.active {
      display: flex;
    }

    .doctor-cards-container {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
      justify-content: center;
      max-width: 1200px;
      max-height: 90vh;
      overflow-y: auto;
      padding: 20px;
    }

    .doctor-card {
      width: 280px;
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      opacity: 0;
      transform: translateX(-100px) scale(0.8);
      animation: slideInDoctor 0.6s ease forwards;
    }

    .doctor-card:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 60px rgba(30, 91, 168, 0.4);
    }

    @keyframes slideInDoctor {
      to {
        opacity: 1;
        transform: translateX(0) scale(1);
      }
    }

    .doctor-card-avatar {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .doctor-card-info {
      padding: 20px;
    }

    .doctor-card-name {
      font-size: 18px;
      font-weight: 700;
      color: #1e5ba8;
      margin-bottom: 8px;
    }

    .doctor-card-spec {
      font-size: 13px;
      color: #666;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .doctor-card-details {
      font-size: 12px;
      color: #555;
    }

    .doctor-card-details div {
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .doctor-card-rating {
      display: flex;
      align-items: center;
      gap: 4px;
      color: #f5a623;
      font-weight: 600;
    }

    .close-overlay-btn {
      position: fixed;
      top: 20px;
      right: 30px;
      background: white;
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 24px;
      cursor: pointer;
      z-index: 10000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
      transition: all 0.3s ease;
    }

    .close-overlay-btn:hover {
      transform: scale(1.1);
      background: #f0f0f0;
    }

    /* Total price display */
    .total-price-display {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 16px 20px;
      border-radius: 10px;
      margin-top: 16px;
      display: none;
    }

    .total-price-display.show {
      display: block;
    }

    .total-price-label {
      font-size: 13px;
      opacity: 0.9;
      margin-bottom: 4px;
    }

    .total-price-value {
      font-size: 24px;
      font-weight: 700;
    }

    .price-breakdown {
      font-size: 12px;
      opacity: 0.8;
      margin-top: 8px;
    }

    /* Time slot error */
    .time-slot-error {
      background: #fff3cd;
      color: #856404;
      padding: 12px;
      border-radius: 8px;
      margin-top: 10px;
      display: none;
      align-items: center;
      gap: 10px;
      border: 1px solid #ffc107;
    }

    /* Estimated time box */
    .estimated-time-box {
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
      border: 1px solid #64b5f6;
      border-radius: 8px;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .estimated-time-box .est-icon {
      font-size: 24px;
    }

    .estimated-time-box .est-label {
      color: #1565c0;
      font-size: 13px;
    }

    .estimated-time-box .est-value {
      color: #0d47a1;
      font-weight: 700;
      font-size: 18px;
      margin-left: 5px;
    }

    .time-slot-error.error {
      background: #f8d7da;
      color: #721c24;
      border-color: #f5c6cb;
    }

    .time-slot-error.success {
      background: #d4edda;
      color: #155724;
      border-color: #c3e6cb;
    }

    /* Lookup results */
    .lookup-results {
      margin-top: 20px;
    }

    .appointment-result-card {
      background: #f8fbff;
      border: 1px solid #e0e8f0;
      border-radius: 10px;
      padding: 16px;
      margin-bottom: 12px;
    }

    .appointment-result-card h4 {
      color: #1e5ba8;
      margin-bottom: 10px;
      font-size: 16px;
    }

    .appointment-result-card p {
      font-size: 13px;
      color: #555;
      margin-bottom: 6px;
    }

    .appointment-status {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .appointment-status.pending {
      background: #fff3cd;
      color: #856404;
    }

    .appointment-status.confirmed {
      background: #d4edda;
      color: #155724;
    }

    .appointment-status.cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .appointment-status.completed {
      background: #cce5ff;
      color: #004085;
    }

    /* Development notice */
    .dev-notice {
      background: #fff3cd;
      color: #856404;
      padding: 12px 16px;
      border-radius: 8px;
      margin-top: 16px;
      text-align: center;
      font-size: 14px;
      display: none;
    }

    .dev-notice.show {
      display: block;
    }

    /* User exists notice */
    .user-exists-notice {
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
      border: 1px solid #64b5f6;
      color: #1565c0;
      padding: 10px 14px;
      border-radius: 8px;
      margin-top: 8px;
      font-size: 13px;
      display: none;
      align-items: center;
      gap: 10px;
      animation: fadeInNotice 0.3s ease;
    }

    .user-exists-notice.show {
      display: flex;
    }

    @keyframes fadeInNotice {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .user-exists-notice .notice-content {
      flex: 1;
    }

    .user-exists-notice .notice-actions {
      display: flex;
      gap: 8px;
    }

    .user-exists-notice .btn-login {
      background: #1e5ba8;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 12px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.2s;
    }

    .user-exists-notice .btn-login:hover {
      background: #1a4a8a;
      transform: scale(1.02);
    }

    .user-exists-notice .btn-dismiss {
      background: transparent;
      color: #666;
      border: 1px solid #ccc;
      padding: 6px 12px;
      border-radius: 4px;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .user-exists-notice .btn-dismiss:hover {
      background: #f5f5f5;
    }

    /* Login promo box */
    .login-promo-box {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px;
      border-radius: 12px;
      margin-top: 24px;
      text-align: center;
    }

    .login-promo-box h3 {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .login-promo-box p {
      font-size: 13px;
      opacity: 0.9;
      margin-bottom: 14px;
    }

    .login-promo-box ul {
      text-align: left;
      font-size: 12px;
      margin-bottom: 16px;
      padding-left: 20px;
      opacity: 0.9;
    }

    .login-promo-box ul li {
      margin-bottom: 6px;
    }

    .login-promo-box .btn-promo-login {
      background: white;
      color: #667eea;
      border: none;
      padding: 10px 24px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
    }

    .login-promo-box .btn-promo-login:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Verification Modal */
    .verification-modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 10001;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .verification-modal.active {
      display: flex;
    }

    .verification-box {
      background: white;
      border-radius: 16px;
      padding: 32px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .verification-box h3 {
      color: #1e5ba8;
      font-size: 20px;
      margin-bottom: 12px;
    }

    .verification-box p {
      color: #666;
      font-size: 14px;
      margin-bottom: 20px;
    }

    .verification-input {
      width: 100%;
      padding: 14px;
      font-size: 24px;
      text-align: center;
      letter-spacing: 8px;
      border: 2px solid #ddd;
      border-radius: 8px;
      margin-bottom: 16px;
      transition: all 0.3s;
    }

    .verification-input:focus {
      outline: none;
      border-color: #1e5ba8;
    }

    .verification-input.success {
      border-color: #28a745;
      background: #d4edda;
    }

    .verification-input.error {
      border-color: #dc3545;
      background: #f8d7da;
    }

    .verification-status {
      font-size: 13px;
      margin-bottom: 16px;
      min-height: 20px;
    }

    .verification-status.success {
      color: #28a745;
    }

    .verification-status.error {
      color: #dc3545;
    }

    .verification-actions {
      display: flex;
      gap: 12px;
    }

    .verification-actions button {
      flex: 1;
      padding: 12px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }

    .btn-verify {
      background: #1e5ba8;
      color: white;
      border: none;
    }

    .btn-verify:hover {
      background: #1a4a8a;
    }

    .btn-verify:disabled {
      background: #ccc;
      cursor: not-allowed;
    }

    .btn-cancel-verify {
      background: white;
      color: #666;
      border: 1px solid #ddd;
    }

    .btn-cancel-verify:hover {
      background: #f5f5f5;
    }

    .resend-code {
      margin-top: 16px;
      font-size: 13px;
      color: #666;
    }

    .resend-code a {
      color: #1e5ba8;
      cursor: pointer;
      text-decoration: underline;
    }

    /* Clinic and Address display */
    .location-info {
      background: #f8fbff;
      border: 1px solid #e0e8f0;
      border-radius: 8px;
      padding: 12px 14px;
      margin-top: 8px;
    }

    .location-info-item {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 8px;
      font-size: 13px;
    }

    .location-info-item:last-child {
      margin-bottom: 0;
    }

    .location-info-item .icon {
      font-size: 16px;
      flex-shrink: 0;
    }

    .location-info-item .label {
      color: #666;
      min-width: 80px;
    }

    .location-info-item .value {
      color: #333;
      font-weight: 500;
    }

    /* Lookup hints */
    .lookup-hint {
      background: #fff3cd;
      border: 1px solid #ffc107;
      border-radius: 8px;
      padding: 12px 16px;
      margin-top: 12px;
      font-size: 13px;
      color: #856404;
      display: none;
    }

    .lookup-hint.show {
      display: block;
    }

    .lookup-hint a {
      color: #1e5ba8;
      font-weight: 600;
      text-decoration: underline;
    }

    .password-input-section {
      margin-top: 12px;
      display: none;
    }

    .password-input-section.show {
      display: block;
    }

    .password-input-section input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      margin-bottom: 8px;
    }

    .support-link {
      font-size: 12px;
      color: #666;
      margin-top: 8px;
    }

    .support-link a {
      color: #1e5ba8;
    }

    /* Button states */
    .submit-btn.confirm-btn {
      background: #f5a623;
    }

    .submit-btn.confirm-btn:hover {
      background: #e09620;
    }

    .submit-btn.verified {
      background: #28a745;
    }

    .submit-btn.verified:hover {
      background: #218838;
    }

    @media (max-width: 968px) {
      .wrapper {
        grid-template-columns: 1fr;
        min-height: auto;
      }

      .form-section {
        padding: 30px;
      }

      .image-section {
        min-height: 400px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .time-section {
        grid-template-columns: 1fr;
      }

      .doctor-card {
        width: 100%;
        max-width: 300px;
      }
    }
  </style>
</head>

<body>
  <script src="{{ asset('frontend/js/auth.js') }}"></script>
  @include('partials.header')

  <div class="wrapper">
    <div class="form-section">
      <h1 class="form-title">Thông tin bệnh nhân</h1>

      <div class="form-content">
        <form id="bookingForm">
          <!-- Name and Birthday -->
          <div class="form-row">
            <div class="form-group">
              <label>Họ và Tên*</label>
              <input type="text" id="patientName" placeholder="Nhập Họ và Tên" required />
            </div>
            <div class="form-group">
              <label>Ngày sinh</label>
              <input type="date" id="patientBirthday" placeholder="dd/mm/yyyy" />
            </div>
          </div>

          <!-- Phone and Email -->
          <div class="form-row">
            <div class="form-group">
              <label>Số điện thoại*</label>
              <input type="tel" id="patientPhone" placeholder="Nhập số điện thoại" required />
              <div class="user-exists-notice" id="phoneExistsNotice">
                <span>📱</span>
                <div class="notice-content">
                  <span id="phoneExistsMsg">Số điện thoại đã có trong hệ thống.</span>
                </div>
                <div class="notice-actions">
                  <button type="button" class="btn-login" onclick="goToLogin()">Đăng nhập</button>
                  <button type="button" class="btn-dismiss" onclick="dismissNotice('phoneExistsNotice')">Bỏ qua</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Email*</label>
              <input type="email" id="patientEmail" placeholder="Nhập email" required />
              <div class="user-exists-notice" id="emailExistsNotice">
                <span>📧</span>
                <div class="notice-content">
                  <span id="emailExistsMsg">Email đã được đăng ký tài khoản.</span>
                </div>
                <div class="notice-actions">
                  <button type="button" class="btn-login" onclick="goToLogin()">Đăng nhập</button>
                  <button type="button" class="btn-dismiss" onclick="dismissNotice('emailExistsNotice')">Bỏ qua</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Specialization and Service -->
          <div class="form-row">
            <div class="form-group">
              <label>Chuyên khoa*</label>
              <select id="serviceSelect" required>
                <option value="">Chọn chuyên khoa</option>
              </select>
              <input type="hidden" id="selectedSpecializationId" />
            </div>
            <div class="form-group">
              <label>Dịch vụ khám*</label>
              <select id="treatmentServiceSelect" required disabled>
                <option value="">Chọn chuyên khoa trước</option>
              </select>
              <input type="hidden" id="selectedServiceId" />
              <input type="hidden" id="selectedServicePrice" />
              <input type="hidden" id="selectedServiceDuration" />
            </div>
          </div>

          <div class="form-group">
            <label>Bác sĩ*</label>
            <input type="text" id="doctorDisplayName" class="doctor-selector-input" placeholder="Click để chọn bác sĩ" readonly required />
            <input type="hidden" id="selectedDoctorId" />
          </div>

          <!-- Clinic and Address Info -->
          <div class="form-group form-row-full">
            <label>Thông tin địa điểm khám*</label>
            <div class="location-info" id="locationInfo" style="display:none;">
              <div class="location-info-item">
                <span class="icon">🏥</span>
                <span class="label">Phòng khám:</span>
                <span class="value" id="clinicName">-</span>
              </div>
              <div class="location-info-item">
                <span class="icon">📍</span>
                <span class="label">Địa chỉ:</span>
                <span class="value" id="clinicAddress">-</span>
              </div>
            </div>
            <input type="text" id="doctorAddressPlaceholder" placeholder="Chọn bác sĩ để xem địa điểm khám" readonly style="display:block;" />
          </div>

          <!-- Appointment Date and Time -->
          <div class="form-row">
            <div class="form-group">
              <label>Ngày khám*</label>
              <input type="date" id="appointmentDate" placeholder="dd/mm/yyyy" required />
            </div>
            <div class="form-group">
              <label>Giờ bắt đầu khám*</label>
              <select id="appointmentTime" required>
                <option value="">Chọn giờ bắt đầu</option>
                <option value="07:00">07:00</option>
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
              </select>
            </div>
          </div>

          <!-- Estimated End Time -->
          <div class="form-group" id="estimatedTimeGroup" style="display:none;">
            <div class="estimated-time-box">
              <span class="est-icon">⏱️</span>
              <div>
                <span class="est-label">Thời gian dự kiến kết thúc:</span>
                <span class="est-value" id="estimatedEndTime">--:--</span>
              </div>
            </div>
          </div>

          <!-- Time slot availability message -->
          <div class="time-slot-error" id="timeSlotError">
            <span id="timeSlotErrorIcon">⚠️</span>
            <span id="timeSlotErrorMsg"></span>
          </div>

          <!-- Reason -->
          <div class="form-group">
            <label>Lý do khám</label>
            <textarea id="bookingNotes" placeholder="Nhập lý do khám"></textarea>
          </div>

          <!-- Payment Method -->
          <div class="form-group">
            <label>Phương thức thanh toán*</label>
            <div class="radio-group">
              <div class="radio-item">
                <input type="radio" id="payment-cash" name="payment" value="cash" />
                <label for="payment-cash">Tiền mặt</label>
              </div>
              <div class="radio-item">
                <input type="radio" id="payment-bank" name="payment" value="bank" />
                <label for="payment-bank">Chuyển khoản</label>
              </div>
            </div>
          </div>

          <!-- Development Notice for bank transfer -->
          <div class="dev-notice" id="bankDevNotice">
            🚧 Chức năng chuyển khoản đang được phát triển. Vui lòng chọn thanh toán tiền mặt.
          </div>

          <!-- Checkboxes -->
          <div class="form-group">
            <div class="checkbox-group">
              <div class="checkbox-item-simple">
                <input type="checkbox" id="foreign" name="foreign" />
                <label for="foreign">Đặt hẹn cho người nước ngoài (phụ thu 25%)</label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="checkbox-group">
              <div class="checkbox-item-simple">
                <input type="checkbox" id="relatives" name="relatives" />
                <label for="relatives">Đặt hẹn cho người thân</label>
              </div>
            </div>
          </div>

          <!-- Total Price Display -->
          <div class="total-price-display" id="totalPriceDisplay">
            <div class="total-price-label">Tổng chi phí dự kiến</div>
            <div class="total-price-value" id="totalPriceValue">0 VNĐ</div>
            <div class="price-breakdown" id="priceBreakdown"></div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="submit-btn" id="submitBtn" disabled>Đặt lịch ngay</button>
        </form>
      </div>
      <!-- Link tra cứu lịch hẹn -->
      <div style="margin-top:24px; text-align:center; padding:20px; background:#f8fbff; border-radius:12px; border:1px solid #e0e8f0;">
        <p style="color:#666; margin-bottom:12px;">Bạn đã có lịch hẹn?</p>
        <a href="/tra-cuu-lich-hen" style="color:#1e5ba8; font-weight:600; font-size:16px; text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
          🔍 Tra cứu lịch đã đặt tại đây →
        </a>
      </div>
    </div>

    <div class="image-section"></div>
  </div>

  <!-- Doctor Selection Overlay -->
  <div class="doctor-overlay" id="doctorOverlay">
    <button class="close-overlay-btn" onclick="closeDoctorOverlay()">✕</button>
    <div class="doctor-cards-container" id="doctorCardsContainer">
      <!-- Doctor cards will be rendered here -->
    </div>
  </div>

  <!-- Verification Modal -->
  <div class="verification-modal" id="verificationModal">
    <div class="verification-box">
      <h3>📧 Xác nhận Email</h3>
      <p>Chúng tôi đã gửi mã xác nhận 6 số đến email <strong id="verifyEmailDisplay"></strong></p>
      <input type="text" class="verification-input" id="verificationCode" maxlength="6" placeholder="______" />
      <div class="verification-status" id="verificationStatus"></div>
      <div class="verification-actions">
        <button class="btn-cancel-verify" onclick="closeVerificationModal()">Hủy</button>
        <button class="btn-verify" id="btnVerify" onclick="verifyCode()">
          <span class="btn-text">Xác nhận</span>
          <span class="btn-spinner" style="display:none;">
            <i class="fas fa-spinner fa-spin"></i> Đang xử lý...
          </span>
        </button>
      </div>
      <div class="resend-code">
        Không nhận được mã? <a onclick="resendVerificationCode()">Gửi lại</a>
      </div>
    </div>
  </div>

  @include('partials.footer')

  <script>
    const API_BASE = '/api';
    const SYSTEM_EMAIL = 'uytinso1vn@gmail.com';
    let allDoctors = [];
    let selectedDoctor = null;
    let isSlotAvailable = true;
    let isEmailVerified = false;
    let pendingBookingData = null;
    let allServices = [];
    const BASE_FEE = 200000;
    let currentVATPercent = 0;
    let existingUserData = null;


    // Initialize page
    document.addEventListener('DOMContentLoaded', async function() {
      await loadServices();
      await loadSpecializations();
      setupEventListeners();
      setMinDate();

      // Auto-fill from localStorage if logged in
      autoFillFromLogin();

      // Check if parameters exist in URL
      const urlParams = new URLSearchParams(window.location.search);
      const doctorId = urlParams.get('doctor');
      const specializationId = urlParams.get('specialization');
      const serviceId = urlParams.get('service');
      const servicesParam = urlParams.get('services'); // Multiple services separated by comma
      const appointmentDate = urlParams.get('date');
      const appointmentTime = urlParams.get('time');

      if (specializationId) {
        // Pre-select specialization
        document.getElementById('serviceSelect').value = specializationId;
        document.getElementById('selectedSpecializationId').value = specializationId;
        await loadServicesBySpecialization(specializationId);
        await loadDoctorsBySpecialization(specializationId);

        // Handle multiple services from URL (services=1,2,3)
        if (servicesParam) {
          const serviceIds = servicesParam.split(',').map(id => id.trim());
          if (serviceIds.length > 0) {
            // Select the first service
            const firstServiceId = serviceIds[0];
            document.getElementById('treatmentServiceSelect').value = firstServiceId;
            document.getElementById('selectedServiceId').value = firstServiceId;
            onTreatmentServiceChange();

            // Show info if multiple services selected
            if (serviceIds.length > 1) {
              // Store all selected services for later use
              window.preSelectedServices = serviceIds;
              showNotification(`Đã chọn ${serviceIds.length} dịch vụ từ trang chuyên khoa. Vui lòng đặt lịch cho từng dịch vụ.`, 'info');
            }
          }
        }
        // If single service param exists, pre-select it after services are loaded
        else if (serviceId) {
          document.getElementById('treatmentServiceSelect').value = serviceId;
          document.getElementById('selectedServiceId').value = serviceId;
          // Trigger change to update price info
          onTreatmentServiceChange();
        }
        
        // Auto-select the doctor with highest rating
        if (!doctorId && allDoctors.length > 0) {
          // Sort by rating_avg descending
          const sortedDoctors = [...allDoctors].sort((a, b) => {
            const ratingA = parseFloat(a.rating_avg) || 0;
            const ratingB = parseFloat(b.rating_avg) || 0;
            return ratingB - ratingA;
          });
          
          // Auto-select the best rated doctor
          const bestDoctor = sortedDoctors[0];
          if (bestDoctor) {
            selectDoctor(bestDoctor);
            showNotification(`Đã tự động chọn bác sĩ có đánh giá cao nhất: ${bestDoctor.full_name}`, 'success');
          }
        }
      }

      if (doctorId) {
        await loadDoctorFromUrl(doctorId);
      }

      // Set appointment date from URL if provided
      if (appointmentDate) {
        document.getElementById('appointmentDate').value = appointmentDate;
      }

      // Set appointment time from URL if provided
      if (appointmentTime) {
        document.getElementById('appointmentTime').value = appointmentTime;
        updateEstimatedEndTime();
      }
    });

    // Show notification toast
    function showNotification(message, type = 'info') {
      // Remove existing notifications
      const existing = document.querySelector('.booking-notification');
      if (existing) existing.remove();

      const notification = document.createElement('div');
      notification.className = `booking-notification ${type}`;
      notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
      `;
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
        color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 400px;
        animation: slideIn 0.3s ease;
      `;
      document.body.appendChild(notification);

      setTimeout(() => notification.remove(), 5000);
    }

    // Auto-fill user data from localStorage
    function autoFillFromLogin() {
      const token = localStorage.getItem('access_token');
      // Check multiple possible localStorage keys
      const userDataStr = localStorage.getItem('user_profile') || localStorage.getItem('user_data') || localStorage.getItem('userData');

      if (token && userDataStr) {
        try {
          const userData = JSON.parse(userDataStr);
          if (userData.full_name) {
            document.getElementById('patientName').value = userData.full_name;
          }
          if (userData.phone) {
            document.getElementById('patientPhone').value = userData.phone;
          }
          if (userData.email) {
            document.getElementById('patientEmail').value = userData.email;
          }
          // Check various possible birthday field names
          const birthday = userData.birthday || userData.birthdate || userData.date_of_birth || userData.dob;
          if (birthday) {
            // Format date to YYYY-MM-DD if needed
            let formattedDate = birthday;
            if (birthday.includes('/')) {
              // Convert DD/MM/YYYY to YYYY-MM-DD
              const parts = birthday.split('/');
              if (parts.length === 3) {
                formattedDate = `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
              }
            }
            document.getElementById('patientBirthday').value = formattedDate;
          }
        } catch (e) {
          console.error('Error auto-filling user data:', e);
        }
      }
    }

    // Load treatment services (all services for caching)
    async function loadServices() {
      try {
        const response = await fetch(`${API_BASE}/public/services`);
        const result = await response.json();
        allServices = result.data || [];
      } catch (error) {
        console.error('Error loading services:', error);
      }
    }

    // Load services filtered by specialization
    function loadServicesBySpecialization(specId) {
      const serviceSelect = document.getElementById('treatmentServiceSelect');

      if (!specId) {
        serviceSelect.innerHTML = '<option value="">Chọn chuyên khoa trước</option>';
        serviceSelect.disabled = true;
        return;
      }

      // Filter services by specialization_id
      const filteredServices = allServices.filter(s => s.specialization_id == specId);

      serviceSelect.innerHTML = '<option value="">Chọn dịch vụ</option>';
      serviceSelect.disabled = false;

      if (filteredServices.length === 0) {
        serviceSelect.innerHTML = '<option value="">Không có dịch vụ nào</option>';
        return;
      }

      filteredServices.forEach(service => {
        const option = document.createElement('option');
        option.value = service.id;
        option.textContent = `${service.name} - ${service.formatted_price}`;
        option.dataset.price = service.price;
        option.dataset.duration = service.duration_minutes || 60;
        option.dataset.specId = service.specialization_id;
        serviceSelect.appendChild(option);
      });
    }

    // Load doctor from URL parameter and auto-fill form
    async function loadDoctorFromUrl(doctorId) {
      try {
        const response = await fetch(`${API_BASE}/public/doctors/${doctorId}`);
        if (!response.ok) {
          console.error('Doctor not found');
          return;
        }

        const doctor = await response.json();
        if (!doctor || !doctor.id) {
          console.error('Invalid doctor data');
          return;
        }

        // Auto select specialization
        if (doctor.specialization_id) {
          const serviceSelect = document.getElementById('serviceSelect');
          serviceSelect.value = doctor.specialization_id;
          document.getElementById('selectedSpecializationId').value = doctor.specialization_id;

          // Load doctors for this specialization
          await loadDoctorsBySpecialization(doctor.specialization_id);
        }

        // Auto select this doctor
        selectDoctor(doctor);

        // Clean URL (remove query params)
        const cleanUrl = window.location.pathname;
        window.history.replaceState({}, document.title, cleanUrl);

      } catch (error) {
        console.error('Error loading doctor from URL:', error);
      }
    }

    // Set minimum date to tomorrow
    function setMinDate() {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      const minDate = tomorrow.toISOString().split('T')[0];
      document.getElementById('appointmentDate').min = minDate;
    }

    // Setup event listeners
    function setupEventListeners() {
      document.getElementById('treatmentServiceSelect').addEventListener('change', onTreatmentServiceChange);
      document.getElementById('serviceSelect').addEventListener('change', onServiceChange);
      document.getElementById('doctorDisplayName').addEventListener('click', openDoctorOverlay);
      document.getElementById('appointmentDate').addEventListener('change', onDateChange);
      document.getElementById('appointmentTime').addEventListener('change', () => {
        checkTimeSlot();
        updateEstimatedEndTime();
      });
      document.querySelectorAll('input[name="payment"]').forEach(radio => {
        radio.addEventListener('change', onPaymentMethodChange);
      });
      document.getElementById('foreign').addEventListener('change', calculateTotalPrice);
      document.getElementById('bookingForm').addEventListener('submit', handleFormSubmit);
      document.getElementById('patientPhone').addEventListener('blur', () => checkUserExists('phone'));
      document.getElementById('patientEmail').addEventListener('blur', () => checkUserExists('email'));

      // Verification code input
      document.getElementById('verificationCode').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 6) {
          verifyCode();
        }
      });
    }

    // When treatment service changes, update price display and duration
    function onTreatmentServiceChange() {
      const serviceSelect = document.getElementById('treatmentServiceSelect');
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];

      if (selectedOption && selectedOption.value) {
        document.getElementById('selectedServiceId').value = selectedOption.value;
        document.getElementById('selectedServicePrice').value = selectedOption.dataset.price || 0;
        document.getElementById('selectedServiceDuration').value = selectedOption.dataset.duration || 60;
        calculateTotalPrice();
        updateEstimatedEndTime();
      } else {
        document.getElementById('selectedServiceId').value = '';
        document.getElementById('selectedServicePrice').value = '';
        document.getElementById('selectedServiceDuration').value = '';
        document.getElementById('estimatedTimeGroup').style.display = 'none';
      }
    }

    // Update estimated end time based on start time and service duration
    function updateEstimatedEndTime() {
      const startTime = document.getElementById('appointmentTime').value;
      const durationMinutes = parseInt(document.getElementById('selectedServiceDuration').value) || 60;
      const estimatedGroup = document.getElementById('estimatedTimeGroup');
      const estimatedValue = document.getElementById('estimatedEndTime');

      if (!startTime) {
        estimatedGroup.style.display = 'none';
        return;
      }

      // Parse start time
      const [hours, minutes] = startTime.split(':').map(Number);
      const startDate = new Date();
      startDate.setHours(hours, minutes || 0, 0, 0);

      // Add duration
      const endDate = new Date(startDate.getTime() + durationMinutes * 60000);
      const endHours = endDate.getHours().toString().padStart(2, '0');
      const endMinutes = endDate.getMinutes().toString().padStart(2, '0');

      estimatedValue.textContent = `${endHours}:${endMinutes}`;
      estimatedGroup.style.display = 'block';
    }

    // When date changes, load booked slots and update dropdown
    async function onDateChange() {
      const doctorId = document.getElementById('selectedDoctorId').value;
      const appointmentDate = document.getElementById('appointmentDate').value;
      const timeSelect = document.getElementById('appointmentTime');
      const errorDiv = document.getElementById('timeSlotError');

      // Reset time selection
      timeSelect.value = '';
      errorDiv.style.display = 'none';

      if (!doctorId || !appointmentDate) {
        // Reset all options to enabled
        Array.from(timeSelect.options).forEach(opt => {
          opt.disabled = false;
          opt.textContent = opt.textContent.replace(' (Đã có người đặt)', '');
        });
        return;
      }

      // Load booked slots
      try {
        const response = await fetch(`${API_BASE}/appointments/booked-slots?doctor_id=${doctorId}&appointment_date=${appointmentDate}`);
        const data = await response.json();
        const bookedSlots = data.booked_slots || [];

        // Update dropdown options
        Array.from(timeSelect.options).forEach(opt => {
          if (opt.value === '') return; // Skip placeholder option

          const isBooked = bookedSlots.includes(opt.value);
          opt.disabled = isBooked;

          // Update text to show booked status
          const baseText = opt.value.replace('-', ' - ');
          if (isBooked) {
            opt.textContent = baseText + ' (Đã có người đặt)';
            opt.style.color = '#999';
          } else {
            opt.textContent = baseText;
            opt.style.color = '';
          }
        });

        // Show info if all slots are booked
        const availableSlots = Array.from(timeSelect.options).filter(opt => opt.value && !opt.disabled);
        if (availableSlots.length === 0) {
          errorDiv.innerHTML = '<span>⚠️</span><span>Tất cả khung giờ trong ngày này đã có người đặt. Vui lòng chọn ngày khác.</span>';
          errorDiv.className = 'time-slot-error error';
          errorDiv.style.display = 'flex';
        }
      } catch (error) {
        console.error('Error loading booked slots:', error);
      }
    }

    // Check if user exists and auto-fill data
    async function checkUserExists(type) {
      const phoneInput = document.getElementById('patientPhone');
      const emailInput = document.getElementById('patientEmail');
      const phone = phoneInput.value.trim();
      const email = emailInput.value.trim();

      let queryParams = [];
      if (type === 'phone' && phone.length >= 9) {
        queryParams.push(`phone=${encodeURIComponent(phone)}`);
      }
      if (type === 'email' && email.includes('@')) {
        queryParams.push(`email=${encodeURIComponent(email)}`);
      }

      if (queryParams.length === 0) return;

      try {
        const response = await fetch(`${API_BASE}/public/check-user?${queryParams.join('&')}`);
        const data = await response.json();

        if (type === 'phone' && data.phone_exists) {
          document.getElementById('phoneExistsMsg').textContent = data.phone_message || 'Số điện thoại đã có trong hệ thống. Bạn có muốn đăng nhập?';
          document.getElementById('phoneExistsNotice').classList.add('show');
        }

        if (type === 'email' && data.email_exists) {
          document.getElementById('emailExistsMsg').textContent = data.email_message || 'Email đã được đăng ký tài khoản. Đăng nhập để trải nghiệm tốt hơn!';
          document.getElementById('emailExistsNotice').classList.add('show');

          // Auto-fill user data if exists
          if (data.user_data) {
            existingUserData = data.user_data;
            document.getElementById('patientName').value = data.user_data.full_name || '';
            if (data.user_data.birthday) {
              document.getElementById('patientBirthday').value = data.user_data.birthday;
            }
          }
        }
      } catch (error) {
        console.error('Error checking user:', error);
      }
    }

    function dismissNotice(noticeId) {
      document.getElementById(noticeId).classList.remove('show');
    }

    function goToLogin() {
      window.location.href = '/dang-nhap';
    }

    // Load specializations as services
    async function loadSpecializations() {
      try {
        const response = await fetch(`${API_BASE}/public/specializations`);
        const result = await response.json();
        const specializations = result.data || result || [];

        const serviceSelect = document.getElementById('serviceSelect');
        serviceSelect.innerHTML = '<option value="">Chọn chuyên khoa</option>';

        if (Array.isArray(specializations)) {
          specializations.forEach(spec => {
            const option = document.createElement('option');
            option.value = spec.id;
            option.textContent = spec.name;
            option.dataset.specId = spec.id;
            serviceSelect.appendChild(option);
          });
        }
        } catch (error) {
        console.error('Error loading specializations:', error);
      }
    }

    async function onServiceChange() {
      const serviceSelect = document.getElementById('serviceSelect');
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      const specId = selectedOption.dataset.specId || serviceSelect.value;

      document.getElementById('selectedSpecializationId').value = specId;
      document.getElementById('doctorDisplayName').value = '';
      document.getElementById('selectedDoctorId').value = '';
      document.getElementById('locationInfo').style.display = 'none';
      document.getElementById('doctorAddressPlaceholder').style.display = 'block';
      selectedDoctor = null;

      // Reset service selection when specialization changes
      document.getElementById('treatmentServiceSelect').value = '';
      document.getElementById('selectedServiceId').value = '';
      document.getElementById('selectedServicePrice').value = '';
      document.getElementById('selectedServiceDuration').value = '';
      document.getElementById('estimatedTimeGroup').style.display = 'none';
      document.getElementById('totalPriceDisplay').classList.remove('show');

      if (specId) {
        // Load services for this specialization
        loadServicesBySpecialization(specId);
        // Load doctors for this specialization
        await loadDoctorsBySpecialization(specId);
      } else {
        allDoctors = [];
        loadServicesBySpecialization(null);
      }
    }

    async function loadDoctorsBySpecialization(specId) {
      try {
        const response = await fetch(`${API_BASE}/public/doctors?specialization_id=${specId}&per_page=50`);
        const result = await response.json();
        allDoctors = result.data || [];
      } catch (error) {
        console.error('Error loading doctors:', error);
        allDoctors = [];
      }
    }

    function openDoctorOverlay() {
      const specId = document.getElementById('selectedSpecializationId').value;
      if (!specId) {
        alert('Vui lòng chọn chuyên khoa trước!');
        return;
      }
      if (allDoctors.length === 0) {
        alert('Không có bác sĩ nào cho chuyên khoa này!');
        return;
      }
      renderDoctorCards();
      document.getElementById('doctorOverlay').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeDoctorOverlay() {
      document.getElementById('doctorOverlay').classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    function renderDoctorCards() {
      const container = document.getElementById('doctorCardsContainer');
      container.innerHTML = '';

      allDoctors.forEach((doctor, index) => {
        const card = document.createElement('div');
        card.className = 'doctor-card';
        card.style.animationDelay = `${index * 0.15}s`;

        const avatarUrl = doctor.avatar_url || '/frontend/img/Screenshot 2025-10-17 201418.png';
        const degree = doctor.degree || '';
        const displayName = degree ? `${degree} ${doctor.full_name}` : doctor.full_name;
        const specialization = doctor.specialization?.name || 'Đa khoa';
        const experience = doctor.experience || 0;
        const rating = doctor.rating_avg ? parseFloat(doctor.rating_avg).toFixed(1) : '0.0';

        card.innerHTML = `
          <img class="doctor-card-avatar" src="${avatarUrl}" alt="${doctor.full_name}" 
               onerror="this.src='/frontend/img/Screenshot 2025-10-17 201418.png'">
          <div class="doctor-card-info">
            <div class="doctor-card-name">${displayName}</div>
            <div class="doctor-card-spec"><span>🩺</span> ${specialization}</div>
            <div class="doctor-card-details">
              <div><span>📅</span> ${experience} năm kinh nghiệm</div>
              <div><span>🎓</span> ${degree || 'Bác sĩ'}</div>
              <div class="doctor-card-rating"><span>⭐</span> ${rating}/5</div>
            </div>
          </div>
        `;
        card.addEventListener('click', () => selectDoctor(doctor));
        container.appendChild(card);
      });
    }

    function selectDoctor(doctor) {
      selectedDoctor = doctor;
      const degree = doctor.degree || '';
      const displayName = degree ? `${degree} ${doctor.full_name}` : doctor.full_name;

      document.getElementById('doctorDisplayName').value = displayName;
      document.getElementById('selectedDoctorId').value = doctor.id;

      // Update location info
      document.getElementById('clinicName').textContent = doctor.clinic?.name || 'Chưa cập nhật';
      document.getElementById('clinicAddress').textContent = doctor.clinic?.address || 'Chưa cập nhật';
      document.getElementById('locationInfo').style.display = 'block';
      document.getElementById('doctorAddressPlaceholder').style.display = 'none';

      setTimeout(() => closeDoctorOverlay(), 300);

      // Reload booked slots if date is already selected
      onDateChange();
    }

    async function checkTimeSlot() {
      const doctorId = document.getElementById('selectedDoctorId').value;
      const appointmentDate = document.getElementById('appointmentDate').value;
      const timeSlot = document.getElementById('appointmentTime').value;
      const patientEmail = document.getElementById('patientEmail').value.trim();
      const patientPhone = document.getElementById('patientPhone').value.trim();
      const errorDiv = document.getElementById('timeSlotError');
      const submitBtn = document.getElementById('submitBtn');

      errorDiv.style.display = 'none';
      isSlotAvailable = true;

      if (!doctorId || !appointmentDate || !timeSlot) return;

      errorDiv.innerHTML = '<span>⏳</span><span>Đang kiểm tra lịch...</span>';
      errorDiv.className = 'time-slot-error';
      errorDiv.style.display = 'flex';

      try {
        // Kiểm tra slot của bác sĩ
        const response = await fetch(`${API_BASE}/appointments/check-slot?doctor_id=${doctorId}&appointment_date=${appointmentDate}&time_slot=${encodeURIComponent(timeSlot)}`);
        const data = await response.json();

        if (data.available === false) {
          isSlotAvailable = false;
          errorDiv.innerHTML = `<span>❌</span><span><strong>Bác sĩ ${selectedDoctor?.full_name || ''}</strong> đã có lịch đặt trước vào khung giờ này. Vui lòng chọn thời gian khác.</span>`;
          errorDiv.className = 'time-slot-error error';
          errorDiv.style.display = 'flex';
          submitBtn.disabled = true;
          return;
        }

        // Kiểm tra lịch hẹn của bệnh nhân (nếu có email hoặc phone)
        if (patientEmail || patientPhone) {
          const patientCheckUrl = `${API_BASE}/appointments/check-patient?appointment_date=${appointmentDate}&time_slot=${encodeURIComponent(timeSlot)}` + 
            (patientEmail ? `&patient_email=${encodeURIComponent(patientEmail)}` : '') +
            (patientPhone ? `&patient_phone=${encodeURIComponent(patientPhone)}` : '');
          
          const patientResponse = await fetch(patientCheckUrl);
          const patientData = await patientResponse.json();

          if (patientData.has_appointment) {
            isSlotAvailable = false;
            errorDiv.innerHTML = `<span>⚠️</span><span>Bạn đã có lịch hẹn với <a href="javascript:void(0)" onclick="goToMyAppointment(${patientData.appointment_id})" style="color: #2196F3; font-weight: bold; cursor: pointer;">${patientData.doctor_name}</a> vào thời gian này!</span>`;
            errorDiv.className = 'time-slot-error error';
            errorDiv.style.display = 'flex';
            submitBtn.disabled = true;
            return;
          }
        }

        // Slot còn trống
        isSlotAvailable = true;
        errorDiv.innerHTML = '<span>✅</span><span>Khung giờ này còn trống!</span>';
        errorDiv.className = 'time-slot-error success';
        errorDiv.style.display = 'flex';

        const paymentMethod = document.querySelector('input[name="payment"]:checked');
        if (paymentMethod && paymentMethod.value === 'cash') {
          submitBtn.disabled = false;
        }
      } catch (error) {
        console.error('Error checking slot:', error);
        errorDiv.style.display = 'none';
        isSlotAvailable = true;
      }
    }

    // Chuyển đến trang lịch hẹn cá nhân và mở chi tiết
    function goToMyAppointment(appointmentId) {
      // Lưu ID lịch hẹn vào localStorage để mở chi tiết sau khi chuyển trang
      localStorage.setItem('openAppointmentId', appointmentId);
      // Chuyển đến trang profile với tab lịch hẹn
      window.location.href = '/ho-so?section=appointments&open=' + appointmentId;
    }

    function onPaymentMethodChange(e) {
      const submitBtn = document.getElementById('submitBtn');
      const bankNotice = document.getElementById('bankDevNotice');
      const priceDisplay = document.getElementById('totalPriceDisplay');

      if (e.target.value === 'cash') {
        bankNotice.classList.remove('show');
        calculateTotalPrice();
        priceDisplay.classList.add('show');
        if (isSlotAvailable) submitBtn.disabled = false;
      } else if (e.target.value === 'bank') {
        bankNotice.classList.add('show');
        priceDisplay.classList.remove('show');
        submitBtn.disabled = true;
      }
    }

    // Generate random VAT amount between 100k and 200k
    function getRandomVAT() {
      // Random from 100000 to 200000 (step 10000)
      const steps = Math.floor(Math.random() * 11); // 0-10
      return 100000 + (steps * 10000);
    }

    // Store current VAT for this session
    let currentRandomVAT = getRandomVAT();

    function calculateTotalPrice() {
      const isForeign = document.getElementById('foreign').checked;
      const servicePrice = parseFloat(document.getElementById('selectedServicePrice').value) || 0;

      // Only calculate if service is selected
      if (servicePrice === 0) {
        document.getElementById('totalPriceDisplay').classList.remove('show');
        return 0;
      }

      let totalPrice = servicePrice;
      let breakdown = [];

      breakdown.push(`Phí dịch vụ: ${formatCurrency(servicePrice)}`);

      // Random VAT from 100k to 200k
      totalPrice += currentRandomVAT;
      breakdown.push(`Phí VAT (đã tính): ${formatCurrency(currentRandomVAT)}`);

      if (isForeign) {
        const foreignSurcharge = Math.round(totalPrice * 0.25);
        totalPrice += foreignSurcharge;
        breakdown.push(`Phụ thu nước ngoài (25%): ${formatCurrency(foreignSurcharge)}`);
      }

      document.getElementById('totalPriceValue').textContent = formatCurrency(totalPrice);
      document.getElementById('priceBreakdown').innerHTML = breakdown.join('<br>');

      // Show price display if payment method selected
      const paymentMethod = document.querySelector('input[name="payment"]:checked');
      if (paymentMethod && paymentMethod.value === 'cash') {
        document.getElementById('totalPriceDisplay').classList.add('show');
      }

      return totalPrice;
    }

    function formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount);
    }

    // Handle form submit - Step 1: Xác nhận -> Gửi mã
    async function handleFormSubmit(e) {
      e.preventDefault();

      if (!isSlotAvailable) {
        alert('Khung giờ này đã có người đặt. Vui lòng chọn thời gian khác!');
        return;
      }

      const submitBtn = document.getElementById('submitBtn');

      // If already verified, submit booking
      if (isEmailVerified) {
        await submitBooking();
        return;
      }

      // Step 1: Validate and send verification code
      const patientEmail = document.getElementById('patientEmail').value.trim();
      const patientName = document.getElementById('patientName').value.trim();
      const patientPhone = document.getElementById('patientPhone').value.trim();
      const doctorId = document.getElementById('selectedDoctorId').value;
      const appointmentDate = document.getElementById('appointmentDate').value;
      const timeSlot = document.getElementById('appointmentTime').value;
      const serviceId = document.getElementById('selectedServiceId').value;
      const specId = document.getElementById('selectedSpecializationId').value;

      if (!specId) {
        alert('Vui lòng chọn chuyên khoa!');
        return;
      }

      if (!serviceId) {
        alert('Vui lòng chọn dịch vụ khám!');
        return;
      }

      if (!doctorId || !patientName || !patientPhone || !patientEmail || !appointmentDate || !timeSlot) {
        alert('Vui lòng điền đầy đủ thông tin bắt buộc!');
        return;
      }

      // Store pending data
      pendingBookingData = {
        doctor_id: parseInt(doctorId),
        appointment_date: appointmentDate,
        time_slot: timeSlot,
        patient_name: patientName,
        patient_phone: patientPhone,
        patient_email: patientEmail,
        patient_birthday: document.getElementById('patientBirthday').value,
        notes: document.getElementById('bookingNotes').value.trim(),
        is_foreign: document.getElementById('foreign').checked,
        is_relative: document.getElementById('relatives').checked,
        fee_amount: calculateTotalPrice(),
        vat_amount: currentRandomVAT,
        payment_method: 'cash',
        clinic_id: selectedDoctor?.clinic?.id || null,
        clinic_name: selectedDoctor?.clinic?.name || null,
        service_id: document.getElementById('selectedServiceId').value || null,
        specialization_id: document.getElementById('selectedSpecializationId').value || null,
        status: 'confirmed'
      };

      // If already verified, submit booking directly
      if (isEmailVerified) {
        await submitBooking();
        return;
      }

      // Send verification code
      submitBtn.disabled = true;
      submitBtn.textContent = 'Đang gửi mã...';

      try {
        const response = await fetch(`${API_BASE}/public/send-booking-code`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: patientEmail
          })
        });

        const result = await response.json();

        if (response.ok) {
          // Show verification modal
          document.getElementById('verifyEmailDisplay').textContent = patientEmail;
          document.getElementById('verificationModal').classList.add('active');
          document.getElementById('verificationCode').value = '';
          document.getElementById('verificationCode').focus();
          document.getElementById('verificationStatus').textContent = '';
          document.getElementById('verificationStatus').className = 'verification-status';
        } else {
          alert('❌ ' + (result.message || 'Không thể gửi mã xác nhận. Vui lòng thử lại!'));
        }
      } catch (error) {
        console.error('Error sending code:', error);
        alert('❌ Có lỗi xảy ra. Vui lòng thử lại!');
      }

      submitBtn.disabled = false;
      submitBtn.textContent = 'Đặt lịch ngay';
    }

    // Show/hide button spinner
    function setVerifyButtonLoading(isLoading) {
      const btnVerify = document.getElementById('btnVerify');
      const btnText = btnVerify.querySelector('.btn-text');
      const btnSpinner = btnVerify.querySelector('.btn-spinner');
      
      if (isLoading) {
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline';
        btnVerify.disabled = true;
        btnVerify.style.opacity = '0.8';
      } else {
        btnText.style.display = 'inline';
        btnSpinner.style.display = 'none';
        btnVerify.disabled = false;
        btnVerify.style.opacity = '1';
      }
    }

    // Verify code
    async function verifyCode() {
      const code = document.getElementById('verificationCode').value.trim();
      const statusEl = document.getElementById('verificationStatus');
      const inputEl = document.getElementById('verificationCode');

      if (code.length !== 6) {
        statusEl.textContent = 'Vui lòng nhập đủ 6 số';
        statusEl.className = 'verification-status error';
        return;
      }

      setVerifyButtonLoading(true);
      statusEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xác nhận mã...';
      statusEl.className = 'verification-status';

      try {
        const response = await fetch(`${API_BASE}/public/verify-booking-code`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: pendingBookingData.patient_email,
            code: code
          })
        });

        const result = await response.json();
        if (response.ok && result.success) {
          inputEl.classList.remove('error');
          inputEl.classList.add('success');
          statusEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ✅ Xác nhận thành công! Đang đặt lịch...';
          statusEl.className = 'verification-status success';
          isEmailVerified = true;

          // Auto submit booking after verification with proper async handling
          await new Promise(resolve => setTimeout(resolve, 1000));
          closeVerificationModal();
          await submitBooking();
        } else {
          inputEl.classList.remove('success');
          inputEl.classList.add('error');
          statusEl.textContent = '❌ ' + (result.message || 'Mã không đúng. Vui lòng thử lại!');
          statusEl.className = 'verification-status error';
          setVerifyButtonLoading(false);
        }
      } catch (error) {
        console.error('Error verifying code:', error);
        statusEl.textContent = '❌ Có lỗi xảy ra. Vui lòng thử lại!';
        statusEl.className = 'verification-status error';
        setVerifyButtonLoading(false);
      }
    }

    function closeVerificationModal() {
      document.getElementById('verificationModal').classList.remove('active');
    }

    async function resendVerificationCode() {
      const statusEl = document.getElementById('verificationStatus');
      statusEl.textContent = 'Đang gửi lại mã...';
      statusEl.className = 'verification-status';

      try {
        const response = await fetch(`${API_BASE}/public/send-booking-code`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            email: pendingBookingData.patient_email
          })
        });

        if (response.ok) {
          statusEl.textContent = '✅ Đã gửi lại mã xác nhận!';
          statusEl.className = 'verification-status success';
          document.getElementById('verificationCode').value = '';
          document.getElementById('verificationCode').classList.remove('error', 'success');
        } else {
          statusEl.textContent = '❌ Không thể gửi mã. Vui lòng thử lại!';
          statusEl.className = 'verification-status error';
        }
      } catch (error) {
        statusEl.textContent = '❌ Có lỗi xảy ra!';
        statusEl.className = 'verification-status error';
      }
    }

    // Final submit booking
    async function submitBooking() {
      const submitBtn = document.getElementById('submitBtn');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Đang đặt lịch...';

      // Validate pending data exists
      if (!pendingBookingData) {
        alert('❌ Lỗi: Không có dữ liệu đặt lịch. Vui lòng điền lại form!');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Đặt hẹn ngay';
        return;
      }

      // Calculate end time from start time + duration
      const startTime = pendingBookingData.time_slot;
      const durationMinutes = parseInt(document.getElementById('selectedServiceDuration')?.value) || 60;

      const [hours, minutes] = startTime.split(':').map(Number);
      const startDate = new Date();
      startDate.setHours(hours, minutes || 0, 0, 0);
      const endDate = new Date(startDate.getTime() + durationMinutes * 60000);
      const endTime = endDate.getHours().toString().padStart(2, '0') + ':' + endDate.getMinutes().toString().padStart(2, '0');

      const bookingData = {
        ...pendingBookingData,
        start_time: startTime,
        end_time: endTime
      };

      try {
        const response = await fetch(`${API_BASE}/public/appointments`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(bookingData)
        });

        const result = await response.json();
        if (response.ok && result.success) {
          alert(`✅ Đặt lịch thành công!\n\nMã đặt lịch: #${result.data?.id || result.id || 'N/A'}\nBác sĩ: ${selectedDoctor?.full_name}\nNgày khám: ${pendingBookingData.appointment_date}\nGiờ khám: ${pendingBookingData.time_slot}\nTổng chi phí: ${formatCurrency(pendingBookingData.fee_amount)}\n\n📧 Email xác nhận đã được gửi đến ${pendingBookingData.patient_email}`);

          // Reset form
          document.getElementById('bookingForm').reset();
          document.getElementById('totalPriceDisplay').classList.remove('show');
          document.getElementById('bankDevNotice').classList.remove('show');
          document.getElementById('timeSlotError').style.display = 'none';
          document.getElementById('locationInfo').style.display = 'none';
          document.getElementById('doctorAddressPlaceholder').style.display = 'block';
          submitBtn.textContent = 'Đặt lịch ngay';
          submitBtn.classList.remove('verified');
          submitBtn.classList.add('confirm-btn');
          submitBtn.disabled = true;
          selectedDoctor = null;
          currentVATPercent = 0;
          isEmailVerified = false;
          pendingBookingData = null;
        } else {
          console.error('Booking failed:', result);
          alert('❌ Lỗi: ' + (result.message || 'Không thể đặt lịch. Vui lòng thử lại!'));
          submitBtn.disabled = false;
          submitBtn.textContent = 'Đặt hẹn ngay';
        }
      } catch (error) {
        console.error('Error submitting booking:', error);
        alert('❌ Có lỗi xảy ra. Vui lòng thử lại sau!');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Đặt hẹn ngay';
      }
    }

    function formatDate(dateStr) {
      if (!dateStr) return '';
      const date = new Date(dateStr);
      return date.toLocaleDateString('vi-VN', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
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