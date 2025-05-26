<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;

class Mention extends Model
{
    protected $fillable = [
        'comment_id',
        'mentioned_user_id',
        'mentioner_user_id'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }

    public function mentioner()
    {
        return $this->belongsTo(User::class, 'mentioner_user_id');
    }
}
