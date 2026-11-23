@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Pelaporan Aset</h1>
    </div>

    <div class="section-body">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-primary">
                    <div class="card-header">
                        Scan Barcode Disini
                    </div>
                    <div class="card-body">
                        <div id="reader" width="600px"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card card-primary">
                    <div class="card-header">
                        Detail Aset Inventaris
                    </div>
                    <div class="card-body">
                        <form action="/tambah-pelaporan" method="POST">
                            @csrf
<<<<<<< HEAD
                            <input type="hidden" name="aset_id" id="id" value="{{ old('aset_id') }}">
                            @error('aset_id')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
=======
                            <input type="hidden" name="aset_id" id="id">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label>Nama Aset</label>
<<<<<<< HEAD
                                        <input type="text" class="form-control" name="nm_aset" id="nm_aset" value="{{ old('nm_aset') }}" readonly>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Kode Aset</label>
                                        <input type="text" class="form-control" name="kd_aset" id="result" value="{{ old('kd_aset') }}">
                                        @error('aset_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
=======
                                        <input type="text" class="form-control" name="nm_aset" id="nm_aset" readonly>
                                    </div>
                                    <div class="col-lg-6">
                                        <label>Kode Aset</label>
                                        <input type="text" class="form-control" name="kd_aset" id="result">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label>Kategori</label>
                                        <input type="text" class="form-control" name="kategori" id="kategori" value="{{ old('kategori') }}" readonly>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>Lokasi</label>
                                        <input type="text" class="form-control" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row my-2">
                                <div class="col">
                                    <label>Titik Koordinat <span style="color: red">*</span></label>
                                    <input type="text" class="form-control" name="judul" id="judul" value="{{ old('judul') }}">
                                    @error('judul')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col">
                                    <label>Kondisi Aset <span style="color: red">*</span></label>
                                    <select class="form-control" name="kondisi_aset" required>
                                        <option value="" {{ old('kondisi_aset') ? '' : 'selected' }} disabled>-- Pilih Kondisi Aset --</option>
                                        <option value="Operasi" {{ old('kondisi_aset') == 'Operasi' ? 'selected' : '' }}>Operasi</option>
                                        <option value="Idle" {{ old('kondisi_aset') == 'Idle' ? 'selected' : '' }}>Idle</option>
                                        <option value="Rusak Ringan" {{ old('kondisi_aset') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                        <option value="Rusak Berat" {{ old('kondisi_aset') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                        <option value="Extracountable" {{ old('kondisi_aset') == 'Extracountable' ? 'selected' : '' }}>Extracountable</option>
                                    </select>
                                    @error('kondisi_aset')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary float-right">Kirim Laporan</button>
                            <!-- Tombol -->
<button type="button" class="btn btn-primary float-left" id="btnCekData">Cek Data</button>

<!-- Script -->
<script>
    document.getElementById('btnCekData').addEventListener('click', function () {
        // Ambil nilai dari textbox input kode aset
        const kode = document.getElementById('result').value;

        if (!kode) {
            alert("Silakan masukkan kode aset terlebih dahulu.");
            return;
        }

        // Kirim permintaan AJAX GET ke endpoint Laravel
        $.ajax({
            url: '/get-data-aset',
            method: 'GET',
            data: {
                result: kode
            },
            success: function (response) {
                // Tampilkan data ke field lainnya
                $('#id').val(response.id);
                $('#nm_aset').val(response.nm_aset);
                $('#kategori').val(response.kategori);
                $('#lokasi').val(response.lokasi);

                if (!response.id) {
                    alert("Data aset dengan kode tersebut tidak ditemukan.");
                }
            },
            error: function (xhr) {
                alert("Data tidak ditemukan atau terjadi kesalahan.");
                console.error(xhr.responseText);
            }
        });
    });
</script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            $.ajax({
                url: '/get-data-aset', // Ganti dengan endpoint yang sesuai di Laravel Anda
                method: 'GET',
                data: {
                    result: decodedText
                },
                success: function(response) {
                    // Handle the response, update UI with product information
                    $('#result').val(decodedText);
                    $('#id').val(response.id);
                    $('#nm_aset').val(response.nm_aset);
                    $('#kategori').val(response.kategori);
                    $('#lokasi').val(response.lokasi);
                    // Tambahan informasi lainnya sesuai kebutuhan
                },
                error: function(error) {
                    console.warn(`Code scan error = ${error}`);
                }
            });
        }

        function onScanFailure(rror) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@endsection
