<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkillsTable extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->foreignId('type_id')->constrained('types')->cascadeOnDelete();
            $table->integer('power');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['name', 'type_id']); // Sugestão: não repetir nome+tipo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
}