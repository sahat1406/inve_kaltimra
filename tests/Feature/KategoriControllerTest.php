<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Lokasi;
use Tests\TestCase;

class KategoriControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan storage fake untuk tes gambar
        Storage::fake('public');

        // Login sebagai user yang valid (admin / user)
        $user = User::factory()->create([
            'role' => 'admin' // jika kamu pakai kolom role biasa
        ]);
        $this->actingAs($user);
    }

    /** @test */
    public function admin_can_display_kategori_index_page()
    {
        Kategori::factory()->create();

        $response = $this->get('/kategori');

        $response->assertStatus(200);
        $response->assertViewIs('kategori.index');
        $response->assertViewHas('kategories');
    }

    /** @test */
    public function admin_can_display_create_kategori_form()
    {
        $response = $this->get('/kategori/create');

        $response->assertStatus(200);
        $response->assertViewIs('kategori.create');
    }

    /** @test */
    public function admin_can_store_a_new_kategori()
    {
        $response = $this->post('/kategori', [
            'kategori' => 'Elektronik'
        ]);

        $response->assertRedirect('/kategori');
        $this->assertDatabaseHas('kategoris', [
            'kategori' => 'Elektronik'
        ]);
    }

    /** @test */
    public function admin_validates_store_kategori_input()
    {
        $response = $this->post('/kategori', [
            'kategori' => ''
        ]);

        $response->assertSessionHasErrors('kategori');
    }

    /** @test */
    public function admin_can_update_an_existing_kategori()
    {
        $kategori = Kategori::factory()->create(['kategori' => 'Lama']);

        $response = $this->put("/kategori/{$kategori->id}", [
            'kategori' => 'Baru'
        ]);

        $response->assertRedirect('/kategori');
        $this->assertDatabaseHas('kategoris', [
            'id' => $kategori->id,
            'kategori' => 'Baru'
        ]);
    }

    /** @test */
    public function admin_can_delete_a_kategori()
    {
        $kategori = Kategori::factory()->create();

        $response = $this->delete("/kategori/{$kategori->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('kategoris', [
            'id' => $kategori->id
        ]);
    }
}
