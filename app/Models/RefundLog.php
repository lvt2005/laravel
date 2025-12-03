<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundLog extends Model
{
    protected $table = 'refund_logs';
    
    public $timestamps = false;

    protected $fillable = [
        'refund_id',
        'action',
        'status_before',
        'status_after',
        'note',
        'performed_by',
        'ip_address',
        'user_agent',
        'created_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function refundRequest(): BelongsTo
    {
        return $this->belongsTo(RefundRequest::class, 'refund_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
