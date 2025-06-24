<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbilityPokedexTable extends Migration
{
    public function up(): void
    {
        Schema::create('ability_pokedex', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokedex_id')->constrained('pokedex')->cascadeOnDelete();
            $table->foreignId('ability_id')->constrained('abilities')->cascadeOnDelete();
            $table->boolean('hidden')->default(false); // coluna para hidden ability
            $table->timestamps();
            $table->unique(['pokedex_id', 'ability_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ability_pokedex');
    }
}