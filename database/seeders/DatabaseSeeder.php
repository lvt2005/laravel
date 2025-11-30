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
        // User::factory(10)->create();

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
        // Tạo bác sĩ mẫu
        $doctorId = \DB::table('doctor')->insertGetId([
            'user_id' => $user->id,
            'specialization_id' => $specializationId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo lịch hẹn mẫu
        $apptId = \DB::table('appointment_schedules')->insertGetId([
            'patient_id' => $user->id,
            'doctor_id' => $doctorId,
            'appointment_date' => now()->toDateString(),
            'time_slot' => '08:00-09:00',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'status' => 'booked', // Đổi từ 'confirmed' sang 'booked'
            'notes' => 'Khám tổng quát',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Tạo phiếu khám mẫu
        \DB::table('medical_notes')->insert([
            'doctor_id' => $doctorId,
            'patient_id' => $user->id,
            'appointment_id' => $apptId,
            'patient_name' => $user->full_name,
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
            'user_id' => $user->id,
            'title' => 'Chào mừng',
            'message' => 'Chào mừng bạn đến với hệ thống!',
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
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
