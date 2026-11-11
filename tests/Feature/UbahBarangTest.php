<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UbahBarangTest extends TestCase
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
    public function user_dapat_mengubah_data_barang_dengan_input_valid()
    {
        Storage::fake('public');

        // Buat kategori dan lokasi
        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();

        // Simulasi gambar awal
        $gambarAwal = UploadedFile::fake()->image('awal.jpg');
        $gambarAwalPath = $gambarAwal->storeAs('gambar', 'awal.jpg', 'public');

        // Buat data barang awal
        $barang = Barang::create([
            'kd_barang' => 'BRG-123456',
            'nm_barang' => 'Laptop Lama',
            'deskripsi' => 'Deskripsi awal',
            'tgl_penambahan' => now(),
            'gambar' => $gambarAwalPath,
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
        ]);

        // File gambar baru untuk update
        $gambarBaru = UploadedFile::fake()->image('baru.jpg');

        // Data update
        $dataBaru = [
            'nm_barang' => 'Laptop Baru',
            'deskripsi' => 'Deskripsi sudah diperbarui',
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
            'gambar' => $gambarBaru,
        ];

        // Kirim PUT request
        $response = $this->put("/barang/{$barang->id}", $dataBaru);

        // Cek redirect sukses
        $response->assertRedirect('/barang');

        // Cek data di database
        $this->assertDatabaseHas('barangs', [
            'id' => $barang->id,
            'nm_barang' => 'Laptop Baru',
            'deskripsi' => 'Deskripsi sudah diperbarui',
        ]);

        // Ambil ulang data barang untuk cek path gambar baru
        $barangBaru = Barang::find($barang->id);
        Storage::disk('public')->assertExists($barangBaru->gambar);
    }
}
