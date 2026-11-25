@php
    $logoPath = public_path('assets/img/logo-pln-np.png');
    $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp

<!-- Kop Surat -->
<table class="kop-table">
    <tr>
        <td class="kop-logo-cell">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Logo PT PLN Nusantara Power" class="kop-logo">
            @endif
        </td>
        <td class="kop-text-cell">
            <div class="kop-company">PT PLN Nusantara Power</div>
            <div class="kop-unit">Unit Pembangkitan Kaltimra</div>
            <div class="kop-sub">Sistem Informasi Inventaris Aset (SIA-PLN) &bull; Pengelolaan &amp; Pemeliharaan Aset</div>
        </td>
        <td class="kop-spacer-cell"></td>
    </tr>
</table>
<div class="kop-line-thick"></div>
<div class="kop-line-thin"></div>

<!-- Judul Laporan -->
<div class="report-header">
    <div class="report-title">{{ $title }}</div>
    <p class="report-meta">Tanggal Cetak: {{ $tanggalCetak }} &nbsp;|&nbsp; {{ $metaSummary }}</p>
</div>
