<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumPost extends Model
{
    use SoftDeletes;
    protected $table = 'forum_post';
    protected $fillable = [
        'user_id', 'doctor_id', 'title', 'content', 'view_count', 'comment_count', 'like_count', 'status', 'category', 'is_pinned', 'pin_order'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }
}
