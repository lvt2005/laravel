<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
  <title>Tra cứu lịch hẹn - Bệnh viện Nam Sài Gòn</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: #e8f0f8;
      padding: 40px 20px;
      min-height: 100vh;
    }

    .container {
      max-width: 900px;
      margin: 0 auto;
    }

    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
      padding: 40px;
      margin-bottom: 24px;
    }

    .page-title {
      font-size: 28px;
      font-weight: 700;
      color: #1e5ba8;
      text-align: center;
      margin-bottom: 8px;
    }

    .page-subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 32px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
    }

    .form-group input {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid #ddd;
      border-radius: 8px;
      font-size: 15px;
      transition: all 0.3s;
    }

    .form-group input:focus {
      outline: none;
      border-color: #1e5ba8;
      box-shadow: 0 0 0 3px rgba(30, 91, 168, 0.1);
    }

    .search-type-tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 24px;
    }

    .tab-btn {
      flex: 1;
      padding: 14px 20px;
      border: 2px solid #ddd;
      background: white;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .tab-btn:hover {
      border-color: #1e5ba8;
      color: #1e5ba8;
    }

    .tab-btn.active {
      background: #1e5ba8;
      border-color: #1e5ba8;
      color: white;
    }

    .submit-btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #1e5ba8, #3a7bd5);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
    }

    .submit-btn:disabled {
      background: #ccc;
      cursor: not-allowed;
      transform: none;
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: #1e5ba8;
      text-decoration: none;
      font-weight: 600;
      margin-bottom: 24px;
      transition: all 0.3s;
    }

    .back-link:hover {
      color: #3a7bd5;
    }

    /* Results Section */
    .results-section {
      display: none;
    }

    .results-section.show {
      display: block;
    }

    .no-results {
      text-align: center;
      padding: 40px;
      color: #666;
    }

    .no-results-icon {
      font-size: 48px;
      margin-bottom: 16px;
    }

    /* Appointment Card */
    .appointment-card {
      background: #f8fbff;
      border: 1px solid #e0e8f0;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 16px;
      cursor: pointer;
      transition: all 0.3s;
    }

    .appointment-card:hover {
      border-color: #1e5ba8;
      box-shadow: 0 4px 12px rgba(30, 91, 168, 0.15);
      transform: translateY(-2px);
    }

    .appointment-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
    }

    .appointment-id {
      font-size: 14px;
      color: #1e5ba8;
      font-weight: 600;
    }

    .appointment-status {
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-confirmed {
      background: #d4edda;
      color: #155724;
    }

    .status-pending {
      background: #fff3cd;
      color: #856404;
    }

    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }

    .status-completed {
      background: #cce5ff;
      color: #004085;
    }

    .appointment-info {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: #555;
    }

    .info-item .icon {
      font-size: 16px;
    }

    .info-item .label {
      color: #888;
    }

    /* Detail Modal */
    .detail-modal {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.5);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .detail-modal.show {
      display: flex;
    }

    .detail-box {
      background: white;
      border-radius: 16px;
      max-width: 600px;
      width: 100%;
      max-height: 90vh;
      overflow-y: auto;
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .detail-header {
      padding: 24px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .detail-header h3 {
      font-size: 20px;
      color: #1e5ba8;
    }

    .close-btn {
      background: none;
      border: none;
      font-size: 24px;
      cursor: pointer;
      color: #666;
      transition: color 0.3s;
    }

    .close-btn:hover {
      color: #333;
    }

    .detail-content {
      padding: 24px;
    }

    .detail-section {
      margin-bottom: 20px;
    }

    .detail-section-title {
      font-weight: 600;
      color: #1e5ba8;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 2px solid #e8f0f8;
    }

    .detail-row {
      display: flex;
      padding: 10px 0;
      border-bottom: 1px solid #f0f0f0;
    }

    .detail-row:last-child {
      border-bottom: none;
    }

    .detail-label {
      width: 140px;
      color: #666;
      font-size: 14px;
    }

    .detail-value {
      flex: 1;
      font-weight: 500;
      color: #333;
    }

    .detail-footer {
      padding: 20px 24px;
      border-top: 1px solid #eee;
      background: #f8fbff;
      border-radius: 0 0 16px 16px;
    }

    .contact-notice {
      background: #fff3cd;
      border: 1px solid #ffc107;
      border-radius: 10px;
      padding: 16px;
      margin-bottom: 16px;
    }

    .contact-notice h4 {
      color: #856404;
      margin-bottom: 8px;
      font-size: 15px;
    }

    .contact-notice p {
      color: #856404;
      font-size: 14px;
      line-height: 1.6;
    }

    .contact-info {
      display: flex;
      gap: 12px;
      margin-top: 12px;
      flex-wrap: wrap;
    }

    .contact-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      background: white;
      border: 1px solid #ddd;
      border-radius: 8px;
      color: #1e5ba8;
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .contact-link:hover {
      border-color: #1e5ba8;
      background: #f0f7ff;
    }

    .btn-login-promo {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #1e5ba8, #3a7bd5);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-login-promo:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(30, 91, 168, 0.3);
    }

    .loading {
      text-align: center;
      padding: 40px;
      color: #666;
    }

    .loading-spinner {
      width: 40px;
      height: 40px;
      border: 4px solid #e8f0f8;
      border-top-color: #1e5ba8;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin: 0 auto 16px;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 600px) {
      .search-type-tabs {
        flex-direction: column;
      }

      .appointment-info {
        grid-template-columns: 1fr;
      }

      .detail-row {
        flex-direction: column;
        gap: 4px;
      }

      .detail-label {
        width: auto;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <a href="/dat-lich" class="back-link">
      ← Quay lại biểu mẫu đặt lịch
    </a>

    <div class="card" id="searchCard">
      <h1 class="page-title">🔍 Tra cứu lịch hẹn</h1>
      <p class="page-subtitle">Nhập email hoặc số điện thoại để tra cứu lịch hẹn của bạn</p>

      <div class="search-type-tabs">
        <button class="tab-btn active" data-type="email" onclick="switchTab('email')">
          📧 Tìm theo Email
        </button>
        <button class="tab-btn" data-type="phone" onclick="switchTab('phone')">
          📱 Tìm theo Số điện thoại
        </button>
      </div>

      <form id="searchForm" onsubmit="searchAppointments(event)">
        <div class="form-group" id="emailGroup">
          <label>Email</label>
          <input type="email" id="searchEmail" placeholder="Nhập email đã đăng ký" />
        </div>
        <div class="form-group" id="phoneGroup" style="display:none;">
          <label>Số điện thoại</label>
          <input type="tel" id="searchPhone" placeholder="Nhập số điện thoại đã đăng ký" />
        </div>
        <button type="submit" class="submit-btn" id="searchBtn">Tra cứu</button>
      </form>
    </div>

    <!-- Results Section -->
    <div class="card results-section" id="resultsSection">
      <h2 style="color:#1e5ba8; margin-bottom:20px;">📋 Kết quả tra cứu</h2>
      
      <div class="loading" id="loadingIndicator" style="display:none;">
        <div class="loading-spinner"></div>
        <p>Đang tìm kiếm...</p>
      </div>

      <div class="no-results" id="noResults" style="display:none;">
        <div class="no-results-icon">📭</div>
        <h3>Không tìm thấy lịch hẹn</h3>
        <p>Vui lòng kiểm tra lại thông tin hoặc đặt lịch mới</p>
        <a href="/dat-lich" class="submit-btn" style="display:inline-block; margin-top:20px; text-decoration:none; padding:14px 32px;">
          Đặt lịch ngay
        </a>
      </div>

      <div id="appointmentsList"></div>
    </div>
  </div>

  <!-- Detail Modal -->
  <div class="detail-modal" id="detailModal">
    <div class="detail-box">
      <div class="detail-header">
        <h3>📅 Chi tiết lịch hẹn</h3>
        <button class="close-btn" onclick="closeDetailModal()">✕</button>
      </div>
      <div class="detail-content" id="detailContent">
        <!-- Content will be loaded here -->
      </div>
      <div class="detail-footer">
        <div class="contact-notice">
          <h4>💡 Bạn muốn chỉnh sửa hoặc biết thêm thông tin?</h4>
          <p>Vui lòng đăng nhập vào hệ thống hoặc liên hệ trực tiếp với bác sĩ/bệnh viện:</p>
          <div class="contact-info" id="contactInfo">
            <!-- Contact links will be added here -->
          </div>
        </div>
        <button class="btn-login-promo" onclick="window.location.href='/dang-nhap'">
          Đăng nhập để quản lý lịch hẹn →
        </button>
      </div>
    </div>
  </div>

  <script>
    const API_BASE = '/api';
    let currentSearchType = 'email';
    let appointmentsData = [];

    function switchTab(type) {
      currentSearchType = type;
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.type === type);
      });
      
      document.getElementById('emailGroup').style.display = type === 'email' ? 'block' : 'none';
      document.getElementById('phoneGroup').style.display = type === 'phone' ? 'block' : 'none';
      
      // Clear inputs
      document.getElementById('searchEmail').value = '';
      document.getElementById('searchPhone').value = '';
    }

    async function searchAppointments(e) {
      e.preventDefault();
      
      const email = document.getElementById('searchEmail').value.trim();
      const phone = document.getElementById('searchPhone').value.trim();
      
      if (currentSearchType === 'email' && !email) {
        alert('Vui lòng nhập email!');
        return;
      }
      
      if (currentSearchType === 'phone' && !phone) {
        alert('Vui lòng nhập số điện thoại!');
        return;
      }

      const resultsSection = document.getElementById('resultsSection');
      const loadingIndicator = document.getElementById('loadingIndicator');
      const noResults = document.getElementById('noResults');
      const appointmentsList = document.getElementById('appointmentsList');
      
      resultsSection.classList.add('show');
      loadingIndicator.style.display = 'block';
      noResults.style.display = 'none';
      appointmentsList.innerHTML = '';

      try {
        let queryParam = currentSearchType === 'email' 
          ? `email=${encodeURIComponent(email)}` 
          : `phone=${encodeURIComponent(phone)}`;
        
        const response = await fetch(`${API_BASE}/public/appointments/lookup?${queryParam}`);
        const result = await response.json();
        
        loadingIndicator.style.display = 'none';
        
        if (result.success && result.data && result.data.length > 0) {
          appointmentsData = result.data;
          renderAppointments(result.data);
        } else {
          noResults.style.display = 'block';
        }
      } catch (error) {
        console.error('Error searching appointments:', error);
        loadingIndicator.style.display = 'none';
        noResults.style.display = 'block';
      }
    }

    function renderAppointments(appointments) {
      const container = document.getElementById('appointmentsList');
      container.innerHTML = '';

      appointments.forEach((apt, index) => {
        const statusClass = getStatusClass(apt.status);
        const statusText = getStatusText(apt.status);
        const formattedDate = formatDate(apt.appointment_date);
        
        const card = document.createElement('div');
        card.className = 'appointment-card';
        card.onclick = () => showDetail(index);
        
        card.innerHTML = `
          <div class="appointment-header">
            <span class="appointment-id">Mã lịch hẹn: #${apt.id}</span>
            <span class="appointment-status ${statusClass}">${statusText}</span>
          </div>
          <div class="appointment-info">
            <div class="info-item">
              <span class="icon">👨‍⚕️</span>
              <span>${apt.doctor?.full_name || apt.doctor_name || 'Chưa xác định'}</span>
            </div>
            <div class="info-item">
              <span class="icon">📅</span>
              <span>${formattedDate}</span>
            </div>
            <div class="info-item">
              <span class="icon">⏰</span>
              <span>${apt.time_slot || apt.start_time || '--:--'}</span>
            </div>
            <div class="info-item">
              <span class="icon">🏥</span>
              <span>${apt.clinic?.name || apt.clinic_name || 'Bệnh viện Nam Sài Gòn'}</span>
            </div>
          </div>
        `;
        
        container.appendChild(card);
      });
    }

    function showDetail(index) {
      const apt = appointmentsData[index];
      if (!apt) return;

      const statusClass = getStatusClass(apt.status);
      const statusText = getStatusText(apt.status);
      const formattedDate = formatDate(apt.appointment_date);
      
      const doctorEmail = apt.doctor?.email || 'uytinso1vn@gmail.com';
      const doctorPhone = apt.doctor?.phone || '0123456789';
      
      document.getElementById('detailContent').innerHTML = `
        <div class="detail-section">
          <div class="detail-section-title">Thông tin lịch hẹn</div>
          <div class="detail-row">
            <span class="detail-label">Mã lịch hẹn:</span>
            <span class="detail-value">#${apt.id}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Trạng thái:</span>
            <span class="detail-value"><span class="appointment-status ${statusClass}">${statusText}</span></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Ngày khám:</span>
            <span class="detail-value">${formattedDate}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Giờ khám:</span>
            <span class="detail-value">${apt.time_slot || apt.start_time || '--:--'}</span>
          </div>
        </div>

        <div class="detail-section">
          <div class="detail-section-title">Thông tin bác sĩ</div>
          <div class="detail-row">
            <span class="detail-label">Bác sĩ:</span>
            <span class="detail-value">${apt.doctor?.degree || ''} ${apt.doctor?.full_name || apt.doctor_name || 'Chưa xác định'}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Chuyên khoa:</span>
            <span class="detail-value">${apt.specialization?.name || apt.doctor?.specialization?.name || 'Đa khoa'}</span>
          </div>
        </div>

        <div class="detail-section">
          <div class="detail-section-title">Địa điểm khám</div>
          <div class="detail-row">
            <span class="detail-label">Phòng khám:</span>
            <span class="detail-value">${apt.clinic?.name || apt.clinic_name || 'Bệnh viện Nam Sài Gòn'}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Địa chỉ:</span>
            <span class="detail-value">${apt.clinic?.address || 'Liên hệ để biết thêm chi tiết'}</span>
          </div>
        </div>

        <div class="detail-section">
          <div class="detail-section-title">Chi phí</div>
          <div class="detail-row">
            <span class="detail-label">Tổng chi phí:</span>
            <span class="detail-value" style="color:#1e5ba8; font-weight:700;">${formatCurrency(apt.fee_amount || 0)}</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Thanh toán:</span>
            <span class="detail-value">${apt.payment_method === 'cash' ? 'Tiền mặt' : 'Chuyển khoản'}</span>
          </div>
        </div>

        ${apt.notes ? `
        <div class="detail-section">
          <div class="detail-section-title">Ghi chú</div>
          <p style="color:#666; line-height:1.6;">${apt.notes}</p>
        </div>
        ` : ''}
      `;

      document.getElementById('contactInfo').innerHTML = `
        <a href="mailto:${doctorEmail}" class="contact-link">
          📧 ${doctorEmail}
        </a>
        <a href="tel:${doctorPhone}" class="contact-link">
          📞 ${doctorPhone}
        </a>
      `;

      document.getElementById('detailModal').classList.add('show');
    }

    function closeDetailModal() {
      document.getElementById('detailModal').classList.remove('show');
    }

    function getStatusClass(status) {
      const classes = {
        'confirmed': 'status-confirmed',
        'pending': 'status-pending',
        'cancelled': 'status-cancelled',
        'completed': 'status-completed'
      };
      return classes[status] || 'status-pending';
    }

    function getStatusText(status) {
      const texts = {
        'confirmed': 'Đã xác nhận',
        'pending': 'Chờ xác nhận',
        'cancelled': 'Đã hủy',
        'completed': 'Hoàn thành'
      };
      return texts[status] || 'Chờ xác nhận';
    }

    function formatDate(dateStr) {
      if (!dateStr) return '--/--/----';
      const date = new Date(dateStr);
      return date.toLocaleDateString('vi-VN', { 
        weekday: 'long',
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric' 
      });
    }

    function formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    // Close modal when clicking outside
    document.getElementById('detailModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeDetailModal();
      }
    });
  </script>
</body>
</html>
