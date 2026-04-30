@extends('layout.main')

@section('title', 'Laporan Rekap A4')

@section('container')
    @php
        $periodLabel = '-';
        if (($periode ?? null) === 'bulanan' && !empty($bulan) && !empty($tahun_bulan)) {
            $periodLabel = \Carbon\Carbon::createFromDate($tahun_bulan, $bulan, 1)->translatedFormat('F Y');
        } elseif (($periode ?? null) === 'tahunan' && !empty($tahun_tahun)) {
            $periodLabel = 'Tahun ' . $tahun_tahun;
        } elseif (!empty($awal) && !empty($akhir)) {
            $periodLabel = \Carbon\Carbon::parse($awal)->format('d/m/Y') . ' s/d ' . \Carbon\Carbon::parse($akhir)->format('d/m/Y');
        }
        $groupedLaporan = $laporan->groupBy('kode_transaksi');
        $grandTotal = $laporan->sum('total');
        $grandPembayaran = collect($pembayaranMasuk ?? [])->sum('total');
    @endphp
    <style>
        .p-7 {
            padding: 0;
        }

        footer {
            display: none !important;
        }

        .report-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #fff;
            color: #222;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 10px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #6b7280;
            padding: 4px 5px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .report-table thead th {
            background: #f4d03f;
            color: #111827;
            text-align: center;
            font-weight: 700;
        }

        .section-title {
            background: #9fd27b;
            border: 1px solid #6b7280;
            font-weight: 700;
            text-align: center;
            padding: 4px 6px;
        }

        .payment-title {
            background: #7cc6e6;
            border: 1px solid #6b7280;
            font-weight: 700;
            text-align: center;
            padding: 4px 6px;
        }

        .grand-row td {
            background: #9fd27b;
            font-weight: 700;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="bg-lightwhite dark:bg-black">
        
        <div
            class="no-print flex items-center justify-between border dark:border-white/10 px-4 h-14 header bg-white dark:bg-white/10">
            <div class="flex items-center space-x-3">
                <span class="text-sm truncate max-w-[180px]"><b>Laporan Rekap A4</b></span>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('laporan.exportPDFRekap', request()->query()) }}"
                    class="btn px-6 text-base w-full sm:w-auto text-center">Cetak PDF Rekap</a>
                <a href="{{ route('laporan.penjualan', request()->query()) }}"
                    class="px-4 py-2 text-sm bg-slate-700 hover:bg-slate-800 text-white rounded-lg transition">Laporan
                    Detail</a>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden content hidden md:flex">
            <div class="flex-1 overflow-auto flex items-start justify-center p-5">
                <div class="report-sheet shadow-lg p-5">
                    <h2 class="text-center text-lg font-bold uppercase mb-1">
                        Laporan Penjualan Rekap {{ $id_kota ? '- ' . $id_kota : '' }}
                    </h2>
                    <p class="text-center text-sm mb-4">Periode {{ $periodLabel }}</p>

                    <div class="section-title text-sm mb-0">Laporan Pesanan Bulan {{ $periodLabel }}</div>
                    <table class="report-table mb-8">
                        <thead>
                            <tr>
                                <th style="width:4%;">No</th>
                                <th style="width:11%;">Invoice</th>
                                <th style="width:10%;">Tanggal</th>
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
                                @php $header = $transaksi->first();
                                    $rowspan = $transaksi->count();
                                $invoiceTotal = $transaksi->sum('total'); @endphp
                                @foreach($transaksi as $index => $item)
                                    <tr>
                                        <td class="text-center" style="{{ !$loop->first ? 'border-top: none;' : '' }}">{{ $loop->first ? $loop->parent->iteration : '' }}</td>
                                        <td class="text-center" style="{{ !$loop->first ? 'border-top: none;' : '' }}">{{ $loop->first ? $kode : '' }}</td>
                                        <td class="text-center" style="{{ !$loop->first ? 'border-top: none;' : '' }}">
                                            {{ $loop->first ? \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d-M-y') : '' }}</td>
                                        <td style="white-space: normal; width:150px; {{ !$loop->first ? 'border-top: none;' : '' }}">
                                            {{ $loop->first ? $header->nama_pelanggan : '' }}
                                        </td>
                                        <td style="white-space: normal; width:150px; {{ !$loop->first ? 'border-top: none;' : '' }}">{{ $loop->first ? $header->kota_mitra : '' }}</td>
                                        <td style="white-space: normal; width:150px;">{{ $item->nama_produk }}</td>
                                        <td class="text-center">{{ number_format($item->barang_keluar ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">
                                            @if($loop->first)
                                                Rp {{ number_format($invoiceTotal, 0, ',', '.') }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-6">Tidak ada data transaksi.</td>
                                </tr>
                            @endforelse
                            <tr class="grand-row">
                                <td colspan="9" class="text-center">Grand Total</td>
                                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="payment-title text-sm mb-0 mt-10">Laporan Pembayaran Masuk Bulan {{ $periodLabel }}</div>
                    <table class="report-table">
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
                                    <td class="text-center">{{ $pembayaran->kode_transaksi }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d-M-y') }}</td>
                                    <td>{{ $pembayaran->nama_pelanggan }}</td>
                                    <td>{{ $pembayaran->kota_mitra }}</td>
                                    <td class="text-right">Rp {{ number_format($pembayaran->total ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp {{ number_format($pembayaran->total ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6">Tidak ada data pembayaran masuk.</td>
                                </tr>
                            @endforelse
                            <tr class="grand-row">
                                <td colspan="6" class="text-center">Grand Total</td>
                                <td class="text-right">Rp {{ number_format($grandPembayaran, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection