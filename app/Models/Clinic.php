<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clinic extends Model
{
    protected $table = 'clinic';
    
    protected $fillable = [
        'name',
        'address',
        'description',
        'email',
        'hotline',
        'opening_hours',
        'status',
    ];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'clinic_id');
    }
}
