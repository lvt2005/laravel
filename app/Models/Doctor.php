<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    protected $table = 'doctor';
    protected $fillable = [
        'description', 'experience', 'rating_avg', 'specialization_id', 
        'user_id', 'doctor_status', 'clinic_id', 'degree'
    ];

    protected $casts = [
        'experience' => 'integer',
        'rating_avg' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialization(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Specialization::class, 'specialization_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Clinic::class, 'clinic_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(TreatmentService::class, 'doctor_service', 'doctor_id', 'service_id');
    }
}
