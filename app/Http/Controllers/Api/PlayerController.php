<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function savePlayer(Request $request)
    {

        $player = new Player();

        $verifyPlayer = Player::where('cpf', $request->cpf)->first();

        $selectedTeam = Team::where('name', $request->team_name)->first();

        if (empty($selectedTeam)) {
            return response()->json('Time não encontrado', '400');
        }


        $verifyNumShirt = Player::where([['id_team', $selectedTeam->id], ['num_shirt', $request->num_shirt]])->first();

        if ($selectedTeam->num_players >= 5) {
            return response()->json('Time já atingiu o limite máximo de jogadores', '400');
        }

        if (!empty($verifyPlayer)) {
            return response()->json('Jogador já cadastrado!', '400');
        } else {

            $player->cpf = $request->cpf;
            $player->name = $request->name;
            $player->id_team = $selectedTeam->id;

            if (!empty($verifyNumShirt)) {
                return response()->json('Número de camisa já está em uso', '400');
            } else {

                $player->num_shirt = $request->num_shirt;

                $player->save();

                $selectedTeam->num_players = $selectedTeam->num_players + 1;

                $selectedTeam->save();

                return response()->json('Jogador cadastrado com sucesso!', '201');
            }
        }
    }

    public function editPlayer(Request $request)
    {
        $player = Player::where('id', $request->id)->first();


        if (!empty($request->name)) {
            $player->name = $request->name;
        }

        if (!empty($request->num_shirt)) {

            $verifyNumShirt = Player::where([['id_team', $player->id_team], ['num_shirt', $request->num_shirt]])->first();

            if (!empty($verifyNumShirt) and $player->cpf != $verifyNumShirt->cpf) {
                return response()->json('Número de camisa já está em uso', '400');
            } else {
                $player->num_shirt = $request->num_shirt;
            }
        }

        $player->update();

        return response()->json('Jogador atualizado com sucesso', '201');
    }

    public function showPlayers()
    {
        $players = Player::all()->groupBy('id_team');

        return response()->json($players, '200');
    }
}
