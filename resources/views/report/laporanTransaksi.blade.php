@extends('layout.main')

@section('title', 'Laporan Penjualan Detail')

@section('container')
<style>
    .p-7 {
        padding: 0px;
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
   body {
    transform: scale(calc(100vw / 900));
    transform-origin: top left;
  }       /* zoom out */

           /* cegah side scroll */
  }
  .mobile {
    visibility: hidden;
    display: none;
  }
 .dark+#include-content{
    color:black;
  }
 table tfoot tr th,
table thead tr th {
    color: #000; /* Light mode */
}

.dark table tfoot tr th,
.dark table thead tr th {
    color: #000000; /* Dark mode */
}



</style>

<div class="bg-lightwhite dark:bg-black">
 <div class="flex items-center border dark:border-white/10 justify-between px-4 h-14 header bg-white dark:bg-white/10" >
    <div class="flex items-center space-x-3">
        <!-- Tombol Kembali -->
        

        <!-- Judul -->
        <span class="text-sm truncate max-w-[140px]">
            <b> Laporan Penjualan </b>
        </span>
    </div>

    <div class="flex items-center space-x-2">
        <a href="{{ url('/laporan/pdf?' . http_build_query(request()->all())) }}"
            class="btn px-6 text-base w-full sm:w-auto text-center">
                Cetak PDF
            </a>
            <button type="button" @click="window.dispatchEvent(new CustomEvent('filter'))"
            class="flex items-center gap-x-2 px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            <span>Filter</span>
        </button>
    </div>
