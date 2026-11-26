@extends('layouts.main')

@section('content')
    <div class="section-header">
        <h1>Data Aset</h1>
        <div class="ml-auto">
            <button type="button" id="btn-hapus-terpilih" class="btn btn-danger mr-2" disabled>
                <i class="fa fa-trash"></i> Hapus Aset (<span id="selected-count">0</span>)
            </button>
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
        <form id="bulk-delete-form" action="/asets/hapus-terpilih" method="POST" class="d-none">
            @method('delete')
            @csrf
            <div id="bulk-delete-inputs"></div>
        </form>
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox" id="select-all" title="Pilih Semua">
                                        </th>
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
                                            <td class="text-center">
                                                <input type="checkbox" class="row-checkbox" value="{{ $aset->id }}">
                                            </td>
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
            var table = $('#table_id').DataTable({
                columnDefs: [
                    { orderable: false, searchable: false, targets: [0, 2, 5] }
                ],
                order: [[1, 'asc']]
            });

            function getSelectedIds() {
                var ids = [];
                table.$('.row-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });
                return ids;
            }

            function syncSelectionState() {
                var selectedIds = getSelectedIds();
                var visibleCheckboxes = $(table.rows({ search: 'applied' }).nodes()).find('.row-checkbox');
                var visibleChecked = visibleCheckboxes.filter(':checked').length;

                $('#selected-count').text(selectedIds.length);
                $('#btn-hapus-terpilih').prop('disabled', selectedIds.length === 0);
                $('#select-all').prop('checked', visibleCheckboxes.length > 0 && visibleChecked === visibleCheckboxes.length);
            }

            $('#select-all').on('change', function() {
                var isChecked = $(this).is(':checked');
                $(table.rows({ search: 'applied' }).nodes()).find('.row-checkbox').prop('checked', isChecked);
                syncSelectionState();
            });

            $('#table_id').on('change', '.row-checkbox', function() {
                syncSelectionState();
            });

            table.on('draw', function() {
                syncSelectionState();
            });

            $('#btn-hapus-terpilih').on('click', function() {
                var ids = getSelectedIds();
                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum ada data dipilih',
                        text: 'Silakan centang minimal satu aset yang ingin dihapus.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Hapus Aset Terpilih?',
                    text: 'Anda akan menghapus ' + ids.length + ' data aset terpilih. Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var container = $('#bulk-delete-inputs');
                        container.empty();
                        ids.forEach(function(id) {
                            container.append($('<input>', {
                                type: 'hidden',
                                name: 'ids[]',
                                value: id
                            }));
                        });
                        $('#bulk-delete-form').submit();
                    }
                });
            });
        });
    </script>
@endsection
