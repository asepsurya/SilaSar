<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Rekap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 18px;
            color: #222;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #6b7280;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        thead th {
            background: #f4d03f;
            text-align: center;
            font-weight: bold;
        }

        .section-title {
            background: #9fd27b;
            text-align: center;
            font-weight: bold;
            border: 1px solid #6b7280;
            padding: 4px 6px;
        }

        .payment-title {
            background: #7cc6e6;
            text-align: center;
            font-weight: bold;
            border: 1px solid #6b7280;
            padding: 4px 6px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }

        .grand-row td {
            background: #9fd27b;
            font-weight: bold;
        }

        .mt-24 {
            margin-top: 24px;
        }
    </style>
</head>

<body>
    @php
        $periodLabel = '-';
        if (($periode ?? null) === 'bulanan' && !empty($bulan) && !empty($tahunBulan)) {
            $periodLabel = \Carbon\Carbon::createFromDate($tahunBulan, $bulan, 1)->translatedFormat('F Y');
        } elseif (($periode ?? null) === 'tahunan' && !empty($tahunTahun)) {
            $periodLabel = 'Tahun ' . $tahunTahun;
        } elseif (!empty($awal) && !empty($akhir)) {
            $periodLabel = \Carbon\Carbon::parse($awal)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($akhir)->format('d/m/Y');
        }
        $groupedLaporan = $laporan->groupBy('kode_transaksi');
        $grandTotal = $laporan->sum('total');
        $grandPembayaran = collect($pembayaranMasuk ?? [])->sum('total');
    @endphp
    <div class="title">Laporan Penjualan Rekap {{ $id_kota ? '- ' . $id_kota : '' }}</div>
    <div class="subtitle">Periode {{ $periodLabel }}</div>
    <div class="section-title">Laporan Penjualan Rekap Bulan {{ $periodLabel }}
        {{ $id_kota ? '(' . $id_kota . ')' : '' }}</div>
    <table>
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:11%;">Invoice</th>
                <th style="width:10%;">Tgl Transaksi</th>
                <th style="width:20%;">Nama Toko</th>
                <th style="width:12%;">Kota</th>
                <th style="width:16%;">Produk</th>
                <th style="width:6%;">Qty</th>
                <th style="width:9%;">Harga</th>
                <th style="width:12%;">Jumlah</th>
                <th style="width:12%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupedLaporan as $kode => $transaksi)
                @php $header = $transaksi->first(); $rowspan = $transaksi->count(); $invoiceTotal = $transaksi->sum('total'); @endphp
                {{-- Use a sub-tbody for each transaction to avoid page breaks inside it --}}
                <tbody style="page-break-inside: avoid;">
                    @foreach($transaksi as $index => $item)
                        <tr>
                            <td class="text-center" style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                {{ $loop->first ? $loop->parent->iteration : '' }}
                            </td>
                            <td class="text-center nowrap" style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                {{ $loop->first ? $kode : '' }}
                            </td>
                            <td class="text-center nowrap" style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                {{ $loop->first ? \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d-M-y') : '' }}
                            </td>
                            <td style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                {{ $loop->first ? $header->nama_pelanggan : '' }}
                            </td>
                            <td style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                {{ $loop->first ? $header->kota_mitra : '' }}
                            </td>
                            <td>{{ $item->nama_produk }}</td>
                            <td class="text-center">{{ number_format($item->barang_keluar ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right nowrap">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right nowrap">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                            <td class="text-right nowrap" style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                @if($loop->first)
                                    Rp {{ number_format($invoiceTotal, 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @empty
                <tbody>
                    <tr><td colspan="10" class="text-center">Tidak ada data transaksi.</td></tr>
                </tbody>
            @endforelse
            <tbody style="page-break-inside: avoid;">
                <tr class="grand-row">
                    <td colspan="9" class="text-center">Grand Total</td>
                    <td class="text-right nowrap">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </tbody>
    </table>
    <div class="mt-24 payment-title">Laporan Pembayaran Masuk Bulan {{ $periodLabel }}</div>
    <table>
        <thead>
            <tr>
                <th style="width:6%;">No</th>
                <th style="width:14%;">Invoice</th>
                <th style="width:14%;">Tgl Bayar</th>
                <th style="width:24%;">Nama Toko</th>
                <th style="width:18%;">Kota</th>
                <th style="width:12%;">Nominal Pembayaran</th>
                <th style="width:12%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($pembayaranMasuk ?? collect()) as $pembayaran)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center nowrap">{{ $pembayaran->kode_transaksi }}</td>
                    <td class="text-center nowrap">
                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d-M-y') }}</td>
                    <td>{{ $pembayaran->nama_pelanggan }}</td>
                    <td>{{ $pembayaran->kota_mitra }}</td>
                    <td class="text-right nowrap">Rp {{ number_format($pembayaran->total ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right nowrap">Rp {{ number_format($pembayaran->total ?? 0, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data pembayaran masuk.</td>
                </tr>
            @endforelse
            <tr class="grand-row">
                <td colspan="6" class="text-center">Grand Total</td>
                <td class="text-right nowrap">Rp {{ number_format($grandPembayaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>