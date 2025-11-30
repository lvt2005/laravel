<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\TreatmentService;
use App\Services\MailService;

class PublicController extends Controller
{
    /**
     * Lấy danh sách bác sĩ (public - không cần auth)
     */
    public function getDoctors(Request $request)
    {
        $perPage = $request->input('per_page', 8);
        $page = $request->input('page', 1);
        $specializationId = $request->input('specialization_id');
        $search = $request->input('search');

        $query = Doctor::with(['user', 'specialization', 'clinic'])
            ->where('doctor_status', 'ACTIVE')
            ->whereHas('user', function ($q) {
                $q->where('status', 'ACTIVE');
            });

        // Lọc theo chuyên khoa
        if ($specializationId) {
            $query->where('specialization_id', $specializationId);
        }

        // Tìm kiếm theo tên
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%");
            });
        }

        // Sắp xếp theo rating và kinh nghiệm
        $query->orderByDesc('rating_avg')->orderByDesc('experience');

        $doctors = $query->paginate($perPage, ['*'], 'page', $page);

        // Format dữ liệu trả về
        $formattedDoctors = $doctors->getCollection()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'full_name' => $doctor->user->full_name ?? 'Bác sĩ',
                'avatar_url' => $doctor->user->avatar_url ?? null,
                'email' => $doctor->user->email ?? null,
                'phone' => $doctor->user->phone ?? null,
                'degree' => $doctor->degree ?? null,
                'experience' => $doctor->experience ?? 0,
                'description' => $doctor->description ?? '',
                'rating_avg' => $doctor->rating_avg ?? 0,
                'rating_count' => $doctor->rating_count ?? 0,
                'specialization_id' => $doctor->specialization_id ?? null,
                'specialization' => $doctor->specialization ? [
                    'id' => $doctor->specialization->id,
                    'name' => $doctor->specialization->name,
                ] : null,
                'clinic' => $doctor->clinic ? [
                    'id' => $doctor->clinic->id,
                    'name' => $doctor->clinic->name,
                    'address' => $doctor->clinic->address,
                ] : null,
            ];
        });

        return response()->json([
            'data' => $formattedDoctors,
            'pagination' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
            ]
        ]);
    }

    /**
     * Lấy thông tin chi tiết một bác sĩ
     */
    public function getDoctor($id)
    {
        $doctor = Doctor::with(['user', 'specialization', 'clinic'])
            ->where('doctor_status', 'ACTIVE')
            ->find($id);

        if (!$doctor) {
            return response()->json(['error' => 'DOCTOR_NOT_FOUND'], 404);
        }

        return response()->json([
            'id' => $doctor->id,
            'full_name' => $doctor->user->full_name ?? 'Bác sĩ',
            'avatar_url' => $doctor->user->avatar_url ?? null,
            'email' => $doctor->user->email ?? null,
            'phone' => $doctor->user->phone ?? null,
            'degree' => $doctor->degree ?? null,
            'experience' => $doctor->experience ?? 0,
            'description' => $doctor->description ?? '',
            'rating_avg' => $doctor->rating_avg ?? 0,
            'specialization_id' => $doctor->specialization_id ?? null,
            'specialization' => $doctor->specialization ? [
                'id' => $doctor->specialization->id,
                'name' => $doctor->specialization->name,
                'description' => $doctor->specialization->description,
            ] : null,
            'clinic' => $doctor->clinic ? [
                'id' => $doctor->clinic->id,
                'name' => $doctor->clinic->name,
                'address' => $doctor->clinic->address,
                'phone' => $doctor->clinic->phone,
            ] : null,
        ]);
    }

    /**
     * Lấy đánh giá của bác sĩ (public)
     */
    public function getDoctorReviews($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'DOCTOR_NOT_FOUND'], 404);
        }

        $reviews = \DB::table('review')
            ->leftJoin('user', 'review.user_id', '=', 'user.id')
            ->where('review.doctor_id', $id)
            ->select(
                'review.id',
                'review.rating',
                'review.comment',
                'review.created_at',
                'user.full_name as patient_name'
            )
            ->orderBy('review.created_at', 'desc')
            ->get();

        $avgRating = $reviews->avg('rating') ?? 0;
        $totalReviews = $reviews->count();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($avgRating, 1),
            'total_reviews' => $totalReviews
        ]);
    }

    /**
     * Lấy danh sách chuyên khoa (public)
     */
    public function getSpecializations()
    {
        $specializations = Specialization::withCount(['doctors' => function ($query) {
            $query->where('doctor_status', 'ACTIVE');
        }])->get();

        return response()->json([
            'success' => true,
            'data' => $specializations->map(function ($spec) {
                return [
                    'id' => $spec->id,
                    'name' => $spec->name,
                    'description' => $spec->description,
                    'image_url' => $spec->image_url,
                    'doctor_count' => $spec->doctors_count,
                ];
            })
        ]);
    }

    /**
     * Lấy danh sách dịch vụ (public)
     */
    public function getServices(Request $request)
    {
        $query = TreatmentService::where('is_active', true);

        // Filter by specialization if provided
        if ($request->has('specialization_id') && $request->specialization_id) {
            $query->where('specialization_id', $request->specialization_id);
        }

        $services = $query->orderBy('name')->get(['id', 'name', 'description', 'price', 'duration_minutes', 'specialization_id']);

        return response()->json([
            'success' => true,
            'data' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'formatted_price' => number_format($service->price, 0, ',', '.') . ' đ',
                    'duration_minutes' => $service->duration_minutes,
                    'specialization_id' => $service->specialization_id,
                ];
            })
        ]);
    }

    /**
     * Check if email or phone exists in database (public)
     * Returns user data (name, birthday) if exists for auto-fill
     */
    public function checkUserExists(Request $request)
    {
        try {
            $email = $request->query('email');
            $phone = $request->query('phone');

            $result = [
                'email_exists' => false,
                'phone_exists' => false,
                'user_data' => null,
                'message' => null
            ];

            if ($email) {
                $user = \DB::table('user')->where('email', $email)->first();
                $result['email_exists'] = !is_null($user);
                if ($user) {
                    $result['email_message'] = 'Email này đã được đăng ký tài khoản. Đăng nhập để trải nghiệm tốt hơn!';
                    $result['user_data'] = [
                        'full_name' => $user->full_name ?? null,
                        'birthday' => $user->birthday ?? null,
                    ];
                }
            }

            if ($phone) {
                $user = \DB::table('user')->where('phone', $phone)->first();
                $result['phone_exists'] = !is_null($user);
                if ($user) {
                    $result['phone_message'] = 'Số điện thoại này đã có trong hệ thống. Bạn có muốn đăng nhập?';
                    if (!$result['user_data']) {
                        $result['user_data'] = [
                            'full_name' => $user->full_name ?? null,
                            'birthday' => $user->birthday ?? null,
                        ];
                    }
                }
            }

            return response()->json($result);
        } catch (\Exception $e) {
            \Log::error('Error checking user exists', ['error' => $e->getMessage()]);
            return response()->json([
                'email_exists' => false,
                'phone_exists' => false,
                'user_data' => null,
                'error' => 'Không thể kiểm tra thông tin'
            ]);
        }
    }

    /**
     * Send booking verification code to email (public - no user required)
     * Uses Cache instead of database for guest users
     */
    public function sendBookingVerificationCode(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }

        $email = $request->input('email');
        
        // Rate limiting: max 3 codes per email per 10 minutes
        $rateLimitKey = 'booking_code_limit:' . $email;
        $attempts = Cache::get($rateLimitKey, 0);
        
        if ($attempts >= 3) {
            return response()->json([
                'error' => 'RATE_LIMITED',
                'message' => 'Bạn đã yêu cầu quá nhiều mã. Vui lòng đợi 10 phút.'
            ], 429);
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store code in cache for 5 minutes
        $cacheKey = 'booking_verification:' . $email;
        Cache::put($cacheKey, Hash::make($code), now()->addMinutes(5));
        
        // Increment rate limit counter
        Cache::put($rateLimitKey, $attempts + 1, now()->addMinutes(10));

        // Send email
        try {
            $mailService = new MailService();
            $emailBody = "
                <h2 style='color:#1e5ba8;'>🔐 Mã xác nhận đặt lịch khám</h2>
                <p>Xin chào,</p>
                <p>Mã xác nhận của bạn là:</p>
                <div style='text-align:center; margin:30px 0;'>
                    <span style='font-size:32px; font-weight:bold; letter-spacing:8px; color:#1e5ba8; background:#f0f7ff; padding:15px 30px; border-radius:10px;'>{$code}</span>
                </div>
                <p style='color:#666;'>Mã này có hiệu lực trong <strong>5 phút</strong>.</p>
                <p style='color:#666;'>Nếu bạn không yêu cầu mã này, vui lòng bỏ qua email này.</p>
                <hr style='margin:20px 0; border:none; border-top:1px solid #ddd;'>
                <p style='color:#999; font-size:12px;'>Email này được gửi tự động từ hệ thống đặt lịch khám.</p>
            ";

            $result = $mailService->send(
                $email,
                'Khách hàng',
                'Mã xác nhận đặt lịch khám',
                $emailBody
            );

            if (!$result) {
                return response()->json([
                    'error' => 'EMAIL_FAILED',
                    'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send booking verification code: ' . $e->getMessage());
            return response()->json([
                'error' => 'EMAIL_FAILED',
                'message' => 'Không thể gửi email. Vui lòng thử lại sau.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Mã xác nhận đã được gửi đến email của bạn',
            'expires_in' => 300
        ]);
    }

    /**
     * Verify booking code (public)
     */
    public function verifyBookingCode(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        if ($v->fails()) {
            return response()->json(['error' => 'VALIDATION', 'fields' => $v->errors()], 422);
        }

        $email = $request->input('email');
        $code = $request->input('code');

        $cacheKey = 'booking_verification:' . $email;
        $hashedCode = Cache::get($cacheKey);

        if (!$hashedCode) {
            return response()->json([
                'success' => false,
                'error' => 'CODE_EXPIRED',
                'message' => 'Mã xác nhận đã hết hạn hoặc không tồn tại'
            ], 400);
        }

        if (!Hash::check($code, $hashedCode)) {
            return response()->json([
                'success' => false,
                'error' => 'INVALID_CODE',
                'message' => 'Mã xác nhận không chính xác'
            ], 400);
        }

        // Mark as verified (store verification status for 30 minutes)
        $verifiedKey = 'booking_verified:' . $email;
        Cache::put($verifiedKey, true, now()->addMinutes(30));
        
        // Delete the used code
        Cache::forget($cacheKey);

        return response()->json([
            'success' => true,
            'message' => 'Xác nhận thành công'
        ]);
    }

    /**
     * Lấy lịch làm việc của bác sĩ (work_schedule)
     */
    public function getDoctorWorkSchedule($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'DOCTOR_NOT_FOUND'], 404);
        }

        $workSchedules = \DB::table('work_schedule')
            ->where('doctor_id', $id)
            ->where('is_available', true)
            ->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
            ->get();

        return response()->json([
            'success' => true,
            'data' => $workSchedules
        ]);
    }

    /**
     * Lấy các slot đã được đặt của bác sĩ trong khoảng ngày
     */
    public function getDoctorBookedSlots($id, Request $request)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'DOCTOR_NOT_FOUND'], 404);
        }

        $startDate = $request->query('start_date', now()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->addDays(14)->format('Y-m-d'));

        $bookedSlots = \DB::table('appointment_schedules')
            ->where('doctor_id', $id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->whereIn('status', ['booked', 'confirmed', 'completed', 'pending'])
            ->select('appointment_date', 'start_time', 'end_time', 'status')
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookedSlots
        ]);
    }

    /**
     * Lấy lịch chi tiết của bác sĩ cho 1 ngày cụ thể
     * Trả về các time slots với trạng thái available/booked
     */
    public function getDoctorDaySchedule($id, Request $request)
    {
        $doctor = Doctor::with(['specialization', 'clinic'])->find($id);
        if (!$doctor) {
            return response()->json(['error' => 'DOCTOR_NOT_FOUND'], 404);
        }

        $date = $request->query('date', now()->format('Y-m-d'));
        $dayOfWeek = date('l', strtotime($date)); // Get day name (Monday, Tuesday, etc.)

        // Get work schedule for this day
        $workSchedule = \DB::table('work_schedule')
            ->where('doctor_id', $id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$workSchedule) {
            return response()->json([
                'success' => true,
                'data' => [
                    'is_working' => false,
                    'message' => 'Bác sĩ không làm việc ngày này',
                    'slots' => []
                ]
            ]);
        }

        // Get booked appointments for this date
        $bookedSlots = \DB::table('appointment_schedules')
            ->where('doctor_id', $id)
            ->where('appointment_date', $date)
            ->whereIn('status', ['booked', 'completed', 'pending'])
            ->select('start_time', 'end_time')
            ->get()
            ->toArray();

        // Generate time slots (30 minutes each)
        $slots = [];
        $startTime = strtotime($workSchedule->start_time);
        $endTime = strtotime($workSchedule->end_time);
        $breakStart = $workSchedule->break_start_time ? strtotime($workSchedule->break_start_time) : null;
        $breakEnd = $workSchedule->break_end_time ? strtotime($workSchedule->break_end_time) : null;
        $slotDuration = 30 * 60; // 30 minutes

        $currentTime = $startTime;
        while ($currentTime + $slotDuration <= $endTime) {
            $slotStart = date('H:i:s', $currentTime);
            $slotEnd = date('H:i:s', $currentTime + $slotDuration);

            // Check if this slot is during break time
            $isDuringBreak = false;
            if ($breakStart && $breakEnd) {
                if ($currentTime >= $breakStart && $currentTime < $breakEnd) {
                    $isDuringBreak = true;
                }
            }

            // Check if this slot is booked
            $isBooked = false;
            foreach ($bookedSlots as $booked) {
                $bookedStart = strtotime($booked->start_time);
                $bookedEnd = strtotime($booked->end_time);
                if ($currentTime >= $bookedStart && $currentTime < $bookedEnd) {
                    $isBooked = true;
                    break;
                }
            }

            // Check if slot is in the past
            $slotDateTime = strtotime($date . ' ' . $slotStart);
            $isPast = $slotDateTime < time();

            $status = 'available';
            if ($isPast) {
                $status = 'past';
            } elseif ($isDuringBreak) {
                $status = 'break';
            } elseif ($isBooked) {
                $status = 'booked';
            }

            $slots[] = [
                'start_time' => substr($slotStart, 0, 5),
                'end_time' => substr($slotEnd, 0, 5),
                'status' => $status
            ];

            $currentTime += $slotDuration;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'is_working' => true,
                'work_schedule' => [
                    'start_time' => substr($workSchedule->start_time, 0, 5),
                    'end_time' => substr($workSchedule->end_time, 0, 5),
                    'break_start' => $workSchedule->break_start_time ? substr($workSchedule->break_start_time, 0, 5) : null,
                    'break_end' => $workSchedule->break_end_time ? substr($workSchedule->break_end_time, 0, 5) : null,
                ],
                'slots' => $slots,
                'doctor' => [
                    'id' => $doctor->id,
                    'specialization_id' => $doctor->specialization_id,
                    'specialization_name' => $doctor->specialization->name ?? null,
                ]
            ]
        ]);
    }

    /**
     * Get maintenance status (always accessible, even during maintenance)
     */
    public function getMaintenanceStatus()
    {
        $isMaintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
        $message = \App\Models\SystemSetting::getMaintenanceMessage();

        return response()->json([
            'success' => true,
            'maintenance' => $isMaintenanceMode,
            'message' => $isMaintenanceMode ? $message : null
        ]);
    }
}
