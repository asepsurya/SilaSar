@extends('layout.main')

@section('title', 'Laporan Transaksi Detail')

@section('container')
<style>
    .p-7 { padding: 0; }
    footer { display: none !important; }
    .report-sheet { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; color: #111; }
    .report-table { width: 100%; border-collapse: collapse; font-size: 11px; }
    .report-table th, .report-table td { border: 1px solid #666; padding: 4px 6px; vertical-align: top; }
    .report-table th { background: #f2f2f2; text-align: center; }
    @media print { .no-print { display: none !important; } }
</style>

<div class="bg-lightwhite dark:bg-black">
    <div class="no-print flex items-center justify-between border dark:border-white/10 px-4 h-14 header bg-white dark:bg-white/10">
        <div class="flex items-center space-x-3">
            <span class="text-sm truncate max-w-[180px]"><b>Laporan Transaksi Detail</b></span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('laporan.exportPDF', request()->query()) }}" class="btn px-6 text-base w-full sm:w-auto text-center">Cetak PDF Detail</a>
            <a href="{{ route('laporan.penjualan.rekap', request()->query()) }}" class="px-4 py-2 text-sm bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">Laporan Rekap A4</a>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden content hidden md:flex">
        <div class="flex-1 overflow-auto flex items-start justify-center p-5">
            <div class="report-sheet shadow-lg p-5">
                <h2 class="text-center text-lg font-bold uppercase mb-1">
                    Laporan Penjualan Detail {{ $id_kota ? '- ' . $id_kota : '' }}
                </h2>
                <p class="text-center text-sm mb-4">
                    Periode:
                    {{ isset($awal) && $awal ? \Carbon\Carbon::parse($awal)->format('d/m/Y') : '-' }} s/d
                    {{ isset($akhir) && $akhir ? \Carbon\Carbon::parse($akhir)->format('d/m/Y') : '-' }}
                </p>

                @php $grandTotal = $laporan->sum('total'); @endphp

                @foreach($laporan->groupBy('kode_transaksi') as $kode => $transaksi)
                    @php $header = $transaksi->first(); $totalAkhir = $transaksi->sum('total'); @endphp

                    <table class="report-table mb-3">
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

                    <table class="report-table mb-2">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Keluar</th>
                                <th>Terjual</th>
                                <th>Retur</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transaksi as $i => $item)
                                <tr>
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td>{{ $item->kode_produk }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td class="text-center">{{ number_format($item->barang_keluar, 0) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah, 0) }}</td>
                                    <td class="text-center">{{ number_format($item->barang_retur, 0) }}</td>
                                    <td class="text-center">{{ $item->satuan }}</td>
                                    <td class="text-right">{{ number_format($item->harga, 0) }}</td>
                                    <td class="text-right">{{ number_format($item->total, 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-end mb-4">
                        <table class="text-sm">
                            <tr>
                                <td class="pr-3 font-semibold">Total Akhir :</td>
                                <td class="text-right font-bold">{{ number_format($totalAkhir, 0) }}</td>
                            </tr>
                        </table>
                    </div>
                    <hr class="border-dashed mb-6 dark:border-white/10">
                @endforeach

                <div class="flex justify-end mt-8">
                    <table class="text-sm border-t-2 border-gray-800">
                        <tr>
                            <td class="pr-4 font-bold uppercase">Grand Total :</td>
                            <td class="text-right font-extrabold text-base">{{ number_format($grandTotal, 0) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
