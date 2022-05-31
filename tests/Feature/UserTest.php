<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{


    public function test_register_user()
    {
        $credentials = array(
            'name' => 'teste',
            'email' => 'teste@teste.com',
            'password' => '123',
            'confirm_password' => '123'
        );


        $response = $this->call('POST', '/api/salvar-usuario', $credentials);
        $response->assertStatus(201, $response->getStatusCode());

    }

    public function test_login_user()
    {
        $credentials = array(
            'email' => 'teste@teste.com',
            'password' => '123'
        );


        $response = $this->call('POST', '/api/autenticar-usuario', $credentials);
        $response->assertStatus(200, $response->getStatusCode());

    }
}
