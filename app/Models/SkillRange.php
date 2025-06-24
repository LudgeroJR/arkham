<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillRange extends Model
{
    /** @use HasFactory<\Database\Factories\SkillRangeFactory> */
    use HasFactory;
    protected $table = 'skill_range';
}
