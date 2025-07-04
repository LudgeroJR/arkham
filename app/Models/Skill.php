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
        return $this->hasMany(SkillRange::class, 'skill_id')->with('range');
    }
    
    // Pokémons que aprendem no moveset
    public function movesetPokemons()
    {
        // Corrija o nome da tabela para 'movesets' se for esse o nome correto
        return $this->belongsToMany(Pokedex::class, 'movesets', 'skill_id', 'pokedex_id');
    }

    // Pokémons que aprendem como eggmove
    public function eggmovePokemons()
    {
        // Corrija o nome da tabela para 'eggmoves'
        return $this->belongsToMany(Pokedex::class, 'eggmoves', 'skill_id', 'pokedex_id');
    }

    // Pokémons que aprendem como movetutor
    public function movetutorPokemons()
    {
        // Corrija o nome da tabela para 'movetutors'
        return $this->belongsToMany(Pokedex::class, 'movetutors', 'skill_id', 'pokedex_id');
    }

}