<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LihatLokasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role dan user admin
        $role = Role::create(['role' => 'admin']);

        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_melihat_daftar_lokasi_barang()
    {
        // Buat data lokasi dummy
        Lokasi::factory()->count(3)->create();

        // Akses halaman lokasi
        $response = $this->get('/lokasi');

        $response->assertStatus(200);
        $response->assertViewIs('lokasi.index');
        $response->assertViewHas('lokasis');
    }

    /** @test */
    public function halaman_lokasi_tampil_meski_lokasi_kosong()
    {
        $response = $this->get('/lokasi');

        $response->assertStatus(200);
        $response->assertViewIs('lokasi.index');
        $response->assertViewHas('lokasis', function ($lokasis) {
            return $lokasis->isEmpty();
        });
    }
}
