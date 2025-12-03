<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đang bảo trì - DoctorHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .maintenance-container {
            background: #ffffff;
            border-radius: 24px;
            padding: 50px 45px;
            max-width: 550px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        
        .maintenance-container::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.03) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(180deg); }
        }
        
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        
        .maintenance-icon {
            font-size: 90px;
            margin-bottom: 25px;
            animation: rotate 3s ease-in-out infinite;
            display: inline-block;
        }
        
        @keyframes rotate {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-15deg); }
            75% { transform: rotate(15deg); }
        }
        
        .maintenance-title {
            font-size: 32px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .maintenance-message {
            color: #4a5568;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        
        .maintenance-message p {
            margin-bottom: 12px;
        }
        
        .custom-message {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 35px;
            font-style: italic;
            color: #4a5568;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
        }
        
        .back-button {
            display: inline-block;
            padding: 14px 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .back-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            background: linear-gradient(135deg, #7c8ef7 0%, #8a5fb5 100%);
        }
        
        .contact-info {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(102, 126, 234, 0.15);
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .contact-info strong {
            color: #667eea;
            font-weight: 600;
        }
        
        @media (max-width: 480px) {
            .maintenance-container {
                padding: 40px 30px;
            }
            
            .maintenance-icon {
                font-size: 70px;
            }
            
            .maintenance-title {
                font-size: 26px;
            }
            
            .maintenance-message {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="content-wrapper">
            <div class="maintenance-icon">🔧</div>
            <h1 class="maintenance-title">Hệ thống đang bảo trì</h1>
            <div class="maintenance-message">
                <p>Chúng tôi đang thực hiện bảo trì định kỳ để cải thiện dịch vụ.</p>
                <p>Vui lòng quay lại sau một thời gian ngắn.</p>
            </div>
            <!-- Uncomment this section if you need a custom message -->
            <!-- <div class="custom-message">
                Thông báo tùy chỉnh của bạn ở đây
            </div> -->
            <div class="contact-info">
                <p>Nếu có vấn đề cần thảo luận hay góp ý, hãy liên hệ qua mail:<br><strong>nhom5@gmail.com</strong></p>
            </div>
        </div>
    </div>
</body>
</html>