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
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
=======
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8

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
<<<<<<< HEAD
            'lokasi_id' => 'required',
            'qty' => 'nullable|integer|min:1|max:100',
=======
            'lokasi_id' => 'required'
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        ], [
            'gambar.required' => 'Wajib menambahkan gambar!',
            'gambar.mimes' => 'Format gambar yang diizinkan: jpeg, jpg, png.',
            'nm_aset.required' => 'Form wajib diisi!',
            'deskripsi.required' => 'Form wajib diisi!',
            'kategori_id.required' => 'Form wajib dipilih!',
            'lokasi_id.required' => 'Form wajib dipilih!',
<<<<<<< HEAD
            'qty.integer' => 'Quantity harus berupa angka!',
            'qty.min' => 'Quantity minimal 1!',
            'qty.max' => 'Quantity maksimal 100 sekaligus!',
=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $path = 'gambar/';
            $file = $request->file('gambar');
<<<<<<< HEAD
=======
            $extension = $file->getClientOriginalExtension();
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
            $fileName = $file->hashName();
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

<<<<<<< HEAD
        $qty = max(1, (int) $request->input('qty', 1));
        $baseName = trim($request->nm_aset);

        // Menentukan nomor awal jika qty > 1
        $startIndex = 1;
        if ($qty > 1) {
            $existing = Aset::where('nm_aset', 'LIKE', $baseName . ' %')->pluck('nm_aset');
            $maxNum = 0;
            foreach ($existing as $name) {
                if (preg_match('/^' . preg_quote($baseName, '/') . '\s+(\d+)$/i', $name, $matches)) {
                    $num = (int) $matches[1];
                    if ($num > $maxNum) {
                        $maxNum = $num;
                    }
                }
            }

            if ($maxNum > 0) {
                $startIndex = $maxNum + 1;
            } elseif (Aset::where('nm_aset', $baseName)->exists()) {
                $startIndex = 2;
            } else {
                $startIndex = 1;
            }
        }

        $writer = new PngWriter();
        $generatedCodes = [];

        DB::beginTransaction();
        try {
            for ($step = 0; $step < $qty; $step++) {
                $namaAset = $qty > 1 ? ($baseName . ' ' . ($startIndex + $step)) : $baseName;

                // Generate kd_aset unik
                do {
                    $kd_aset = 'AST-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
                } while (in_array($kd_aset, $generatedCodes) || Aset::where('kd_aset', $kd_aset)->exists());

                $generatedCodes[] = $kd_aset;

                // Buat file QR Code jika belum ada
                $qrCodePath = 'qrcode/' . $kd_aset . '.png';
                if (!Storage::disk('public')->exists($qrCodePath)) {
                    $qrCode = new QrCode($kd_aset);
                    $qrImage = $writer->write($qrCode);
                    Storage::disk('public')->put($qrCodePath, $qrImage->getString());
                }

                Aset::create([
                    'kd_aset'        => $kd_aset,
                    'nm_aset'        => $namaAset,
                    'no_sub_aset'    => $request->no_sub_aset,
                    'satuan'         => $request->satuan ?? 'unit',
                    'deskripsi'      => $request->deskripsi,
                    'tgl_penambahan' => $request->tgl_penambahan ?? now()->format('Y-m-d'),
                    'tgl_aku'        => $request->tgl_aku,
                    'gambar'         => $gambar,
                    'kategori_id'    => $request->kategori_id,
                    'lokasi_id'      => $request->lokasi_id,
                    'kota'           => $request->kota,
                    'ket_lok'        => $request->ket_lok,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }
            return back()->with('error', 'Gagal menambahkan aset: ' . $e->getMessage())->withInput();
        }

        $successMsg = $qty > 1
            ? "Berhasil menambahkan {$qty} data aset baru ({$baseName} {$startIndex} s/d {$baseName} " . ($startIndex + $qty - 1) . ")"
            : 'Berhasil menambahkan data aset baru';

        return redirect('/asets')->with('success', $successMsg);
=======
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
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
    }

    public function show(string $id)
    {
<<<<<<< HEAD
        $aset = Aset::with(['kategori', 'lokasi'])->findOrFail($id);

        // Pastikan QR code file tersedia di storage jika belum ada
        $qrCodePath = 'qrcode/' . $aset->kd_aset . '.png';
        if (!Storage::disk('public')->exists($qrCodePath)) {
            $qrCode = new QrCode($aset->kd_aset);
            $writer = new PngWriter();
            $qrImage = $writer->write($qrCode);
            Storage::disk('public')->put($qrCodePath, $qrImage->getString());
        }

        // Ambil pelaporan lengkap dengan relasi feedback dan user pelapor
        $pelaporans = Pelaporan::with(['feedback', 'user'])->where('aset_id', $aset->id)->orderBy('id', 'DESC')->get();

        // Cari unit lain dalam batch jika aset memiliki penomoran (misal: "Meja 1")
        $batchAsets = collect();
        if (preg_match('/^(.*?)\s+\d+$/', $aset->nm_aset, $matches)) {
            $baseName = trim($matches[1]);
            $batchAsets = Aset::where('nm_aset', 'LIKE', $baseName . ' %')
                ->where('id', '!=', $aset->id)
                ->orderBy('id', 'ASC')
                ->limit(12)
                ->get();
        }
=======
        $aset = Aset::findOrFail($id);

        // Ambil pelaporan lengkap dengan relasi feedback-nya
        $pelaporans = Pelaporan::with('feedback')->where('aset_id', $aset->id)->get();
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8

        return view('asets.show', [
            'aset' => $aset,
            'pelaporans' => $pelaporans,
<<<<<<< HEAD
            'batchAsets' => $batchAsets,
=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
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
<<<<<<< HEAD
            // Hapus gambar lama hanya jika tidak dipakai oleh aset lain
            if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
                $isShared = Aset::where('gambar', $aset->gambar)->where('id', '!=', $aset->id)->exists();
                if (!$isShared) {
                    Storage::disk('public')->delete($aset->gambar);
                }
=======
            if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
                Storage::disk('public')->delete($aset->gambar);
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
            }

            $path = 'gambar/';
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

        $aset->update([
<<<<<<< HEAD
            'nm_aset'        => $request->nm_aset,
            'no_sub_aset'    => $request->no_sub_aset,
            'satuan'         => $request->satuan ?? ($aset->satuan ?? 'unit'),
            'deskripsi'      => $request->deskripsi,
            'tgl_aku'        => $request->tgl_aku,
            'kota'           => $request->kota,
            'ket_lok'        => $request->ket_lok,
            'gambar'         => $gambar,
            'kategori_id'    => $request->kategori_id,
            'lokasi_id'      => $request->lokasi_id,
=======
            'nm_aset' => $request->nm_aset,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'kategori_id' => $request->kategori_id,
            'lokasi_id' => $request->lokasi_id,
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        ]);

        return redirect('/asets')->with('success', 'Berhasil memperbarui data aset');
    }

    public function destroy(string $id)
    {
        $aset = Aset::findOrFail($id);

<<<<<<< HEAD
        // Hapus gambar fisik hanya jika tidak dipakai oleh aset lain
        if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
            $isShared = Aset::where('gambar', $aset->gambar)->where('id', '!=', $aset->id)->exists();
            if (!$isShared) {
                Storage::disk('public')->delete($aset->gambar);
            }
=======
        if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
            Storage::disk('public')->delete($aset->gambar);
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        }

        $qrPath = 'qrcode/' . $aset->kd_aset . '.png';
        if (Storage::disk('public')->exists($qrPath)) {
            Storage::disk('public')->delete($qrPath);
        }

        $aset->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus data aset');
    }
<<<<<<< HEAD

    /**
     * Cetak label QR Code untuk satu aset
     */
    public function cetakQr(string $id)
    {
        $aset = Aset::with(['kategori', 'lokasi'])->findOrFail($id);

        $qrCodePath = 'qrcode/' . $aset->kd_aset . '.png';
        if (!Storage::disk('public')->exists($qrCodePath)) {
            $qrCode = new QrCode($aset->kd_aset);
            $writer = new PngWriter();
            $qrImage = $writer->write($qrCode);
            Storage::disk('public')->put($qrCodePath, $qrImage->getString());
        }

        return view('asets.cetak-qr', [
            'aset' => $aset
        ]);
    }

    /**
     * Cetak semua label QR Code dalam format grid siap cetak (sticker sheet)
     */
    public function cetakSemuaQr(Request $request)
    {
        $query = Aset::with(['kategori', 'lokasi'])->orderBy('id', 'DESC');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }
        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        $asets = $query->get();

        // Pastikan QR code untuk semua aset sudah terbuat
        $writer = new PngWriter();
        foreach ($asets as $aset) {
            $qrCodePath = 'qrcode/' . $aset->kd_aset . '.png';
            if (!Storage::disk('public')->exists($qrCodePath)) {
                $qrCode = new QrCode($aset->kd_aset);
                $qrImage = $writer->write($qrCode);
                Storage::disk('public')->put($qrCodePath, $qrImage->getString());
            }
        }

        return view('asets.cetak-semua-qr', [
            'asets' => $asets,
            'kategories' => Kategori::all(),
            'lokasies' => Lokasi::all(),
        ]);
    }
=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
}
