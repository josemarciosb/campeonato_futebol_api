<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ChampionshipTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_championship()
    {
        $data = array(
            'name' => 'Torneio teste',
            'start_date' => '10/07/2021',
            'end_date' => '20/07/2021'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/salvar-campeonato', $data);

        $response->assertStatus(201);
    }

    public function test_inscribe_team()
    {
        $data = array(
            'team_name' => 'Time 2'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/inscrever-time-campeonato/1', $data);

        $response->assertStatus(201);
    }

    public function test_show_classification()
    {
        $data = array(

        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/exibir-classificacao/1', $data);

        $response->assertJsonFragment(['goals' => 0]);
        $response->assertStatus(200);
    }
}
