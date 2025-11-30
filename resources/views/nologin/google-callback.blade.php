<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Đang xử lý đăng nhập Google...</title>
    <link rel="stylesheet" href="{{ asset('frontend/login/login.css') }}">
    <script src="{{ asset('frontend/js/auth.js') }}"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
        }
        .callback-container {
            background: white;
            padding: 40px 60px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .callback-container h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .callback-container p {
            color: #666;
            margin: 0;
        }
        .error-message {
            color: #e74c3c;
            margin-top: 15px;
            display: none;
        }
        .btn-retry {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 14px;
            display: none;
        }
        .btn-retry:hover {
            background: #5a6fd6;
        }
    </style>
</head>
<body>
    <div class="callback-container">
        <div class="spinner" id="spinner"></div>
        <h2 id="statusTitle">Đang xử lý...</h2>
        <p id="statusMessage">Vui lòng đợi trong giây lát</p>
        <p class="error-message" id="errorMessage"></p>
        <button class="btn-retry" id="btnRetry" onclick="window.location.href='{{ route("dang-nhap") }}'">
            Quay lại đăng nhập
        </button>
    </div>

    <script>
        // Parse URL fragment (hash) để lấy tokens từ Google
        function parseHashParams() {
            const hash = window.location.hash.substring(1);
            const params = {};
            hash.split('&').forEach(part => {
                const [key, value] = part.split('=');
                if (key && value) {
                    params[key] = decodeURIComponent(value);
                }
            });
            return params;
        }

        // Decode JWT token
        function decodeJWT(token) {
            try {
                const base64Url = token.split('.')[1];
                const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                const jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                return JSON.parse(jsonPayload);
            } catch (e) {
                console.error('Error decoding JWT:', e);
                return null;
            }
        }

        // Show error
        function showError(message) {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('statusTitle').textContent = 'Đăng nhập thất bại';
            document.getElementById('statusMessage').textContent = '';
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorMessage').style.display = 'block';
            document.getElementById('btnRetry').style.display = 'inline-block';
        }

        // Redirect based on role
        function redirectAfterLogin(role) {
            if (role === 'ADMIN') {
                window.location.href = '/quan-tri';
            } else if (role === 'DOCTOR') {
                window.location.href = '/bac-si/ho-so';
            } else {
                // USER và các role khác đều đến bảng điều khiển (dashboard)
                window.location.href = '/bang-dieu-khien';
            }
        }

        // Main process
        async function processGoogleCallback() {
            try {
                const params = parseHashParams();
                
                // Check for error from Google
                if (params.error) {
                    showError('Google từ chối quyền truy cập: ' + (params.error_description || params.error));
                    return;
                }

                const idToken = params.id_token;
                
                if (!idToken) {
                    showError('Không nhận được token từ Google. Vui lòng thử lại.');
                    return;
                }

                // Verify nonce
                const savedNonce = sessionStorage.getItem('google_nonce');
                const payload = decodeJWT(idToken);
                
                if (!payload) {
                    showError('Không thể xác thực token từ Google.');
                    return;
                }

                if (savedNonce && payload.nonce !== savedNonce) {
                    console.warn('Nonce mismatch - possible replay attack');
                    // Continue anyway for now, just log warning
                }

                // Clear nonce
                sessionStorage.removeItem('google_nonce');

                // Update status
                document.getElementById('statusMessage').textContent = 'Đang đăng nhập...';

                // Prepare Google data
                const googleData = {
                    email: payload.email,
                    full_name: payload.name,
                    avatar_url: payload.picture,
                    dob: null,
                    gender: null,
                    address: null
                };

                // Call API to login/register with Google
                const res = await AuthAPI.googleLogin(googleData);

                if (!res.ok) {
                    const errorMessages = {
                        'EMAIL_EXISTS': 'Email này đã được liên kết với tài khoản khác.',
                        'MISSING_EMAIL': 'Không thể lấy email từ tài khoản Google.',
                        'ACCOUNT_INACTIVE': 'Tài khoản của bạn đã bị vô hiệu hóa.',
                        'GOOGLE_AUTH_FAILED': 'Đăng nhập Google thất bại.'
                    };
                    showError(errorMessages[res.data.error] || 'Đã xảy ra lỗi: ' + res.data.error);
                    return;
                }

                // Success
                document.getElementById('statusTitle').textContent = 'Đăng nhập thành công!';
                document.getElementById('statusMessage').textContent = 'Xin chào ' + res.data.user.full_name + '! Đang chuyển hướng...';

                // Redirect after short delay
                setTimeout(() => redirectAfterLogin(res.data.user.type), 1000);

            } catch (error) {
                console.error('Google callback error:', error);
                showError('Đã xảy ra lỗi khi xử lý đăng nhập. Vui lòng thử lại.');
            }
        }

        // Run on page load
        processGoogleCallback();
    </script>
</body>
</html>
