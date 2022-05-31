<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Championship;
use App\Models\Game;
use App\Models\Goal;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoalController extends Controller
{
    public function saveGoal(Request $request)
    {
        $goal = new Goal();


        $player = Player::where('id', $request->id_player)->first();

        if (empty($player)) {
            return response()->json('Jogador não encontrado', '400');
        } else {
            $goal->id_player = $request->id_player;
        }



        $game = Game::where('id', $request->id_game)->first();

        if (empty($game)) {
            return response()->json('Jogo não encontrado', '400');
        } else {
            $goal->id_game = $request->id_game;
        }


        $championship = Championship::where('id', $request->id_championship)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        } else {
            $goal->id_championship = $request->id_championship;
        }


        if (empty($request->time)) {
            return response()->json('O gol deve ter um tempo no qual foi marcado', '400');
        } else {
            if (preg_match('/[0-5][0-9](:[0-5][0-9])/', $request->time)) {
                $goal->time = $request->time;
            } else {
                return response()->json('Tempo inválido (Tempo válido: 00:00 à 59:59)', '400');
            }
        }

        $goal->save();

        return response()->json('Gol registrado para o jogador ' . $player->name, '201');
    }

    public function showTopScorers($idChampionship)
    {

        $championship = Championship::where('id', $idChampionship)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        }

        $goals = Goal::select('id_player', DB::raw('COUNT(*) AS goals'))
            ->where('id_championship', $idChampionship)
            ->groupby('id_player')
            ->orderby('goals', 'desc')
            ->get();

        $scorers = [];

        foreach ($goals as $goal) {
            $player = Player::where('id', $goal->id_player)->first();

            $scorers[] = [
                'player' => $player->name,
                'goals' => $goal->goals
            ];
        }

        return response()->json($scorers, '200');
    }
}
