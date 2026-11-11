<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pelaporan;
use App\Models\Feedback;
use App\Models\FeedbackReply;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CetakPelaporanTest extends TestCase
{
    use RefreshDatabase;

    protected $kategori;
    protected $lokasi;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role admin
        Role::create([
            'id' => 1,
            'role' => 'admin',
        ]);

        // Buat kategori dan lokasi yang diperlukan untuk relasi barang
        $this->kategori = Kategori::factory()->create();
        $this->lokasi   = Lokasi::factory()->create();

        // Buat dan login sebagai admin
        $this->admin = User::factory()->create([
            'role_id' => 1, // Sesuai dengan role admin yang dibuat
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_dapat_mencetak_rekap_pelaporan()
    {
        // Buat barang
        $barang = Barang::factory()->create([
            'kategori_id' => $this->kategori->id,
            'lokasi_id'   => $this->lokasi->id,
        ]);

        // Buat pelaporan dengan user_id admin dan status selesai
        $pelaporan = Pelaporan::factory()->create([
            'barang_id' => $barang->id,
            'user_id'   => $this->admin->id, // ✅ wajib diset
            'status'    => 'selesai',
        ]);

        // Buat feedback dan balasannya
        $feedback = Feedback::factory()->create([
            'pelaporan_id' => $pelaporan->id,
        ]);

        FeedbackReply::factory()->create([
            'feedback_id' => $feedback->id,
        ]);

        // Jalankan request ke route cetak
        $response = $this->get("/pelaporan-selesai/cetak-laporan/{$pelaporan->id}");

        // Pastikan response status 200 dan tipe PDF
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
