<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\UserToken;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Map to existing singular table `user`
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
        'address',
        'avatar_url',
        'dob',
        'gender',
        'phone',
        'status',
        'type',
        'last_login_at',
        'login_count',
        'two_factor_enabled',
        'email_notification',
        'reply_notification',
        'failed_login_attempts',
        'locked_at',
        'locked_until',
        'last_failed_login_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'last_login_at' => 'datetime',
            'locked_at' => 'datetime',
            'locked_until' => 'datetime',
            'last_failed_login_at' => 'datetime',
        ];
    }

    // Relationship to user tokens (refresh/session tracking)
    public function tokens(): HasMany
    {
        return $this->hasMany(UserToken::class);
    }

    // Increment login count helper
    public function markLogin(): void
    {
        $this->login_count = ($this->login_count ?? 0) + 1;
        $this->last_login_at = now();
        $this->save();
    }
}
