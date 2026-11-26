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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    /**
     * Validasi input form tambah maupun ubah data aset.
     */
    private function validateAset(Request $request, bool $isUpdate = false): \Illuminate\Contracts\Validation\Validator
    {
        $today = now()->format('Y-m-d');

        $rules = [
            'gambar'      => $isUpdate ? 'nullable|mimes:jpeg,jpg,png' : 'required|mimes:jpeg,jpg,png',
            'nm_aset'     => 'required',
            'deskripsi'   => 'required',
            'kategori_id' => 'required',
            'lokasi_id'   => 'required',
            'tgl_aku'     => 'nullable|date|before_or_equal:' . $today,
        ];

        $messages = [
            'gambar.mimes'            => 'Format gambar yang diizinkan: jpeg, jpg, png.',
            'nm_aset.required'        => 'Form wajib diisi!',
            'deskripsi.required'      => 'Form wajib diisi!',
            'kategori_id.required'    => 'Form wajib dipilih!',
            'lokasi_id.required'      => 'Form wajib dipilih!',
            'tgl_aku.date'            => 'Format tanggal pengakuan awal tidak valid!',
            'tgl_aku.before_or_equal' => 'Tanggal pengakuan awal tidak boleh lebih dari hari ini!',
        ];

        if (!$isUpdate) {
            $rules['qty'] = 'nullable|integer|min:1|max:100';
            $messages['gambar.required'] = 'Wajib menambahkan gambar!';
            $messages['qty.integer'] = 'Quantity harus berupa angka!';
            $messages['qty.min'] = 'Quantity minimal 1!';
            $messages['qty.max'] = 'Quantity maksimal 100 sekaligus!';
        }

        return Validator::make($request->all(), $rules, $messages);
    }

    public function store(Request $request)
    {
        $validator = $this->validateAset($request);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $path = 'gambar/';
            $file = $request->file('gambar');
            $fileName = $file->hashName();
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

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
        $createdQrPaths = [];

        DB::beginTransaction();
        try {
            for ($step = 0; $step < $qty; $step++) {
                $namaAset = $qty > 1 ? ($baseName . ' ' . ($startIndex + $step)) : $baseName;

                // Buat record terlebih dahulu agar nomor urut mengikuti ID yang unik dan berurutan
                $aset = Aset::create([
                    'kd_aset'        => 'TMP-' . Str::uuid(),
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

                $sequence = $aset->id;
                do {
                    $kd_aset = 'AST-' . str_pad($sequence, 6, '0', STR_PAD_LEFT);
                    $sequence++;
                } while (Aset::where('kd_aset', $kd_aset)->where('id', '!=', $aset->id)->exists());

                $aset->update(['kd_aset' => $kd_aset]);

                // Buat file QR Code jika belum ada
                $qrCodePath = 'qrcode/' . $kd_aset . '.png';
                if (!Storage::disk('public')->exists($qrCodePath)) {
                    $qrCode = new QrCode($kd_aset);
                    $qrImage = $writer->write($qrCode);
                    Storage::disk('public')->put($qrCodePath, $qrImage->getString());
                    $createdQrPaths[] = $qrCodePath;
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            if ($gambar && Storage::disk('public')->exists($gambar)) {
                Storage::disk('public')->delete($gambar);
            }
            foreach ($createdQrPaths as $qrPath) {
                if (Storage::disk('public')->exists($qrPath)) {
                    Storage::disk('public')->delete($qrPath);
                }
            }
            return back()->with('error', 'Gagal menambahkan aset: ' . $e->getMessage())->withInput();
        }

        $successMsg = $qty > 1
            ? "Berhasil menambahkan {$qty} data aset baru ({$baseName} {$startIndex} s/d {$baseName} " . ($startIndex + $qty - 1) . ")"
            : 'Berhasil menambahkan data aset baru';

        return redirect('/asets')->with('success', $successMsg);
    }

    public function show(string $id)
    {
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

        return view('asets.show', [
            'aset' => $aset,
            'pelaporans' => $pelaporans,
            'batchAsets' => $batchAsets,
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

        $validator = $this->validateAset($request, true);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gambar = $aset->gambar;
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama hanya jika tidak dipakai oleh aset lain
            if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
                $isShared = Aset::where('gambar', $aset->gambar)->where('id', '!=', $aset->id)->exists();
                if (!$isShared) {
                    Storage::disk('public')->delete($aset->gambar);
                }
            }

            $path = 'gambar/';
            $file = $request->file('gambar');
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $gambar = $file->storeAs($path, $fileName, 'public');
        }

        $aset->update([
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
        ]);

        return redirect('/asets')->with('success', 'Berhasil memperbarui data aset');
    }

    /**
     * Hapus satu record aset beserta QR Code dan gambar fisiknya (jika tidak dipakai aset lain).
     */
    private function deleteAsetRecord(Aset $aset): void
    {
        if ($aset->gambar && Storage::disk('public')->exists($aset->gambar)) {
            $isShared = Aset::where('gambar', $aset->gambar)->where('id', '!=', $aset->id)->exists();
            if (!$isShared) {
                Storage::disk('public')->delete($aset->gambar);
            }
        }

        $qrPath = 'qrcode/' . $aset->kd_aset . '.png';
        if (Storage::disk('public')->exists($qrPath)) {
            Storage::disk('public')->delete($qrPath);
        }

        $aset->delete();
    }

    public function destroy(string $id)
    {
        $aset = Aset::findOrFail($id);
        $this->deleteAsetRecord($aset);

        return redirect()->back()->with('success', 'Berhasil menghapus data aset');
    }

    /**
     * Hapus beberapa aset terpilih sekaligus.
     */
    public function destroySelected(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:asets,id',
        ], [
            'ids.required' => 'Pilih minimal satu aset yang ingin dihapus.',
            'ids.array'    => 'Format pilihan aset tidak valid.',
            'ids.min'      => 'Pilih minimal satu aset yang ingin dihapus.',
            'ids.*.exists' => 'Terdapat data aset yang tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error', $validator->errors()->first());
        }

        $ids = array_values(array_unique($request->input('ids', [])));

        DB::transaction(function () use ($ids) {
            $asets = Aset::whereIn('id', $ids)->get();
            foreach ($asets as $aset) {
                $this->deleteAsetRecord($aset);
            }
        });

        return redirect('/asets')->with('success', 'Berhasil menghapus ' . count($ids) . ' data aset terpilih');
    }

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
}
