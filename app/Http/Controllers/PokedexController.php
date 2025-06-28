<?php

namespace App\Http\Controllers;

use App\Models\Pokedex;

class PokedexController extends Controller
{
    public function index()
    {
        // Group all pokémons by dex number, order by dex then by id (or name)
        $pokemonsByDex = Pokedex::orderBy('dex')->orderBy('id')->get()->groupBy('dex');
        return view('psoul.pokedex', ['pokemonsByDex' => $pokemonsByDex]);
    }
}