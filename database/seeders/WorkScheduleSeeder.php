<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkScheduleSeeder extends Seeder
{
    /**
     * Seed dữ liệu lịch làm việc mẫu cho các bác sĩ
     */
    public function run(): void
    {
        // Lấy tất cả bác sĩ
        $doctors = DB::table('doctor')->pluck('id')->toArray();

        if (empty($doctors)) {
            $this->command->info('Không có bác sĩ nào trong database. Vui lòng seed bác sĩ trước.');
            return;
        }

        // Các ngày trong tuần
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        // Các mẫu lịch làm việc - tất cả đều có 2 buổi sáng + chiều
        $scheduleTemplates = [
            // Template 1: Làm việc T2-T6 (cả ngày 08:00-17:00)
            [
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
            ],
            // Template 2: Làm việc T2-T7 (cả ngày 07:30-17:30)
            [
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'start_time' => '07:30:00',
                'end_time' => '17:30:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
            ],
            // Template 3: Làm việc T2-T6 (cả ngày 08:00-18:00)
            [
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'start_time' => '08:00:00',
                'end_time' => '18:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
            ],
            // Template 4: Làm việc cả tuần trừ CN (08:00-17:30)
            [
                'days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'start_time' => '08:00:00',
                'end_time' => '17:30:00',
                'break_start_time' => '11:30:00',
                'break_end_time' => '13:30:00',
            ],
            // Template 5: Làm việc T2,T4,T6,T7 (08:00-17:00)
            [
                'days' => ['Monday', 'Wednesday', 'Friday', 'Saturday'],
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'break_start_time' => '12:00:00',
                'break_end_time' => '13:00:00',
            ],
        ];

        // Xóa dữ liệu cũ (nếu có)
        DB::table('work_schedule')->truncate();

        $now = now();
        $insertData = [];

        foreach ($doctors as $index => $doctorId) {
            // Chọn template ngẫu nhiên hoặc theo thứ tự
            $template = $scheduleTemplates[$index % count($scheduleTemplates)];
            
            foreach ($template['days'] as $day) {
                $insertData[] = [
                    'doctor_id' => $doctorId,
                    'day_of_week' => $day,
                    'start_time' => $template['start_time'],
                    'end_time' => $template['end_time'],
                    'break_start_time' => $template['break_start_time'],
                    'break_end_time' => $template['break_end_time'],
                    'is_available' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Insert theo batch
        $chunks = array_chunk($insertData, 100);
        foreach ($chunks as $chunk) {
            DB::table('work_schedule')->insert($chunk);
        }

        $this->command->info('Đã seed ' . count($insertData) . ' lịch làm việc cho ' . count($doctors) . ' bác sĩ.');
    }
}
