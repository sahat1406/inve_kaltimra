<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class LaporanInventarisController extends Controller
{
    public function index()
    {
        return view('laporan-inventaris.index', [
            'asets'   => Aset::orderBy('id', 'DESC')->get()
        ]);
    }

    public function cetakLaporan(Request $request)
    {
        $selectedIds = $this->parseSelectedIds($request);

        $aset = Aset::with('lokasi')
            ->when($selectedIds, fn ($query) => $query->whereIn('id', $selectedIds))
            ->orderBy('id', 'DESC')
            ->get();

        $pdf = PDF::loadView('laporan-inventaris.laporan', [
            'asets' => $aset,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-inventaris.pdf');
    }
}
