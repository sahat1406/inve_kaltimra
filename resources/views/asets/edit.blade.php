@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Edit Data Aset</h1>
        <div class="ml-auto">
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-plus"></i> Kembali</a>
        </div>
    </div>

    <div class="section-body">
        <form action="/asets/{{ $aset->id }}" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Aset <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="nm_aset"
                                    value="{{ $aset->nm_aset }}">
                                @error('nm_aset')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label>Deskripsi <span style="color: red">*</span></label>
                                <textarea class="form-control" name="deskripsi" class="deskripsi">{{ $aset->deskripsi }}</textarea>
                                @error('deskripsi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mt-3">
<<<<<<< HEAD
                                <div class="col-6">
=======
                                <div class="col-4">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                    <label>Kategori <span style="color: red">*</span></label>
                                    <select class="form-control" name="kategori_id">
                                        @foreach ($kategories as $kategori)
                                            @if (old('kategori_id', $aset->kategori_id) == $kategori->id)
                                                <option value="{{ $kategori->id }}" selected>{{ $kategori->kategori }}
                                                </option>
                                            @else
                                                <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
<<<<<<< HEAD
                                <div class="col-6">
=======
                                {{-- <div class="col-4">
                                    <label>Merek <span style="color: red">*</span></label>
                                    <select class="form-control" name="merk_id">
                                        @foreach ($merks as $merk)
                                            @if (old('merk_id', $aset->merk_id) == $merk->id)
                                                <option value="{{ $merk->id }}" selected>{{ $merk->merk }}
                                                </option>
                                            @else
                                                <option value="{{ $merk->id }}">{{ $merk->merk }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('merk_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                                <div class="col-4">
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                                    <label>Lokasi <span style="color: red">*</span></label>
                                    <select class="form-control" name="lokasi_id">
                                        @foreach ($lokasies as $lokasi)
                                            @if (old('lokasi_id', $aset->lokasi_id) == $lokasi->id)
                                                <option value="{{ $lokasi->id }}" selected>{{ $lokasi->lokasi }}
                                                </option>
                                            @else
                                                <option value="{{ $lokasi->id }}">{{ $lokasi->lokasi }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('lokasi_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

<<<<<<< HEAD
                            <div class="form-group mb-3 mt-3">
                                <label for="satuan">Satuan</label>
                                <select class="form-control" name="satuan" id="satuan">
                                    <option value="unit" {{ old('satuan', $aset->satuan) == 'unit' ? 'selected' : '' }}>unit</option>
                                    <option value="set" {{ old('satuan', $aset->satuan) == 'set' ? 'selected' : '' }}>set</option>
                                    <option value="buah" {{ old('satuan', $aset->satuan) == 'buah' ? 'selected' : '' }}>buah</option>
                                    <option value="pcs" {{ old('satuan', $aset->satuan) == 'pcs' ? 'selected' : '' }}>pcs</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tgl_aku">Tanggal Pengakuan Awal (Opsional)</label>
                                    <input type="date" class="form-control" name="tgl_aku" id="tgl_aku" value="{{ old('tgl_aku', $aset->tgl_aku) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Kota (Opsional)</label>
                                    <input type="text" class="form-control" name="kota" value="{{ old('kota', $aset->kota) }}">
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Keterangan Lokasi (Opsional)</label>
                                <textarea class="form-control" name="ket_lok" rows="2">{{ old('ket_lok', $aset->ket_lok) }}</textarea>
                            </div>

=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <img src="{{ asset('storage/' . $aset->gambar) }}"
                                    class="img-preview img-fluid mb-3 mt-2" id="preview"
                                    style="border-radius: 5px; max-height:300px; overflow:hidden;"><br>
                                <label>Gambar <span style="color: red">*</span></label>
                                <input type="file" class="form-control" name="gambar" id="gambar"
                                    onchange="previewImage()">
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

    <!-- Preview Image -->
    <script>
        function previewImage() {
            var preview = document.getElementById('preview');
            var fileInput = document.getElementById('gambar');
            var file = fileInput.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
@endsection
