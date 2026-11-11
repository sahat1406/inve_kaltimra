<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class LokasiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user admin dan login
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($this->adminUser);
    }

    /** @test */
    public function user_can_show_lokasi_index()
    {
        $response = $this->get('/lokasi');

        $response->assertStatus(200);
        $response->assertViewIs('lokasi.index');
    }

    /** @test */
    public function user_can_store_lokasi()
    {
        $response = $this->post('/lokasi', [
            'lokasi' => 'Gudang A',
        ]);

        $response->assertRedirect('/lokasi');
        $this->assertDatabaseHas('lokasis', [
            'lokasi' => 'Gudang A',
        ]);
    }

    // test lainnya...
}
