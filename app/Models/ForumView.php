<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumView extends Model
{
    protected $table = 'forum_view';
    protected $fillable = [
        'post_id', 'user_ip', 'user_id', 'user_agent'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }
}
