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
            'id'            => null,
            'nm_aset'       => null,
            'kategori_id'   => null,
            'lokasi_id'     => null
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
            'judul'     => 'required',
            'deskripsi' => 'required'
        ], [
            'judul.required'        => 'Form wajib diisi !',
            'deskripsi.required'    => 'Form wajib diisi !'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Pelaporan::create([
            'aset_id'   => $request->aset_id,
            'judul'     => $request->judul,
            'deskripsi' => $request->deskripsi,
            'user_id'   => Auth::id()
        ]);

        return redirect('/tambah-pelaporan')->with('success', 'Berhasil menambahkan pelaporan baru');
    }
}
