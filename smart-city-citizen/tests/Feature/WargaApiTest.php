<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WargaApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite diperlukan untuk test database in-memory.');
        }

        parent::setUp();
    }

    public function test_warga_crud_api_flow(): void
    {
        $payload = [
            'nama' => 'Budi Santoso',
            'nik' => '3276010101010001',
            'alamat' => 'Jl. Merdeka No. 10',
            'no_hp' => '081234567890',
        ];

        $createResponse = $this->postJson('/api/warga', $payload)
            ->assertCreated()
            ->assertJsonPath('data.nama', 'Budi Santoso');

        $id = $createResponse->json('data.id');

        $this->getJson('/api/warga')
            ->assertOk()
            ->assertJsonPath('data.0.nik', '3276010101010001');

        $this->getJson("/api/warga/{$id}")
            ->assertOk()
            ->assertJsonPath('data.no_hp', '081234567890');

        $this->putJson("/api/warga/{$id}", [
            'nama' => 'Budi Santoso Update',
            'nik' => '3276010101010001',
            'alamat' => 'Jl. Merdeka No. 11',
            'no_hp' => '081234567891',
        ])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Budi Santoso Update');

        $this->deleteJson("/api/warga/{$id}")
            ->assertOk();

        $this->getJson("/api/warga/{$id}")
            ->assertNotFound();
    }

    public function test_warga_api_validation_returns_422(): void
    {
        $this->postJson('/api/warga', [
            'nama' => '',
            'nik' => '123',
            'alamat' => '',
            'no_hp' => 'nomor tidak valid',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nama', 'nik', 'alamat', 'no_hp']);
    }
}
