<?php

namespace Tests\Feature;

use App\Models\Contrat;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContratTest extends TestCase
{
    public function test_store()
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
            ->postJson('/api/contrat', [
                'produit_id' => 1,
                'numero_contrat' => Str::random(6),
                'date_effet' => '2000-02-20',
                'date_signature' => '2000-02-20',
                'statut' => 'CONTRAT',
                'sous_statut' => 'EN COURS',
                'iban' => '123456789',
            ])
            ->assertStatus(200);
    }

    public function test_update()
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
            ->postJson('/api/contrat', [
                'contrat_id' => Contrat::latest()->id,
                'numero_contrat' => Str::random(6),
                'date_effet' => '2000-02-20',
                'date_signature' => '2000-02-20',
            ])
            ->assertStatus(200);
    }
}
