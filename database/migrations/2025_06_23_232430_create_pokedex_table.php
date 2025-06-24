<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokedexTable extends Migration
{
    public function up(): void
    {
        Schema::create('pokedex', function (Blueprint $table) {
            $table->id();
            $table->integer('dex');
            $table->string('name');
            $table->text('description');
            $table->string('thumb');
            $table->foreignId('primary_type_id')->constrained('types')->cascadeOnDelete();
            $table->foreignId('secondary_type_id')->nullable()->constrained('types')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['dex', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokedex');
    }
}