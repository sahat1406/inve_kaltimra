<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label QR Code - {{ $aset->kd_aset }} ({{ $aset->nm_aset }})</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .no-print {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        /* Label Sticker Container */
        .label-card {
            width: 90mm;
            min-height: 55mm;
            background: #fff;
            border: 2px solid #222;
            border-radius: 8px;
            padding: 10px 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .label-header {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
            text-align: center;
        }

        .label-header h3 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #004b87;
        }

        .label-header p {
            margin: 2px 0 0 0;
            font-size: 10px;
            font-weight: bold;
            color: #333;
            letter-spacing: 1px;
        }

        .label-body {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .qr-box {
            flex-shrink: 0;
            text-align: center;
        }

        .qr-box img {
            width: 120px;
            height: 120px;
            display: block;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .qr-box .kd-text {
            margin-top: 4px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
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
            font-size: 10px;
            text-transform: uppercase;
        }

        .info-box td.val {
            font-weight: bold;
            color: #111;
        }

        .label-footer {
            margin-top: 6px;
            border-top: 1px dashed #ccc;
            padding-top: 4px;
            font-size: 9px;
            color: #777;
            text-align: center;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
                margin: 0;
                display: block;
            }

            .no-print {
                display: none !important;
            }

            .label-card {
                box-shadow: none;
                border: 1.5px solid #000;
                page-break-inside: avoid;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn btn-primary" onclick="window.print()">🖨️ Cetak Label</button>
        <button class="btn btn-secondary" onclick="window.close()">✕ Tutup</button>
    </div>

    <div class="label-card">
        <div class="label-header">
            <h3>PLN NP UP KALTIMRA</h3>
            <p>SIA KALTIMRA</p>
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
                        <td class="val" style="font-size: 13px; color: #004b87;">{{ $aset->nm_aset }}</td>
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
</body>
</html>
