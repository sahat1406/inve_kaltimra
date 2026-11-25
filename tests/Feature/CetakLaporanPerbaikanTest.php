<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pelaporan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class CetakLaporanPerbaikanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_dapat_mencetak_semua_atau_laporan_perbaikan_terpilih()
    {
        $managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $manager     = User::factory()->create(['role_id' => $managerRole->id]);

        $kategori = Kategori::create(['kategori' => 'Elektronik']);
        $lokasi   = Lokasi::create(['lokasi' => 'Ruang Kontrol']);

        $aset = Aset::create([
            'kd_aset'        => 'AST-PERB-01',
            'nm_aset'        => 'Panel Monitor Kontrol',
            'deskripsi'      => 'Monitor ruang kontrol utama',
            'tgl_penambahan' => '2026-11-26',
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $kategori->id,
            'lokasi_id'      => $lokasi->id,
        ]);

        $pelaporan1 = Pelaporan::create([
            'aset_id'   => $aset->id,
            'user_id'   => $manager->id,
            'judul'     => 'Panel Berkedip',
            'deskripsi' => 'Layar monitor berkedip saat beban puncak',
            'status'    => 'selesai',
        ]);

        $pelaporan2 = Pelaporan::create([
            'aset_id'   => $aset->id,
            'user_id'   => $manager->id,
            'judul'     => 'Kabel HDMI Longgar',
            'deskripsi' => 'Tampilan putus-putus pada layar',
            'status'    => 'selesai',
        ]);

        $indexResponse = $this->actingAs($manager)->get('/laporan-perbaikan');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Cetak Terpilih');
        $indexResponse->assertSee('select-all');
        $indexResponse->assertSee('value="' . $pelaporan1->id . '"', false);
        $indexResponse->assertSee('value="' . $pelaporan2->id . '"', false);

        $viewHtml = view('laporan-perbaikan.laporan', [
            'pelaporans' => Pelaporan::with('aset.lokasi')->where('status', 'selesai')->get(),
        ])->render();

        $this->assertStringContainsString('PT PLN Nusantara Power', $viewHtml);
        $this->assertStringContainsString('Unit Pembangkitan Kaltimra', $viewHtml);
        $this->assertStringContainsString('Laporan Perbaikan Aset', $viewHtml);
        $this->assertStringContainsString('Mengetahui,', $viewHtml);
        $this->assertStringContainsString('Manager UP Kaltimra', $viewHtml);
        $this->assertStringContainsString('Penanggung Jawab Inventaris', $viewHtml);
        $this->assertStringContainsString($pelaporan1->judul, $viewHtml);
        $this->assertStringContainsString($aset->nm_aset, $viewHtml);

        $capturedViewData = null;
        View::composer('laporan-perbaikan.laporan', function ($view) use (&$capturedViewData) {
            $capturedViewData = $view->getData();
        });

        $selectedResponse = $this->actingAs($manager)->get('/laporan-perbaikan/laporan?ids=' . $pelaporan1->id);
        $selectedResponse->assertStatus(200);
        $selectedResponse->assertHeader('content-type', 'application/pdf');
        $this->assertCount(1, $capturedViewData['pelaporans']);
        $this->assertSame($pelaporan1->id, $capturedViewData['pelaporans']->first()->id);

        $allResponse = $this->actingAs($manager)->get('/laporan-perbaikan/laporan');
        $allResponse->assertStatus(200);
        $allResponse->assertHeader('content-type', 'application/pdf');
        $this->assertCount(2, $capturedViewData['pelaporans']);
    }
}
