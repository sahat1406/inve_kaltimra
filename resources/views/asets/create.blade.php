@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Tambah Data Aset</h1>
        <div class="ml-auto">
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="section-body">
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form action="/asets" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>Tanggal Penambahan</label>
                                <input type="text" class="form-control" name="tgl_penambahan" id="tgl_penambahan"
                                    readonly value="{{ old('tgl_penambahan') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Aset <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('nm_aset') is-invalid @enderror" name="nm_aset" value="{{ old('nm_aset') }}" placeholder="Contoh: Meja, Kursi, Laptop">
                                @error('nm_aset')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Quantity (Jumlah Aset) <span style="color: red">*</span></label>
                                    <input type="number" class="form-control @error('qty') is-invalid @enderror" name="qty" id="qty" min="1" max="100" value="{{ old('qty', 1) }}" required>
                                    <small class="form-text text-muted">
                                        Jika lebih dari 1 (misal: 10), sistem otomatis menambahkan 10 data aset terpisah (<b>[Nama Aset] 1</b> s/d <b>[Nama Aset] 10</b>) beserta QR Code unik masing-masing.
                                    </small>
                                    @error('qty')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="satuan">Satuan</label>
                                    <select class="form-control" name="satuan" id="satuan">
                                        <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>unit</option>
                                        <option value="set" {{ old('satuan') == 'set' ? 'selected' : '' }}>set</option>
                                        <option value="buah" {{ old('satuan') == 'buah' ? 'selected' : '' }}>buah</option>
                                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Kategori <span style="color: red">*</span></label>
                                    <select class="form-control @error('kategori_id') is-invalid @enderror" name="kategori_id">
                                        <option value="" selected>-- Pilih Kategori -- </option>
                                        @foreach ($kategories as $kategori)
                                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Lokasi <span style="color: red">*</span></label>
                                    <select class="form-control @error('lokasi_id') is-invalid @enderror" name="lokasi_id">
                                        <option value="" selected>-- Pilih Lokasi -- </option>
                                        @foreach ($lokasies as $lokasi)
                                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->lokasi }}</option>
                                        @endforeach
                                    </select>
                                    @error('lokasi_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Deskripsi <span style="color: red">*</span></label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Nomor Sub Aset (Opsional)</label>
                                <input type="text" class="form-control" name="no_sub_aset" value="{{ old('no_sub_aset') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tgl_aku">Tanggal Pengakuan Awal (Opsional)</label>
                                    <input type="date" class="form-control" name="tgl_aku" id="tgl_aku" value="{{ old('tgl_aku') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Kota (Opsional)</label>
                                    <input type="text" class="form-control" name="kota" value="{{ old('kota') }}">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Keterangan Lokasi (Opsional)</label>
                                <textarea class="form-control" name="ket_lok" rows="2">{{ old('ket_lok') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <img src="" class="img-preview img-fluid mb-3 mt-2" id="preview"
                                    style="border-radius: 5px; max-height:300px; overflow:hidden;"><br>
                                <label>Gambar <span style="color: red">*</span></label>
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" id="gambarInput" onchange="previewImage()">
                                @error('gambar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        var today = new Date();
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0');
        var day = today.getDate().toString().padStart(2, '0');
        var formattedDate = year + '-' + month + '-' + day;

        var tglElem = document.getElementById('tgl_penambahan');
        if (tglElem && !tglElem.value) {
            tglElem.value = formattedDate;
        }

        function previewImage() {
            var preview = document.getElementById('preview');
            var input = document.getElementById('gambarInput');
            if (input && input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
            }
        }
    </script>
@endsection
