<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan Aset</title>
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
        'title'        => 'Laporan Perbaikan Aset',
        'tanggalCetak' => $tanggalCetak,
        'metaSummary'  => 'Total Perbaikan Selesai: ' . $pelaporans->count() . ' item',
    ])

    <!-- Tabel Data Perbaikan -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 13%;">Judul</th>
                <th style="width: 22%;">Deskripsi</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Nama Aset</th>
                <th style="width: 13%;">Lokasi</th>
                <th style="width: 11%;">Tgl. Pelaporan</th>
                <th style="width: 11%;">Selesai Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pelaporans as $pelaporan)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $pelaporan->judul }}</td>
                    <td class="text-left">{{ $pelaporan->deskripsi }}</td>
                    <td class="text-center">
                        <span class="status-badge">{{ $pelaporan->status }}</span>
                    </td>
                    <td class="text-left">{{ optional($pelaporan->aset)->nm_aset ?? '-' }}</td>
                    <td class="text-left">{{ optional(optional($pelaporan->aset)->lokasi)->lokasi ?? '-' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pelaporan->created_at)->format('d-m-Y H:i') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pelaporan->updated_at)->format('d-m-Y H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Belum ada data perbaikan selesai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @include('partials.laporan-ttd', ['tanggalCetak' => $tanggalCetak])
</body>

</html>
