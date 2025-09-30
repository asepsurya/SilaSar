<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Detail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #000;
        }
        h2 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        p {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #555;
            padding: 4px 6px;
        }
        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        td {
            vertical-align: top;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .no-border td {
            border: none !important;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .total-table td {
            border: none;
            padding: 4px 6px;
        }
        .grand-total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 13px;
        }
        hr {
            border: none;
            border-top: 1px dotted #555;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Detail</h2>
    <p>
        Periode: {{ isset($awal) ? \Carbon\Carbon::parse($awal)->format('d/m/Y') : '-' }}
        - {{ isset($akhir) ? \Carbon\Carbon::parse($akhir)->format('d/m/Y') : '-' }}
    </p>

```
@php $grandTotal = $laporan->sum('total'); @endphp

@foreach($laporan->groupBy('kode_transaksi') as $kode => $transaksi)
    @php
        $header = $transaksi->first();
        $totalAkhir = $transaksi->sum('total');
    @endphp

    <!-- HEADER TRANSAKSI (tanpa border) -->
    <table class="no-border">
        <tr>
            <td width="20%"><strong>No Transaksi</strong></td>
            <td>{{ $kode }}</td>
            <td width="20%"><strong>Tanggal</strong></td>
            <td>{{ \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Kode Pel.</strong></td>
            <td>{{ $header->kode_mitra }}</td>
            <td><strong>Nama Pelanggan</strong></td>
            <td>{{ $header->nama_pelanggan }}</td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td colspan="3">{{ $header->alamat }}</td>
        </tr>
    </table>

    <!-- DETAIL ITEM -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Masuk</th>
                <th>Keluar</th>
                <th>Retur</th>
                <th>Satuan</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $i => $item)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                <td>{{ $item->kode_produk }}</td>
                <td>{{ $item->nama_produk }}</td>
                <td class="text-right">{{ number_format($item->jumlah, 0) }}</td>
                <td class="text-center">{{ number_format($item->barang_keluar, 0) }}</td>
                <td class="text-center">{{ number_format($item->barang_retur, 0) }}</td>
                <td class="text-center">{{ $item->satuan }}</td>
                <td class="text-center">{{ number_format($item->harga, 0) }}</td>
                <td class="text-right">{{ number_format($item->total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER PER TRANSAKSI -->
    <table class="total-table" width="100%">
        <tr>
            <td class="text-right"><strong>Total Akhir :</strong></td>
            <td width="100" class="text-right"><strong>{{ number_format($totalAkhir, 0) }}</strong></td>
        </tr>
    </table>
    <hr>
@endforeach

<!-- GRAND TOTAL -->
<table class="total-table" width="100%">
    <tr class="grand-total">
        <td class="text-right">Grand Total :</td>
        <td width="120" class="text-right">{{ number_format($grandTotal, 0) }}</td>
    </tr>
</table>
```

</body>
</html>
