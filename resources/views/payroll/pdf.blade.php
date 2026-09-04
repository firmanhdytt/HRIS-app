<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - {{ $employee->name }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 6px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
        }

        .company-info {
            font-size: 11px;
        }

        .title-box {
            text-align: center;
            margin: 12px 0;
            font-size: 15px;
            font-weight: bold;
        }

        .slip-meta {
            text-align: center;
            margin-top: -4px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th {
            background: #e6e6e6;
            font-weight: bold;
            border: 1px solid #000;
            padding: 6px;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
        }

        .red {
            color: #c70000;
        }

        .total-row td {
            background: #f3f3f3;
            font-weight: bold;
            font-size: 13px;
        }

        .signature-box {
            margin-top: 30px;
            width: 100%;
        }

        .signature-col {
            width: 50%;
            text-align: center;
            font-size: 12px;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>

<body>

    @php
        // Logo base64 agar tampil di PDF
        $logo = base64_encode(file_get_contents(public_path('images/logo.png')));

        // dipakai di komponen gaji
        function hm_str($min)
        {
            if ($min <= 0) return '—';
            $j = intdiv($min, 60);
            $m = $min % 60;
            return trim(($j > 0 ? $j . ' jam ' : '') . ($m > 0 ? $m . ' menit' : ''));
        }

        // dipakai di tabel rincian absensi
        if (!function_exists('hm_pdf')) {
            function hm_pdf($min) {
                if ($min <= 0) return '—';
                $h = intdiv($min, 60);
                $m = $min % 60;
                return trim(($h>0?$h.' jam ':'').($m>0?$m.' menit':'')) ?: '—';
            }
        }
    @endphp

    <!-- HEADER -->
    <div class="header">
        <img src="data:image/png;base64,{{ $logo }}" class="logo">
        <div class="company-name">P.IRT TUNAS KELAPA</div>
        <div class="company-info">• No: 2.09.1275.01.01247.02 • Jl. B.Zein Hamid Medan Johor </div>
    </div>

    <div class="title-box">SLIP GAJI KARYAWAN</div>
    <div class="slip-meta">Periode {{ $from }} s/d {{ $to }}</div>

    <!-- IDENTITAS -->
    <table>
        <tr>
            <th style="width: 180px;">Nama Karyawan</th>
            <td>{{ strtoupper($employee->name) }}</td>
        </tr>
        <tr>
            <th>ID Karyawan</th>
            <td>{{ $employee->employee_id }}</td>
        </tr>
    </table>

    <!-- PERHITUNGAN GAJI -->
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Komponen</th>
                <th style="width: 40%;">Perhitungan</th>
                <th style="width: 30%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td>Gaji Pokok</td>
                <td>{{ $bd['total_hari_bekerja'] }} hari × Rp {{ number_format($gajiPerHari, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($bd['gaji_pokok_total'], 0, ',', '.') }}</td>
            </tr>

            <tr>
                <td>Lembur</td>
                <td>
                    @if($bd['menit_lembur'] > 0)
                        {{ hm_str($bd['menit_lembur']) }} × Rp {{ number_format($tarifLembur, 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($bd['menit_lembur'] > 0)
                        Rp {{ number_format($bd['gaji_lembur_total'], 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr>
                <td>Kerajinan</td>
                <td>
                    @if($bd['menit_kerajinan'] > 0)
                        {{ hm_str($bd['menit_kerajinan']) }} × Rp {{ number_format($setting->gaji_pokok / 8, 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if($bd['menit_kerajinan'] > 0)
                        Rp {{ number_format($bd['kerajinan_total'], 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr>
                <td>Potongan Terlambat</td>
                <td>
                    @if(($setting->potongan_mode ?? 'per_minute') === 'per_minute')
                        @if($bd['total_keterlambatan_menit'] > 0)
                            {{ $bd['total_keterlambatan_menit'] }} menit × <span class="red">Rp -{{ number_format($tarifTelat, 0, ',', '.') }}</span>
                        @else
                            —
                        @endif
                    @else
                        @if($bd['jumlah_hari_terlambat'] > 0)
                            {{ $bd['jumlah_hari_terlambat'] }} kali × <span class="red">Rp -{{ number_format($tarifTelat, 0, ',', '.') }}</span>
                        @else
                            —
                        @endif
                    @endif
                </td>
                <td>
                    @if($bd['potongan_total'] > 0)
                        <span class="red">Rp -{{ number_format($bd['potongan_total'], 0, ',', '.') }}</span>
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr>
                <td>Pinjaman</td>
                <td>—</td>
                <td>
                    @if($bd['pinjaman'] > 0)
                        <span class="red">Rp -{{ number_format($bd['pinjaman'], 0, ',', '.') }}</span>
                    @else
                        —
                    @endif
                </td>
            </tr>

            <tr>
                <td>Bonus</td>
                <td>—</td>
                <td>
                    @if($bd['bonus'] > 0)
                        Rp {{ number_format($bd['bonus'], 0, ',', '.') }}
                    @else
                        —
                    @endif
                </td>
            </tr>

        </tbody>
    </table>
    <table>
        <tr class="total-row">
            <td colspan="2">TOTAL GAJI</td>
            <td>Rp {{ number_format($calc['total_gaji'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="title-box">RINCIAN ABSENSI</div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Lembur</th>
                <th>Terlambat</th>
                <th>Kerajinan</th>
            </tr>
        </thead>

        <tbody>
            @foreach($calc['raw_absensi'] as $a)

                @php
                    $jm = $a->jam_masuk ? \Carbon\Carbon::parse("{$a->tanggal} {$a->jam_masuk}") : null;
                    $jk = $a->jam_keluar ? \Carbon\Carbon::parse("{$a->tanggal} {$a->jam_keluar}") : null;

                    $jmFormatted = $jm ? $jm->format('H:i') : '—';
                    $jkFormatted = $jk ? $jk->format('H:i') : '—';

                    $base0800 = \Carbon\Carbon::parse("{$a->tanggal} 08:00:00");
                    $base0815 = \Carbon\Carbon::parse("{$a->tanggal} 08:05:00");
                    $base1700 = \Carbon\Carbon::parse("{$a->tanggal} 17:00:00");

                    $lembur = ($jk && $jk->gt($base1700)) ? $base1700->diffInMinutes($jk) : 0;
                    $telat  = ($jm && $jm->gt($base0815)) ? $base0815->diffInMinutes($jm) : 0;
                    $rajin  = ($jm && $jm->lt($base0800)) ? $jm->diffInMinutes($base0800) : 0;

                    $hari = \Carbon\Carbon::parse($a->tanggal)->translatedFormat('l');
                @endphp

                <tr>
                    <td>{{ $a->tanggal }}</td>
                    <td>{{ $hari }}</td>
                    <td style="text-align:center;">{{ $jmFormatted }}</td>
                    <td style="text-align:center;">{{ $jkFormatted }}</td>
                    <td style="text-align:center;">{{ hm_pdf($lembur) }}</td>
                    <td style="text-align:center;">{{ hm_pdf($telat) }}</td>
                    <td style="text-align:center;">{{ hm_pdf($rajin) }}</td>
                </tr>

            @endforeach
        </tbody>
    </table>

    

    {{-- <table class="signature-box">
        <tr>
            <td class="signature-col">
                <br><br><br><br>_____________________________<br>
                IBUK BOS
            </td>
            <td class="signature-col">
                <br><br><br><br>_____________________________<br>
                {{ $employee->name }}
            </td>
        </tr>
    </table>

    <div class="footer">
        © {{ date('Y') }} P.IRT TUNAS KELAPA — Dokumen Resmi
    </div> --}}

</body>

</html>
