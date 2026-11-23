@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Tambah Data Aset</h1>
        <div class="ml-auto">
<<<<<<< HEAD
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
=======
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-plus"></i> Kembali</a>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        </div>
    </div>

    <div class="section-body">
<<<<<<< HEAD
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        <form action="/asets" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-body">
<<<<<<< HEAD
                            <div class="form-group mb-3">
                                <label>Tanggal Penambahan</label>
                                <input type="text" class="form-control" name="tgl_penambahan" id="tgl_penambahan"
                                    readonly value="{{ old('tgl_penambahan') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>Nama Aset <span style="color: red">*</span></label>
                                <input type="text" class="form-control @error('nm_aset') is-invalid @enderror" name="nm_aset" value="{{ old('nm_aset') }}" placeholder="Contoh: Meja, Kursi, Laptop">
=======
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="text" class="form-control" name="tgl_penambahan" id="tgl_penambahan"
                                    readonly value="">
                            </div>
                            <div class="form-group">
                                <label>Nomor Sub Aset <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="no_sub_aset">
                                @error('no_sub_aset')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Aset <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="nm_aset">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                @error('nm_aset')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
<<<<<<< HEAD

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
=======
                            <div class="form-group mb-3">
                                <label>Deskripsi <span style="color: red">*</span></label>
                                <textarea class="form-control" name="deskripsi" class="deskripsi"></textarea>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                @error('deskripsi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
<<<<<<< HEAD

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
=======
                            
                            
                            <div class="col-4 mb-3">
                                <label>Kategori <span style="color: red">*</span></label>
                                <select class="form-control" name="kategori_id">
                                    <option value="" selected>-- Pilih Kategori -- </option>
                                    @foreach ($kategories as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                                {{-- <div class="col-4 mb-3">
                                    <label>Merek <span style="color: red">*</span></label>
                                    <select class="form-control" name="merk_id">
                                        <option value="" selected>-- Pilih Merek -- </option>
                                        @foreach ($merks as $merk)
                                            <option value="{{ $merk->id }}">{{ $merk->merk }}</option>
                                        @endforeach
                                    </select>
                                    @error('merk_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                            <div class="col-4 mb-3">
                                <label>Lokasi <span style="color: red">*</span></label>
                                <select class="form-control" name="lokasi_id">
                                    <option value="" selected>-- Pilih Lokasi -- </option>
                                    @foreach ($lokasies as $lokasi)
                                        <option value="{{ $lokasi->id }}">{{ $lokasi->lokasi }}</option>
                                    @endforeach
                                </select>
                                @error('lokasi_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                              
                            <div class="form-group mb-3">
                                <label>Keterangan Lokasi <span style="color: red">*</span></label>
                                <textarea class="form-control" name="ket_lok" class="ket_lok"></textarea>
                                @error('ket_lok')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="tgl_aku">Tanggal Pengakuan Awal <span style="color: red">*</span></label>
                                <input type="date" class="form-control" name="tgl_aku" id="tgl_aku" required>
                                @error('tgl_aku')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Kota <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="kota">
                                @error('kota')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Quantity <span style="color: red">*</span></label>
                                <input type="number" class="form-control" name="qty">
                                @error('qty')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-4 mb-3">
                                <label for="satuan">Satuan <span style="color: red">*</span></label>
                                <select class="form-control" name="satuan" id="satuan" required>
                                    <option value="" selected>-- Pilih Satuan --</option>
                                    <option value="unit">unit</option>
                                    <option value="set">set</option>
                                    <option value="buah">buah</option>
                                    <option value="pcs">pcs</option>
                                </select>
                                @error('satuan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
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
<<<<<<< HEAD
                                <input type="file" class="form-control @error('gambar') is-invalid @enderror" name="gambar" id="gambarInput" onchange="previewImage()">
=======
                                <input type="file" class="form-control" name="gambar" onchange="previewImage()">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                @error('gambar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

<<<<<<< HEAD
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Simpan</button>
=======
                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                </div>
            </div>
        </form>
    </div>

    <script>
<<<<<<< HEAD
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
=======
        // Mendapatkan tanggal hari ini
        var today = new Date();

        // Mendapatkan tahun, bulan, dan tanggal
        var year = today.getFullYear();
        var month = (today.getMonth() + 1).toString().padStart(2, '0'); // Bulan dimulai dari 0
        var day = today.getDate().toString().padStart(2, '0');

        // Format tanggal sesuai kebutuhan (misal: YYYY-MM-DD)
        var formattedDate = year + '-' + month + '-' + day;

        // Mengatur nilai input tanggal
        document.getElementById('tgl_penambahan').value = formattedDate;
    </script>

    <!-- Preview Image -->
    <script>
        function previewImage() {
            preview.src = URL.createObjectURL(event.target.files[0]);
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        }
    </script>
@endsection
