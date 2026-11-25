@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Laporan Inventaris Aset</h1>
        <div class="ml-auto">
            <button type="button" id="btn-cetak-terpilih" class="btn btn-primary mr-2" disabled>
                <i class="fa fa-check-square"></i> Cetak Terpilih (<span id="selected-count">0</span>)
            </button>
            <a href="/laporan-inventaris/laporan" target="_blank" class="btn btn-danger">
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
                            Centang aset yang ingin dicetak lalu klik <strong>Cetak Terpilih</strong>, atau klik <strong>Cetak Semua</strong> untuk mencetak seluruh aset.
                        </p>
                        <div class="table-responsive">
                            <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox" id="select-all" title="Pilih Semua">
                                        </th>
                                        <th>No</th>
                                        <th>Kode Aset</th>
                                        <th>Nama Aset</th>
                                        <th>Tgl. Penambahan</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asets as $aset)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="row-checkbox" value="{{ $aset->id }}">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $aset->kd_aset }}</td>
                                            <td>{{ $aset->nm_aset }}</td>
                                            <td>{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                                            <td>{{ optional($aset->lokasi)->lokasi ?? '-' }}</td>
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

    @include('partials.laporan-selection-script', ['printUrl' => '/laporan-inventaris/laporan'])
@endsection
