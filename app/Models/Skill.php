<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $table = 'skills';

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }
    public function ranges()
    {
        return $this->hasMany(SkillRange::class, 'skill_id');
    }
}