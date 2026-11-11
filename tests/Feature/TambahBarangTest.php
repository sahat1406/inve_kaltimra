<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TambahBarangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Buat role dan user admin
        $role = Role::create(['role' => 'admin']);

        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_menambah_barang_baru_dengan_data_valid()
    {
        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();
        $file = UploadedFile::fake()->image('barang.jpg');

        $response = $this->post('/barang', [
            'nm_barang'       => 'Laptop Lenovo',
            'deskripsi'       => 'Laptop untuk keperluan kerja',
            'gambar'          => $file,
            'kategori_id'     => $kategori->id,
            'lokasi_id'       => $lokasi->id,
            'tgl_penambahan'  => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/barang');
        $response->assertSessionHas('success', 'Berhasil menambahkan data barang baru');

        $this->assertDatabaseHas('barangs', [
            'nm_barang'   => 'Laptop Lenovo',
            'deskripsi'   => 'Laptop untuk keperluan kerja',
            'kategori_id' => $kategori->id,
            'lokasi_id'   => $lokasi->id,
        ]);

        // Pastikan gambar tersimpan
        Storage::disk('public')->assertExists('gambar/' . $file->hashName());

        $barang = Barang::latest()->first();

        // Pastikan QR code tersimpan jika pakai QR code
        Storage::disk('public')->assertExists('qrcode/' . $barang->kd_barang . '.png');
    }

    /** @test */
    public function tambah_barang_gagal_jika_input_tidak_valid()
    {
        $response = $this->post('/barang', []); // Kirim form kosong

        $response->assertSessionHasErrors([
            'nm_barang', 'deskripsi', 'gambar', 'kategori_id', 'lokasi_id'
        ]);
    }
}
