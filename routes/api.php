<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChampionshipController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\GoalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



#----USER

#Teste
//Route::get('users', [UserController::class, 'showUsers'])->name('show.user');

#Method
Route::post('salvar-usuario', [UserController::class, 'saveUser'])->name('user.save');
Route::post('autenticar-usuario', [AuthController::class, 'login'])->name('auth.login');


Route::middleware(['auth.api:api'])->group(function () {

#----TEAM
    Route::post('/salvar-time', [TeamController::class, 'saveTeam'])->name('team.save');
    Route::get('/exibir-times', [TeamController::class, 'showTeams'])->name('team.show');
    Route::put('/editar-time', [TeamController::class, 'editTeam'])->name('team.edit');
    Route::delete('/deletar-time/{team}', [TeamController::class, 'deleteTeam'])->name('team.delete');

#----PLAYER
    Route::post('/salvar-jogador', [PlayerController::class, 'savePlayer'])->name('player.save');
    Route::put('/editar-jogador', [PlayerController::class, 'editPlayer'])->name('player.edit');
    Route::get('/exibir-jogadores', [PlayerController::class, 'showPlayers'])->name('player.show');

#----CHAMPIONSHIP
    route::post('/salvar-campeonato', [ChampionshipController::class, 'saveChampionship'])->name('championship.save');
    route::put('/editar-campeonato', [ChampionshipController::class, 'editChampionship'])->name('championship.edit');
    route::post('/inscrever-time-campeonato/{championship}', [ChampionshipController::class, 'inscribeInChampionship'])->name('championship.inscribe');
    route::post('/exibir-classificacao/{championship}', [ChampionshipController::class, 'showRanking'])->name('championship.showRanking');
#----GAME
    route::post('/salvar-jogo', [GameController::class, 'saveGame'])->name('game.save');
    route::put('/editar-jogo', [GameController::class, 'editGame'])->name('game.edit');

#----GOAL
    route::post('/salvar-gol', [GoalController::class, 'saveGoal'])->name('goal.save');
    route::get('/exibir-artilheiros/{championship}',[GoalController::class, 'showTopScorers'])->name('goal.topScorers');

});
