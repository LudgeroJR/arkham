<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokedex extends Model
{
    /** @use HasFactory<\Database\Factories\PokedexFactory> */
    use HasFactory;
    protected $table = 'pokedex';
}
