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

class TanggalPengakuanAwalTest extends TestCase
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

        $this->kategori = Kategori::create(['kategori' => 'Elektronik']);
        $this->lokasi = Lokasi::create(['lokasi' => 'Ruang Server']);

        $this->actingAs($this->user);
    }

    /** @test */
    public function form_tambah_dan_edit_membatasi_input_tgl_aku_maksimal_hari_ini()
    {
        $today = now()->format('Y-m-d');

        $createResponse = $this->get('/asets/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('id="tgl_aku" max="' . $today . '"', false);

        $aset = Aset::create([
            'kd_aset' => 'AST-000001',
            'nm_aset' => 'Switch Core',
            'deskripsi' => 'Switch jaringan utama',
            'tgl_penambahan' => $today,
            'tgl_aku' => now()->subDays(5)->format('Y-m-d'),
            'gambar' => 'gambar/default.jpg',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $editResponse = $this->get('/asets/' . $aset->id . '/edit');
        $editResponse->assertStatus(200);
        $editResponse->assertSee('id="tgl_aku" max="' . $today . '"', false);
    }

    /** @test */
    public function tambah_aset_gagal_jika_tgl_aku_di_masa_depan()
    {
        $futureDate = now()->addDay()->format('Y-m-d');

        $response = $this->post('/asets', [
            'nm_aset' => 'Router Baru',
            'deskripsi' => 'Router cadangan',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('router.jpg'),
            'tgl_aku' => $futureDate,
        ]);

        $response->assertSessionHasErrors('tgl_aku');
        $this->assertDatabaseCount('asets', 0);
    }

    /** @test */
    public function tambah_aset_gagal_jika_format_tgl_aku_tidak_valid()
    {
        $response = $this->post('/asets', [
            'nm_aset' => 'Router Baru',
            'deskripsi' => 'Router cadangan',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('router.jpg'),
            'tgl_aku' => 'bukan-tanggal',
        ]);

        $response->assertSessionHasErrors('tgl_aku');
        $this->assertDatabaseCount('asets', 0);
    }

    /** @test */
    public function tambah_aset_berhasil_dengan_tgl_aku_hari_ini_atau_lampau_atau_kosong()
    {
        $today = now()->format('Y-m-d');
        $past = now()->subYears(2)->format('Y-m-d');

        $this->post('/asets', [
            'nm_aset' => 'Aset Hari Ini',
            'deskripsi' => 'Deskripsi 1',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('aset1.jpg'),
            'tgl_aku' => $today,
        ])->assertSessionHasNoErrors()->assertRedirect('/asets');

        $this->post('/asets', [
            'nm_aset' => 'Aset Lampau',
            'deskripsi' => 'Deskripsi 2',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('aset2.jpg'),
            'tgl_aku' => $past,
        ])->assertSessionHasNoErrors()->assertRedirect('/asets');

        $this->post('/asets', [
            'nm_aset' => 'Aset Tanpa Tgl Aku',
            'deskripsi' => 'Deskripsi 3',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'gambar' => UploadedFile::fake()->image('aset3.jpg'),
            'tgl_aku' => null,
        ])->assertSessionHasNoErrors()->assertRedirect('/asets');

        $this->assertDatabaseHas('asets', ['nm_aset' => 'Aset Hari Ini', 'tgl_aku' => $today]);
        $this->assertDatabaseHas('asets', ['nm_aset' => 'Aset Lampau', 'tgl_aku' => $past]);
        $this->assertDatabaseHas('asets', ['nm_aset' => 'Aset Tanpa Tgl Aku', 'tgl_aku' => null]);
    }

    /** @test */
    public function update_aset_gagal_jika_tgl_aku_di_masa_depan()
    {
        $originalDate = now()->subDays(10)->format('Y-m-d');
        $futureDate = now()->addDays(3)->format('Y-m-d');

        $aset = Aset::create([
            'kd_aset' => 'AST-000002',
            'nm_aset' => 'UPS Server',
            'deskripsi' => 'UPS 3000VA',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'tgl_aku' => $originalDate,
            'gambar' => 'gambar/ups.jpg',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $response = $this->put('/asets/' . $aset->id, [
            'nm_aset' => 'UPS Server',
            'deskripsi' => 'UPS 3000VA',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'tgl_aku' => $futureDate,
        ]);

        $response->assertSessionHasErrors('tgl_aku');
        $this->assertDatabaseHas('asets', [
            'id' => $aset->id,
            'tgl_aku' => $originalDate,
        ]);
    }

    /** @test */
    public function update_aset_berhasil_dengan_tgl_aku_hari_ini_atau_lampau()
    {
        $today = now()->format('Y-m-d');

        $aset = Aset::create([
            'kd_aset' => 'AST-000003',
            'nm_aset' => 'Printer Kantor',
            'deskripsi' => 'Printer laser',
            'tgl_penambahan' => $today,
            'tgl_aku' => now()->subMonth()->format('Y-m-d'),
            'gambar' => 'gambar/printer.jpg',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
        ]);

        $response = $this->put('/asets/' . $aset->id, [
            'nm_aset' => 'Printer Kantor',
            'deskripsi' => 'Printer laser',
            'kategori_id' => $this->kategori->id,
            'lokasi_id' => $this->lokasi->id,
            'tgl_aku' => $today,
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/asets');
        $this->assertDatabaseHas('asets', [
            'id' => $aset->id,
            'tgl_aku' => $today,
        ]);
    }
}
