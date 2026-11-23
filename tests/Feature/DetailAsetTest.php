<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Feedback;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pelaporan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DetailAsetTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;
    protected $kategori;
    protected $lokasi;

    protected function setUp(): void
    {
        parent::setUp();

        $managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $adminRole   = Role::create(['id' => 2, 'role' => 'admin']);

        $this->admin = User::factory()->create(['role_id' => $managerRole->id]);
        $this->user  = User::factory()->create(['role_id' => $adminRole->id]);

        $this->kategori = Kategori::create(['kategori' => 'Elektronik']);
        $this->lokasi   = Lokasi::create(['lokasi' => 'Ruang Server UP Kaltimra']);
    }

    /** @test */
    public function admin_dan_user_dapat_melihat_detail_aset_lengkap()
    {
        $aset = Aset::create([
            'kd_aset'        => 'AST-TEST01',
            'nm_aset'        => 'Server Rack Utama',
            'no_sub_aset'    => 'SUB-001',
            'satuan'         => 'unit',
            'deskripsi'      => 'Server rack untuk sistem data inventaris',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'tgl_aku'        => '2025-01-15',
            'kota'           => 'Balikpapan',
            'ket_lok'        => 'Lantai 2 Gedung Administrasi',
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $this->kategori->id,
            'lokasi_id'      => $this->lokasi->id,
        ]);

        $response = $this->actingAs($this->user)->get('/asets/' . $aset->id);

        $response->assertStatus(200);
        $response->assertSeeText('Server Rack Utama');
        $response->assertSeeText('AST-TEST01');
        $response->assertSeeText('Jumlah Aset');
        $response->assertSeeText('Elektronik');
        $response->assertSeeText('Ruang Server UP Kaltimra');
        $response->assertSeeText('Lantai 2 Gedung Administrasi');
        $response->assertSeeText('Server rack untuk sistem data inventaris');
        $response->assertSeeText('Tanggal Penambahan');
        $response->assertSeeText('Tanggal Pengakuan Awal');
        $response->assertSeeText('Keterangan Lokasi');
        $response->assertSeeText('Riwayat Pelaporan & Pemeliharaan Aset', false);
        $response->assertSeeText('Baik / Normal Beroperasi');
    }

    /** @test */
    public function halaman_detail_menampilkan_riwayat_pelaporan_dan_analisis_perbaikan()
    {
        $aset = Aset::create([
            'kd_aset'        => 'AST-TEST02',
            'nm_aset'        => 'AC Ruang Rapat',
            'deskripsi'      => 'Pendingin ruangan',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $this->kategori->id,
            'lokasi_id'      => $this->lokasi->id,
        ]);

        $pelaporan = Pelaporan::create([
            'aset_id'   => $aset->id,
            'user_id'   => $this->user->id,
            'judul'     => 'AC Tidak Dingin',
            'deskripsi' => 'Filter kotor dan freon berkurang',
            'status'    => 'selesai',
        ]);

        Feedback::create([
            'pelaporan_id' => $pelaporan->id,
            'user_id'      => $this->admin->id,
            'komentar'     => 'Sudah dilakukan pembersihan filter dan pengisian ulang freon R32',
        ]);

        $response = $this->actingAs($this->admin)->get('/asets/' . $aset->id);

        $response->assertStatus(200);
        $response->assertSeeText('AC Tidak Dingin');
        $response->assertSeeText('Filter kotor dan freon berkurang');
        $response->assertSeeText('Sudah dilakukan pembersihan filter dan pengisian ulang freon R32');
        $response->assertSeeText('Selesai');
        $response->assertSeeText($this->user->name);
    }
}
