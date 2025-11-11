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
            margin: 0;
            padding: 30px;
            background: #fff;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #ffd700; /* garis bawah kuning */
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .header-left {
            text-align: left;
        }

        .header-left h2 {
            margin: 0;
            font-size: 18px;
            color: #003399;
            font-weight: bold;
        }

        .header-left .title {
            font-size: 15px;
            font-weight: bold;
            color: #000;
            margin-top: 3px;
        }

        .header-left .periode {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }

        .header-right img {
            height: 50px;
            object-fit: contain;
        }

        /* TABEL */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 6px 8px;
          
            border-bottom: 1px solid #e5e5e5;
        }

        th {
            text-align: left;
            background-color: #003399;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
           
        }


        tr.subtotal td {
            font-weight: bold;
            background-color: #eef2fb;
        }

        tr.final-total td {
            font-weight: bold;
            background-color: #d1d9f0;
            border-top: 2px solid #003399;
        }

        .balance-ok {
            color: green;
            font-weight: bold;
        }

        .balance-error {
            color: red;
            font-weight: bold;
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            font-size: 11px;
            color: #666;
            text-align: right;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <div class="header">
        <div class="header-left">
             @if(isset($logo) && $logo)
                <img src="{{ $logo }}" alt="Logo" width="100">
            @endif
            <h2>{{ strtoupper($perusahaan->nama_perusahaan ?? 'PERUSAHAAN') }}</h2>
            <div class="title">LAPORAN NERACA SALDO</div>
            <div class="periode">
                Periode:
                @if(isset($periode) && $periode === 'tahunan')
                    Tahunan {{ $tahun ?? date('Y') }}
                @elseif(isset($periode) && $periode === 'rentang' && isset($tanggal_awal) && isset($tanggal_akhir))
                    {{ $tanggal_awal }} s.d. {{ $tanggal_akhir }}
                @else
                    {{ isset($bulan) ? \Carbon\Carbon::createFromFormat('m', $bulan)->translatedFormat('F') : \Carbon\Carbon::now()->translatedFormat('F') }}
                    {{ $tahun ?? date('Y') }}
                @endif
            </div>
        </div>
    </div>

    <!-- TABLE -->
    <table>
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

                {{-- TOTAL --}}
                <tr class="subtotal">
                    <td colspan="2" >Total</td>
                    <td>{{ number_format($data->sum('saldo_debit'), 0, ',', '.') }}</td>
                    <td>{{ number_format($data->sum('saldo_kredit'), 0, ',', '.') }}</td>
                </tr>

                {{-- BALANCE --}}
                <tr class="final-total">
                    <td colspan="2" >Balance</td>
                    <td colspan="2" ">
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
                    </td>
                </tr>
            @else
                <tr>
                    <td colspan="4" style="text-align:center; padding:20px;">Tidak ada data transaksi untuk periode ini</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>

</body>
</html>
