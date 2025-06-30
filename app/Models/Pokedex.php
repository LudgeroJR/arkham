<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokedex extends Model
{
    use HasFactory;
    protected $table = 'pokedex';

    // Tipagens
    public function primaryType() {
        return $this->belongsTo(Type::class, 'primary_type_id');
    }
    public function secondaryType() {
        return $this->belongsTo(Type::class, 'secondary_type_id');
    }

    // Abilities (pivô ability_pokedex, inclui info de hidden)
    public function abilities() {
        return $this->belongsToMany(Ability::class, 'ability_pokedex')
            ->withPivot('hidden')
            ->withTimestamps();
    }

    // Moveset (ordenado por position)
    public function moveset() {
        // Certifique-se que a foreign key está correta (pokedex_id)
        return $this->hasMany(Moveset::class, 'pokedex_id')->orderBy('position');
    }

    // Eggmoves
    public function eggmoves() {
        return $this->hasMany(Eggmove::class, 'pokedex_id');
    }

    // Movetutors
    public function movetutors() {
        return $this->hasMany(Movetutor::class, 'pokedex_id');
    }

    // Loot
    public function loot() {
        return $this->hasMany(Loot::class, 'pokedex_id');
    }
}