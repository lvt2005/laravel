<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumReport extends Model
{
    protected $table = 'forum_report';
    protected $fillable = [
        'user_id', 'post_id', 'comment_id', 'reason', 'detail', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }
    
    public function comment()
    {
        return $this->belongsTo(ForumComment::class, 'comment_id');
    }
}
