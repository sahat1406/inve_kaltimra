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
            <a href="/asets/{{ $aset->id }}/cetak-qr" target="_blank" class="btn btn-info mr-2"><i class="fa fa-qrcode"></i> Cetak QR Code</a>
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <label><b>Gambar Aset</b></label>
                        <img src="{{ asset('storage/' . $aset->gambar) }}" alt="gambar aset" class="img-fluid rounded" style="max-height: 250px; object-fit: cover;">
                    </div>

                    <hr>
                    <div class="card-body text-center">
                        <label><b>Barcode / QR Code Aset</b></label>
                        <img src="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}" alt="qr-code"
                            style="width: 220px; height: 220px; display: block; margin: 0 auto;" class="img-thumbnail">
                        <div class="mt-3">
                            <a href="/asets/{{ $aset->id }}/cetak-qr" target="_blank" class="btn btn-info btn-sm">
                                <i class="fa fa-print"></i> Cetak QR Code
                            </a>
                            <a href="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}" download="QR_{{ $aset->kd_aset }}_{{ \Illuminate\Support\Str::slug($aset->nm_aset) }}.png" class="btn btn-outline-primary btn-sm ml-1">
                                <i class="fa fa-download"></i> Unduh QR
                            </a>
                        </div>
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
                                <td style="width: 25%;"><b>Kode Aset</b></td>
                                <td style="width: 5%;">:</td>
                                <td><span class="badge badge-light font-weight-bold" style="font-size: 16px;">{{ $aset->kd_aset }}</span></td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Penambahan</b></td>
                                <td>:</td>
                                <td>{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <td><b>Kategori</b></td>
                                <td>:</td>
                                <td>{{ optional($aset->kategori)->kategori ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><b>Lokasi</b></td>
                                <td>:</td>
                                <td>{{ optional($aset->lokasi)->lokasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><b>Deskripsi</b></td>
                                <td>:</td>
                                <td>{!! nl2br(e($aset->deskripsi)) !!}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="section-title mt-0">History Perbaikan Aset</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
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
                                    @forelse ($pelaporans as $pelaporan)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pelaporan->judul }}</td>
                                            <td>{{ $pelaporan->deskripsi }}</td>
                                            <td>{{ $pelaporan->feedback ? $pelaporan->feedback->analisis_perbaikan : '-' }}</td>
                                            <td>{{ $pelaporan->updated_at }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada riwayat perbaikan untuk aset ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
