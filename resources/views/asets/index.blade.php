@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Data Aset</h1>
        <div class="ml-auto">
            <a href="/asets/create" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah
                Aset</a>
        </div>
    </div>

    <div class="section-body">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
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
                                                    style="width: 150px"; height="150px"></td>
                                            <td>{{ $aset->kd_aset }}</td>
                                            <td>{{ $aset->nm_aset }}</td>
                                            <td>
                                                <a href="/asets/{{ $aset->id }}" class="btn btn-success">Detail</a>
                                                <a href="/asets/{{ $aset->id }}/edit" class="btn btn-warning">Edit</a>
                                                <form id="{{ $aset->id }}" action="/asets/{{ $aset->id }}"
                                                    method="POST" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <div class="btn btn-danger swal-confirm"
                                                        data-form="{{ $aset->id }}">Hapus
                                                    </div>
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
