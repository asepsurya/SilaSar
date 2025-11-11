<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 30px;
            background: #fff;
        }

        /* Header mirip rekening Mandiri */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #ffd700;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }

        .header-left h2 {
            margin: 0;
            font-size: 18px;
            color: #003399;
            font-weight: bold;
        }

        .header-left .subtitle {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
        }

        .header-left .periode {
            font-size: 12px;
            color: #555;
        }

        .header-right {
            text-align: right;
        }

        /* Tabel utama */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th, td {
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #e5e5e5;
        }

        th {
            background-color: #003399;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
        }

        tr.section-title td {
            background: #dbe4f3;
            font-weight: bold;
            color: #003366;
            text-transform: uppercase;
        }

        tr.subtotal td {
            background: #eef2fb;
            font-weight: bold;
        }

        tr.final-total td {
            background: #d1d9f0;
            font-weight: bold;
            border-top: 2px solid #003399;
        }

        td:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
              @php
                    $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id ?? null);
                    $logoPerusahaan = $perusahaan && $perusahaan->logo
                        ? public_path('storage/' . $perusahaan->logo)
                        : public_path('assets/default_logo.png');
                @endphp
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPerusahaan)) }}" alt="Logo Perusahaan" width="100">
            <h2>{{ strtoupper($perusahaan->nama_perusahaan) }}</h2>
            <div class="subtitle">LAPORAN LABA RUGI</div>
            <div class="periode">
                Periode:
                @if($periode === 'tahunan')
                    Tahunan {{ $tahun }}
                @elseif($periode === 'rentang' && $tanggal_awal && $tanggal_akhir)
                    {{ $tanggal_awal }} s.d. {{ $tanggal_akhir }}
                @else
                    {{ \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') }} {{ $tahun }}
                @endif
            </div>
        </div>
       
    </div>

    <!-- Pendapatan -->
    <table>
        <tr class="section-title"><td>Pendapatan</td><td></td></tr>
        @foreach($labaRugi['pendapatan'] ?? [] as $item)
        <tr><td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td></tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Pendapatan</td>
            <td>Rp {{ number_format(($labaRugi['pendapatan'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- HPP -->
    <table>
        <tr class="section-title"><td>Harga Pokok Penjualan</td><td></td></tr>
        @foreach($labaRugi['hpp'] ?? [] as $item)
        <tr><td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td></tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Harga Pokok Penjualan</td>
            <td>Rp {{ number_format(($labaRugi['hpp'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba Kotor -->
    <table>
        <tr class="final-total"><td>Laba Kotor</td>
            <td>Rp {{ number_format($labaRugi['laba_kotor'] ?? 0,2,',','.') }}</td></tr>
    </table>

    <!-- Beban Operasional -->
    <table>
        <tr class="section-title"><td>Beban Operasional</td><td></td></tr>
        @foreach($labaRugi['beban_operasional'] ?? [] as $item)
        <tr><td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td></tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Beban Operasional</td>
            <td>Rp {{ number_format(($labaRugi['beban_operasional'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba Operasional -->
    <table>
        <tr class="final-total"><td>Laba Operasional</td>
            <td>Rp {{ number_format($labaRugi['laba_operasional'] ?? 0,2,',','.') }}</td></tr>
    </table>

    <!-- Pendapatan / Beban Lainnya -->
    <table>
        <tr class="section-title"><td>Pendapatan / Beban Lainnya</td><td></td></tr>
        @foreach($labaRugi['pendapatan_lainnya'] ?? [] as $item)
        <tr><td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td></tr>
        @endforeach
        @foreach($labaRugi['beban_lainnya'] ?? [] as $item)
        <tr><td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>(Rp {{ number_format($item->saldo,2,',','.') }})</td></tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Pendapatan / Beban Lainnya</td>
            <td>Rp {{ number_format((($labaRugi['pendapatan_lainnya'] ?? collect())->sum('saldo')) - (($labaRugi['beban_lainnya'] ?? collect())->sum('saldo')),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba (Rugi) Bersih -->
    <table>
        <tr class="final-total">
            <td>Laba (Rugi) Bersih</td>
            <td>Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,2,',','.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>

</body>
</html>
