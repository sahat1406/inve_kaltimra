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

class HapusBarangTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role dan user admin
        $role = Role::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);

        $this->actingAs($this->user);
    }

    /** @test */
    public function user_dapat_menghapus_data_barang_beserta_gambar_dan_qrcode()
    {
        Storage::fake('public');

        $kategori = Kategori::factory()->create();
        $lokasi = Lokasi::factory()->create();

        // Simulasi file gambar dan QR code
        $gambar = UploadedFile::fake()->image('barang.jpg')->storeAs('gambar', 'barang.jpg', 'public');
        $qrPath = 'qrcode/BRG-999999.png';
        Storage::disk('public')->put($qrPath, 'isi qr code palsu');

        // Buat barang dengan gambar dan QR code
        $barang = Barang::create([
            'kd_barang'     => 'BRG-999999',
            'nm_barang'     => 'Barang Uji Coba',
            'deskripsi'     => 'Deskripsi uji coba',
            'tgl_penambahan'=> now(),
            'gambar'        => $gambar,
            'kategori_id'   => $kategori->id,
            'lokasi_id'     => $lokasi->id,
        ]);

        // Jalankan request hapus
        $response = $this->delete("/barang/{$barang->id}");

        $response->assertRedirect(); // Redirect balik
        $this->assertDatabaseMissing('barangs', ['id' => $barang->id]);

        // Pastikan gambar dan QR code juga dihapus
        Storage::disk('public')->assertMissing($gambar);
        Storage::disk('public')->assertMissing($qrPath);
    }
}
