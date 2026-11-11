<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Kategori;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HapusKategoriTest extends TestCase
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
    public function user_dapat_menghapus_data_kategori()
    {
        $kategori = Kategori::factory()->create([
            'kategori' => 'Akan Dihapus'
        ]);

        $response = $this->delete("/kategori/{$kategori->id}");

        $response->assertRedirect(); // atau ->assertRedirect('/kategori') jika diarahkan ke index
        $this->assertDatabaseMissing('kategoris', [
            'id' => $kategori->id,
        ]);
    }
}
