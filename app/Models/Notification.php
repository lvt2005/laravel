<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'related_id', 'is_read', 'sent_via'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
