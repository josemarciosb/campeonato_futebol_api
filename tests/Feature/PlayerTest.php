<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PlayerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_player()
    {
        $data = array(
            'cpf' => '0004',
            'name' => 'Davi',
            'team_name' => 'Time 2',
            'num_shirt' => '1'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/salvar-jogador', $data);

        $response->assertStatus(201);
    }

    public function test_edit_player()
    {
        $data = array(
            'id' => '1',
            'name' => 'David',
            'num_shirt' => '1'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->put('/api/editar-jogador', $data);

        $response->assertStatus(201);
    }


}
