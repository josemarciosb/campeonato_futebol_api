<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_player');
            $table->unsignedBigInteger('id_game');
            $table->unsignedBigInteger('id_championship');
            $table->string('time');
            $table->timestamps();

            $table->foreign('id_player')->references('id')->on('players')->onDelete('CASCADE');
            $table->foreign('id_game')->references('id')->on('games')->onDelete('CASCADE');
            $table->foreign('id_championship')->references('id')->on('championships')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
