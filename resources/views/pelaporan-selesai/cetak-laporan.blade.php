<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Perbaikan Selesai</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            border: 1px solid black;
            padding: 15px;
        }

        .header {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        td {
            padding: 6px;
            vertical-align: top;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Laporan Perbaikan Selesai</h2>
        </div>

        <hr>

        <table>
            <tr>
                <td><b>Judul Pelaporan</b></td>
                <td>:</td>
                <td>{{ $pelaporan->judul }}</td>
            </tr>
            <tr>
                <td><b>Deskripsi Pelaporan</b></td>
                <td>:</td>
                <td>{{ $pelaporan->deskripsi }}</td>
            </tr>
            <tr>
                <td><b>Nama Aset</b></td>
                <td>:</td>
                <td>{{ optional($pelaporan->aset)->nm_aset }}</td>
            </tr>
            <tr>
                <td><b>Merk</b></td>
                <td>:</td>
                <td>{{ optional($pelaporan->aset->merk)->merk ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Kategori</b></td>
                <td>:</td>
                <td>{{ optional($pelaporan->aset->kategori)->kategori }}</td>
            </tr>
            <tr>
                <td><b>Lokasi</b></td>
                <td>:</td>
                <td>{{ optional($pelaporan->aset->lokasi)->lokasi }}</td>
            </tr>
            <tr>
                <td><b>Tanggal Pelaporan</b></td>
                <td>:</td>
                <td>{{ $pelaporan->created_at }}</td>
            </tr>
            <tr>
                <td><b>Tanggal Selesai Perbaikan</b></td>
                <td>:</td>
                <td>{{ $pelaporan->updated_at }}</td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td><b>Analisis Perbaikan</b></td>
                <td>:</td>
                <td>{{ $feedback?->analisis_perbaikan ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Feedback User</b></td>
                <td>:</td>
                <td>{{ $feedbackReply?->feedback_replies ?? '-' }}</td>
            </tr>
        </table>
    </div>
</body>
</html>
