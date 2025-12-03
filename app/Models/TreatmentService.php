<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentService extends Model
{
    use HasFactory;

    protected $table = 'treatment_service';

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_minutes',
        'is_active',
        'specialization_id',
        'avatar_url',
        'benefit1',
        'benefit2',
        'benefit3',
        'benefit4',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_minutes' => 'integer',
    ];

    /**
     * Scope to get only active services
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get appointments using this service
     */
    public function appointments()
    {
        return $this->hasMany(AppointmentSchedule::class, 'service_id');
    }

    /**
     * Get the specialization this service belongs to
     */
    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }
}
