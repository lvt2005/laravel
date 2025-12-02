<?php

namespace App\Services;

require_once base_path('PHPMailer/PHPMailer.php');
require_once base_path('PHPMailer/SMTP.php');
require_once base_path('PHPMailer/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\SystemSetting;

class MailService
{
    private $mailer;
    
    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->setupMailer();
    }
    
    private function setupMailer()
    {
        // Server settings
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = 'uptinso1vn27@gmail.com';
        $this->mailer->Password   = 'nsgtudyehsxupbzq';
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 587;
        $this->mailer->CharSet    = 'UTF-8';
        
        // Sender
        $this->mailer->setFrom('uptinso1vn27@gmail.com', 'Hệ thống Đặt lịch Bác sĩ');
    }
    
    /**
     * Check if email can be sent based on system settings
     * @param string $userType - 'USER', 'DOCTOR', or 'SYSTEM' (for verification codes, etc.)
     * @return bool
     */
    public function canSendEmail($userType = 'SYSTEM')
    {
        // Always allow system emails (verification codes, password reset, etc.)
        if ($userType === 'SYSTEM') {
            return SystemSetting::isEmailEnabled();
        }
        
        // Check user type specific settings
        if ($userType === 'USER') {
            return SystemSetting::isUserEmailEnabled();
        }
        
        if ($userType === 'DOCTOR') {
            return SystemSetting::isDoctorEmailEnabled();
        }
        
        return SystemSetting::isEmailEnabled();
    }
    
    /**
     * Send appointment reminder email
     */
    public function sendAppointmentReminder($toEmail, $toName, $appointmentDate, $appointmentTime, $doctorName, $clinicName, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '🔔 Nhắc nhở lịch hẹn khám bệnh - ' . $appointmentDate;
            
            $body = $this->getAppointmentReminderTemplate($toName, $appointmentDate, $appointmentTime, $doctorName, $clinicName);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    /**
     * Send payment confirmation email
     */
    public function sendPaymentConfirmation($toEmail, $toName, $transactionId, $amount, $paymentMethod, $appointmentDetails, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '✅ Xác nhận thanh toán thành công - Mã GD: ' . $transactionId;
            
            $body = $this->getPaymentConfirmationTemplate($toName, $transactionId, $amount, $paymentMethod, $appointmentDetails);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    /**
     * Send forum activity notification email
     */
    public function sendForumActivityNotification($toEmail, $toName, $activityType, $actorName, $postTitle, $content = null, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            
            $subject = match($activityType) {
                'comment' => "💬 {$actorName} đã bình luận về câu hỏi của bạn",
                'like' => "❤️ {$actorName} đã thích bình luận của bạn",
                'reply' => "↩️ {$actorName} đã trả lời bình luận của bạn",
                default => "📢 Có hoạt động mới trên diễn đàn"
            };
            
            $this->mailer->Subject = $subject;
            
            $body = $this->getForumActivityTemplate($toName, $activityType, $actorName, $postTitle, $content);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    /**
     * Send verification code email
     * Note: Verification codes are SYSTEM emails - always sent if email system is enabled
     */
    public function sendVerificationCode($toEmail, $toName, $code, $expiresInMinutes = 5)
    {
        // System emails are always allowed if email is enabled
        if (!$this->canSendEmail('SYSTEM')) {
            return ['success' => false, 'message' => 'Hệ thống email đang bảo trì', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '🔐 Mã xác thực của bạn - Hệ thống Đặt lịch Bác sĩ';
            
            $body = $this->getVerificationCodeTemplate($toName, $code, $expiresInMinutes);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Mã xác thực của bạn là: {$code}. Mã này sẽ hết hạn sau {$expiresInMinutes} phút.";
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }

    /**
     * Send 2FA verification code for login
     * Note: 2FA codes are SYSTEM emails - always sent if email system is enabled
     */
    public function send2FACode($toEmail, $toName, $code, $expiresInMinutes = 10)
    {
        // System emails are always allowed if email is enabled
        if (!$this->canSendEmail('SYSTEM')) {
            return ['success' => false, 'message' => 'Hệ thống email đang bảo trì', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '🔒 Mã xác thực 2 yếu tố - Đăng nhập Hệ thống Đặt lịch Bác sĩ';
            
            $body = $this->get2FACodeTemplate($toName, $code, $expiresInMinutes);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Mã xác thực 2 yếu tố của bạn là: {$code}. Mã này sẽ hết hạn sau {$expiresInMinutes} phút.";
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    /**
     * Send missed appointment notification
     */
    public function sendMissedAppointmentNotification($toEmail, $toName, $appointmentDate, $appointmentTime, $doctorName, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '⚠️ Lịch hẹn bị bỏ lỡ - ' . $appointmentDate;
            
            $body = $this->getMissedAppointmentTemplate($toName, $appointmentDate, $appointmentTime, $doctorName);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    /**
     * Generic send email method
     * @param string $userType - USER, DOCTOR, or SYSTEM for checking email settings
     */
    public function send($toEmail, $toName, $subject, $htmlBody, $textBody = null, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlBody;
            $this->mailer->AltBody = $textBody ?? strip_tags(str_replace(['<br>', '</div>'], "\n", $htmlBody));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }
    
    // ==================== EMAIL TEMPLATES ====================
    
    private function getBaseTemplate($title, $content)
    {
        return '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 20px 0;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                            <h1 style="color: white; margin: 0; font-size: 24px;">🏥 Hệ thống Đặt lịch Bác sĩ</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td style="padding: 30px;">
                            ' . $content . '
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #eee;">
                            <p style="color: #666; margin: 0 0 10px 0; font-size: 14px;">
                                Đây là email tự động, vui lòng không trả lời email này.
                            </p>
                            <p style="color: #999; margin: 0; font-size: 12px;">
                                © 2024 Hệ thống Đặt lịch Bác sĩ. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }
    
    private function getAppointmentReminderTemplate($name, $date, $time, $doctorName, $clinicName)
    {
        $content = '
            <h2 style="color: #667eea; margin: 0 0 20px 0;">Xin chào ' . htmlspecialchars($name) . '!</h2>
            <p style="color: #333; line-height: 1.6;">
                Đây là email nhắc nhở về lịch hẹn khám bệnh của bạn vào <strong>ngày mai</strong>.
            </p>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px 0; color: #666;">📅 Ngày khám:</td>
                        <td style="padding: 5px 0; color: #333; font-weight: bold;">' . htmlspecialchars($date) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #666;">⏰ Giờ khám:</td>
                        <td style="padding: 5px 0; color: #333; font-weight: bold;">' . htmlspecialchars($time) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #666;">👨‍⚕️ Bác sĩ:</td>
                        <td style="padding: 5px 0; color: #333; font-weight: bold;">' . htmlspecialchars($doctorName) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #666;">🏥 Phòng khám:</td>
                        <td style="padding: 5px 0; color: #333; font-weight: bold;">' . htmlspecialchars($clinicName) . '</td>
                    </tr>
                </table>
            </div>
            <p style="color: #333; line-height: 1.6;">
                <strong>Lưu ý quan trọng:</strong>
            </p>
            <ul style="color: #666; line-height: 1.8;">
                <li>Vui lòng đến trước giờ hẹn 15 phút</li>
                <li>Mang theo giấy tờ tùy thân và kết quả xét nghiệm (nếu có)</li>
                <li>Liên hệ hotline nếu cần hỗ trợ</li>
            </ul>
        ';
        
        return $this->getBaseTemplate('Nhắc nhở lịch hẹn', $content);
    }
    
    private function getPaymentConfirmationTemplate($name, $transactionId, $amount, $paymentMethod, $appointmentDetails)
    {
        $content = '
            <h2 style="color: #28a745; margin: 0 0 20px 0;">✅ Thanh toán thành công!</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>, chúng tôi xác nhận đã nhận được thanh toán của bạn.
            </p>
            <div style="background-color: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #c3e6cb;">
                <h3 style="color: #155724; margin: 0 0 15px 0;">Chi tiết giao dịch</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Mã giao dịch:</td>
                        <td style="padding: 5px 0; color: #155724; font-weight: bold;">#' . htmlspecialchars($transactionId) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Số tiền:</td>
                        <td style="padding: 5px 0; color: #155724; font-weight: bold;">' . number_format($amount, 0, ',', '.') . 'đ</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Phương thức:</td>
                        <td style="padding: 5px 0; color: #155724; font-weight: bold;">' . htmlspecialchars($paymentMethod) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Thời gian:</td>
                        <td style="padding: 5px 0; color: #155724; font-weight: bold;">' . date('d/m/Y H:i') . '</td>
                    </tr>
                </table>
            </div>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #667eea; margin: 0 0 15px 0;">Thông tin lịch hẹn</h3>
                <p style="color: #666; margin: 0;">' . htmlspecialchars($appointmentDetails) . '</p>
            </div>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Vui lòng giữ email này để làm bằng chứng thanh toán. Nếu có thắc mắc, xin liên hệ hotline hỗ trợ.
            </p>
        ';
        
        return $this->getBaseTemplate('Xác nhận thanh toán', $content);
    }
    
    private function getForumActivityTemplate($name, $activityType, $actorName, $postTitle, $content)
    {
        $activityText = match($activityType) {
            'comment' => '<strong>' . htmlspecialchars($actorName) . '</strong> đã bình luận về câu hỏi của bạn',
            'like' => '<strong>' . htmlspecialchars($actorName) . '</strong> đã thích bình luận của bạn',
            'reply' => '<strong>' . htmlspecialchars($actorName) . '</strong> đã trả lời bình luận của bạn',
            default => 'Có hoạt động mới trên diễn đàn'
        };
        
        $activityIcon = match($activityType) {
            'comment' => '💬',
            'like' => '❤️',
            'reply' => '↩️',
            default => '📢'
        };
        
        $contentHtml = '
            <h2 style="color: #667eea; margin: 0 0 20px 0;">Xin chào ' . htmlspecialchars($name) . '!</h2>
            <p style="color: #333; line-height: 1.6; font-size: 16px;">
                ' . $activityIcon . ' ' . $activityText . '
            </p>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #667eea;">
                <h4 style="color: #667eea; margin: 0 0 10px 0;">📝 ' . htmlspecialchars($postTitle) . '</h4>
                ' . ($content ? '<p style="color: #666; margin: 0; font-style: italic;">"' . htmlspecialchars(substr($content, 0, 200)) . (strlen($content) > 200 ? '...' : '') . '"</p>' : '') . '
            </div>
            <p style="text-align: center; margin: 25px 0;">
                <a href="#" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; display: inline-block;">Xem chi tiết</a>
            </p>
        ';
        
        return $this->getBaseTemplate('Thông báo diễn đàn', $contentHtml);
    }
    
    private function getVerificationCodeTemplate($name, $code, $expiresInMinutes)
    {
        $content = '
            <h2 style="color: #667eea; margin: 0 0 20px 0;">Xin chào ' . htmlspecialchars($name) . '!</h2>
            <p style="color: #333; line-height: 1.6;">
                Bạn đã yêu cầu mã xác thực để truy cập tài khoản. Dưới đây là mã của bạn:
            </p>
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin: 25px 0; text-align: center;">
                <span style="color: white; font-size: 36px; font-weight: bold; letter-spacing: 8px;">' . htmlspecialchars($code) . '</span>
            </div>
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
                <p style="color: #856404; margin: 0; font-size: 14px;">
                    ⚠️ <strong>Lưu ý:</strong> Mã này sẽ hết hạn sau <strong>' . $expiresInMinutes . ' phút</strong>. Vui lòng không chia sẻ mã này với bất kỳ ai.
                </p>
            </div>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay lập tức.
            </p>
        ';
        
        return $this->getBaseTemplate('Mã xác thực', $content);
    }

    private function get2FACodeTemplate($name, $code, $expiresInMinutes)
    {
        $content = '
            <h2 style="color: #667eea; margin: 0 0 20px 0;">🔒 Xác thực 2 yếu tố</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>,
            </p>
            <p style="color: #333; line-height: 1.6;">
                Chúng tôi nhận thấy có yêu cầu đăng nhập vào tài khoản của bạn. Để bảo mật tài khoản, vui lòng nhập mã xác thực dưới đây:
            </p>
            <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; border-radius: 10px; margin: 25px 0; text-align: center;">
                <span style="color: white; font-size: 42px; font-weight: bold; letter-spacing: 10px;">' . htmlspecialchars($code) . '</span>
            </div>
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
                <p style="color: #856404; margin: 0; font-size: 14px;">
                    ⚠️ <strong>Lưu ý bảo mật:</strong>
                </p>
                <ul style="color: #856404; margin: 10px 0 0 0; padding-left: 20px; font-size: 14px;">
                    <li>Mã này sẽ hết hạn sau <strong>' . $expiresInMinutes . ' phút</strong></li>
                    <li>Không chia sẻ mã này với bất kỳ ai</li>
                    <li>Chúng tôi sẽ không bao giờ hỏi mã này qua điện thoại</li>
                </ul>
            </div>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Nếu bạn không thực hiện yêu cầu đăng nhập này, vui lòng đổi mật khẩu ngay và liên hệ với chúng tôi.
            </p>
        ';
        
        return $this->getBaseTemplate('Xác thực 2 yếu tố', $content);
    }
    
    private function getMissedAppointmentTemplate($name, $date, $time, $doctorName)
    {
        $content = '
            <h2 style="color: #dc3545; margin: 0 0 20px 0;">⚠️ Lịch hẹn đã bị bỏ lỡ</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>,
            </p>
            <p style="color: #333; line-height: 1.6;">
                Chúng tôi nhận thấy bạn đã bỏ lỡ lịch hẹn khám bệnh. Dưới đây là thông tin chi tiết:
            </p>
            <div style="background-color: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #f5c6cb;">
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px 0; color: #721c24;">📅 Ngày khám:</td>
                        <td style="padding: 5px 0; color: #721c24; font-weight: bold;">' . htmlspecialchars($date) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #721c24;">⏰ Giờ khám:</td>
                        <td style="padding: 5px 0; color: #721c24; font-weight: bold;">' . htmlspecialchars($time) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #721c24;">👨‍⚕️ Bác sĩ:</td>
                        <td style="padding: 5px 0; color: #721c24; font-weight: bold;">' . htmlspecialchars($doctorName) . '</td>
                    </tr>
                </table>
            </div>
            <p style="color: #333; line-height: 1.6;">
                Nếu bạn muốn đặt lại lịch hẹn, vui lòng truy cập trang web của chúng tôi hoặc liên hệ hotline hỗ trợ.
            </p>
            <p style="text-align: center; margin: 25px 0;">
                <a href="#" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: bold; display: inline-block;">Đặt lịch mới</a>
            </p>
        ';
        
        return $this->getBaseTemplate('Lịch hẹn bị bỏ lỡ', $content);
    }

    /**
     * Send payment pending approval confirmation email
     */
    public function sendPaymentPendingConfirmation($toEmail, $toName, $transactionId, $amount, $paymentMethod, $appointmentDetails, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '⏳ Thanh toán đang chờ phê duyệt - Mã GD: ' . $transactionId;
            
            $body = $this->getPaymentPendingTemplate($toName, $transactionId, $amount, $paymentMethod, $appointmentDetails);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }

    /**
     * Send refund OTP email (SYSTEM email - always allowed if email is enabled)
     */
    public function sendRefundOtp($toEmail, $toName, $otp)
    {
        // OTP is a SYSTEM email - always allowed
        if (!$this->canSendEmail('SYSTEM')) {
            return ['success' => false, 'message' => 'Hệ thống email đang bảo trì', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '🔐 Mã xác nhận yêu cầu hoàn tiền';
            
            $body = $this->getRefundOtpTemplate($toName, $otp);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = "Mã xác nhận yêu cầu hoàn tiền của bạn là: {$otp}. Mã này có hiệu lực trong 10 phút.";
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }

    /**
     * Send refund approved notification email
     */
    public function sendRefundApprovedNotification($toEmail, $toName, $amount, $appointmentDetails, $userType = 'USER')
    {
        // Check if email is enabled for this user type
        if (!$this->canSendEmail($userType)) {
            return ['success' => false, 'message' => 'Email đã bị tắt cho loại người dùng này', 'disabled' => true];
        }
        
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($toEmail, $toName);
            
            $this->mailer->isHTML(true);
            $this->mailer->Subject = '💰 Hoàn tiền thành công!';
            
            $body = $this->getRefundApprovedTemplate($toName, $amount, $appointmentDetails);
            $this->mailer->Body = $body;
            $this->mailer->AltBody = strip_tags(str_replace(['<br>', '</div>'], "\n", $body));
            
            $this->mailer->send();
            return ['success' => true, 'message' => 'Email sent successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $this->mailer->ErrorInfo];
        }
    }

    private function getPaymentPendingTemplate($name, $transactionId, $amount, $paymentMethod, $appointmentDetails)
    {
        $content = '
            <h2 style="color: #ffc107; margin: 0 0 20px 0;">⏳ Thanh toán đang chờ phê duyệt</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>, chúng tôi đã nhận được yêu cầu thanh toán của bạn và đang chờ admin phê duyệt.
            </p>
            <div style="background-color: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
                <h3 style="color: #856404; margin: 0 0 15px 0;">Chi tiết giao dịch</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px 0; color: #856404;">Mã giao dịch:</td>
                        <td style="padding: 5px 0; color: #856404; font-weight: bold;">#' . htmlspecialchars($transactionId) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #856404;">Số tiền:</td>
                        <td style="padding: 5px 0; color: #856404; font-weight: bold;">' . number_format($amount, 0, ',', '.') . 'đ</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #856404;">Phương thức:</td>
                        <td style="padding: 5px 0; color: #856404; font-weight: bold;">' . htmlspecialchars($paymentMethod) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #856404;">Trạng thái:</td>
                        <td style="padding: 5px 0; color: #856404; font-weight: bold;">Đang chờ phê duyệt</td>
                    </tr>
                </table>
            </div>
            <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3 style="color: #667eea; margin: 0 0 15px 0;">Thông tin lịch hẹn</h3>
                <p style="color: #666; margin: 0;">' . htmlspecialchars($appointmentDetails) . '</p>
            </div>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Chúng tôi sẽ thông báo cho bạn khi thanh toán được phê duyệt. Thời gian xử lý thường từ 15-30 phút trong giờ làm việc.
            </p>
        ';
        
        return $this->getBaseTemplate('Thanh toán chờ phê duyệt', $content);
    }

    private function getRefundOtpTemplate($name, $otp)
    {
        $content = '
            <h2 style="color: #667eea; margin: 0 0 20px 0;">🔐 Xác nhận yêu cầu hoàn tiền</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>,
            </p>
            <p style="color: #333; line-height: 1.6;">
                Bạn đã yêu cầu hoàn tiền cho lịch hẹn. Vui lòng nhập mã xác nhận dưới đây để tiếp tục:
            </p>
            <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; border-radius: 10px; margin: 25px 0; text-align: center;">
                <span style="color: white; font-size: 42px; font-weight: bold; letter-spacing: 10px;">' . htmlspecialchars($otp) . '</span>
            </div>
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffc107;">
                <p style="color: #856404; margin: 0; font-size: 14px;">
                    ⚠️ <strong>Lưu ý:</strong>
                </p>
                <ul style="color: #856404; margin: 10px 0 0 0; padding-left: 20px; font-size: 14px;">
                    <li>Mã này có hiệu lực trong <strong>10 phút</strong></li>
                    <li>Không chia sẻ mã này với bất kỳ ai</li>
                    <li>Sau khi xác nhận, yêu cầu sẽ được gửi đến admin phê duyệt</li>
                </ul>
            </div>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Nếu bạn không yêu cầu hoàn tiền, vui lòng bỏ qua email này.
            </p>
        ';
        
        return $this->getBaseTemplate('Mã xác nhận hoàn tiền', $content);
    }

    private function getRefundApprovedTemplate($name, $amount, $appointmentDetails)
    {
        $content = '
            <h2 style="color: #28a745; margin: 0 0 20px 0;">💰 Hoàn tiền thành công!</h2>
            <p style="color: #333; line-height: 1.6;">
                Xin chào <strong>' . htmlspecialchars($name) . '</strong>,
            </p>
            <p style="color: #333; line-height: 1.6;">
                Yêu cầu hoàn tiền của bạn đã được phê duyệt. Chúng tôi đã hoàn lại số tiền vào tài khoản của bạn.
            </p>
            <div style="background-color: #d4edda; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #c3e6cb;">
                <h3 style="color: #155724; margin: 0 0 15px 0;">Chi tiết hoàn tiền</h3>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Số tiền hoàn:</td>
                        <td style="padding: 5px 0; color: #155724; font-weight: bold; font-size: 18px;">' . number_format($amount, 0, ',', '.') . 'đ</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Lịch hẹn:</td>
                        <td style="padding: 5px 0; color: #155724;">' . htmlspecialchars($appointmentDetails) . '</td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 0; color: #155724;">Thời gian:</td>
                        <td style="padding: 5px 0; color: #155724;">' . date('d/m/Y H:i') . '</td>
                    </tr>
                </table>
            </div>
            <p style="color: #333; line-height: 1.6;">
                Cảm ơn bạn đã sử dụng hệ thống đặt lịch bác sĩ của chúng tôi!
            </p>
            <p style="color: #666; line-height: 1.6; font-size: 14px;">
                Nếu có thắc mắc, vui lòng liên hệ hotline hỗ trợ.
            </p>
        ';
        
        return $this->getBaseTemplate('Hoàn tiền thành công', $content);
    }
}
