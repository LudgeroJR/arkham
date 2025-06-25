<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->string('name'); // Nome do jogo
            $table->string('nick'); // Nick do membro nesse jogo
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
}
