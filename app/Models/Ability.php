<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    public function pokemons()
    {
        return $this->belongsToMany(Pokedex::class, 'ability_pokedex')
            ->withPivot('hidden')
            ->withTimestamps();
    }
}