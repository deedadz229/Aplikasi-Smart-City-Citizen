<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (! extension_loaded('pdo_sqlite')) {
            $this->markTestSkipped('Ekstensi pdo_sqlite diperlukan untuk test database in-memory.');
        }

        parent::setUp();
    }

    public function test_petugas_crud_api_flow(): void
    {
        $payload = [
            'nama' => 'Budi Santoso',
            'nip' => '199402122023011002',
            'jabatan' => 'Komandan Regu Satpol PP',
            'no_hp' => '081234567890',
        ];

        // 1. Test POST (Create)
        $createResponse = $this->postJson('/api/petugas', $payload)
            ->assertCreated()
            ->assertJsonPath('data.nama', 'Budi Santoso');

        $id = $createResponse->json('data.id');

        // 2. Test GET (Index)
        $this->getJson('/api/petugas')
            ->assertOk()
            ->assertJsonPath('data.0.nip', '199402122023011002');

        // 3. Test GET Detail (Show)
        $this->getJson("/api/petugas/{$id}")
            ->assertOk()
            ->assertJsonPath('data.no_hp', '081234567890');

        // 4. Test PUT (Update)
        $this->putJson("/api/petugas/{$id}", [
            'nama' => 'Budi Santoso Update',
            'nip' => '199402122023011002',
            'jabatan' => 'Kepala Bidang Ketertiban Umum',
            'no_hp' => '081234567891',
        ])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Budi Santoso Update')
            ->assertJsonPath('data.jabatan', 'Kepala Bidang Ketertiban Umum');

        // 5. Test DELETE (Destroy)
        $this->deleteJson("/api/petugas/{$id}")
            ->assertOk();

        // 6. Test GET Detail after delete (Should be 404)
        $this->getJson("/api/petugas/{$id}")
            ->assertNotFound();
    }

    public function test_petugas_api_validation_returns_422(): void
    {
        $this->postJson('/api/petugas', [
            'nama' => '',
            'nip' => '',
            'jabatan' => '',
            'no_hp' => 'nomor tidak valid',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['nama', 'nip', 'jabatan', 'no_hp']);
    }
}
