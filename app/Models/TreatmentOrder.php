<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentOrder extends Model
{
    protected $table = 'treatment_order';
    
    protected $fillable = [
        'user_id',
        'doctor_id',
        'service_id',
        'appointment_date',
        'status',
        'note',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(TreatmentService::class, 'service_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(TreatmentProgress::class, 'order_id');
    }
}
