<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Detail Per Karyawan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 25px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background: #ddd; }
        h2, h3, h4, h5 { text-align: center; margin: 0; padding: 0; }
    </style>
</head>
<body>

<h2>Laporan Detail Karyawan</h2>
<h3>{{ $karyawan->name }}</h3>
<h4>Periode: {{ $start->translatedFormat('d F Y') }} - {{ $end->translatedFormat('d F Y') }}</h4>
<h5>Total Hari Bekerja: {{ $totalHari }} Hari</h5>

<br>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Total Waktu</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $i => $a)
        @php
            $jam = floor($a->total_menit / 60);
            $menit = $a->total_menit % 60;
        @endphp

        <tr>
            <td>{{ $i + 1 }}</td>

            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>

            <!-- HARI BAHASA INDONESIA -->
            <td>{{ \Carbon\Carbon::parse($a->tanggal)->locale('id')->translatedFormat('l') }}</td>

            <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i:s') : '-' }}</td>
            <td>{{ $a->jam_keluar ? \Carbon\Carbon::parse($a->jam_keluar)->format('H:i:s') : '-' }}</td>

            <td>{{ $jam }} Jam {{ $menit }} Menit</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br><br>

<h3>Total Jam Bekerja Keseluruhan: {{ $totalJamAkhir }}</h3>

</body>
</html>
