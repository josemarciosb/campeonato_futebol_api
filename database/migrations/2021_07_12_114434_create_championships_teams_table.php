<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionshipsTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('championships_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_championship');
            $table->unsignedBigInteger('id_team');
            $table->integer('points');
            $table->integer('goals');
            $table->integer('yellow_cards');
            $table->integer('red_cards');
            $table->timestamps();

            $table->foreign('id_championship')->references('id')->on('championships')->onDelete('CASCADE');
            $table->foreign('id_team')->references('id')->on('teams')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('championships_teams');
    }
}
