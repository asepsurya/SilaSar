<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca Saldo</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 30px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header-left {
            flex: 2;
        }
        .header-left img {
            height: 60px;
            object-fit: contain;
            margin-bottom: 20px;
        }
        .header-center {

            flex: 2;
            text-align: left;
        }
        .header-center h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header-center .title {
            font-size: 16px;
            margin: 5px 0;
        }
        .header-center .periode {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .header-right {
            flex: 1;
            text-align: right;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .table td:nth-child(1) {
            width: 15%;
            text-align: center;
        }
        .table td:nth-child(2) {
            width: 45%;
        }
        .table td:nth-child(3), .table td:nth-child(4) {
            width: 20%;
            text-align: right;
        }
        .table .subtotal {
            font-weight: bold;
            background: #fafafa;
        }
        .table .final-total {
            font-weight: bold;
            background: #e9e9e9;
        }
        .balance-ok {
            color: green;
            font-weight: bold;
        }
        .balance-error {
            color: red;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-left">
            {{-- logo perusahaan --}}
            @if(isset($logo) && $logo)
                <img src="{{ $logo }}" alt="Logo">
            @endif
        </div>
        <div class="header-center">
            <h2>{{ isset($perusahaan) ? $perusahaan->nama_perusahaan : 'Perusahaan' }}</h2>
            <div class="title">Laporan Neraca Saldo</div>
            <div class="periode">
                Periode:
                @if(isset($periode) && $periode === 'tahunan')
                    Tahunan {{ isset($tahun) ? $tahun : date('Y') }}
                @elseif(isset($periode) && $periode === 'rentang' && isset($tanggal_awal) && isset($tanggal_akhir))
                    {{ $tanggal_awal }} s.d. {{ $tanggal_akhir }}
                @else
                    {{ isset($bulan) ? \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') : \Carbon\Carbon::now()->translatedFormat('F') }} {{ isset($tahun) ? $tahun : date('Y') }}
                @endif
            </div>
        </div>
        <div class="header-right">
            {{-- Kosongkan untuk balance --}}
        </div>
    </div>

    <!-- Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Akun</th>
                <th>Saldo Debit</th>
                <th>Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data) && $data->count() > 0)
                @foreach($data as $akun)
                <tr>
                    <td>{{ $akun->kode_akun }}</td>
                    <td>{{ $akun->nama_akun }}</td>
                    <td>{{ number_format($akun->saldo_debit, 0, ',', '.') }}</td>
                    <td>{{ number_format($akun->saldo_kredit, 0, ',', '.') }}</td>
                </tr>
                @endforeach

                {{-- Total --}}
                <tr class="subtotal">
                    <td colspan="2" style="text-align: center; font-weight: bold;">Total</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($data->sum('saldo_debit'), 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($data->sum('saldo_kredit'), 0, ',', '.') }}</td>
                </tr>

                {{-- Balance --}}
                <tr class="final-total">
                    <td colspan="2" style="text-align: center; font-weight: bold;">Balance</td>
                    <td colspan="2" style="text-align: center; font-weight: bold;">
                        @php
                            $totalDebit = $data->sum('saldo_debit');
                            $totalKredit = $data->sum('saldo_kredit');
                            $selisih = $totalDebit - $totalKredit;
                        @endphp
                        @if($totalDebit == $totalKredit)
                            <span class="balance-ok">Seimbang</span>
                        @else
                            <span class="balance-error">Tidak Seimbang (Selisih: {{ number_format($selisih, 0, ',', '.') }})</span>
                        @endif
            @else
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">Tidak ada data transaksi untuk periode ini</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Laporan Neraca Saldo - {{ isset($perusahaan) ? $perusahaan->nama_perusahaan : 'Perusahaan' }}
    </div>

</body>
</html>
