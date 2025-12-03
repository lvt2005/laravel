<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    
    protected $fillable = [
        'user_id',
        'method',
        'method_name',
        'masked_detail',
        'card_number',
        'card_holder',
        'expiry_month',
        'expiry_year',
        'cvv',
        'wallet_number',
        'wallet_type',
        'bank_account',
        'bank_name',
    ];

    protected $hidden = [
        'card_number',
        'cvv',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(AppointmentSchedule::class, 'payment_method_id');
    }
}
