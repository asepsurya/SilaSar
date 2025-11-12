@extends('layout.main')
@section('title', 'Log Stok Produk')
@section('container')
<style>
    .masuk {
        background-color: #e6ffed;
    }

    .keluar {
        background-color: #ffe6e6;
    }

    .dark .masuk {
        background-color: #064e3b;
    }

    .dark .keluar {
        background-color: #7f1d1d;
    }
  
    .dektop {
        visibility: block;
    }
    @media (max-width: 768px) {
        .mobile {
            display: none !important;
        }
        .section2 {
            width: 100%;    
        }
        .dektop {
            display: block !important;
        }
    }
/* === Select2 Minimal Clean Style === */
.select2-container--default .select2-selection--single {
border: 1px solid #e5e7eb !important; /* border-gray-200 */
background-color: #ffffff !important; /* bg-white */
}
.dark .select2-container--default .select2-selection--single {
border: 1px solid #242424 !important; /* border-gray-700 */
background-color: #000000 !important; /* bg-black */
color: #ffffff !important; /* text-white */
}


.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
border-color: #9ca3af !important; /* focus border-gray-400 */
box-shadow: none !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
background-color: #f3f4f6 !important; /* bg-gray-100 */
color: #111827 !important; /* text-gray-900 */
}
@media (max-width: 768px) {
    #print-area {
        transform: scale(0.75);         /* perkecil tampilan 75% */
        transform-origin: top center;   /* pusatkan tampilan */
    }

    #include-content {
        width: 100% !important;
        height: auto !important;
    }

    table {
        font-size: 10px !important;
    }

    th, td {
        padding: 3px 5px !important;
    }

    h2 {
        font-size: 14px !important;
    }

    p {
        font-size: 12px !important;
    }
}
</style>
<div class="px-2 py-1 mb-2 flex items-center justify-between  block md:hidden">
        <h2 class="text-lg font-semibold">Alur Stok </h2>
            <a  href="{{ route('index.produk') }}" href="javascript:void(0);" class="mt-0 py-1 px-3 inline-block bg-black/5 dark:bg-white/5 rounded-lg text-black/40 dark:text-white/40 border border-black/5 dark:border-white/5 hover:bg-transparent dark:hover:bg-transparent hover:text-black dark:hover:text-white transition-all duration-300">
                Kembali
            </a> 
    </div>
