<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    protected $table = 'email_queue';
    
    public $timestamps = false;

    protected $fillable = [
        'recipient',
        'subject',
        'body',
        'options',
        'status',
        'attempts',
        'error_message',
        'scheduled_at',
        'sent_at',
        'created_at',
    ];

    protected $casts = [
        'options' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
