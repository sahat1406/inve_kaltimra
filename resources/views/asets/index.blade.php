@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Data Aset</h1>
        <div class="ml-auto">
            <a href="/asets/cetak-semua-qr" target="_blank" class="btn btn-dark mr-2"><i class="fa fa-print"></i> Cetak Semua QR Code</a>
            <a href="/asets/create" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Aset</a>
        </div>
    </div>

    <div class="section-body">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Kode Aset</th>
                                        <th>Nama Aset</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($asets as $aset)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><img src="{{ asset('storage/' . $aset->gambar) }}" alt="gambar aset"
                                                    style="width: 120px; height: 120px; object-fit: cover; border-radius: 4px;"></td>
                                            <td><strong>{{ $aset->kd_aset }}</strong></td>
                                            <td>{{ $aset->nm_aset }}</td>
                                            <td>
                                                <a href="/asets/{{ $aset->id }}" class="btn btn-success btn-sm">Detail</a>
                                                <a href="/asets/{{ $aset->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="/asets/{{ $aset->id }}/cetak-qr" target="_blank" class="btn btn-info btn-sm" title="Cetak Label QR Code">
                                                    <i class="fa fa-qrcode"></i> Cetak QR
                                                </a>
                                                <form id="{{ $aset->id }}" action="/asets/{{ $aset->id }}"
                                                    method="POST" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="button" class="btn btn-danger btn-sm swal-confirm"
                                                        data-form="{{ $aset->id }}">Hapus
                                                    </button>
                                                </form>
                                            </td>
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
