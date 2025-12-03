<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentProgress extends Model
{
    protected $table = 'treatment_progress';
    
    protected $fillable = [
        'order_id',
        'date',
        'diagnosis',
        'prescription',
        'doctor_note',
        'result',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(TreatmentOrder::class, 'order_id');
    }
}
