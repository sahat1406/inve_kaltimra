<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HapusAsetTerpilihTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $kategori;
    protected $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $role = Role::create(['role' => 'admin']);
        $this->user = User::factory()->create(['role_id' => $role->id]);
        $this->kategori = Kategori::create(['kategori' => 'Elektronik']);
        $this->lokasi = Lokasi::create(['lokasi' => 'Gedung Utama']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function halaman_index_aset_menampilkan_checkbox_dan_tombol_hapus_terpilih()
    {
        $this->post('/asets', [
            'nm_aset' => 'Monitor LG',
            'deskripsi' => 'Monitor 24 inch',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('monitor.jpg'),
        ]);

        $aset = Aset::first();

        $response = $this->get('/asets');
        $response->assertStatus(200);
        $response->assertSee('id="select-all"', false);
        $response->assertSee('class="row-checkbox" value="' . $aset->id . '"', false);
        $response->assertSee('id="btn-hapus-terpilih"', false);
        $response->assertSeeText('Hapus Aset (0)');
        $response->assertSee('/asets/hapus-terpilih', false);
    }

    /** @test */
    public function user_dapat_menghapus_beberapa_aset_terpilih_sekaligus()
    {
        // Buat 2 aset yang berbagi gambar (qty=2)
        $this->post('/asets', [
            'nm_aset' => 'Kursi Rapat',
            'deskripsi' => 'Kursi',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 2,
            'gambar' => UploadedFile::fake()->image('kursi.jpg'),
        ]);

        // Buat 1 aset terpisah dengan gambar sendiri
        $this->post('/asets', [
            'nm_aset' => 'Proyektor',
            'deskripsi' => 'Proyektor ruang rapat',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 1,
            'gambar' => UploadedFile::fake()->image('proyektor.jpg'),
        ]);

        $kursi1 = Aset::where('nm_aset', 'Kursi Rapat 1')->first();
        $kursi2 = Aset::where('nm_aset', 'Kursi Rapat 2')->first();
        $proyektor = Aset::where('nm_aset', 'Proyektor')->first();

        // Hapus Kursi Rapat 1 dan Proyektor (Kursi Rapat 2 tidak dipilih)
        $response = $this->delete('/asets/hapus-terpilih', [
            'ids' => [$kursi1->id, $proyektor->id],
        ]);

        $response->assertRedirect('/asets');
        $response->assertSessionHas('success', 'Berhasil menghapus 2 data aset terpilih');

        $this->assertDatabaseMissing('asets', ['id' => $kursi1->id]);
        $this->assertDatabaseMissing('asets', ['id' => $proyektor->id]);
        $this->assertDatabaseHas('asets', ['id' => $kursi2->id]);

        // QR Code aset terhapus ikut hilang, QR Code Kursi 2 tetap ada
        Storage::disk('public')->assertMissing('qrcode/' . $kursi1->kd_aset . '.png');
        Storage::disk('public')->assertMissing('qrcode/' . $proyektor->kd_aset . '.png');
        Storage::disk('public')->assertExists('qrcode/' . $kursi2->kd_aset . '.png');

        // Gambar Proyektor terhapus, gambar Kursi masih ada karena dipakai Kursi 2
        Storage::disk('public')->assertMissing($proyektor->gambar);
        Storage::disk('public')->assertExists($kursi2->gambar);

        // Hapus sisa Kursi Rapat 2 -> sekarang gambar Kursi harus ikut terhapus
        $this->delete('/asets/hapus-terpilih', [
            'ids' => [$kursi2->id],
        ])->assertRedirect('/asets');

        Storage::disk('public')->assertMissing($kursi2->gambar);
    }

    /** @test */
    public function hapus_terpilih_gagal_jika_tidak_ada_aset_yang_dipilih()
    {
        $response = $this->from('/asets')->delete('/asets/hapus-terpilih', []);

        $response->assertRedirect('/asets');
        $response->assertSessionHasErrors('ids');
    }

    /** @test */
    public function hapus_terpilih_gagal_jika_terdapat_id_aset_tidak_valid()
    {
        $this->post('/asets', [
            'nm_aset' => 'Meja Kerja',
            'deskripsi' => 'Meja',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('meja.jpg'),
        ]);

        $aset = Aset::first();

        $response = $this->from('/asets')->delete('/asets/hapus-terpilih', [
            'ids' => [$aset->id, 999999],
        ]);

        $response->assertRedirect('/asets');
        $response->assertSessionHasErrors('ids.1');
        $this->assertDatabaseHas('asets', ['id' => $aset->id]);
    }
}
