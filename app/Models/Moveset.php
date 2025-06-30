<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moveset extends Model
{
    use HasFactory;

    public function pokemon()
    {
        // Certifique-se que a foreign key está correta (pokedex_id)
        return $this->belongsTo(Pokedex::class, 'pokedex_id');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }
}