</div>
 <!-- Modal -->
        <div x-data="{ open: false }" @filter.window="open = true" @close-modal.window="open = false">
            <!-- Overlay -->
            <div
                class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                :class="{ 'block': open, 'hidden': !open }"
            >
                <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                    <!-- Modal Box -->
                    <div
                        x-show="open"
                        x-transition
                        x-transition.duration.300
                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                        style="display: none;"
                    >
                        <!-- Header -->
                        <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                            <h5 class="font-semibold text-lg">Filter</h5>
                            <button
                                type="button"
                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                @click="open = false"
                            >
                                <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                                    <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-5">
                              <form method="GET"  class="flex flex-col gap-5">
                                        <!-- Filter Waktu -->
                                        <div>
                                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode Waktu</label>
                                            <div class="flex flex-col gap-2">
                                                <select name="periode" id="periodeFilter"
                                                    class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                    <option value="">Semua Waktu</option>
                                                    <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                    <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                    <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                                </select>
                                                <div id="filterBulanan" class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <select name="bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Bulan</option>
                                                            @for($m=1;$m<=12;$m++)
                                                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <select name="tahun_bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Tahun</option>
                                                            @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                                <option value="{{ $y }}" {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="filterTahunan" class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                                    <select name="tahun_tahun" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                        <option value="">Pilih Tahun</option>
                                                        @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                            <option value="{{ $y }}" {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div id="filterRentang" class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                            placeholder="Tanggal Awal">
                                                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                            placeholder="Tanggal Akhir">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end mt-4">
                                            <button type="submit"
                                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300">
                                                Terapkan Filter
                                            </button>
                                        </div>
                                    </form>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            function showFilterFields() {
                                                var periode = document.getElementById('periodeFilter').value;
                                                document.getElementById('filterBulanan').classList.toggle('hidden', periode !== 'bulanan');
                                                document.getElementById('filterTahunan').classList.toggle('hidden', periode !== 'tahunan');
                                                document.getElementById('filterRentang').classList.toggle('hidden', periode !== 'rentang');
                                            }
                                            document.getElementById('periodeFilter').addEventListener('change', showFilterFields);
                                            showFilterFields();
                                            $('#mitraFilter').select2({
                                                width: '100%',
                                                placeholder: "Cari Mitra...",
                                                allowClear: true
                                            });
                                        });
                                    </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<div class="flex flex-1 overflow-hidden content hidden md:flex "> 
        <!-- Workspace -->
        <div class="flex-1 overflow-auto flex items-start justify-center p-5"
            style="border: none; " id="print-area">
            <div class="border w-full shadow-lg   p-5" style="color:black; background-color:white; width: 210mm; height: 297mm;  min-height: 290mm; /* Default height */
                height: auto; " class="print-page" id="include-content" >
               
                    <div class="rounded-lg ">
                        <h2 class="text-center text-lg sm:text-xl font-bold tracking-wide uppercase mb-2">
                            Laporan Penjualan 
                        </h2>

                        <p class="text-center text-xs sm:text-sm mb-5">
                            Periode:
                            @if(request('periode') === 'bulanan' && request('bulan') && request('tahun_bulan'))
                                {{ \Carbon\Carbon::createFromDate(request('tahun_bulan'), request('bulan'), 1)->translatedFormat('F Y') }}
                            @elseif(request('periode') === 'tahunan' && request('tahun_tahun'))
                                Tahun {{ request('tahun_tahun') }}
                            @elseif(!empty($awal) && !empty($akhir))
                                {{ \Carbon\Carbon::parse($awal)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($akhir)->format('d/m/Y') }}
                            @else
                                -
                            @endif
                        </p>


                        @php $grandTotal = $laporan->sum('total'); @endphp

                        @forelse($laporan->groupBy('kode_transaksi') as $kode => $transaksi)
                            @php
                                $header = $transaksi->first();
                                $totalAkhir = $transaksi->sum('total');
                            @endphp

                            <!-- HEADER TRANSAKSI -->
                            <div class="mb-3 page-break-inside-avoid table-responsive">
                                <table class="w-full text-xs sm:text-sm border border-gray-300 rounded ">
                                    <tr class="bg-lightwhite">
                                        <td class="border px-2 py-1 w-28 font-semibold">No Transaksi</td>
                                        <td class="border px-2 py-1">{{ $kode }}</td>
                                        <td class="border px-2 py-1 w-20 sm:w-28 font-semibold">Tanggal</td>
                                        <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-2 py-1 font-semibold">Kode Pel.</td>
                                        <td class="border px-2 py-1">{{ $header->kode_mitra }}</td>
                                        <td class="border px-2 py-1 font-semibold">Nama Pelanggan</td>
                                        <td class="border px-2 py-1">{{ $header->nama_pelanggan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="border px-2 py-1 font-semibold">Alamat</td>
                                        <td class="border px-2 py-1" colspan="3">{{ $header->alamat }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- DETAIL ITEM -->
                            <div class="overflow-x-auto page-break-inside-auto table-responsive  ">
                                <table class="w-full text-[11px] border" style="color:black">
                                    <thead>
                                        <tr class="bg-lightwhite  ">
                                            <th class="border px-2 py-1 dark:text-black ">No.</th>
                                            <th class="border px-2 py-1 dark:text-black">Kode</th>
                                            <th class="border px-2 py-1 dark:text-black">Nama</th>
                                            <th class="border px-2 py-1 text-center dark:text-black">Keluar</th>
                                            <th class="border px-2 py-1 text-center dark:text-black">Terjual</th>
                                            <th class="border px-2 py-1 text-center dark:text-black">Retur</th>
                                            <th class="border px-2 py-1 text-center dark:text-black">Satuan</th>
                                            <th class="border px-2 py-1 text-center dark:text-black">Harga</th>
                                            <th class="border px-2 py-1 text-right dark:text-black">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksi as $i => $item)
                                        <tr class="">
                                            <td class="border px-2 py-1 text-center">{{ $i+1 }}</td>
                                            <td class="border px-2 py-1">{{ $item->kode_produk }}</td>
                                            <td class="border px-2 py-1">{{ $item->nama_produk }}</td>
                                            <td class="border px-2 py-1 text-center">{{ number_format($item->barang_keluar, 0) }}</td>
                                            <td class="border px-2 py-1 text-center">{{ number_format($item->jumlah, 0) }}</td>
                                            <td class="border px-2 py-1 text-center">{{ number_format($item->barang_retur, 0) }}</td>
                                            <td class="border px-2 py-1 text-center">{{ $item->satuan }}</td>
                                            <td class="border px-2 py-1 text-center">{{ number_format($item->harga, 0) }}</td>
                                            <td class="border px-2 py-1 text-right">{{ number_format($item->total, 0) }}</td>
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
                                        @empty
                            <tr>
                                    <td colspan="10">
                                        <div class="flex flex-col items-center justify-center py-10 text-gray-500 dark:text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 13h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2zm0 0v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4" />
                                            </svg>
                                            <p class="text-sm font-medium">Tidak ada data ditemukan untuk periode ini.</p>
                                        </div>
                                    </td>
                                </tr>
                        @endforelse
    

                        <!-- GRAND TOTAL -->
                        <div class="flex justify-end mt-8 page-break-inside-avoid bg-lightwhite">
                            <table class="text-sm border-t-2 border-gray-800">
                                <tr>
                                    <td class="pr-4 font-bold uppercase">Grand Total :</td>
                                    <td class="text-right font-extrabold text-base sm:text-lg">{{ number_format($grandTotal, 0) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                {{-- <iframe src="/transaksi/dok/konsinyasi/{{  $id }}" class="w-full h-full" frameborder="0"></iframe> --}}
            </div>
        </div>
    </div>
</div>
    
<div class="flex md:hidden items-center justify-center min-h-screen  bg-yellow-50 dark:bg-yellow-900/20 px-6 text-center">
    <div class="bg-white dark:bg-white/10  border dark:border-white/10 rounded-xl shadow-md p-6 max-w-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-yellow-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a1 1 0 00.86 1.5h18.64a1 1 0 00.86-1.5L13.71 3.86a1 1 0 00-1.72 0z" />
        </svg>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
            Dokumen ini hanya dapat ditampilkan di Desktop
        </h2>
        <p class="text-sm  dark:text-gray-300">
            Untuk melihat laporan lengkap, silakan cetak PDF dari perangkat Desktop Anda.
        </p>
        <a href="/laporan/pdf?{{ http_build_query(request()->all()) }}" 
           class="inline-block mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
            📄 Cetak PDF
        </a>
    </div>
</div>




@endsection
