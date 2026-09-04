<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi — Rekap Per Karyawan (Range)</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background: #eee; font-weight: bold; }
        h2, h4 { text-align: center; margin: 0; padding: 0; }
    </style>
</head>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = base64_encode(file_get_contents($logoPath));
    @endphp
    <div style="text-align: center; margin-bottom: 10px;">
        <img src="data:image/png;base64,{{ $logoData }}" alt="Logo" style="width: 120px;">
    </div>
    <h2>Laporan Absensi — Range Tanggal</h2>
    <h4>
        Dari: {{ $start->translatedFormat('d F Y') }}  
        Sampai: {{ $end->translatedFormat('d F Y') }}
    </h4>

    <br>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Total Hari Bekerja</th>
                <th>Total Jam Bekerja</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($rekap as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->nama }}</td>
                <td>{{ $r->total_hari }} Hari</td>
                <td>{{ number_format($r->total_jam, 2) }} Jam</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
