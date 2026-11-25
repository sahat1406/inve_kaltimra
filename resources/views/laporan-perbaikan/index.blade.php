@extends('layouts.main')
@section('content')
    <div class="section-header">
        <h1>Laporan Perbaikan Inventaris</h1>
        <div class="ml-auto">
            <button type="button" id="btn-cetak-terpilih" class="btn btn-primary mr-2" disabled>
                <i class="fa fa-check-square"></i> Cetak Terpilih (<span id="selected-count">0</span>)
            </button>
            <a href="/laporan-perbaikan/laporan" target="_blank" class="btn btn-danger">
                <i class="fa fa-print"></i> Cetak Semua
            </a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <p class="text-muted mb-3">
                            Centang laporan perbaikan yang ingin dicetak lalu klik <strong>Cetak Terpilih</strong>, atau klik <strong>Cetak Semua</strong> untuk mencetak seluruh laporan.
                        </p>
                        <div class="table-responsive">
                            <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox" id="select-all" title="Pilih Semua">
                                        </th>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Nama Aset</th>
                                        <th>Lokasi</th>
                                        <th>Tgl. Pelaporan</th>
                                        <th>Selesai Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pelaporans as $pelaporan)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="row-checkbox" value="{{ $pelaporan->id }}">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pelaporan->judul }}</td>
                                            <td>{{ $pelaporan->deskripsi }}</td>
                                            <td><span class="badge badge-success m-2">{{ $pelaporan->status }}</span></td>
                                            <td>{{ optional($pelaporan->aset)->nm_aset ?? '-' }}</td>
                                            <td>{{ optional(optional($pelaporan->aset)->lokasi)->lokasi ?? '-' }}</td>
                                            <td>{{ $pelaporan->created_at }}</td>
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
    </div>

    @include('partials.laporan-selection-script', ['printUrl' => '/laporan-perbaikan/laporan'])
@endsection
