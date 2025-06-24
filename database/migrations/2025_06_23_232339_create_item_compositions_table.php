<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemCompositionsTable extends Migration
{
    public function up(): void
    {
        Schema::create('item_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('items')->cascadeOnDelete();
            $table->integer('amount');
            $table->timestamps();
            $table->unique(['item_id', 'material_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_compositions');
    }
}