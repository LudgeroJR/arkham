<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillRangeTable extends Migration
{
    public function up(): void
    {
        Schema::create('skill_range', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->foreignId('range_id')->constrained('ranges')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['skill_id', 'range_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_range');
    }
}