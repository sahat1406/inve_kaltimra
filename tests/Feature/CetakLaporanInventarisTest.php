<?php

namespace Tests\Feature;

use App\Models\Aset;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class CetakLaporanInventarisTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function manager_dapat_mencetak_semua_atau_aset_terpilih()
    {
        $managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $manager     = User::factory()->create(['role_id' => $managerRole->id]);

        $kategori = Kategori::create(['kategori' => 'Elektronik']);
        $lokasi   = Lokasi::create(['lokasi' => 'Ruang Server']);

        $aset1 = Aset::create([
            'kd_aset'        => 'AST-INV-01',
            'nm_aset'        => 'UPS Server 3000VA',
            'deskripsi'      => 'Backup daya ruang server',
            'tgl_penambahan' => '2026-11-26',
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $kategori->id,
            'lokasi_id'      => $lokasi->id,
        ]);

        $aset2 = Aset::create([
            'kd_aset'        => 'AST-INV-02',
            'nm_aset'        => 'Switch Core 24 Port',
            'deskripsi'      => 'Switch jaringan utama',
            'tgl_penambahan' => '2026-11-26',
            'gambar'         => 'gambar/default.jpg',
            'kategori_id'    => $kategori->id,
            'lokasi_id'      => $lokasi->id,
        ]);

        $indexResponse = $this->actingAs($manager)->get('/laporan-inventaris');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Cetak Terpilih');
        $indexResponse->assertSee('select-all');
        $indexResponse->assertSee('value="' . $aset1->id . '"', false);
        $indexResponse->assertSee('value="' . $aset2->id . '"', false);

        $viewHtml = view('laporan-inventaris.laporan', [
            'asets' => Aset::with('lokasi')->get(),
        ])->render();

        $this->assertStringContainsString('PT PLN Nusantara Power', $viewHtml);
        $this->assertStringContainsString('Unit Pembangkitan Kaltimra', $viewHtml);
        $this->assertStringContainsString('Laporan Inventaris Aset', $viewHtml);
        $this->assertStringContainsString('Mengetahui,', $viewHtml);
        $this->assertStringContainsString('Manager UP Kaltimra', $viewHtml);
        $this->assertStringContainsString('Penanggung Jawab Inventaris', $viewHtml);
        $this->assertStringContainsString($aset1->kd_aset, $viewHtml);

        $capturedViewData = null;
        View::composer('laporan-inventaris.laporan', function ($view) use (&$capturedViewData) {
            $capturedViewData = $view->getData();
        });

        $selectedResponse = $this->actingAs($manager)->get('/laporan-inventaris/laporan?ids=' . $aset2->id);
        $selectedResponse->assertStatus(200);
        $selectedResponse->assertHeader('content-type', 'application/pdf');
        $this->assertCount(1, $capturedViewData['asets']);
        $this->assertSame($aset2->id, $capturedViewData['asets']->first()->id);

        $allResponse = $this->actingAs($manager)->get('/laporan-inventaris/laporan');
        $allResponse->assertStatus(200);
        $allResponse->assertHeader('content-type', 'application/pdf');
        $this->assertCount(2, $capturedViewData['asets']);
    }
}
