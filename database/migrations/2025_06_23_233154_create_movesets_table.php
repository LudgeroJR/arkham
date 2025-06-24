<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovesetsTable extends Migration
{
    public function up(): void
    {
        Schema::create('movesets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokedex_id')->constrained('pokedex')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->integer('position');
            $table->integer('level');
            $table->timestamps();
            $table->unique(['pokedex_id', 'skill_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movesets');
    }
}