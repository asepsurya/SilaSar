@extends('layout.main')

@section('title', 'Laporan Penjualan Detail')

@section('container')
<style>
    .p-7 {
        padding: 10px;
    }
    footer {
        display: none !important;
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .page-break-inside-avoid {
            page-break-inside: avoid;
        }
    }
    @media (max-width: 640px) {
  .table-responsive {
    transform: scale(0.9);         /* zoom out */

           /* cegah side scroll */
  }

}
</style>
<div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md mb-5 flex items-center justify-between">
 <div class="flex items-center gap-2 w-full">
    <form method="GET" action="" class="flex flex-col sm:flex-row gap-3 w-full">
        <div class="w-full sm:w-auto">
            <label for="awal" class="block mb-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                Tanggal Awal
            </label>
            <input type="date" name="awal" id="awal"
                class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 
                       dark:border-white/10 rounded-lg focus:ring-0 focus:shadow-none"
                value="{{ request('awal') ?? '' }}">
        </div>

        <div class="w-full sm:w-auto">
            <label for="akhir" class="block mb-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                Tanggal Akhir
            </label>
            <input type="date" name="akhir" id="akhir"
                class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 
                       dark:border-white/10 rounded-lg focus:ring-0 focus:shadow-none"
                value="{{ request('akhir') ?? '' }}">
        </div>

        <div class="flex gap-2 items-end w-full sm:w-auto">
            <button type="submit"
                class="btn py-2.5 px-6 text-base w-full sm:w-auto">
                Filter
            </button>
            <a href="/laporan/pdf"
                class="btn py-2.5 px-6 text-base w-full sm:w-auto text-center">
                Cetak PDF
            </a>
        </div>
    </form>
</div>
  
</div>






<div class="rounded-lg">
    <h2 class="text-center text-lg sm:text-xl font-bold tracking-wide uppercase mb-2">
        Laporan Penjualan Detail
    </h2>

    <p class="text-center text-xs sm:text-sm text-gray-600 mb-4">
        Periode:
        {{ isset($awal) ? \Carbon\Carbon::parse($awal)->format('d/m/Y') : '-' }} s/d
        {{ isset($akhir) ? \Carbon\Carbon::parse($akhir)->format('d/m/Y') : '-' }}
    </p>

    @php $grandTotal = $laporan->sum('total'); @endphp

    @foreach($laporan->groupBy('kode_transaksi') as $kode => $transaksi)
        @php
            $header = $transaksi->first();
            $totalAkhir = $transaksi->sum('total');
        @endphp

        <!-- HEADER TRANSAKSI -->
        <div class="mb-3 page-break-inside-avoid table-responsive">
            <table class="w-full text-xs sm:text-sm border border-gray-300 rounded dark:border-white/10">
                <tr class="bg-lightwhite dark:bg-white/5">
                    <td class="border dark:border-white/10 px-2 py-1 w-28 font-semibold">No Transaksi</td>
                    <td class="border dark:border-white/10 px-2 py-1">{{ $kode }}</td>
                    <td class="border dark:border-white/10 px-2 py-1 w-20 sm:w-28 font-semibold">Tanggal</td>
                    <td class="border dark:border-white/10 px-2 py-1">{{ \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="border dark:border-white/10 px-2 py-1 font-semibold">Kode Pel.</td>
                    <td class="border dark:border-white/10 px-2 py-1">{{ $header->kode_mitra }}</td>
                    <td class="border dark:border-white/10 px-2 py-1 font-semibold">Nama Pelanggan</td>
                    <td class="border dark:border-white/10 px-2 py-1">{{ $header->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <td class="border dark:border-white/10 px-2 py-1 font-semibold">Alamat</td>
                    <td class="border dark:border-white/10 px-2 py-1" colspan="3">{{ $header->alamat }}</td>
                </tr>
            </table>
        </div>

        <!-- DETAIL ITEM -->
        <div class="overflow-x-auto page-break-inside-auto table-responsive">
            <table class="w-full text-[11px] sm:text-xs border dark:border-white/10 border-black/10 min-w-max">
                <thead>
                    <tr class="bg-lightwhite dark:bg-white/5 text-gray-700">
                        <th class="border dark:border-white/10 px-2 py-1">No</th>
                        <th class="border dark:border-white/10 px-2 py-1">Kode</th>
                        <th class="border dark:border-white/10 px-2 py-1">Nama</th>
                        <th class="border dark:border-white/10 px-2 py-1 text-center">Masuk</th>
                        <th class="border dark:border-white/10 px-2 py-1 text-center">Keluar</th>
                        <th class="border dark:border-white/10 px-2 py-1 text-center">Retur</th>
                        <th class="border dark:border-white/10 px-2 py-1 text-center">Satuan</th>
                        <th class="border dark:border-white/10 px-2 py-1 text-center">Harga</th>
                        <th class="border dark:border-white/10 px-2 py-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $i => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ $i+1 }}</td>
                        <td class="border dark:border-white/10 px-2 py-1">{{ $item->kode_produk }}</td>
                        <td class="border dark:border-white/10 px-2 py-1">{{ $item->nama_produk }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ number_format($item->jumlah, 0) }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ number_format($item->barang_keluar, 0) }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ number_format($item->barang_retur, 0) }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ $item->satuan }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-center">{{ number_format($item->harga, 0) }}</td>
                        <td class="border dark:border-white/10 px-2 py-1 text-right">{{ number_format($item->total, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- FOOTER PER TRANSAKSI -->
        <div class="flex justify-end mb-4 page-break-inside-avoid">
            <table class="text-xs sm:text-sm">
                <tr>
                    <td class="pr-3 font-semibold">Total Akhir :</td>
                    <td class="text-right font-bold">{{ number_format($totalAkhir, 0) }}</td>
                </tr>
            </table>
        </div>
        <hr class="border-dashed mb-6 dark:border-white/10">
    @endforeach

    <!-- GRAND TOTAL -->
    <div class="flex justify-end mt-8 page-break-inside-avoid bg-lightwhite dark:bg-white/5">
        <table class="text-sm border-t-2 border-gray-800">
            <tr>
                <td class="pr-4 font-bold uppercase">Grand Total :</td>
                <td class="text-right font-extrabold text-base sm:text-lg">{{ number_format($grandTotal, 0) }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
