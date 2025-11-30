<?php

namespace App\Console\Commands;

use App\Models\AppointmentSchedule;
use App\Models\Notification;
use App\Services\MailService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAppointmentStatus extends Command
{
    protected $signature = 'appointments:update-status';
    protected $description = 'Update appointment status to missed if overdue';

    public function handle()
    {
        $now = Carbon::now();
        $mailService = new MailService();

        // Find appointments that are 'confirmed' or 'booked' or 'pending_confirmation' 
        // and are past their end time.
        
        $overdueAppointments = AppointmentSchedule::whereIn('status', ['confirmed', 'booked', 'pending_confirmation'])
            ->where(function ($query) use ($now) {
                $query->where('appointment_date', '<', $now->toDateString())
                      ->orWhere(function ($q) use ($now) {
                          $q->where('appointment_date', '=', $now->toDateString())
                            ->where('end_time', '<', $now->toTimeString());
                      });
            })
            ->with(['patient', 'doctor'])
            ->get();

        $updatedCount = 0;
        
        foreach ($overdueAppointments as $appointment) {
            $appointment->status = 'missed';
            $appointment->save();
            $updatedCount++;

            // Create notification and send email for missed appointment
            if ($appointment->patient_id && $appointment->patient) {
                $doctorName = $appointment->doctor ? $appointment->doctor->full_name : 'Bác sĩ';
                
                // Send missed appointment email
                if ($appointment->patient->email) {
                    $result = $mailService->sendMissedAppointmentNotification(
                        $appointment->patient->email,
                        $appointment->patient->full_name ?? $appointment->patient->name ?? 'Quý khách',
                        $appointment->appointment_date,
                        $appointment->start_time,
                        $doctorName
                    );
                    
                    if ($result['success']) {
                        $this->info("📧 Missed appointment email sent to {$appointment->patient->email}");
                    } else {
                        $this->error("❌ Failed to send email: {$result['message']}");
                    }
                }
                
                Notification::create([
                    'user_id' => $appointment->patient_id,
                    'title' => 'Bạn đã vắng cuộc hẹn',
                    'message' => "Bạn đã không đến cuộc hẹn ngày {$appointment->appointment_date} lúc {$appointment->start_time} với {$doctorName}. Vui lòng liên hệ để đặt lịch mới.",
                    'type' => 1, // Type 1: Reminders/System
                    'related_id' => $appointment->id,
                    'is_read' => false,
                    'sent_via' => 1 // System
                ]);
            }
            
            $this->info("⚠️ Appointment {$appointment->id} marked as missed.");
        }
        
        $this->info("📊 Summary: {$updatedCount} appointments updated to 'missed'");
        return 0;
    }
}
