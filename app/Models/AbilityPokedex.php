<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbilityPokedex extends Model
{
    use HasFactory;
    protected $table = 'ability_pokedex';

    public function pokedex()
    {
        return $this->belongsTo(Pokedex::class, 'pokedex_id');
    }

    public function ability()
    {
        return $this->belongsTo(Ability::class, 'ability_id');
    }
}