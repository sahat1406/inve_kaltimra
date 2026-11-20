<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Inventaris</title>
    <style>
        .container {
            text-align: center;
            margin: auto;
        }

        .column {
            text-align: center;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        table {
            margin: auto;
            width: 100%;
        }

        tr {
            text-align: left;
        }

        table,
        th,
        td {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th,
        td {
            padding: 5px;
        }

        th {
            background-color: gainsboro;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="column">
                <h2>Laporan Inventaris Aset</h2>
            </div>
            <div class="col">
                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Aset</th>
                            <th>Nama Aset</th>
                            <th>Tgl. Penambahan</th>
                            <th>Lokasi</th>
                            {{-- <th>Merek</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($asets as $aset)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $aset->kd_aset }}</td>
                                <td>{{ $aset->nm_aset }}</td>
                                <td>{{ \Carbon\Carbon::parse($aset->tgl_penambahan)->format('d-m-Y') }}</td>
                                <td>{{ $aset->lokasi->lokasi }}</td>
                                {{-- <td>{{ $aset->merk->merk }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
