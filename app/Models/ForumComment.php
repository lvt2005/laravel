<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumComment extends Model
{
    use SoftDeletes;
    protected $table = 'forum_comment';
    protected $fillable = [
        'post_id', 'user_id', 'doctor_id', 'parent_comment_id', 'content', 'like_count', 'is_answer', 'is_helpful'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo(ForumComment::class, 'parent_comment_id');
    }

    public function children()
    {
        return $this->hasMany(ForumComment::class, 'parent_comment_id');
    }
}
