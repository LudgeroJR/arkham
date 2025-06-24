<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemQuestTable extends Migration
{
    public function up(): void
    {
        Schema::create('item_quest', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quest_id')->constrained('quests')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['quest_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_quest');
    }
}