<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Pelaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TambahPelaporanController extends Controller
{
    public function index()
    {
        return view('tambah-pelaporan.index');
    }

    public function getDataAset(Request $request)
    {
        $qrCode = $request->input('result');
        $aset = Aset::where('kd_aset', $qrCode)->first();

        $dataAset = [
<<<<<<< HEAD
            'id'        => null,
            'nm_aset'   => null,
            'kategori'  => null,
            'lokasi'    => null
=======
            'id'            => null,
            'nm_aset'       => null,
            'kategori_id'   => null,
            'lokasi_id'     => null
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        ];

        if ($aset) {
            $dataAset = [
                'id'        => $aset->id,
                'nm_aset'   => $aset->nm_aset,
                'kategori'  => $aset->kategori->kategori,
                'lokasi'    => $aset->lokasi->lokasi
            ];
        }

        return response()->json($dataAset);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'aset_id'       => 'required|exists:asets,id',
            'judul'         => 'required',
            'kondisi_aset'  => 'required'
        ], [
            'aset_id.required'      => 'Silakan scan barcode atau klik Cek Data aset terlebih dahulu !',
            'aset_id.exists'        => 'Data aset tidak ditemukan !',
            'judul.required'        => 'Form wajib diisi !',
            'kondisi_aset.required' => 'Kondisi aset wajib dipilih !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Pelaporan::create([
            'aset_id'   => $request->aset_id,
            'judul'     => $request->judul,
<<<<<<< HEAD
            'deskripsi' => $request->kondisi_aset,
=======
            'deskripsi' => $request->deskripsi,
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
            'user_id'   => Auth::id()
        ]);

        return redirect('/tambah-pelaporan')->with('success', 'Berhasil menambahkan pelaporan baru');
    }
}
