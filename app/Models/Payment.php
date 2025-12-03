<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = 'payment';
    
    protected $fillable = [
        'order_id',
        'amount',
        'method',
        'status',
        'transaction_code',
        'qr_code_url',
        'qr_data',
        'bank_transaction_id',
        'transaction_time',
        'expires_at',
        'verified_at',
        'metadata',
        'is_refund_locked',
        'refund_status',
        'is_payment_locked',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'transaction_time' => 'datetime',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_refund_locked' => 'boolean',
        'is_payment_locked' => 'boolean',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class, 'payment_id');
    }

    public function refundRequests(): HasMany
    {
        return $this->hasMany(RefundRequest::class, 'payment_id');
    }
}
