<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function users()
    {
        return $this->belongsTo('users', 'user_id');
    }


    public function posts()
    {
        return $this->belongsTo('posts', 'post_id');
    }
}
