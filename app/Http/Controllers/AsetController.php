<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Lokasi;
use App\Models\Feedback;
use App\Models\Kategori;
use App\Models\Pelaporan;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AsetController extends Controller
{
    public function index()
    {
        return view('asets.index', [
            'asets' => Aset::orderBy('id', 'DESC')->get()
        ]);
    }

    public function create()
    {
        return view('asets.create', [
            'kategories' => Kategori::all(),
            'lokasies' => Lokasi::all()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gambar' => 'required|mimes:jpeg,jpg,png',
            'nm_aset' => 'required',
            'deskripsi' => 'required',
            'kategori_id' => 'required',
            'lokasi_id' => 'required'
        ], [
            'gambar.required' => 'Wajib menambahkan gambar!',
            'gambar.mimes' => 'Format gambar yang diizinkan: jpeg, jpg, png.',
            'nm_aset.required' => 'Form wajib diisi!',
            'deskripsi.required' => 'Form wajib diisi!',
            'kategori_id.required' => 'Form wajib dipilih!',
            'lokasi_id.required' => 'Form wajib dipilih!',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $path = 'gambar/';
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->hashName();
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

        $kd_aset = 'AST-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $qrCodePath = 'qrcode/' . $kd_aset . '.png';

if (!Storage::disk('public')->exists($qrCodePath)) {
    $qrCode = new QrCode($kd_aset);
    $writer = new PngWriter();

    $qrImage = $writer->write($qrCode);

    // Simpan hasil QR ke storage
    Storage::disk('public')->put($qrCodePath, $qrImage->getString());
}

        Aset::create([
            'kd_aset' => $kd_aset,
            'nm_aset' => $request->nm_aset,
            'deskripsi' => $request->deskripsi,
            'tgl_penambahan' => $request->tgl_penambahan,
            'gambar' => $gambar,
            'kategori_id' => $request->kategori_id,
            'lokasi_id' => $request->lokasi_id,
        ]);

        return redirect('/asets')->with('success', 'Berhasil menambahkan data aset baru');
    }

    public function show(string $id)
    {
        $aset = Aset::findOrFail($id);

        // Ambil pelaporan lengkap dengan relasi feedback-nya
        $pelaporans = Pelaporan::with('feedback')->where('aset_id', $aset->id)->get();

        return view('asets.show', [
            'aset' => $aset,
            'pelaporans' => $pelaporans,
        ]);
    }

    public function edit(string $id)
    {
        $aset = Aset::findOrFail($id);

        return view('asets.edit', [
            'aset' => $aset,
            'kategories' => Kategori::all(),
            'lokasies' => Lokasi::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        $aset = Aset::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'gambar' => 'nullable|mimes:jpeg,jpg,png',
            'nm_aset' => 'required',
            'deskripsi' => 'required',
            'kategori_id' => 'required',
            'lokasi_id' => 'required'
        ], [
            'gambar.mimes' => 'Format gambar yang diizinkan: jpeg, jpg, png.',
            'nm_aset.required' => 'Form wajib diisi!',
            'deskripsi.required' => 'Form wajib diisi!',
            'kategori_id.required' => 'Form wajib dipilih!',
            'lokasi_id.required' => 'Form wajib dipilih!',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gambar = $aset->gambar;
        if ($request->hasFile('gambar')) {
            if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
                Storage::disk('public')->delete($aset->gambar);
            }

            $path = 'gambar/';
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

        $aset->update([
            'nm_aset' => $request->nm_aset,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'kategori_id' => $request->kategori_id,
            'lokasi_id' => $request->lokasi_id,
        ]);

        return redirect('/asets')->with('success', 'Berhasil memperbarui data aset');
    }

    public function destroy(string $id)
    {
        $aset = Aset::findOrFail($id);

        if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
            Storage::disk('public')->delete($aset->gambar);
        }

        $qrPath = 'qrcode/' . $aset->kd_aset . '.png';
        if (Storage::disk('public')->exists($qrPath)) {
            Storage::disk('public')->delete($qrPath);
        }

        $aset->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data aset');
    }
}