<div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 rounded-md">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center p-5 gap-4">
       <p class="text-sm font-semibold  hidden sm:block">Alur Stok</p>
        <form id="filterForm" action="{{ route('produk.log.detail') }}" method="GET" class="flex flex-row items-center justify-center gap-2 section2">
            @csrf
           
            <select name="produk_id" class="select form-select w-full py-2 px-3 text-sm border border-gray-300 dark:bg-blackrounded-md dark:bg-black bg-white dark:border-white/10 text-gray-900 dark:text-gray-100" onchange="this.form.submit()">
                <option value="">Semua Produk</option>
                @foreach($produkList as $produk)
                <option value="{{ $produk->kode_produk }}" {{ $produk_id == $produk->kode_produk ? 'selected' : '' }}>
                    {{ $produk->nama_produk }}
                </option>
                @endforeach
            </select>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    $('.select').select2({
                        width: '100%',
                        placeholder: "Pilih Produk...",
                       
                        dropdownParent: $('#filterForm') // biar dropdown tetap di area form
                    });
                });
            </script>
            <button type="button" @click="window.dispatchEvent(new CustomEvent('filter'))" class="flex-1 flex gap-x-2 px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition justify-center">
                <span>Filter</span>
            </button>
        </form>
    </div>


    <!-- Modal -->
    <div x-data="{ open: false }" @filter.window="open = true" @close-modal.window="open = false">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="{ 'block': open, 'hidden': !open }">
            <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                <!-- Modal Box -->
                <div x-show="open" x-transition x-transition.duration.300 class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                    <!-- Header -->
                    <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                        <h5 class="font-semibold text-lg">Filter</h5>
                        <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="open = false">
                            <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                                <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <form method="GET" action="{{ route('produk.log.detail') }}" class="flex flex-col gap-5">
                            @csrf
                            <!-- Filter Waktu -->
                            <div>
                                <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode Waktu</label>
                                <div class="flex flex-col gap-2">

                                    <select name="periode" id="periodeFilter" class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                        <option value="">Semua Waktu</option>
                                        <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                        <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                    </select>
                                    <div id="filterBulanan" class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                        <div class="flex gap-2">
                                            <select name="bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Pilih Bulan</option>
                                                @for($m=1;$m<=12;$m++) <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                    </option>
                                                    @endfor
                                            </select>
                                            <select name="tahun_bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Pilih Tahun</option>
                                                @for($y = date('Y')-5; $y <= date('Y'); $y++) <option value="{{ $y }}" {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                    @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" name="produk_id" value="{{ $produk_id }}" class="hidden">
                                    <div id="filterTahunan" class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                        <select name="tahun_tahun" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                            <option value="">Pilih Tahun</option>
                                            @for($y = date('Y')-5; $y <= date('Y'); $y++) <option value="{{ $y }}" {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div id="filterRentang" class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                        <div class="flex gap-2">
                                            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white" placeholder="Tanggal Awal">
                                            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white" placeholder="Tanggal Akhir">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300">
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
                                    width: '100%'
                                    , placeholder: "Cari Mitra..."
                                    , allowClear: true
                                });
                            });

                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="table-responsive">
    <table class="table-hover text-xs">
        <thead>
            <tr>
                <th>#</th>
                <th>Kode Transaksi</th>
                <th class="mobile"></th>
                <th class="mobile">Keterangan</th>
                <th>Tanggal</th>
                <th>Masuk</th>
                <th>keluar</th>
                
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach ($logs as $log)
            <tr class="hover:bg-gray-50">
                <td>{{ $no++ }}</td>
                <td>{{ $log->referensi }}</td>
                <td class="mobile">
                    @if($log->tipe == 'masuk')
                    <p class="px-1.5 text-[#4AA785] bg-[#DEF8EE] text-xs rounded-[18px] inline-block">Masuk</p>
                    @else
                    <p class="px-1.5 text-[#E24E42] bg-[#FDEAEA] text-xs rounded-[18px] inline-block">Keluar</p>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                <td class="mobile">{{ $log->keterangan }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-center  text-green-600  masuk">
                    @if($log->tipe == 'masuk')
                    {{ number_format($log->jumlah, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center  text-sm text-red-600  keluar">
                    @if($log->tipe == 'keluar')
                    {{ number_format($log->jumlah, 0, ',', '.') }}
                    @else
                    -
                    @endif
                </td>
               
            </tr>
            @endforeach
            @if ($logs->isEmpty())
            <tr>
                <td colspan="8" class="py-8 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7.5V6a1.5 1.5 0 011.5-1.5H9l2-2h2l2 2h4.5A1.5 1.5 0 0121 6v1.5M3 7.5h18M3 7.5v10.5A1.5 1.5 0 004.5 19.5h15A1.5 1.5 0 0021 18V7.5M9 12h6m-6 4h3" />
                        </svg>
                        Tidak ada Log produk yang tersedia.
                    </div>
                </td>
            </tr>
        @endif

        </tbody>

        </tbody>
    </table>
</div>

</div>
<script>
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>
<div class="mt-6 flex justify-center">
    <div class=" dark:text-white rounded-xl px-6 py-3 shadow-md text-center w-full sm:w-auto">
        <h3 class="text-lg sm:text-xl font-semibold tracking-wide">
            📦 Stok Saat Ini:
            <span class="ml-2 font-bold text-yellow-300">
                {{ number_format($stokSaatIni, 0, ',', '.') }}
            </span>
        </h3>
    </div>
</div>

 
@endsection
