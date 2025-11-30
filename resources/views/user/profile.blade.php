<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="{{ asset('frontend/img/favicon.ico') }}" />
    <title>Trang Cá Nhân - Hệ Thống Đặt Lịch Hẹn Bác Sĩ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('frontend/user/user.css') }}" />
</head>

<body>
    <div class="container">
        <div class="main-content">
            <div class="sidebar">
                <div class="profile-card">
                    <img id="avatar-img" src="{{ asset('frontend/img/logocanhan.jpg') }}" alt="Profile" class="profile-img" />
                    <h3 id="pf-display-name">--</h3>
                    <p style="color: #999; margin-bottom: 20px" id="pf-role">--</p>

                    <div class="profile-info">
                        <div class="info-row">
                            <span class="info-label">Họ Tên:</span>
                            <span class="info-value" id="pf-name" data-user-id="">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Giới tính:</span>
                            <span class="info-value" id="pf-gender">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Ngày sinh:</span>
                            <span class="info-value" id="pf-dob">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Số điện thoại:</span>
                            <span class="info-value" id="pf-phone">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="pf-email">--</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Địa chỉ:</span>
                            <span class="info-value" id="pf-address">--</span>
                        </div>
                    </div>

                    <form id="avatar-upload-form" class="avatar-upload-form" enctype="multipart/form-data">
                        <input type="file" id="avatar-input" accept="image/*" hidden />
                        <div class="avatar-actions">
                            <button type="button" class="btn btn-secondary" id="avatar-upload-btn">
                                <i class="fas fa-upload"></i> Chọn ảnh mới
                            </button>
                            <button type="submit" class="btn btn-primary" id="avatar-save-btn" style="display:none">
                                <i class="fas fa-save"></i> Lưu ảnh
                            </button>
                        </div>
                    </form>

                    <button class="edit-btn" id="openEditProfileBtn">
                        <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                    </button>
                </div>

                <div class="nav-menu">
                    <div class="nav-item active" data-section="appointments" tabindex="0" role="button">
                        <i class="fas fa-calendar-check"></i>
                        <span>Lịch hẹn</span>
                    </div>
                    <div class="nav-item" data-section="medical-history" tabindex="0" role="button">
                        <i class="fas fa-file-medical"></i>
                        <span>Lịch sử khám</span>
                    </div>
                    <div class="nav-item" data-section="notifications" tabindex="0" role="button" style="position: relative;">
                        <i class="fas fa-bell"></i>
                        <span>Thông báo</span>
                        <span id="notif-badge" class="badge badge-danger" style="position: absolute; top: 5px; right: 10px; display: none; background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px;">0</span>
                    </div>
                    <div class="nav-item" data-section="payment" tabindex="0" role="button">
                        <i class="fas fa-credit-card"></i>
                        <span>Thanh toán</span>
                    </div>
                    <div class="nav-item" data-section="reviews" tabindex="0" role="button">
                        <i class="fas fa-star"></i>
                        <span>Đánh giá bác sĩ</span>
                    </div>
                    <div class="nav-item" data-section="forum" tabindex="0" role="button">
                        <i class="fas fa-comments"></i>
                        <span>Diễn đàn sức khỏe</span>
                    </div>
                    <div class="nav-item" data-section="feedback" tabindex="0" role="button">
                        <i class="fas fa-comment-dots"></i>
                        <span>Góp ý</span>
                    </div>
                    <div class="nav-item" data-section="setting" tabindex="0" role="button">
                        <i class="fa fa-cog"></i>
                        <span>Cài Đặt</span>
                    </div>
                    <div class="nav-item" data-section="logout" tabindex="0" role="button" onclick="handleLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Đăng xuất</span>
                    </div>
                </div>
            </div>

            <div class="content-area">
                <!-- Lịch hẹn -->
                <div id="appointments" class="content-section active">
                    <h2 class="section-title">Lịch Hẹn Của Tôi</h2>

                    <div class="tab-buttons" id="appointment-filters">
                        <button class="tab-btn active" data-filter="all">
                            Tất cả
                        </button>
                        <button class="tab-btn" data-filter="pending_confirmation">
                            Chờ xác nhận
                        </button>
                        <button class="tab-btn" data-filter="upcoming">
                            Sắp tới
                        </button>
                        <button class="tab-btn" data-filter="confirmed">
                            Đã xác nhận
                        </button>
                        <button class="tab-btn" data-filter="completed">
                            Đã hoàn thành
                        </button>
                        <button class="tab-btn" data-filter="cancelled">
                            Đã hủy
                        </button>
                    </div>

                    <div id="appointment-list"></div>
                </div>

                <!-- Lịch sử khám -->
                <div id="medical-history" class="content-section">
                    <h2 class="section-title">Lịch Sử Khám Bệnh</h2>
                    <div id="medical-history-list" class="medical-history-container"></div>
                </div>

                <!-- Thông báo -->
                <div id="notifications" class="content-section">
                    <h2 class="section-title">Thông Báo</h2>
                    <div class="notification-actions" style="margin-bottom: 15px; display: flex; gap: 10px; justify-content: flex-end;">
                        <button class="btn btn-sm btn-outline-primary" id="markAllReadBtn">
                            <i class="fas fa-check-double"></i> Đọc tất cả
                        </button>
                        <button class="btn btn-sm btn-outline-danger" id="deleteAllNotifBtn">
                            <i class="fas fa-trash-alt"></i> Xóa tất cả
                        </button>
                    </div>
                    <div id="notification-list" class="notification-container"></div>
                </div>

                <!-- Thanh toán -->
                <div id="payment" class="content-section">
                    <h2 class="section-title">Quản Lý Thanh Toán</h2>

                    <h3 style="margin-bottom: 20px; color: #667eea">
                        <i class="fas fa-wallet"></i> Phương thức thanh toán
                    </h3>

                    <div id="payment-methods"></div>

                    <button class="btn btn-primary" style="margin-bottom: 30px" id="addPaymentBtn">
                        <i class="fas fa-plus"></i> Thêm phương thức thanh toán
                    </button>

                    <h3 style="margin-bottom: 20px; color: #667eea">
                        <i class="fas fa-history"></i> Lịch sử giao dịch
                    </h3>

                    <div id="transaction-list"></div>

                    <!-- PHẦN TRA CỨU HOÀN TIỀN -->
                    <h3 style="margin: 40px 0 20px 0; color: #667eea">
                        <i class="fas fa-search-dollar"></i> Tra Cứu Hoàn Tiền
                    </h3>

                    <div class="refund-lookup-section">
                        <form class="search-form" id="refundSearchForm">
                            <div class="search-input-group">
                                <label for="searchAppointmentId">Tìm theo lịch hẹn</label>
                                <select id="searchAppointmentId">
                                    <option value="">-- Chọn lịch hẹn --</option>
                                </select>
                                <i class="fas fa-calendar-check"></i>
                            </div>

                            <div class="search-input-group">
                                <label for="searchStatus">Trạng thái</label>
                                <select id="searchStatus">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="completed">Đã hoàn tiền</option>
                                    <option value="processing">Đang xử lý</option>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="rejected">Từ chối</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-search">
                                <i class="fas fa-search"></i>
                                Tìm kiếm
                            </button>
                        </form>

                        <!-- Refund List - Dữ liệu được load từ API -->
                        <div class="refund-list" id="refundList">
                            <div style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; display: block;"></i>
                                <p>Chưa có yêu cầu hoàn tiền nào</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Đánh giá bác sĩ -->
                <div id="reviews" class="content-section">
                        <h2 class="section-title">Đánh Giá Bác Sĩ</h2>
                        <div id="review-form-container">
                            <form id="submitReviewForm" class="review-form">
                                <div class="form-group">
                                    <label for="reviewAppointment">Chọn lịch hẹn</label>
                                    <select id="reviewAppointment" required>
                                        <option value="">Chọn lịch hẹn</option>
                                    </select>
                                </div>
                                <div class="doctor-preview" id="doctorPreviewContainer" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: #f8f9fa; border-radius: 10px; margin-bottom: 15px;">
                                    <img id="reviewDoctorAvatar" src="{{ asset('frontend/img/logocanhan.jpg') }}" alt="Doctor" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; display: none;">
                                    <span id="reviewDoctorName" style="font-weight: bold; color: #667eea;"></span>
                                </div>
                                <div class="form-group">
                                    <label>Đánh giá</label>
                                    <div class="star-rating" id="reviewStars">
                                        <span class="star" data-value="1">★</span>
                                        <span class="star" data-value="2">★</span>
                                        <span class="star" data-value="3">★</span>
                                        <span class="star" data-value="4">★</span>
                                        <span class="star" data-value="5">★</span>
                                    </div>
                                    <input type="hidden" id="reviewRating" value="0" />
                                </div>
                                <div class="form-group">
                                    <label for="reviewComment">Nhận xét</label>
                                    <textarea id="reviewComment" rows="4"
                                        placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="review-list"></div>
                </div>

                <!-- Diễn đàn sức khỏe -->
                <div id="forum" class="content-section">
                        <h2 class="section-title">Diễn Đàn Sức Khỏe</h2>

                        <div class="shared-forum" id="forumContainer" data-forum-app data-user-type="patient" data-user-name=""
                            data-user-id="" data-user-avatar="" data-default-filter="mine">
                            <p style="color:#666; margin-bottom: 15px;">
                                Các câu hỏi và phản hồi được đồng bộ giữa trang người dùng và trang cá nhân bác sĩ theo
                                thời gian thực
                                thông qua bộ nhớ trình duyệt.
                            </p>

                            <div class="tab-buttons" data-forum-filters>
                                <button class="tab-btn active" data-filter="mine">Câu hỏi của tôi</button>
                                <button class="tab-btn" data-filter="all">Tất cả câu hỏi</button>
                                <button class="tab-btn" data-filter="answered">Đã trả lời</button>
                            </div>

                            <form class="forum-new-question" data-question-form>
                                <div class="forum-form-group">
                                    <label>Tiêu đề câu hỏi *</label>
                                    <input type="text" placeholder="Nhập tiêu đề câu hỏi..." data-question-title
                                        required>
                                </div>
                                <div class="forum-form-group">
                                    <label>Nội dung chi tiết *</label>
                                    <textarea placeholder="Mô tả triệu chứng, tình trạng sức khỏe bạn muốn thảo luận..."
                                        data-question-content required></textarea>
                                </div>
                                <div class="reply-form-actions">
                                    <button type="reset" class="btn btn-secondary">Xóa nội dung</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Đăng câu hỏi
                                    </button>
                                </div>
                                <small style="display:block;margin-top:10px;color:#95a5a6;">
                                    * Câu hỏi sẽ hiển thị cho bác sĩ ở trang quản trị cá nhân để phản hồi.
                                </small>
                            </form>

                            <div class="forum-posts" data-forum-list></div>
                        </div>
                </div>

                <!-- Góp ý -->
                <div id="feedback" class="content-section">
                        <h2 class="section-title">Góp ý từ người dùng tới admin</h2>
                        <p style="color: #666; margin-bottom: 20px;">
                            Kênh tiếp nhận 24/7, phân loại tự động tới CSKH và bác sĩ phụ trách.
                        </p>
                        <form id="userFeedbackForm" class="feedback-form">
                            <div class="form-group">
                                <label for="feedbackTitle">Tiêu đề góp ý</label>
                                <input type="text" id="feedbackTitle" name="title" placeholder="Ví dụ: Cần thêm hướng dẫn chuẩn bị nội soi" required />
                            </div>
                            <div class="form-group">
                                <label for="feedbackContent">Nội dung chi tiết</label>
                                <textarea id="feedbackContent" name="content" rows="4"
                                    placeholder="Mô tả trải nghiệm, điều cần cải thiện hoặc lời khen..." required></textarea>
                            </div>
                            <div class="form-group" style="display:flex; gap:15px; flex-wrap:wrap;">
                                <div style="flex:1; min-width:200px;">
                                    <label for="feedbackAttachment">Tệp đính kèm (link)</label>
                                    <input type="url" id="feedbackAttachment" name="attachment" placeholder="https://drive..." />
                                </div>
                                <div style="flex:1; min-width:200px;">
                                    <label for="feedbackPriority">Mức độ ưu tiên</label>
                                    <select id="feedbackPriority" name="priority">
                                        <option value="normal">Chuẩn</option>
                                        <option value="high">Cao</option>
                                        <option value="urgent">Khẩn cấp</option>
                                    </select>
                                </div>
                            </div>
                            <div style="text-align:right; margin-top:10px;">
                                <button class="btn btn-primary" type="submit" id="submitFeedbackBtn">
                                    <i class="fas fa-paper-plane"></i> Gửi góp ý
                                </button>
                            </div>
                        </form>

                        <!-- Danh sách góp ý đã gửi -->
                        <div id="myFeedbackList" style="margin-top: 30px;">
                            <h3 style="color: #667eea; margin-bottom: 15px;"><i class="fas fa-history"></i> Góp ý đã gửi</h3>
                            <div id="feedbackListContent"></div>
                        </div>
                </div>

                <!-- Đánh giá bác sĩ (Công Khai) -->
                <div id="public-reviews" class="content-section">
                        <h2 class="section-title">Đánh Giá Bác Sĩ (Công Khai)</h2>

                        <div class="filter-section">
                            <select class="filter-select" id="filterDoctor" onchange="filterPublicReviews()">
                                <option value="">Tất cả bác sĩ</option>
                            </select>
                            <select class="filter-select" id="filterRating" onchange="filterPublicReviews()">
                                <option value="">Tất cả đánh giá</option>
                            </select>
                        </div>

                        <div id="public-review-list"></div>
                </div>

                <!-- Cài đặt -->
                <div id="setting" class="content-section">
                    <h2 class="section-title">Cài Đặt Tài Khoản</h2>
                    
                    <div class="settings-container">
                        <!-- Thông báo Email -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="setting-info">
                                    <h3>Thông báo qua Email</h3>
                                    <p>Tự động gửi email khi có lịch hẹn mới, thay đổi hoặc nhắc nhở</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleEmailNotification" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Thông báo trả lời -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-reply"></i>
                                </div>
                                <div class="setting-info">
                                    <h3>Thông báo khi có trả lời</h3>
                                    <p>Nhận thông báo khi bác sĩ trả lời câu hỏi của bạn trên diễn đàn</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleReplyNotification" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Xác thực 2 bước -->
                        <div class="setting-card">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="setting-info">
                                    <h3>Xác thực 2 bước (2FA)</h3>
                                    <p>Bảo vệ tài khoản bằng mã xác thực gửi qua email khi đăng nhập</p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="toggleTwoFactor">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        <!-- Đổi mật khẩu -->
                        <div class="setting-card setting-card-action">
                            <div class="setting-header">
                                <div class="setting-icon">
                                    <i class="fas fa-key"></i>
                                </div>
                                <div class="setting-info">
                                    <h3>Đổi mật khẩu</h3>
                                    <p>Cập nhật mật khẩu để bảo vệ tài khoản của bạn</p>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary" id="openChangePasswordBtn">
                                <i class="fas fa-edit"></i> Thay đổi
                            </button>
                        </div>
                    </div>

                    <!-- Status Message -->
                    <div id="settingStatusMsg" class="alert" style="display: none; margin-top: 20px;"></div>
                </div>
            </div>

            <!-- Modal for appointment details -->
            <div id="appointmentModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-calendar-check"></i> Chi Tiết Lịch Hẹn</h3>
                        <button type="button" class="close-modal" data-close-modal="appointmentModal">&times;</button>
                    </div>
                    <div id="modalBody"></div>
                </div>
            </div>

            <!-- Modal Sửa Đánh Giá -->
            <div id="editReviewModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas a-edit"></i> Sửa Đánh Giá</h3>
                        <button type="button" class="close-modal" data-close-modal="editReviewModal">&times;</button>
                    </div>
                    <form id="editReviewForm">
                        <div class="form-group">
                            <label>Bác sĩ</label>
                            <input type="text" id="editReviewDoctor" readonly>
                        </div>
                        <div class="form-group">
                            <label>Đánh giá của bạn</label>
                            <div class="star-rating" id="editStarRating">
                                <span class="star" data-rate-edit="1">★</span>
                                <span class="star" data-rate-edit="2">★</span>
                                <span class="star" data-rate-edit="3">★</span>
                                <span class="star" data-rate-edit="4">★</span>
                                <span class="star" data-rate-edit="5">★</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nhận xét</label>
                            <textarea style="width: 100%; height: 200px;" id="editReviewText"
                                placeholder="Chia sẻ trải nghiệm của bạn về bác sĩ..."></textarea>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary"
                                data-close-modal="editReviewModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Edit Profile -->
            <div id="editProfileModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-user"></i> Chỉnh Sửa Thông Tin</h3>
                        <button type="button" class="close-modal" data-close-modal="editProfileModal">&times;</button>
                    </div>
                    <form id="editProfileForm">
                        <div class="form-group">
                            <label for="editName">Họ Tên</label>
                            <input type="text" id="editName" required />
                        </div>
                        <div class="form-group">
                            <label for="editGender">Giới tính</label>
                            <select id="editGender" required>
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editDob">Ngày sinh</label>
                            <input type="date" id="editDob" />
                        </div>
                        <div class="form-group">
                            <label for="editPhone">Số điện thoại</label>
                            <input type="tel" id="editPhone" />
                        </div>
                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" readonly />
                        </div>
                        <div class="form-group">
                            <label for="editAddress">Địa chỉ</label>
                            <input type="text" id="editAddress" />
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary"
                                data-close-modal="editProfileModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Sửa Phương Thức Thanh Toán -->
            <div id="editPaymentModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-credit-card"></i> Sửa Phương Thức Thanh Toán</h3>
                        <button type="button" class="close-modal" data-close-modal="editPaymentModal">&times;</button>
                    </div>
                    <form id="editPaymentForm">
                        <div class="form-group">
                            <label for="editPaymentType">Loại phương thức</label>
                            <select id="editPaymentType" required onchange="updatePaymentForm('edit')">
                                <option value="card">Thẻ tín dụng/Ghi nợ</option>
                                <option value="wallet">Ví điện tử</option>
                                <option value="bank">Tài khoản ngân hàng</option>
                            </select>
                        </div>
                        <div class="form-group" id="editCardNumberGroup">
                            <label for="editCardNumber">Số thẻ</label>
                            <input type="text" id="editCardNumber" placeholder="1234 5678 9012 3456" maxlength="19" />
                        </div>
                        <div class="form-group" id="editCardHolderGroup">
                            <label for="editCardHolder">Tên chủ thẻ</label>
                            <input type="text" id="editCardHolder" placeholder="NGUYEN VAN A" />
                        </div>
                        <div style="display: flex; gap: 15px" id="editCardExpiryGroup">
                            <div class="form-group" style="flex: 1" id="editExpiryMonthGroup">
                                <label for="editExpiryMonth">Tháng hết hạn</label>
                                <select id="editExpiryMonth">
                                    <option value="">Chọn tháng</option>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1" id="editExpiryYearGroup">
                                <label for="editExpiryYear">Năm hết hạn</label>
                                <select id="editExpiryYear">
                                    <option value="">Chọn năm</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1" id="editCVVGroup">
                                <label for="editCVV">CVV</label>
                                <input type="text" id="editCVV" placeholder="123" maxlength="4" />
                            </div>
                        </div>
                        <div class="form-group" id="editWalletNumberGroup" style="display: none">
                            <label for="editWalletNumber">Số điện thoại/Số tài khoản</label>
                            <input type="text" id="editWalletNumber" placeholder="0123456789" />
                        </div>
                        <div class="form-group" id="editWalletTypeGroup" style="display: none">
                            <label for="editWalletType">Loại ví</label>
                            <select id="editWalletType">
                                <option value="MoMo">MoMo</option>
                                <option value="ZaloPay">ZaloPay</option>
                                <option value="ShopeePay">ShopeePay</option>
                                <option value="VNPay">VNPay</option>
                            </select>
                        </div>
                        <div class="form-group" id="editBankAccountGroup" style="display: none">
                            <label for="editBankAccount">Số tài khoản ngân hàng</label>
                            <input type="text" id="editBankAccount" placeholder="1234567890" />
                        </div>
                        <div class="form-group" id="editBankNameGroup" style="display: none">
                            <label for="editBankName">Tên ngân hàng</label>
                            <input type="text" id="editBankName" placeholder="Vietcombank" />
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary"
                                data-close-modal="editPaymentModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Thêm Phương Thức Thanh Toán -->
            <div id="addPaymentModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-plus-circle"></i> Thêm Phương Thức Thanh Toán</h3>
                        <button type="button" class="close-modal" data-close-modal="addPaymentModal">&times;</button>
                    </div>
                    <form id="addPaymentForm">
                        <div class="form-group">
                            <label for="addPaymentType">Loại phương thức</label>
                            <select id="addPaymentType" required onchange="updatePaymentForm('add')">
                                <option value="">Chọn loại phương thức</option>
                                <option value="card">Thẻ tín dụng/Ghi nợ</option>
                                <option value="wallet">Ví điện tử</option>
                                <option value="bank">Tài khoản ngân hàng</option>
                            </select>
                        </div>
                        <div class="form-group" id="addCardNumberGroup" style="display: none">
                            <label for="addCardNumber">Số thẻ</label>
                            <input type="text" id="addCardNumber" placeholder="1234 5678 9012 3456" maxlength="19" />
                        </div>
                        <div class="form-group" id="addCardHolderGroup" style="display: none">
                            <label for="addCardHolder">Tên chủ thẻ</label>
                            <input type="text" id="addCardHolder" placeholder="NGUYEN VAN A" />
                        </div>
                        <div style="display: none; gap: 15px" id="addCardExpiryGroup">
                            <div class="form-group" style="flex: 1" id="addExpiryMonthGroup">
                                <label for="addExpiryMonth">Tháng hết hạn</label>
                                <select id="addExpiryMonth">
                                    <option value="">Chọn tháng</option>
                                    @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1" id="addExpiryYearGroup">
                                <label for="addExpiryYear">Năm hết hạn</label>
                                <select id="addExpiryYear">
                                    <option value="">Chọn năm</option>
                                </select>
                            </div>
                            <div class="form-group" style="flex: 1" id="addCVVGroup">
                                <label for="addCVV">CVV</label>
                                <input type="text" id="addCVV" placeholder="123" maxlength="4" />
                            </div>
                        </div>
                        <div class="form-group" id="addWalletNumberGroup" style="display: none">
                            <label for="addWalletNumber">Số điện thoại/Số tài khoản</label>
                            <input type="text" id="addWalletNumber" placeholder="0123456789" />
                        </div>
                        <div class="form-group" id="addWalletTypeGroup" style="display: none">
                            <label for="addWalletType">Loại ví</label>
                            <select id="addWalletType">
                                <option value="">Chọn loại ví</option>
                                <option value="MoMo">MoMo</option>
                                <option value="ZaloPay">ZaloPay</option>
                                <option value="ShopeePay">ShopeePay</option>
                                <option value="VNPay">VNPay</option>
                            </select>
                        </div>
                        <div class="form-group" id="addBankAccountGroup" style="display: none">
                            <label for="addBankAccount">Số tài khoản ngân hàng</label>
                            <input type="text" id="addBankAccount" placeholder="1234567890" />
                        </div>
                        <div class="form-group" id="addBankNameGroup" style="display: none">
                            <label for="addBankName">Tên ngân hàng</label>
                            <input type="text" id="addBankName" placeholder="Vietcombank" />
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary"
                                data-close-modal="addPaymentModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Đổi Mật Khẩu -->
            <div id="changePasswordModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-key"></i> Đổi Mật Khẩu</h3>
                        <button type="button" class="close-modal" data-close-modal="changePasswordModal">&times;</button>
                    </div>
                    <form id="changePasswordForm">
                        <div class="form-group">
                            <label for="currentPassword">Mật khẩu hiện tại</label>
                            <input type="password" id="currentPassword" required />
                        </div>
                        <div class="form-group">
                            <label for="newPassword">Mật khẩu mới</label>
                            <input type="password" id="newPassword" required minlength="8" />
                        </div>
                        <div class="form-group">
                            <label for="confirmNewPassword">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirmNewPassword" required minlength="8" />
                        </div>
                        <div id="passwordError" style="color: #e74c3c; display: none; margin-bottom: 10px;"></div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary" data-close-modal="changePasswordModal">Hủy</button>
                            <button type="submit" class="btn btn-primary" id="submitChangePassword">
                                <i class="fas fa-save"></i> Lưu mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Đặt Câu Hỏi -->
            <div id="questionModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-plus-circle"></i> Đặt câu hỏi</h3>
                        <button type="button" class="close-modal" data-close-modal="questionModal">&times;</button>
                    </div>
                    <form id="questionForm">
                        <div class="form-group">
                            <label for="questionTitle">Tiêu đề</label>
                            <input type="text" id="questionTitle" required />
                        </div>
                        <div class="form-group">
                            <label for="questionContent">Nội dung</label>
                            <textarea id="questionContent" rows="5" required></textarea>
                        </div>
                        <div style="display:flex; gap:10px; justify-content:flex-end">
                            <button type="button" class="btn btn-secondary"
                                data-close-modal="questionModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Đăng câu hỏi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Modal -->
            <div id="reportModal" class="report-modal">
                <div class="report-modal-content">
                    <div class="report-modal-header">
                        <h3><i class="fas fa-flag"></i> Báo cáo nội dung</h3>
                        <button type="button" class="close-modal" data-close-modal="reportModal">&times;</button>
                    </div>
                    <input type="hidden" id="reportType" value="">
                    <input type="hidden" id="reportTargetId" value="">
                    <input type="hidden" id="reportPostId" value="">

                    <div class="report-reasons">
                        <div class="report-reason-option">
                            <input type="radio" name="reportReason" id="reason1" value="spam">
                            <label for="reason1">Spam hoặc quảng cáo</label>
                        </div>
                        <div class="report-reason-option">
                            <input type="radio" name="reportReason" id="reason2" value="harassment">
                            <label for="reason2">Quấy rối hoặc bắt nạt</label>
                        </div>
                        <div class="report-reason-option">
                            <input type="radio" name="reportReason" id="reason3" value="misinformation">
                            <label for="reason3">Thông tin sai lệch về y tế</label>
                        </div>
                        <div class="report-reason-option">
                            <input type="radio" name="reportReason" id="reason4" value="inappropriate">
                            <label for="reason4">Nội dung không phù hợp</label>
                        </div>
                        <div class="report-reason-option">
                            <input type="radio" name="reportReason" id="reason5" value="other">
                            <label for="reason5">Lý do khác</label>
                        </div>
                    </div>

                    <textarea id="reportDetail" class="report-detail-input" placeholder="Mô tả chi tiết lý do báo cáo (tùy chọn)..."></textarea>

                    <div class="report-modal-actions">
                        <button type="button" class="btn-report-cancel" data-close-modal="reportModal">Hủy</button>
                        <button type="button" class="btn-report-submit" id="submitReportBtn">
                            <i class="fas fa-paper-plane"></i> Gửi báo cáo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Scroll to top button -->
            <button class="scroll-to-top-btn" id="scrollToTopBtn">
                <i class="fas fa-arrow-up"></i>
            </button>

            <!-- Scroll progress bar -->
            <div class="scroll-progress" id="scrollProgress"></div>
        </div>



        <script src="{{ asset('frontend/js/auth.js') }}"></script>
        <script>
            // Check authentication
            if (!localStorage.getItem('access_token')) {
                window.location.replace('{{ route("dang-nhap") }}');
            }

            // Logout function
            function handleLogout() {
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                localStorage.removeItem('session_id');
                sessionStorage.clear();
                window.location.href = '{{ route("dang-nhap") }}';
            }
        </script>
        <script>
            // Refund management functions
            async function loadRefunds(filters = {}) {
                try {
                    const params = new URLSearchParams();
                    if (filters.transaction_id) params.append('transaction_id', filters.transaction_id);
                    if (filters.status) params.append('status', filters.status);
                    if (filters.date_from) params.append('date_from', filters.date_from);
                    if (filters.date_to) params.append('date_to', filters.date_to);

                    const url = '/api/profile/refunds' + (params.toString() ? '?' + params.toString() : '');
                    const resp = await window.AuthAPI.apiFetch(url);

                    if (resp.refunds) {
                        renderRefundList(resp.refunds);
                    }
                } catch (err) {
                    console.error('Error loading refunds:', err);
                }
            }

            function renderRefundList(refunds) {
                const container = document.getElementById('refundList');
                if (!container) return;

                if (!refunds || refunds.length === 0) {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 40px; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>Không có yêu cầu hoàn tiền nào</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = refunds.map(item => {
                    const statusClass = {
                        'REQUESTED': 'status-pending',
                        'PROCESSING': 'status-processing',
                        'COMPLETED': 'status-completed',
                        'REJECTED': 'status-rejected'
                    }[item.status] || 'status-pending';

                    const statusIcon = {
                        'REQUESTED': 'fa-clock',
                        'PROCESSING': 'fa-spinner',
                        'COMPLETED': 'fa-check-circle',
                        'REJECTED': 'fa-times-circle'
                    }[item.status] || 'fa-clock';

                    return `
                        <div class="refund-item" data-id="${item.id}">
                            <div class="refund-item-header">
                                <div class="refund-id">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                    ${item.refund_code}
                                </div>
                                <div class="refund-status ${statusClass}">
                                    <i class="fas ${statusIcon}"></i>
                                    ${item.status_label}
                                </div>
                            </div>

                            <div class="refund-details">
                                <div class="detail-item">
                                    <span class="detail-label">Mã giao dịch gốc</span>
                                    <span class="detail-value">${item.transaction_code}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Bác sĩ</span>
                                    <span class="detail-value">${item.doctor_name || '--'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Ngày yêu cầu</span>
                                    <span class="detail-value">${formatRefundDate(item.requested_at)}</span>
                                </div>
                                ${item.completed_at ? `
                                <div class="detail-item">
                                    <span class="detail-label">Ngày hoàn tiền</span>
                                    <span class="detail-value">${formatRefundDate(item.completed_at)}</span>
                                </div>
                                ` : ''}
                                <div class="detail-item">
                                    <span class="detail-label">Số tiền hoàn</span>
                                    <span class="detail-value amount">${formatRefundCurrency(item.amount)}</span>
                                </div>
                            </div>

                            ${item.reason ? `
                            <div class="refund-reason">
                                <h5>Lý do hoàn tiền:</h5>
                                <p>${item.reason}</p>
                            </div>
                            ` : ''}

                            <div class="refund-actions">
                                ${item.can_cancel ? `
                                <button class="btn-cancel" onclick="cancelRefundRequest(${item.id})">
                                    <i class="fas fa-ban"></i>
                                    Hủy yêu cầu
                                </button>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function formatRefundDate(dateStr) {
                if (!dateStr) return '--';
                const date = new Date(dateStr);
                return date.toLocaleDateString('vi-VN', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            function formatRefundCurrency(amount) {
                if (!amount) return '0đ';
                return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
            }

            async function cancelRefundRequest(id) {
                if (!confirm('Bạn có chắc chắn muốn hủy yêu cầu hoàn tiền này?')) return;

                try {
                    const resp = await window.AuthAPI.apiFetch(`/api/profile/refunds/${id}/cancel`, {
                        method: 'POST'
                    });
                    alert(resp.message || 'Đã hủy yêu cầu hoàn tiền');
                    loadRefunds(); // Reload list
                } catch (err) {
                    alert(err.message || 'Không thể hủy yêu cầu');
                }
            }

            // Refund search form handler
            document.getElementById('refundSearchForm')?.addEventListener('submit', function (e) {
                e.preventDefault();

                const filters = {
                    appointment_id: document.getElementById('searchAppointmentId').value,
                    status: document.getElementById('searchStatus').value
                };

                loadRefunds(filters);
            });

            // Populate appointment dropdown when payment section is shown
            async function populateAppointmentDropdown() {
                try {
                    const resp = await window.AuthAPI.apiFetch('/profile/dashboard');
                    const data = await resp.json ? await resp.json() : resp;
                    const appointments = data.appointments || [];
                    const select = document.getElementById('searchAppointmentId');
                    if (!select) return;

                    select.innerHTML = '<option value="">-- Chọn lịch hẹn --</option>';
                    appointments.forEach(appt => {
                        const date = new Date(appt.appointment_date).toLocaleDateString('vi-VN');
                        const opt = document.createElement('option');
                        opt.value = appt.id;
                        opt.textContent = `${date} - ${appt.doctor_name || 'Bác sĩ'} - ${appt.clinic_name || ''}`;
                        select.appendChild(opt);
                    });
                } catch (err) {
                    console.error('Error loading appointments:', err);
                }
            }

            // Load refunds when payment section is shown
            document.querySelector('[data-section="payment"]')?.addEventListener('click', function() {
                loadRefunds();
                populateAppointmentDropdown();
            });

            // ========== FEEDBACK HANDLER ==========
            document.getElementById('userFeedbackForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();

                const title = document.getElementById('feedbackTitle').value.trim();
                const content = document.getElementById('feedbackContent').value.trim();
                const attachment = document.getElementById('feedbackAttachment').value.trim();
                const priority = document.getElementById('feedbackPriority').value;

                if (!title || !content) {
                    alert('Vui lòng nhập tiêu đề và nội dung góp ý');
                    return;
                }

                const submitBtn = document.getElementById('submitFeedbackBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';

                try {
                    const fullContent = `[${priority.toUpperCase()}] ${title}\n\n${content}${attachment ? '\n\nĐính kèm: ' + attachment : ''}`;

                    const resp = await window.AuthAPI.apiFetch('/profile/feedback', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            content: fullContent,
                            type: 'feedback'
                        })
                    });

                    const data = await resp.json ? await resp.json() : resp;

                    if (data.success) {
                        alert('Góp ý của bạn đã được gửi thành công!');
                        document.getElementById('feedbackTitle').value = '';
                        document.getElementById('feedbackContent').value = '';
                        document.getElementById('feedbackAttachment').value = '';
                        document.getElementById('feedbackPriority').value = 'normal';
                        loadMyFeedbacks();
                    } else {
                        alert(data.message || 'Không thể gửi góp ý');
                    }
                } catch (err) {
                    console.error('Feedback error:', err);
                    alert(err.message || 'Có lỗi xảy ra khi gửi góp ý');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi góp ý';
                }
            });

            // Load user's feedback history
            async function loadMyFeedbacks() {
                try {
                    const container = document.getElementById('feedbackListContent');
                    if (!container) return;

                    // Load từ forum_report với type feedback
                    const resp = await window.AuthAPI.apiFetch('/profile/my-feedbacks');
                    const data = await resp.json ? await resp.json() : resp;

                    if (!data.feedbacks || data.feedbacks.length === 0) {
                        container.innerHTML = '<p style="color:#999; text-align:center;">Chưa có góp ý nào</p>';
                        return;
                    }

                    container.innerHTML = data.feedbacks.map(fb => `
                        <div class="feedback-item" style="background:#f8f9fa; padding:15px; border-radius:10px; margin-bottom:10px;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                                <span style="color:#667eea; font-weight:bold;">${new Date(fb.created_at).toLocaleDateString('vi-VN')}</span>
                                <span class="status-${fb.status.toLowerCase()}" style="font-size:12px; padding:3px 8px; border-radius:12px;">
                                    ${fb.status === 'PENDING' ? 'Chờ xử lý' : fb.status === 'REVIEWED' ? 'Đã xem' : fb.status}
                                </span>
                            </div>
                            <p style="margin:0; color:#333;">${fb.detail.substring(0, 200)}${fb.detail.length > 200 ? '...' : ''}</p>
                        </div>
                    `).join('');
                } catch (err) {
                    console.error('Error loading feedbacks:', err);
                }
            }

            // Load feedbacks when feedback section is shown
            document.querySelector('[data-section="feedback"]')?.addEventListener('click', function() {
                loadMyFeedbacks();
            });
        </script>
        <script src="{{ asset('frontend/js/user_profile.js') }}"></script>
        <script src="{{ asset('frontend/js/forum-sync.js') }}"></script>
</body>

</html>
