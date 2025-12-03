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
        /* 2FA Form Styles */
        .twofa-form {
            display: none;
        }
        .twofa-form.active {
            display: block;
        }
        .twofa-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 20px;
            text-align: center;
            letter-spacing: 8px;
            margin: 15px 0;
            box-sizing: border-box;
        }
        .twofa-input:focus {
            border-color: #667eea;
            outline: none;
        }
        .twofa-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            margin-top: 10px;
        }
        .twofa-btn:hover {
            background: #5a6fd6;
        }
        .twofa-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .twofa-error {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 10px;
        }
        .twofa-info {
            color: #666;
            font-size: 13px;
            margin-top: 10px;
        }
        .twofa-resend {
            color: #667eea;
            text-decoration: underline;
            cursor: pointer;
            font-size: 13px;
            margin-top: 15px;
            display: inline-block;
        }
        .twofa-resend:hover {
            color: #5a6fd6;
        }
        .twofa-resend.disabled {
            color: #999;
            cursor: not-allowed;
            text-decoration: none;
        }
        .loading-container, .twofa-form {
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="callback-container">
        <!-- Loading State -->
        <div class="loading-container" id="loadingContainer">
            <div class="spinner" id="spinner"></div>
            <h2 id="statusTitle">Đang xử lý...</h2>
            <p id="statusMessage">Vui lòng đợi trong giây lát</p>
            <p class="error-message" id="errorMessage"></p>
            <button class="btn-retry" id="btnRetry" onclick="window.location.href='{{ route("dang-nhap") }}'">
                Quay lại đăng nhập
            </button>
        </div>
        
        <!-- 2FA Form -->
        <div class="twofa-form" id="twofaForm">
            <h2>🔐 Xác thực 2 bước</h2>
            <p>Mã xác thực đã được gửi đến email của bạn</p>
            <input type="hidden" id="twofaEmail" value="">
            <input type="text" class="twofa-input" id="twofaCode" maxlength="6" placeholder="000000" pattern="[0-9]*" inputmode="numeric">
            <p class="twofa-error" id="twofaError" style="display: none;"></p>
            <button class="twofa-btn" id="twofaSubmitBtn" onclick="verify2FACode()">Xác nhận</button>
            <p class="twofa-info">Mã xác thực có hiệu lực trong 10 phút</p>
            <span class="twofa-resend" id="twofaResend" onclick="resend2FACode()">Gửi lại mã</span>
            <br>
            <button class="btn-retry" style="display: inline-block; margin-top: 15px;" onclick="window.location.href='{{ route("dang-nhap") }}'">
                Quay lại đăng nhập
            </button>
        </div>
    </div>

    <script>
        // Store google data for resending 2FA
        let storedGoogleData = null;
        let resendCooldown = false;

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

        // Show 2FA form
        function show2FAForm(email) {
            document.getElementById('loadingContainer').style.display = 'none';
            document.getElementById('twofaForm').classList.add('active');
            document.getElementById('twofaEmail').value = email;
            document.getElementById('twofaCode').value = '';
            document.getElementById('twofaCode').focus();
            document.getElementById('twofaError').style.display = 'none';
        }

        // Show 2FA error
        function show2FAError(message) {
            document.getElementById('twofaError').textContent = message;
            document.getElementById('twofaError').style.display = 'block';
        }

        // Verify 2FA code
        async function verify2FACode() {
            const email = document.getElementById('twofaEmail').value;
            const code = document.getElementById('twofaCode').value.trim();

            if (!code || code.length !== 6) {
                show2FAError('Vui lòng nhập đủ 6 số');
                return;
            }

            const btn = document.getElementById('twofaSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Đang xác thực...';
            document.getElementById('twofaError').style.display = 'none';

            try {
                const response = await fetch('/api/auth/verify-2fa', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, code })
                });
                const data = await response.json();

                if (!response.ok) {
                    const errorMessages = {
                        'INVALID_CODE': 'Mã xác thực không đúng',
                        'CODE_EXPIRED': 'Mã xác thực đã hết hạn. Vui lòng yêu cầu mã mới.',
                        'USER_NOT_FOUND': 'Không tìm thấy tài khoản'
                    };
                    show2FAError(errorMessages[data.error] || 'Đã xảy ra lỗi, vui lòng thử lại');
                    btn.disabled = false;
                    btn.textContent = 'Xác nhận';
                    return;
                }

                // Success - save tokens and redirect
                if (data.access_token) {
                    localStorage.setItem('access_token', data.access_token);
                    localStorage.setItem('refresh_token', data.refresh_token);
                    localStorage.setItem('session_id', data.session_id);
                    sessionStorage.setItem('access_token', data.access_token);
                    sessionStorage.setItem('refresh_token', data.refresh_token);
                    sessionStorage.setItem('session_id', data.session_id);
                }

                document.getElementById('twofaForm').innerHTML = '<h2>✅ Xác thực thành công!</h2><p>Đang chuyển hướng...</p>';
                
                setTimeout(() => redirectAfterLogin(data.user.type), 1000);

            } catch (error) {
                console.error('2FA verification error:', error);
                show2FAError('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
                btn.disabled = false;
                btn.textContent = 'Xác nhận';
            }
        }

        // Resend 2FA code
        async function resend2FACode() {
            if (resendCooldown || !storedGoogleData) return;

            const resendBtn = document.getElementById('twofaResend');
            resendCooldown = true;
            resendBtn.classList.add('disabled');
            
            let countdown = 60;
            resendBtn.textContent = `Gửi lại sau ${countdown}s`;
            
            const countdownInterval = setInterval(() => {
                countdown--;
                resendBtn.textContent = `Gửi lại sau ${countdown}s`;
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    resendCooldown = false;
                    resendBtn.classList.remove('disabled');
                    resendBtn.textContent = 'Gửi lại mã';
                }
            }, 1000);

            try {
                const res = await AuthAPI.googleLogin(storedGoogleData);
                if (res.ok && res.data.requires_2fa) {
                    document.getElementById('twofaError').style.display = 'none';
                    alert('Mã xác thực mới đã được gửi đến email của bạn!');
                }
            } catch (error) {
                console.error('Resend 2FA error:', error);
            }
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

                // Store for potential resend
                storedGoogleData = googleData;

                // Call API to login/register with Google
                const res = await AuthAPI.googleLogin(googleData);

                if (!res.ok) {
                    const errorMessages = {
                        'EMAIL_EXISTS': 'Email này đã được liên kết với tài khoản khác.',
                        'MISSING_EMAIL': 'Không thể lấy email từ tài khoản Google.',
                        'ACCOUNT_INACTIVE': 'Tài khoản của bạn đã bị vô hiệu hóa.',
                        'ACCOUNT_BLOCKED': 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ nhom5@gmail.com để được hỗ trợ.',
                        'ACCESS_DISABLED': 'Hệ thống đang được bảo trì. Vui lòng quay lại sau.',
                        'GOOGLE_AUTH_FAILED': 'Đăng nhập Google thất bại.'
                    };
                    // Ưu tiên message từ server nếu có
                    const errorMsg = res.data.message || errorMessages[res.data.error] || 'Đã xảy ra lỗi: ' + res.data.error;
                    showError(errorMsg);
                    return;
                }

                // Check if 2FA is required
                if (res.data.requires_2fa) {
                    show2FAForm(res.data.email);
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

        // Allow only numbers in 2FA input
        document.addEventListener('DOMContentLoaded', function() {
            const twofaInput = document.getElementById('twofaCode');
            if (twofaInput) {
                twofaInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
                twofaInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        verify2FACode();
                    }
                });
            }
        });

        // Run on page load
        processGoogleCallback();
    </script>
</body>
</html>
