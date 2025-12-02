<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\SystemSetting;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function me(Request $request)
    {
        $u = $request->user();
        return response()->json([
            'id' => $u->id,
            'full_name' => $u->full_name,
            'email' => $u->email,
            'type' => $u->type,
            'avatar_url' => $u->avatar_url,
            'status' => $u->status,
            'gender' => $u->gender,
            'dob' => optional($u->dob)?->format('Y-m-d'),
            'phone' => $u->phone,
            'address' => $u->address,
            'created_at' => optional($u->created_at)?->format('Y-m-d H:i:s'),
            'two_factor_enabled' => (bool) $u->two_factor_enabled,
        ]);
    }

    public function update(Request $request)
    {
        $u = $request->user();
        $fields = $request->only(['full_name','avatar_url','address','phone','dob','gender']);
        unset($fields['email']); // Ensure email cannot be updated
        foreach ($fields as $k=>$v) {
            if ($v !== null) {
                if ($k === 'dob' && $v) {
                    // Chỉ nhận đúng định dạng yyyy-mm-dd, nếu không thì bỏ qua
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $v)) {
                        $u->dob = $v;
                    }
                } elseif ($k === 'gender' && $v) {
                    $validGenders = ['MALE','FEMALE','OTHER'];
                    $gender = strtoupper($v);
                    if (in_array($gender, $validGenders)) {
                        $u->gender = $gender;
                    }
                } else {
                    $u->$k = $v;
                }
            }
        }
        $u->save();
        return response()->json(['message' => 'Updated']);
    }

    public function updateDoctorProfile(Request $request)
    {
        $u = $request->user();
        if ($u->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->only(['degree','experience','description','specialization_id','clinic_id','rating_avg']);
        $serviceIds = $request->input('service_ids', []);

        $doctor = Doctor::where('user_id', $u->id)->first();
        if (!$doctor) {
            // Create a doctor row if missing
            $doctor = Doctor::create(array_merge($data, ['user_id' => $u->id]));
        } else {
            foreach ($data as $k => $v) {
                if ($v !== null) {
                    // Basic validation/conversion
                    if ($k === 'experience') {
                        $doctor->$k = is_numeric($v) ? intval($v) : $doctor->$k;
                    } elseif ($k === 'specialization_id' || $k === 'clinic_id') {
                        $doctor->$k = $v ? intval($v) : null;
                    } elseif ($k === 'rating_avg') {
                        $doctor->$k = is_numeric($v) ? floatval($v) : $doctor->$k;
                    } else {
                        $doctor->$k = $v;
                    }
                }
            }
            $doctor->save();
        }

        // Sync services if provided
        if (is_array($serviceIds)) {
            DB::table('doctor_service')->where('doctor_id', $doctor->id)->delete();
            foreach ($serviceIds as $serviceId) {
                if ($serviceId) {
                    DB::table('doctor_service')->insert([
                        'doctor_id' => $doctor->id,
                        'service_id' => intval($serviceId),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Doctor profile updated', 'doctor' => $doctor]);
    }

    public function doctorProfile(Request $request)
    {
        $u = $request->user();
        if ($u->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        // Get doctor with specialization and clinic
        $doctor = DB::table('doctor')
            ->leftJoin('specialization', 'specialization.id', '=', 'doctor.specialization_id')
            ->leftJoin('clinic', 'clinic.id', '=', 'doctor.clinic_id')
            ->select(
                'doctor.*',
                'specialization.name as specialization_name',
                'clinic.name as clinic_name'
            )
            ->where('doctor.user_id', $u->id)
            ->first();

        // Get doctor's service IDs
        if ($doctor) {
            $serviceIds = DB::table('doctor_service')
                ->where('doctor_id', $doctor->id)
                ->pluck('service_id')
                ->toArray();
            $doctor->service_ids = $serviceIds;
        }

        return response()->json(['doctor' => $doctor]);
    }

    public function markNotificationRead(Request $request, $id)
    {
        $user = $request->user();
        if ($id === 'all') {
            DB::table('notification')->where('user_id', $user->id)->update(['is_read' => 1]);
        } else {
            DB::table('notification')->where('id', $id)->where('user_id', $user->id)->update(['is_read' => 1]);
        }
        return response()->json(['message' => 'Marked as read']);
    }

    public function deleteNotification(Request $request, $id)
    {
        $user = $request->user();
        if ($id === 'all') {
            DB::table('notification')->where('user_id', $user->id)->delete();
        } else {
            DB::table('notification')->where('id', $id)->where('user_id', $user->id)->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }

    public function userDashboard(Request $request)
    {
        $user = $request->user();
        
        // Build condition to find appointments by patient_id OR by email/phone match
        $appointmentCondition = function($query) use ($user) {
            $query->where('appt.patient_id', $user->id);
            // Also match by email if user has email
            if ($user->email) {
                $query->orWhere('appt.patient_email', $user->email);
            }
            // Also match by phone if user has phone
            if ($user->phone) {
                $query->orWhere('appt.patient_phone', $user->phone);
            }
        };
        
        $appointments = DB::table('appointment_schedules as appt')
            ->leftJoin('doctor','doctor.id','=','appt.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->leftJoin('clinic','clinic.id','=','appt.clinic_id')
            ->leftJoin('specialization','specialization.id','=','doctor.specialization_id')
            ->leftJoin('payment_methods as pm','pm.id','=','appt.payment_method_id')
            ->select(
                'appt.*',
                'doctor.id as doctor_id',
                'doctor.degree as doctor_degree',
                'doctor.experience as doctor_experience',
                'doctor.rating_avg as doctor_rating',
                'doc_user.full_name as doctor_name',
                'doc_user.avatar_url as doctor_avatar',
                'doc_user.phone as doctor_phone',
                'doc_user.email as doctor_email',
                'specialization.name as doctor_specialization',
                'clinic.name as clinic_name',
                'clinic.address as clinic_address',
                'pm.method_name as payment_method',
                'pm.method as payment_method_type'
            )
            ->where($appointmentCondition)
            ->orderByDesc('appt.appointment_date')
            ->limit(20)
            ->get();

        $medicalHistory = DB::table('appointment_schedules as appt')
            ->leftJoin('doctor','doctor.id','=','appt.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->leftJoin('clinic','clinic.id','=','appt.clinic_id')
            ->leftJoin('specialization','specialization.id','=','doctor.specialization_id')
            ->select(
                'appt.*',
                'doc_user.full_name as doctor_name',
                'doc_user.avatar_url as doctor_avatar',
                'specialization.name as specialization_name',
                'clinic.name as clinic_name',
                'clinic.address as clinic_address'
            )
            ->where($appointmentCondition)
            ->whereIn('appt.status', ['completed', 'cancelled', 'missed'])
            ->orderByDesc('appt.appointment_date')
            ->limit(50)
            ->get();

        $notifications = DB::table('notification')
            ->where('user_id',$user->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $paymentMethods = DB::table('payment_methods')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $transactions = DB::table('appointment_schedules as appt')
            ->leftJoin('doctor','doctor.id','=','appt.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->leftJoin('clinic','clinic.id','=','appt.clinic_id')
            ->leftJoin('payment_methods as pm','pm.id','=','appt.payment_method_id')
            ->leftJoin('specialization','specialization.id','=','doctor.specialization_id')
            ->select(
                'appt.*',
                'doc_user.full_name as doctor_name',
                'doc_user.avatar_url as doctor_avatar',
                'doc_user.phone as doctor_phone',
                'doc_user.email as doctor_email',
                'doctor.degree as doctor_degree',
                'doctor.experience as doctor_experience',
                'doctor.rating_avg as doctor_rating',
                'specialization.name as doctor_specialization',
                'clinic.name as clinic_name',
                'clinic.address as clinic_address',
                'pm.method_name as payment_method_name',
                'pm.masked_detail as payment_detail',
                'pm.method_name as payment_method'
            )
            ->where($appointmentCondition)
            ->where(function($q) {
                // Include appointments with payment status OR those with fee_amount > 0
                $q->whereIn('appt.payment_status', ['PAID', 'PENDING_APPROVAL', 'REFUND_PENDING', 'REFUNDED'])
                  ->orWhere('appt.fee_amount', '>', 0);
            })
            ->orderByDesc('appt.updated_at')
            ->limit(50)
            ->get();

        $reviews = DB::table('review as rv')
            ->leftJoin('doctor','doctor.id','=','rv.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->select('rv.*','doc_user.full_name as doctor_name', 'doc_user.avatar_url as doctor_avatar')
            ->where('rv.user_id',$user->id)
            ->orderByDesc('rv.created_at')
            ->limit(20)
            ->get();

        // Get list of doctor_ids that user has already reviewed
        $reviewedDoctorIds = DB::table('review')
            ->where('user_id', $user->id)
            ->pluck('doctor_id')
            ->toArray();

        $reviewableAppointments = DB::table('appointment_schedules as appt')
            ->leftJoin('doctor','doctor.id','=','appt.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->leftJoin('review','review.appointment_id','=','appt.id')
            ->select(
                'appt.id','appt.appointment_date','appt.status','appt.doctor_id',
                'doc_user.full_name as doctor_name', 'doc_user.avatar_url as doctor_avatar'
            )
            ->where($appointmentCondition)
            ->where('appt.status','completed')
            ->whereNull('review.id')
            ->whereNotIn('appt.doctor_id', $reviewedDoctorIds) // Exclude doctors already reviewed
            ->get();

        // Forum likes of current user (posts only for now)
        $userLikes = DB::table('forum_like')
            ->select('post_id')
            ->where('user_id', $user->id)
            ->where('type', 'POST')
            ->whereNotNull('post_id')
            ->get();

        return response()->json([
            'appointments' => $appointments,
            'medical_notes' => $medicalHistory,
            'notifications' => $notifications,
            'payment_methods' => $paymentMethods,
            'transactions' => $transactions,
            'reviews' => $reviews,
            'reviewable_appointments' => $reviewableAppointments,
            'user_likes' => $userLikes,
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $user = $request->user();
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            // Store avatar on the public disk, not default disk
            $path = $file->storeAs('avatars', $filename, 'public');
            $url = '/storage/avatars/' . $filename;
            $user->avatar_url = $url;
            $user->save();
            return response()->json(['avatar_url' => $url]);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    // Upload avatar for any user (admin only) - returns URL without saving to user
    public function uploadAvatarFile(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Store avatar on the public disk
            $path = $file->storeAs('avatars', $filename, 'public');
            $url = '/storage/avatars/' . $filename;
            return response()->json(['avatar_url' => $url]);
        }
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    // Upload specialization image
    public function uploadSpecializationImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Validate file
            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }
            
            $filename = 'spec_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Store on the public disk
            $path = $file->storeAs('specializations', $filename, 'public');
            $url = '/storage/specializations/' . $filename;
            
            return response()->json([
                'success' => true,
                'image_url' => $url,
                'url' => $url
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'No file uploaded'
        ], 400);
    }

    public function addPaymentMethod(Request $request)
    {
        $user = $request->user();
        $type = $request->input('type');
        $data = $request->all();
        $methodName = null;
        $maskedDetail = null;
        $insert = [
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if ($type === 'card') {
            $methodName = 'Thẻ ' . substr($data['card_number'] ?? '', -4);
            $maskedDetail = '**** ' . substr($data['card_number'] ?? '', -4);
            $insert = array_merge($insert, [
                'method' => 'CREDIT_CARD',
                'method_name' => $methodName,
                'masked_detail' => $maskedDetail,
                'card_number' => $data['card_number'] ?? null,
                'card_holder' => $data['card_holder'] ?? null,
                'expiry_month' => $data['expiry_month'] ?? null,
                'expiry_year' => $data['expiry_year'] ?? null,
                'cvv' => $data['cvv'] ?? null,
            ]);
        } elseif ($type === 'wallet') {
            $methodName = 'Ví ' . ($data['wallet_type'] ?? '');
            $maskedDetail = $data['wallet_number'] ?? '';
            $insert = array_merge($insert, [
                'method' => strtoupper($data['wallet_type'] ?? 'WALLET'),
                'method_name' => $methodName,
                'masked_detail' => $maskedDetail,
                'wallet_number' => $data['wallet_number'] ?? null,
                'wallet_type' => $data['wallet_type'] ?? null,
            ]);
        } elseif ($type === 'bank') {
            $methodName = 'TK ' . ($data['bank_name'] ?? '');
            $maskedDetail = $data['bank_account'] ?? '';
            $insert = array_merge($insert, [
                'method' => 'BANK_TRANSFER',
                'method_name' => $methodName,
                'masked_detail' => $maskedDetail,
                'bank_account' => $data['bank_account'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
            ]);
        } else {
            return response()->json(['message' => 'Loại phương thức không hợp lệ'], 400);
        }
        $id = DB::table('payment_methods')->insertGetId($insert);
        return response()->json(['message' => 'Đã thêm phương thức thanh toán', 'id' => $id]);
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        $user = $request->user();
        $method = DB::table('payment_methods')->where('id', $id)->where('user_id', $user->id)->first();
        if (!$method) {
            return response()->json(['message' => 'Không tìm thấy phương thức thanh toán'], 404);
        }
        $data = $request->only([
            'method', 'method_name', 'masked_detail',
            'card_number', 'card_holder', 'expiry_month', 'expiry_year', 'cvv',
            'wallet_number', 'wallet_type',
            'bank_account', 'bank_name'
        ]);
        $data['updated_at'] = now();
        DB::table('payment_methods')->where('id', $id)->update($data);
        return response()->json(['message' => 'Đã cập nhật phương thức thanh toán']);
    }

    public function deletePaymentMethod(Request $request, $id)
    {
        $user = $request->user();
        $deleted = DB::table('payment_methods')->where('id', $id)->where('user_id', $user->id)->delete();
        if ($deleted) {
            return response()->json(['message' => 'Đã xóa phương thức thanh toán']);
        } else {
            return response()->json(['message' => 'Không tìm thấy phương thức thanh toán'], 404);
        }
    }

    public function requestOnlinePayment(Request $request, $appointmentId)
    {
        // Check if payment system is enabled
        if (!SystemSetting::isPaymentEnabled()) {
            return response()->json([
                'message' => 'Chức năng thanh toán hệ thống đang bảo trì. Vui lòng thử lại sau.'
            ], 503);
        }
        
        $user = $request->user();
        
        // Find appointment by id AND (patient_id OR patient_email OR patient_phone match)
        $appointment = DB::table('appointment_schedules')
            ->where('id', $appointmentId)
            ->where(function($query) use ($user) {
                $query->where('patient_id', $user->id);
                if ($user->email) {
                    $query->orWhere('patient_email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('patient_phone', $user->phone);
                }
            })
            ->first();
            
        if (!$appointment) {
            return response()->json(['message'=>'Lịch hẹn không tồn tại hoặc không thuộc về bạn'],404);
        }
        // Allow payment for confirmed or booked appointments
        if (!in_array($appointment->status, ['confirmed', 'booked'])) {
            return response()->json(['message'=>'Chỉ thanh toán cho lịch đã xác nhận'],400);
        }
        if (in_array($appointment->payment_status, ['PAID', 'PENDING_APPROVAL'])) {
            return response()->json(['message'=>'Lịch đã thanh toán hoặc đang chờ duyệt'],400);
        }
        // Block payment if refund was approved (REFUNDED status)
        if ($appointment->payment_status === 'REFUNDED') {
            return response()->json(['message'=>'Lịch hẹn đã được hoàn tiền, không thể thanh toán lại'],400);
        }
        $methodId = $request->input('payment_method_id');
        $method = DB::table('payment_methods')
            ->where('id',$methodId)
            ->where('user_id',$user->id)
            ->first();
        if (!$method) {
            return response()->json(['message'=>'Phương thức không hợp lệ'],422);
        }
        
        // Generate transaction ID
        $transactionId = 'TXN' . date('YmdHis') . $appointment->id;
        
        // Set to PENDING_APPROVAL - wait for admin approval
        DB::table('appointment_schedules')
            ->where('id',$appointment->id)
            ->update([
                'payment_status'=>'PENDING_APPROVAL',
                'payment_method_id'=>$method->id,
                'paid_at'=>now(),
                'refund_status'=>'NONE',
                'refund_locked'=>false,
                'transaction_id'=>$transactionId
            ]);
        
        // Get appointment details for email
        $doctor = DB::table('doctor')
            ->leftJoin('user', 'user.id', '=', 'doctor.user_id')
            ->where('doctor.id', $appointment->doctor_id)
            ->select('user.full_name')
            ->first();
        
        $appointmentDetails = "Ngày khám: {$appointment->appointment_date}, Giờ: {$appointment->start_time}";
        if ($doctor) {
            $appointmentDetails .= ", Bác sĩ: {$doctor->full_name}";
        }
        
        // Create notification - pending approval
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Thanh toán đang chờ duyệt',
            'message' => "Thanh toán của bạn cho lịch hẹn ngày {$appointment->appointment_date} đang chờ admin phê duyệt. Mã giao dịch: {$transactionId}",
            'type' => 2,
            'related_id' => $appointment->id,
            'is_read' => false,
            'sent_via' => 1
        ]);
        
        // Send email confirmation - pending
        if ($user->email) {
            try {
                $mailService = new \App\Services\MailService();
                $mailService->sendPaymentPendingConfirmation(
                    $user->email,
                    $user->full_name ?? 'Quý khách',
                    $transactionId,
                    $appointment->fee_amount ?? 0,
                    $method->method_name ?? $method->method ?? 'Không xác định',
                    $appointmentDetails
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send payment pending email: ' . $e->getMessage());
            }
        }
        
        return response()->json(['message'=>'Thanh toán đang chờ admin phê duyệt', 'transaction_id' => $transactionId]);
    }

    /**
     * Gửi OTP xác nhận yêu cầu hoàn tiền
     */
    public function sendRefundOtp(Request $request, $appointmentId)
    {
        $user = $request->user();
        $appointment = DB::table('appointment_schedules')
            ->where('id', $appointmentId)
            ->where(function($query) use ($user) {
                $query->where('patient_id', $user->id);
                if ($user->email) {
                    $query->orWhere('patient_email', $user->email);
                }
            })
            ->first();
            
        if (!$appointment) {
            return response()->json(['message'=>'Lịch hẹn không tồn tại'],404);
        }
        
        if ($appointment->payment_status !== 'PAID') {
            return response()->json(['message'=>'Chỉ có thể hoàn tiền cho lịch đã thanh toán'],400);
        }
        
        if ($appointment->refund_status === 'REQUESTED' || $appointment->payment_status === 'REFUND_PENDING') {
            return response()->json(['message'=>'Đã có yêu cầu hoàn tiền đang chờ xử lý'],400);
        }
        
        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(10);
        
        DB::table('appointment_schedules')
            ->where('id', $appointment->id)
            ->update([
                'refund_otp' => $otp,
                'refund_otp_expires_at' => $expiresAt
            ]);
        
        // Send OTP via email
        $email = $user->email ?? $appointment->patient_email;
        if ($email) {
            try {
                $mailService = new \App\Services\MailService();
                $mailService->sendRefundOtp($email, $user->full_name ?? 'Quý khách', $otp);
            } catch (\Exception $e) {
                \Log::error('Failed to send refund OTP: ' . $e->getMessage());
                return response()->json(['message'=>'Không thể gửi mã xác nhận. Vui lòng thử lại.'],500);
            }
        }
        
        return response()->json(['message'=>'Đã gửi mã xác nhận đến email của bạn']);
    }

    /**
     * Xác nhận OTP và gửi yêu cầu hoàn tiền
     */
    public function confirmRefundOtp(Request $request, $appointmentId)
    {
        $user = $request->user();
        $otp = $request->input('otp');
        $reason = $request->input('reason', '');
        
        if (!$otp || strlen($otp) !== 6) {
            return response()->json(['message'=>'Mã OTP không hợp lệ'],422);
        }
        
        $appointment = DB::table('appointment_schedules')
            ->where('id', $appointmentId)
            ->where(function($query) use ($user) {
                $query->where('patient_id', $user->id);
                if ($user->email) {
                    $query->orWhere('patient_email', $user->email);
                }
            })
            ->first();
            
        if (!$appointment) {
            return response()->json(['message'=>'Lịch hẹn không tồn tại'],404);
        }
        
        if ($appointment->payment_status !== 'PAID') {
            return response()->json(['message'=>'Chỉ có thể hoàn tiền cho lịch đã thanh toán'],400);
        }
        
        // Verify OTP
        if ($appointment->refund_otp !== $otp) {
            return response()->json(['message'=>'Mã OTP không chính xác'],400);
        }
        
        if ($appointment->refund_otp_expires_at && now()->gt($appointment->refund_otp_expires_at)) {
            return response()->json(['message'=>'Mã OTP đã hết hạn. Vui lòng gửi lại.'],400);
        }
        
        // Update to REFUND_PENDING
        DB::table('appointment_schedules')
            ->where('id', $appointment->id)
            ->update([
                'payment_status' => 'REFUND_PENDING',
                'refund_status' => 'REQUESTED',
                'refund_requested_at' => now(),
                'refund_reason' => $reason,
                'refund_otp' => null,
                'refund_otp_expires_at' => null
            ]);
        
        // Create notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Yêu cầu hoàn tiền đang chờ duyệt',
            'message' => "Yêu cầu hoàn tiền cho lịch hẹn ngày {$appointment->appointment_date} đang chờ admin phê duyệt.",
            'type' => 2,
            'related_id' => $appointment->id,
            'is_read' => false,
            'sent_via' => 1
        ]);
        
        return response()->json(['message'=>'Yêu cầu hoàn tiền đã được gửi, đang chờ admin phê duyệt']);
    }

    public function requestRefund(Request $request, $appointmentId)
    {
        $user = $request->user();
        $appointment = DB::table('appointment_schedules')
            ->where('id',$appointmentId)
            ->where('patient_id',$user->id)
            ->first();
        if (!$appointment) {
            return response()->json(['message'=>'Lịch hẹn không tồn tại'],404);
        }
        if ($appointment->payment_status !== 'PAID' || $appointment->refund_locked) {
            return response()->json(['message'=>'Lịch không đủ điều kiện hoàn tiền'],400);
        }
        $methodId = $request->input('refund_method_id');
        $method = DB::table('payment_methods')
            ->where('id',$methodId)
            ->where('user_id',$user->id)
            ->first();
        if (!$method) {
            return response()->json(['message'=>'Phương thức hoàn tiền không hợp lệ'],422);
        }
        DB::table('appointment_schedules')
            ->where('id',$appointment->id)
            ->update([
                'refund_status'=>'REQUESTED',
                'refund_method_id'=>$method->id,
                'refund_requested_at'=>now(),
                'refund_locked'=>true,
                'payment_status'=>'REFUND_PENDING'
            ]);
        return response()->json(['message'=>'Đã ghi nhận yêu cầu hoàn tiền. Hoàn tất trong 1 giờ']);
    }

    /**
     * Lấy danh sách yêu cầu hoàn tiền của user
     */
    public function getRefunds(Request $request)
    {
        $user = $request->user();
        $query = DB::table('appointment_schedules as a')
            ->join('users as d', 'a.doctor_id', '=', 'd.id')
            ->leftJoin('clinics as c', 'a.clinic_id', '=', 'c.id')
            ->leftJoin('payment_methods as pm', 'a.refund_method_id', '=', 'pm.id')
            ->where('a.patient_id', $user->id)
            ->whereNotNull('a.refund_status')
            ->select([
                'a.id',
                'a.appointment_date',
                'a.fee_amount',
                'a.payment_status',
                'a.refund_status',
                'a.refund_requested_at',
                'a.refund_completed_at',
                'a.refund_reason',
                'd.full_name as doctor_name',
                'c.clinic_name',
                'pm.method_type as refund_method_type',
                'pm.account_number as refund_account'
            ]);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('a.refund_status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('a.refund_requested_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('a.refund_requested_at', '<=', $request->date_to);
        }

        // Filter by transaction ID (appointment ID)
        if ($request->filled('transaction_id')) {
            $query->where('a.id', 'like', '%' . $request->transaction_id . '%');
        }

        $refunds = $query->orderByDesc('a.refund_requested_at')->get();

        // Format response
        $formatted = $refunds->map(function ($item) {
            $statusLabels = [
                'REQUESTED' => 'Chờ xử lý',
                'PROCESSING' => 'Đang xử lý',
                'COMPLETED' => 'Đã hoàn tiền',
                'REJECTED' => 'Từ chối'
            ];

            return [
                'id' => $item->id,
                'refund_code' => '#RF' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
                'transaction_code' => '#TXN' . str_pad($item->id, 8, '0', STR_PAD_LEFT),
                'doctor_name' => $item->doctor_name,
                'clinic_name' => $item->clinic_name,
                'amount' => $item->fee_amount,
                'status' => $item->refund_status,
                'status_label' => $statusLabels[$item->refund_status] ?? $item->refund_status,
                'requested_at' => $item->refund_requested_at,
                'completed_at' => $item->refund_completed_at,
                'reason' => $item->refund_reason,
                'refund_method' => $item->refund_method_type,
                'can_cancel' => in_array($item->refund_status, ['REQUESTED', 'PROCESSING'])
            ];
        });

        return response()->json(['refunds' => $formatted]);
    }

    /**
     * Hủy yêu cầu hoàn tiền
     */
    public function cancelRefund(Request $request, $id)
    {
        $user = $request->user();
        $appointment = DB::table('appointment_schedules')
            ->where('id', $id)
            ->where('patient_id', $user->id)
            ->whereIn('refund_status', ['REQUESTED', 'PROCESSING'])
            ->first();

        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy yêu cầu hoàn tiền hoặc không thể hủy'], 404);
        }

        DB::table('appointment_schedules')
            ->where('id', $id)
            ->update([
                'refund_status' => null,
                'refund_method_id' => null,
                'refund_requested_at' => null,
                'refund_locked' => false,
                'payment_status' => 'PAID',
                'updated_at' => now()
            ]);

        return response()->json(['message' => 'Đã hủy yêu cầu hoàn tiền']);
    }

    public function submitReview(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'appointment_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        
        // Build condition to find appointment by patient_id OR by email/phone match
        $appointment = DB::table('appointment_schedules')
            ->where('id', $request->appointment_id)
            ->where(function($query) use ($user) {
                $query->where('patient_id', $user->id);
                if ($user->email) {
                    $query->orWhere('patient_email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('patient_phone', $user->phone);
                }
            })
            ->first();
            
        if (!$appointment || $appointment->status !== 'completed') {
            return response()->json(['message'=>'Chỉ đánh giá lịch đã hoàn thành'],422);
        }
        
        // Check if user already reviewed THIS DOCTOR (not just this appointment)
        $existingDoctorReview = DB::table('review')
            ->where('doctor_id', $appointment->doctor_id)
            ->where('user_id', $user->id)
            ->first();
        if ($existingDoctorReview) {
            return response()->json(['message'=>'Bạn đã đánh giá bác sĩ này rồi. Mỗi bác sĩ chỉ được đánh giá 1 lần.'],422);
        }
        
        // Also check if this specific appointment was reviewed
        $existingAppointmentReview = DB::table('review')
            ->where('appointment_id', $appointment->id)
            ->first();
        if ($existingAppointmentReview) {
            return response()->json(['message'=>'Lịch hẹn này đã được đánh giá'],422);
        }
        
        // Insert review
        $reviewId = DB::table('review')->insertGetId([
            'user_id' => $user->id,
            'doctor_id' => $appointment->doctor_id,
            'appointment_id' => $appointment->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // If rating is 1 or 2 stars, log to admin system_logs for review
        if ($request->rating <= 2) {
            $doctorInfo = DB::table('doctor')
                ->join('user', 'doctor.user_id', '=', 'user.id')
                ->where('doctor.id', $appointment->doctor_id)
                ->select('user.full_name as doctor_name')
                ->first();
            
            DB::table('system_logs')->insert([
                'action' => 'low_rating_review',
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => json_encode([
                    'review_id' => $reviewId,
                    'doctor_id' => $appointment->doctor_id,
                    'doctor_name' => $doctorInfo->doctor_name ?? null,
                    'patient_name' => $user->full_name,
                    'patient_email' => $user->email,
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                    'appointment_id' => $appointment->id,
                    'message' => "Bệnh nhân '{$user->full_name}' đánh giá {$request->rating} sao cho BS. " . ($doctorInfo->doctor_name ?? 'N/A')
                ]),
                'created_at' => now()
            ]);
        }
        
        return response()->json(['message'=>'Đã gửi đánh giá']);
    }

    public function updateReview(Request $request, $id)
    {
        $user = $request->user();
        $review = DB::table('review')->where('id', $id)->where('user_id', $user->id)->first();
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);
        DB::table('review')->where('id', $id)->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Review updated']);
    }

    public function deleteUserReview(Request $request, $id)
    {
        $user = $request->user();
        $review = DB::table('review')->where('id', $id)->where('user_id', $user->id)->first();
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        DB::table('review')->where('id', $id)->delete();
        return response()->json(['message' => 'Review deleted']);
    }

    public function listReviews(Request $request)
    {
        $user = $request->user();
        $reviews = DB::table('review as rv')
            ->leftJoin('doctor','doctor.id','=','rv.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->select('rv.*','doc_user.full_name as doctor_name')
            ->where('rv.user_id',$user->id)
            ->orderByDesc('rv.created_at')
            ->get();
        // Get list of doctor_ids that user already reviewed
        $reviewedDoctorIds = DB::table('review')
            ->where('user_id', $user->id)
            ->pluck('doctor_id')
            ->toArray();
        
        // Get reviewable appointments - exclude doctors already reviewed
        $reviewable = DB::table('appointment_schedules as appt')
            ->leftJoin('doctor','doctor.id','=','appt.doctor_id')
            ->leftJoin('user as doc_user','doc_user.id','=','doctor.user_id')
            ->select('appt.id','appt.appointment_date','appt.doctor_id','doc_user.full_name as doctor_name','doc_user.avatar as doctor_avatar')
            ->where(function($query) use ($user) {
                $query->where('appt.patient_id', $user->id);
                if ($user->email) {
                    $query->orWhere('appt.patient_email', $user->email);
                }
                if ($user->phone) {
                    $query->orWhere('appt.patient_phone', $user->phone);
                }
            })
            ->where('appt.status','completed')
            ->whereNotIn('appt.doctor_id', $reviewedDoctorIds) // Exclude already reviewed doctors
            ->groupBy('appt.doctor_id') // Only show one appointment per doctor
            ->get();
        return response()->json([
            'reviews'=>$reviews,
            'reviewable'=>$reviewable,
        ]);
    }

    /**
     * Danh sách thông báo cho bác sĩ đang đăng nhập
     * Gồm: lịch hẹn, đánh giá mới, thông báo từ admin...
     */
    public function doctorNotifications(Request $request)
    {
        $user = $request->user();
        if ($user->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $rows = DB::table('notification')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        $now = now();
        $items = $rows->map(function ($n) use ($now) {
            // type: 1 = lịch hẹn, 2 = đánh giá, 3 = admin (quy ước mềm, có thể đổi sau)
            $type = (int)($n->type ?? 0);
            $created = $n->created_at ? \Carbon\Carbon::parse($n->created_at) : $now;
            $minutes = $created->diffInMinutes($now);
            if ($minutes < 60) {
                $timeHuman = (int)$minutes . ' phút trước';
            } elseif ($minutes < 24 * 60) {
                $hours = (int) floor($minutes / 60);
                $timeHuman = $hours . ' giờ trước';
            } else {
                $days = (int) floor($minutes / (60 * 24));
                $timeHuman = $days . ' ngày trước';
            }

            return [
                'id' => $n->id,
                'title' => $n->title ?? '',
                'message' => $n->message ?? '',
                'type' => $type,
                'related_id' => $n->related_id,
                'is_read' => (bool)$n->is_read,
                'created_at_human' => $timeHuman,
            ];
        });

        return response()->json($items);
    }

    public function markAllDoctorNotificationsRead(Request $request)
    {
        $user = $request->user();
        if ($user->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        DB::table('notification')
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->whereNull('is_read')
                  ->orWhere('is_read', 0);
            })
            ->update(['is_read' => 1, 'updated_at' => now()]);
        return response()->json(['message' => 'ok']);
    }

    public function deleteDoctorNotification(Request $request, int $id)
    {
        $user = $request->user();
        if ($user->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        DB::table('notification')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->delete();
        return response()->json(['message' => 'deleted']);
    }

    public function getDoctorConfirmedAppointments(Request $request)
    {
        $u = $request->user();
        if ($u->type !== 'DOCTOR') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        
        // Lấy doctor_id từ bảng doctor
        $doctor = DB::table('doctor')->where('user_id', $u->id)->first();
        if (!$doctor) {
            return response()->json([]);
        }
        
        // Lấy các lịch khám đã được đặt / xác nhận cho bác sĩ hiện tại
        // Bao gồm cả lịch từ khách không đăng nhập (patient_id = null nhưng có patient_name)
        $appointments = DB::table('appointment_schedules as appt')
            ->leftJoin('user as patient', 'patient.id', '=', 'appt.patient_id')
            ->leftJoin('clinic as c', 'c.id', '=', 'appt.clinic_id')
            ->leftJoin('treatment_service as ts', 'ts.id', '=', 'appt.service_id')
            ->leftJoin('specialization as sp', 'sp.id', '=', 'ts.specialization_id')
            ->where('appt.doctor_id', $doctor->id)
            ->whereIn('appt.status', ['booked', 'pending_confirmation', 'confirmed', 'completed', 'cancelled'])
            ->where(function($query) {
                // Lấy lịch có patient_id (user đã đăng nhập) HOẶC có patient_name (khách không đăng nhập)
                $query->whereNotNull('appt.patient_id')
                      ->orWhereNotNull('appt.patient_name');
            })
            ->select(
                'appt.id',
                'appt.appointment_date as date',
                'appt.time_slot',
                'appt.start_time',
                'appt.end_time',
                'appt.status',
                'appt.payment_status',
                'appt.fee_amount',
                'appt.notes',
                // Ưu tiên patient_name từ bảng appointment nếu có, nếu không lấy từ user
                DB::raw('COALESCE(appt.patient_name, patient.full_name) as patient_name'),
                DB::raw('COALESCE(appt.patient_phone, patient.phone) as patient_phone'),
                DB::raw('COALESCE(appt.patient_email, patient.email) as patient_email'),
                DB::raw('COALESCE(appt.clinic_name, c.name) as clinic_name'),
                DB::raw('COALESCE(c.address, "") as clinic_address'),
                'appt.room_number',
                'ts.name as service_name',
                'ts.duration_minutes as service_duration',
                'sp.name as specialization_name'
            )
            ->orderBy('appt.appointment_date')
            ->orderBy('appt.start_time')
            ->get();

        return response()->json($appointments);
    }

    public function getUsers(Request $request)
    {
        try {
            $users = User::all();
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error('Error fetching users', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function createUser(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:user,doctor,admin,USER,DOCTOR,ADMIN',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE,active,inactive',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        $user = User::create([
            'full_name' => $validatedData['full_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'phone' => $validatedData['phone'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'type' => strtoupper($validatedData['type'] ?? 'USER'),
            'status' => strtoupper($validatedData['status'] ?? 'ACTIVE'),
            'gender' => $validatedData['gender'] ?? null,
            'dob' => $validatedData['dob'] ?? null,
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:user,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:ACTIVE,INACTIVE,active,inactive',
            'type' => 'nullable|string|in:user,doctor,admin,USER,DOCTOR,ADMIN',
            'password' => 'nullable|string|min:6',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
        ]);

        // Handle password separately - hash if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']); // Don't update password if not provided
        }

        // Normalize status and type to uppercase
        if (isset($validatedData['status'])) {
            $validatedData['status'] = strtoupper($validatedData['status']);
        }
        if (isset($validatedData['type'])) {
            $validatedData['type'] = strtoupper($validatedData['type']);
        }

        $user->update($validatedData);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    // ============================================
    // DOCTOR CRUD METHODS
    // ============================================

    public function getDoctors(Request $request)
    {
        try {
            $doctors = Doctor::with(['user', 'specialization', 'clinic', 'services'])->get();

            // Transform data for frontend
            $result = $doctors->map(function($doctor) {
                return [
                    'id' => $doctor->id,
                    'user_id' => $doctor->user_id,
                    'full_name' => $doctor->user->full_name ?? null,
                    'email' => $doctor->user->email ?? null,
                    'phone' => $doctor->user->phone ?? null,
                    'gender' => $doctor->user->gender ?? null,
                    'dob' => $doctor->user->dob ?? null,
                    'address' => $doctor->user->address ?? null,
                    'avatar_url' => $doctor->user->avatar_url ?? null,
                    'experience' => $doctor->experience,
                    'description' => $doctor->description,
                    'degree' => $doctor->degree ?? null,
                    'rating_avg' => $doctor->rating_avg,
                    'doctor_status' => $doctor->doctor_status,
                    'specialization_id' => $doctor->specialization_id,
                    'specialization_name' => $doctor->specialization->name ?? null,
                    'clinic_id' => $doctor->clinic_id,
                    'clinic_name' => $doctor->clinic->name ?? null,
                    'services' => $doctor->services->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'price' => $s->price]),
                    'service_ids' => $doctor->services->pluck('id')->toArray(),
                    'created_at' => $doctor->created_at,
                    'updated_at' => $doctor->updated_at,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error fetching doctors', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching doctors', 'error' => $e->getMessage()], 500);
        }
    }

    public function createDoctor(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|string|min:6',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'gender' => 'nullable|string',
                'dob' => 'nullable|date',
                'avatar_url' => 'nullable|string',
                'experience' => 'nullable|integer|min:0',
                'description' => 'nullable|string|max:255',
                'degree' => 'nullable|string|max:255',
                'specialization_id' => 'nullable|integer|exists:specialization,id',
                'clinic_id' => 'nullable|integer|exists:clinic,id',
                'doctor_status' => 'nullable|string|in:ACTIVE,INACTIVE,NONE',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'integer|exists:treatment_services,id',
            ]);

            // Create user account first with type DOCTOR
            $user = User::create([
                'full_name' => $validatedData['full_name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'gender' => $validatedData['gender'] ?? null,
                'dob' => $validatedData['dob'] ?? null,
                'avatar_url' => $validatedData['avatar_url'] ?? null,
                'type' => 'DOCTOR',
                'status' => 'ACTIVE',
            ]);

            // Create doctor record linked to user
            $doctor = Doctor::create([
                'user_id' => $user->id,
                'experience' => $validatedData['experience'] ?? 0,
                'description' => $validatedData['description'] ?? null,
                'degree' => $validatedData['degree'] ?? null,
                'specialization_id' => $validatedData['specialization_id'] ?? null,
                'clinic_id' => $validatedData['clinic_id'] ?? null,
                'doctor_status' => $validatedData['doctor_status'] ?? 'ACTIVE',
                'rating_avg' => 0,
            ]);

            // Sync services if provided
            if (!empty($validatedData['service_ids'])) {
                $doctor->services()->sync($validatedData['service_ids']);
            }

            return response()->json([
                'message' => 'Doctor created successfully',
                'doctor' => $doctor->load(['user', 'specialization', 'clinic', 'services'])
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating doctor', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating doctor', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateDoctor(Request $request, $id)
    {
        try {
            $doctor = Doctor::with('user')->find($id);
            if (!$doctor) {
                return response()->json(['message' => 'Doctor not found'], 404);
            }

            $validatedData = $request->validate([
                'full_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:user,email,' . $doctor->user_id,
                'password' => 'nullable|string|min:6',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'gender' => 'nullable|string',
                'dob' => 'nullable|date',
                'avatar_url' => 'nullable|string',
                'experience' => 'nullable|integer|min:0',
                'description' => 'nullable|string|max:255',
                'degree' => 'nullable|string|max:255',
                'specialization_id' => 'nullable|integer|exists:specialization,id',
                'clinic_id' => 'nullable|integer|exists:clinic,id',
                'doctor_status' => 'nullable|string|in:ACTIVE,INACTIVE,NONE',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'integer|exists:treatment_services,id',
            ]);

            // Update user data
            $userData = [];
            if (isset($validatedData['full_name'])) $userData['full_name'] = $validatedData['full_name'];
            if (isset($validatedData['email'])) $userData['email'] = $validatedData['email'];
            if (!empty($validatedData['password'])) $userData['password'] = bcrypt($validatedData['password']);
            if (isset($validatedData['phone'])) $userData['phone'] = $validatedData['phone'];
            if (isset($validatedData['address'])) $userData['address'] = $validatedData['address'];
            if (isset($validatedData['gender'])) $userData['gender'] = $validatedData['gender'];
            if (isset($validatedData['dob'])) $userData['dob'] = $validatedData['dob'];
            if (isset($validatedData['avatar_url'])) $userData['avatar_url'] = $validatedData['avatar_url'];

            if (!empty($userData) && $doctor->user) {
                $doctor->user->update($userData);
            }

            // Update doctor data
            $doctorData = [];
            if (isset($validatedData['experience'])) $doctorData['experience'] = $validatedData['experience'];
            if (isset($validatedData['description'])) $doctorData['description'] = $validatedData['description'];
            if (isset($validatedData['degree'])) $doctorData['degree'] = $validatedData['degree'];
            if (isset($validatedData['specialization_id'])) $doctorData['specialization_id'] = $validatedData['specialization_id'];
            if (isset($validatedData['clinic_id'])) $doctorData['clinic_id'] = $validatedData['clinic_id'];
            if (isset($validatedData['doctor_status'])) $doctorData['doctor_status'] = $validatedData['doctor_status'];

            if (!empty($doctorData)) {
                $doctor->update($doctorData);
            }

            // Sync services if provided
            if (array_key_exists('service_ids', $validatedData)) {
                $doctor->services()->sync($validatedData['service_ids'] ?? []);
            }

            return response()->json([
                'message' => 'Doctor updated successfully',
                'doctor' => $doctor->fresh()->load(['user', 'specialization', 'clinic', 'services'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating doctor', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error updating doctor', 'error' => $e->getMessage()], 500);
        }
    }

    public function deleteDoctor(Request $request, $id)
    {
        try {
            $doctor = Doctor::with('user')->find($id);
            if (!$doctor) {
                return response()->json(['message' => 'Doctor not found'], 404);
            }

            // Delete doctor record (user will be kept but type changed)
            $doctor->delete();

            // Optionally change user type back to USER
            if ($doctor->user) {
                $doctor->user->update(['type' => 'USER']);
            }

            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting doctor', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error deleting doctor', 'error' => $e->getMessage()], 500);
        }
    }

    // Get specializations for dropdown
    public function getSpecializations(Request $request)
    {
        try {
            $specializations = \DB::table('specialization')->select('id', 'name', 'description')->get();
            return response()->json($specializations);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching specializations'], 500);
        }
    }

    // Get clinics for dropdown
    public function getClinics(Request $request)
    {
        try {
            $clinics = \DB::table('clinic')->select('id', 'name', 'address')->get();
            return response()->json($clinics);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching clinics'], 500);
        }
    }

    // ==================== CLINIC CRUD ====================

    // Get all clinics for admin management
    public function getAllClinics(Request $request)
    {
        try {
            $clinics = \DB::table('clinic')
                ->select('id', 'name', 'address', 'hotline', 'email', 'opening_hours', 'description', 'status', 'created_at', 'updated_at')
                ->orderBy('id', 'desc')
                ->get();
            return response()->json($clinics);
        } catch (\Exception $e) {
            Log::error('Error fetching clinics', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching clinics'], 500);
        }
    }

    // Get single clinic
    public function getClinic(Request $request, $id)
    {
        try {
            $clinic = \DB::table('clinic')->where('id', $id)->first();
            if (!$clinic) {
                return response()->json(['message' => 'Clinic not found'], 404);
            }
            return response()->json($clinic);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching clinic'], 500);
        }
    }

    // Create clinic
    public function createClinic(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'hotline' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'opening_hours' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $id = \DB::table('clinic')->insertGetId([
                'name' => $validatedData['name'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'hotline' => $validatedData['hotline'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'opening_hours' => $validatedData['opening_hours'] ?? null,
                'description' => $validatedData['description'] ?? null,
                'status' => $validatedData['status'] ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $clinic = \DB::table('clinic')->where('id', $id)->first();
            return response()->json([
                'message' => 'Clinic created successfully',
                'clinic' => $clinic
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating clinic', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating clinic', 'error' => $e->getMessage()], 500);
        }
    }

    // Update clinic
    public function updateClinic(Request $request, $id)
    {
        try {
            $clinic = \DB::table('clinic')->where('id', $id)->first();
            if (!$clinic) {
                return response()->json(['message' => 'Clinic not found'], 404);
            }

            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'address' => 'nullable|string|max:255',
                'hotline' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'opening_hours' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $updateData = [];
            if (isset($validatedData['name'])) $updateData['name'] = $validatedData['name'];
            if (isset($validatedData['address'])) $updateData['address'] = $validatedData['address'];
            if (isset($validatedData['hotline'])) $updateData['hotline'] = $validatedData['hotline'];
            if (isset($validatedData['email'])) $updateData['email'] = $validatedData['email'];
            if (isset($validatedData['opening_hours'])) $updateData['opening_hours'] = $validatedData['opening_hours'];
            if (isset($validatedData['description'])) $updateData['description'] = $validatedData['description'];
            if (isset($validatedData['status'])) $updateData['status'] = $validatedData['status'];
            $updateData['updated_at'] = now();

            \DB::table('clinic')->where('id', $id)->update($updateData);

            $clinic = \DB::table('clinic')->where('id', $id)->first();
            return response()->json([
                'message' => 'Clinic updated successfully',
                'clinic' => $clinic
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating clinic', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error updating clinic', 'error' => $e->getMessage()], 500);
        }
    }

    // Delete clinic
    public function deleteClinic(Request $request, $id)
    {
        try {
            $clinic = \DB::table('clinic')->where('id', $id)->first();
            if (!$clinic) {
                return response()->json(['message' => 'Clinic not found'], 404);
            }

            \DB::table('clinic')->where('id', $id)->delete();
            return response()->json(['message' => 'Clinic deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting clinic', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error deleting clinic', 'error' => $e->getMessage()], 500);
        }
    }

    // ==================== PAYMENT MANAGEMENT ====================

    // Get all payments for admin management
    public function getPayments(Request $request)
    {
        try {
            $filter = $request->query('filter', 'all'); // pending, refund, success, failed, all

            $query = \DB::table('appointment_schedules as a')
                ->leftJoin('user as u', 'a.patient_id', '=', 'u.id')
                ->leftJoin('doctor as d', 'a.doctor_id', '=', 'd.id')
                ->leftJoin('user as du', 'd.user_id', '=', 'du.id')
                ->leftJoin('payment_methods as pm', 'a.payment_method_id', '=', 'pm.id')
                ->select(
                    'a.id',
                    'a.patient_id',
                    'a.patient_name',
                    'a.patient_phone',
                    'u.email as patient_email',
                    'a.doctor_id',
                    'du.full_name as doctor_name',
                    'a.clinic_name',
                    'a.appointment_date',
                    'a.time_slot',
                    'a.start_time',
                    'a.end_time',
                    'a.status as appointment_status',
                    'a.notes',
                    'a.fee_amount',
                    'a.payment_status',
                    'pm.method_name as payment_method',
                    'pm.method as payment_method_type',
                    'a.paid_at',
                    'a.refund_status',
                    'a.refund_requested_at',
                    'a.refund_locked',
                    'a.created_at',
                    'a.updated_at'
                )
                ->whereIn('a.payment_status', ['PAID', 'REFUND_PENDING', 'REFUNDED'])
                ->orWhere(function($q) {
                    $q->where('a.payment_status', 'UNPAID')
                      ->whereIn('a.refund_status', ['REQUESTED', 'REJECTED']);
                });

            // Apply filter
            if ($filter === 'pending') {
                // Payments waiting for admin confirmation (PAID but not verified yet)
                $query = \DB::table('appointment_schedules as a')
                    ->leftJoin('user as u', 'a.patient_id', '=', 'u.id')
                    ->leftJoin('doctor as d', 'a.doctor_id', '=', 'd.id')
                    ->leftJoin('user as du', 'd.user_id', '=', 'du.id')
                    ->leftJoin('payment_methods as pm', 'a.payment_method_id', '=', 'pm.id')
                    ->select(
                        'a.id', 'a.patient_id', 'a.patient_name', 'a.patient_phone',
                        'u.email as patient_email', 'a.doctor_id', 'du.full_name as doctor_name',
                        'a.clinic_name', 'a.appointment_date', 'a.time_slot', 'a.start_time', 'a.end_time',
                        'a.status as appointment_status', 'a.notes', 'a.fee_amount', 'a.payment_status',
                        'pm.method_name as payment_method', 'pm.method as payment_method_type',
                        'a.paid_at', 'a.refund_status', 'a.refund_requested_at', 'a.refund_locked',
                        'a.created_at', 'a.updated_at'
                    )
                    ->where('a.payment_status', 'PAID')
                    ->where('a.refund_status', 'NONE');
            } elseif ($filter === 'refund') {
                // Refund requests
                $query = \DB::table('appointment_schedules as a')
                    ->leftJoin('user as u', 'a.patient_id', '=', 'u.id')
                    ->leftJoin('doctor as d', 'a.doctor_id', '=', 'd.id')
                    ->leftJoin('user as du', 'd.user_id', '=', 'du.id')
                    ->leftJoin('payment_methods as pm', 'a.payment_method_id', '=', 'pm.id')
                    ->select(
                        'a.id', 'a.patient_id', 'a.patient_name', 'a.patient_phone',
                        'u.email as patient_email', 'a.doctor_id', 'du.full_name as doctor_name',
                        'a.clinic_name', 'a.appointment_date', 'a.time_slot', 'a.start_time', 'a.end_time',
                        'a.status as appointment_status', 'a.notes', 'a.fee_amount', 'a.payment_status',
                        'pm.method_name as payment_method', 'pm.method as payment_method_type',
                        'a.paid_at', 'a.refund_status', 'a.refund_requested_at', 'a.refund_locked',
                        'a.created_at', 'a.updated_at'
                    )
                    ->where('a.refund_status', 'REQUESTED');
            } elseif ($filter === 'success') {
                // Successfully processed (REFUNDED or approved payments)
                $query = \DB::table('appointment_schedules as a')
                    ->leftJoin('user as u', 'a.patient_id', '=', 'u.id')
                    ->leftJoin('doctor as d', 'a.doctor_id', '=', 'd.id')
                    ->leftJoin('user as du', 'd.user_id', '=', 'du.id')
                    ->leftJoin('payment_methods as pm', 'a.payment_method_id', '=', 'pm.id')
                    ->select(
                        'a.id', 'a.patient_id', 'a.patient_name', 'a.patient_phone',
                        'u.email as patient_email', 'a.doctor_id', 'du.full_name as doctor_name',
                        'a.clinic_name', 'a.appointment_date', 'a.time_slot', 'a.start_time', 'a.end_time',
                        'a.status as appointment_status', 'a.notes', 'a.fee_amount', 'a.payment_status',
                        'pm.method_name as payment_method', 'pm.method as payment_method_type',
                        'a.paid_at', 'a.refund_status', 'a.refund_requested_at', 'a.refund_locked',
                        'a.created_at', 'a.updated_at'
                    )
                    ->where(function($q) {
                        $q->where('a.payment_status', 'REFUNDED')
                          ->orWhere('a.refund_status', 'APPROVED');
                    });
            } elseif ($filter === 'failed') {
                // Rejected payments/refunds
                $query = \DB::table('appointment_schedules as a')
                    ->leftJoin('user as u', 'a.patient_id', '=', 'u.id')
                    ->leftJoin('doctor as d', 'a.doctor_id', '=', 'd.id')
                    ->leftJoin('user as du', 'd.user_id', '=', 'du.id')
                    ->leftJoin('payment_methods as pm', 'a.payment_method_id', '=', 'pm.id')
                    ->select(
                        'a.id', 'a.patient_id', 'a.patient_name', 'a.patient_phone',
                        'u.email as patient_email', 'a.doctor_id', 'du.full_name as doctor_name',
                        'a.clinic_name', 'a.appointment_date', 'a.time_slot', 'a.start_time', 'a.end_time',
                        'a.status as appointment_status', 'a.notes', 'a.fee_amount', 'a.payment_status',
                        'pm.method_name as payment_method', 'pm.method as payment_method_type',
                        'a.paid_at', 'a.refund_status', 'a.refund_requested_at', 'a.refund_locked',
                        'a.created_at', 'a.updated_at'
                    )
                    ->where('a.refund_status', 'REJECTED');
            }

            $payments = $query->orderBy('a.updated_at', 'desc')->get();

            // Get counts for badges
            $counts = [
                'pending' => \DB::table('appointment_schedules')
                    ->where('payment_status', 'PAID')
                    ->where('refund_status', 'NONE')
                    ->count(),
                'refund' => \DB::table('appointment_schedules')
                    ->where('refund_status', 'REQUESTED')
                    ->count(),
                'success' => \DB::table('appointment_schedules')
                    ->where(function($q) {
                        $q->where('payment_status', 'REFUNDED')
                          ->orWhere('refund_status', 'APPROVED');
                    })
                    ->count(),
                'failed' => \DB::table('appointment_schedules')
                    ->where('refund_status', 'REJECTED')
                    ->count(),
            ];

            return response()->json([
                'payments' => $payments,
                'counts' => $counts
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching payments', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching payments', 'error' => $e->getMessage()], 500);
        }
    }

    // Approve payment (confirm PAID status)
    public function approvePayment(Request $request, $id)
    {
        try {
            $appointment = \DB::table('appointment_schedules')->where('id', $id)->first();
            if (!$appointment) {
                return response()->json(['message' => 'Appointment not found'], 404);
            }

            $type = $request->input('type', 'payment'); // payment or refund

            if ($type === 'refund') {
                // Approve refund request
                \DB::table('appointment_schedules')->where('id', $id)->update([
                    'refund_status' => 'APPROVED',
                    'payment_status' => 'REFUNDED',
                    'refund_locked' => true,
                    'updated_at' => now()
                ]);

                // Send notification to user
                $this->sendPaymentNotification($appointment->patient_id, 'refund_approved', [
                    'appointment_id' => $id,
                    'amount' => $appointment->fee_amount,
                    'message' => 'Yêu cầu hoàn tiền của bạn đã được duyệt. Tiền sẽ được hoàn lại trong 3-5 ngày làm việc.'
                ]);

                return response()->json(['message' => 'Refund approved successfully']);
            } else {
                // Confirm payment (already PAID, just mark as verified)
                \DB::table('appointment_schedules')->where('id', $id)->update([
                    'refund_status' => 'APPROVED', // Mark as verified/approved
                    'updated_at' => now()
                ]);

                // Send notification to user
                $this->sendPaymentNotification($appointment->patient_id, 'payment_confirmed', [
                    'appointment_id' => $id,
                    'amount' => $appointment->fee_amount,
                    'message' => 'Thanh toán của bạn đã được xác nhận thành công.'
                ]);

                return response()->json(['message' => 'Payment confirmed successfully']);
            }
        } catch (\Exception $e) {
            Log::error('Error approving payment', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error approving payment', 'error' => $e->getMessage()], 500);
        }
    }

    // Reject payment or refund
    public function rejectPayment(Request $request, $id)
    {
        try {
            $appointment = \DB::table('appointment_schedules')->where('id', $id)->first();
            if (!$appointment) {
                return response()->json(['message' => 'Appointment not found'], 404);
            }

            $reason = $request->input('reason', 'Không có lý do cụ thể');
            $type = $request->input('type', 'payment'); // payment or refund

            if ($type === 'refund') {
                // Reject refund request
                \DB::table('appointment_schedules')->where('id', $id)->update([
                    'refund_status' => 'REJECTED',
                    'refund_locked' => true,
                    'updated_at' => now()
                ]);

                // Send notification to user with reason
                $this->sendPaymentNotification($appointment->patient_id, 'refund_rejected', [
                    'appointment_id' => $id,
                    'amount' => $appointment->fee_amount,
                    'reason' => $reason,
                    'message' => 'Yêu cầu hoàn tiền của bạn đã bị từ chối. Lý do: ' . $reason
                ]);
            } else {
                // Reject payment (mark as failed)
                \DB::table('appointment_schedules')->where('id', $id)->update([
                    'payment_status' => 'UNPAID',
                    'refund_status' => 'REJECTED',
                    'updated_at' => now()
                ]);

                // Send notification to user with reason
                $this->sendPaymentNotification($appointment->patient_id, 'payment_rejected', [
                    'appointment_id' => $id,
                    'amount' => $appointment->fee_amount,
                    'reason' => $reason,
                    'message' => 'Thanh toán của bạn đã bị từ chối. Lý do: ' . $reason
                ]);
            }

            return response()->json(['message' => 'Request rejected successfully']);
        } catch (\Exception $e) {
            Log::error('Error rejecting payment', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error rejecting payment', 'error' => $e->getMessage()], 500);
        }
    }

    // Helper to send payment notifications
    private function sendPaymentNotification($userId, $type, $data)
    {
        try {
            $titles = [
                'payment_confirmed' => 'Thanh toán đã được xác nhận',
                'payment_rejected' => 'Thanh toán bị từ chối',
                'refund_approved' => 'Yêu cầu hoàn tiền được duyệt',
                'refund_rejected' => 'Yêu cầu hoàn tiền bị từ chối'
            ];

            \DB::table('notification')->insert([
                'user_id' => $userId,
                'title' => $titles[$type] ?? 'Thông báo thanh toán',
                'message' => $data['message'] ?? '',
                'type' => 'PAYMENT',
                'data' => json_encode($data),
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending payment notification', ['error' => $e->getMessage()]);
        }
    }

    // Helper to send appointment notifications to doctor
    private function sendDoctorAppointmentNotification($doctorId, $type, $data)
    {
        try {
            // Lấy user_id của bác sĩ từ bảng doctor
            $doctor = \DB::table('doctor')->where('id', $doctorId)->first();
            if (!$doctor || !$doctor->user_id) {
                Log::warning('Doctor not found for notification', ['doctor_id' => $doctorId]);
                return;
            }

            $titles = [
                'new_appointment' => '📅 Lịch hẹn mới',
                'appointment_confirmed' => '✅ Bệnh nhân đã xác nhận lịch hẹn',
                'appointment_cancelled' => '❌ Bệnh nhân đã hủy lịch hẹn',
                'appointment_completed' => '✔️ Lịch hẹn đã hoàn thành'
            ];

            $messages = [
                'new_appointment' => 'Bệnh nhân ' . ($data['patient_name'] ?? 'N/A') . ' đã đặt lịch khám vào ' . ($data['date'] ?? '') . ' lúc ' . ($data['time'] ?? ''),
                'appointment_confirmed' => 'Bệnh nhân ' . ($data['patient_name'] ?? 'N/A') . ' đã xác nhận lịch hẹn ngày ' . ($data['date'] ?? '') . ' lúc ' . ($data['time'] ?? ''),
                'appointment_cancelled' => 'Bệnh nhân ' . ($data['patient_name'] ?? 'N/A') . ' đã hủy lịch hẹn ngày ' . ($data['date'] ?? '') . ' lúc ' . ($data['time'] ?? ''),
                'appointment_completed' => 'Lịch hẹn với bệnh nhân ' . ($data['patient_name'] ?? 'N/A') . ' ngày ' . ($data['date'] ?? '') . ' đã hoàn thành'
            ];

            \DB::table('notification')->insert([
                'user_id' => $doctor->user_id,
                'title' => $titles[$type] ?? 'Thông báo lịch hẹn',
                'message' => $messages[$type] ?? ($data['message'] ?? ''),
                'type' => 1, // 1 = lịch hẹn
                'related_id' => $data['appointment_id'] ?? null,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending doctor notification', ['error' => $e->getMessage()]);
        }
    }

    // ==================== SPECIALIZATION MANAGEMENT ====================

    /**
     * Get all specializations
     */
    public function getAllSpecializations(Request $request)
    {
        try {
            $specializations = \DB::table('specialization')
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $specializations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching specializations', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách chuyên specialty'
            ], 500);
        }
    }

    /**
     * Get a single specialization
     */
    public function getSpecialization($id)
    {
        try {
            $specialization = \DB::table('specialization')->where('id', $id)->first();

            if (!$specialization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy chuyên specialty'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $specialization
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching specialization', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin chuyên specialty'
            ], 500);
        }
    }

    /**
     * Create a new specialization
     */
    public function createSpecialization(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'nullable|string|max:500'
            ], [
                'name.required' => 'Tên chuyên specialty là bắt buộc',
                'name.max' => 'Tên chuyên specialty không được quá 255 ký tự'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Check duplicate name
            $existingSpec = \DB::table('specialization')
                ->where('name', $request->name)
                ->first();

            if ($existingSpec) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên chuyên khoa đã tồn tại'
                ], 400);
            }

            $id = \DB::table('specialization')->insertGetId([
                'name' => $request->name,
                'description' => $request->description,
                'image_url' => $request->image_url,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $specialization = \DB::table('specialization')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Tạo chuyên khoa thành công',
                'data' => $specialization
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating specialization', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo chuyên specialty'
            ], 500);
        }
    }

    /**
     * Update an existing specialization
     */
    public function updateSpecialization(Request $request, $id)
    {
        try {
            $specialization = \DB::table('specialization')->where('id', $id)->first();

            if (!$specialization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy chuyên khoa'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'nullable|string|max:500'
            ], [
                'name.required' => 'Tên chuyên khoa là bắt buộc',
                'name.max' => 'Tên chuyên khoa không được quá 255 ký tự'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Check duplicate name (exclude current)
            $existingSpec = \DB::table('specialization')
                ->where('name', $request->name)
                ->where('id', '!=', $id)
                ->first();

            if ($existingSpec) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên chuyên khoa đã tồn tại'
                ], 400);
            }

            \DB::table('specialization')->where('id', $id)->update([
                'name' => $request->name,
                'description' => $request->description,
                'image_url' => $request->image_url,
                'updated_at' => now()
            ]);

            $updatedSpec = \DB::table('specialization')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật chuyên khoa thành công',
                'data' => $updatedSpec
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating specialization', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật chuyên specialty'
            ], 500);
        }
    }

    /**
     * Delete a specialization
     */
    public function deleteSpecialization($id)
    {
        try {
            $specialization = \DB::table('specialization')->where('id', $id)->first();

            if (!$specialization) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy chuyên specialty'
                ], 404);
            }

            // Check if specialization is being used by doctors
            $doctorCount = \DB::table('doctor')
                ->where('specialization_id', $id)
                ->count();

            if ($doctorCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể xóa chuyên specialty này vì đang có {$doctorCount} bác sĩ sử dụng"
                ], 400);
            }

            \DB::table('specialization')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa chuyên specialty thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting specialization', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa chuyên specialty'
            ], 500);
        }
    }

    // ==================== REVIEW MANAGEMENT ====================

    /**
     * Get all reviews with filters
     */
    public function getReviews(Request $request)
    {
        try {
            $query = \DB::table('review')
                ->join('user', 'review.user_id', '=', 'user.id')
                ->join('doctor', 'review.doctor_id', '=', 'doctor.id')
                ->join('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->leftJoin('appointment_schedules', 'review.appointment_id', '=', 'appointment_schedules.id')
                ->select(
                    'review.id',
                    'review.rating',
                    'review.comment',
                    'review.created_at',
                    'review.appointment_id',
                    'review.user_id',
                    'review.doctor_id',
                    'user.full_name as patient_name',
                    'doctor_user.full_name as doctor_name',
                    'doctor.id as doctor_id'
                );

            // Filter by rating
            if ($request->has('rating') && $request->rating !== 'all') {
                $query->where('review.rating', $request->rating);
            }

            // Filter by doctor_id
            if ($request->has('doctor_id') && $request->doctor_id !== 'all') {
                $query->where('review.doctor_id', $request->doctor_id);
            }

            // Search by patient name or comment
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('user.full_name', 'like', "%{$search}%")
                      ->orWhere('review.comment', 'like', "%{$search}%");
                });
            }

            $reviews = $query->orderBy('review.created_at', 'desc')->get();

            // Calculate average rating
            $avgRating = null;
            if (!$request->has('rating') || $request->rating === 'all') {
                if (!$request->has('doctor_id') || $request->doctor_id === 'all') {
                    // Overall average
                    $avgRating = \DB::table('review')->avg('rating');
                } else {
                    // Average for specific doctor
                    $avgRating = \DB::table('review')
                        ->where('doctor_id', $request->doctor_id)
                        ->avg('rating');
                }
            }

            return response()->json([
                'success' => true,
                'data' => $reviews,
                'average_rating' => $avgRating ? round($avgRating, 1) : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching reviews', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách đánh giá'
            ], 500);
        }
    }

    /**
     * Get a single review
     */
    public function getReview($id)
    {
        try {
            $review = \DB::table('review')
                ->join('user', 'review.user_id', '=', 'user.id')
                ->join('doctor', 'review.doctor_id', '=', 'doctor.id')
                ->join('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->select(
                    'review.*',
                    'user.full_name as patient_name',
                    'doctor_user.full_name as doctor_name'
                )
                ->where('review.id', $id)
                ->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đánh giá'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $review
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching review', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin đánh giá'
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function deleteReview($id)
    {
        try {
            $review = \DB::table('review')->where('id', $id)->first();

            if (!$review) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đánh giá'
                ], 404);
            }

            \DB::table('review')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa đánh giá thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting review', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa đánh giá'
            ], 500);
        }
    }

    /**
     * Search doctors for autocomplete
     */
    public function searchDoctors(Request $request)
    {
        try {
            $search = $request->get('q', '');

            $doctors = \DB::table('doctor')
                ->join('user', 'doctor.user_id', '=', 'user.id')
                ->select('doctor.id', 'user.full_name')
                ->when($search, function($query) use ($search) {
                    return $query->where('user.full_name', 'like', "%{$search}%");
                })
                ->orderBy('user.full_name')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $doctors
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching doctors', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tìm kiếm bác sĩ'
            ], 500);
        }
    }

    // ==================== DASHBOARD & STATISTICS ====================

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(Request $request)
    {
        try {
            $now = Carbon::now();
            $currentMonth = $now->month;
            $currentYear = $now->year;
            $lastMonth = $now->copy()->subMonth();

            // Total patients (users with type USER)
            $totalPatients = \DB::table('user')->where('type', 'USER')->count();
            $patientsLastMonth = \DB::table('user')
                ->where('type', 'USER')
                ->whereYear('created_at', $lastMonth->year)
                ->whereMonth('created_at', $lastMonth->month)
                ->count();
            $patientsThisMonth = \DB::table('user')
                ->where('type', 'USER')
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $currentMonth)
                ->count();

            // Active doctors
            $activeDoctors = \DB::table('doctor')
                ->where('doctor_status', 'ACTIVE')
                ->count();
            $doctorsLastMonth = \DB::table('doctor')
                ->whereYear('created_at', $lastMonth->year)
                ->whereMonth('created_at', $lastMonth->month)
                ->count();

            // Appointments this month
            $appointmentsThisMonth = \DB::table('appointment_schedules')
                ->whereYear('appointment_date', $currentYear)
                ->whereMonth('appointment_date', $currentMonth)
                ->count();
            $appointmentsLastMonth = \DB::table('appointment_schedules')
                ->whereYear('appointment_date', $lastMonth->year)
                ->whereMonth('appointment_date', $lastMonth->month)
                ->count();

            // Revenue this month (from PAID appointments)
            $revenueThisMonth = \DB::table('appointment_schedules')
                ->where('payment_status', 'PAID')
                ->whereYear('paid_at', $currentYear)
                ->whereMonth('paid_at', $currentMonth)
                ->sum('fee_amount');
            $revenueLastMonth = \DB::table('appointment_schedules')
                ->where('payment_status', 'PAID')
                ->whereYear('paid_at', $lastMonth->year)
                ->whereMonth('paid_at', $lastMonth->month)
                ->sum('fee_amount');

            // Recent appointments
            $recentAppointments = \DB::table('appointment_schedules')
                ->join('doctor', 'appointment_schedules.doctor_id', '=', 'doctor.id')
                ->join('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->select(
                    'appointment_schedules.id',
                    'appointment_schedules.patient_name',
                    'doctor_user.full_name as doctor_name',
                    'appointment_schedules.appointment_date',
                    'appointment_schedules.start_time',
                    'appointment_schedules.status'
                )
                ->whereNotNull('appointment_schedules.patient_id')
                ->orderBy('appointment_schedules.created_at', 'desc')
                ->limit(5)
                ->get();

            // Recent activities (notifications or system logs)
            $recentActivities = \DB::table('notification')
                ->select('id', 'title', 'message', 'type', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Calculate percentage changes
            $patientChange = $patientsLastMonth > 0 ? round((($patientsThisMonth - $patientsLastMonth) / $patientsLastMonth) * 100) : ($patientsThisMonth > 0 ? 100 : 0);
            $appointmentChange = $appointmentsLastMonth > 0 ? round((($appointmentsThisMonth - $appointmentsLastMonth) / $appointmentsLastMonth) * 100) : ($appointmentsThisMonth > 0 ? 100 : 0);
            $revenueChange = $revenueLastMonth > 0 ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100) : ($revenueThisMonth > 0 ? 100 : 0);

            return response()->json([
                'success' => true,
                'data' => [
                    'total_patients' => $totalPatients,
                    'patients_this_month' => $patientsThisMonth,
                    'patient_change' => $patientChange,
                    'active_doctors' => $activeDoctors,
                    'doctor_change' => $doctorsLastMonth > 0 ? round((($activeDoctors - $doctorsLastMonth) / $doctorsLastMonth) * 100) : 0,
                    'appointments_this_month' => $appointmentsThisMonth,
                    'appointment_change' => $appointmentChange,
                    'revenue_this_month' => $revenueThisMonth,
                    'revenue_change' => $revenueChange,
                    'recent_appointments' => $recentAppointments,
                    'recent_activities' => $recentActivities
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching dashboard stats', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thống kê'
            ], 500);
        }
    }

    /**
     * Get report statistics
     */
    public function getReportStats(Request $request)
    {
        try {
            $period = $request->get('period', 'month'); // month, quarter, year
            $reportType = $request->get('type', 'all'); // all, appointments, revenue, users, reviews

            $now = Carbon::now();
            $currentYear = $now->year;
            $currentMonth = $now->month;

            // Determine date range
            if ($period === 'month') {
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $prevStartDate = $now->copy()->subMonth()->startOfMonth();
                $prevEndDate = $now->copy()->subMonth()->endOfMonth();
            } elseif ($period === 'quarter') {
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                $prevStartDate = $now->copy()->subQuarter()->startOfQuarter();
                $prevEndDate = $now->copy()->subQuarter()->endOfQuarter();
            } else { // year
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $prevStartDate = $now->copy()->subYear()->startOfYear();
                $prevEndDate = $now->copy()->subYear()->endOfYear();
            }

            // Total appointments in period
            $totalAppointments = \DB::table('appointment_schedules')
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->count();
            $prevAppointments = \DB::table('appointment_schedules')
                ->whereBetween('appointment_date', [$prevStartDate, $prevEndDate])
                ->count();

            // Total revenue in period
            $totalRevenue = \DB::table('appointment_schedules')
                ->where('payment_status', 'PAID')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->sum('fee_amount');
            $prevRevenue = \DB::table('appointment_schedules')
                ->where('payment_status', 'PAID')
                ->whereBetween('paid_at', [$prevStartDate, $prevEndDate])
                ->sum('fee_amount');

            // New users in period
            $newUsers = \DB::table('user')
                ->where('type', 'USER')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
            $prevUsers = \DB::table('user')
                ->where('type', 'USER')
                ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
                ->count();

            // Average rating
            $avgRating = \DB::table('review')->avg('rating');
            $totalReviews = \DB::table('review')->count();

            // Appointments by status
            $appointmentsByStatus = \DB::table('appointment_schedules')
                ->select('status', \DB::raw('count(*) as count'))
                ->whereBetween('appointment_date', [$startDate, $endDate])
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray();

            // Weekly chart data (last 4 weeks)
            $weeklyData = [];
            for ($i = 3; $i >= 0; $i--) {
                $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
                $weekEnd = $now->copy()->subWeeks($i)->endOfWeek();

                $weeklyData[] = [
                    'week' => 'Tuần ' . (4 - $i),
                    'appointments' => \DB::table('appointment_schedules')
                        ->whereBetween('appointment_date', [$weekStart, $weekEnd])
                        ->count(),
                    'revenue' => \DB::table('appointment_schedules')
                        ->where('payment_status', 'PAID')
                        ->whereBetween('paid_at', [$weekStart, $weekEnd])
                        ->sum('fee_amount'),
                    'users' => \DB::table('user')
                        ->where('type', 'USER')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->count(),
                    'reviews' => \DB::table('review')
                        ->whereBetween('created_at', [$weekStart, $weekEnd])
                        ->count()
                ];
            }

            // Monthly chart data (last 6 months)
            $monthlyData = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthDate = $now->copy()->subMonths($i);
                $monthStart = $monthDate->copy()->startOfMonth();
                $monthEnd = $monthDate->copy()->endOfMonth();

                $monthlyData[] = [
                    'month' => 'T' . $monthDate->month,
                    'appointments' => \DB::table('appointment_schedules')
                        ->whereBetween('appointment_date', [$monthStart, $monthEnd])
                        ->count(),
                    'revenue' => \DB::table('appointment_schedules')
                        ->where('payment_status', 'PAID')
                        ->whereBetween('paid_at', [$monthStart, $monthEnd])
                        ->sum('fee_amount'),
                    'users' => \DB::table('user')
                        ->where('type', 'USER')
                        ->whereBetween('created_at', [$monthStart, $monthEnd])
                        ->count(),
                    'reviews' => \DB::table('review')
                        ->whereBetween('created_at', [$monthStart, $monthEnd])
                        ->count()
                ];
            }

            // Top doctors by appointments
            $topDoctors = \DB::table('appointment_schedules')
                ->join('doctor', 'appointment_schedules.doctor_id', '=', 'doctor.id')
                ->join('user', 'doctor.user_id', '=', 'user.id')
                ->select(
                    'doctor.id',
                    'user.full_name',
                    \DB::raw('count(*) as appointment_count'),
                    \DB::raw('sum(appointment_schedules.fee_amount) as total_revenue')
                )
                ->whereBetween('appointment_schedules.appointment_date', [$startDate, $endDate])
                ->groupBy('doctor.id', 'user.full_name')
                ->orderByDesc('appointment_count')
                ->limit(5)
                ->get();

            // Calculate changes
            $appointmentChange = $prevAppointments > 0 ? round((($totalAppointments - $prevAppointments) / $prevAppointments) * 100) : ($totalAppointments > 0 ? 100 : 0);
            $revenueChange = $prevRevenue > 0 ? round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100) : ($totalRevenue > 0 ? 100 : 0);
            $userChange = $prevUsers > 0 ? round((($newUsers - $prevUsers) / $prevUsers) * 100) : ($newUsers > 0 ? 100 : 0);

            return response()->json([
                'success' => true,
                'data' => [
                    'period' => $period,
                    'period_label' => $period === 'month' ? 'Tháng ' . $currentMonth . '/' . $currentYear : ($period === 'quarter' ? 'Quý ' . ceil($currentMonth / 3) . '/' . $currentYear : 'Năm ' . $currentYear),
                    'total_appointments' => $totalAppointments,
                    'appointment_change' => $appointmentChange,
                    'total_revenue' => $totalRevenue,
                    'revenue_change' => $revenueChange,
                    'new_users' => $newUsers,
                    'user_change' => $userChange,
                    'avg_rating' => $avgRating ? round($avgRating, 1) : 0,
                    'total_reviews' => $totalReviews,
                    'appointments_by_status' => $appointmentsByStatus,
                    'weekly_data' => $weeklyData,
                    'monthly_data' => $monthlyData,
                    'top_doctors' => $topDoctors
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching report stats', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy báo cáo thống kê'
            ], 500);
        }
    }

    // ==================== APPOINTMENT MANAGEMENT ====================

    /**
     * Get all appointments with filters
     */
    public function getAppointments(Request $request)
    {
        try {
            $query = \DB::table('appointment_schedules')
                ->join('doctor', 'appointment_schedules.doctor_id', '=', 'doctor.id')
                ->join('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->leftJoin('user as patient', 'appointment_schedules.patient_id', '=', 'patient.id')
                ->leftJoin('clinic', 'appointment_schedules.clinic_id', '=', 'clinic.id')
                ->select(
                    'appointment_schedules.*',
                    'doctor_user.full_name as doctor_name',
                    'patient.full_name as patient_full_name',
                    'clinic.name as clinic_name'
                );

            // Filter by status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('appointment_schedules.status', $request->status);
            }

            // Search
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('appointment_schedules.patient_name', 'like', "%{$search}%")
                      ->orWhere('patient.full_name', 'like', "%{$search}%")
                      ->orWhere('doctor_user.full_name', 'like', "%{$search}%");
                });
            }

            $appointments = $query->orderBy('appointment_schedules.appointment_date', 'desc')
                ->orderBy('appointment_schedules.start_time', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching appointments', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách lịch hẹn'
            ], 500);
        }
    }

    /**
     * Get a single appointment
     */
    public function getAppointment($id)
    {
        try {
            $appointment = \DB::table('appointment_schedules')
                ->join('doctor', 'appointment_schedules.doctor_id', '=', 'doctor.id')
                ->join('user as doctor_user', 'doctor.user_id', '=', 'doctor_user.id')
                ->leftJoin('user as patient', 'appointment_schedules.patient_id', '=', 'patient.id')
                ->leftJoin('clinic', 'appointment_schedules.clinic_id', '=', 'clinic.id')
                ->select(
                    'appointment_schedules.*',
                    'doctor_user.full_name as doctor_name',
                    'patient.full_name as patient_full_name',
                    'clinic.name as clinic_name'
                )
                ->where('appointment_schedules.id', $id)
                ->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch hẹn'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $appointment
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching appointment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin lịch hẹn'
            ], 500);
        }
    }

    /**
     * Check if a time slot is available for a doctor
     */
    public function checkSlotAvailability(Request $request)
    {
        try {
            $doctorId = $request->query('doctor_id');
            $appointmentDate = $request->query('appointment_date');
            $timeSlot = $request->query('time_slot');

            if (!$doctorId || !$appointmentDate || !$timeSlot) {
                return response()->json([
                    'available' => true,
                    'message' => 'Thiếu thông tin'
                ]);
            }

            // Kiểm tra xem đã có lịch hẹn nào với doctor, ngày và time_slot này chưa
            // Chỉ check các status đang hoạt động (không check cancelled, completed)
            $existing = \DB::table('appointment_schedules')
                ->where('doctor_id', $doctorId)
                ->where('appointment_date', $appointmentDate)
                ->where('time_slot', $timeSlot)
                ->whereNotIn('status', ['cancelled', 'completed', 'available']) // Loại trừ lịch đã hủy/hoàn thành/trống
                ->where(function($query) {
                    // Đã có người đặt (có patient_id hoặc patient_name)
                    $query->whereNotNull('patient_id')
                          ->orWhereNotNull('patient_name');
                })
                ->first();

            if ($existing) {
                // Lấy thông tin bác sĩ
                $doctor = \DB::table('doctor')
                    ->join('user', 'user.id', '=', 'doctor.user_id')
                    ->where('doctor.id', $doctorId)
                    ->select('user.full_name', 'doctor.degree')
                    ->first();

                $doctorName = $doctor ? ($doctor->degree ? $doctor->degree . ' ' . $doctor->full_name : $doctor->full_name) : 'Bác sĩ';

                return response()->json([
                    'available' => false,
                    'message' => "Bác sĩ {$doctorName} đã có lịch đặt trước vào khung giờ này. Vui lòng chọn thời gian khác.",
                    'doctor_name' => $doctorName
                ]);
            }

            return response()->json([
                'available' => true,
                'message' => 'Khung giờ trống'
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking slot availability', ['error' => $e->getMessage()]);
            return response()->json([
                'available' => true, // Nếu lỗi, cho phép đặt, server sẽ validate lại
                'message' => 'Không thể kiểm tra'
            ]);
        }
    }

    /**
     * Get all booked slots for a doctor on a specific date
     */
    public function getBookedSlots(Request $request)
    {
        try {
            $doctorId = $request->query('doctor_id');
            $appointmentDate = $request->query('appointment_date');

            if (!$doctorId || !$appointmentDate) {
                return response()->json(['booked_slots' => []]);
            }

            $bookedSlots = \DB::table('appointment_schedules')
                ->where('doctor_id', $doctorId)
                ->where('appointment_date', $appointmentDate)
                ->whereNotIn('status', ['cancelled', 'completed', 'available'])
                ->where(function($query) {
                    $query->whereNotNull('patient_id')
                          ->orWhereNotNull('patient_name');
                })
                ->pluck('time_slot')
                ->toArray();

            return response()->json(['booked_slots' => $bookedSlots]);
        } catch (\Exception $e) {
            Log::error('Error getting booked slots', ['error' => $e->getMessage()]);
            return response()->json(['booked_slots' => []]);
        }
    }

    /**
     * Create a new appointment
     */
    public function createAppointment(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctor,id',
                'appointment_date' => 'required|date',
                'time_slot' => 'required|string'
            ], [
                'doctor_id.required' => 'Vui lòng chọn bác sĩ',
                'appointment_date.required' => 'Vui lòng chọn ngày hẹn',
                'time_slot.required' => 'Vui lòng chọn khung giờ'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Parse time slot
            $times = explode('-', $request->time_slot);
            $startTime = trim($times[0]);
            $endTime = isset($times[1]) ? trim($times[1]) : $startTime;

            // Kiểm tra xem có bản ghi cũ đã bị hủy/hoàn thành không
            $cancelledSlot = \DB::table('appointment_schedules')
                ->where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('time_slot', $request->time_slot)
                ->whereIn('status', ['cancelled', 'completed'])
                ->first();

            // Check for active duplicate - chỉ check các lịch chưa bị hủy/hoàn thành và đã có bệnh nhân
            $existing = \DB::table('appointment_schedules')
                ->where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('time_slot', $request->time_slot)
                ->whereNotIn('status', ['cancelled', 'completed']) // Loại trừ lịch đã hủy/hoàn thành
                ->where(function($query) use ($request) {
                    // Chỉ check nếu đã có bệnh nhân đặt (không phải slot trống available)
                    $query->whereNotNull('patient_id')
                          ->orWhere('status', '!=', 'available');
                })
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khung giờ này đã có người đặt trước'
                ], 400);
            }

            // Get patient name if patient_id provided
            $patientName = $request->patient_name;
            if ($request->patient_id) {
                $patient = \DB::table('user')->where('id', $request->patient_id)->first();
                if ($patient) {
                    $patientName = $patient->full_name;
                }
            }

            // Get clinic info - từ request hoặc từ bác sĩ
            $clinicId = $request->clinic_id;
            $clinicName = null;
            
            // Nếu không có clinic_id từ request, lấy từ doctor
            if (!$clinicId) {
                $doctor = \DB::table('doctor')->where('id', $request->doctor_id)->first();
                if ($doctor && $doctor->clinic_id) {
                    $clinicId = $doctor->clinic_id;
                }
            }
            
            if ($clinicId) {
                $clinic = \DB::table('clinic')->where('id', $clinicId)->first();
                if ($clinic) {
                    $clinicName = $clinic->name;
                }
            }

            // Nếu có bản ghi cũ đã hủy/hoàn thành, cập nhật lại thay vì tạo mới
            if ($cancelledSlot) {
                \DB::table('appointment_schedules')->where('id', $cancelledSlot->id)->update([
                    'patient_id' => $request->patient_id,
                    'patient_name' => $patientName,
                    'patient_phone' => $request->patient_phone,
                    'clinic_id' => $clinicId,
                    'clinic_name' => $clinicName,
                    'room_number' => $request->room_number,
                    'status' => $request->status ?? 'pending_confirmation',
                    'notes' => $request->notes,
                    'fee_amount' => $request->fee_amount ?? 0,
                    'updated_at' => now()
                ]);

                // Gửi thông báo cho bác sĩ
                $this->sendDoctorAppointmentNotification($request->doctor_id, 'new_appointment', [
                    'appointment_id' => $cancelledSlot->id,
                    'patient_name' => $patientName,
                    'date' => $request->appointment_date,
                    'time' => $request->time_slot
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đặt lịch hẹn thành công',
                    'data' => ['id' => $cancelledSlot->id]
                ], 201);
            }

            // Tạo mới nếu không có bản ghi cũ
            $id = \DB::table('appointment_schedules')->insertGetId([
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'time_slot' => $request->time_slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'patient_id' => $request->patient_id,
                'patient_name' => $patientName,
                'patient_phone' => $request->patient_phone,
                'clinic_id' => $clinicId,
                'clinic_name' => $clinicName,
                'room_number' => $request->room_number,
                'status' => $request->status ?? 'pending_confirmation',
                'notes' => $request->notes,
                'fee_amount' => $request->fee_amount ?? 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Gửi thông báo cho bác sĩ
            $this->sendDoctorAppointmentNotification($request->doctor_id, 'new_appointment', [
                'appointment_id' => $id,
                'patient_name' => $patientName,
                'date' => $request->appointment_date,
                'time' => $request->time_slot
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo lịch hẹn thành công',
                'data' => ['id' => $id]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating appointment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo lịch hẹn: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an appointment
     */
    public function updateAppointment(Request $request, $id)
    {
        try {
            $appointment = \DB::table('appointment_schedules')->where('id', $id)->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch hẹn'
                ], 404);
            }

            // Nếu chỉ cập nhật status (từ user confirm/cancel)
            if ($request->has('status') && !$request->has('doctor_id')) {
                $newStatus = $request->status;
                $allowedStatuses = ['pending_confirmation', 'confirmed', 'cancelled', 'completed'];
                
                if (!in_array($newStatus, $allowedStatuses)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Trạng thái không hợp lệ'
                    ], 400);
                }

                // Kiểm tra logic chuyển trạng thái
                $currentStatus = $appointment->status;
                
                // Chỉ cho phép hủy khi đang ở trạng thái pending_confirmation
                if ($newStatus === 'cancelled' && $currentStatus === 'confirmed') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không thể hủy lịch hẹn đã xác nhận. Vui lòng liên hệ email: uytinso1vn@gmail.com'
                    ], 400);
                }

                $updateData = [
                    'status' => $newStatus,
                    'updated_at' => now()
                ];

                // Khi xác nhận lịch hẹn, tính giá tiền
                if ($newStatus === 'confirmed' && $currentStatus === 'pending_confirmation') {
                    // Lấy thông tin bác sĩ và chuyên khoa
                    $doctor = \DB::table('doctor')->where('id', $appointment->doctor_id)->first();
                    if ($doctor && $doctor->specialization_id) {
                        $specialization = \DB::table('specialization')->where('id', $doctor->specialization_id)->first();
                        if ($specialization && $specialization->base_price > 0) {
                            $basePrice = (float) $specialization->base_price;
                            // Phí VAR ngẫu nhiên từ 10% đến 20%
                            $varPercent = mt_rand(10, 20) / 100;
                            $varFee = $basePrice * $varPercent;
                            $totalFee = $basePrice + $varFee;
                            // Làm tròn số chẵn hàng trăm
                            $totalFee = ceil($totalFee / 100) * 100;
                            $updateData['fee_amount'] = $totalFee;
                        }
                    }
                }

                \DB::table('appointment_schedules')->where('id', $id)->update($updateData);

                // Gửi thông báo cho bác sĩ khi bệnh nhân xác nhận hoặc hủy
                if ($newStatus === 'confirmed') {
                    // Lấy tên bệnh nhân
                    $patientName = $appointment->patient_name;
                    if (!$patientName && $appointment->patient_id) {
                        $patient = \DB::table('user')->where('id', $appointment->patient_id)->first();
                        $patientName = $patient ? $patient->full_name : 'Bệnh nhân';
                    }
                    
                    $this->sendDoctorAppointmentNotification($appointment->doctor_id, 'appointment_confirmed', [
                        'appointment_id' => $id,
                        'patient_name' => $patientName,
                        'date' => $appointment->appointment_date,
                        'time' => $appointment->time_slot
                    ]);
                } elseif ($newStatus === 'cancelled') {
                    // Lấy tên bệnh nhân
                    $patientName = $appointment->patient_name;
                    if (!$patientName && $appointment->patient_id) {
                        $patient = \DB::table('user')->where('id', $appointment->patient_id)->first();
                        $patientName = $patient ? $patient->full_name : 'Bệnh nhân';
                    }
                    
                    $this->sendDoctorAppointmentNotification($appointment->doctor_id, 'appointment_cancelled', [
                        'appointment_id' => $id,
                        'patient_name' => $patientName,
                        'date' => $appointment->appointment_date,
                        'time' => $appointment->time_slot
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => $newStatus === 'confirmed' ? 'Xác nhận lịch hẹn thành công' : 
                                ($newStatus === 'cancelled' ? 'Đã hủy lịch hẹn' : 'Cập nhật thành công')
                ]);
            }

            // Cập nhật đầy đủ thông tin (từ admin)
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctor,id',
                'appointment_date' => 'required|date',
                'time_slot' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Parse time slot
            $times = explode('-', $request->time_slot);
            $startTime = trim($times[0]);
            $endTime = isset($times[1]) ? trim($times[1]) : $startTime;

            // Check for duplicate (exclude current)
            $existing = \DB::table('appointment_schedules')
                ->where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('time_slot', $request->time_slot)
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khung giờ này đã tồn tại cho bác sĩ này'
                ], 400);
            }

            // Get patient name if patient_id provided
            $patientName = $request->patient_name;
            if ($request->patient_id) {
                $patient = \DB::table('user')->where('id', $request->patient_id)->first();
                if ($patient) {
                    $patientName = $patient->full_name;
                }
            }

            // Get clinic name if clinic_id provided
            $clinicName = null;
            if ($request->clinic_id) {
                $clinic = \DB::table('clinic')->where('id', $request->clinic_id)->first();
                if ($clinic) {
                    $clinicName = $clinic->name;
                }
            }

            \DB::table('appointment_schedules')->where('id', $id)->update([
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $request->appointment_date,
                'time_slot' => $request->time_slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'patient_id' => $request->patient_id,
                'patient_name' => $patientName,
                'patient_phone' => $request->patient_phone,
                'clinic_id' => $request->clinic_id,
                'clinic_name' => $clinicName,
                'room_number' => $request->room_number,
                'status' => $request->status ?? 'available',
                'notes' => $request->notes,
                'fee_amount' => $request->fee_amount ?? 0,
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lịch hẹn thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating appointment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật lịch hẹn'
            ], 500);
        }
    }

    /**
     * Delete an appointment
     */
    public function deleteAppointment($id)
    {
        try {
            $appointment = \DB::table('appointment_schedules')->where('id', $id)->first();

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy lịch hẹn'
                ], 404);
            }

            \DB::table('appointment_schedules')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa lịch hẹn thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting appointment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa lịch hẹn'
            ], 500);
        }
    }

    /**
     * Get patients list for dropdown
     */
    public function getPatients(Request $request)
    {
        try {
            $search = $request->get('q', '');

            $patients = \DB::table('user')
                ->where('type', 'USER')
                ->when($search, function($query) use ($search) {
                    return $query->where('full_name', 'like', "%{$search}%");
                })
                ->select('id', 'full_name', 'phone')
                ->orderBy('full_name')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching patients', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách bệnh nhân'
            ], 500);
        }
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request)
    {
        try {
            $user = $request->user();
            
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
                'new_password.required' => 'Vui lòng nhập mật khẩu mới',
                'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
                'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Verify current password
            if (!password_verify($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không đúng'
                ], 400);
            }
            
            // Update password
            $user->password = bcrypt($request->new_password);
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error changing password', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể đổi mật khẩu. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Update 2FA setting
     */
    public function updateTwoFactor(Request $request)
    {
        try {
            $user = $request->user();
            
            // Only USER and DOCTOR can enable 2FA (not ADMIN)
            if ($user->type === 'ADMIN') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tài khoản Admin không cần xác thực 2 yếu tố'
                ], 400);
            }
            
            // Accept both 'enabled' and 'two_factor_enabled'
            $enabled = $request->has('enabled') ? $request->enabled : $request->two_factor_enabled;
            
            $user->two_factor_enabled = (bool)$enabled;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => $user->two_factor_enabled ? 'Đã bật xác thực 2 yếu tố' : 'Đã tắt xác thực 2 yếu tố',
                'two_factor_enabled' => $user->two_factor_enabled
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating 2FA setting', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật cài đặt. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Submit feedback to admin
     */
    public function submitFeedback(Request $request)
    {
        try {
            $user = $request->user();
            
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:2000',
                'type' => 'nullable|string|in:feedback,suggestion,bug,other'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            // Insert feedback into forum_report table
            \DB::table('forum_report')->insert([
                'user_id' => $user->id,
                'post_id' => null,
                'comment_id' => null,
                'reason' => $request->type ?? 'Góp ý từ ' . ($user->type === 'DOCTOR' ? 'bác sĩ' : 'người dùng'),
                'detail' => $request->content,
                'status' => 'PENDING',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Góp ý của bạn đã được gửi tới Admin. Cảm ơn bạn!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error submitting feedback', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể gửi góp ý. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Get user's submitted feedbacks
     */
    public function getMyFeedbacks(Request $request)
    {
        try {
            $user = $request->user();
            
            $feedbacks = \DB::table('forum_report')
                ->where('user_id', $user->id)
                ->whereNull('post_id')
                ->whereNull('comment_id')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            
            return response()->json([
                'success' => true,
                'feedbacks' => $feedbacks
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching user feedbacks', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách góp ý'
            ], 500);
        }
    }

    /**
     * Get login history for current user
     */
    public function getLoginHistory(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get login history from user_token table
            $history = \DB::table('user_token')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'device' => $item->device ?? 'Trình duyệt',
                        'ip_address' => $item->ip_address ?? 'Không xác định',
                        'location' => $item->location ?? 'Không xác định',
                        'created_at' => Carbon::parse($item->created_at)->format('d/m/Y H:i'),
                        'last_used' => $item->updated_at ? Carbon::parse($item->updated_at)->diffForHumans() : 'Vừa đăng nhập',
                        'is_current' => false // Could compare with current token if needed
                    ];
                });
            
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching login history', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy lịch sử đăng nhập'
            ], 500);
        }
    }

    /**
     * Create public appointment (no auth required)
     */
    public function createPublicAppointment(Request $request)
    {
        try {
            Log::info('Creating public appointment', ['data' => $request->all()]);
            
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctor,id',
                'appointment_date' => 'required|date|after_or_equal:today',
                'time_slot' => 'required|string',
                'patient_name' => 'required|string|max:255',
                'patient_phone' => 'required|string|max:20',
                'patient_email' => 'required|email|max:255'
            ], [
                'doctor_id.required' => 'Vui lòng chọn bác sĩ',
                'appointment_date.required' => 'Vui lòng chọn ngày hẹn',
                'appointment_date.after_or_equal' => 'Ngày hẹn phải từ hôm nay trở đi',
                'time_slot.required' => 'Vui lòng chọn khung giờ',
                'patient_name.required' => 'Vui lòng nhập họ tên',
                'patient_phone.required' => 'Vui lòng nhập số điện thoại',
                'patient_email.required' => 'Vui lòng nhập email',
                'patient_email.email' => 'Email không hợp lệ'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Parse time slot and handle start_time/end_time
            $times = explode('-', $request->time_slot);
            $startTime = $request->start_time ?? trim($times[0]);
            
            // Calculate end_time: use provided end_time, or calculate from start + duration
            if ($request->end_time && $request->end_time !== $startTime) {
                $endTime = $request->end_time;
            } else {
                // Get service duration if service_id provided
                $duration = 60; // default 60 minutes
                if ($request->service_id) {
                    $service = \DB::table('treatment_service')->where('id', $request->service_id)->first();
                    if ($service && $service->duration_minutes) {
                        $duration = $service->duration_minutes;
                    }
                }
                // Calculate end time from start + duration
                $startDateTime = \Carbon\Carbon::createFromFormat('H:i', substr($startTime, 0, 5));
                $endDateTime = $startDateTime->copy()->addMinutes($duration);
                $endTime = $endDateTime->format('H:i');
            }

            // Check for active duplicate (not cancelled/completed)
            $existing = \DB::table('appointment_schedules')
                ->where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('time_slot', $request->time_slot)
                ->whereNotIn('status', ['cancelled', 'completed', 'available'])
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Khung giờ này đã có người đặt trước. Vui lòng chọn thời gian khác!'
                ], 400);
            }

            // Get clinic info from doctor
            $clinicId = $request->clinic_id;
            $clinicName = $request->clinic_name;
            
            if (!$clinicId) {
                $doctor = \DB::table('doctor')->where('id', $request->doctor_id)->first();
                if ($doctor && $doctor->clinic_id) {
                    $clinicId = $doctor->clinic_id;
                    $clinic = \DB::table('clinic')->where('id', $clinicId)->first();
                    if ($clinic) {
                        $clinicName = $clinic->name;
                    }
                }
            }

            // Check if there's a cancelled/available slot we can reuse
            $cancelledSlot = \DB::table('appointment_schedules')
                ->where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('time_slot', $request->time_slot)
                ->whereIn('status', ['cancelled', 'available'])
                ->first();

            $appointmentData = [
                'patient_id' => null, // No user account
                'patient_name' => $request->patient_name,
                'patient_phone' => $request->patient_phone,
                'patient_email' => $request->patient_email,
                'clinic_id' => $clinicId,
                'clinic_name' => $clinicName,
                'status' => $request->status ?? 'confirmed',
                'notes' => $request->notes,
                'fee_amount' => $request->fee_amount ?? 0,
                'is_foreign' => $request->is_foreign ?? false,
                'is_relative' => $request->is_relative ?? false,
                'payment_method' => $request->payment_method ?? 'cash',
                'service_id' => $request->service_id ?? null,
                'updated_at' => now()
            ];

            if ($cancelledSlot) {
                // Update existing cancelled/available slot
                \DB::table('appointment_schedules')
                    ->where('id', $cancelledSlot->id)
                    ->update($appointmentData);
                $id = $cancelledSlot->id;
            } else {
                // Create new appointment
                $appointmentData['doctor_id'] = $request->doctor_id;
                $appointmentData['appointment_date'] = $request->appointment_date;
                $appointmentData['time_slot'] = $request->time_slot;
                $appointmentData['start_time'] = $startTime;
                $appointmentData['end_time'] = $endTime;
                $appointmentData['created_at'] = now();
                
                $id = \DB::table('appointment_schedules')->insertGetId($appointmentData);
            }

            // Send notification to doctor
            $this->sendDoctorAppointmentNotification($request->doctor_id, 'new_appointment', [
                'appointment_id' => $id,
                'patient_name' => $request->patient_name,
                'date' => $request->appointment_date,
                'time' => $request->time_slot
            ]);

            // Send confirmation email to patient
            try {
                $doctorInfo = \DB::table('doctor')
                    ->join('user', 'doctor.user_id', '=', 'user.id')
                    ->leftJoin('clinic', 'doctor.clinic_id', '=', 'clinic.id')
                    ->where('doctor.id', $request->doctor_id)
                    ->select('user.full_name as doctor_name', 'doctor.degree', 'clinic.name as clinic_name', 'clinic.address as clinic_address')
                    ->first();

                $emailBody = "
                    <h2 style='color:#1e5ba8;'>🏥 Xác nhận đặt lịch khám thành công!</h2>
                    <p>Chào <strong>{$request->patient_name}</strong>,</p>
                    <p>Bạn đã đặt lịch khám thành công với thông tin như sau:</p>
                    <table style='width:100%; border-collapse:collapse; margin:20px 0;'>
                        <tr style='background:#f0f7ff;'>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Mã lịch hẹn:</td>
                            <td style='padding:10px; border:1px solid #ddd;'><strong>#{$id}</strong></td>
                        </tr>
                        <tr>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Bác sĩ:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>" . ($doctorInfo->degree ?? '') . " " . ($doctorInfo->doctor_name ?? 'Bác sĩ') . "</td>
                        </tr>
                        <tr style='background:#f0f7ff;'>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Ngày khám:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>" . date('d/m/Y', strtotime($request->appointment_date)) . "</td>
                        </tr>
                        <tr>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Giờ khám:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>{$request->time_slot}</td>
                        </tr>
                        <tr style='background:#f0f7ff;'>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Phòng khám:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>" . ($doctorInfo->clinic_name ?? 'Chưa xác định') . "</td>
                        </tr>
                        <tr>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Địa chỉ:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>" . ($doctorInfo->clinic_address ?? 'Chưa xác định') . "</td>
                        </tr>
                        <tr style='background:#f0f7ff;'>
                            <td style='padding:10px; border:1px solid #ddd; font-weight:bold;'>Tổng chi phí:</td>
                            <td style='padding:10px; border:1px solid #ddd;'>" . number_format($request->fee_amount ?? 0, 0, ',', '.') . " VNĐ</td>
                        </tr>
                    </table>
                    <p style='color:#666;'>Vui lòng đến trước giờ hẹn 15 phút để hoàn tất thủ tục.</p>
                    <p style='color:#666;'>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>
                    <hr style='margin:20px 0; border:none; border-top:1px solid #ddd;'>
                    <p style='color:#999; font-size:12px;'>Email này được gửi tự động từ hệ thống đặt lịch khám. Vui lòng không trả lời email này.</p>
                ";

                $mailService = new MailService();
                $mailService->send(
                    $request->patient_email,
                    $request->patient_name,
                    'Xác nhận đặt lịch khám #' . $id,
                    $emailBody
                );
            } catch (\Exception $emailError) {
                Log::warning('Failed to send confirmation email', ['error' => $emailError->getMessage()]);
                // Don't fail the whole request if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch thành công!',
                'data' => ['id' => $id]
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating public appointment', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể đặt lịch. Vui lòng thử lại!'
            ], 500);
        }
    }

    /**
     * Lookup appointments by email and phone (public)
     * Returns hints when one field is correct but other is wrong
     */
    public function lookupAppointments(Request $request)
    {
        try {
            $email = $request->query('email');
            $phone = $request->query('phone');

            // Allow lookup with at least one field
            if (!$email && !$phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập email hoặc số điện thoại'
                ], 400);
            }

            // Build query based on provided fields
            $query = \DB::table('appointment_schedules as a')
                ->join('doctor', 'a.doctor_id', '=', 'doctor.id')
                ->join('user as du', 'doctor.user_id', '=', 'du.id')
                ->leftJoin('clinic', 'a.clinic_id', '=', 'clinic.id')
                ->select(
                    'a.id',
                    'a.appointment_date',
                    'a.time_slot',
                    'a.start_time',
                    'a.end_time',
                    'a.patient_name',
                    'a.patient_phone',
                    'a.patient_email',
                    'a.status',
                    'a.notes',
                    'a.fee_amount',
                    'a.created_at',
                    'du.full_name as doctor_name',
                    'clinic.name as clinic_name',
                    'clinic.address as clinic_address'
                );

            // If both fields provided, look for exact match
            if ($email && $phone) {
                $appointments = $query
                    ->where('a.patient_email', $email)
                    ->where('a.patient_phone', $phone)
                    ->orderBy('a.appointment_date', 'desc')
                    ->orderBy('a.start_time', 'desc')
                    ->get();

                if ($appointments->isEmpty()) {
                    // Check if email exists with different phone
                    $emailExists = \DB::table('appointment_schedules')
                        ->where('patient_email', $email)
                        ->exists();

                    // Check if phone exists with different email  
                    $phoneExists = \DB::table('appointment_schedules')
                        ->where('patient_phone', $phone)
                        ->exists();

                    if ($emailExists && !$phoneExists) {
                        return response()->json([
                            'success' => false,
                            'hint' => 'email_mismatch',
                            'message' => 'Số điện thoại không khớp với email đã nhập'
                        ]);
                    }

                    if ($phoneExists && !$emailExists) {
                        return response()->json([
                            'success' => false,
                            'hint' => 'phone_mismatch',
                            'message' => 'Email không khớp với số điện thoại đã nhập'
                        ]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy lịch hẹn nào'
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'data' => $appointments
                ]);
            }

            // If only email provided
            if ($email) {
                $appointments = $query
                    ->where('a.patient_email', $email)
                    ->orderBy('a.appointment_date', 'desc')
                    ->get();
            } else {
                // If only phone provided
                $appointments = $query
                    ->where('a.patient_phone', $phone)
                    ->orderBy('a.appointment_date', 'desc')
                    ->get();
            }

            return response()->json([
                'success' => true,
                'data' => $appointments
            ]);

        } catch (\Exception $e) {
            Log::error('Error looking up appointments', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tra cứu lịch hẹn'
            ], 500);
        }
    }

    // ==================== SERVICE MANAGEMENT (ADMIN) ====================

    /**
     * Get all services (admin)
     */
    public function getAllServices(Request $request)
    {
        try {
            $services = \DB::table('treatment_service as ts')
                ->leftJoin('specialization as s', 'ts.specialization_id', '=', 's.id')
                ->select('ts.*', 's.name as specialization_name')
                ->orderBy('s.name', 'asc')
                ->orderBy('ts.name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching services', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy danh sách dịch vụ'
            ], 500);
        }
    }

    /**
     * Get a single service
     */
    public function getService($id)
    {
        try {
            $service = \DB::table('treatment_service as ts')
                ->leftJoin('specializations as s', 'ts.specialization_id', '=', 's.id')
                ->select('ts.*', 's.name as specialization_name')
                ->where('ts.id', $id)
                ->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dịch vụ'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $service
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching service', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy thông tin dịch vụ'
            ], 500);
        }
    }

    /**
     * Create a new service
     */
    public function createService(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'specialization_id' => 'required|integer|exists:specialization,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_minutes' => 'nullable|integer|min:1',
                'is_active' => 'nullable|boolean'
            ], [
                'specialization_id.required' => 'Chuyên khoa là bắt buộc',
                'specialization_id.exists' => 'Chuyên khoa không tồn tại',
                'name.required' => 'Tên dịch vụ là bắt buộc',
                'name.max' => 'Tên dịch vụ không được quá 255 ký tự',
                'price.required' => 'Giá dịch vụ là bắt buộc',
                'price.min' => 'Giá dịch vụ phải lớn hơn hoặc bằng 0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Check duplicate name within same specialization
            $existingService = \DB::table('treatment_service')
                ->where('name', $request->name)
                ->where('specialization_id', $request->specialization_id)
                ->first();

            if ($existingService) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên dịch vụ đã tồn tại trong chuyên khoa này'
                ], 400);
            }

            $id = \DB::table('treatment_service')->insertGetId([
                'specialization_id' => $request->specialization_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration_minutes' => $request->duration_minutes ?? 30,
                'is_active' => $request->is_active ?? true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $service = \DB::table('treatment_service as ts')
                ->leftJoin('specialization as s', 'ts.specialization_id', '=', 's.id')
                ->select('ts.*', 's.name as specialization_name')
                ->where('ts.id', $id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Tạo dịch vụ thành công',
                'data' => $service
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating service', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo dịch vụ'
            ], 500);
        }
    }

    /**
     * Update an existing service
     */
    public function updateService(Request $request, $id)
    {
        try {
            $service = \DB::table('treatment_service')->where('id', $id)->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dịch vụ'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'specialization_id' => 'required|integer|exists:specialization,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'duration_minutes' => 'nullable|integer|min:1',
                'is_active' => 'nullable|boolean'
            ], [
                'specialization_id.required' => 'Chuyên khoa là bắt buộc',
                'specialization_id.exists' => 'Chuyên khoa không tồn tại',
                'name.required' => 'Tên dịch vụ là bắt buộc',
                'name.max' => 'Tên dịch vụ không được quá 255 ký tự',
                'price.required' => 'Giá dịch vụ là bắt buộc'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            // Check duplicate name within same specialization (exclude current)
            $existingService = \DB::table('treatment_service')
                ->where('name', $request->name)
                ->where('specialization_id', $request->specialization_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingService) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tên dịch vụ đã tồn tại trong chuyên khoa này'
                ], 400);
            }

            \DB::table('treatment_service')->where('id', $id)->update([
                'specialization_id' => $request->specialization_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'duration_minutes' => $request->duration_minutes ?? $service->duration_minutes,
                'is_active' => $request->has('is_active') ? $request->is_active : $service->is_active,
                'updated_at' => now()
            ]);

            $updatedService = \DB::table('treatment_service')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ thành công',
                'data' => $updatedService
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating service', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật dịch vụ'
            ], 500);
        }
    }

    /**
     * Delete a service
     */
    public function deleteService($id)
    {
        try {
            $service = \DB::table('treatment_service')->where('id', $id)->first();

            if (!$service) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dịch vụ'
                ], 404);
            }

            // Check if service is being used in appointments
            $appointmentCount = \DB::table('appointment_schedules')
                ->where('service_id', $id)
                ->count();

            if ($appointmentCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể xóa dịch vụ này vì đang có {$appointmentCount} lịch hẹn sử dụng"
                ], 400);
            }

            \DB::table('treatment_service')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting service', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa dịch vụ'
            ], 500);
        }
    }

    /**
     * Get user settings
     */
    public function getSettings(Request $request)
    {
        try {
            $user = $request->user();
            
            return response()->json([
                'success' => true,
                'settings' => [
                    'email_notification' => (bool)($user->email_notification ?? true),
                    'reply_notification' => (bool)($user->reply_notification ?? true),
                    'two_factor_enabled' => (bool)($user->two_factor_enabled ?? false)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting settings', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể lấy cài đặt'
            ], 500);
        }
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        try {
            $user = $request->user();
            $updated = false;
            
            if ($request->has('email_notification')) {
                $user->email_notification = (bool)$request->email_notification;
                $updated = true;
            }
            
            if ($request->has('reply_notification')) {
                $user->reply_notification = (bool)$request->reply_notification;
                $updated = true;
            }
            
            if ($updated) {
                $user->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu cài đặt'
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating settings', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật cài đặt'
            ], 500);
        }
    }
}
