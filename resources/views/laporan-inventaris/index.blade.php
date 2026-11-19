@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Laporan Inventaris Aset</h1>
        <div class="ml-auto">
            <a href="/laporan-inventaris/laporan" class="btn btn-danger"><i class="fa fa-print"></i> Cetak Laporan</a>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                    <tr>
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $aset->kd_aset }}</td>
                                            <td>{{ $aset->nm_aset }}</td>
                                            <td>{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                                            <td>{{ $aset->lokasi->lokasi }}</td>
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

    <!-- Datatables Jquery -->
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        })
    </script>
@endsection
