<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Kategori;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LihatKategoriTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $role = Role::create(['role' => 'admin']);

        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_melihat_daftar_kategori()
    {
        // Buat data kategori
        Kategori::create(['kategori' => 'Elektronik']);
        Kategori::create(['kategori' => 'Furniture']);

        // Kunjungi halaman index kategori
        $response = $this->get('/kategori');

        $response->assertStatus(200);
        $response->assertSee('Elektronik');
        $response->assertSee('Furniture');
    }
}
