<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Championship;
use App\Models\Team;
use DateTime;
use Illuminate\Http\Request;

class ChampionshipController extends Controller
{
    public function saveChampionship(Request $request)
    {
        $championship = new Championship();

        $championship->name = $request->name;
        $championship->status = "Inscrições Abertas";

        $splitStartDate = explode('/', $request->start);


        if (DateTime::createFromFormat('d/m/Y',  $request->start_date)) {
            $start_date = DateTime::createFromFormat('d/m/Y',  $request->start_date);
            $start_date =  $start_date->format('Y-m-d');
            $championship->start_date = $start_date;
        } else {
            return response()->json('Formato inválido de data em start_date (formato válido: dd/mm/yyyy)', '400');
        }

        if (DateTime::createFromFormat('d/m/Y',  $request->end_date)) {
            $end_date = DateTime::createFromFormat('d/m/Y',  $request->end_date);
            $end_date =  $end_date->format('Y-m-d');
            $championship->end_date = $end_date;
        } else {
            return response()->json('Formato inválido de data em end_date (formato válido: dd/mm/yyyy)', '400');
        }

        $championship->save();

        return response()->json('Campeonato cadastrado com sucesso!', '201');
    }

    public function editChampionship(Request $request)
    {
        $championship = Championship::where('id', $request->id)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        }

        if (!empty($request->status)) {

            if ($request->status != 'Inscrições abertas' and $request->status != 'Em andamento' and $request->status != 'Finalizado') {
                return response()->json('Status inválido! (status permitidos: "Inscrições abertas", "Em andamento" ou "Finalizado") ', '400');
            } else {
                $championship->status = $request->status;
            }
        }

        $championship->update();

        return response()->json('Campeonato atualizado com sucesso', '201');
    }

    public function inscribeInChampionship(Request $request, $idChampionship)
    {

        $championship = Championship::where('id', $idChampionship)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        }

        $team = Team::where('name', $request->team_name)->first();

        if (empty($team)) {
            return response()->json('Time inválido', '400');
        }

        if (!empty($championship->teams()->where('id_team', $team->id)->first())) {
            return response()->json('Time já inscrito no campeonato', '400');
        }

        $championship->teams()->attach($team->id, [
            'points' => 0,
            'goals' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0
        ]);

        return response()->json('Inscrição realizada no campeonato ' . $championship->name, '201');
    }

    public function showRanking(Request $request, $idChampionship)
    {
        $championship = Championship::where('id', $idChampionship)->first();

        if (empty($championship)) {
            return response()->json('Campeonato não encontrado', '400');
        }

        $teams = $championship->teams()->get();

        $arrRanking = [];

        foreach ($teams as $team) {
            $cardPoints = ($team->pivot->yellow_cards * 1) + ($team->pivot->red_cards * 2);

            $arrRanking[$team->name] = [
                'points' => $team->pivot->points,
                'goals' => $team->pivot->goals,
                'yellow_cards' => $team->pivot->yellow_cards,
                'red_cards' => $team->pivot->red_cards,
                'card_points' => $cardPoints
            ];

        }

        $ranking = collect($arrRanking)->sortBy('card_points')->sortByDesc('points');

        if ($request->filter == '1') {
            $ranking = collect($arrRanking)->sortByDesc('goals');
        } elseif ($request->filter == '2') {
            $ranking = collect($arrRanking)->sortByDesc('yellow_cards');
        } elseif ($request->filter == '3') {
            $ranking = collect($arrRanking)->sortByDesc('red_cards');
        } elseif ($request-> filter == '4') {
            $ranking = collect($arrRanking)->sortByDesc('card_points');
        }

        return response()->json($ranking, '200');
    }
}
