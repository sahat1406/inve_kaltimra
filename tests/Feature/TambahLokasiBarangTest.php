<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TambahLokasiBarangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role admin dan login sebagai admin
        $role = Role::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'role_id' => $role->id
        ]);
        $this->actingAs($user);
    }

    /** @test */
    public function user_dapat_menambahkan_lokasi_baru_dengan_data_valid()
    {
        $response = $this->post('/lokasi', [
            'lokasi' => 'Gudang 1'
        ]);

        $response->assertRedirect('/lokasi');
        $response->assertSessionHas('success', 'Berhasil menambahkan lokasi baru');
        $this->assertDatabaseHas('lokasis', [
            'lokasi' => 'Gudang 1'
        ]);
    }

    /** @test */
    public function tambah_lokasi_gagal_jika_lokasi_kosong()
    {
        $response = $this->post('/lokasi', [
            'lokasi' => ''
        ]);

        $response->assertSessionHasErrors('lokasi');
        $this->assertDatabaseMissing('lokasis', [
            'lokasi' => ''
        ]);
    }

    /** @test */
    public function tambah_lokasi_gagal_jika_lokasi_sudah_ada()
    {
        Lokasi::factory()->create(['lokasi' => 'Gudang 2']);

        $response = $this->post('/lokasi', [
            'lokasi' => 'Gudang 2'
        ]);

        $response->assertSessionHasErrors('lokasi');
        $this->assertCount(1, Lokasi::where('lokasi', 'Gudang 2')->get());
    }
}
