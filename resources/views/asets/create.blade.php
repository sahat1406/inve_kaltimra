@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Tambah Data Aset</h1>
        <div class="ml-auto">
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-plus"></i> Kembali</a>
        </div>
    </div>

    <div class="section-body">
        <form action="/asets" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-body">
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
                                @error('nm_aset')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label>Deskripsi <span style="color: red">*</span></label>
                                <textarea class="form-control" name="deskripsi" class="deskripsi"></textarea>
                                @error('deskripsi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            
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
                                <input type="file" class="form-control" name="gambar" onchange="previewImage()">
                                @error('gambar')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Simpan</button>
                </div>
            </div>
        </form>
    </div>

    <script>
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
        }
    </script>
@endsection
