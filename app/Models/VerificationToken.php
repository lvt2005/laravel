<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationToken extends Model
{
    protected $table = 'verification_tokens';
    
    protected $fillable = [
        'user_id',
        'email',
        'token_hash',
        'verification_code',
        'token_type',
        'expires_at',
        'used_at',
        'is_active',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
