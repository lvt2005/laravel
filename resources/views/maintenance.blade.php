<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Hệ thống đang bảo trì - DoctorHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .maintenance-container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .maintenance-icon {
            font-size: 80px;
            margin-bottom: 30px;
        }
        
        .maintenance-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .maintenance-message {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .maintenance-message p {
            margin-bottom: 10px;
        }
        
        .custom-message {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 30px;
            font-style: italic;
            color: #555;
            border-left: 4px solid #667eea;
        }
        
        .back-button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 500;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .contact-info {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 14px;
        }
        
        @media (max-width: 480px) {
            .maintenance-container {
                padding: 40px 25px;
            }
            
            .maintenance-icon {
                font-size: 60px;
            }
            
            .maintenance-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1 class="maintenance-title">Hệ thống đang bảo trì</h1>
        <div class="maintenance-message">
            <p>Chúng tôi đang thực hiện bảo trì định kỳ để cải thiện dịch vụ.</p>
            <p>Vui lòng quay lại sau một thời gian ngắn.</p>
        </div>
        @if(isset($message) && $message)
        <div class="custom-message">
            {{ $message }}
        </div>
        @endif
        <div class="contact-info">
            <p>Nếu có vấn đề cần thảo luận hay góp ý, hãy liên hệ qua mail: <strong>uytinso1vn@gmail.com</strong></p>
        </div>
    </div>
</body>
</html>
