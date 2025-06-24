<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNpcBuysTable extends Migration
{
    public function up(): void
    {
        Schema::create('npc_buys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('npc_id')->constrained('npcs')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['npc_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('npc_buys');
    }
}