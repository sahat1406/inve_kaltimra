<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HapusLokasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $role = Role::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_menghapus_lokasi()
    {
        $lokasi = Lokasi::factory()->create([
            'lokasi' => 'Gudang Lama',
        ]);

        $response = $this->delete("/lokasi/{$lokasi->id}");

        $response->assertRedirect('/lokasi');
        $this->assertDatabaseMissing('lokasis', [
            'id' => $lokasi->id,
        ]);
    }

}
