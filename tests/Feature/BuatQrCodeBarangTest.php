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

class BuatQrCodeBarangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan role admin tersedia di database test
        Role::create(['id' => 1, 'role' => 'admin']);
    }

    public function test_admin_dapat_menyimpan_barang_dan_qrcode_dibuat()
    {
        Storage::fake('public');

        // Buat kategori & lokasi
        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();

        // Buat user admin
        $admin = User::factory()->create([
            'role_id' => 1, // Cocokkan dengan ID role admin yang dibuat di atas
        ]);

        $this->actingAs($admin);

        $response = $this->post('/barang', [
            'nm_barang'     => 'Laptop Asus',
            'deskripsi'     => 'Laptop untuk keperluan kerja',
            'tgl_penambahan'=> now()->toDateString(),
            'kategori_id'   => $kategori->id,
            'lokasi_id'     => $lokasi->id,
            'gambar'        => UploadedFile::fake()->image('laptop.png'),
        ]);

        $response->assertRedirect('/barang');

        $barang = Barang::first();
        $this->assertNotNull($barang);

        // Pastikan QR code dibuat
        Storage::disk('public')->assertExists('qrcode/' . $barang->kd_barang . '.png');

        // Pastikan data barang ada di database
        $this->assertDatabaseHas('barangs', [
            'nm_barang' => 'Laptop Asus',
            'deskripsi' => 'Laptop untuk keperluan kerja',
        ]);
    }
}
