<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = [
        'likes_count',
        'comments_count'
    ];


    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
