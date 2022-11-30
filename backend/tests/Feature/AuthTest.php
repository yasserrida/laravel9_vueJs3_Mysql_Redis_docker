<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_login()
    {
        $this->postJson('/api/login', [
            'email' => 'admin@yasser.com',
            'password' => '123456',
        ])
            ->assertStatus(200);
    }

    public function test_logout()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'admin@yasser.com',
            'password' => '123456',
        ]);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $response->decodeResponseJson()['access_token'],
        ])
            ->postJson('/api/logout')
            ->assertStatus(200);
    }
}
