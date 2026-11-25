<script>
    $(document).ready(function() {
        var table = $('#table_id').DataTable({
            columnDefs: [
                { orderable: false, searchable: false, targets: 0 }
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
            $('#btn-cetak-terpilih').prop('disabled', selectedIds.length === 0);
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

        $('#btn-cetak-terpilih').on('click', function() {
            var ids = getSelectedIds();
            if (ids.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum ada data dipilih',
                    text: 'Silakan centang minimal satu data yang ingin dicetak.'
                });
                return;
            }
            window.open('{{ $printUrl }}?ids=' + encodeURIComponent(ids.join(',')), '_blank');
        });
    });
</script>
