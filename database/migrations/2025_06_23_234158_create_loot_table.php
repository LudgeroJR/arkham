<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLootTable extends Migration
{
    public function up(): void
    {
        Schema::create('loot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokedex_id')->constrained('pokedex')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->integer('amount_min');
            $table->integer('amount_max')->nullable();
            $table->timestamps();
            $table->unique(['pokedex_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loot');
    }
}