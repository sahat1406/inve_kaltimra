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

class TambahAsetMultiQtyTest extends TestCase
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
        $this->user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->kategori = Kategori::create(['kategori' => 'Elektronik & Furniture']);
        $this->lokasi = Lokasi::create(['lokasi' => 'Gedung Administrasi']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function tambah_aset_tunggal_dengan_qty_1()
    {
        $file = UploadedFile::fake()->image('laptop.jpg');

        $response = $this->post('/asets', [
            'nm_aset' => 'Laptop ASUS ROG',
            'deskripsi' => 'Laptop gaming untuk tim kreatif',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 1,
            'gambar' => $file,
            'tgl_penambahan' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/asets');
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('asets', 1);

        $aset = Aset::first();
        $this->assertEquals('Laptop ASUS ROG', $aset->nm_aset);
        $this->assertNotEmpty($aset->kd_aset);

        // Pastikan QR code dan gambar tersimpan
        Storage::disk('public')->assertExists('qrcode/' . $aset->kd_aset . '.png');
        Storage::disk('public')->assertExists($aset->gambar);
    }

    /** @test */
    public function tambah_aset_banyak_otomatis_membuat_aset_terpisah_dengan_nomor_dan_qr_unik()
    {
        $file = UploadedFile::fake()->image('meja.jpg');

        $qty = 5;
        $response = $this->post('/asets', [
            'nm_aset' => 'Meja Rapat',
            'deskripsi' => 'Meja rapat kayu jati',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => $qty,
            'gambar' => $file,
            'tgl_penambahan' => now()->format('Y-m-d'),
        ]);

        $response->assertRedirect('/asets');
        $response->assertSessionHas('success');

        // Harus ada 5 aset di database
        $this->assertDatabaseCount('asets', 5);

        $asets = Aset::orderBy('id', 'asc')->get();

        // Cek nama berurutan 1 sampai 5
        for ($i = 1; $i <= $qty; $i++) {
            $expectedName = "Meja Rapat {$i}";
            $this->assertEquals($expectedName, $asets[$i - 1]->nm_aset);

            // Cek QR Code setiap aset unik dan tersimpan di storage
            Storage::disk('public')->assertExists('qrcode/' . $asets[$i - 1]->kd_aset . '.png');
        }

        // Cek bahwa semua kode aset unik dan berurutan
        $kdAsetList = $asets->pluck('kd_aset')->toArray();
        $this->assertCount(5, array_unique($kdAsetList));
        $this->assertEquals([
            'AST-000001',
            'AST-000002',
            'AST-000003',
            'AST-000004',
            'AST-000005',
        ], $kdAsetList);

        // Cek bahwa semua aset menggunakan file gambar yang sama
        $gambarList = $asets->pluck('gambar')->toArray();
        $this->assertCount(1, array_unique($gambarList));
        Storage::disk('public')->assertExists($gambarList[0]);
    }

    /** @test */
    public function penambahan_aset_berikutnya_melanjutkan_nomor_urut()
    {
        $file = UploadedFile::fake()->image('kursi.jpg');

        // Batch 1: buat 3 kursi -> Kursi 1, Kursi 2, Kursi 3
        $this->post('/asets', [
            'nm_aset' => 'Kursi Kerja',
            'deskripsi' => 'Kursi kantor ergonomis',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 3,
            'gambar' => $file,
        ]);

        $this->assertDatabaseCount('asets', 3);

        // Batch 2: buat lagi 2 kursi -> harus Kursi Kerja 4, Kursi Kerja 5
        $file2 = UploadedFile::fake()->image('kursi2.jpg');
        $this->post('/asets', [
            'nm_aset' => 'Kursi Kerja',
            'deskripsi' => 'Kursi kantor ergonomis batch 2',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 2,
            'gambar' => $file2,
        ]);

        $this->assertDatabaseCount('asets', 5);

        $names = Aset::orderBy('id', 'asc')->pluck('nm_aset')->toArray();
        $this->assertEquals([
            'Kursi Kerja 1',
            'Kursi Kerja 2',
            'Kursi Kerja 3',
            'Kursi Kerja 4',
            'Kursi Kerja 5',
        ], $names);
    }

    /** @test */
    public function menghapus_satu_aset_tidak_menghapus_gambar_yang_dipakai_aset_lain()
    {
        $file = UploadedFile::fake()->image('meja.jpg');

        $this->post('/asets', [
            'nm_aset' => 'Meja',
            'deskripsi' => 'Meja',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 2,
            'gambar' => $file,
        ]);

        $meja1 = Aset::where('nm_aset', 'Meja 1')->first();
        $meja2 = Aset::where('nm_aset', 'Meja 2')->first();
        $gambarPath = $meja1->gambar;

        Storage::disk('public')->assertExists($gambarPath);
        Storage::disk('public')->assertExists('qrcode/' . $meja1->kd_aset . '.png');

        // Hapus Meja 1
        $this->delete('/asets/' . $meja1->id);

        // QR Code Meja 1 harus terhapus
        Storage::disk('public')->assertMissing('qrcode/' . $meja1->kd_aset . '.png');

        // Gambar fisik MASIH HARUS ADA karena masih dipakai oleh Meja 2
        Storage::disk('public')->assertExists($gambarPath);

        // Hapus Meja 2 (aset terakhir yang memakai gambar tersebut)
        $this->delete('/asets/' . $meja2->id);

        // Sekarang gambar baru benar-benar terhapus
        Storage::disk('public')->assertMissing($gambarPath);
    }

    /** @test */
    public function dapat_mengakses_halaman_cetak_qr_dan_cetak_semua_qr()
    {
        $file = UploadedFile::fake()->image('komputer.jpg');

        $this->post('/asets', [
            'nm_aset' => 'PC All-in-One',
            'deskripsi' => 'PC untuk administrasi',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 2,
            'gambar' => $file,
        ]);

        $aset = Aset::first();

        // Tes cetak QR satu aset
        $responseSingle = $this->get('/asets/' . $aset->id . '/cetak-qr');
        $responseSingle->assertStatus(200);
        $responseSingle->assertSee($aset->kd_aset);
        $responseSingle->assertSee($aset->nm_aset);

        // Tes cetak semua QR
        $responseAll = $this->get('/asets/cetak-semua-qr');
        $responseAll->assertStatus(200);
        $responseAll->assertSee($aset->kd_aset);
    }

    /** @test */
    public function kode_aset_berurutan_dan_melewati_kode_yang_sudah_ada()
    {
        // Simulasikan sudah ada aset dengan kode AST-000002 pada id 1
        Aset::create([
            'kd_aset' => 'AST-000002',
            'nm_aset' => 'Aset Lama',
            'deskripsi' => 'Aset eksisting',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'gambar' => 'gambar/lama.jpg',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        // Penambahan berikutnya (id 2 -> bentrok AST-000002 -> lompat ke AST-000003)
        $this->post('/asets', [
            'nm_aset' => 'Scanner',
            'deskripsi' => 'Scanner dokumen',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'qty' => 2,
            'gambar' => UploadedFile::fake()->image('scanner.jpg'),
        ])->assertRedirect('/asets');

        $newCodes = Aset::where('nm_aset', 'LIKE', 'Scanner%')
            ->orderBy('id', 'asc')
            ->pluck('kd_aset')
            ->toArray();

        $this->assertEquals(['AST-000003', 'AST-000004'], $newCodes);
        Storage::disk('public')->assertExists('qrcode/AST-000003.png');
        Storage::disk('public')->assertExists('qrcode/AST-000004.png');
    }
}
