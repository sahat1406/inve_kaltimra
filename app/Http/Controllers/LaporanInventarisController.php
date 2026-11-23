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

    public function cetakLaporan()
    {
        $aset = Aset::orderBy('id', 'DESC')->get();

        $pdf = PDF::loadView('laporan-inventaris.laporan', [
            'asets'    => $aset
        ]);

        return $pdf->stream('laporan-inventaris.pdf');
    }
}
