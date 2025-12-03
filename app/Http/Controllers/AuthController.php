<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserToken;
use App\Services\JwtService;
use App\Models\SystemSetting;
use App\Services\MailService;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->only(['full_name','email','phone','password']);
        $v = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|min:6',
            'password' => 'required|min:6',
        ]);
        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION','fields'=>$v->errors()], 422);
        }
        if (User::where('email',$data['email'])->exists()) {
            return response()->json(['error'=>'EMAIL_EXISTS'],409);
        }
        if (User::where('phone',$data['phone'])->exists()) {
            return response()->json(['error'=>'PHONE_EXISTS'],409);
        }
        $user = User::create([
            'full_name'=>$data['full_name'],
            'email'=>$data['email'],
            'phone'=>$data['phone'],
            'password'=>Hash::make($data['password']),
            'type'=>'USER',
            'status'=>'ACTIVE',
            'two_factor_enabled'=>false
        ]);
        $sessionId = (string) Str::uuid();
        $refreshPlain = Str::random(64);
        UserToken::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'refresh_token_hash' => hash('sha256', $refreshPlain),
            'expires_at' => now()->addDays(30),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);
        $access = app(JwtService::class)->createAccessToken($user, $sessionId);
        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'access_token' => $access,
            'refresh_token' => $refreshPlain,
            'session_id' => $sessionId
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->only(['email','password']);
        $v = Validator::make($data,[
            'email'=>'required|email',
            'password'=>'required'
        ]);
        if ($v->fails()) {
            return response()->json(['error'=>'VALIDATION','fields'=>$v->errors()],422);
        }
        $user = User::where('email',$data['email'])->first();
        if (!$user) {
            return response()->json(['error'=>'EMAIL_NOT_FOUND'],404);
        }
        
        // Check if account status is INACTIVE (manually locked by admin)
        if ($user->status === 'INACTIVE') {
            return response()->json([
                'error' => 'ACCOUNT_BLOCKED',
                'message' => 'Tài khoản của bạn đã bị khóa. Hãy liên hệ với chúng tôi để biết thêm chi tiết: nhom5@gmail.com'
            ], 403);
        }
        
        // Check if account is locked due to failed login attempts
        if ($user->locked_until && $user->locked_until > now()) {
            $remainingMinutes = now()->diffInMinutes($user->locked_until);
            return response()->json([
                'error' => 'ACCOUNT_LOCKED',
                'message' => "Tài khoản đã bị khóa do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau {$remainingMinutes} phút.",
                'locked_until' => $user->locked_until->toIso8601String()
            ], 423);
        }
        
        // If lock expired, reset the lock
        if ($user->locked_until && $user->locked_until <= now()) {
            $user->locked_until = null;
            $user->locked_at = null;
            $user->failed_login_attempts = 0;
            $user->save();
        }
        
        if (!Hash::check($data['password'],$user->password)) {
            // Check if auto-block is enabled
            $autoBlockEnabled = SystemSetting::get('auto_block_failed_login', true);
            $maxAttempts = (int) SystemSetting::get('max_failed_login_attempts', 5);
            
            if ($autoBlockEnabled) {
                // Increment failed attempts
                $user->failed_login_attempts = ($user->failed_login_attempts ?? 0) + 1;
                $user->last_failed_login_at = now();
                
                // Lock account if max attempts reached
                if ($user->failed_login_attempts >= $maxAttempts) {
                    $user->locked_at = now();
                    $user->locked_until = now()->addMinutes(30); // Lock for 30 minutes
                    $user->save();
                    
                    return response()->json([
                        'error' => 'ACCOUNT_LOCKED',
                        'message' => "Tài khoản đã bị khóa do đăng nhập sai {$maxAttempts} lần liên tiếp. Vui lòng thử lại sau 30 phút.",
                        'locked_until' => $user->locked_until->toIso8601String()
                    ], 423);
                }
                
                $user->save();
                $remainingAttempts = $maxAttempts - $user->failed_login_attempts;
                return response()->json([
                    'error' => 'WRONG_PASSWORD',
                    'message' => "Mật khẩu không đúng. Còn {$remainingAttempts} lần thử.",
                    'remaining_attempts' => $remainingAttempts
                ], 401);
            }
            
            return response()->json(['error'=>'WRONG_PASSWORD'],401);
        }
        
        // Login successful - reset failed attempts
        if ($user->failed_login_attempts > 0) {
            $user->failed_login_attempts = 0;
            $user->locked_at = null;
            $user->locked_until = null;
        }

        // Check access control based on user type
        if ($user->type === 'USER' && !SystemSetting::isUserAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bệnh nhân đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }
        
        if ($user->type === 'DOCTOR' && !SystemSetting::isDoctorAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bác sĩ đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }

        // Check if 2FA is enabled (only for USER and DOCTOR, not ADMIN)
        if ($user->two_factor_enabled && in_array($user->type, ['USER', 'DOCTOR'])) {
            // Generate and send 2FA code
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store code in verification_tokens table
            DB::table('verification_tokens')->insert([
                'user_id' => $user->id,
                'email' => $user->email,
                'verification_code' => $code,
                'token_hash' => hash('sha256', $code),
                'token_type' => '_2FA',
                'expires_at' => now()->addMinutes(10),
                'is_active' => 1,
                'created_at' => now(),
            ]);

            // Send email with 2FA code
            try {
                $mailService = new \App\Services\MailService();
                $mailService->send2FACode($user->email, $user->full_name, $code);
            } catch (\Exception $e) {
                \Log::error('Failed to send 2FA email', ['error' => $e->getMessage()]);
            }

            return response()->json([
                'requires_2fa' => true,
                'email' => $user->email,
                'message' => 'Mã xác thực đã được gửi đến email của bạn'
            ], 200);
        }

        $user->markLogin();
        $sessionId = (string) Str::uuid();
        $refreshPlain = Str::random(64);
        UserToken::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'refresh_token_hash' => hash('sha256', $refreshPlain),
            'expires_at' => now()->addDays(30),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);
        $access = app(JwtService::class)->createAccessToken($user, $sessionId);
        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'access_token' => $access,
            'refresh_token' => $refreshPlain,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Verify 2FA code and complete login
     */
    public function verify2FA(Request $request)
    {
        $data = $request->only(['email', 'code']);
        $v = Validator::make($data, [
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);
        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }

        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->json(['error' => 'EMAIL_NOT_FOUND'], 404);
        }

        // Verify the 2FA code
        $tokenRecord = DB::table('verification_tokens')
            ->where('user_id', $user->id)
            ->where('verification_code', $data['code'])
            ->where('token_type', '_2FA')
            ->where('expires_at', '>', now())
            ->where('is_active', 1)
            ->whereNull('used_at')
            ->first();

        if (!$tokenRecord) {
            return response()->json(['error' => 'INVALID_2FA_CODE', 'message' => 'Mã xác thực không hợp lệ hoặc đã hết hạn'], 400);
        }

        // Mark token as used
        DB::table('verification_tokens')
            ->where('id', $tokenRecord->id)
            ->update(['used_at' => now(), 'is_active' => 0]);

        // Check access control based on user type
        if ($user->type === 'USER' && !SystemSetting::isUserAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bệnh nhân đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }
        
        if ($user->type === 'DOCTOR' && !SystemSetting::isDoctorAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bác sĩ đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }

        // Complete login
        $user->markLogin();
        $sessionId = (string) Str::uuid();
        $refreshPlain = Str::random(64);
        UserToken::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'refresh_token_hash' => hash('sha256', $refreshPlain),
            'expires_at' => now()->addDays(30),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);
        $access = app(JwtService::class)->createAccessToken($user, $sessionId);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'type' => $user->type,
            ],
            'access_token' => $access,
            'refresh_token' => $refreshPlain,
            'session_id' => $sessionId
        ]);
    }

    public function refresh(Request $request)
    {
        $refresh = $request->input('refresh_token');
        $sessionId = $request->input('session_id');
        if (!$refresh || !$sessionId) {
            return response()->json(['message' => 'Missing refresh token or session id'], 400);
        }
        $tokenRow = UserToken::where('session_id',$sessionId)->active()->first();
        if (!$tokenRow || !hash_equals($tokenRow->refresh_token_hash, hash('sha256', $refresh))) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }
        if ($tokenRow->expires_at->isPast()) {
            return response()->json(['message' => 'Refresh token expired'], 401);
        }
        $user = $tokenRow->user;
        // Rotate refresh
        $newRefresh = Str::random(64);
        $tokenRow->refresh_token_hash = hash('sha256', $newRefresh);
        $tokenRow->save();
        $access = app(JwtService::class)->createAccessToken($user, $sessionId);
        return response()->json([
            'access_token' => $access,
            'refresh_token' => $newRefresh,
            'session_id' => $sessionId,
        ]);
    }

    public function logout(Request $request)
    {
        $sessionId = $request->input('session_id');
        if (!$sessionId) {
            return response()->json(['message' => 'Missing session id'], 400);
        }
        $tokenRow = UserToken::where('session_id',$sessionId)->active()->first();
        if ($tokenRow) {
            $tokenRow->revoked_at = now();
            $tokenRow->save();
        }
        return response()->json(['message' => 'Logged out']);
    }

    public function googleLogin(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json(['error'=>'MISSING_EMAIL', 'message'=>'Thiếu email từ Google'],400);
        }

        // Lấy đầy đủ thông tin từ Google
        $fullName = $request->input('full_name', $email);
        $avatarUrl = $request->input('avatar_url');
        $dob = $request->input('dob'); // Ngày sinh (nếu có)
        $gender = $request->input('gender'); // Giới tính: MALE, FEMALE, OTHER
        $address = $request->input('address');

        // Validate gender nếu có
        if ($gender && !in_array(strtoupper($gender), ['MALE', 'FEMALE', 'OTHER'])) {
            $gender = null;
        } else if ($gender) {
            $gender = strtoupper($gender);
        }

        // Kiểm tra user đã tồn tại chưa
        $existingUser = User::where('email', $email)->first();
        
        if ($existingUser) {
            // Check if account status is INACTIVE (manually locked by admin)
            if ($existingUser->status === 'INACTIVE') {
                return response()->json([
                    'error' => 'ACCOUNT_BLOCKED',
                    'message' => 'Tài khoản của bạn đã bị khóa. Hãy liên hệ với chúng tôi để biết thêm chi tiết: nhom5@gmail.com'
                ], 403);
            }
            
            // Check if 2FA is enabled for existing user
            if ($existingUser->two_factor_enabled && in_array($existingUser->type, ['USER', 'DOCTOR'])) {
                // Generate and send 2FA code
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                
                // Store code in verification_tokens table
                DB::table('verification_tokens')->insert([
                    'user_id' => $existingUser->id,
                    'email' => $existingUser->email,
                    'verification_code' => $code,
                    'token_hash' => hash('sha256', $code),
                    'token_type' => '_2FA',
                    'expires_at' => now()->addMinutes(10),
                    'is_active' => 1,
                    'created_at' => now(),
                ]);

                // Send email with 2FA code
                try {
                    $mailService = new \App\Services\MailService();
                    $mailService->send2FACode($existingUser->email, $existingUser->full_name, $code);
                } catch (\Exception $e) {
                    \Log::error('Failed to send 2FA email', ['error' => $e->getMessage()]);
                }

                return response()->json([
                    'requires_2fa' => true,
                    'email' => $existingUser->email,
                    'message' => 'Mã xác thực đã được gửi đến email của bạn'
                ], 200);
            }
            
            // User đã tồn tại - chỉ cập nhật thông tin nếu chưa có
            if (!$existingUser->avatar_url && $avatarUrl) {
                $existingUser->avatar_url = $avatarUrl;
            }
            if (!$existingUser->dob && $dob) {
                $existingUser->dob = $dob;
            }
            if (!$existingUser->gender && $gender) {
                $existingUser->gender = $gender;
            }
            if (!$existingUser->address && $address) {
                $existingUser->address = $address;
            }
            $existingUser->save();
            $user = $existingUser;
        } else {
            // Check if new user registration (USER type) is allowed
            if (!SystemSetting::isUserAccessEnabled()) {
                return response()->json([
                    'error' => 'ACCESS_DISABLED',
                    'message' => 'Đăng ký tài khoản đang tạm ngưng. Vui lòng quay lại sau.'
                ], 403);
            }
            
            // Tạo user mới với type = USER, password = null (không cần cho Google login)
            $user = User::create([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => null, // Không cần thiết cho Google login
                'password' => null, // Không cần mật khẩu cho Google login
                'avatar_url' => $avatarUrl,
                'dob' => $dob,
                'gender' => $gender,
                'address' => $address,
                'type' => 'USER', // Mặc định USER
                'status' => 'ACTIVE'
            ]);
        }

        // Check access control based on user type
        if ($user->type === 'USER' && !SystemSetting::isUserAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bệnh nhân đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }
        
        if ($user->type === 'DOCTOR' && !SystemSetting::isDoctorAccessEnabled()) {
            return response()->json([
                'error' => 'ACCESS_DISABLED',
                'message' => 'Dữ liệu của bác sĩ đang được bảo trì. Vui lòng quay lại sau.'
            ], 403);
        }

        $user->markLogin();
        $sessionId = (string) Str::uuid();
        $refreshPlain = Str::random(64);
        UserToken::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'refresh_token_hash' => hash('sha256', $refreshPlain),
            'expires_at' => now()->addDays(30),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);
        $access = app(JwtService::class)->createAccessToken($user, $sessionId);
        return response()->json([
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'dob' => $user->dob,
                'gender' => $user->gender,
                'address' => $user->address,
                'type' => $user->type,
            ],
            'access_token' => $access,
            'refresh_token' => $refreshPlain,
            'session_id' => $sessionId
        ]);
    }

    /**
     * Send verification code to email (for password reset, etc.)
     * Code expires in 5 minutes
     */
    public function sendVerificationCode(Request $request)
    {
        $v = Validator::make($request->only(['email', 'type']), [
            'email' => 'required|email',
            'type' => 'required|in:RESET_PASSWORD,EMAIL_VERIFY,_2FA'
        ]);
        
        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }
        
        $email = $request->input('email');
        $type = $request->input('type');
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'EMAIL_NOT_FOUND', 'message' => 'Email không tồn tại trong hệ thống'], 404);
        }
        
        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Delete any existing codes for this user and type
        DB::table('verification_tokens')
            ->where('user_id', $user->id)
            ->where('token_type', $type)
            ->delete();
        
        // Create new verification token with 5-minute expiry
        $tokenId = DB::table('verification_tokens')->insertGetId([
            'user_id' => $user->id,
            'email' => $user->email,
            'token_type' => $type,
            'token_hash' => Hash::make($code . $user->id),
            'verification_code' => Hash::make($code),
            'expires_at' => now()->addMinutes(5),
            'is_active' => true,
            'used_at' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Send email with verification code
        try {
            $mailService = new MailService();
            $result = $mailService->sendVerificationCode(
                $user->email,
                $user->full_name ?? 'Quý khách',
                $code,
                5 // 5 minutes expiry
            );
            
            if (!$result['success']) {
                return response()->json([
                    'error' => 'EMAIL_FAILED',
                    'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send verification code email: ' . $e->getMessage());
            return response()->json([
                'error' => 'EMAIL_FAILED',
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Mã xác thực đã được gửi đến email của bạn',
            'expires_in' => 300 // 5 minutes in seconds
        ]);
    }
    
    /**
     * Verify the code sent to email
     */
    public function verifyCode(Request $request)
    {
        $v = Validator::make($request->only(['email', 'code', 'type']), [
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'type' => 'required|in:RESET_PASSWORD,EMAIL_VERIFY,_2FA'
        ]);
        
        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }
        
        $email = $request->input('email');
        $code = $request->input('code');
        $type = $request->input('type');
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'EMAIL_NOT_FOUND'], 404);
        }
        
        $token = DB::table('verification_tokens')
            ->where('user_id', $user->id)
            ->where('token_type', $type)
            ->where('is_active', true)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$token) {
            return response()->json([
                'error' => 'CODE_EXPIRED',
                'message' => 'Mã xác thực đã hết hạn hoặc không tồn tại'
            ], 400);
        }
        
        if (!Hash::check($code, $token->verification_code)) {
            return response()->json([
                'error' => 'INVALID_CODE',
                'message' => 'Mã xác thực không chính xác'
            ], 400);
        }
        
        // Mark code as used
        DB::table('verification_tokens')
            ->where('id', $token->id)
            ->update([
                'is_active' => false,
                'used_at' => now(),
                'updated_at' => now()
            ]);
        
        // Generate a temporary token for password reset
        $tempToken = Str::random(64);
        
        // Store temp token for password reset (valid for 10 minutes)
        if ($type === 'RESET_PASSWORD') {
            // Delete existing record first
            DB::table('password_resets')->where('email', $email)->delete();
            
            // Insert new record with all required fields
            DB::table('password_resets')->insert([
                'email' => $email,
                'token_hash' => Hash::make($tempToken),
                'verification_code' => Hash::make($tempToken),
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
                'attempts' => 0
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Xác thực thành công',
            'temp_token' => $tempToken
        ]);
    }
    
    /**
     * Reset password with verified code
     */
    public function resetPassword(Request $request)
    {
        $v = Validator::make($request->only(['email', 'temp_token', 'new_password', 'confirm_password']), [
            'email' => 'required|email',
            'temp_token' => 'required|string',
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);
        
        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }
        
        $email = $request->input('email');
        $tempToken = $request->input('temp_token');
        $newPassword = $request->input('new_password');
        
        // Verify temp token
        $reset = DB::table('password_resets')
            ->where('email', $email)
            ->where('expires_at', '>', now())
            ->whereNull('used_at')
            ->first();
        
        if (!$reset || !Hash::check($tempToken, $reset->token_hash)) {
            return response()->json([
                'error' => 'INVALID_TOKEN',
                'message' => 'Token không hợp lệ hoặc đã hết hạn'
            ], 400);
        }
        
        // Update password
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['error' => 'USER_NOT_FOUND'], 404);
        }
        
        $user->password = Hash::make($newPassword);
        $user->save();
        
        // Delete reset token
        DB::table('password_resets')->where('email', $email)->delete();
        
        // Revoke all existing sessions for security
        UserToken::where('user_id', $user->id)->update(['revoked_at' => now()]);
        
        return response()->json([
            'success' => true,
            'message' => 'Mật khẩu đã được cập nhật thành công'
        ]);
    }
}
