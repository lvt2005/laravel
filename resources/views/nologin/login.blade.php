<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('frontend/login/login.css') }}" />
  <title>Đăng Nhập | Hệ Thống Đặt Lịch Hẹn Bác Sĩ</title>
  <!-- Google Sign-In API -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <script src="{{ asset('frontend/js/auth.js') }}"></script>
</head>

<body>
  <!-- Toast notification container -->
  <div id="toastContainer" class="toast-container"></div>
  
  <div class="container" id="container">
    <!-- Form Đăng Ký -->
    <div class="form-container sign-up">
      <form id="signup_form">
        <h1>Tạo Tài Khoản</h1>
        <div class="social-icons">
          <a href="#" class="icon" id="btnGoogleSignup" title="Đăng ký bằng Google"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
        <span>Sử dụng email để đăng ký</span>
        <label for="signup_full_name" style="display:none">Họ tên</label>
        <input id="signup_full_name" type="text" placeholder="Họ Tên" aria-label="Họ Tên" />
        <span class="error-msg" id="err_full_name"></span>
        <label for="signup_email" style="display:none">Email</label>
        <input id="signup_email" type="email" placeholder="Email" aria-label="Email" />
        <span class="error-msg" id="err_email"></span>
        <label for="signup_phone" style="display:none">SĐT</label>
        <input id="signup_phone" type="text" placeholder="SĐT" aria-label="Số điện thoại" />
        <span class="error-msg" id="err_phone"></span>
        <label for="signup_password" style="display:none">Mật khẩu</label>
        <input id="signup_password" type="password" placeholder="Mật Khẩu (>=6 ký tự)" aria-label="Mật khẩu" />
        <span class="error-msg" id="err_password"></span>
        <button type="button" id="btnSignup">Đăng Ký</button>
      </form>
    </div>

    <!-- Form Đăng Nhập -->
    <div class="form-container sign-in">
      <form id="login_form">
        <h1>Đăng Nhập</h1>
        <div class="social-icons">
          <a href="#" class="icon" id="btnGoogleLogin" title="Đăng nhập bằng Google"><i class="fa-brands fa-google-plus-g"></i></a>
          <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
        </div>
        <span>Sử dụng email và mật khẩu</span>
        <label for="login_email" style="display:none">Email</label>
        <input id="login_email" type="email" placeholder="Email" aria-label="Email" />
        <span class="error-msg" id="err_login_email"></span>
        <label for="login_password" style="display:none">Mật khẩu</label>
        <input id="login_password" type="password" placeholder="Mật Khẩu" aria-label="Mật khẩu" />
        <span class="error-msg" id="err_login_password"></span>
        <a href="{{ route('quen-mat-khau') }}">Quên Mật Khẩu?</a>
        <button type="button" id="btnLogin">Đăng Nhập</button>
      </form>

      <!-- 2FA Verification Form (hidden by default) -->
      <form id="twofa_form" style="display: none;">
        <h1>Xác thực 2 yếu tố</h1>
        <div style="text-align: center; margin-bottom: 15px;">
          <i class="fa-solid fa-shield-halved" style="font-size: 48px; color: #28a745;"></i>
        </div>
        <span style="text-align: center; display: block; margin-bottom: 15px;">Nhập mã 6 số đã gửi đến email của bạn</span>
        <input id="twofa_email" type="hidden" />
        <input id="twofa_code" type="text" placeholder="Nhập mã 6 số" aria-label="Mã xác thực" maxlength="6" style="text-align: center; font-size: 24px; letter-spacing: 8px;" />
        <span class="error-msg" id="err_twofa_code"></span>
        <p style="font-size: 12px; color: #666; margin: 10px 0;">Mã sẽ hết hạn sau 10 phút</p>
        <button type="button" id="btnVerify2FA">Xác thực</button>
        <button type="button" id="btnBack2FA" style="background: transparent; color: #666; border: 1px solid #ccc; margin-top: 10px;">
          <i class="fa-solid fa-arrow-left"></i> Quay lại
        </button>
      </form>
    </div>

    <div class="toggle-container">
      <div class="toggle">
        <div class="toggle-panel toggle-left">
          <h1>Chào Mừng Trở Lại!</h1>
          <p>Nhập thông tin để tiếp tục sử dụng hệ thống đặt lịch hẹn bác sĩ</p>
          <button class="hidden" id="login">Đăng Nhập</button>
        </div>
        <div class="toggle-panel toggle-right">
          <h1>Xin Chào!</h1>
          <p>Đăng ký để mở khóa đầy đủ chức năng của hệ thống đặt lịch hẹn</p>
          <button class="hidden" id="register">Đăng Ký</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Google Client ID
    const GOOGLE_CLIENT_ID = '320024732573-659gsb5b7n4p9794l2vugpkj4l6odsae.apps.googleusercontent.com';
    
    const container = document.getElementById('container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    registerBtn.addEventListener('click', () => container.classList.add('active'));
    loginBtn.addEventListener('click', () => container.classList.remove('active'));

    // Toast notification functions
    function showToast(message, type = 'info', duration = 4000) {
      const toastContainer = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      
      let icon = '';
      switch(type) {
        case 'success': icon = '<i class="fas fa-check-circle"></i>'; break;
        case 'error': icon = '<i class="fas fa-exclamation-circle"></i>'; break;
        case 'warning': icon = '<i class="fas fa-exclamation-triangle"></i>'; break;
        default: icon = '<i class="fas fa-info-circle"></i>';
      }
      
      toast.innerHTML = `${icon}<span>${message}</span>`;
      toastContainer.appendChild(toast);
      
      // Trigger animation
      setTimeout(() => toast.classList.add('show'), 10);
      
      // Auto remove
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, duration);
    }

    function setLoading(is) {
      document.body.classList.toggle('loading', is);
      document.querySelectorAll('button').forEach(b => b.disabled = is);
    }
    
    function setErr(id, msg) {
      const el = document.getElementById(id);
      if (!el) return;
      if (msg) {
        el.textContent = msg;
        el.classList.add('active');
      } else {
        el.textContent = '';
        el.classList.remove('active');
      }
    }
    
    function clearSignupErrors() { ['err_full_name', 'err_email', 'err_phone', 'err_password'].forEach(i => setErr(i, null)); }
    function clearLoginErrors() { ['err_login_email', 'err_login_password'].forEach(i => setErr(i, null)); }

    // Map error codes to Vietnamese messages
    function getErrorMessage(errorCode, field = '') {
      const errorMessages = {
        'EMAIL_EXISTS': 'Email này đã được đăng ký. Vui lòng sử dụng email khác hoặc đăng nhập.',
        'PHONE_EXISTS': 'Số điện thoại này đã được sử dụng. Vui lòng dùng số khác.',
        'EMAIL_NOT_FOUND': 'Email không tồn tại trong hệ thống. Vui lòng kiểm tra lại hoặc đăng ký tài khoản mới.',
        'WRONG_PASSWORD': 'Mật khẩu không chính xác. Vui lòng thử lại.',
        'MISSING_EMAIL': 'Không thể lấy email từ tài khoản Google. Vui lòng thử lại.',
        'VALIDATION': 'Thông tin không hợp lệ. Vui lòng kiểm tra lại.',
        'ACCOUNT_INACTIVE': 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ hỗ trợ.',
        'GOOGLE_AUTH_FAILED': 'Đăng nhập Google thất bại. Vui lòng thử lại.',
        'NETWORK_ERROR': 'Lỗi kết nối mạng. Vui lòng kiểm tra kết nối internet.',
        'SERVER_ERROR': 'Lỗi máy chủ. Vui lòng thử lại sau.',
      };
      return errorMessages[errorCode] || `Đã xảy ra lỗi: ${errorCode}`;
    }

    async function doSignup() {
      clearSignupErrors();
      const full_name = signup_full_name.value.trim();
      const email = signup_email.value.trim();
      const phone = signup_phone.value.trim();
      const password = signup_password.value;
      let ok = true;
      
      if (!full_name) { setErr('err_full_name', 'Vui lòng nhập họ tên'); ok = false; }
      if (!email) { setErr('err_email', 'Vui lòng nhập email'); ok = false; }
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setErr('err_email', 'Email không hợp lệ'); ok = false; }
      if (!phone) { setErr('err_phone', 'Vui lòng nhập số điện thoại'); ok = false; }
      else if (!/^[0-9]{10,11}$/.test(phone)) { setErr('err_phone', 'Số điện thoại phải có 10-11 chữ số'); ok = false; }
      if (password.length < 6) { setErr('err_password', 'Mật khẩu phải có ít nhất 6 ký tự'); ok = false; }
      
      if (!ok) return;
      
      setLoading(true);
      try {
        const res = await AuthAPI.register(full_name, email, phone, password);
        if (!res.ok) {
          const error = res.data.error;
          if (error === 'EMAIL_EXISTS') {
            setErr('err_email', getErrorMessage('EMAIL_EXISTS'));
            showToast(getErrorMessage('EMAIL_EXISTS'), 'error');
          } else if (error === 'PHONE_EXISTS') {
            setErr('err_phone', getErrorMessage('PHONE_EXISTS'));
            showToast(getErrorMessage('PHONE_EXISTS'), 'error');
          } else if (error === 'VALIDATION' && res.data.fields) {
            Object.keys(res.data.fields).forEach(f => { 
              setErr('err_' + f, res.data.fields[f][0]);
            });
            showToast('Vui lòng kiểm tra lại thông tin đăng ký', 'warning');
          } else {
            showToast(getErrorMessage(error), 'error');
          }
        } else {
          showToast('Đăng ký thành công! Đang chuyển hướng...', 'success');
          setTimeout(() => redirectAfterLogin(res.data.user.type), 1000);
        }
      } catch (e) { 
        setErr('err_email', 'Lỗi kết nối. Vui lòng thử lại.');
        showToast(getErrorMessage('NETWORK_ERROR'), 'error');
      } finally {
        setLoading(false);
      }
    }

    async function doLogin() {
      clearLoginErrors();
      const email = login_email.value.trim();
      const password = login_password.value;
      let ok = true;
      
      if (!email) { setErr('err_login_email', 'Vui lòng nhập email'); ok = false; }
      else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setErr('err_login_email', 'Email không hợp lệ'); ok = false; }
      if (!password) { setErr('err_login_password', 'Vui lòng nhập mật khẩu'); ok = false; }
      
      if (!ok) return;
      
      setLoading(true);
      try {
        const res = await AuthAPI.login(email, password);
        if (!res.ok) {
          const error = res.data.error;
          if (error === 'EMAIL_NOT_FOUND') {
            setErr('err_login_email', getErrorMessage('EMAIL_NOT_FOUND'));
            showToast(getErrorMessage('EMAIL_NOT_FOUND'), 'error');
          } else if (error === 'WRONG_PASSWORD') {
            setErr('err_login_password', getErrorMessage('WRONG_PASSWORD'));
            showToast(getErrorMessage('WRONG_PASSWORD'), 'error');
          } else if (error === 'VALIDATION' && res.data.fields) {
            Object.keys(res.data.fields).forEach(f => { 
              setErr('err_login_' + f, res.data.fields[f][0]);
            });
          } else {
            showToast(getErrorMessage(error), 'error');
          }
        } else if (res.data.requires_2fa) {
          // 2FA required - show 2FA form
          showToast('Mã xác thực đã được gửi đến email của bạn', 'info');
          show2FAForm(email);
        } else {
          showToast('Đăng nhập thành công!', 'success');
          setTimeout(() => redirectAfterLogin(res.data.user.type), 800);
        }
      } catch (e) { 
        setErr('err_login_email', 'Lỗi kết nối. Vui lòng thử lại.');
        showToast(getErrorMessage('NETWORK_ERROR'), 'error');
      } finally {
        setLoading(false);
      }
    }

    // Show 2FA verification form
    function show2FAForm(email) {
      document.getElementById('login_form').style.display = 'none';
      document.getElementById('twofa_form').style.display = 'block';
      document.getElementById('twofa_email').value = email;
      document.getElementById('twofa_code').value = '';
      document.getElementById('twofa_code').focus();
    }

    // Hide 2FA form and show login form
    function hide2FAForm() {
      document.getElementById('twofa_form').style.display = 'none';
      document.getElementById('login_form').style.display = 'block';
    }

    // Verify 2FA code
    async function verify2FA() {
      const email = document.getElementById('twofa_email').value;
      const code = document.getElementById('twofa_code').value.trim();

      if (!code || code.length !== 6) {
        setErr('err_twofa_code', 'Vui lòng nhập đủ 6 số');
        return;
      }

      setLoading(true);
      try {
        const response = await fetch('/api/auth/verify-2fa', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, code })
        });
        const data = await response.json();

        if (!response.ok) {
          setErr('err_twofa_code', data.message || 'Mã xác thực không đúng');
          showToast(data.message || 'Mã xác thực không đúng', 'error');
        } else {
          // Save tokens and redirect
          localStorage.setItem('access_token', data.access_token);
          localStorage.setItem('refresh_token', data.refresh_token);
          localStorage.setItem('session_id', data.session_id);
          showToast('Đăng nhập thành công!', 'success');
          setTimeout(() => redirectAfterLogin(data.user.type), 800);
        }
      } catch (e) {
        setErr('err_twofa_code', 'Lỗi kết nối. Vui lòng thử lại.');
        showToast(getErrorMessage('NETWORK_ERROR'), 'error');
      } finally {
        setLoading(false);
      }
    }

    // Google Sign-In handler
    async function handleGoogleLogin(response) {
      setLoading(true);
      try {
        // Decode JWT token from Google
        const payload = JSON.parse(atob(response.credential.split('.')[1]));
        
        const googleData = {
          email: payload.email,
          full_name: payload.name,
          avatar_url: payload.picture,
          dob: null,
          gender: null,
          address: null
        };
        
        const res = await AuthAPI.googleLogin(googleData);
        
        if (!res.ok) {
          const error = res.data.error;
          showToast(getErrorMessage(error || 'GOOGLE_AUTH_FAILED'), 'error');
        } else {
          showToast(`Xin chào ${res.data.user.full_name}! Đang chuyển hướng...`, 'success');
          setTimeout(() => redirectAfterLogin(res.data.user.type), 1000);
        }
      } catch (e) {
        console.error('Google login error:', e);
        showToast(getErrorMessage('GOOGLE_AUTH_FAILED'), 'error');
      } finally {
        setLoading(false);
      }
    }

    // Initialize Google Sign-In
    function initGoogleSignIn() {
      if (typeof google !== 'undefined' && google.accounts) {
        google.accounts.id.initialize({
          client_id: GOOGLE_CLIENT_ID,
          callback: handleGoogleLogin,
          auto_select: false,
          cancel_on_tap_outside: true
        });
      }
    }

    // Redirect to Google OAuth (phương pháp chính - đáng tin cậy hơn)
    function redirectToGoogleOAuth() {
      const redirectUri = encodeURIComponent(window.location.origin + '/dang-nhap/google-callback');
      const scope = encodeURIComponent('email profile openid');
      const responseType = 'id_token token';
      const nonce = Math.random().toString(36).substring(2, 15);
      
      // Lưu nonce để verify sau
      sessionStorage.setItem('google_nonce', nonce);
      
      const googleAuthUrl = `https://accounts.google.com/o/oauth2/v2/auth?` +
        `client_id=${GOOGLE_CLIENT_ID}` +
        `&redirect_uri=${redirectUri}` +
        `&response_type=${encodeURIComponent(responseType)}` +
        `&scope=${scope}` +
        `&nonce=${nonce}` +
        `&prompt=select_account`;
      
      window.location.href = googleAuthUrl;
    }

    // Trigger Google Sign-In popup hoặc redirect
    function triggerGoogleSignIn() {
      // Sử dụng redirect flow (đáng tin cậy hơn)
      redirectToGoogleOAuth();
    }

    // Demo Google login (for development without real Google Client ID)
    async function demoGoogleLogin() {
      const email = prompt('Nhập email Google của bạn:');
      if (!email) return;
      
      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showToast('Email không hợp lệ', 'error');
        return;
      }
      
      setLoading(true);
      try {
        const googleData = {
          email: email,
          full_name: email.split('@')[0].replace(/[._]/g, ' '),
          avatar_url: null,
          dob: null,
          gender: null,
          address: null
        };
        
        const res = await AuthAPI.googleLogin(googleData);
        
        if (!res.ok) {
          showToast(getErrorMessage(res.data.error || 'GOOGLE_AUTH_FAILED'), 'error');
        } else {
          showToast(`Xin chào ${res.data.user.full_name}! Đang chuyển hướng...`, 'success');
          setTimeout(() => redirectAfterLogin(res.data.user.type), 1000);
        }
      } catch (e) {
        showToast(getErrorMessage('NETWORK_ERROR'), 'error');
      } finally {
        setLoading(false);
      }
    }

    function redirectAfterLogin(role) {
      if (!role) return;
      const access = localStorage.getItem('access_token');
      const refresh = localStorage.getItem('refresh_token');
      const sid = localStorage.getItem('session_id');
      if (access) sessionStorage.setItem('access_token', access);
      if (refresh) sessionStorage.setItem('refresh_token', refresh);
      if (sid) sessionStorage.setItem('session_id', sid);
      
      // Chuyển hướng dựa theo role - sử dụng URL sạch
      if (role === 'ADMIN') {
        window.location.href = '/quan-tri';
      } else if (role === 'DOCTOR') {
        window.location.href = '/bac-si/ho-so';
      } else {
        // USER và các role khác đều đến bảng điều khiển (dashboard)
        window.location.href = '/bang-dieu-khien';
      }
    }

    // Gắn sự kiện
    document.getElementById('btnSignup')?.addEventListener('click', e => { e.preventDefault(); doSignup(); });
    document.getElementById('btnLogin')?.addEventListener('click', e => { e.preventDefault(); doLogin(); });
    document.getElementById('btnGoogleLogin')?.addEventListener('click', e => { e.preventDefault(); triggerGoogleSignIn(); });
    document.getElementById('btnGoogleSignup')?.addEventListener('click', e => { e.preventDefault(); triggerGoogleSignIn(); });
    
    // 2FA event handlers
    document.getElementById('btnVerify2FA')?.addEventListener('click', e => { e.preventDefault(); verify2FA(); });
    document.getElementById('btnBack2FA')?.addEventListener('click', e => { e.preventDefault(); hide2FAForm(); });
    
    // Enter key handlers
    document.getElementById('login_password')?.addEventListener('keypress', e => { if (e.key === 'Enter') doLogin(); });
    document.getElementById('signup_password')?.addEventListener('keypress', e => { if (e.key === 'Enter') doSignup(); });
    document.getElementById('twofa_code')?.addEventListener('keypress', e => { if (e.key === 'Enter') verify2FA(); });

    // Initialize Google Sign-In when page loads
    window.onload = function() {
      setTimeout(initGoogleSignIn, 500);
    };
  </script>
</body>

</html>
