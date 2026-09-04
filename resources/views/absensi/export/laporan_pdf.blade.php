<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Harian</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background: #ddd; }
        h2, h4 { text-align: center; margin: 0; padding: 0; }
    </style>
</head>
<body>

    <h2>Laporan Absensi Harian</h2>
    <h4>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h4>

    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>JAM MASUK</th>
                <th>JAM KELUAR</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensi as $i => $a)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $a->karyawan->name ?? '-' }}</td>
                <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->timezone('Asia/Jakarta')->format('H:i:s') : '-' }}</td>
                <td>{{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->timezone('Asia/Jakarta')->format('H:i:s') : '-' }}</td>
                <td>{{ $a->total_menit ? floor($a->total_menit / 60) . ' Jam ' . ($a->total_menit % 60) . ' Menit' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
