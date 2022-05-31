<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_save_team()
    {
        $data = array(
            'name' => 'Time 2',
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->post('/api/salvar-time', $data);

        $response->assertStatus(201);
    }

    public function test_show_teams()
    {
        $data = array(
            'cpf' => '0001',
            'cpf' => '0002',
            'num_shirt' => '9'
        );

        $user = User::factory()->make();

        $response = $this->actingAs($user, 'api')
        ->get('/api/exibir-times');

        $response->assertJsonFragment($data);
        $response->assertStatus(200);
    }
}
