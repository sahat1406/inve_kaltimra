<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UbahLokasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role dan user admin
        $role = Role::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);

        // Login sebagai admin
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_mengubah_data_lokasi_dengan_input_valid()
    {
        // Buat data lokasi awal
        $lokasi = Lokasi::create([
            'lokasi' => 'Gudang Lama'
        ]);

        // Data update
        $dataBaru = [
            'lokasi' => 'Gudang Baru'
        ];

        // Kirim PUT request ke route update
        $response = $this->put("/lokasi/{$lokasi->id}", $dataBaru);

        // Assert redirect ke /lokasi
        $response->assertRedirect('/lokasi');

        // Pastikan data di database diperbarui
        $this->assertDatabaseHas('lokasis', [
            'id' => $lokasi->id,
            'lokasi' => 'Gudang Baru'
        ]);
    }
}
