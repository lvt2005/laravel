<?php

namespace App\Http\Controllers;

use App\Models\MedicalNote;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorMedicalNoteController extends Controller
{
    /**
     * Get doctor ID from authenticated user
     */
    private function getDoctorId(Request $request): ?int
    {
        $user = $request->user();
        if (!$user) return null;
        
        $doctor = Doctor::where('user_id', $user->id)->first();
        return $doctor?->id;
    }

    public function index(Request $request)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['message' => 'Doctor profile not found'], 404);

        $query = MedicalNote::where('doctor_id', $doctorId)->orderByDesc('visit_date')->orderByDesc('id');

        if ($search = $request->query('q')) {
            $query->where(function ($q2) use ($search) {
                $q2->where('patient_name', 'like', "%{$search}%")
                    ->orWhere('chief_complaint', 'like', "%{$search}%")
                    ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        $notes = $query->paginate(10);
        return response()->json($notes);
    }

    public function completedPatients(Request $request)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);

        $search = trim($request->get('search', ''));
        
        // Include all appointment statuses that should allow medical notes
        // Including: completed, booked, confirmed, ended, in_progress, pending_confirmation
        // Exclude: cancelled, rejected
        $query = \DB::table('appointment_schedules')
            ->leftJoin('user', 'user.id', '=', 'appointment_schedules.patient_id')
            // Also try to match by email if patient_id is null (guest booking with existing account)
            ->leftJoin('user as user_by_email', function($join) {
                $join->on('user_by_email.email', '=', 'appointment_schedules.patient_email')
                     ->whereNull('appointment_schedules.patient_id');
            })
            ->select(
                'appointment_schedules.patient_id', 
                \DB::raw('COALESCE(appointment_schedules.patient_name, user.full_name, user_by_email.full_name) as patient_name'),
                \DB::raw('COALESCE(appointment_schedules.patient_phone, user.phone, user_by_email.phone) as patient_phone'),
                \DB::raw('COALESCE(appointment_schedules.patient_email, user.email, user_by_email.email) as patient_email'),
                \DB::raw('COALESCE(user.avatar_url, user_by_email.avatar_url) as patient_avatar'),
                'appointment_schedules.appointment_date', 
                'appointment_schedules.status', 
                'appointment_schedules.id as appointment_id'
            )
            ->where('appointment_schedules.doctor_id', $doctorId)
            ->whereIn('appointment_schedules.status', [
                'completed', 'COMPLETED',
                'booked', 'BOOKED', 
                'confirmed', 'CONFIRMED',
                'ended', 'ENDED',
                'in_progress', 'IN_PROGRESS',
                'pending_confirmation', 'PENDING_CONFIRMATION'
            ])
            ->where(function($q) {
                $q->whereNotNull('appointment_schedules.patient_name')
                  ->orWhereNotNull('appointment_schedules.patient_id');
            });
        
        if ($search) {
            // Use LIKE directly without LOWER() for better Vietnamese support
            // MySQL LIKE is case-insensitive by default with utf8mb4_unicode_ci collation
            $searchPattern = "%{$search}%";
            $query->where(function($q) use ($searchPattern) {
                $q->whereRaw('COALESCE(appointment_schedules.patient_name, user.full_name, user_by_email.full_name) LIKE ?', [$searchPattern])
                  ->orWhereRaw('COALESCE(appointment_schedules.patient_phone, user.phone, user_by_email.phone) LIKE ?', [$searchPattern])
                  ->orWhereRaw('COALESCE(appointment_schedules.patient_email, user.email, user_by_email.email) LIKE ?', [$searchPattern])
                  ->orWhere('appointment_schedules.patient_id', 'like', $searchPattern);
            });
        }
        
        $rows = $query->orderBy('appointment_schedules.appointment_date', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rows
        ]);
    }

    public function store(Request $request)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['message' => 'Doctor profile not found'], 404);

        // Support both old field names and new simplified ones
        $data = $request->validate([
            'patient_id' => ['nullable', 'integer'],
            'appointment_id' => ['nullable', 'integer'],
            'patient_name' => ['nullable', 'string', 'max:255'],
            'patient_phone' => ['nullable', 'string', 'max:255'],
            'patient_email' => ['nullable', 'string', 'max:255'],
            'clinical_history' => ['nullable', 'string'],
            'chief_complaint' => ['nullable', 'string'],
            'symptoms' => ['nullable', 'string'], // Alias for chief_complaint
            'physical_examination' => ['nullable', 'string'],
            'diagnosis' => ['required', 'string'],
            'treatment_plan' => ['nullable', 'string'],
            'prescription' => ['nullable', 'string'], // Alias for treatment_plan
            'notes' => ['nullable', 'string'],
            'visit_date' => ['nullable', 'date'],
            'visit_type' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric'],
            'height' => ['nullable', 'numeric'],
            'blood_pressure_systolic' => ['nullable', 'integer'],
            'blood_pressure_diastolic' => ['nullable', 'integer'],
            'temperature' => ['nullable', 'numeric'],
            'heart_rate' => ['nullable', 'integer'],
            'status' => ['nullable', 'string', 'max:255'],
        ]);

        // Map simplified field names to database columns
        if (isset($data['symptoms']) && !isset($data['chief_complaint'])) {
            $data['chief_complaint'] = $data['symptoms'];
        }
        if (isset($data['prescription']) && !isset($data['treatment_plan'])) {
            $data['treatment_plan'] = $data['prescription'];
        }
        unset($data['symptoms'], $data['prescription']);

        // Get patient info from appointment if not provided
        if (!empty($data['appointment_id']) && empty($data['patient_name'])) {
            $appointment = \DB::table('appointment_schedules')
                ->leftJoin('user', 'user.id', '=', 'appointment_schedules.patient_id')
                ->select(
                    'appointment_schedules.patient_id',
                    \DB::raw('COALESCE(appointment_schedules.patient_name, user.full_name) as patient_name'),
                    \DB::raw('COALESCE(appointment_schedules.patient_phone, user.phone) as patient_phone'),
                    \DB::raw('COALESCE(appointment_schedules.patient_email, user.email) as patient_email')
                )
                ->where('appointment_schedules.id', $data['appointment_id'])
                ->first();
            
            if ($appointment) {
                $data['patient_id'] = $appointment->patient_id;
                $data['patient_name'] = $appointment->patient_name ?? 'Bệnh nhân';
                $data['patient_phone'] = $appointment->patient_phone;
                $data['patient_email'] = $appointment->patient_email;
            }
        }

        // Set defaults
        $data['doctor_id'] = $doctorId;
        $data['status'] = $data['status'] ?? 'final';
        $data['visit_date'] = $data['visit_date'] ?? date('Y-m-d');
        $data['patient_name'] = $data['patient_name'] ?? 'Bệnh nhân';
        $data['chief_complaint'] = $data['chief_complaint'] ?? '';
        $data['physical_examination'] = $data['physical_examination'] ?? '';
        $data['clinical_history'] = $data['clinical_history'] ?? '';
        $data['treatment_plan'] = $data['treatment_plan'] ?? '';

        $note = MedicalNote::create($data);

        return response()->json(['success' => true, 'data' => $note], 201);
    }

    public function show(Request $request, $id)
    {
        $doctorId = $this->getDoctorId($request);
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);
        return response()->json($note);
    }

    public function update(Request $request, $id)
    {
        $doctorId = $this->getDoctorId($request);
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);

        $data = $request->validate([
            'patient_id' => ['nullable', 'integer'],
            'appointment_id' => ['nullable', 'integer'],
            'patient_name' => ['sometimes', 'string', 'max:255'],
            'patient_phone' => ['sometimes', 'nullable', 'string', 'max:255'],
            'clinical_history' => ['sometimes', 'nullable', 'string'],
            'chief_complaint' => ['sometimes', 'nullable', 'string'],
            'symptoms' => ['sometimes', 'nullable', 'string'], // Alias for chief_complaint
            'physical_examination' => ['sometimes', 'nullable', 'string'],
            'diagnosis' => ['sometimes', 'string'],
            'treatment_plan' => ['sometimes', 'nullable', 'string'],
            'prescription' => ['sometimes', 'nullable', 'string'], // Alias for treatment_plan
            'notes' => ['sometimes', 'nullable', 'string'],
            'visit_date' => ['sometimes', 'date'],
            'visit_type' => ['sometimes', 'string', 'max:255'],
            'weight' => ['sometimes', 'nullable', 'numeric'],
            'height' => ['sometimes', 'nullable', 'numeric'],
            'blood_pressure_systolic' => ['sometimes', 'nullable', 'integer'],
            'blood_pressure_diastolic' => ['sometimes', 'nullable', 'integer'],
            'temperature' => ['sometimes', 'nullable', 'numeric'],
            'heart_rate' => ['sometimes', 'nullable', 'integer'],
            'status' => ['sometimes', 'string', 'max:255'],
        ]);

        // Map simplified field names to database columns
        if (isset($data['symptoms'])) {
            $data['chief_complaint'] = $data['symptoms'];
            unset($data['symptoms']);
        }
        if (isset($data['prescription'])) {
            $data['treatment_plan'] = $data['prescription'];
            unset($data['prescription']);
        }

        $note->fill($data);
        $note->save();

        return response()->json(['success' => true, 'data' => $note]);
    }

    public function destroy(Request $request, $id)
    {
        $doctorId = $this->getDoctorId($request);
        $note = MedicalNote::where('doctor_id', $doctorId)->findOrFail($id);
        $note->delete();
        return response()->json(['message' => 'Deleted']);
    }

    /**
     * Get appointments for today and tomorrow for the current doctor
     */
    public function getTodayTomorrowAppointments(Request $request)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);

        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        $appointments = \DB::table('appointment_schedules')
            ->leftJoin('user', 'user.id', '=', 'appointment_schedules.patient_id')
            ->leftJoin('user as user_by_email', function($join) {
                $join->on('user_by_email.email', '=', 'appointment_schedules.patient_email')
                     ->whereNull('appointment_schedules.patient_id');
            })
            ->select(
                'appointment_schedules.id',
                'appointment_schedules.patient_id',
                \DB::raw('COALESCE(appointment_schedules.patient_name, user.full_name, user_by_email.full_name) as patient_name'),
                \DB::raw('COALESCE(appointment_schedules.patient_phone, user.phone, user_by_email.phone) as patient_phone'),
                \DB::raw('COALESCE(appointment_schedules.patient_email, user.email, user_by_email.email) as patient_email'),
                \DB::raw('COALESCE(user.avatar_url, user_by_email.avatar_url) as patient_avatar'),
                'appointment_schedules.appointment_date',
                'appointment_schedules.start_time',
                'appointment_schedules.end_time',
                'appointment_schedules.time_slot',
                'appointment_schedules.status',
                'appointment_schedules.payment_status',
                'appointment_schedules.fee_amount',
                'appointment_schedules.service_name',
                'appointment_schedules.clinic_name',
                'appointment_schedules.notes'
            )
            ->where('appointment_schedules.doctor_id', $doctorId)
            ->whereBetween('appointment_schedules.appointment_date', [$today, $tomorrow])
            ->whereNotIn('appointment_schedules.status', ['available', 'cancelled'])
            ->orderBy('appointment_schedules.appointment_date')
            ->orderBy('appointment_schedules.start_time')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ]);
    }

    /**
     * Send medical note to patient via email
     */
    public function sendToPatient(Request $request, $id)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);

        $note = MedicalNote::where('doctor_id', $doctorId)->find($id);
        if (!$note) {
            return response()->json(['success' => false, 'message' => 'Medical note not found'], 404);
        }

        // Get appointment info if available
        $appointment = null;
        if ($note->appointment_id) {
            $appointment = \DB::table('appointment_schedules')->find($note->appointment_id);
        }

        // Get patient email
        $patientEmail = $note->patient_email ?? ($appointment->patient_email ?? null);
        
        // Try to get from user table if not in note
        if (!$patientEmail && $note->patient_id) {
            $user = \DB::table('user')->find($note->patient_id);
            $patientEmail = $user->email ?? null;
        }

        if (!$patientEmail) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy email bệnh nhân'], 400);
        }

        // Get doctor info
        $doctor = \DB::table('doctor')
            ->join('user', 'user.id', '=', 'doctor.user_id')
            ->where('doctor.id', $doctorId)
            ->select('user.full_name as doctor_name', 'doctor.specialization_id')
            ->first();

        $feeAmount = $appointment->fee_amount ?? 0;
        $paymentStatus = $appointment->payment_status ?? 'UNPAID';
        $isPaid = in_array(strtoupper($paymentStatus), ['PAID', 'COMPLETED']);

        // Build email content
        $subject = "Kết quả khám bệnh - " . date('d/m/Y', strtotime($note->visit_date));
        
        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background: #4a69bd; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .section { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; }
                .section h3 { color: #4a69bd; margin-top: 0; }
                .amount { font-size: 24px; color: #e74c3c; font-weight: bold; }
                .paid { color: #27ae60; }
                .unpaid { color: #e74c3c; }
                .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>🏥 DoctorHub</h1>
                <h2>Kết quả khám bệnh</h2>
            </div>
            <div class='content'>
                <p>Xin chào <strong>{$note->patient_name}</strong>,</p>
                <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi. Dưới đây là kết quả khám bệnh của bạn:</p>
                
                <div class='section'>
                    <h3>📋 Thông tin khám bệnh</h3>
                    <p><strong>Ngày khám:</strong> " . date('d/m/Y', strtotime($note->visit_date)) . "</p>
                    <p><strong>Bác sĩ:</strong> {$doctor->doctor_name}</p>
                </div>

                <div class='section'>
                    <h3>🩺 Triệu chứng</h3>
                    <p>{$note->chief_complaint}</p>
                </div>

                <div class='section'>
                    <h3>📝 Chẩn đoán</h3>
                    <p>{$note->diagnosis}</p>
                </div>

                <div class='section'>
                    <h3>💊 Đơn thuốc / Điều trị</h3>
                    <p>{$note->treatment_plan}</p>
                </div>

                <div class='section'>
                    <h3>💰 Chi phí dịch vụ</h3>
                    <p class='amount'>" . number_format($feeAmount, 0, ',', '.') . " đ</p>
                    <p><em>(Chưa bao gồm tiền thuốc)</em></p>
                    <p><strong>Trạng thái:</strong> <span class='" . ($isPaid ? 'paid' : 'unpaid') . "'>" . 
                        ($isPaid ? '✅ Đã thanh toán' : '❌ Chưa thanh toán') . "</span></p>
                    " . (!$isPaid ? "<p style='color: #e74c3c;'><strong>⚠️ Vui lòng thanh toán tiền mặt tại phòng thuốc!</strong></p>" : "") . "
                </div>
            </div>
            <div class='footer'>
                <p>Xin cảm ơn quý khách đã sử dụng dịch vụ của DoctorHub!</p>
                <p>Nếu có thắc mắc, vui lòng liên hệ hotline: 1900-xxxx</p>
            </div>
        </body>
        </html>
        ";

        // Send email
        try {
            \Mail::html($body, function ($message) use ($patientEmail, $subject, $note) {
                $message->to($patientEmail)
                        ->subject($subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi email thành công đến ' . $patientEmail
            ]);
        } catch (\Exception $e) {
            \Log::error('Send medical note email error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gửi email thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send medical note to pharmacy
     */
    public function sendToPharmacy(Request $request, $id)
    {
        $doctorId = $this->getDoctorId($request);
        if (!$doctorId) return response()->json(['success' => false, 'message' => 'Doctor profile not found'], 404);

        $note = MedicalNote::where('doctor_id', $doctorId)->find($id);
        if (!$note) {
            return response()->json(['success' => false, 'message' => 'Medical note not found'], 404);
        }

        // Get appointment info if available
        $appointment = null;
        if ($note->appointment_id) {
            $appointment = \DB::table('appointment_schedules')->find($note->appointment_id);
        }

        // Get doctor info
        $doctor = \DB::table('doctor')
            ->join('user', 'user.id', '=', 'doctor.user_id')
            ->where('doctor.id', $doctorId)
            ->select('user.full_name as doctor_name')
            ->first();

        $feeAmount = $appointment->fee_amount ?? 0;
        $paymentStatus = $appointment->payment_status ?? 'UNPAID';
        $isPaid = in_array(strtoupper($paymentStatus), ['PAID', 'COMPLETED']);

        // Get pharmacy email from system settings or use default
        $pharmacyEmail = \DB::table('system_settings')->where('key', 'pharmacy_email')->value('value') 
                         ?? 'pharmacy@doctor-appointment.com';

        $subject = "Đơn thuốc - Mã BN: " . ($note->patient_id ? 'BN' . str_pad($note->patient_id, 6, '0', STR_PAD_LEFT) : 'Khách vãng lai');
        
        $body = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .header { background: #27ae60; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .section { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #27ae60; }
                .section h3 { color: #27ae60; margin-top: 0; }
                .amount { font-size: 20px; color: #2c3e50; font-weight: bold; }
                .paid { color: #27ae60; }
                .unpaid { color: #e74c3c; font-weight: bold; }
                table { width: 100%; border-collapse: collapse; }
                td, th { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>💊 PHÒNG THUỐC - ĐƠN THUỐC MỚI</h1>
            </div>
            <div class='content'>
                <div class='section'>
                    <h3>👤 Thông tin bệnh nhân</h3>
                    <table>
                        <tr><td><strong>Họ tên:</strong></td><td>{$note->patient_name}</td></tr>
                        <tr><td><strong>Mã BN:</strong></td><td>" . ($note->patient_id ? 'BN' . str_pad($note->patient_id, 6, '0', STR_PAD_LEFT) : 'Khách vãng lai') . "</td></tr>
                        <tr><td><strong>SĐT:</strong></td><td>" . ($note->patient_phone ?? 'N/A') . "</td></tr>
                    </table>
                </div>

                <div class='section'>
                    <h3>📅 Thông tin lịch hẹn</h3>
                    <table>
                        <tr><td><strong>Ngày khám:</strong></td><td>" . date('d/m/Y', strtotime($note->visit_date)) . "</td></tr>
                        <tr><td><strong>Bác sĩ:</strong></td><td>{$doctor->doctor_name}</td></tr>
                        <tr><td><strong>Dịch vụ:</strong></td><td>" . ($appointment->service_name ?? 'Khám bệnh') . "</td></tr>
                    </table>
                </div>

                <div class='section'>
                    <h3>🩺 Chẩn đoán</h3>
                    <p>{$note->diagnosis}</p>
                </div>

                <div class='section'>
                    <h3>💊 ĐƠN THUỐC</h3>
                    <p style='white-space: pre-line; font-size: 16px;'>{$note->treatment_plan}</p>
                </div>

                <div class='section'>
                    <h3>💰 Thanh toán dịch vụ</h3>
                    <p class='amount'>" . number_format($feeAmount, 0, ',', '.') . " đ</p>
                    <p><em>(Tiền dịch vụ - chưa tính tiền thuốc)</em></p>
                    <p><strong>Trạng thái:</strong> <span class='" . ($isPaid ? 'paid' : 'unpaid') . "'>" . 
                        ($isPaid ? '✅ ĐÃ THANH TOÁN DỊCH VỤ' : '❌ CHƯA THANH TOÁN DỊCH VỤ - THU THÊM') . "</span></p>
                </div>
            </div>
        </body>
        </html>
        ";

        // Send email to pharmacy
        try {
            \Mail::html($body, function ($message) use ($pharmacyEmail, $subject) {
                $message->to($pharmacyEmail)
                        ->subject($subject);
            });

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi đến phòng thuốc (' . $pharmacyEmail . ')'
            ]);
        } catch (\Exception $e) {
            \Log::error('Send to pharmacy error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gửi thất bại: ' . $e->getMessage()
            ], 500);
        }
    }
}

