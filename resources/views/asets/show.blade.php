@extends('layouts.main')
<<<<<<< HEAD

@section('title', 'Detail Aset - ' . $aset->nm_aset)

@push('css')
<style>
    .table-detail td {
        padding: 10px 8px;
        font-size: 14.5px;
        vertical-align: top;
        border-bottom: 1px solid #f2f4f8;
    }
    .table-detail tr:last-child td {
        border-bottom: none;
    }
    .table-detail .label-col {
        width: 32%;
        font-weight: 600;
        color: #495057;
    }
    .table-detail .sep-col {
        width: 3%;
        color: #6c757d;
    }
    .table-detail .val-col {
        width: 65%;
        color: #1e293b;
    }
    .card-title-custom {
        font-size: 15px;
        font-weight: 700;
        color: #005B9F;
        border-bottom: 2px solid #E2E8F0;
        padding-bottom: 8px;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush
=======
<style>
    td {
        font-size: 16px;
        padding-bottom: 5px;
    }
</style>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8

@section('content')
    <div class="section-header">
        <h1>Detail Aset</h1>
<<<<<<< HEAD
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="/asets">Data Aset</a></div>
            <div class="breadcrumb-item">{{ $aset->kd_aset }}</div>
        </div>
        <div class="ml-auto">
            <a href="/asets/{{ $aset->id }}/cetak-qr" target="_blank" class="btn btn-info mr-2">
                <i class="fa fa-qrcode mr-1"></i> Cetak Label QR
            </a>
            @if (in_array(auth()->user()->role->role, ['manager', 'admin']))
                <a href="/asets/{{ $aset->id }}/edit" class="btn btn-warning mr-2">
                    <i class="fa fa-edit mr-1"></i> Edit Aset
                </a>
            @endif
            <a href="/asets" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Kembali
            </a>
=======
        <div class="ml-auto">
            <a href="/asets" class="btn btn-secondary"><i class="fa fa-back"></i> Kembali</a>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        </div>
    </div>

    <div class="section-body">
        <div class="row">
<<<<<<< HEAD
            {{-- KOLOM KIRI: Foto, QR Code, dan Status --}}
            <div class="col-lg-4 col-md-5">
                {{-- Foto Fisik Aset --}}
                <div class="card card-primary mb-3">
                    <div class="card-header py-2">
                        <h4><i class="fas fa-camera text-primary mr-1"></i> Foto Fisik Aset</h4>
                    </div>
                    <div class="card-body text-center p-3">
                        <img src="{{ asset('storage/' . $aset->gambar) }}"
                             alt="{{ $aset->nm_aset }}"
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 240px; width: 100%; object-fit: cover;"
                             onerror="this.src='{{ asset('assets/img/avatar/avatar-1.png') }}';">
                    </div>
                </div>

                {{-- QR Code / Barcode Inventaris --}}
                <div class="card card-info mb-3">
                    <div class="card-header py-2">
                        <h4><i class="fas fa-qrcode text-info mr-1"></i> QR Code Inventaris</h4>
                    </div>
                    <div class="card-body text-center p-3">
                        <img src="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}"
                             alt="QR Code {{ $aset->kd_aset }}"
                             style="width: 180px; height: 180px; margin: 0 auto;"
                             class="img-thumbnail shadow-sm mb-2">
                        <div class="font-weight-bold text-dark mb-2" style="font-size: 15px; letter-spacing: 0.5px;">
                            {{ $aset->kd_aset }}
                        </div>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="/asets/{{ $aset->id }}/cetak-qr" target="_blank" class="btn btn-sm btn-info mr-1">
                                <i class="fa fa-print mr-1"></i> Cetak
                            </a>
                            <a href="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}"
                               download="QR_{{ $aset->kd_aset }}_{{ \Illuminate\Support\Str::slug($aset->nm_aset) }}.png"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-download mr-1"></i> Unduh
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Status Operasional & Ringkasan --}}
                @php
                    $pendingPelaporan = $pelaporans->firstWhere('status', 'menunggu');
                    $repairPelaporan = $pelaporans->firstWhere('status', 'sedang diperbaiki');
                    $activePelaporan = $repairPelaporan ?? $pendingPelaporan;
                @endphp
                <div class="card mb-3">
                    <div class="card-header py-2">
                        <h4><i class="fas fa-heartbeat text-danger mr-1"></i> Status Terkini</h4>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 font-weight-bold">Kondisi Operasional:</small>
                            @if ($repairPelaporan)
                                <span class="badge badge-primary px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                    <i class="fas fa-tools mr-1"></i> Sedang Dalam Perbaikan
                                </span>
                            @elseif ($pendingPelaporan)
                                <span class="badge badge-warning px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Ada Laporan Kerusakan
                                </span>
                            @else
                                <span class="badge badge-success px-3 py-2 font-weight-bold" style="font-size: 13px;">
                                    <i class="fas fa-check-circle mr-1"></i> Baik / Normal Beroperasi
                                </span>
                            @endif
                        </div>

                        <ul class="list-group list-group-flush small text-secondary">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span>Total Pelaporan</span>
                                <span class="badge badge-light font-weight-bold">{{ $pelaporans->count() }} kali</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span>Perbaikan Selesai</span>
                                <span class="badge badge-success">{{ $pelaporans->where('status', 'selesai')->count() }} kali</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span>Unit Instansi</span>
                                <span class="font-weight-bold text-dark">PLN NP UP Kaltimra</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Semua Informasi Detail Aset --}}
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-cube text-primary mr-1"></i>
                            {{ $aset->nm_aset }}
                        </h4>
                        <span class="badge badge-primary font-weight-bold px-3 py-2" style="font-size: 14px;">
                            {{ $aset->kd_aset }}
                        </span>
                    </div>

                    <div class="card-body">
                        <table class="table-detail w-100 mb-4">
                            <tbody>
                                <tr>
                                    <td class="label-col">Nama Aset</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">{{ $aset->nm_aset }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Kode Aset</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">{{ $aset->kd_aset }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Jumlah Aset</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">1 {{ $aset->satuan ?: 'unit' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Tanggal Penambahan</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">
                                        {{ $aset->tgl_penambahan ? \Carbon\Carbon::parse($aset->tgl_penambahan)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-col">Kategori</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">{{ optional($aset->kategori)->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Lokasi</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">{{ optional($aset->lokasi)->lokasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Deskripsi</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col" style="white-space: pre-line;">{{ $aset->deskripsi ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-col">Tanggal Pengakuan Awal</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">
                                        {{ $aset->tgl_aku ? \Carbon\Carbon::parse($aset->tgl_aku)->translatedFormat('d F Y') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-col">Keterangan Lokasi</td>
                                    <td class="sep-col">:</td>
                                    <td class="val-col">{{ $aset->ket_lok ?: '-' }}</td>
                                </tr>
                            </tbody>
                        </table>

                        {{-- RIWAYAT PELAPORAN & PEMELIHARAAN ASET --}}
                        <div class="card-title-custom mt-4">
                            <i class="fas fa-tools"></i> Riwayat Pelaporan & Pemeliharaan Aset
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%;">No</th>
                                        <th style="width: 18%;">Tanggal Lapor</th>
                                        <th style="width: 18%;">Pelapor</th>
                                        <th style="width: 25%;">Titik & Masalah</th>
                                        <th style="width: 16%;">Status</th>
                                        <th style="width: 18%;">Analisis Perbaikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelaporans as $pelaporan)
                                        <tr>
                                            <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="font-weight-bold text-dark">{{ $pelaporan->created_at->format('d-m-Y') }}</div>
                                                <small class="text-muted">{{ $pelaporan->created_at->format('H:i') }} WIB</small>
                                            </td>
                                            <td>
                                                <i class="fas fa-user-circle text-secondary mr-1"></i>
                                                {{ optional($pelaporan->user)->name ?? 'User / Pegawai' }}
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-primary">{{ $pelaporan->judul }}</div>
                                                <small class="text-muted d-block">{{ $pelaporan->deskripsi }}</small>
                                            </td>
                                            <td>
                                                @if ($pelaporan->status == 'menunggu')
                                                    <span class="badge badge-warning">Menunggu</span>
                                                @elseif ($pelaporan->status == 'sedang diperbaiki')
                                                    <span class="badge badge-primary">Sedang Diperbaiki</span>
                                                @elseif ($pelaporan->status == 'selesai')
                                                    <span class="badge badge-success">Selesai</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $pelaporan->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($pelaporan->feedback && $pelaporan->feedback->komentar)
                                                    <span class="text-dark">{{ $pelaporan->feedback->komentar }}</span>
                                                @elseif ($pelaporan->feedback && !empty($pelaporan->feedback->analisis_perbaikan))
                                                    <span class="text-dark">{{ $pelaporan->feedback->analisis_perbaikan }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                                Aset ini belum pernah memiliki riwayat pelaporan kerusakan. Kondisi aset dalam status baik.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
=======
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <label for="gambar"><b>Gambar Aset</b></label>
                        <img src="{{ asset('storage/' . $aset->gambar) }}" alt="gambar aset" class="card-img-top">
                    </div>

                    <hr>
                    <div class="card-body">
                        <label for="gambar"><b>Barcode Aset</b></label>
                        <img src="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}" alt="qr-code"
                            style="width: 250px; height: 250px; display: block; margin: 0 auto;">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><b>{{ $aset->nm_aset }}</b></div>
                        <hr>
                        <table style="width:100%">
                            <tr>
                                <td><b>Kode Aset</b></td>
                                <td>:</td>
                                <td>{{ $aset->kd_aset }}</td>
                            </tr>
                            <tr>
                                <td><b>Tanggal Penambahan</b></td>
                                <td>:</td>
                                <td>{{ $aset->tgl_penambahan }}</td>
                            </tr>
                            <tr>
                                <td><b>Kategori</b></td>
                                <td>:</td>
                                <td>{{ $aset->kategori->kategori }}</td>
                            </tr>
                            {{-- <tr>
                                <td><b>Merek</b></td>
                                <td>:</td>
                                <td>{{ $aset->merk->merk }}</td>
                            </tr> --}}
                            <tr>
                                <td><b>Lokasi</b></td>
                                <td>:</td>
                                <td>{{ $aset->lokasi->lokasi }}</td>
                            </tr>
                            <tr>
                                <td><b>Deskripsi</b></td>
                                <td>:</td>
                                <td>{!! $aset->deskripsi !!}</td>
                            </tr>
                        </table>
                    </div>
                    <hr>
                    <div class="card-body">
                        <div class="section-title mt-0">History Perbaikan Aset</div>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Pelaporan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Analisis Perbaikan</th>
                                    <th scope="col">Tanggal Perbaikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelaporans as $pelaporan)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pelaporan->judul }}</td>
                                        <td>{{ $pelaporan->deskripsi }}</td>
                                        <td>{{ $pelaporan->feedback ? $pelaporan->feedback->analisis_perbaikan : '-' }}</td>
                                        <td>{{ $pelaporan->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
