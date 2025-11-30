<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SystemSettingsController;

Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/verify-2fa',[AuthController::class,'verify2FA']);
Route::post('/auth/google',[AuthController::class,'googleLogin']);
Route::post('/auth/refresh',[AuthController::class,'refresh']);
Route::post('/auth/logout',[AuthController::class,'logout']);

// Verification code endpoints (public - no auth required)
Route::post('/auth/send-verification-code', [AuthController::class, 'sendVerificationCode']);
Route::post('/auth/verify-code', [AuthController::class, 'verifyCode']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword']);

// Public API (không cần auth)
Route::get('/public/doctors', [PublicController::class, 'getDoctors']);
Route::get('/public/doctors/{id}', [PublicController::class, 'getDoctor']);
Route::get('/public/doctors/{id}/reviews', [PublicController::class, 'getDoctorReviews']);
Route::get('/public/doctors/{id}/work-schedule', [PublicController::class, 'getDoctorWorkSchedule']);
Route::get('/public/doctors/{id}/booked-slots', [PublicController::class, 'getDoctorBookedSlots']);
Route::get('/public/doctors/{id}/day-schedule', [PublicController::class, 'getDoctorDaySchedule']);
Route::get('/public/specializations', [PublicController::class, 'getSpecializations']);
Route::get('/public/services', [PublicController::class, 'getServices']);

// Check appointment slot availability (public - có thể gọi không cần auth để check trước)
Route::get('/appointments/check-slot', [ProfileController::class, 'checkSlotAvailability']);
Route::get('/appointments/booked-slots', [ProfileController::class, 'getBookedSlots']);

// Public appointment booking (không cần auth) - dùng route riêng để tránh conflict
Route::post('/public/appointments', [ProfileController::class, 'createPublicAppointment']);

// Lookup appointments by email and phone (public)
Route::get('/public/appointments/lookup', [ProfileController::class, 'lookupAppointments']);

// Check if email or phone exists in database (public)
Route::get('/public/check-user', [PublicController::class, 'checkUserExists']);

// Booking verification (public - no user required)
Route::post('/public/send-booking-code', [PublicController::class, 'sendBookingVerificationCode']);
Route::post('/public/verify-booking-code', [PublicController::class, 'verifyBookingCode']);

// Maintenance status check (always public, not affected by maintenance middleware)
Route::get('/public/maintenance-status', [PublicController::class, 'getMaintenanceStatus']);

use App\Http\Controllers\ForumController;
use App\Http\Controllers\DoctorMedicalNoteController;
Route::middleware(['jwt.custom'])->group(function() {
    Route::get('/profile/me',[ProfileController::class,'me']);
    Route::get('/profile/dashboard',[ProfileController::class,'userDashboard']);
    Route::patch('/profile/me',[ProfileController::class,'update']);
    Route::post('/profile/avatar',[ProfileController::class,'uploadAvatar']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);
    Route::patch('/profile/two-factor', [ProfileController::class, 'updateTwoFactor']);
    Route::post('/profile/two-factor', [ProfileController::class, 'updateTwoFactor']);
    Route::get('/profile/settings', [ProfileController::class, 'getSettings']);
    Route::post('/profile/settings', [ProfileController::class, 'updateSettings']);
    Route::post('/profile/feedback', [ProfileController::class, 'submitFeedback']);
    Route::get('/profile/my-feedbacks', [ProfileController::class, 'getMyFeedbacks']);
    Route::get('/profile/login-history', [ProfileController::class, 'getLoginHistory']);
    Route::post('/profile/payment', [ProfileController::class, 'addPaymentMethod']);
    Route::patch('/profile/payment/{id}', [ProfileController::class, 'updatePaymentMethod']);
    Route::delete('/profile/payment/{id}', [ProfileController::class, 'deletePaymentMethod']);
    Route::post('/profile/appointments/{id}/pay',[ProfileController::class,'requestOnlinePayment']);
    Route::post('/profile/appointments/{id}/refund/send-otp',[ProfileController::class,'sendRefundOtp']);
    Route::post('/profile/appointments/{id}/refund/confirm',[ProfileController::class,'confirmRefundOtp']);
    Route::post('/profile/appointments/{id}/refund',[ProfileController::class,'requestRefund']);
    Route::get('/profile/refunds', [ProfileController::class, 'getRefunds']);
    Route::post('/profile/refunds/{id}/cancel', [ProfileController::class, 'cancelRefund']);
    Route::get('/profile/reviews', [ProfileController::class, 'listReviews']);
    Route::post('/profile/reviews', [ProfileController::class, 'submitReview']);
    Route::put('/profile/reviews/{id}', [ProfileController::class, 'updateReview']);
    Route::delete('/profile/reviews/{id}', [ProfileController::class, 'deleteUserReview']);
    Route::post('/profile/notifications/{id}/read', [ProfileController::class, 'markNotificationRead']);
    Route::delete('/profile/notifications/{id}', [ProfileController::class, 'deleteNotification']);
    Route::middleware(['role:ADMIN'])->get('/admin/ping', fn() => response()->json(['message' => 'admin ok']));
    Route::middleware(['role:DOCTOR'])->get('/doctor/ping', fn() => response()->json(['message' => 'doctor ok']));
    Route::middleware(['role:USER'])->get('/user/ping', fn() => response()->json(['message' => 'user ok']));
    Route::get('/profile/doctor',[ProfileController::class,'doctorProfile']);
    Route::patch('/profile/doctor',[ProfileController::class,'updateDoctorProfile']);
    // Medical notes (doctor)
    Route::middleware(['role:DOCTOR'])->group(function () {
        // Notifications cho bác sĩ
        Route::get('/doctor/notifications', [ProfileController::class, 'doctorNotifications']);
        Route::post('/doctor/notifications/read-all', [ProfileController::class, 'markAllDoctorNotificationsRead']);
        Route::delete('/doctor/notifications/{id}', [ProfileController::class, 'deleteDoctorNotification']);
        Route::get('/doctor/appointments/confirmed', [ProfileController::class, 'getDoctorConfirmedAppointments']);
        Route::get('/doctor/medical-notes', [DoctorMedicalNoteController::class, 'index']);
        Route::post('/doctor/medical-notes', [DoctorMedicalNoteController::class, 'store']);
        Route::get('/doctor/medical-notes/{id}', [DoctorMedicalNoteController::class, 'show']);
        Route::patch('/doctor/medical-notes/{id}', [DoctorMedicalNoteController::class, 'update']);
        Route::delete('/doctor/medical-notes/{id}', [DoctorMedicalNoteController::class, 'destroy']);
        Route::get('/doctor/completed-patients', [DoctorMedicalNoteController::class, 'completedPatients']);
    });
    // Forum API
    Route::post('/forum/posts', [ForumController::class, 'store']);
    Route::get('/forum/posts', [ForumController::class, 'index']);
    Route::post('/forum/posts/{id}/views', [ForumController::class, 'incrementViews']);
    Route::get('/forum/posts/{id}/comments', [ForumController::class, 'getComments']);
    Route::post('/forum/posts/{id}/comments', [ForumController::class, 'addComment']);
    Route::patch('/forum/posts/{id}', [ForumController::class, 'update']);
    Route::delete('/forum/posts/{id}', [ForumController::class, 'destroy']);
    Route::patch('/forum/posts/{postId}/comments/{commentId}', [ForumController::class, 'updateComment']);
    Route::delete('/forum/posts/{postId}/comments/{commentId}', [ForumController::class, 'destroyComment']);
    // Like/Unlike forum post
    Route::post('/forum/posts/{id}/likes', [ForumController::class, 'likePost']);
    Route::delete('/forum/posts/{id}/likes', [ForumController::class, 'unlikePost']);
    // Like/Unlike comment
    Route::post('/forum/posts/{postId}/comments/{commentId}/likes', [ForumController::class, 'likeComment']);
    Route::delete('/forum/posts/{postId}/comments/{commentId}/likes', [ForumController::class, 'unlikeComment']);
    // Report post or comment
    Route::post('/forum/report', [ForumController::class, 'report']);
});

Route::middleware(['jwt.custom'])->group(function () {
    // Users CRUD
    Route::get('/users', [ProfileController::class, 'getUsers']);
    Route::post('/users', [ProfileController::class, 'createUser']);
    Route::put('/users/{id}', [ProfileController::class, 'updateUser']);
    Route::delete('/users/{id}', [ProfileController::class, 'deleteUser']);
    
    // Doctors CRUD
    Route::get('/doctors', [ProfileController::class, 'getDoctors']);
    Route::post('/doctors', [ProfileController::class, 'createDoctor']);
    Route::put('/doctors/{id}', [ProfileController::class, 'updateDoctor']);
    Route::delete('/doctors/{id}', [ProfileController::class, 'deleteDoctor']);
    
    // Get specializations and clinics for dropdowns
    Route::get('/specializations', [ProfileController::class, 'getSpecializations']);
    Route::get('/clinics', [ProfileController::class, 'getClinics']);
    
    // Clinics CRUD (admin management)
    Route::get('/clinics/all', [ProfileController::class, 'getAllClinics']);
    Route::get('/clinics/{id}', [ProfileController::class, 'getClinic']);
    Route::post('/clinics', [ProfileController::class, 'createClinic']);
    Route::put('/clinics/{id}', [ProfileController::class, 'updateClinic']);
    Route::delete('/clinics/{id}', [ProfileController::class, 'deleteClinic']);
    
    // Payments management (admin)
    Route::get('/payments', [ProfileController::class, 'getPayments']);
    Route::post('/payments/{id}/approve', [ProfileController::class, 'approvePayment']);
    Route::post('/payments/{id}/reject', [ProfileController::class, 'rejectPayment']);
    
    // Specializations management (admin)
    Route::get('/specializations/all', [ProfileController::class, 'getAllSpecializations']);
    Route::get('/specializations/{id}', [ProfileController::class, 'getSpecialization']);
    Route::post('/specializations', [ProfileController::class, 'createSpecialization']);
    Route::put('/specializations/{id}', [ProfileController::class, 'updateSpecialization']);
    Route::delete('/specializations/{id}', [ProfileController::class, 'deleteSpecialization']);
    
    // Services management (admin)
    Route::get('/services', [ProfileController::class, 'getAllServices']); // Alias for frontend
    Route::get('/services/all', [ProfileController::class, 'getAllServices']);
    Route::get('/services/{id}', [ProfileController::class, 'getService']);
    Route::post('/services', [ProfileController::class, 'createService']);
    Route::put('/services/{id}', [ProfileController::class, 'updateService']);
    Route::delete('/services/{id}', [ProfileController::class, 'deleteService']);
    
    // Reviews management (admin)
    Route::get('/reviews', [ProfileController::class, 'getReviews']);
    Route::get('/reviews/{id}', [ProfileController::class, 'getReview']);
    Route::delete('/reviews/{id}', [ProfileController::class, 'deleteReview']);
    Route::get('/doctors/search', [ProfileController::class, 'searchDoctors']);
    
    // Dashboard & Reports
    Route::get('/dashboard/stats', [ProfileController::class, 'getDashboardStats']);
    Route::get('/reports/stats', [ProfileController::class, 'getReportStats']);
    
    // Appointments management
    Route::get('/appointments', [ProfileController::class, 'getAppointments']);
    Route::get('/appointments/available-slots', [ProfileController::class, 'getAvailableTimeSlots']);
    Route::get('/appointments/{id}', [ProfileController::class, 'getAppointment']);
    Route::post('/appointments', [ProfileController::class, 'createAppointment']);
    Route::put('/appointments/{id}', [ProfileController::class, 'updateAppointment']);
    Route::delete('/appointments/{id}', [ProfileController::class, 'deleteAppointment']);
    Route::get('/patients/search', [ProfileController::class, 'searchPatients']);
    
    // Upload avatar file (returns URL only, doesn't save to user)
    Route::post('/upload/avatar', [ProfileController::class, 'uploadAvatarFile']);
    
    // Upload specialization image
    Route::post('/upload/specialization-image', [ProfileController::class, 'uploadSpecializationImage']);
    
    // System Settings & Logs (Admin only)
    Route::middleware(['role:ADMIN'])->prefix('admin')->group(function () {
        // System settings
        Route::get('/settings', [SystemSettingsController::class, 'getSettings']);
        Route::put('/settings', [SystemSettingsController::class, 'updateSettings']);
        Route::post('/settings/clear-cache', [SystemSettingsController::class, 'clearCache']);
        
        // System logs
        Route::get('/logs', [SystemSettingsController::class, 'getLogs']);
        Route::delete('/logs/{id}', [SystemSettingsController::class, 'deleteLog']);
        Route::get('/dashboard-stats', [SystemSettingsController::class, 'getDashboardStats']);
        
        // IP management
        Route::get('/blocked-ips', [SystemSettingsController::class, 'getBlockedIps']);
        Route::post('/block-ip', [SystemSettingsController::class, 'blockIp']);
        Route::post('/unblock-ip', [SystemSettingsController::class, 'unblockIp']);
        
        // Reviews & Reports
        Route::get('/negative-reviews', [SystemSettingsController::class, 'getNegativeReviews']);
        Route::delete('/reviews/{id}', [SystemSettingsController::class, 'deleteReview']);
        Route::get('/feedbacks', [SystemSettingsController::class, 'getFeedbacks']);
        Route::put('/reports/{id}/status', [SystemSettingsController::class, 'updateReportStatus']);
        Route::patch('/reports/{id}/status', [SystemSettingsController::class, 'updateReportStatus']);
        Route::delete('/reports/{id}', [SystemSettingsController::class, 'deleteReport']);
    });
});
