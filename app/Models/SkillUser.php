<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SkillUser extends Pivot
{
    use HasFactory;
    /**
     * @var bool
     */
    public $incrementing = true;

    protected $fillable = [
        'skill_id',
        'user_id'
    ];
}
