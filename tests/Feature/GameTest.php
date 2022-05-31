<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_game()
    {
        $data = array(
            'team_home' => 'Time 1',
            'team_vis' => 'Time 2',
            'id_championship' => '1',
            'date' => '16/07/2021',
            'start_time' => '15:20',
            'end_time' => '15:40',
            'goals_team_home' => '2',
            'goals_team_vis' => '0',
            'yellow_cards_home' => '1',
            'yellow_cards_vis' => '0',
            'red_cards_home' => '0',
            'red_cards_vis' => '1'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/salvar-jogo', $data);

        $response->assertStatus(201);
    }

    public function test_edit_game()
    {
        $data = array(
            'id_game' => '1',
            'date' => '16/07/2021',
            'start_time' => '15:20',
            'end_time' => '15:40',
            'goals_team_home' => '2',
            'goals_team_vis' => '0',
            'yellow_cards_home' => '1',
            'yellow_cards_vis' => '0',
            'red_cards_home' => '0',
            'red_cards_vis' => '1'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->put('/api/editar-jogo', $data);

        $response->assertStatus(201);
    }
}
