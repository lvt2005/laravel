<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>@yield('title', 'Bệnh viện Nam Sài Gòn')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    @yield('styles')
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
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #ffffff;
            padding-left: 5px;
        }

        .contact-info p {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.85);
        }

        .contact-info i {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            margin-top: 3px;
        }

        /* Newsletter Form */
        .newsletter-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 25px;
            font-size: 13px;
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .newsletter-form input:focus {
            outline: none;
            background-color: rgba(255, 255, 255, 0.25);
        }

        .newsletter-form button {
            padding: 12px 25px;
            background-color: #f4c430;
            color: #fff;
            border: none;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            background-color: #e8b81a;
        }

        /* Social Icons */
        .social-icons {
            display: flex;
            gap: 12px;
        }

        .social-icons a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 50%;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: #f4c430;
            transform: translateY(-3px);
        }

        /* Footer Bottom */
        .footer-bottom {
            padding-top: 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-bottom p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
        }

        .footer-links {
            display: flex;
            list-style: none;
            gap: 5px;
        }

        .footer-links li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 12px;
            padding: 0 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            transition: color 0.3s ease;
        }

        .footer-links li:last-child a {
            border-right: none;
        }

        .footer-links li a:hover {
            color: #ffffff;
        }

        /* Responsive */
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
            .container {
                padding: 0 15px;
            }

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
</head>
<body>
    <script src="{{ asset('frontend/js/auth.js') }}"></script>
    
    {{-- Include unified header --}}
    @include('partials.header')

    @yield('content')

    @include('partials.footer')

    @yield('scripts')
</body>
</html>
