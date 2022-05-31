<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function saveTeam(Request $request)
    {

        $team = new Team();

        $verifyTeam = Team::where('name', $request->name)->first();

        if (!empty($verifyTeam)) {
            return response()->json('Time já cadastrado!' , '400');

        } else {

            $team->name = $request->name;
            $team->num_players = 0;

            $team->save();

            return response()->json('Time cadastrado com sucesso' , '201');
        }
    }

    public function showTeams()
    {
        $teams = Team::all();

        if (empty($teams)) {
            return response()->json('Nennhum time não encontrado', '400');
        }

        $listTeams = ['teams' => [] ];

        foreach ($teams as $team){
            $listTeams['teams'][$team->name] = [
            'num_players' => $team->num_players,

            ];

            $players = $team->players()->get();

            foreach ($players as $player){
                $listTeams['teams'][$team->name]['players'][$player->name] = [
                    'cpf' => $player->cpf,
                    'num_shirt' => $player->num_shirt
                ];
            }
        }

        return response()->json($listTeams, '200');

    }

    public function editTeam(Request $request)
    {

        $team = Team::where('id', $request->id)->first();

        $verifyTeam = Team::where('name', $request->name)->first();

        if (!empty($verifyTeam)) {
            return response()->json('Time já cadastrado!' , '400');

        } else {

            $team->name = $request->name;

            $team->update();

            return response()->json('Time atualizado com sucesso' , '201');
        }
    }


    public function deleteTeam($team)
    {
        $team = Team::where('id', $team)->first();

        if(empty($team)){
            return response()->json('Time não encontrado', '400');

        } else {
            $team->delete();

            return response()->json('Time deletado com sucesso' , '200');
        }
    }


}
