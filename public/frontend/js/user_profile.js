
  // Mở modal sửa phương thức thanh toán (đặt trong IIFE để dùng được state)
  function openEditPaymentModal(id) {
    const method = (state.dashboard?.payment_methods || []).find(m => m.id === id);
    if (!method) return;
    state.editingPaymentId = id;
    const type = mapMethodToType(method.method_type || method.type);
    byId('editPaymentType').value = type;
    updatePaymentForm('edit');
    if (type === 'card') {
      byId('editCardNumber').value = method.card_number || '';
      byId('editCardHolder').value = method.card_holder || '';
      byId('editExpiryMonth').value = method.expiry_month || '';
      byId('editExpiryYear').value = method.expiry_year || '';
      byId('editCVV').value = '';
    } else if (type === 'wallet') {
      byId('editWalletNumber').value = method.wallet_number || '';
      byId('editWalletType').value = method.wallet_type || '';
    } else if (type === 'bank') {
      byId('editBankAccount').value = method.bank_account || '';
      byId('editBankName').value = method.bank_name || '';
    }
    openModal('editPaymentModal');
  }

  // Xóa phương thức thanh toán (đặt trong IIFE để dùng được state)
  async function deletePaymentMethod(id) {
    if (!confirm('Bạn có chắc muốn xóa phương thức này?')) return;
    try {
      const resp = await window.AuthAPI.apiFetch(`/profile/payment/${id}`, { method: 'DELETE' });
      await ensureOk(resp);
      await refreshDashboard();
      alert('Đã xóa phương thức thanh toán');
    } catch (err) {
      alert(err.message || 'Không thể xóa phương thức');
    }
  }
