<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_team_home');
            $table->unsignedBigInteger('id_team_vis');
            $table->unsignedBigInteger('id_championship');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('goals_team_home');
            $table->integer('goals_team_vis');
            $table->integer('yellow_cards_home');
            $table->integer('yellow_cards_vis');
            $table->integer('red_cards_home');
            $table->integer('red_cards_vis');
            $table->timestamps();

            $table->foreign('id_team_home')->references('id')->on('teams')->onDelete('NO ACTION');
            $table->foreign('id_team_vis')->references('id')->on('teams')->onDelete('NO ACTION');
            $table->foreign('id_championship')->references('id')->on('championships')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
