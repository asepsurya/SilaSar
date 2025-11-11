<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 40px;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #04458b;
            padding-bottom: 10px;
        }

        .header-left img {
            height: 55px;
            width: auto;
        }

        .header-title {
            text-align: right;
        }

        .header-title h2 {
            margin: 0;
            font-size: 18px;
            color: #000000;
        }

        .header-title span {
            font-size: 11px;
            color: #666;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
        }

        th {
            background-color: #e8f1ff;
            color: #004a99;
            font-weight: 600;
            text-align: left;
        }

        td.text-right {
            text-align: right;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @php
                $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id ?? null);
                $logoPerusahaan = $perusahaan && $perusahaan->logo
                    ? public_path('storage/' . $perusahaan->logo)
                    : public_path('assets/default_logo.png');
            @endphp
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPerusahaan)) }}" alt="Logo Perusahaan">
             <h2>LAPORAN KEUANGAN</h2>
            <span>Periode: {{ $periode ?? 'Semua Data' }}</span><br>
            <span>Dicetak: {{ now()->format('d/m/Y H:i') }}</span><br>
            <span>(Dalam Rupiah)</span>
        </div>

    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">Akun</th>
                <th style="width: 25%">Deskripsi</th>
                <th style="width: 15%">Tipe</th>
                <th style="width: 25%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPemasukan = 0;
                $totalPengeluaran = 0;
            @endphp
            @foreach($keuangan as $item)
                <tr>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->akun->nama_akun ?? '-' }}</td>
                    <td>{{ $item->deskripsi }}</td>
                    <td>{{ ucfirst($item->tipe) }}</td>
                    <td class="text-right">
                        Rp{{ number_format($item->total, 0, ',', '.') }}
                    </td>
                </tr>
                @php
                    if ($item->tipe === 'pemasukan') {
                        $totalPemasukan += $item->total;
                    } else {
                        $totalPengeluaran += $item->total;
                    }
                @endphp
            @endforeach

            <tr style="background-color:#f2f7ff;">
                <td colspan="4" style="text-align:right; font-weight:bold;">Total Pemasukan</td>
                <td class="text-right">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color:#f2f7ff;">
                <td colspan="4" style="text-align:right; font-weight:bold;">Total Pengeluaran</td>
                <td class="text-right">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color:#e8f1ff; font-weight:bold;">
                <td colspan="4" style="text-align:right;">Saldo Akhir</td>
                <td class="text-right">Rp{{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis oleh sistem pada {{ now()->format('d F Y, H:i') }}
    </div>
</body>
</html>
