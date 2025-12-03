<?php

namespace App\Console\Commands;

use App\Models\AppointmentSchedule;
use App\Models\Notification;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send reminders for upcoming appointments';

    public function handle()
    {
        // Remind 1 day before
        $tomorrow = Carbon::tomorrow()->toDateString();

        $appointments = AppointmentSchedule::where('appointment_date', $tomorrow)
            ->whereIn('status', ['confirmed', 'booked'])
            ->whereNotNull('patient_id')
            ->with(['patient', 'doctor', 'clinic'])
            ->get();

        $mailService = new MailService();
        $sentCount = 0;
        $failedCount = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->patient && $appointment->patient->email) {
                // Get doctor and clinic info
                $doctorName = $appointment->doctor ? $appointment->doctor->full_name : 'Bác sĩ';
                $clinicName = $appointment->clinic ? $appointment->clinic->name : 'Phòng khám';
                
                // Send Email using PHPMailer
                $result = $mailService->sendAppointmentReminder(
                    $appointment->patient->email,
                    $appointment->patient->full_name ?? $appointment->patient->name ?? 'Quý khách',
                    $appointment->appointment_date,
                    $appointment->start_time,
                    $doctorName,
                    $clinicName
                );
                
                $emailStatus = $result['success'] ? 'sent' : 'failed';
                
                if ($result['success']) {
                    $sentCount++;
                    $this->info("✅ Email sent to {$appointment->patient->email} for appointment {$appointment->id}");
                } else {
                    $failedCount++;
                    $this->error("❌ Failed to send email to {$appointment->patient->email}: {$result['message']}");
                }
                
                // Create Notification
                Notification::create([
                    'user_id' => $appointment->patient_id,
                    'title' => 'Nhắc nhở lịch hẹn',
                    'message' => "Bạn có lịch hẹn khám bệnh vào ngày mai ({$appointment->appointment_date}) lúc {$appointment->start_time} với {$doctorName} tại {$clinicName}. " . ($result['success'] ? 'Email nhắc nhở đã được gửi.' : ''),
                    'type' => 1,
                    'related_id' => $appointment->id,
                    'is_read' => false,
                    'sent_via' => $result['success'] ? 1 : 0 // 1 = email sent, 0 = notification only
                ]);
            }
        }

        $this->info("📧 Summary: {$sentCount} emails sent, {$failedCount} failed");
        return 0;
    }
}
