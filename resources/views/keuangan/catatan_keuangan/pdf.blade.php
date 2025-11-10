<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>
    <style>
        .summary-table {
    width: 300px;
    float: right;
    margin-top: 30px;
    border-collapse: collapse;
    font-size: 12px;
}

.summary-table th, .summary-table td {
    border: 1px solid #999;
    padding: 8px;
    text-align: left;
}

.summary-table th {
    background-color: #f0f0f0;
    font-weight: bold;
}

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2, h4 {
            text-align: center;
            margin: 0;
        }

        .meta {
            margin-top: 5px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 15px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #888;
        }
    </style>
</head>
<body>

    <h2>Laporan Keuangan</h2>
    <div class="meta">
        Periode: {{ $periode ?? 'Semua Data' }} <br>
        Dicetak:  {{ now() }}
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

        {{-- Baris Ringkasan --}}
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Total Pemasukan</td>
            <td class="text-right">Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Total Pengeluaran</td>
            <td class="text-right">Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bold;">Saldo Akhir</td>
            <td class="text-right">
                Rp{{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>



</body>
</html>
