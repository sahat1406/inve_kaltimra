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

class AnalisisPerbaikanTest extends TestCase
{
    use RefreshDatabase;

    protected User $manager;
    protected User $admin;
    protected Aset $aset;

    protected function setUp(): void
    {
        parent::setUp();

        $managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $adminRole   = Role::create(['id' => 2, 'role' => 'admin']);

        $this->manager = User::factory()->create(['role_id' => $managerRole->id]);
        $this->admin   = User::factory()->create(['role_id' => $adminRole->id]);

        $kategori = Kategori::create(['kategori' => 'Elektronik']);
        $lokasi   = Lokasi::create(['lokasi' => 'Ruang IT']);

        $this->aset = Aset::create([
            'kd_aset'        => 'AST-001',
            'nm_aset'        => 'Printer Kantor',
            'deskripsi'      => 'Printer laser',
            'tgl_penambahan' => now()->format('Y-m-d'),
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $kategori->id,
            'lokasi_id'      => $lokasi->id,
        ]);
    }

    /** @test */
    public function manager_menutup_pelaporan_dengan_analisis_perbaikan_dan_tampil_di_cek_pelaporan()
    {
        $pelaporan = Pelaporan::create([
            'aset_id'   => $this->aset->id,
            'user_id'   => $this->admin->id,
            'judul'     => 'Ruang IT Lt 1',
            'deskripsi' => 'Printer macet saat mencetak',
            'status'    => 'sedang diperbaiki',
        ]);

        $response = $this->actingAs($this->manager)->put("/pelaporan-masuk/detail/{$pelaporan->id}/selesai", [
            'pelaporan_id'       => $pelaporan->id,
            'analisis_perbaikan' => 'Roller kertas sudah dibersihkan dan diganti',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('pelaporans', [
            'id'     => $pelaporan->id,
            'status' => 'selesai',
        ]);

        $this->assertDatabaseHas('feedback', [
            'pelaporan_id'       => $pelaporan->id,
            'analisis_perbaikan' => 'Roller kertas sudah dibersihkan dan diganti',
        ]);

        $detailResponse = $this->actingAs($this->admin)->get("/cek-pelaporan/detail/{$pelaporan->id}");
        $detailResponse->assertStatus(200);
        $detailResponse->assertSeeText('Roller kertas sudah dibersihkan dan diganti');

        $masukDetailResponse = $this->actingAs($this->manager)->get("/pelaporan-masuk/detail/{$pelaporan->id}");
        $masukDetailResponse->assertStatus(200);
        $masukDetailResponse->assertSeeText('Roller kertas sudah dibersihkan dan diganti');
    }

    /** @test */
    public function balasan_feedback_tersimpan_ke_feedback_yang_tepat_meski_id_berbeda()
    {
        $pelaporan1 = Pelaporan::create([
            'aset_id'   => $this->aset->id,
            'user_id'   => $this->admin->id,
            'judul'     => 'Titik 1',
            'deskripsi' => 'Masalah 1',
            'status'    => 'menunggu',
        ]);

        $pelaporan2 = Pelaporan::create([
            'aset_id'   => $this->aset->id,
            'user_id'   => $this->admin->id,
            'judul'     => 'Titik 2',
            'deskripsi' => 'Masalah 2',
            'status'    => 'selesai',
        ]);

        $feedback = Feedback::create([
            'pelaporan_id'       => $pelaporan2->id,
            'user_id'            => $this->manager->id,
            'analisis_perbaikan' => 'Sudah diganti kabel power',
        ]);

        $this->assertNotEquals($pelaporan2->id, $feedback->id);

        $replyResponse = $this->actingAs($this->admin)->post("/cek-pelaporan/detail/{$pelaporan2->id}", [
            'feedback_id'      => $feedback->id,
            'feedback_replies' => 'Terima kasih, sudah normal kembali',
        ]);

        $replyResponse->assertRedirect();

        $this->assertDatabaseHas('feedback_replies', [
            'feedback_id'      => $feedback->id,
            'feedback_replies' => 'Terima kasih, sudah normal kembali',
        ]);

        $detailResponse = $this->actingAs($this->admin)->get("/cek-pelaporan/detail/{$pelaporan2->id}");
        $detailResponse->assertStatus(200);
        $detailResponse->assertSeeText('Sudah diganti kabel power');
        $detailResponse->assertSeeText('Terima kasih, sudah normal kembali');
    }

    /** @test */
    public function cek_pelaporan_menampilkan_pesan_jika_analisis_perbaikan_belum_ada()
    {
        $pelaporan = Pelaporan::create([
            'aset_id'   => $this->aset->id,
            'user_id'   => $this->admin->id,
            'judul'     => 'Titik 3',
            'deskripsi' => 'Masalah 3',
            'status'    => 'menunggu',
        ]);

        $detailResponse = $this->actingAs($this->admin)->get("/cek-pelaporan/detail/{$pelaporan->id}");
        $detailResponse->assertStatus(200);
        $detailResponse->assertSeeText('Belum ada analisis perbaikan.');
        $detailResponse->assertSeeText('Feedback dapat diberikan setelah pelaporan selesai diperbaiki.');
    }
}