(function () {
  'use strict';

  const state = {
    me: null,
    dashboard: null,
    appointments: [],
    appointmentFilter: 'all',
    messages: [],
    publicReviews: [],
    editingPaymentId: null,
    forumPosts: [],
    forumFilter: 'mine',
    postComments: new Map(),
    likedPosts: new Set(),
    likedComments: new Set()
  };
  const dom = {};
  const numberFormatter = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });
  const dateFormatter = new Intl.DateTimeFormat('vi-VN');

  function byId(id) {
    return document.getElementById(id);
  }

  async function ensureOk(resp, parseJson) {
    const data = await resp.json().catch(() => ({}));
    if (!resp.ok) {
      throw new Error(data.message || 'Yêu cầu thất bại');
    }
    return parseJson ? data : resp;
  }

  function formatDate(value) {
    if (!value) return '--';
    const d = new Date(value);
    return isNaN(d) ? value : dateFormatter.format(d);
  }

  function formatCurrency(amount) {
    try {
      return numberFormatter.format(Number(amount || 0));
    } catch (_) {
      return `${amount || 0}đ`;
    }
  }

  function setText(id, text) {
    const el = byId(id);
    if (el) el.textContent = text;
  }

  function emptyState(message) {
    return `<div class="empty-state">${escapeHtml(message)}</div>`;
  }

  function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    }[ch] || ch));
  }

  // Status label for appointments
  function statusLabel(status) {
    const labels = {
      'available': 'Đã xác nhận', // Lịch có patient = đã xác nhận
      'booked': 'Đã đặt',
      'pending_confirmation': 'Chờ xác nhận',
      'confirmed': 'Đã xác nhận',
      'completed': 'Đã hoàn thành',
      'cancelled': 'Đã hủy',
      'missed': 'Vắng mặt'
    };
    return labels[(status || '').toLowerCase()] || status || 'Không xác định';
  }

  // Status class for styling
  function statusClass(status) {
    const classes = {
      'available': 'status-confirmed', // Lịch có patient = style như confirmed
      'booked': 'status-booked',
      'pending_confirmation': 'status-pending',
      'confirmed': 'status-confirmed',
      'completed': 'status-completed',
      'cancelled': 'status-cancelled',
      'missed': 'status-cancelled'
    };
    return classes[(status || '').toLowerCase()] || 'status-default';
  }

  function updatePaymentForm(mode) {
    const prefix = mode === 'edit' ? 'edit' : 'add';
    const typeSelect = byId(`${prefix}PaymentType`);
    if (!typeSelect) return;
    const type = typeSelect.value;
    const cardGroups = ['CardNumberGroup', 'CardHolderGroup', 'CardExpiryGroup'];
    const walletGroups = ['WalletNumberGroup', 'WalletTypeGroup'];
    const bankGroups = ['BankAccountGroup', 'BankNameGroup'];
    const toggle = (groupList, visible) => {
      groupList.forEach(groupId => {
        const el = byId(`${prefix}${groupId}`);
        if (el) el.style.display = visible ? '' : 'none';
      });
    };
    toggle(cardGroups, type === 'card');
    toggle(walletGroups, type === 'wallet');
    toggle(bankGroups, type === 'bank');
  }

  function waitForAuthAPI(callback) {
    if (window.AuthAPI) {
      callback();
      return;
    }
    const start = Date.now();
    const timer = setInterval(() => {
      if (window.AuthAPI) {
        clearInterval(timer);
        callback();
      } else if (Date.now() - start > 5000) {
        clearInterval(timer);
        console.error('AuthAPI unavailable. Ensure auth.js is loaded before user_profile.js');
      }
    }, 50);
  }

  waitForAuthAPI(() => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
      init();
    }
  });

  function ensureAuthAPI() {
    if (!window.AuthAPI) {
      console.error('AuthAPI unavailable. Ensure auth.js is loaded before user_profile.js');
      alert('Không tìm thấy AuthAPI. Vui lòng tải lại trang.');
      return false;
    }
    return true;
  }

  function init() {
    if (!ensureAuthAPI()) return;
    cacheDom();
    bindStaticHandlers();
    updatePaymentForm('add');
    updatePaymentForm('edit');
    loadProfileData();
  }

  function cacheDom() {
    Object.assign(dom, {
      avatarImg: byId('avatar-img'),
      avatarInput: byId('avatar-input'),
      avatarForm: byId('avatar-upload-form'),
      avatarUploadBtn: byId('avatar-upload-btn'),
      avatarSaveBtn: byId('avatar-save-btn'),
      editProfileModal: byId('editProfileModal'),
      editProfileForm: byId('editProfileForm'),
      openEditProfileBtn: byId('openEditProfileBtn'),
      appointmentList: byId('appointment-list'),
      appointmentFilters: byId('appointment-filters'),
      medicalHistoryList: byId('medical-history-list'),
      notificationList: byId('notification-list'),
      paymentMethods: byId('payment-methods'),
      transactionList: byId('transaction-list'),
      reviewList: byId('review-list'),
      reviewForm: byId('submitReviewForm'),
      reviewStars: byId('reviewStars'),
      reviewRating: byId('reviewRating'),
      reviewAppointment: byId('reviewAppointment'),
      reviewComment: byId('reviewComment'),
      addPaymentBtn: byId('addPaymentBtn'),
      addPaymentModal: byId('addPaymentModal'),
      addPaymentForm: byId('addPaymentForm'),
      editPaymentModal: byId('editPaymentModal'),
      editPaymentForm: byId('editPaymentForm'),
      sections: document.querySelectorAll('.content-section'),
      navItems: document.querySelectorAll('.nav-item'),
      scrollBtn: byId('scrollToTopBtn'),
      scrollProgress: byId('scrollProgress'),
      filterDoctorSelect: byId('filterDoctor'),
      filterRatingSelect: byId('filterRating'),
      publicReviewList: byId('public-review-list'),
      doctorsList: byId('doctorsList'),
      chatMessagesArea: byId('chatMessagesArea'),
      quickReplyButtons: document.querySelectorAll('.quick-reply-btn'),
      messageInput: byId('messageInput'),
      sendMessageBtn: byId('sendMessageBtn'),
      forumTabs: byId('forumTabs'),
      forumPosts: byId('forum-posts'),
      openQuestionBtn: byId('openQuestionBtn'),
      questionForm: byId('questionForm'),
      markAllReadBtn: byId('markAllReadBtn'),
      deleteAllNotifBtn: byId('deleteAllNotifBtn')
    });
  }

  function bindStaticHandlers() {
    bindNavigation();
    bindAppointmentFilters();
    bindAvatarUpload();
    bindProfileForm();
    bindReviewForm();
    bindModalCloseHandlers();
    bindAddPaymentFlow();
    bindScrollHelpers();
    bindPublicReviewFilters();
    bindChatInteractions();
    bindForumInteractions();
    
    if (dom.markAllReadBtn) dom.markAllReadBtn.addEventListener('click', () => markNotificationRead('all'));
    if (dom.deleteAllNotifBtn) dom.deleteAllNotifBtn.addEventListener('click', () => {
        if(confirm('Bạn có chắc muốn xóa tất cả thông báo?')) deleteNotification('all');
    });
  }

  function bindNavigation() {
    dom.navItems?.forEach(item => {
      item.addEventListener('click', () => showSection(item.dataset.section));
      item.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          showSection(item.dataset.section);
        }
      });
    });
  }

  function bindAppointmentFilters() {
    if (!dom.appointmentFilters) return;
    dom.appointmentFilters.addEventListener('click', e => {
      const btn = e.target.closest('.tab-btn');
      if (!btn) return;
      dom.appointmentFilters.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      applyAppointmentFilter(btn.dataset.filter || 'all');
    });
  }

  function bindAvatarUpload() {
    if (!dom.avatarForm || !dom.avatarUploadBtn || !dom.avatarInput || !dom.avatarSaveBtn) return;
    dom.avatarUploadBtn.addEventListener('click', () => dom.avatarInput.click());
    dom.avatarInput.addEventListener('change', () => {
      const file = dom.avatarInput.files?.[0];
      if (!file) return;
      if (!file.type.startsWith('image/')) {
        alert('Chỉ hỗ trợ tải lên hình ảnh');
        dom.avatarInput.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = e => { if (dom.avatarImg) dom.avatarImg.src = e.target.result; };
      reader.readAsDataURL(file);
      dom.avatarSaveBtn.style.display = 'inline-block';
    });
    dom.avatarForm.addEventListener('submit', async e => {
      e.preventDefault();
      if (!dom.avatarInput.files || !dom.avatarInput.files[0]) return;
      try {
        const formData = new FormData();
        formData.append('avatar', dom.avatarInput.files[0]);
        const resp = await window.AuthAPI.apiFetch('/profile/avatar', {
          method: 'POST',
          body: formData,
          headers: {}
        });
        const data = await resp.json().catch(() => ({}));
        if (!resp.ok) throw new Error(data.message || 'Upload thất bại');
        if (dom.avatarImg && data.avatar_url) dom.avatarImg.src = data.avatar_url;
        dom.avatarInput.value = '';
        dom.avatarSaveBtn.style.display = 'none';
        alert('Cập nhật ảnh đại diện thành công');
      } catch (err) {
        alert(err.message || 'Không thể cập nhật ảnh');
      }
    });
  }

  function bindProfileForm() {
    if (dom.openEditProfileBtn) {
      dom.openEditProfileBtn.addEventListener('click', () => openModal('editProfileModal', prefillProfileForm));
    }
    if (dom.editProfileForm) {
      dom.editProfileForm.addEventListener('submit', async e => {
        e.preventDefault();
        const payload = {
          full_name: byId('editName')?.value.trim() || null,
          gender: normalizeGenderValue(byId('editGender')?.value),
          dob: byId('editDob')?.value || null,
          phone: byId('editPhone')?.value.trim() || null,
          address: byId('editAddress')?.value.trim() || null
        };
        try {
          const resp = await window.AuthAPI.apiFetch('/profile/me', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
          });
          await ensureOk(resp);
          state.me = { ...state.me, ...payload };
          bindUserProfile(state.me);
          closeModal('editProfileModal');
          alert('Đã cập nhật thông tin cá nhân');
        } catch (err) {
          alert(err.message || 'Không thể cập nhật thông tin');
        }
      });
    }
  }

  function bindReviewForm() {
    if (dom.reviewForm) {
      dom.reviewForm.addEventListener('submit', submitReviewForm);
    }
    if (dom.reviewStars && dom.reviewRating) {
      dom.reviewStars.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', () => {
          const value = Number(star.dataset.value);
          dom.reviewRating.value = value;
          dom.reviewStars.querySelectorAll('.star').forEach(s => s.classList.toggle('active', Number(s.dataset.value) <= value));
        });
      });
    }
  }

  // Submit review form handler
  async function submitReviewForm(e) {
    e.preventDefault();
    const appointmentId = dom.reviewAppointment?.value;
    const rating = Number(dom.reviewRating?.value || 0);
    const comment = dom.reviewComment?.value || '';

    if (!appointmentId) {
      alert('Vui lòng chọn lịch hẹn cần đánh giá');
      return;
    }
    if (rating < 1) {
      alert('Vui lòng chọn số sao đánh giá');
      return;
    }

    try {
      const resp = await window.AuthAPI.apiFetch('/profile/review', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          appointment_id: appointmentId,
          rating: rating,
          comment: comment
        })
      });
      const data = await resp.json().catch(() => ({}));
      if (!resp.ok) {
        throw new Error(data.message || 'Không thể gửi đánh giá');
      }
      alert(data.message || 'Đã gửi đánh giá thành công!');
      // Reset form
      dom.reviewForm?.reset();
      dom.reviewRating.value = 0;
      dom.reviewStars?.querySelectorAll('.star').forEach(s => s.classList.remove('active'));
      byId('reviewDoctorAvatar').style.display = 'none';
      byId('reviewDoctorName').textContent = '';
      // Refresh dashboard to update reviewable list
      await refreshDashboard();
    } catch (err) {
      alert(err.message || 'Lỗi gửi đánh giá');
    }
  }

  function bindModalCloseHandlers() {
    document.addEventListener('click', e => {
      const target = e.target;
      if (target.dataset?.closeModal) {
        closeModal(target.dataset.closeModal);
      }
      if (target.classList.contains('modal')) {
        target.classList.remove('active');
      }
    });
  }

  function bindAddPaymentFlow() {
    dom.addPaymentBtn?.addEventListener('click', () => handleAddPayment());
    dom.addPaymentForm?.addEventListener('submit', async e => {
      e.preventDefault();
      try {
        const payload = collectPaymentPayload('add');
        const resp = await window.AuthAPI.apiFetch('/profile/payment', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        await ensureOk(resp);
        closeModal('addPaymentModal');
        await refreshDashboard();
        alert('Đã thêm phương thức thanh toán');
      } catch (err) {
        alert(err.message || 'Không thể thêm phương thức');
      }
    });
    dom.editPaymentForm?.addEventListener('submit', async e => {
      e.preventDefault();
      if (!state.editingPaymentId) return;
      try {
        const payload = collectPaymentPayload('edit');
        const resp = await window.AuthAPI.apiFetch(`/profile/payment/${state.editingPaymentId}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        await ensureOk(resp);
        closeModal('editPaymentModal');
        state.editingPaymentId = null;
        await refreshDashboard();
        alert('Đã cập nhật phương thức thanh toán');
      } catch (err) {
        alert(err.message || 'Không thể cập nhật phương thức');
      }
    });
  }

  function bindScrollHelpers() {
    if (dom.scrollBtn) {
      dom.scrollBtn.addEventListener('click', scrollToTopFast);
    }
    window.addEventListener('scroll', updateScrollIndicators);
    updateScrollIndicators();
  }

  function bindPublicReviewFilters() {
    dom.filterDoctorSelect?.addEventListener('change', filterPublicReviews);
    dom.filterRatingSelect?.addEventListener('change', filterPublicReviews);
  }

  function bindChatInteractions() {
    dom.quickReplyButtons?.forEach(btn => {
      btn.addEventListener('click', () => insertQuickReply(btn.dataset.message));
    });
    dom.sendMessageBtn?.addEventListener('click', sendMessage);
    dom.messageInput?.addEventListener('keydown', e => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
      }
    });
    renderMessages([]);
  }

  function bindForumInteractions() {
    dom.openQuestionBtn?.addEventListener('click', () => openModal('questionModal'));
    dom.questionForm?.addEventListener('submit', submitQuestionForm);
    dom.forumTabs?.addEventListener('click', e => {
      const btn = e.target.closest('.tab-btn');
      if (!btn) return;
      dom.forumTabs.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
      btn.classList.add('active');
      state.forumFilter = btn.dataset.filter || 'all';
      renderForumPosts();
    });
    dom.forumPosts?.addEventListener('click', handleForumPostClick);
    // Lắng nghe submit bình luận qua event delegation
    dom.forumPosts?.addEventListener('submit', e => {
      const form = e.target.closest('form.comment-form');
      if (!form) return;
      submitComment(e);
    });
    
    // Report modal event listeners
    const submitReportBtn = byId('submitReportBtn');
    if (submitReportBtn) {
      submitReportBtn.addEventListener('click', submitReport);
    }
    
    // Report reason option selection
    document.querySelectorAll('.report-reason-option').forEach(option => {
      option.addEventListener('click', () => {
        document.querySelectorAll('.report-reason-option').forEach(o => o.classList.remove('selected'));
        option.classList.add('selected');
        const radio = option.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
      });
    });
  }

  async function loadProfileData() {
    try {
      const [meResp, dashboardResp] = await Promise.all([
        window.AuthAPI.apiFetch('/profile/me'),
        window.AuthAPI.apiFetch('/profile/dashboard')
      ]);
      const me = await ensureOk(meResp, true);
      
      // Check user type - redirect if not USER
      if (me.type === 'DOCTOR') {
        window.location.href = '/bac-si/ho-so';
        return;
      } else if (me.type === 'ADMIN') {
        window.location.href = '/quan-tri';
        return;
      }
      
      const dashboard = await ensureOk(dashboardResp, true);
      state.me = me;
      state.dashboard = dashboard;
      state.appointments = dashboard.appointments || [];
      state.messages = dashboard.messages || [];
      state.publicReviews = dashboard.public_reviews || [];
      state.forumPosts = dashboard.forum_posts || [];
      hydrateLikedPosts(dashboard.user_likes || []);
      bindUserProfile(me);
      renderAllSections(dashboard);
    } catch (err) {
      console.error('Profile init failed', err);
      // Xóa token khỏi sessionStorage để tránh lặp lỗi
      if (window.sessionStorage) {
        window.sessionStorage.removeItem('access_token');
        window.sessionStorage.removeItem('refresh_token');
        window.sessionStorage.removeItem('session_id');
      }
      // Redirect to login
      window.location.href = '/dang-nhap';
    }
  }

  async function refreshDashboard() {
    try {
      const resp = await window.AuthAPI.apiFetch('/profile/dashboard');
      const data = await ensureOk(resp, true);
      state.dashboard = data;
      state.appointments = data.appointments || [];
      state.forumPosts = data.forum_posts || [];
      hydrateLikedPosts(data.user_likes || []);
      renderAllSections(data);
    } catch (err) {
      console.error('Không thể refresh dashboard', err);
    }
  }

  function renderAllSections(dashboard) {
    renderAppointments(dashboard.appointments || []);
    renderMedicalHistory(dashboard.medical_notes || []);
    renderNotifications(dashboard.notifications || []);
    renderPaymentMethods(dashboard.payment_methods || []);
    renderTransactions(dashboard.transactions || []);
    renderReviews(dashboard.reviews || []);
    populateReviewAppointments(dashboard.reviewable_appointments || []);
    renderMessages(state.messages || []);
    renderForumPosts(dashboard.forum_posts || []);
    renderPublicReviews(dashboard.public_reviews || []);
  }

  function bindUserProfile(user) {
    setText('pf-name', user.full_name || '--');
    setText('pf-display-name', user.full_name || '--');
    setText('pf-role', roleLabel(user.type));
    setText('pf-gender', formatGender(user.gender));
    setText('pf-dob', formatDate(user.dob));
    setText('pf-phone', user.phone || '--');
    setText('pf-email', user.email || '--');
    setText('pf-address', user.address || '--');
    const avatar = user.avatar_url || '/frontend/img/logocanhan.jpg';
    if (dom.avatarImg) dom.avatarImg.src = avatar;
    const emailEl = byId('pf-email');
    if (emailEl) emailEl.title = 'Email gắn với tài khoản và không thể thay đổi';
    
    // Update forum container với thông tin người dùng thực
    const forumContainer = byId('forumContainer');
    if (forumContainer) {
      forumContainer.dataset.userId = user.id || '';
      forumContainer.dataset.userName = user.full_name || 'Người dùng';
      forumContainer.dataset.userAvatar = avatar;
      forumContainer.dataset.userType = user.type === 'DOCTOR' ? 'doctor' : 'patient';
    }
    
    // Khởi tạo forum sau khi đã có thông tin user
    if (typeof window.initForumSync === 'function') {
      window.initForumSync();
    }
  }

  function roleLabel(type) {
    switch (type) {
      case 'USER': return 'Người dùng';
      case 'DOCTOR': return 'Bác sĩ';
      case 'ADMIN': return 'Quản trị viên';
      default: return 'Không xác định';
    }
  }

  function renderAppointments(list) {
    state.appointments = Array.isArray(list) ? list : [];
    applyAppointmentFilter(state.appointmentFilter);
  }

  function applyAppointmentFilter(filter) {
    state.appointmentFilter = filter;
    if (!dom.appointmentList) return;
    const now = new Date();
    const filtered = state.appointments.filter(item => {
      switch (filter) {
        case 'upcoming':
          return new Date(item.appointment_date) >= now && !['completed', 'cancelled'].includes((item.status || '').toLowerCase());
        case 'pending_confirmation':
          return (item.status || '').toLowerCase() === 'pending_confirmation';
        case 'confirmed':
          return (item.status || '').toLowerCase() === 'confirmed';
        case 'completed':
          return (item.status || '').toLowerCase() === 'completed';
        case 'cancelled':
          return (item.status || '').toLowerCase() === 'cancelled';
        default:
          return true;
      }
    });
    if (!filtered.length) {
      dom.appointmentList.innerHTML = emptyState('Chưa có lịch hẹn');
      return;
    }
    dom.appointmentList.innerHTML = '';
    filtered.forEach(item => {
      const card = document.createElement('div');
      card.className = 'appointment-card';
      card.style.cursor = 'pointer';
      const isPending = (item.status || '').toLowerCase() === 'pending_confirmation';
      const doctorAvatar = item.doctor_avatar || '/frontend/img/logocanhan.jpg';
      
      // Format time display
      let timeDisplay = '';
      if (item.start_time && item.end_time) {
        const startTime = item.start_time.substring(0, 5);
        const endTime = item.end_time.substring(0, 5);
        timeDisplay = `${startTime} - ${endTime}`;
      } else if (item.time_slot) {
        timeDisplay = item.time_slot;
      } else if (item.start_time) {
        timeDisplay = item.start_time.substring(0, 5);
      }
      
      card.innerHTML = `
        <div class="appointment-header">
          <div class="doctor-info-brief" style="display: flex; align-items: center; gap: 10px;">
            <img src="${escapeHtml(doctorAvatar)}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
            <div class="doctor-name"><i class="fas fa-user-md"></i> ${escapeHtml(item.doctor_name || 'Chưa rõ bác sĩ')}</div>
          </div>
          <span class="appointment-status ${statusClass(item.status)}">${statusLabel(item.status)}</span>
          ${item.payment_status === 'PAID' ? '<span class="badge badge-paid">PAID</span>' : ''}
        </div>
        <div class="appointment-details">
          <div class="detail-item"><i class="fas fa-stethoscope"></i><span>${escapeHtml(item.doctor_specialization || item.clinic_name || 'Đang cập nhật')}</span></div>
          <div class="detail-item"><i class="fas fa-clock"></i><span>${formatDate(item.appointment_date)} - ${escapeHtml(timeDisplay)}</span></div>
          <div class="detail-item"><i class="fas fa-map-marker-alt"></i><span>${escapeHtml(item.clinic_address || 'Đang cập nhật')}</span></div>
          ${item.fee_amount > 0 ? `
          <div class="detail-item">
            <i class="fas fa-money-bill-wave"></i>
            <span>
              ${formatCurrency(item.fee_amount)}
              <span class="var-tooltip" title="Đã tính phí VAR">
                <i class="fas fa-question-circle"></i>
              </span>
            </span>
          </div>
          ` : ''}
          <div class="detail-item"><i class="fas fa-info-circle"></i><span>${escapeHtml(item.notes || 'Lịch hẹn đã được xác nhận')}</span></div>
        </div>
        <div class="appointment-actions" onclick="event.stopPropagation();">
          ${isPending ? `
            <button class="btn btn-success btn-confirm-appointment" data-id="${item.id}">
              <i class="fas fa-check"></i> Xác nhận lịch hẹn
            </button>
            <button class="btn btn-danger btn-cancel-appointment" data-id="${item.id}">
              <i class="fas fa-times"></i> Hủy
            </button>
          ` : buildPaymentActions(item)}
        </div>
      `;
      // Click vào card để xem chi tiết (ngoại trừ nút actions)
      card.addEventListener('click', () => showAppointmentDetail(item));
      dom.appointmentList.appendChild(card);
    });
    
    // Bind confirm/cancel buttons
    bindAppointmentActions();
    // Bind payment buttons
    bindPaymentActions();
  }

  function showAppointmentDetail(item) {
    const modal = byId('appointmentModal');
    const modalBody = byId('modalBody');
    if (!modal || !modalBody) return;
    
    const doctorAvatar = item.doctor_avatar || '/frontend/img/logocanhan.jpg';
    const rating = parseFloat(item.doctor_rating) || 0;
    const ratingStars = '★'.repeat(Math.round(rating)) + '☆'.repeat(5 - Math.round(rating));
    
    // Format time display
    let timeDisplay = '';
    if (item.start_time && item.end_time) {
      const startTime = item.start_time.substring(0, 5);
      const endTime = item.end_time.substring(0, 5);
      timeDisplay = `${startTime} - ${endTime}`;
    } else if (item.time_slot) {
      timeDisplay = item.time_slot;
    } else if (item.start_time) {
      timeDisplay = item.start_time.substring(0, 5);
    }
    
    // Payment status label with badge
    const paymentStatus = (item.payment_status || '').toUpperCase();
    let paymentLabel = '';
    let paymentBadge = '';
    
    switch(paymentStatus) {
      case 'PAID':
        paymentLabel = '<span class="status-confirmed">Đã thanh toán</span>';
        break;
      case 'PENDING_APPROVAL':
        paymentLabel = '<span class="status-pending">Chờ duyệt thanh toán</span>';
        paymentBadge = '<span style="background: #ffc107; color: #333; padding: 2px 6px; border-radius: 4px; font-size: 11px; margin-left: 5px;"><i class="fas fa-clock"></i> Đợi xác nhận</span>';
        break;
      case 'REFUND_PENDING':
        paymentLabel = '<span class="status-confirmed">Đã thanh toán</span>';
        paymentBadge = '<span style="background: #ffc107; color: #333; padding: 2px 6px; border-radius: 4px; font-size: 11px; margin-left: 5px;"><i class="fas fa-clock"></i> Đợi duyệt hoàn tiền</span>';
        break;
      case 'REFUNDED':
        paymentLabel = '<span class="status-info">Đã hoàn tiền</span>';
        break;
      default:
        paymentLabel = '<span class="status-pending">Chưa thanh toán</span>';
    }
    
    // Build action buttons
    let actionButtons = '';
    if (paymentStatus === 'PAID' && (item.status === 'confirmed' || item.status === 'available')) {
      actionButtons = `
        <div style="margin-top: 20px; text-align: center;">
          <button class="btn btn-outline-danger btn-request-refund-modal" data-id="${item.id}" data-amount="${item.fee_amount || 0}">
            <i class="fas fa-undo"></i> Yêu cầu hoàn tiền
          </button>
        </div>
      `;
    } else if (paymentStatus === 'UNPAID' && (item.status === 'confirmed' || item.status === 'available')) {
      actionButtons = `
        <div style="margin-top: 20px; text-align: center;">
          <button class="btn btn-primary btn-pay-modal" data-id="${item.id}" data-amount="${item.fee_amount || 200000}">
            <i class="fas fa-credit-card"></i> Thanh toán ngay
          </button>
        </div>
      `;
    }
    
    modalBody.innerHTML = `
      <div class="appointment-detail-modal">
        <div class="doctor-profile-section" style="display: flex; gap: 20px; margin-bottom: 20px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; color: white;">
          <img src="${escapeHtml(doctorAvatar)}" alt="Avatar" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid white;">
          <div class="doctor-info" style="flex: 1;">
            <h3 style="margin: 0 0 5px 0; font-size: 1.4em;">${escapeHtml(item.doctor_name || 'Chưa rõ bác sĩ')}</h3>
            <p style="margin: 0 0 5px 0; opacity: 0.9;"><i class="fas fa-stethoscope"></i> ${escapeHtml(item.doctor_specialization || 'Chuyên khoa')}</p>
            <p style="margin: 0 0 5px 0; opacity: 0.9;"><i class="fas fa-graduation-cap"></i> ${escapeHtml(item.doctor_degree || 'Bác sĩ')}</p>
            <p style="margin: 0 0 5px 0; opacity: 0.9;"><i class="fas fa-briefcase"></i> ${item.doctor_experience ? item.doctor_experience + ' năm kinh nghiệm' : 'Kinh nghiệm'}</p>
            <p style="margin: 0; font-size: 1.1em;"><span style="color: gold;">${ratingStars}</span> ${rating > 0 ? rating.toFixed(1) : 'Chưa có đánh giá'}</p>
          </div>
          <div style="text-align: right;">
            <div style="font-size: 1.5em; font-weight: bold;">${formatCurrency(item.fee_amount || 0)}</div>
            ${paymentStatus === 'PAID' ? '<span style="background: #28a745; padding: 4px 12px; border-radius: 20px; font-size: 12px;">PAID</span>' : ''}
            ${paymentStatus === 'PENDING_APPROVAL' ? '<span style="background: #ffc107; color: #333; padding: 4px 12px; border-radius: 20px; font-size: 12px;">PENDING</span>' : ''}
            ${paymentStatus === 'REFUND_PENDING' ? '<span style="background: #17a2b8; padding: 4px 12px; border-radius: 20px; font-size: 12px;">REFUND</span>' : ''}
          </div>
        </div>
        
        <div class="appointment-info-section" style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
          <h4 style="margin: 0 0 15px 0; color: #667eea;"><i class="fas fa-calendar-alt"></i> Thông tin lịch hẹn</h4>
          <div style="display: grid; gap: 10px;">
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-clock" style="color: #667eea; width: 20px;"></i>
              <span><strong>Thời gian:</strong> ${formatDate(item.appointment_date)} - ${escapeHtml(timeDisplay)}</span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-hospital" style="color: #667eea; width: 20px;"></i>
              <span><strong>Phòng khám:</strong> ${escapeHtml(item.clinic_name || 'Đang cập nhật')}</span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-map-marker-alt" style="color: #667eea; width: 20px;"></i>
              <span><strong>Địa chỉ:</strong> ${escapeHtml(item.clinic_address || 'Đang cập nhật')}</span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-info-circle" style="color: #667eea; width: 20px;"></i>
              <span><strong>Trạng thái:</strong> <span class="${statusClass(item.status)}">${statusLabel(item.status)}</span></span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-money-bill-wave" style="color: #667eea; width: 20px;"></i>
              <span>
                <strong>Phí khám:</strong> ${formatCurrency(item.fee_amount || 0)}
                ${item.fee_amount > 0 ? '<span class="var-tooltip" title="Đã tính phí VAR"><i class="fas fa-question-circle" style="color: #667eea; cursor: help;"></i></span>' : ''}
              </span>
            </div>
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-credit-card" style="color: #667eea; width: 20px;"></i>
              <span><strong>Thanh toán:</strong> ${paymentLabel}${paymentBadge}</span>
            </div>
            ${item.payment_method ? `
            <div style="display: flex; gap: 10px; align-items: center;">
              <i class="fas fa-wallet" style="color: #667eea; width: 20px;"></i>
              <span><strong>Phương thức:</strong> ${escapeHtml(item.payment_method)}</span>
            </div>
            ` : ''}
          </div>
        </div>
        
        <div class="contact-section" style="margin-top: 20px; padding: 15px; background: #e8f4f8; border-radius: 12px;">
          <h4 style="margin: 0 0 10px 0; color: #17a2b8;"><i class="fas fa-phone-alt"></i> Liên hệ bác sĩ</h4>
          <div style="display: grid; gap: 8px;">
            ${item.doctor_phone ? `<div><i class="fas fa-phone" style="color: #28a745; width: 20px;"></i> <a href="tel:${escapeHtml(item.doctor_phone)}">${escapeHtml(item.doctor_phone)}</a></div>` : ''}
            ${item.doctor_email ? `<div><i class="fas fa-envelope" style="color: #dc3545; width: 20px;"></i> <a href="mailto:${escapeHtml(item.doctor_email)}">${escapeHtml(item.doctor_email)}</a></div>` : ''}
          </div>
        </div>
        
        ${actionButtons}
      </div>
    `;
    
    modal.classList.add('active');
    
    // Bind action buttons in modal
    const payBtn = modalBody.querySelector('.btn-pay-modal');
    if (payBtn) {
      payBtn.addEventListener('click', function() {
        closeModal('appointmentModal');
        openPaymentModal(this.dataset.id, this.dataset.amount);
      });
    }
    
    const refundBtn = modalBody.querySelector('.btn-request-refund-modal');
    if (refundBtn) {
      refundBtn.addEventListener('click', function() {
        closeModal('appointmentModal');
        openRefundModal(this.dataset.id, this.dataset.amount);
      });
    }
  }

  function bindAppointmentActions() {
    // Xác nhận lịch hẹn
    document.querySelectorAll('.btn-confirm-appointment').forEach(btn => {
      btn.addEventListener('click', async function() {
        const appointmentId = this.dataset.id;
        
        // Hiển thị cảnh báo
        const confirmed = confirm(
          '⚠️ CẢNH BÁO ⚠️\n\n' +
          'Khi bạn xác nhận lịch hẹn này, bạn sẽ KHÔNG THỂ HỦY được nữa!\n\n' +
          'Nếu cần hủy lịch sau khi đã xác nhận, vui lòng liên hệ với chúng tôi qua email:\n' +
          '📧 uytinso1vn@gmail.com\n\n' +
          'Bạn có chắc chắn muốn xác nhận lịch hẹn này?'
        );
        
        if (!confirmed) return;
        
        try {
          this.disabled = true;
          this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
          
          const resp = await window.AuthAPI.apiFetch(`/appointments/${appointmentId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'confirmed' })
          });
          
          const data = await resp.json();
          
          if (resp.ok) {
            alert('✅ Xác nhận lịch hẹn thành công!\n\nVui lòng đến đúng giờ theo lịch hẹn.');
            await refreshDashboard();
          } else {
            throw new Error(data.message || 'Không thể xác nhận lịch hẹn');
          }
        } catch (err) {
          alert('❌ Lỗi: ' + (err.message || 'Không thể xác nhận lịch hẹn'));
          this.disabled = false;
          this.innerHTML = '<i class="fas fa-check"></i> Xác nhận lịch hẹn';
        }
      });
    });
    
    // Hủy lịch hẹn (chỉ khi chưa xác nhận)
    document.querySelectorAll('.btn-cancel-appointment').forEach(btn => {
      btn.addEventListener('click', async function() {
        const appointmentId = this.dataset.id;
        
        const confirmed = confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');
        if (!confirmed) return;
        
        try {
          this.disabled = true;
          this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang hủy...';
          
          const resp = await window.AuthAPI.apiFetch(`/appointments/${appointmentId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'cancelled' })
          });
          
          const data = await resp.json();
          
          if (resp.ok) {
            alert('Đã hủy lịch hẹn.');
            await refreshDashboard();
          } else {
            throw new Error(data.message || 'Không thể hủy lịch hẹn');
          }
        } catch (err) {
          alert('Lỗi: ' + (err.message || 'Không thể hủy lịch hẹn'));
          this.disabled = false;
          this.innerHTML = '<i class="fas fa-times"></i> Hủy';
        }
      });
    });
  }

  function buildPaymentActions(item) {
    const status = (item.status || '').toLowerCase();
    const paymentStatus = (item.payment_status || '').toUpperCase();
    
    // Nếu đã hoàn thành, không hiển thị nút gì
    if (status === 'completed') {
      return '<span class="text-muted"><i class="fas fa-check-circle"></i> Đã hoàn thành</span>';
    }
    
    // Nếu đã hủy
    if (status === 'cancelled') {
      return '<span class="text-muted"><i class="fas fa-times-circle"></i> Đã hủy</span>';
    }
    
    // Nếu đã hoàn tiền - chặn thanh toán lại
    if (paymentStatus === 'REFUNDED') {
      return `<span class="text-info"><i class="fas fa-undo"></i> Đã hoàn tiền</span>`;
    }
    
    // Nếu đang chờ hoàn tiền
    if (paymentStatus === 'REFUND_PENDING') {
      return `
        <span class="text-success"><i class="fas fa-check-circle"></i> Đã thanh toán</span>
        <span class="badge-pending-refund" style="margin-left: 8px; background: #ffc107; color: #333; padding: 3px 8px; border-radius: 4px; font-size: 12px;">
          <i class="fas fa-clock"></i> Đợi duyệt hoàn tiền
        </span>
      `;
    }
    
    // Nếu đã xác nhận
    if (status === 'confirmed' || status === 'available') {
      // Nếu đã thanh toán và được duyệt
      if (paymentStatus === 'PAID') {
        return `
          <span class="text-success"><i class="fas fa-check-circle"></i> Đã thanh toán</span>
          <button class="btn btn-outline-danger btn-sm btn-request-refund" data-id="${item.id}" data-amount="${item.fee_amount || 0}" style="margin-left: 10px;">
            <i class="fas fa-undo"></i> Yêu cầu hoàn tiền
          </button>
        `;
      }
      // Đang chờ admin duyệt thanh toán
      if (paymentStatus === 'PENDING_APPROVAL') {
        return `
          <span class="text-success"><i class="fas fa-calendar-check"></i> Đã xác nhận</span>
          <span class="badge-pending" style="margin-left: 8px; background: #ffc107; color: #333; padding: 3px 8px; border-radius: 4px; font-size: 12px;">
            <i class="fas fa-clock"></i> Đợi xác nhận thanh toán
          </span>
        `;
      }
      // Chưa thanh toán - hiển thị nút thanh toán
      return `
        <span class="text-success" style="margin-right: 10px;"><i class="fas fa-calendar-check"></i> Đã xác nhận</span>
        <button class="btn btn-primary btn-pay-appointment" data-id="${item.id}" data-amount="${item.fee_amount || 200000}">
          <i class="fas fa-credit-card"></i> Thanh toán
        </button>
      `;
    }
    
    return '';
  }

  // Bind payment button click
  function bindPaymentActions() {
    document.querySelectorAll('.btn-pay-appointment').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const appointmentId = this.dataset.id;
        const amount = this.dataset.amount || 200000;
        openPaymentModal(appointmentId, amount);
      });
    });
    
    // Bind refund button
    document.querySelectorAll('.btn-request-refund').forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const appointmentId = this.dataset.id;
        const amount = this.dataset.amount || 0;
        openRefundModal(appointmentId, amount);
      });
    });
  }

  // Open refund modal with OTP verification
  async function openRefundModal(appointmentId, amount) {
    // Show refund confirmation modal
    let modal = byId('refundModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'refundModal';
      modal.className = 'profile-modal';
      modal.innerHTML = `
        <div class="modal-content" style="max-width: 450px;">
          <div class="modal-header" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
            <h3><i class="fas fa-undo"></i> Yêu cầu hoàn tiền</h3>
            <button type="button" class="close-modal" onclick="closeModal('refundModal')">&times;</button>
          </div>
          <div class="modal-body" id="refundModalBody"></div>
        </div>
      `;
      document.body.appendChild(modal);
    }

    const modalBody = document.getElementById('refundModalBody');
    modalBody.innerHTML = `
      <div style="padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ffc107, #ff9800); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 36px; color: white;"></i>
          </div>
          <h4 style="margin: 0 0 10px 0;">Xác nhận yêu cầu hoàn tiền</h4>
          <p style="color: #666; margin: 0;">Số tiền hoàn: <strong style="color: #dc3545;">${formatCurrency(amount)}</strong></p>
        </div>
        
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
          <p style="margin: 0; color: #856404; font-size: 14px;">
            <i class="fas fa-info-circle"></i> Chúng tôi sẽ gửi mã xác nhận đến email của bạn. Sau khi xác nhận, yêu cầu sẽ được gửi đến admin phê duyệt.
          </p>
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
          <label style="display: block; margin-bottom: 5px; font-weight: 500;">Lý do hoàn tiền (không bắt buộc):</label>
          <textarea id="refundReason" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; resize: none;" placeholder="Nhập lý do..."></textarea>
        </div>
        
        <button id="sendRefundOtpBtn" class="btn btn-danger" style="width: 100%; padding: 12px;" data-id="${appointmentId}">
          <i class="fas fa-paper-plane"></i> Gửi mã xác nhận
        </button>
      </div>
    `;

    modal.classList.add('active');

    // Bind send OTP button
    document.getElementById('sendRefundOtpBtn').addEventListener('click', async function() {
      const btn = this;
      const appointmentId = btn.dataset.id;
      
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
      
      try {
        const resp = await window.AuthAPI.apiFetch(`/profile/appointments/${appointmentId}/refund/send-otp`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' }
        });
        
        const data = await resp.json();
        
        if (resp.ok) {
          showRefundOtpInput(appointmentId, amount);
        } else {
          throw new Error(data.message || 'Không thể gửi mã xác nhận');
        }
      } catch (err) {
        alert('Lỗi: ' + (err.message || 'Không thể gửi mã xác nhận'));
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi mã xác nhận';
      }
    });
  }

  function showRefundOtpInput(appointmentId, amount) {
    const modalBody = document.getElementById('refundModalBody');
    const reason = document.getElementById('refundReason')?.value || '';
    
    modalBody.innerHTML = `
      <div style="padding: 20px; text-align: center;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
          <i class="fas fa-envelope-open-text" style="font-size: 36px; color: white;"></i>
        </div>
        <h4 style="margin: 0 0 10px 0;">Nhập mã xác nhận</h4>
        <p style="color: #666; margin-bottom: 20px;">Mã đã được gửi đến email của bạn</p>
        
        <div class="otp-input-container" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
          <input type="text" maxlength="6" id="refundOtpInput" 
            style="width: 180px; text-align: center; font-size: 24px; letter-spacing: 8px; padding: 12px; border: 2px solid #667eea; border-radius: 10px;" 
            placeholder="000000">
        </div>
        
        <p style="color: #999; font-size: 14px; margin-bottom: 20px;">
          <i class="fas fa-clock"></i> Mã có hiệu lực trong 10 phút
        </p>
        
        <button id="confirmRefundOtpBtn" class="btn btn-danger" style="width: 100%; padding: 12px;" data-id="${appointmentId}" data-reason="${escapeHtml(reason)}">
          <i class="fas fa-check"></i> Xác nhận hoàn tiền
        </button>
        
        <button id="resendRefundOtpBtn" class="btn btn-link" style="margin-top: 10px;" data-id="${appointmentId}">
          Gửi lại mã
        </button>
      </div>
    `;
    
    // Focus on OTP input
    document.getElementById('refundOtpInput').focus();
    
    // Bind confirm button
    document.getElementById('confirmRefundOtpBtn').addEventListener('click', async function() {
      const btn = this;
      const otp = document.getElementById('refundOtpInput').value.trim();
      const reason = btn.dataset.reason;
      
      if (!otp || otp.length !== 6) {
        alert('Vui lòng nhập mã 6 số');
        return;
      }
      
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
      
      try {
        const resp = await window.AuthAPI.apiFetch(`/profile/appointments/${appointmentId}/refund/confirm`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ otp, reason })
        });
        
        const data = await resp.json();
        
        if (resp.ok) {
          closeModal('refundModal');
          showRefundSuccess();
          setTimeout(async () => {
            hideRefundSuccess();
            await refreshDashboard();
          }, 2000);
        } else {
          throw new Error(data.message || 'Xác nhận thất bại');
        }
      } catch (err) {
        alert('Lỗi: ' + (err.message || 'Không thể xác nhận'));
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Xác nhận hoàn tiền';
      }
    });
    
    // Bind resend button
    document.getElementById('resendRefundOtpBtn').addEventListener('click', async function() {
      const btn = this;
      btn.disabled = true;
      btn.textContent = 'Đang gửi...';
      
      try {
        await window.AuthAPI.apiFetch(`/profile/appointments/${appointmentId}/refund/send-otp`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' }
        });
        alert('Đã gửi lại mã xác nhận');
      } catch (err) {
        alert('Không thể gửi lại mã');
      }
      
      btn.disabled = false;
      btn.textContent = 'Gửi lại mã';
    });
  }

  function showRefundSuccess() {
    let overlay = document.getElementById('refundSuccessOverlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'refundSuccessOverlay';
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10000;
      `;
      overlay.innerHTML = `
        <div style="background: white; padding: 40px 60px; border-radius: 20px; text-align: center;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-check" style="font-size: 40px; color: white;"></i>
          </div>
          <h3 style="margin: 0 0 10px 0; color: #333;">Yêu cầu đã được gửi!</h3>
          <p style="margin: 0; color: #666;">Đang chờ admin phê duyệt hoàn tiền</p>
        </div>
      `;
      document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
  }

  function hideRefundSuccess() {
    const overlay = document.getElementById('refundSuccessOverlay');
    if (overlay) overlay.style.display = 'none';
  }

  // Open payment modal
  function openPaymentModal(appointmentId, amount) {
    // Lấy danh sách phương thức thanh toán của user
    const paymentMethods = state.dashboard?.payment_methods || [];
    
    let methodsHtml = '';
    if (paymentMethods.length === 0) {
      methodsHtml = `
        <div class="empty-state" style="padding: 20px; text-align: center;">
          <i class="fas fa-credit-card" style="font-size: 48px; color: #ccc; margin-bottom: 10px;"></i>
          <p>Bạn chưa có phương thức thanh toán nào.</p>
          <button class="btn btn-primary" onclick="closeModal('paymentSelectModal'); handleAddPayment();">
            <i class="fas fa-plus"></i> Thêm phương thức thanh toán
          </button>
        </div>
      `;
    } else {
      methodsHtml = `
        <div class="payment-method-list" style="max-height: 300px; overflow-y: auto;">
          ${paymentMethods.map(method => `
            <div class="payment-method-option" data-method-id="${method.id}" style="display: flex; align-items: center; padding: 15px; border: 2px solid #e0e0e0; border-radius: 10px; margin-bottom: 10px; cursor: pointer; transition: all 0.3s;">
              <div class="payment-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                <i class="fas fa-${method.method === 'CREDIT_CARD' ? 'credit-card' : method.method === 'BANK_TRANSFER' ? 'university' : 'wallet'}" style="color: white; font-size: 20px;"></i>
              </div>
              <div style="flex: 1;">
                <h4 style="margin: 0 0 5px 0;">${escapeHtml(method.method_name || 'Phương thức')}</h4>
                <p style="margin: 0; color: #666; font-size: 14px;">${escapeHtml(method.masked_detail || '')}</p>
              </div>
              <div class="check-icon" style="display: none; color: #28a745; font-size: 24px;">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          `).join('')}
        </div>
        <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
          <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: bold;">
            <span>Tổng thanh toán:</span>
            <span style="color: #667eea;">${formatCurrency(amount)}</span>
          </div>
        </div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
          <button class="btn btn-secondary" style="flex: 1;" onclick="closeModal('paymentSelectModal')">Hủy</button>
          <button class="btn btn-primary" style="flex: 1;" id="confirmPaymentBtn" disabled>
            <i class="fas fa-lock"></i> Xác nhận thanh toán
          </button>
        </div>
      `;
    }

    // Create or update modal
    let modal = document.getElementById('paymentSelectModal');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'paymentSelectModal';
      modal.className = 'modal';
      modal.innerHTML = `
        <div class="modal-content" style="max-width: 500px;">
          <div class="modal-header">
            <h3><i class="fas fa-credit-card"></i> Chọn phương thức thanh toán</h3>
            <button type="button" class="close-modal" onclick="closeModal('paymentSelectModal')">&times;</button>
          </div>
          <div class="modal-body" id="paymentSelectBody"></div>
        </div>
      `;
      document.body.appendChild(modal);
    }

    document.getElementById('paymentSelectBody').innerHTML = methodsHtml;
    modal.classList.add('active');

    // Bind method selection
    let selectedMethodId = null;
    modal.querySelectorAll('.payment-method-option').forEach(option => {
      option.addEventListener('click', function() {
        // Remove selection from all
        modal.querySelectorAll('.payment-method-option').forEach(o => {
          o.style.borderColor = '#e0e0e0';
          o.style.background = 'white';
          o.querySelector('.check-icon').style.display = 'none';
        });
        // Select this one
        this.style.borderColor = '#667eea';
        this.style.background = '#f8f9ff';
        this.querySelector('.check-icon').style.display = 'block';
        selectedMethodId = this.dataset.methodId;
        
        // Enable confirm button
        const confirmBtn = document.getElementById('confirmPaymentBtn');
        if (confirmBtn) confirmBtn.disabled = false;
      });
    });

    // Bind confirm button
    const confirmBtn = document.getElementById('confirmPaymentBtn');
    if (confirmBtn) {
      confirmBtn.onclick = () => processPayment(appointmentId, selectedMethodId, amount);
    }
  }

  // Process payment
  async function processPayment(appointmentId, methodId, amount) {
    if (!methodId) {
      alert('Vui lòng chọn phương thức thanh toán');
      return;
    }

    // Close payment modal
    closeModal('paymentSelectModal');

    // Show loading overlay
    showPaymentLoading();

    try {
      // Simulate payment processing (2 seconds)
      await new Promise(resolve => setTimeout(resolve, 2000));

      // Call API to update payment status
      const resp = await window.AuthAPI.apiFetch(`/profile/appointments/${appointmentId}/pay`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ payment_method_id: methodId })
      });

      const data = await resp.json();

      // Hide loading
      hidePaymentLoading();

      if (resp.ok) {
        // Show success message
        showPaymentSuccess();
        
        // Refresh dashboard after 1.5 seconds
        setTimeout(async () => {
          hidePaymentSuccess();
          await refreshDashboard();
        }, 1500);
      } else {
        throw new Error(data.message || 'Thanh toán thất bại');
      }
    } catch (err) {
      hidePaymentLoading();
      alert('Lỗi: ' + (err.message || 'Không thể xử lý thanh toán'));
    }
  }

  // Show payment loading overlay
  function showPaymentLoading() {
    let overlay = document.getElementById('paymentLoadingOverlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'paymentLoadingOverlay';
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10000;
      `;
      overlay.innerHTML = `
        <div style="background: white; padding: 40px 60px; border-radius: 20px; text-align: center;">
          <div class="spinner" style="width: 60px; height: 60px; border: 5px solid #f3f3f3; border-top: 5px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
          <h3 style="margin: 0; color: #333;">Đang xử lý thanh toán...</h3>
          <p style="margin: 10px 0 0; color: #666;">Vui lòng không tắt trình duyệt</p>
        </div>
        <style>
          @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
          }
        </style>
      `;
      document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
  }

  // Hide payment loading overlay
  function hidePaymentLoading() {
    const overlay = document.getElementById('paymentLoadingOverlay');
    if (overlay) overlay.style.display = 'none';
  }

  // Show payment success
  function showPaymentSuccess() {
    let overlay = document.getElementById('paymentSuccessOverlay');
    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'paymentSuccessOverlay';
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10000;
      `;
      overlay.innerHTML = `
        <div style="background: white; padding: 40px 60px; border-radius: 20px; text-align: center;">
          <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ffc107, #ff9800); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <i class="fas fa-clock" style="font-size: 40px; color: white;"></i>
          </div>
          <h3 style="margin: 0; color: #ff9800;">Đang chờ phê duyệt!</h3>
          <p style="margin: 10px 0 0; color: #666;">Thanh toán của bạn đang chờ admin xác nhận</p>
        </div>
      `;
      document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
  }

  // Hide payment success
  function hidePaymentSuccess() {
    const overlay = document.getElementById('paymentSuccessOverlay');
    if (overlay) overlay.style.display = 'none';
  }

  function renderMedicalHistory(list) {
    if (!dom.medicalHistoryList) return;
    if (!list.length) {
      dom.medicalHistoryList.innerHTML = emptyState('Chưa có lịch sử khám');
      return;
    }
    dom.medicalHistoryList.innerHTML = '';
    list.forEach(item => {
      const record = document.createElement('div');
      record.className = 'medical-record';
      record.style.cursor = 'pointer';
      
      let statusText = statusLabel(item.status);
      let statusCls = statusClass(item.status);
      if (item.status === 'missed') {
          statusText = 'Vắng mặt';
          statusCls = 'status-cancelled';
      }

      record.innerHTML = `
        <div class="record-header">
          <div class="record-date"><i class="fas fa-calendar"></i> ${formatDate(item.appointment_date)}</div>
          <div><strong>${escapeHtml(item.doctor_name || 'Bác sĩ')}</strong></div>
        </div>
        <div class="record-content">
          <p><i class="fas fa-info-circle"></i> Trạng thái: <span class="${statusCls}">${statusText}</span></p>
          <p><i class="fas fa-hospital"></i> ${escapeHtml(item.clinic_name || '')}</p>
          <p><i class="fas fa-notes-medical"></i> ${escapeHtml(item.notes || 'Không có ghi chú')}</p>
        </div>
      `;
      
      record.addEventListener('click', () => showAppointmentDetail(item));
      
      dom.medicalHistoryList.appendChild(record);
    });
  }

  function renderNotifications(list) {
    if (!dom.notificationList) return;
    
    // Update badge
    const unreadCount = list.filter(n => !n.is_read).length;
    const badge = byId('notif-badge');
    if (badge) {
      badge.textContent = unreadCount;
      badge.style.display = unreadCount > 0 ? 'block' : 'none';
    }

    if (!list.length) {
      dom.notificationList.innerHTML = emptyState('Không có thông báo');
      return;
    }
    dom.notificationList.innerHTML = '';
    list.forEach(item => {
      const notif = document.createElement('div');
      notif.className = `notification-item ${item.is_read ? 'read' : 'unread'}`;
      notif.dataset.id = item.id;
      notif.style.position = 'relative';
      notif.style.cursor = 'grab';
      
      notif.innerHTML = `
        <div class="notif-icon notif-reminder"><i class="fas fa-bell"></i></div>
        <div class="notif-content">
          <h4>${escapeHtml(item.title || 'Thông báo')}</h4>
          <p>${escapeHtml(item.message || '')}</p>
          <span class="notif-time"><i class="fas fa-clock"></i> ${formatDate(item.created_at)}</span>
        </div>
      `;
      
      // Click to mark read
      notif.addEventListener('click', () => {
          if (!item.is_read) markNotificationRead(item.id);
      });
      
      // Drag logic
      let isDragging = false;
      let startX, startY;
      
      notif.addEventListener('mousedown', e => {
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        notif.style.zIndex = 1000;
        notif.style.transition = 'none';
        notif.style.cursor = 'grabbing';
      });
      
      const onMouseMove = e => {
        if (!isDragging) return;
        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        notif.style.transform = `translate(${dx}px, ${dy}px)`;
        
        const containerRect = dom.notificationList.getBoundingClientRect();
        const notifRect = notif.getBoundingClientRect();
        
        if (notifRect.right < containerRect.left || notifRect.left > containerRect.right || 
            notifRect.bottom < containerRect.top || notifRect.top > containerRect.bottom) {
            notif.style.opacity = 0.5;
        } else {
            notif.style.opacity = 1;
        }
      };
      
      const onMouseUp = e => {
        if (!isDragging) return;
        isDragging = false;
        notif.style.zIndex = '';
        notif.style.transition = 'transform 0.3s';
        notif.style.cursor = 'grab';
        
        const containerRect = dom.notificationList.getBoundingClientRect();
        const notifRect = notif.getBoundingClientRect();
        
        if (notifRect.right < containerRect.left || notifRect.left > containerRect.right || 
            notifRect.bottom < containerRect.top || notifRect.top > containerRect.bottom) {
            if (confirm('Bạn có muốn xóa thông báo này không?')) {
                deleteNotification(item.id);
            } else {
                notif.style.transform = 'translate(0, 0)';
                notif.style.opacity = 1;
            }
        } else {
            notif.style.transform = 'translate(0, 0)';
        }
      };

      window.addEventListener('mousemove', onMouseMove);
      window.addEventListener('mouseup', onMouseUp);

      dom.notificationList.appendChild(notif);
    });
  }

  async function markNotificationRead(id) {
      try {
          await window.AuthAPI.apiFetch(`/profile/notifications/${id}/read`, { method: 'POST' });
          if (id === 'all') {
              state.dashboard.notifications.forEach(n => n.is_read = true);
          } else {
              const n = state.dashboard.notifications.find(x => x.id === id);
              if (n) n.is_read = true;
          }
          renderNotifications(state.dashboard.notifications);
      } catch (e) { console.error(e); }
  }

  async function deleteNotification(id) {
      try {
          await window.AuthAPI.apiFetch(`/profile/notifications/${id}`, { method: 'DELETE' });
          if (id === 'all') {
              state.dashboard.notifications = [];
          } else {
              state.dashboard.notifications = state.dashboard.notifications.filter(x => x.id !== id);
          }
          renderNotifications(state.dashboard.notifications);
      } catch (e) { console.error(e); }
  }


  function renderPaymentMethods(list) {
    if (!dom.paymentMethods) return;
    if (!list.length) {
      dom.paymentMethods.innerHTML = emptyState('Chưa có phương thức thanh toán');
      return;
    }
    dom.paymentMethods.innerHTML = '';
    list.forEach(method => {
      const card = document.createElement('div');
      card.className = 'payment-method-card';
      card.innerHTML = `
        <div class="payment-info">
          <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
          <div>
            <h4 class="payment-name">${escapeHtml(method.method_name || 'Phương thức')}</h4>
            <p class="payment-detail">${escapeHtml(method.masked_detail || '')}</p>
          </div>
        </div>
        <div class="payment-actions">
          <button class="btn btn-primary"><i class="fas fa-edit"></i> Sửa</button>
          <button class="btn btn-danger"><i class="fas fa-trash"></i> Xóa</button>
        </div>
      `;
      const [editBtn, deleteBtn] = card.querySelectorAll('.payment-actions button');
      editBtn.addEventListener('click', () => openEditPaymentModal(method.id));
      deleteBtn.addEventListener('click', () => deletePaymentMethod(method.id));
      dom.paymentMethods.appendChild(card);
    });
  }

  function renderTransactions(list) {
    if (!dom.transactionList) return;
    if (!list.length) {
      dom.transactionList.innerHTML = emptyState('Chưa có giao dịch');
      return;
    }
    dom.transactionList.innerHTML = '';
    list.forEach(item => {
      const row = document.createElement('div');
      row.className = 'transaction-item';
      row.style.cursor = 'pointer';
      
      // Payment status badge
      const paymentStatus = (item.payment_status || '').toUpperCase();
      let statusBadge = '';
      let statusClass = 'status-pending';
      
      switch(paymentStatus) {
        case 'PAID':
          statusBadge = 'Đã thanh toán';
          statusClass = 'status-success';
          break;
        case 'PENDING_APPROVAL':
          statusBadge = 'Chờ duyệt';
          statusClass = 'status-warning';
          break;
        case 'REFUND_PENDING':
          statusBadge = 'Chờ hoàn tiền';
          statusClass = 'status-info';
          break;
        case 'REFUNDED':
          statusBadge = 'Đã hoàn tiền';
          statusClass = 'status-info';
          break;
        default:
          statusBadge = 'Chưa thanh toán';
          statusClass = 'status-pending';
      }
      
      row.innerHTML = `
        <div class="transaction-info">
          <h4>${escapeHtml(item.clinic_name || 'Thanh toán dịch vụ')}</h4>
          <p><i class="fas fa-calendar"></i> ${formatDate(item.paid_at || item.created_at)}</p>
          <p><small>${escapeHtml(item.payment_method_name || '')} - ${escapeHtml(item.payment_detail || '')}</small></p>
        </div>
        <div class="transaction-amount">
          <div class="amount">${formatCurrency(item.fee_amount || 0)}</div>
          <span class="transaction-status ${statusClass}">${statusBadge}</span>
        </div>
      `;
      
      row.addEventListener('click', () => showAppointmentDetail(item));
      
      dom.transactionList.appendChild(row);
    });
  }

  function renderReviews(list) {
    if (!dom.reviewList) return;
    if (!list.length) {
      dom.reviewList.innerHTML = emptyState('Chưa có đánh giá');
      return;
    }
    dom.reviewList.innerHTML = '';
    list.forEach(item => {
      const card = document.createElement('div');
      card.className = 'review-card';
      const doctorAvatar = item.doctor_avatar || '/frontend/img/logocanhan.jpg';
      
      card.innerHTML = `
        <div style="display:flex;justify-content:space-between;margin-bottom:15px;">
          <div style="display:flex; gap:10px; align-items:center;">
            <img src="${escapeHtml(doctorAvatar)}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
            <div>
                <h4>${escapeHtml(item.doctor_name || 'Bác sĩ')}</h4>
                <p style="color:#999;font-size:14px">${formatDate(item.created_at)}</p>
            </div>
          </div>
          <div class="review-actions">
             <button class="btn-link edit-review-btn" data-id="${item.id}" data-rating="${item.rating}" data-comment="${escapeHtml(item.comment)}">Sửa</button>
             <button class="btn-link text-danger delete-review-btn" data-id="${item.id}">Xóa</button>
          </div>
        </div>
        <div class="stars">${'★'.repeat(item.rating || 0)}</div>
        <p style="line-height:1.6;color:#666">${escapeHtml(item.comment || '')}</p>
      `;
      
      card.querySelector('.edit-review-btn').addEventListener('click', function() {
          openEditReviewModal(this.dataset.id, this.dataset.rating, this.dataset.comment);
      });
      
      card.querySelector('.delete-review-btn').addEventListener('click', function() {
          deleteReview(this.dataset.id);
      });

      dom.reviewList.appendChild(card);
    });
  }

  function openEditReviewModal(id, rating, comment) {
      const modal = byId('editReviewModal');
      if(!modal) return;
      
      // Populate form
      const form = byId('editReviewForm');
      form.dataset.id = id;
      
      // Set rating stars
      const stars = modal.querySelectorAll('.star');
      stars.forEach(s => s.classList.toggle('active', Number(s.dataset.value) <= Number(rating)));
      
      // Set comment
      const commentInput = modal.querySelector('textarea');
      if(commentInput) commentInput.value = comment;
      
      // Bind star click in modal
      stars.forEach(star => {
        star.onclick = () => {
            const val = Number(star.dataset.value);
            form.dataset.rating = val;
            stars.forEach(s => s.classList.toggle('active', Number(s.dataset.value) <= val));
        };
      });
      // Set initial rating data
      form.dataset.rating = rating;

      // Bind submit
      form.onsubmit = async (e) => {
          e.preventDefault();
          try {
              const newRating = form.dataset.rating;
              const newComment = commentInput.value;
              
              await window.AuthAPI.apiFetch(`/profile/reviews/${id}`, {
                  method: 'PUT',
                  headers: {'Content-Type': 'application/json'},
                  body: JSON.stringify({ rating: newRating, comment: newComment })
              });
              
              closeModal('editReviewModal');
              refreshDashboard();
              alert('Đã cập nhật đánh giá');
          } catch(err) {
              alert(err.message || 'Lỗi cập nhật');
          }
      };

      openModal('editReviewModal');
  }

  async function deleteReview(id) {
      if(!confirm('Bạn có chắc muốn xóa đánh giá này?')) return;
      try {
          await window.AuthAPI.apiFetch(`/profile/reviews/${id}`, { method: 'DELETE' });
          refreshDashboard();
          alert('Đã xóa đánh giá');
      } catch(err) {
          alert(err.message || 'Lỗi xóa đánh giá');
      }
  }

  function populateReviewAppointments(list) {
    if (!dom.reviewAppointment) return;
    dom.reviewAppointment.innerHTML = '<option value="">Chọn lịch hẹn</option>';
    state.reviewableAppointments = list;
    list.forEach(item => {
      const opt = document.createElement('option');
      opt.value = item.id;
      opt.dataset.doctorAvatar = item.doctor_avatar || '/frontend/img/logocanhan.jpg';
      opt.dataset.doctorName = item.doctor_name || 'Bác sĩ';
      opt.textContent = `${item.doctor_name || 'Bác sĩ'} - ${formatDate(item.appointment_date)}`;
      dom.reviewAppointment.appendChild(opt);
    });
    
    // Add change listener to show doctor avatar
    dom.reviewAppointment.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const avatarPreview = byId('reviewDoctorAvatar');
        const namePreview = byId('reviewDoctorName');
        if (avatarPreview) {
            avatarPreview.src = selected.dataset?.doctorAvatar || '/frontend/img/logocanhan.jpg';
            avatarPreview.style.display = this.value ? 'block' : 'none';
        }
        if (namePreview) {
            namePreview.textContent = selected.dataset?.doctorName || '';
        }
    });
  }

  function renderMessages(list) {
    if (!dom.chatMessagesArea) return;
    if (!list.length) {
      dom.chatMessagesArea.innerHTML = emptyState('Chưa có tin nhắn');
      return;
    }
    dom.chatMessagesArea.innerHTML = '';
    list.forEach(msg => {
      const bubble = document.createElement('div');
      bubble.className = `chat-message ${msg.sender === 'doctor' ? 'from-doctor' : 'from-user'}`;
      bubble.innerHTML = `
        <div class="chat-meta">
          <span>${escapeHtml(msg.sender_name || (msg.sender === 'doctor' ? 'Bác sĩ' : 'Bạn'))}</span>
          <small>${formatDate(msg.sent_at)}</small>
        </div>
        <p>${escapeHtml(msg.message || '')}</p>
      `;
      dom.chatMessagesArea.appendChild(bubble);
    });
    dom.chatMessagesArea.scrollTop = dom.chatMessagesArea.scrollHeight;
  }

  let forumFilter = 'mine';

  function isDoctorAuthor(author) {
    if (!author) return false;
    if (author.type && String(author.type).toUpperCase() === 'DOCTOR') return true;
    if (author.role && String(author.role).toUpperCase().includes('DOCTOR')) return true;
    if (author.doctor_id) return true;
    return false;
  }

  function formatAuthorName(author) {
    const name = author?.full_name || author?.name || 'Người dùng';
    return isDoctorAuthor(author) ? `${name} (Bác sĩ)` : name;
  }

  function renderForumPosts(list) {
    if (!dom.forumPosts) return;
    // Nếu không truyền list, lấy từ state
    if (!Array.isArray(list)) list = state.forumPosts || [];
    let filtered = [];
    if (state.forumFilter === 'mine') {
      filtered = list.filter(post => {
        const authorId = post.author?.id ?? post.user?.id;
        return authorId === state.me?.id;
      });
    } else if (state.forumFilter === 'all') {
      filtered = list;
    } else if (state.forumFilter === 'popular') {
      filtered = [...list].sort((a, b) => {
        const likeDiff = (b.like_count || 0) - (a.like_count || 0);
        if (likeDiff !== 0) return likeDiff;
        return (b.comment_count || 0) - (a.comment_count || 0);
      });
    } else {
      filtered = list;
    }
    if (!filtered.length) {
      dom.forumPosts.innerHTML = emptyState('Chưa có câu hỏi nào');
      return;
    }
    dom.forumPosts.innerHTML = '';
    filtered.forEach(post => {
      const liked = state.likedPosts.has(post.id);
      const isOwner = (post.user?.id ?? post.author?.id) === state.me?.id;
      const isDoctor = post.is_doctor || (post.author && post.author.type === 'DOCTOR') || (post.user && post.user.type === 'DOCTOR');
      const card = document.createElement('div');
      card.className = 'forum-post-card';
      card.dataset.postId = post.id;
      const avatar = resolveAvatar(post.author || post.user);
      const displayName = formatAuthorName(post.author || post.user || {});
      const doctorBadge = isDoctor ? '<span class="doctor-badge-forum"><i class="fas fa-user-md"></i> Bác sĩ</span>' : '';
      const authorName = isDoctor 
        ? `<span style="color:#28a745;font-weight:600;">BS. ${escapeHtml(displayName)}</span>`
        : escapeHtml(displayName);
      card.innerHTML = `
        <div class="forum-post-header">
          <div class="forum-author ${isDoctor ? 'is-doctor' : ''}">
            <img src="${avatar}" alt="avatar" class="forum-avatar" style="${isDoctor ? 'border: 3px solid #28a745;' : ''}" />
            <div>
              <h4>${authorName} ${doctorBadge}</h4>
              <small>${formatDate(post.created_at)}</small>
            </div>
          </div>
          <button class="btn-link" data-action="view-post" data-post-id="${post.id}">
            ${post.isExpanded ? 'Thu gọn' : 'Xem chi tiết'}
          </button>
        </div>
        <div class="forum-post-content">
          <h3>${escapeHtml(post.title)}</h3>
          <p>${escapeHtml(post.content)}</p>
        </div>
        <div class="forum-post-stats">
          <span><i class="fas fa-eye"></i> <span data-view-count="${post.id}">${post.view_count || 0}</span></span>
          <span><i class="fas fa-heart ${liked ? 'liked' : ''}"></i> <span data-like-count="${post.id}">${post.like_count || 0}</span></span>
          <span><i class="fas fa-comment"></i> ${post.comment_count || 0}</span>
        </div>
        <div class="forum-post-actions">
          <button class="btn btn-light ${liked ? 'active' : ''}" data-action="like-post" data-post-id="${post.id}">
            ${liked ? 'Đã thích' : 'Thích'}
          </button>
          <button class="btn btn-outline" data-action="view-post" data-post-id="${post.id}">
            Bình luận
          </button>
          ${isOwner ? `
            <button class="btn btn-secondary" data-action="edit-post" data-post-id="${post.id}">Sửa</button>
            <button class="btn btn-danger" data-action="delete-post" data-post-id="${post.id}">Xóa</button>
          ` : `
            <button class="btn-report" data-action="report-post" data-post-id="${post.id}">
              <i class="fas fa-flag"></i> Báo cáo
            </button>
          `}
        </div>
        <div class="forum-comment-section" data-comment-wrapper="${post.id}" style="display:${post.isExpanded ? 'block' : 'none'}">
          ${buildCommentForm(post.id)}
          <div class="comment-list" data-comment-list="${post.id}">
            ${buildCommentHtml(post.id)}
          </div>
        </div>
      `;
      dom.forumPosts.appendChild(card);
    });
  }

  dom.forumTabs?.addEventListener('click', e => {
    const btn = e.target.closest('.tab-btn');
    if (!btn) return;
    dom.forumTabs.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
      state.forumFilter = btn.dataset.filter || 'mine';
    renderForumPosts(state.forumPosts || []);
  });

  function filterForumPosts(filter) {
    if (filter === 'mine') return state.forumPosts.filter(post => post.user_id === state.me?.id);
    if (filter === 'popular') return [...state.forumPosts].sort((a, b) => b.like_count - a.like_count);
    return state.forumPosts;
  }

  function buildForumPostHtml(post) {
    const liked = state.likedPosts.has(post.id);
    return `
      <div class="forum-post-header">
        <div class="forum-author">
          <img src="${resolveAvatar(post.author)}" alt="avatar" class="forum-avatar" />
          <div>
            <h4>${escapeHtml(post.author?.full_name || 'Người dùng')}</h4>
            <small>${formatDate(post.created_at)}</small>
          </div>
        </div>
        <button class="btn-link" data-action="view-post" data-post-id="${post.id}">
          ${post.isExpanded ? 'Thu gọn' : 'Xem chi tiết'}
        </button>
      </div>
          function renderForumPosts(list = state.forumPosts) {
      <div class="forum-post-content">
        <h3>${escapeHtml(post.title)}</h3>
        <p>${escapeHtml(post.content)}</p>
      </div>
      <div class="forum-post-stats">
        <span><i class="fas fa-eye"></i> <span data-view-count="${post.id}">${post.view_count || 0}</span></span>
        <span><i class="fas fa-heart ${liked ? 'liked' : ''}"></i> <span data-like-count="${post.id}">${post.like_count || 0}</span></span>
        <span><i class="fas fa-comment"></i> ${post.comment_count || 0}</span>
      </div>
      <div class="forum-post-actions">
        <button class="btn btn-light ${liked ? 'active' : ''}" data-action="like-post" data-post-id="${post.id}">
          ${liked ? 'Đã thích' : 'Thích'}
        </button>
        <button class="btn btn-outline" data-action="view-post" data-post-id="${post.id}">
          Bình luận
        </button>
      </div>
      <div class="forum-comment-section" data-comment-wrapper="${post.id}" style="display:${post.isExpanded ? 'block' : 'none'}">
        ${buildCommentForm(post.id)}
        <div class="comment-list" data-comment-list="${post.id}">
          ${buildCommentHtml(post.id)}
        </div>
      </div>
    `;
  }

  function buildCommentForm(postId) {
    return `
      <form class="comment-form" data-action="submit-comment" data-post-id="${postId}">
        <textarea name="comment" placeholder="Viết bình luận..."></textarea>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Gửi</button>
        </div>
      </form>
    `;
  }

  function buildCommentHtml(postId) {
    const comments = state.postComments.get(Number(postId)) || [];
    if (!comments.length) return '<p class="empty-state">Chưa có bình luận</p>';
    return comments.map(comment => {
      const isDoctor = comment.is_doctor || (comment.author && comment.author.type === 'DOCTOR');
      const doctorClass = isDoctor ? 'comment-doctor' : '';
      const doctorBadge = isDoctor ? '<span class="doctor-badge-forum"><i class="fas fa-user-md"></i> Bác sĩ</span>' : '';
      const authorName = isDoctor 
        ? `<span style="color:#28a745;font-weight:600;">BS. ${escapeHtml(comment.author?.full_name || 'Bác sĩ')}</span>`
        : escapeHtml(formatAuthorName(comment.author || {}));
      
      const isLiked = state.likedComments?.has(comment.id);
      const likeCount = comment.like_count || 0;
      const likeClass = isLiked ? 'liked' : '';
      
      return `
      <div class="comment-item ${doctorClass}" data-comment-id="${comment.id}" data-post-id="${postId}" data-author="${escapeHtml(comment.author?.full_name || '')}">
        <div class="comment-header">
          <div class="comment-author">
            <img src="${resolveAvatar(comment.author)}" alt="avatar" class="comment-avatar" />
            <div class="comment-author-info">
              <h5>${authorName} ${doctorBadge}</h5>
              <small>${formatDate(comment.created_at)}</small>
            </div>
          </div>
        </div>
        <div class="comment-content">${escapeHtml(comment.content)}</div>
        <div class="comment-actions">
          <button type="button" class="btn-like-comment ${likeClass}" data-action="like-comment" data-post-id="${postId}" data-comment-id="${comment.id}">
            <i class="fas fa-heart"></i> Thích ${likeCount > 0 ? `<span class="like-count">(${likeCount})</span>` : ''}
          </button>
          <button type="button" class="btn-link" data-action="reply-comment" data-post-id="${postId}" data-comment-id="${comment.id}" data-author="${comment.author?.full_name || ''}">
            <i class="fas fa-reply"></i> Trả lời
          </button>
          ${comment.author?.id === state.me?.id ? `
            <button type="button" class="btn-link" data-action="edit-comment" data-post-id="${postId}" data-comment-id="${comment.id}">
              <i class="fas fa-edit"></i> Sửa
            </button>
            <button type="button" class="btn-link text-danger" data-action="delete-comment" data-post-id="${postId}" data-comment-id="${comment.id}">
              <i class="fas fa-trash"></i> Xóa
            </button>
          ` : `
            <button type="button" class="btn-report" data-action="report-comment" data-post-id="${postId}" data-comment-id="${comment.id}">
              <i class="fas fa-flag"></i> Báo cáo
            </button>
          `}
        </div>
      </div>
    `}).join('');
  }

  function togglePostDetails(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post) return;
    post.isExpanded = !post.isExpanded;
    if (post.isExpanded) {
      incrementView(postId);
      loadComments(postId);
    }
    renderForumPosts();
  }

  function incrementView(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post) return;
    post.view_count = (post.view_count || 0) + 1;
    fetchApi(`/forum/posts/${postId}/views`, { method: 'POST' }).catch(() => {});
  }

  function togglePostLike(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post) return;
    const liked = state.likedPosts.has(post.id);
    // Optimistic UI update
    post.like_count = Math.max(0, (post.like_count || 0) + (liked ? -1 : 1));
    if (liked) state.likedPosts.delete(post.id); else state.likedPosts.add(post.id);
    renderForumPosts();
    // Sync with backend, server returns current like_count
    fetchApi(`/forum/posts/${postId}/likes`, { method: liked ? 'DELETE' : 'POST' })
      .then(r => r.ok ? r.json() : Promise.reject())
      .then(data => {
        if (!data) return;
        const target = state.forumPosts.find(p => String(p.id) === String(postId));
        if (!target) return;
        if (typeof data.like_count === 'number') {
          target.like_count = data.like_count;
        }
        const isLikedNow = !liked;
        if (isLikedNow) state.likedPosts.add(target.id); else state.likedPosts.delete(target.id);
        renderForumPosts();
      })
      .catch(() => {
        // Revert optimistic change on error
        const target = state.forumPosts.find(p => String(p.id) === String(postId));
        if (!target) return;
        target.like_count = Math.max(0, (target.like_count || 0) + (liked ? 1 : -1));
        if (liked) state.likedPosts.add(target.id); else state.likedPosts.delete(target.id);
        renderForumPosts();
      });
  }

  async function loadComments(postId) {
    const key = Number(postId);
    if (state.postComments.has(key)) return;
    try {
      const resp = await fetchApi(`/forum/posts/${postId}/comments`);
      const data = await resp.json();
      state.postComments.set(key, data.comments || []);
      // Cập nhật lại comment_count cho post tương ứng nếu backend trả về
      if (typeof data.comment_count === 'number') {
        const post = state.forumPosts.find(p => String(p.id) === String(postId));
        if (post) post.comment_count = data.comment_count;
      } else {
        const post = state.forumPosts.find(p => String(p.id) === String(postId));
        if (post) post.comment_count = (data.comments || []).length;
      }
      renderForumPosts();
      // Focus lại vào form bình luận nếu vừa mở rộng post
      setTimeout(() => ensureCommentComposer(postId), 60);
    } catch (err) {
      console.error('Không thể tải bình luận', err);
    }
  }

  async function submitComment(e) {
    e.preventDefault();
    const form = e.target;
    const postId = form.dataset.postId;
    const key = Number(postId);
    const textarea = form.querySelector('textarea');
    const content = textarea.value.trim();
    if (!content) return;
    try {
      const resp = await fetchApi(`/forum/posts/${postId}/comments`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ content })
      });
      const data = await resp.json();
      state.postComments.set(key, data.comments || []);
      const post = state.forumPosts.find(p => String(p.id) === String(postId));
      if (post) {
        post.comment_count = typeof data.comment_count === 'number'
          ? data.comment_count
          : (data.comments || []).length;
      }
      textarea.value = '';
      renderForumPosts();
    } catch (err) {
      alert(err.message || 'Không thể gửi bình luận');
    }
  }

  function openReplyForm(postId, commentId, author, autoMention = false) {
    const list = dom.forumPosts?.querySelector(`[data-comment-id="${commentId}"]`);
    if (!list) return;
    const existingForm = list.querySelector('form');
    if (existingForm) {
      const existingTextarea = existingForm.querySelector('textarea');
      if (autoMention && existingTextarea && author) {
        existingTextarea.value = `@${author} `;
        existingTextarea.setSelectionRange(existingTextarea.value.length, existingTextarea.value.length);
      }
      existingTextarea?.focus();
      return;
    }
    const form = document.createElement('form');
    form.className = 'reply-form';
    form.innerHTML = `
      <textarea placeholder="@${escapeHtml(author || '')} "></textarea>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Trả lời</button>
      </div>
    `;
    const textarea = form.querySelector('textarea');
    if (autoMention && textarea && author) {
      textarea.value = `@${author} `;
    }
    form.addEventListener('submit', async e => {
      e.preventDefault();
      const content = textarea.value.trim();
      if (!content) return;
      try {
        const resp = await fetchApi(`/forum/posts/${postId}/comments`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ content: `@${author} ${content}`, parent_comment_id: commentId })
        });
        const data = await resp.json();
        state.postComments.set(postId, data.comments || []);
        renderForumPosts();
      } catch (err) {
        alert(err.message || 'Không thể trả lời');
      }
    });
    list.appendChild(form);
    textarea?.focus();
  }

  function ensureCommentComposer(postId) {
    const rerendered = ensurePostExpanded(postId);
    const focusTextarea = () => {
      const textarea = dom.forumPosts?.querySelector(`.forum-post-card[data-post-id="${postId}"] .comment-form textarea`);
      if (textarea) {
        textarea.focus();
        textarea.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    };
    if (rerendered) {
      setTimeout(focusTextarea, 60);
    } else {
      focusTextarea();
    }
  }

  function ensurePostExpanded(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post) return false;
    if (post.isExpanded) return false;
    post.isExpanded = true;
    incrementView(postId);
    loadComments(postId);
    renderForumPosts();
    return true;
  }

  function handleForumPostClick(event) {
    const actionBtn = event.target.closest('[data-action]');
    if (actionBtn) {
      const { action, postId, commentId, author } = actionBtn.dataset;
      if (action === 'view-post') {
        togglePostDetails(postId);
      } else if (action === 'like-post') {
        togglePostLike(postId);
      } else if (action === 'reply-comment') {
        openReplyForm(postId, commentId, author, true);
      } else if (action === 'edit-post') {
        openEditPostForm(postId);
      } else if (action === 'delete-post') {
        deletePost(postId);
      } else if (action === 'edit-comment') {
        openEditCommentForm(postId, commentId);
      } else if (action === 'delete-comment') {
        deleteComment(postId, commentId);
      } else if (action === 'like-comment') {
        toggleCommentLike(postId, commentId);
      } else if (action === 'report-comment') {
        openReportModal('comment', commentId, postId);
      } else if (action === 'report-post') {
        openReportModal('post', postId);
      }
      return;
    }

    if (event.target.closest('form')) return;

    const commentItem = event.target.closest('.comment-item');
    if (commentItem && dom.forumPosts?.contains(commentItem)) {
      openReplyForm(commentItem.dataset.postId, commentItem.dataset.commentId, commentItem.dataset.author, true);
      return;
    }

    const postCard = event.target.closest('.forum-post-card');
    if (postCard && dom.forumPosts?.contains(postCard)) {
      ensureCommentComposer(postCard.dataset.postId);
    }
  }

  // Toggle like on comment
  async function toggleCommentLike(postId, commentId) {
    try {
      const isLiked = state.likedComments.has(Number(commentId));
      const method = isLiked ? 'DELETE' : 'POST';
      
      // Optimistic UI update
      if (isLiked) {
        state.likedComments.delete(Number(commentId));
      } else {
        state.likedComments.add(Number(commentId));
      }
      
      // Update the comment in state
      const comments = state.postComments.get(Number(postId)) || [];
      const comment = comments.find(c => c.id === Number(commentId));
      if (comment) {
        comment.like_count = Math.max(0, (comment.like_count || 0) + (isLiked ? -1 : 1));
      }
      
      // Re-render comments
      const commentsContainer = document.querySelector(`[data-post-id="${postId}"] .forum-post-comments`);
      if (commentsContainer) {
        commentsContainer.innerHTML = buildCommentHtml(postId);
      }
      
      // Sync with backend - use correct route
      const resp = await fetchApi(`/forum/posts/${postId}/comments/${commentId}/likes`, { method });
      if (!resp.ok) throw new Error('Failed to toggle like');
      
      // Refresh comments to get accurate count
      loadComments(postId);
    } catch (err) {
      console.error('Cannot like comment', err);
      // Revert on error
      loadComments(postId);
    }
  }

  // Open report modal
  function openReportModal(type, id, postId = null) {
    const modal = byId('reportModal');
    if (!modal) {
      alert('Report modal not found');
      return;
    }
    
    // Reset form
    document.querySelectorAll('.report-reason-option').forEach(o => o.classList.remove('selected'));
    document.querySelectorAll('.report-reason-option input[type="radio"]').forEach(r => r.checked = false);
    const detailInput = byId('reportDetail');
    if (detailInput) detailInput.value = '';
    
    // Set type and ID
    byId('reportType').value = type;
    byId('reportTargetId').value = id;
    byId('reportPostId').value = postId || '';
    
    modal.classList.add('active');
  }
  
  async function submitReport() {
    const type = byId('reportType').value;
    const targetId = byId('reportTargetId').value;
    const postId = byId('reportPostId').value;
    const reasonRadio = document.querySelector('input[name="reportReason"]:checked');
    const detail = byId('reportDetail').value.trim();
    
    if (!reasonRadio) {
      alert('Vui lòng chọn lý do báo cáo');
      return;
    }
    
    const reason = reasonRadio.value + (detail ? ': ' + detail : '');
    
    try {
      const payload = { reason };
      if (type === 'post') {
        payload.post_id = targetId;
      } else {
        payload.comment_id = targetId;
        if (postId) payload.post_id = postId;
      }
      
      const resp = await fetchApi('/forum/report', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      
      if (!resp.ok) throw new Error('Không thể gửi báo cáo');
      
      closeModal('reportModal');
      alert('Báo cáo đã được gửi. Cảm ơn bạn đã góp phần xây dựng cộng đồng!');
    } catch (err) {
      alert(err.message || 'Không thể gửi báo cáo');
    }
  }

  function openEditPostForm(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post || (post.author?.id ?? post.user?.id) !== state.me?.id) return;
    const newTitle = prompt('Sửa tiêu đề câu hỏi', post.title || '');
    if (newTitle === null) return;
    const newContent = prompt('Sửa nội dung câu hỏi', post.content || '');
    if (newContent === null) return;
    fetchApi(`/forum/posts/${postId}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ title: newTitle.trim(), content: newContent.trim() })
    })
      .then(r => r.json())
      .then(data => {
        if (data.post) {
          const idx = state.forumPosts.findIndex(p => p.id === data.post.id);
          if (idx !== -1) state.forumPosts[idx] = { ...state.forumPosts[idx], ...data.post };
          renderForumPosts();
        }
      })
      .catch(() => alert('Không thể sửa câu hỏi'));
  }

  function deletePost(postId) {
    const post = state.forumPosts.find(p => String(p.id) === String(postId));
    if (!post || (post.author?.id ?? post.user?.id) !== state.me?.id) return;
    if (!confirm('Bạn có chắc muốn xóa câu hỏi này?')) return;
    fetchApi(`/forum/posts/${postId}`, { method: 'DELETE' })
      .then(r => r.ok ? r : Promise.reject())
      .then(() => {
        state.forumPosts = state.forumPosts.filter(p => String(p.id) !== String(postId));
        renderForumPosts();
      })
      .catch(() => alert('Không thể xóa câu hỏi'));
  }

  function openEditCommentForm(postId, commentId) {
    const comments = state.postComments.get(Number(postId)) || [];
    const comment = comments.find(c => String(c.id) === String(commentId));
    if (!comment || comment.author?.id !== state.me?.id) return;
    const newContent = prompt('Sửa bình luận', comment.content || '');
    if (newContent === null) return;
    fetchApi(`/forum/posts/${postId}/comments/${commentId}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ content: newContent.trim() })
    })
      .then(r => r.json())
      .then(data => {
        state.postComments.set(Number(postId), data.comments || []);
        const post = state.forumPosts.find(p => String(p.id) === String(postId));
        if (post) {
          post.comment_count = typeof data.comment_count === 'number'
            ? data.comment_count
            : (data.comments || []).length;
        }
        renderForumPosts();
      })
      .catch(() => alert('Không thể sửa bình luận'));
  }

  function deleteComment(postId, commentId) {
    const comments = state.postComments.get(Number(postId)) || [];
    const comment = comments.find(c => String(c.id) === String(commentId));
    if (!comment || comment.author?.id !== state.me?.id) return;
    if (!confirm('Bạn có chắc muốn xóa bình luận này?')) return;
    fetchApi(`/forum/posts/${postId}/comments/${commentId}`, { method: 'DELETE' })
      .then(r => r.json())
      .then(data => {
        state.postComments.set(Number(postId), data.comments || []);
        const post = state.forumPosts.find(p => String(p.id) === String(postId));
        if (post) {
          post.comment_count = typeof data.comment_count === 'number'
            ? data.comment_count
            : (data.comments || []).length;
        }
        renderForumPosts();
      })
      .catch(() => alert('Không thể xóa bình luận'));
  }

  async function submitQuestionForm(e) {
    e.preventDefault();
    const title = byId('questionTitle').value.trim();
    const content = byId('questionContent').value.trim();
    if (!title || !content) return alert('Vui lòng nhập đủ tiêu đề và nội dung');
    try {
      const resp = await window.AuthAPI.apiFetch('/forum/posts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ title, content })
      });
      await ensureOk(resp);
      closeModal('questionModal');
      // Lưu trạng thái scroll & bài đang mở (nếu có)
      const prevScroll = window.scrollY;
      const expandedIds = new Set(
        (state.forumPosts || [])
          .filter(p => p.isExpanded)
          .map(p => p.id)
      );
      await loadForumPosts();
      // Khôi phục trạng thái mở rộng bài viết
      if (state.forumPosts) {
        state.forumPosts.forEach(p => {
          p.isExpanded = expandedIds.has(p.id);
        });
        renderForumPosts(state.forumPosts);
      }
      // Khôi phục vị trí scroll gần đúng
      window.scrollTo({ top: prevScroll, behavior: 'instant' });
      alert('Đã đăng câu hỏi thành công!');
    } catch (err) {
      alert(err.message || 'Không thể đăng câu hỏi');
    }
  }

  async function loadForumPosts() {
    try {
      const resp = await window.AuthAPI.apiFetch('/forum/posts');
      const data = await resp.json();
      state.forumPosts = data.posts || [];
      renderForumPosts(state.forumPosts);
    } catch (err) {
      console.error('Không thể tải danh sách bài viết', err);
    }
  }

  function hydrateLikedPosts(list) {
    state.likedPosts = new Set((list || []).map(item => item.post_id || item));
  }

  function resolveAvatar(author) {
    if (!author) return '/frontend/img/logocanhan.jpg';
    return author.avatar_url || (author.type === 'DOCTOR' ? '/frontend/img/doctor-avatar.png' : '/frontend/img/logocanhan.jpg');
  }

  function collectPaymentPayload(mode) {
    const prefix = mode === 'edit' ? 'edit' : 'add';
    const typeSelect = byId(`${prefix}PaymentType`);
    const type = typeSelect?.value;
    if (!type) throw new Error('Vui lòng chọn loại phương thức');
    if (type === 'card') {
      const cardNumber = byId(`${prefix}CardNumber`)?.value.replace(/\s+/g, '') || '';
      if (!cardNumber) throw new Error('Vui lòng nhập số thẻ');
      return {
        type: 'card',
        card_number: cardNumber,
        card_holder: byId(`${prefix}CardHolder`)?.value.trim() || null,
        expiry_month: byId(`${prefix}ExpiryMonth`)?.value || null,
        expiry_year: byId(`${prefix}ExpiryYear`)?.value || null,
        cvv: byId(`${prefix}CVV`)?.value || null,
        method_name: 'Thẻ ' + cardNumber.slice(-4),
        masked_detail: '**** ' + cardNumber.slice(-4)
      };
    }
    if (type === 'wallet') {
      const walletNumber = byId(`${prefix}WalletNumber`)?.value.trim();
      const walletType = byId(`${prefix}WalletType`)?.value;
      if (!walletNumber || !walletType) throw new Error('Vui lòng nhập thông tin ví');
      return {
        type: 'wallet',
        wallet_number: walletNumber,
        wallet_type: walletType,
        method_name: 'Ví ' + walletType,
        masked_detail: walletNumber
      };
    }
    if (type === 'bank') {
      const bankAccount = byId(`${prefix}BankAccount`)?.value.trim();
      const bankName = byId(`${prefix}BankName`)?.value.trim();
      if (!bankAccount || !bankName) throw new Error('Vui lòng nhập thông tin ngân hàng');
      return {
        type: 'bank',
        bank_account: bankAccount,
        bank_name: bankName,
        method_name: 'TK ' + bankName,
        masked_detail: bankAccount
      };
    }
    throw new Error('Loại phương thức không hợp lệ');
  }

  function mapMethodToType(method) {
    const normalized = (method || '').toUpperCase();
    if (normalized === 'CREDIT_CARD') return 'card';
    if (normalized === 'BANK_TRANSFER') return 'bank';
    if (!normalized) return '';
    if (['CARD', 'CARD_PAYMENT'].includes(normalized)) return 'card';
    return normalized === 'BANK' ? 'bank' : 'wallet';
  }

  async function fetchApi(path, options = {}) {
    return window.AuthAPI.apiFetch(path.startsWith('/') ? path : `/${path}`, options);
  }

  // Re-apply payment action listeners after each appointment render
  const originalApplyAppointmentFilter = applyAppointmentFilter;
  applyAppointmentFilter = function (filter) {
    originalApplyAppointmentFilter(filter);
    showPaymentButtons();
  };

  function showPaymentButtons(item) {
    if (!item || !item.payment_status) return '';
    if (item.payment_status === 'PAID') {
      return '<button class="btn btn-success">Xem hóa đơn</button>';
    }
    if (item.payment_status === 'PENDING') {
      return '<button class="btn btn-primary">Thanh toán</button>';
    }
    return '';
  }

  function renderPublicReviews(list) {
    state.publicReviews = Array.isArray(list) ? list : [];
    populatePublicReviewFilters(state.publicReviews);
    filterPublicReviews();
  }

  function populatePublicReviewFilters(list) {
    if (!dom.filterDoctorSelect || !dom.filterRatingSelect) return;
    const doctors = Array.from(new Set(list.map(item => item.doctor_name).filter(Boolean)));
    dom.filterDoctorSelect.innerHTML = '<option value="">Tất cả bác sĩ</option>';
    doctors.forEach(name => {
      const opt = document.createElement('option');
      opt.value = name;
      opt.textContent = name;
      dom.filterDoctorSelect.appendChild(opt);
    });
    dom.filterRatingSelect.innerHTML = '<option value="">Tất cả đánh giá</option>';
    [5, 4, 3, 2, 1].forEach(r => {
      const opt = document.createElement('option');
      opt.value = String(r);
      opt.textContent = `${r} sao trở lên`;
      dom.filterRatingSelect.appendChild(opt);
    });
  }

  function filterPublicReviews() {
    if (!dom.publicReviewList) return;
    const doctorFilter = dom.filterDoctorSelect?.value || '';
    const ratingFilter = Number(dom.filterRatingSelect?.value || 0);
    const filtered = state.publicReviews.filter(item => {
      const matchesDoctor = doctorFilter ? item.doctor_name === doctorFilter : true;
      const matchesRating = ratingFilter ? Number(item.rating || 0) >= ratingFilter : true;
      return matchesDoctor && matchesRating;
    });
    if (!filtered.length) {
      dom.publicReviewList.innerHTML = emptyState('Không có đánh giá phù hợp');
      return;
    }
    dom.publicReviewList.innerHTML = '';
    filtered.forEach(item => {
      const card = document.createElement('div');
      card.className = 'public-review-card';
      card.innerHTML = `
        <div class="public-review-header">
          <div class="author-info">
            <h4>${escapeHtml(item.patient_name || 'Người dùng')}</h4>
            <p>${escapeHtml(item.doctor_name || 'Bác sĩ')}</p>
          </div>
          <div class="public-review-stars">${'★'.repeat(item.rating || 0)}</div>
        </div>
        <p>${escapeHtml(item.comment || '')}</p>
        <small>${formatDate(item.created_at)}</small>
      `;
      dom.publicReviewList.appendChild(card);
    });
  }

  function openModal(id, callback) {
    const modal = byId(id);
    if (modal) {
      modal.classList.add('active');
      if (callback) callback();
    }
  }

  function closeModal(id) {
    const modal = byId(id);
    if (modal) {
      modal.classList.remove('active');
    }
  }

  function handleAddPayment() {
    openModal('addPaymentModal');
  }

  function openPayModal() {
    // Assuming there's a payModal, adjust if needed
    openModal('payModal');
  }

  function openRefundModal() {
    // Assuming there's a refundModal, adjust if needed
    openModal('refundModal');
  }

  // Expose legacy hooks for inline attributes
  window.filterPublicReviews = filterPublicReviews;
  window.updatePaymentForm = updatePaymentForm;
  window.handleAddPayment = handleAddPayment;
  window.openPayModal = openPayModal;
  window.openRefundModal = openRefundModal;
  window.scrollToTopFast = scrollToTopFast;
  window.closeEditPaymentModal = () => closeModal('editPaymentModal');
  window.closeAddPaymentModal = () => closeModal('addPaymentModal');
  window.closeEditProfileModal = () => closeModal('editProfileModal');
  window.showSection = showSection;

  function scrollToTopFast() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  function showSection(sectionId) {
    dom.sections.forEach(sec => sec.classList.toggle('active', sec.id === sectionId));
    dom.navItems?.forEach(item => item.classList.toggle('active', item.dataset.section === sectionId));
    switch (sectionId) {
      case 'appointments':
        renderAppointments(state.dashboard?.appointments || []);
        break;
      case 'medical-history':
        renderMedicalHistory(state.dashboard?.medical_notes || []);
        break;
      case 'notifications':
        renderNotifications(state.dashboard?.notifications || []);
        break;
      case 'payment':
        renderPaymentMethods(state.dashboard?.payment_methods || []);
        renderTransactions(state.dashboard?.transactions || []);
        break;
      case 'reviews':
        renderReviews(state.dashboard?.reviews || []);
        break;
      case 'messages':
        renderMessages(state.messages || []);
        break;
      case 'forum':
        loadForumPosts();
        break;
      case 'public-reviews':
        renderPublicReviews(state.dashboard?.public_reviews || []);
        break;
    }
  }

  function updateScrollIndicators() {
    if (!dom.scrollBtn || !dom.scrollProgress) return;
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const percent = docHeight > 0 ? Math.min(100, Math.round((scrollTop / docHeight) * 100)) : 0;
    dom.scrollProgress.style.width = percent + '%';
    dom.scrollBtn.style.display = scrollTop > 200 ? 'block' : 'none';
  }

  function sendMessage() {
    if (!dom.messageInput || !dom.messageInput.value.trim()) return;
    const message = dom.messageInput.value.trim();
    // Simulate sending message (replace with real API call if needed)
    state.messages.push({
      sender: 'user',
      sender_name: state.me?.full_name || 'Bạn',
      message,
      sent_at: new Date().toISOString()
    });
    renderMessages(state.messages);
    dom.messageInput.value = '';
  }

  function formatGender(gender) {
    switch (gender) {
      case 'MALE': return 'Nam';
      case 'FEMALE': return 'Nữ';
      default: return 'Không xác định';
    }
  }

  function prefillProfileForm() {
    byId('editName').value = state.me?.full_name || '';
    byId('editGender').value = state.me?.gender === 'MALE' ? 'Nam' : (state.me?.gender === 'FEMALE' ? 'Nữ' : 'Khác');
    byId('editDob').value = state.me?.dob || '';
    byId('editPhone').value = state.me?.phone || '';
    byId('editEmail').value = state.me?.email || '';
    byId('editAddress').value = state.me?.address || '';
  }

  function normalizeGenderValue(value) {
    if (!value) return 'UNKNOWN';
    switch (value.toLowerCase()) {
      case 'nam': return 'MALE';
      case 'nữ': return 'FEMALE';
      case 'male': return 'MALE';
      case 'female': return 'FEMALE';
      default: return value.toUpperCase();
    }
  }


  // Mở modal sửa phương thức thanh toán (đặt trong IIFE để dùng được state)
  function openEditPaymentModal(id) {
    const method = (state.dashboard?.payment_methods || []).find(m => m.id === id);
    if (!method) return;
    state.editingPaymentId = id;
    const type = mapMethodToType(method.method_type || method.type);
    byId('editPaymentType').value = type;
    updatePaymentForm('edit');
    if (type === 'card') {
      byId('editCardNumber').value = method.card_number || '';
      byId('editCardHolder').value = method.card_holder || '';
      byId('editExpiryMonth').value = method.expiry_month || '';
      byId('editExpiryYear').value = method.expiry_year || '';
      byId('editCVV').value = '';
    } else if (type === 'wallet') {
      byId('editWalletNumber').value = method.wallet_number || '';
      byId('editWalletType').value = method.wallet_type || '';
    } else if (type === 'bank') {
      byId('editBankAccount').value = method.bank_account || '';
      byId('editBankName').value = method.bank_name || '';
    }
    openModal('editPaymentModal');
  }

  // Xóa phương thức thanh toán (đặt trong IIFE để dùng được state)
  async function deletePaymentMethod(id) {
    if (!confirm('Bạn có chắc muốn xóa phương thức này?')) return;
    try {
      const resp = await window.AuthAPI.apiFetch(`/profile/payment/${id}`, { method: 'DELETE' });
      await ensureOk(resp);
      await refreshDashboard();
      alert('Đã xóa phương thức thanh toán');
    } catch (err) {
      alert(err.message || 'Không thể xóa phương thức');
    }
  }

  // ==================== SETTINGS SECTION ====================
  
  // Load user settings
  async function loadUserSettings() {
    try {
      const resp = await window.AuthAPI.apiFetch('/profile/settings');
      const data = await ensureOk(resp);
      
      // Update toggle states
      if (data.settings) {
        byId('toggleEmailNotification').checked = data.settings.email_notification !== false;
        byId('toggleReplyNotification').checked = data.settings.reply_notification !== false;
        byId('toggleTwoFactor').checked = data.settings.two_factor_enabled === true;
      }
    } catch (err) {
      console.error('Error loading settings:', err);
    }
  }

  // Save a single setting
  async function saveSetting(key, value) {
    try {
      const resp = await window.AuthAPI.apiFetch('/profile/settings', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ [key]: value })
      });
      const data = await ensureOk(resp);
      showSettingStatus(data.message || 'Đã lưu cài đặt', 'success');
      return true;
    } catch (err) {
      showSettingStatus(err.message || 'Không thể lưu cài đặt', 'danger');
      return false;
    }
  }

  // Toggle 2FA
  async function toggleTwoFactor(enabled) {
    try {
      const resp = await window.AuthAPI.apiFetch('/profile/two-factor', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ enabled: enabled })
      });
      const data = await ensureOk(resp);
      showSettingStatus(data.message || (enabled ? 'Đã bật xác thực 2 bước' : 'Đã tắt xác thực 2 bước'), 'success');
      return true;
    } catch (err) {
      showSettingStatus(err.message || 'Không thể cập nhật xác thực 2 bước', 'danger');
      // Revert toggle
      byId('toggleTwoFactor').checked = !enabled;
      return false;
    }
  }

  // Change password
  async function changePassword(currentPassword, newPassword) {
    try {
      const resp = await window.AuthAPI.apiFetch('/profile/change-password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          current_password: currentPassword,
          new_password: newPassword,
          new_password_confirmation: newPassword
        })
      });
      const data = await ensureOk(resp);
      return { success: true, message: data.message || 'Đổi mật khẩu thành công' };
    } catch (err) {
      return { success: false, message: err.message || 'Không thể đổi mật khẩu' };
    }
  }

  // Show setting status message
  function showSettingStatus(message, type) {
    const msgEl = byId('settingStatusMsg');
    if (msgEl) {
      msgEl.textContent = message;
      msgEl.className = `alert alert-${type}`;
      msgEl.style.display = 'block';
      setTimeout(() => {
        msgEl.style.display = 'none';
      }, 3000);
    }
  }

  // Initialize settings event listeners
  function initSettingsHandlers() {
    // Email notification toggle
    const emailToggle = byId('toggleEmailNotification');
    if (emailToggle) {
      emailToggle.addEventListener('change', function() {
        saveSetting('email_notification', this.checked);
      });
    }

    // Reply notification toggle
    const replyToggle = byId('toggleReplyNotification');
    if (replyToggle) {
      replyToggle.addEventListener('change', function() {
        saveSetting('reply_notification', this.checked);
      });
    }

    // 2FA toggle
    const twoFactorToggle = byId('toggleTwoFactor');
    if (twoFactorToggle) {
      twoFactorToggle.addEventListener('change', function() {
        toggleTwoFactor(this.checked);
      });
    }

    // Open change password modal
    const changePassBtn = byId('openChangePasswordBtn');
    if (changePassBtn) {
      changePassBtn.addEventListener('click', function() {
        openModal('changePasswordModal');
      });
    }

    // Change password form
    const changePassForm = byId('changePasswordForm');
    if (changePassForm) {
      changePassForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const currentPass = byId('currentPassword').value;
        const newPass = byId('newPassword').value;
        const confirmPass = byId('confirmNewPassword').value;
        const errorEl = byId('passwordError');
        const submitBtn = byId('submitChangePassword');

        // Validate
        if (newPass !== confirmPass) {
          errorEl.textContent = 'Mật khẩu mới không khớp';
          errorEl.style.display = 'block';
          return;
        }

        if (newPass.length < 8) {
          errorEl.textContent = 'Mật khẩu mới phải có ít nhất 8 ký tự';
          errorEl.style.display = 'block';
          return;
        }

        errorEl.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';

        const result = await changePassword(currentPass, newPass);

        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Lưu mật khẩu';

        if (result.success) {
          alert(result.message);
          closeModal('changePasswordModal');
          changePassForm.reset();
        } else {
          errorEl.textContent = result.message;
          errorEl.style.display = 'block';
        }
      });
    }

    // Load settings when setting section is shown
    const settingNav = document.querySelector('[data-section="setting"]');
    if (settingNav) {
      settingNav.addEventListener('click', function() {
        loadUserSettings();
      });
    }
  }

  // Call init on DOM ready
  document.addEventListener('DOMContentLoaded', function() {
    initSettingsHandlers();
  });

  // Expose functions to global scope
  window.closeModal = closeModal;
  window.handleAddPayment = handleAddPayment;
  window.openPaymentModal = openPaymentModal;
  window.formatCurrency = formatCurrency;
  window.hidePaymentSuccess = hidePaymentSuccess;

  console.log('[profile] access_token:', localStorage.getItem('access_token'));
})();
