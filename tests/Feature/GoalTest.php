<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GoalTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_goal()
    {
        $data = array(
            'id_player' => '3',
            'id_game' => '1',
            'id_championship' => '1',
            'time' => '15:00'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/salvar-gol', $data);

        $response->assertStatus(201);
    }

    public function test_show_top_scorers()
    {
        $data = array(

        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->get('/api/exibir-artilheiros/1');

        $response->assertJsonFragment(['player' => 'Torres']);
        $response->assertStatus(200);
    }
}
