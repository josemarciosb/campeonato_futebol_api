<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Championship;
use App\Models\Game;
use App\Models\Team;
use DateTime;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function saveGame(Request $request)
    {
        $game = new Game();

        $teamHome = Team::where('name', $request->team_home)->first();

        if (empty($teamHome)) {
            return response()->json('Time mandante não encontrado', '400');
        } else {
            $game->id_team_home = $teamHome->id;
        }

        $teamVis = Team::where('name', $request->team_vis)->first();

        if (empty($teamVis)) {
            return response()->json('Time visitante não encontrado', '400');
        } else {
            if ($teamHome->id == $teamVis->id) {
                return response()->json('Time visitante não pode ser o mesmo que o time mandante', '400');
            } else {
                $game->id_team_vis = $teamVis->id;
            }
        }

        $championship = Championship::where('id', $request->id_championship)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        } else {
            $game->id_championship = $request->id_championship;
        }

        $inscriptionTeamHome = $championship->teams()->where('id_team', $teamHome->id)->first();
        $inscriptionTeamVis = $championship->teams()->where('id_team', $teamVis->id)->first();

        if(empty($inscriptionTeamHome))
        {
            return response()->json('Time mandante não inscrito no campeonato', '400');
        }

        if(empty($inscriptionTeamVis))
        {
            return response()->json('Time visitante não inscrito no campeonato', '400');
        }

        if (DateTime::createFromFormat('d/m/Y',  $request->date)) {
            $date = DateTime::createFromFormat('d/m/Y',  $request->date);
            $date =  $date->format('Y-m-d');
            $game->date = $date;
        } else {
            return response()->json('Formato inválido de data em date (formato válido: dd/mm/yyyy)', '400');
        }

        if (DateTime::createFromFormat('H:i',  $request->start_time)) {
            $start_time = DateTime::createFromFormat('H:i',  $request->start_time);
            $game->start_time = $start_time->format('H:i');
        } else {
            return response()->json('Formato inválido de hora em start_time (formato válido: HH:mm)', '400');
        }

        if (DateTime::createFromFormat('H:i',  $request->end_time)) {
            $end_time = DateTime::createFromFormat('H:i',  $request->end_time);

            $interval = (strtotime($end_time->format('H:i')) - strtotime($start_time->format('H:i'))) / 60;

            if ($interval > 60) {
                return response()->json('O tempo de jogo não pode ser maior que 60 minutos', '400');
            } else {
                $game->end_time = $end_time->format('H:i');
            }
        } else {
            return response()->json('Formato inválido de hora em end_time (formato válido: HH:mm)', '400');
        }

        if (!is_numeric($request->goals_team_home)) {
            return response()->json('Gols do time mandante não pode ser vazio', 400);
        } else {
            $game->goals_team_home = $request->goals_team_home;
        }

        if (!is_numeric($request->goals_team_vis)) {
            return response()->json('Gols do time visitante não pode ser vazio', 400);
        } else {
            $game->goals_team_vis = $request->goals_team_vis;
        }

        if (!is_numeric($request->yellow_cards_home)) {
            return response()->json('Número de cartões amarelos do time mandante não pode ser vazio', 400);
        } else {
            $game->yellow_cards_home = $request->yellow_cards_home;
        }

        if (!is_numeric($request->yellow_cards_vis)) {
            return response()->json('Número de cartões amarelos do time visitante não pode ser vazio', 400);
        } else {
            $game->yellow_cards_vis = $request->yellow_cards_vis;
        }

        if (!is_numeric($request->red_cards_home)) {
            return response()->json('Número de cartões vermelhos do time mandante não pode ser vazio', 400);
        } else {
            $game->red_cards_home = $request->red_cards_home;
        }

        if (!is_numeric($request->red_cards_vis)) {
            return response()->json('Número de cartões vermelhos do time visitante não pode ser vazio', 400);
        } else {
            $game->red_cards_vis = $request->red_cards_vis;
        }

        $game->save();

        if ($game->goals_team_home > $game->goals_team_vis) {
            $pointsHome = 3;
            $pointsVis = 0;

        } elseif ($game->goals_team_home < $game->goals_team_vis) {
            $pointsHome = 0;
            $pointsVis = 3;

        }  elseif ($game->goals_team_home == $game->goals_team_vis) {
            $pointsHome = 1;
            $pointsVis = 1;
        }

        $championship->teams()->updateExistingPivot($teamHome->id, [
            'points' => $pointsHome + $inscriptionTeamHome->pivot->points,
            'goals' => $game->goals_team_home + $inscriptionTeamHome->pivot->goals,
            'yellow_cards' => $game->yellow_cards_home + $inscriptionTeamHome->pivot->yellow_cards,
            'red_cards' => $game->red_cards_home + $inscriptionTeamHome->pivot->red_cards
        ]);


        $championship->teams()->updateExistingPivot($teamVis->id, [
            'points' => $pointsVis + $inscriptionTeamVis->pivot->points,
            'goals' => $game->goals_team_vis + $inscriptionTeamVis->pivot->goals,
            'yellow_cards' => $game->yellow_cards_vis + $inscriptionTeamVis->pivot->yellow_cards,
            'red_cards' => $game->red_cards_vis + $inscriptionTeamVis->pivot->red_cards
        ]);

        return response()->json('Jogo registrado com sucesso', '201');
    }

    public function editGame(Request $request){

        $game = Game::where('id', $request->id_game)->first();

        if(empty($game)){
            return response('Jogo não encontrado', '400');
        }

        $championship = Championship::where('id', $game->id_championship)->first();

        $teamHome = $championship->teams()->where('id_team', $game->id_team_home)->first();

        $teamVis = $championship->teams()->where('id_team', $game->id_team_vis)->first();

        if ($game->goals_team_home > $game->goals_team_vis) {
            $pointsHome = 3;
            $pointsVis = 0;

        } elseif ($game->goals_team_home < $game->goals_team_vis) {
            $pointsHome = 0;
            $pointsVis = 3;

        }  elseif ($game->goals_team_home == $game->goals_team_vis) {
            $pointsHome = 1;
            $pointsVis = 1;
        }

        if ($request->goals_team_home > $request->goals_team_vis) {
            $newPointsHome = 3 - $pointsHome;
            $newPointsVis = 0 - $pointsVis;

        } elseif ($request->goals_team_home < $request->goals_team_vis) {
            $newPointsHome = 0 - $pointsHome;
            $newPointsVis = 3 - $pointsVis;

        }  elseif ($request->goals_team_home == $request->goals_team_vis) {
            $newPointsHome = 1 - $pointsHome;
            $newPointsVis = 1 - $pointsVis;
        }

        $newGoalsHome = $request->goals_team_home - $game->goals_team_home;
        $newGoalsVis = $request->goals_team_vis - $game->goals_team_vis;
        $newYellowCardsHome = $request->yellow_cards_home - $game->yellow_cards_home;
        $newYellowCardsVis = $request->yellow_cards_vis - $game->yellow_cards_vis;
        $newRedCardsHome = $request->red_cards_home - $game->red_cards_home;
        $newRedCardsVis = $request->red_cards_vis - $game->red_cards_vis;

        $championship->teams()->updateExistingPivot($teamHome->id, [
            'points' =>  $teamHome->pivot->points + $newPointsHome,
            'goals' => $teamHome->pivot->goals + $newGoalsHome,
            'yellow_cards' => $teamHome->pivot->yellow_cards + $newYellowCardsHome,
            'red_cards' => $teamHome->pivot->red_cards + $newRedCardsHome
        ]);

        $championship->teams()->updateExistingPivot($teamVis->id, [
            'points' =>  $teamVis->pivot->points + $newPointsVis,
            'goals' => $teamVis->pivot->goals + $newGoalsVis,
            'yellow_cards' => $teamVis->pivot->yellow_cards + $newYellowCardsVis,
            'red_cards' => $teamVis->pivot->red_cards + $newRedCardsVis
        ]);

        if(!empty($request->date)){
            if (DateTime::createFromFormat('d/m/Y',  $request->date)) {
                $date = DateTime::createFromFormat('d/m/Y',  $request->date);
                $date =  $date->format('Y-m-d');
                $game->date = $date;
            } else {
                return response()->json('Formato inválido de data em date (formato válido: dd/mm/yyyy)', '400');
            }
        }

        if(!empty($request->start_time)){
            if (DateTime::createFromFormat('H:i',  $request->start_time)) {
                $start_time = DateTime::createFromFormat('H:i',  $request->start_time);
                $game->start_time = $start_time->format('H:i');
            } else {
                return response()->json('Formato inválido de hora em start_time (formato válido: HH:mm)', '400');
            }
        }

        if(!empty($request->end_time)){
            if (DateTime::createFromFormat('H:i',  $request->end_time)) {
                $end_time = DateTime::createFromFormat('H:i',  $request->end_time);

                $interval = (strtotime($end_time->format('H:i')) - strtotime($start_time->format('H:i'))) / 60;

                if ($interval > 60) {
                    return response()->json('O tempo de jogo não pode ser maior que 60 minutos', '400');
                } else {
                    $game->end_time = $end_time->format('H:i');
                }
            } else {
                return response()->json('Formato inválido de hora em end_time (formato válido: HH:mm)', '400');
            }
        }

        if (is_numeric($request->goals_team_home)) {
            $game->goals_team_home = $request->goals_team_home;
        }

        if (is_numeric($request->goals_team_vis)) {
            $game->goals_team_vis = $request->goals_team_vis;
        }

        if (is_numeric($request->yellow_cards_home)) {
            $game->yellow_cards_home = $request->yellow_cards_home;
        }

        if (is_numeric($request->yellow_cards_vis)) {
            $game->yellow_cards_vis = $request->yellow_cards_vis;
        }

        if (is_numeric($request->red_cards_home)) {
            $game->red_cards_home = $request->red_cards_home;
        }

        if (is_numeric($request->red_cards_vis)) {
            $game->red_cards_vis = $request->red_cards_vis;
        }

        $game->update();


        return response()->json('Jogo atualizado com sucesso', '201');
    }
}
