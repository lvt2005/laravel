<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentSchedule extends Model
{
    protected $table = 'appointment_schedules';
    
    protected $fillable = [
        'doctor_id',
        'appointment_date',
        'time_slot',
        'start_time',
        'end_time',
        'patient_id',
        'patient_name',
        'patient_phone',
        'patient_email',
        'clinic_id',
        'service_id',
        'service_name',
        'clinic_name',
        'room_number',
        'status',
        'notes',
        'is_foreign',
        'is_relative',
        'payment_method',
        'fee_amount',
        'payment_status',
        'payment_method_id',
        'paid_at',
        'transaction_id',
        'refund_status',
        'refund_method_id',
        'refund_requested_at',
        'refund_locked',
        'refund_otp',
        'refund_otp_expires_at',
        'refund_reason',
        'refund_completed_at'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'paid_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_otp_expires_at' => 'datetime',
        'refund_completed_at' => 'datetime',
        'refund_locked' => 'boolean',
        'is_foreign' => 'boolean',
        'is_relative' => 'boolean',
        'fee_amount' => 'decimal:2',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class, 'clinic_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(TreatmentService::class, 'service_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function refundMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'refund_method_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'appointment_id');
    }

    public function medicalNote()
    {
        return $this->hasOne(MedicalNote::class, 'appointment_id');
    }
}
