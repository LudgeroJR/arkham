<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = 'types';

    public function primaryForPokemons()
    {
        return $this->hasMany(Pokedex::class, 'primary_type_id');
    }

    public function secondaryForPokemons()
    {
        return $this->hasMany(Pokedex::class, 'secondary_type_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'type_id');
    }
}