<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEggmovesTable extends Migration
{
    public function up(): void
    {
        Schema::create('eggmoves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokedex_id')->constrained('pokedex')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['pokedex_id', 'skill_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eggmoves');
    }
}