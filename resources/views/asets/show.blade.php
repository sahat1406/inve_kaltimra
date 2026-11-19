@extends('layouts.main')
<style>
    td {
        font-size: 16px;
        padding-bottom: 5px;
    }
</style>

@section('content')
    <div class="section-header">
        <h1>Detail Aset</h1>
        <div class="ml-auto">
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-back"></i> Kembali</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="gambar"><b>Gambar Aset</b></label>
                        <img src="{{ asset('storage/' . $aset->gambar) }}" alt="gambar aset" class="card-img-top">
                    </div>

                    <hr>
                    <div class="card-body">
                        <label for="gambar"><b>Barcode Aset</b></label>
                        <img src="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}" alt="qr-code"
                            style="width: 250px; height: 250px; display: block; margin: 0 auto;">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><b>{{ $aset->nm_aset }}</b></div>
                        <hr>
                        <table style="width:100%">
                            <tr>
                                <td><b>Kode Aset</b></td>
                                <td>:</td>
                                <td>{{ $aset->kd_aset }}</td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Penambahan</b></td>
                                <td>:</td>
                                <td>{{ $aset->tgl_penambahan }}</td>
                            </tr>
                            <tr>
                                <td><b>Kategori</b></td>
                                <td>:</td>
                                <td>{{ $aset->kategori->kategori }}</td>
                            </tr>
                            {{-- <tr>
                                <td><b>Merek</b></td>
                                <td>:</td>
                                <td>{{ $aset->merk->merk }}</td>
                            </tr> --}}
                            <tr>
                                <td><b>Lokasi</b></td>
                                <td>:</td>
                                <td>{{ $aset->lokasi->lokasi }}</td>
                            </tr>
                            <tr>
                                <td><b>Deskripsi</b></td>
                                <td>:</td>
                                <td>{!! $aset->deskripsi !!}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="section-title mt-0">History Perbaikan Aset</div>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Pelaporan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Analisis Perbaikan</th>
                                    <th scope="col">Tanggal Perbaikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelaporans as $pelaporan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pelaporan->judul }}</td>
                                        <td>{{ $pelaporan->deskripsi }}</td>
                                        <td>{{ $pelaporan->feedback ? $pelaporan->feedback->analisis_perbaikan : '-' }}</td>
                                        <td>{{ $pelaporan->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
