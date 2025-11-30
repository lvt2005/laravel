<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Specialization extends Model
{
    protected $table = 'specialization';
    
    protected $fillable = [
        'name',
        'description',
        'image_url',
    ];

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'specialization_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(TreatmentService::class, 'specialization_id');
    }
}
