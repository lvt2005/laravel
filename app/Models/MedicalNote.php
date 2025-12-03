<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalNote extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medical_notes';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'patient_name',
        'patient_phone',
        'patient_email',
        'clinical_history',
        'chief_complaint',
        'physical_examination',
        'diagnosis',
        'treatment_plan',
        'notes',
        'visit_date',
        'visit_type',
        'weight',
        'height',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'temperature',
        'heart_rate',
        'status',
        'sent_to_patient',
        'sent_to_pharmacy',
        'pharmacy_status',
        'pharmacy_processed_at',
        'medicine_details',
        'medicine_total',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'weight' => 'float',
        'height' => 'float',
        'temperature' => 'float',
        'blood_pressure_systolic' => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'heart_rate' => 'integer',
        'sent_to_patient' => 'boolean',
        'sent_to_pharmacy' => 'boolean',
        'pharmacy_processed_at' => 'datetime',
        'medicine_total' => 'float',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(AppointmentSchedule::class, 'appointment_id');
    }
}
