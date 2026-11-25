<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Aset</title>
    @include('partials.laporan-styles')
</head>

<body>
    @php
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $now = \Carbon\Carbon::now();
        $tanggalCetak = $now->day . ' ' . $bulanIndo[$now->month] . ' ' . $now->year;
    @endphp

    @include('partials.laporan-kop', [
        'title'        => 'Laporan Inventaris Aset',
        'tanggalCetak' => $tanggalCetak,
        'metaSummary'  => 'Total Aset: ' . $asets->count() . ' item',
    ])

    <!-- Tabel Data Aset -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 18%;">Kode Aset</th>
                <th style="width: 34%;">Nama Aset</th>
                <th style="width: 17%;">Tgl. Penambahan</th>
                <th style="width: 25%;">Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($asets as $aset)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $aset->kd_aset }}</td>
                    <td class="text-left">{{ $aset->nm_aset }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                    <td class="text-left">{{ optional($aset->lokasi)->lokasi ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data aset inventaris.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('partials.laporan-ttd', ['tanggalCetak' => $tanggalCetak])
</body>

</html>
