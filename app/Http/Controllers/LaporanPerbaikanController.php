<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Pelaporan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LaporanPerbaikanController extends Controller
{
    public function index()
    {
        return view('laporan-perbaikan.index', [
            'pelaporans'  => Pelaporan::where('status', 'selesai')->orderBy('id', 'DESC')->get()
        ]);
    }

    public function cetakLaporan(Request $request)
    {
        $selectedIds = $this->parseSelectedIds($request);

        $pelaporan = Pelaporan::with('aset.lokasi')
            ->where('status', 'selesai')
            ->when($selectedIds, fn ($query) => $query->whereIn('id', $selectedIds))
            ->orderBy('id', 'DESC')
            ->get();

        $pdf = PDF::loadView('laporan-perbaikan.laporan', [
            'pelaporans' => $pelaporan,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-perbaikan.pdf');
    }
}
