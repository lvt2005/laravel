<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo Admin user
        User::factory()->create([
            'full_name' => 'Administrator',
            'email' => 'admin@doctor-appointment.com',
            'password' => bcrypt('Admin@123'),
            'type' => 'ADMIN',
            'status' => 'ACTIVE',
        ]);

        // Tạo Doctor user
        $doctorUser = User::factory()->create([
            'full_name' => 'Bác sĩ Nguyễn Văn A',
            'email' => 'doctor@doctor-appointment.com',
            'password' => bcrypt('Doctor@123'),
            'type' => 'DOCTOR',
            'status' => 'ACTIVE',
            'phone' => '0901234567',
        ]);

        // Tạo Test User
        User::factory()->create([
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('test1234'),
            'type' => 'USER',
            'status' => 'ACTIVE',
        ]);

        $user = \App\Models\User::first();
        // Tạo chuyên specialty mẫu
        $specializationId = \DB::table('specialization')->insertGetId([
            'name' => 'Nội tổng quát',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Thêm các chuyên khoa khác
        \DB::table('specialization')->insert([
            ['name' => 'Tim mạch', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Da liễu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Thần kinh', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nhi khoa', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        // Tạo bác sĩ mẫu - sử dụng doctorUser thay vì user đầu tiên (admin)
        $doctorId = \DB::table('doctor')->insertGetId([
            'user_id' => $doctorUser->id,
            'specialization_id' => $specializationId,
            'experience' => 10,
            'description' => 'Bác sĩ chuyên khoa nội tổng quát với hơn 10 năm kinh nghiệm',
            'degree' => 'Tiến sĩ Y khoa',
            'doctor_status' => 'ACTIVE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Tạo clinic mẫu
        $clinicId = \DB::table('clinic')->insertGetId([
            'name' => 'Phòng khám Đa khoa ABC',
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'hotline' => '1900 1234',
            'email' => 'contact@phongkhamabc.vn',
            'opening_hours' => '08:00 - 17:00',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Cập nhật doctor với clinic
        \DB::table('doctor')->where('id', $doctorId)->update(['clinic_id' => $clinicId]);
        
        // Tạo treatment service mẫu
        $serviceId = \DB::table('treatment_service')->insertGetId([
            'name' => 'Khám tổng quát',
            'description' => 'Khám sức khỏe tổng quát định kỳ',
            'price' => 300000,
            'duration_minutes' => 30,
            'is_active' => true,
            'specialization_id' => $specializationId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Gán service cho doctor
        \DB::table('doctor_service')->insert([
            'doctor_id' => $doctorId,
            'service_id' => $serviceId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Lấy test user (không phải admin hay doctor)
        $testUser = \App\Models\User::where('email', 'test@example.com')->first();
        
        // Tạo lịch hẹn mẫu
        $apptId = \DB::table('appointment_schedules')->insertGetId([
            'patient_id' => $testUser->id,
            'doctor_id' => $doctorId,
            'clinic_id' => $clinicId,
            'service_id' => $serviceId,
            'appointment_date' => now()->toDateString(),
            'time_slot' => '08:00-09:00',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => 'booked',
            'notes' => 'Khám tổng quát',
            'patient_name' => $testUser->full_name,
            'patient_phone' => $testUser->phone ?? '0123456789',
            'patient_email' => $testUser->email,
            'fee_amount' => 300000,
            'payment_status' => 'UNPAID',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo phiếu khám mẫu
        \DB::table('medical_notes')->insert([
            'doctor_id' => $doctorId,
            'patient_id' => $testUser->id,
            'appointment_id' => $apptId,
            'patient_name' => $testUser->full_name,
            'clinical_history' => 'Không có tiền sử bệnh.',
            'chief_complaint' => 'Đau đầu',
            'physical_examination' => 'Bình thường',
            'diagnosis' => 'Cảm cúm',
            'treatment_plan' => 'Nghỉ ngơi, uống thuốc',
            'notes' => 'Tái khám sau 1 tuần',
            'visit_date' => now()->toDateString(),
            'visit_type' => 'routine',
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo thông báo mẫu
        \DB::table('notification')->insert([
            'user_id' => $testUser->id,
            'title' => 'Chào mừng',
            'message' => 'Chào mừng bạn đến với hệ thống!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Tạo thông báo cho admin
        \DB::table('notification')->insert([
            'user_id' => $user->id,
            'title' => 'Hệ thống sẵn sàng',
            'message' => 'Hệ thống đặt lịch khám đã được khởi tạo thành công!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo payment mẫu
        $paymentId = \DB::table('payment')->insertGetId([
            'amount' => 200000,
            'method' => 'CASH',
            'status' => 'PAID',
            'transaction_code' => 'TXN123',
            'order_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo payment_logs mẫu
        \DB::table('payment_logs')->insert([
            'payment_id' => $paymentId,
            'action' => 'pay',
            'status_before' => 'PENDING',
            'status_after' => 'PAID',
            'amount' => 200000,
            'note' => 'Thanh toán thành công',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'seeder',
            'created_at' => now(),
        ]);
        // Tạo review mẫu
        \DB::table('review')->insert([
            'comment' => 'Bác sĩ rất tận tình',
            'rating' => 5,
            'doctor_id' => $doctorId,
            'user_id' => $testUser->id,
            'appointment_id' => $apptId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Tạo work schedule mẫu cho doctor
        $daysOfWeek = [1, 2, 3, 4, 5]; // Thứ 2 đến thứ 6
        foreach ($daysOfWeek as $day) {
            \DB::table('work_schedule')->insert([
                'doctor_id' => $doctorId,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:30:00',
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Tạo system settings mẫu - All settings ON by default
        $settings = [
            // General settings
            ['setting_key' => 'site_name', 'setting_value' => 'Doctor Appointment System', 'setting_type' => 'string', 'description' => 'Tên hệ thống'],
            
            // Maintenance settings
            ['setting_key' => 'maintenance_mode', 'setting_value' => 'false', 'setting_type' => 'boolean', 'description' => 'Chế độ bảo trì'],
            ['setting_key' => 'maintenance_message', 'setting_value' => 'Hệ thống đang bảo trì, vui lòng quay lại sau.', 'setting_type' => 'string', 'description' => 'Thông báo bảo trì'],
            
            // Payment settings - ON by default
            ['setting_key' => 'payment_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Bật tính năng thanh toán online'],
            
            // Email settings - ON by default
            ['setting_key' => 'email_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Bật gửi email thông báo'],
            ['setting_key' => 'email_user_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Gửi email cho bệnh nhân'],
            ['setting_key' => 'email_doctor_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Gửi email cho bác sĩ'],
            
            // Access settings - ON by default
            ['setting_key' => 'access_user_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Cho phép bệnh nhân truy cập'],
            ['setting_key' => 'access_doctor_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Cho phép bác sĩ truy cập'],
            
            // Guest booking - ON by default
            ['setting_key' => 'guest_booking_enabled', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Cho phép khách đặt lịch không cần đăng nhập'],
            
            // Security settings
            ['setting_key' => 'auto_block_failed_login', 'setting_value' => 'true', 'setting_type' => 'boolean', 'description' => 'Tự động chặn IP khi đăng nhập sai nhiều lần'],
            ['setting_key' => 'max_failed_login_attempts', 'setting_value' => '5', 'setting_type' => 'number', 'description' => 'Số lần đăng nhập sai tối đa'],
            
            // Blocked IPs (JSON array)
            ['setting_key' => 'blocked_ips', 'setting_value' => '[]', 'setting_type' => 'json', 'description' => 'Danh sách IP bị chặn'],
        ];
        
        foreach ($settings as $setting) {
            \DB::table('system_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
