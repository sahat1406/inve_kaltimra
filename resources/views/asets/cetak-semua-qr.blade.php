<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Semua Label QR Code - Inventaris Aset</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
        }

        .no-print {
            max-width: 960px;
            margin: 0 auto 20px auto;
            background: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        select {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 13px;
        }

        .btn {
            padding: 9px 18px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .btn-dark {
            background-color: #343a40;
            color: #fff;
        }

        /* Grid container for labels */
        .sheet-container {
            max-width: 960px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .label-card {
            background: #fff;
            border: 1.5px solid #222;
            border-radius: 6px;
            padding: 10px 12px;
            page-break-inside: avoid;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        .label-header {
            border-bottom: 1.5px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-align: center;
        }

        .label-header h4 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
            color: #004b87;
        }

        .label-header p {
            margin: 2px 0 0 0;
            font-size: 9px;
            font-weight: bold;
            color: #333;
        }

        .label-body {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qr-box {
            flex-shrink: 0;
            text-align: center;
        }

        .qr-box img {
            width: 100px;
            height: 100px;
            display: block;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .qr-box .kd-text {
            margin-top: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .info-box {
            flex-grow: 1;
            font-size: 11px;
            line-height: 1.4;
        }

        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-box td {
            vertical-align: top;
            padding: 2px 0;
        }

        .info-box td.label-key {
            width: 60px;
            color: #555;
            font-size: 9px;
            text-transform: uppercase;
        }

        .info-box td.val {
            font-weight: bold;
            color: #111;
        }

        .label-footer {
            margin-top: 6px;
            border-top: 1px dashed #ccc;
            padding-top: 3px;
            font-size: 8px;
            color: #777;
            text-align: center;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            .sheet-container {
                max-width: 100%;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .label-card {
                box-shadow: none;
                border: 1.5px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div>
            <h2 style="margin: 0 0 5px 0;">Cetak Lembar QR Code Aset</h2>
            <p style="margin: 0; font-size: 13px; color: #666;">Total: {{ $asets->count() }} label aset</p>
        </div>

        <form method="GET" action="/asets/cetak-semua-qr" class="filter-form">
            <select name="kategori_id" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach ($kategories as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->kategori }}</option>
                @endforeach
            </select>

            <select name="lokasi_id" onchange="this.form.submit()">
                <option value="">-- Semua Lokasi --</option>
                @foreach ($lokasies as $l)
                    <option value="{{ $l->id }}" {{ request('lokasi_id') == $l->id ? 'selected' : '' }}>{{ $l->lokasi }}</option>
                @endforeach
            </select>

            <button type="button" class="btn btn-primary" onclick="window.print()">🖨️ Cetak Semua</button>
            <button type="button" class="btn btn-secondary" onclick="window.close()">✕ Tutup</button>
        </form>
    </div>

    @if ($asets->isEmpty())
        <div style="text-align: center; padding: 50px; color: #888; font-size: 16px;">
            Tidak ada data aset yang ditemukan.
        </div>
    @else
        <div class="sheet-container">
            @foreach ($asets as $aset)
                <div class="label-card">
                    <div class="label-header">
                        <h4>PLN INDONESIA POWER</h4>
                        <p>SISTEM INVENTARIS ASET UPDK KALTIMRA</p>
                    </div>

                    <div class="label-body">
                        <div class="qr-box">
                            <img src="{{ asset('storage/qrcode/' . $aset->kd_aset . '.png') }}" alt="{{ $aset->kd_aset }}">
                            <div class="kd-text">{{ $aset->kd_aset }}</div>
                        </div>

                        <div class="info-box">
                            <table>
                                <tr>
                                    <td class="label-key">Aset</td>
                                    <td class="val" style="font-size: 12px; color: #004b87;">{{ $aset->nm_aset }}</td>
                                </tr>
                                <tr>
                                    <td class="label-key">Kategori</td>
                                    <td class="val">{{ optional($aset->kategori)->kategori ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-key">Lokasi</td>
                                    <td class="val">{{ optional($aset->lokasi)->lokasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="label-key">Tgl Tambah</td>
                                    <td class="val">{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="label-footer">
                        Pindai QR Code untuk pengecekan / pelaporan perbaikan aset
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
