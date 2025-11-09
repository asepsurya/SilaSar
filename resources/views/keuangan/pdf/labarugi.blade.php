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
            margin: 30px;
        }
     .header {
    display: flex;
    justify-content: space-between; /* kiri - kanan */
    align-items: center;            /* sejajarin vertikal */
   margin-bottom: 20px;

}

.header-left h2 {
    margin: 0;
    font-size: 20px;
}
.header-left .periode {
    font-size: 14px;
    color: #666;
}

.header-right img {
    height: 50px;
    object-fit: contain;
}
        .periode {
            font-size: 12px;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .table td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
        }
        .table .section-title {
            font-weight: bold;
            background: #f3f3f3;
            text-transform: uppercase;
        }
        .table .subtotal {
            font-weight: bold;
            background: #fafafa;
        }
        .table td:first-child {
            width: 70%;
        }
        .table td:last-child {
            text-align: right;
            white-space: nowrap;
        }
        .final-total {
            font-weight: bold;
            background: #e9e9e9;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #666;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
         <div class="header-right">
            {{-- logo perusahaan --}}
          @if($logo)
            <img src="{{ $logo }}" alt="Logo" style="height:100px;">
        @endif
        </div>
        <div class="header-left">
            <h2>{{ $perusahaan->nama_perusahaan }}</h2>
            <div>Laporan Laba Rugi</div>
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
    <table class="table">
        <tr class="section-title">
            <td>Pendapatan</td><td></td>
        </tr>
        @foreach($labaRugi['pendapatan'] ?? [] as $item)
        <tr>
            <td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Pendapatan</td>
            <td>Rp {{ number_format(($labaRugi['pendapatan'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- HPP -->
    <table class="table">
        <tr class="section-title">
            <td>Harga Pokok Penjualan</td><td></td>
        </tr>
        @foreach($labaRugi['hpp'] ?? [] as $item)
        <tr>
            <td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Harga Pokok Penjualan</td>
            <td>Rp {{ number_format(($labaRugi['hpp'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba Kotor -->
    <table class="table">
        <tr class="final-total">
            <td>Laba Kotor</td>
            <td>Rp {{ number_format($labaRugi['laba_kotor'] ?? 0,2,',','.') }}</td>
        </tr>
    </table>

    <!-- Beban Operasional -->
    <table class="table">
        <tr class="section-title">
            <td>Beban Operasional</td><td></td>
        </tr>
        @foreach($labaRugi['beban_operasional'] ?? [] as $item)
        <tr>
            <td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Beban Operasional</td>
            <td>Rp {{ number_format(($labaRugi['beban_operasional'] ?? collect())->sum('saldo'),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba Operasional -->
    <table class="table">
        <tr class="final-total">
            <td>Laba Operasional</td>
            <td>Rp {{ number_format($labaRugi['laba_operasional'] ?? 0,2,',','.') }}</td>
        </tr>
    </table>

    <!-- Pendapatan / Beban Lainnya -->
    <table class="table">
        <tr class="section-title">
            <td>Pendapatan / Beban Lainnya</td><td></td>
        </tr>
        @foreach($labaRugi['pendapatan_lainnya'] ?? [] as $item)
        <tr>
            <td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>Rp {{ number_format($item->saldo,2,',','.') }}</td>
        </tr>
        @endforeach
        @foreach($labaRugi['beban_lainnya'] ?? [] as $item)
        <tr>
            <td>{{ $item->kode_akun ?? '' }} {{ $item->nama_akun }}</td>
            <td>(Rp {{ number_format($item->saldo,2,',','.') }})</td>
        </tr>
        @endforeach
        <tr class="subtotal">
            <td>Total Pendapatan / Beban Lainnya</td>
            <td>Rp {{ number_format((($labaRugi['pendapatan_lainnya'] ?? collect())->sum('saldo')) - (($labaRugi['beban_lainnya'] ?? collect())->sum('saldo')),2,',','.') }}</td>
        </tr>
    </table>

    <!-- Laba (Rugi) Bersih -->
    <table class="table">
        <tr class="final-total">
            <td>Laba (Rugi) Bersih</td>
            <td>Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,2,',','.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Laporan Laba Rugi : {{ $periode ?? '' }}
    </div>

</body>
</html>
