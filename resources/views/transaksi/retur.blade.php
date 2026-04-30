@extends('layout.main')
@section('title', 'Data Retur')

@section('css')
    <style>
        /* Select2 Custom Styles to match Tailwind form-input */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border-radius: 0.5rem !important;
            /* rounded-lg */
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            background-color: transparent !important;
            display: flex;
            align-items: center;
        }

        .dark .select2-container .select2-selection--single {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: inherit !important;
            line-height: normal !important;
            padding-left: 0.75rem !important;
            /* match px-3/4 */
            margin: 0 !important;
            font-size: 0.875rem !important;
            /* text-sm */
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            right: 10px !important;
        }

        .select2-dropdown {
            border-radius: 0.5rem !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            font-size: 0.875rem !important;
            padding: 4px !important;
        }

        .dark .select2-dropdown {
            background-color: #1f2937 !important;
            /* Tailwind gray-800 */
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .select2-search__field {
            border-radius: 0.375rem !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            padding: 0.35rem 0.5rem !important;
        }

        .dark .select2-search__field {
            background-color: #111827 !important;
            /* Tailwind gray-900 */
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        .dark .select2-results__option {
            color: #d1d5db !important;
            border-radius: 0.25rem;
            margin-bottom: 2px;
        }

        .select2-results__option {
            border-radius: 0.25rem;
            margin-bottom: 2px;
        }

        .dark .select2-results__option--highlighted {
            background-color: #2563eb !important;
            /* Tailwind blue-600 */
            color: #ffffff !important;
        }

        .select2-results__option--highlighted {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }
    </style>
@endsection

@section('container')
    <div class="px-2 py-1 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-lg font-semibold">Data Retur</h2>
        <div class="flex flex-wrap items-center gap-3 text-xs">
            <div
                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Retur Pending: <b class="text-sm">{{ number_format($totalRetur, 0, ',', '.') }}</b></span>
            </div>
            <div
                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-500/20 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Selesai (Kembali Stok): <b
                        class="text-sm">{{ number_format($totalDikembalikan, 0, ',', '.') }}</b></span>
            </div>
            <div
                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-50 dark:bg-white/5 text-slate-700 dark:text-white/70 border border-slate-200 dark:border-white/10 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <span>Baris Data: <b class="text-sm">{{ number_format($totalBaris, 0, ',', '.') }}</b></span>
            </div>
        </div>
    </div>

    <div x-data="{ openFilter: false, activeTab: 'pending' }" class="mb-4">
        <!-- Control Header: Filter (Left) and Tabs (Right) -->
        <div
            class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-4 border-b border-black/10 dark:border-white/10 pb-2 md:pb-0">
            <!-- Left: Filter & Reset -->
            <div class="flex flex-wrap gap-2 pb-2 md:pb-3">
                <button @click="openFilter = true"
                    class="mb-3 flex items-center gap-2 px-4 py-2 text-sm bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/10 rounded-lg transition shadow-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    Filter Data
                </button>
                @if (request()->anyFilled(['kode_mitra', 'kode_transaksi', 'tanggal_awal', 'tanggal_akhir']))
                    <a href="{{ route('transaksi.retur') }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition border border-red-200 font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>

            <!-- Right: Tabs -->
            <div class="flex gap-4 overflow-x-auto w-full md:w-auto">
                <button type="button" @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400' : 'text-gray-500 hover:text-black dark:text-white/60 dark:hover:text-white border-transparent hover:border-gray-300 dark:hover:border-white/30'"
                    class="flex items-center gap-2 px-2 py-2 text-sm border-b-2 font-bold transition-colors -mb-[2px] md:-mb-[3px] whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Retur Pending
                </button>
                <button type="button" @click="activeTab = 'alur'"
                    :class="activeTab === 'alur' ? 'text-blue-600 border-blue-600 dark:text-blue-400 dark:border-blue-400' : 'text-gray-500 hover:text-black dark:text-white/60 dark:hover:text-white border-transparent hover:border-gray-300 dark:hover:border-white/30'"
                    class="flex items-center gap-2 px-2 py-2 text-sm border-b-2 font-bold transition-colors -mb-[2px] md:-mb-[3px] whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Alur Kembali ke Stok
                </button>
            </div>
        </div>

        {{-- Modal Filter --}}
        <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[1000] hidden overflow-y-auto" style="z-index:999;"
            :class="{ 'block': openFilter, 'hidden': !openFilter }" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4" @click.self="openFilter = false">
                <div x-show="openFilter" x-transition x-transition.duration.300
                    class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">

                    <div
                        class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                        <h5 class="font-semibold text-lg">Filter Data Retur</h5>
                        <button type="button"
                            class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                            @click="openFilter = false">
                            <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <path
                                    d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                    fill="currentcolor"></path>
                                <path
                                    d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                    fill="currentcolor"></path>
                            </svg>
                        </button>
                    </div>

                    <form method="GET" action="{{ route('transaksi.retur') }}" class="p-5 flex flex-col gap-5"
                        id="filterForm">
                        <div class="space-y-4">
                            <div class="space-y-1 mb-3">
                                <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Mitra /
                                    Pelanggan</label>
                                <select name="kode_mitra" class="form-select select2-filter w-full">
                                    <option value="">Semua Mitra</option>
                                    @foreach ($mitra as $item)
                                        <option value="{{ $item->kode_mitra }}" {{ request('kode_mitra') == $item->kode_mitra ? 'selected' : '' }}>
                                            {{ $item->nama_mitra }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1 mb-3">
                                <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">No.
                                    Transaksi</label>
                                <select name="kode_transaksi" class="form-select select2-filter w-full">
                                    <option value="">Semua Transaksi</option>
                                    @foreach ($transaksiList as $item)
                                        <option value="{{ $item->kode_transaksi }}" {{ request('kode_transaksi') == $item->kode_transaksi ? 'selected' : '' }}>
                                            {{ $item->kode_transaksi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Dari
                                        Tanggal</label>
                                    <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                        class="form-input w-full py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 bg-transparent dark:bg-transparent text-black dark:text-white text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Sampai
                                        Tanggal</label>
                                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                        class="form-input w-full py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 bg-transparent dark:bg-transparent text-black dark:text-white text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit"
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 font-bold uppercase">
                                Terapkan Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="activeTab === 'pending'" x-transition.opacity>
            <div class="hidden md:block">
                <div class="clean-table-container">
                    <div class="table-responsive">
                        <table class="clean-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>

                                    <th>No. Retur</th>
                                    <th>Mitra</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            @forelse ($returs as $index => $batch)
                                <tbody x-data="{ openBatch: false }" class="border-b border-black/5 dark:border-white/5">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($batch->tanggal)->format('d/m/Y') }}
                                        </td>

                                        <td class="font-medium text-blue-600 dark:text-blue-400 text-xs">
                                            {{ $batch->no_retur }}
                                        </td>
                                        <td>
                                            {{ $batch->items->first()->transaksi->mitra->nama_mitra ?? $batch->kode_mitra }}
                                        </td>
                                        <td>
                                            <button type="button" @click="openBatch = !openBatch"
                                                class="flex items-center gap-1 px-3 py-2 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg transition font-semibold">
                                                Proses Retur
                                                <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openBatch}"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr x-show="openBatch" style="display: none;">
                                        <td colspan="6" class="p-0 border-0 bg-gray-50/50 dark:bg-black/20">
                                            <div x-show="openBatch" x-transition.opacity
                                                class="p-4 md:px-8 border-l-4 border-green-500">
                                                <h6 class="font-bold mb-3 text-sm text-black/70 dark:text-white/70">Daftar
                                                    Produk: {{ $batch->no_retur }}</h6>
                                                <div
                                                    class="overflow-x-auto bg-white dark:bg-white/5 rounded-lg border border-black/10 dark:border-white/10 shadow-sm">
                                                    <table class="w-full text-sm">
                                                        <thead
                                                            class="bg-gray-50 dark:bg-black/40 border-b border-black/10 dark:border-white/10">
                                                            <tr>
                                                                <th class="p-3 text-left">Produk</th>
                                                                <th class="p-3 ">Jml Retur</th>
                                                                <th class="p-3 ">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-black/5 dark:divide-white/5">
                                                            @foreach($batch->items as $item)
                                                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                                                    <td class="p-3">
                                                                        <div class="flex items-start gap-3">
                                                                            <img src="{{ optional($item->produk)->first_image ? asset('storage/' . $item->produk->first_image) : asset('assets/images/404.jpg') }}"
                                                                                alt="Produk"
                                                                                class="h-10 w-10 rounded-lg object-cover flex-shrink-0 border border-black/10 dark:border-white/10 shadow-sm"
                                                                                onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" />
                                                                            <div>
                                                                                <div
                                                                                    class="font-semibold text-black dark:text-white leading-tight">
                                                                                    {{ $item->produk->nama_produk ?? $item->kode_produk }}
                                                                                </div>
                                                                                <div class="text-[10px] text-gray-500 mt-0.5">
                                                                                    {{ $item->kode_transaksi }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="p-3 font-bold text-amber-600 ">
                                                                        {{ $item->barang_retur }}
                                                                    </td>
                                                                    <td class="p-3 flex">
                                                                        <div x-data="{ openSingle: false }"
                                                                            class="flex justify-center">
                                                                            <button @click="openSingle = true"
                                                                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 text-xs bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-500/30 font-bold rounded-lg transition cursor-pointer">Kembalikan
                                                                                ke Stok</button>

                                                                            {{-- Modal Single Item --}}
                                                                            <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[1000] hidden overflow-y-auto"
                                                                                style="z-index:9999;"
                                                                                :class="{ 'block': openSingle, 'hidden': !openSingle }">
                                                                                <div class="flex items-center justify-center min-h-screen px-4"
                                                                                    @click.self="openSingle = false">
                                                                                    <div x-show="openSingle" x-transition
                                                                                        class="bg-white dark:bg-black relative shadow-3xl border border-black/10 dark:border-white/10 p-0 rounded-lg overflow-hidden w-full max-w-md my-8 text-left">
                                                                                        <div
                                                                                            class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                                                            <h5 class="font-bold">Kembalikan ke Stok
                                                                                            </h5>
                                                                                            <button type="button"
                                                                                                @click="openSingle = false"
                                                                                                class="text-black/40 hover:text-black dark:text-white/40 dark:hover:text-white">
                                                                                                <svg class="w-4 h-4" fill="none"
                                                                                                    viewBox="0 0 24 24"
                                                                                                    stroke="currentColor">
                                                                                                    <path stroke-linecap="round"
                                                                                                        stroke-linejoin="round"
                                                                                                        stroke-width="2"
                                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                        <form
                                                                                            action="{{ route('transaksi.retur.kembalikan') }}"
                                                                                            method="POST" class="p-5 space-y-4">
                                                                                            @csrf
                                                                                            <input type="hidden"
                                                                                                name="transaksi_product_id"
                                                                                                value="{{ $item->id }}">
                                                                                            <div
                                                                                                class="bg-lightblue-100 rounded-2xl p-3 flex flex-row items-center gap-3 mb-2 shadow-sm">
                                                                                                <!-- Gambar di Kiri -->
                                                                                                <img src="{{ optional($item->produk)->first_image ? asset('storage/' . $item->produk->first_image) : asset('assets/images/404.jpg') }}"
                                                                                                    alt="Produk"
                                                                                                    class="h-12 w-12 rounded-lg object-cover flex-shrink-0 border border-black/10 dark:border-white/10 shadow-sm"
                                                                                                    onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" />

                                                                                                <!-- Teks di Kanan -->
                                                                                                <div class="flex flex-col min-w-0">
                                                                                                    <p
                                                                                                        class="text-xs font-bold text-amber-900 dark:text-amber-400 truncate">
                                                                                                        {{ $item->produk->nama_produk ?? $item->kode_produk }}
                                                                                                    </p>
                                                                                                    <p
                                                                                                        class="text-[10px] text-amber-700 dark:text-amber-500/70">
                                                                                                        {{ $item->kode_transaksi }}
                                                                                                    </p>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div
                                                                                                class="mb-2 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                                                <label
                                                                                                    class="block text-xs font-semibold mb-1">Jumlah</label>
                                                                                                <input type="number"
                                                                                                    name="jumlah_kembali" min="1"
                                                                                                    max="{{ $item->barang_retur }}"
                                                                                                    value="{{ $item->barang_retur }}"
                                                                                                    class="w-full form-input text-sm border-gray-300 dark:border-white/10 rounded-lg dark:bg-transparent">
                                                                                            </div>
                                                                                            <div>
                                                                                                <label
                                                                                                    class="block text-xs font-semibold mb-1">Keterangan</label>
                                                                                                <textarea name="keterangan_lain"
                                                                                                    rows="2"
                                                                                                    class="w-full form-input text-sm border-gray-300 dark:border-white/10 rounded-lg dark:bg-transparent"
                                                                                                    placeholder="Opsional"></textarea>
                                                                                            </div>
                                                                                            <div class="flex gap-2 pt-2">
                                                                                                <button type="submit"
                                                                                                    class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold transition">Simpan</button>
                                                                                                <button type="button"
                                                                                                    @click="openSingle = false"
                                                                                                    class="px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-gray-200 dark:hover:bg-white/10 rounded-lg text-sm font-semibold transition">Batal</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @empty
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="px-3 py-8 text-center text-gray-500">
                                            Belum ada data retur.
                                        </td>
                                    </tr>
                                </tbody>
                            @endforelse
                        </table>
                    </div>
                    <span class="mt-4">{{ $returs->links('vendor.pagination.clean') }}</span>
                </div>
            </div>

            <div class="md:hidden space-y-3">
                @forelse ($returs as $batch)
                    <div x-data="{ openBatch: false }"
                        class="border border-black/10 dark:border-white/10 rounded-lg p-4 bg-white dark:bg-white/5 transition-all">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                        {{ $batch->no_retur }}
                                    </span>
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-white/5 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-white/10">
                                        {{ $batch->kode_transaksi }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-black dark:text-white mt-1">
                                    {{ $batch->items->first()->transaksi->mitra->nama_mitra ?? $batch->kode_mitra }}
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 text-xs font-semibold">
                                {{ number_format($batch->total_barang_retur, 0, ',', '.') }} item
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-500">
                            <span>{{ \Carbon\Carbon::parse($batch->tanggal)->format('d/m/Y') }}</span>
                            <button type="button" @click="openBatch = !openBatch"
                                class="flex items-center gap-1 text-green-600 dark:text-green-500 font-bold p-1">
                                Kelola Retur
                                <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': openBatch}" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        <div x-show="openBatch" x-transition.opacity style="display: none;"
                            class="mt-4 pt-4 border-t border-black/10 dark:border-white/10 space-y-3">
                            <h6 class="font-bold text-xs text-black/70 dark:text-white/70 mb-2">Daftar Produk:</h6>
                            @foreach($batch->items as $item)
                                <div class="p-3 border border-black/10 dark:border-white/10 rounded-lg bg-gray-50 dark:bg-black/20">
                                    <div class="flex items-start gap-3 mb-3">
                                        <img src="{{ optional($item->produk)->first_image ? asset('storage/' . $item->produk->first_image) : asset('assets/images/404.jpg') }}"
                                            alt="Produk"
                                            class="h-12 w-12 rounded-lg object-cover flex-shrink-0 border border-black/10 dark:border-white/10 shadow-sm"
                                            onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" />
                                        <div>
                                            <p class="text-sm font-bold text-black dark:text-white leading-tight">
                                                {{ $item->produk->nama_produk ?? $item->kode_produk }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $item->kode_transaksi }} | Pending: <span
                                                    class="font-bold text-amber-600">{{ $item->barang_retur }}</span></p>
                                        </div>
                                    </div>
                                    <form action="{{ route('transaksi.retur.kembalikan') }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="transaksi_product_id" value="{{ $item->id }}">
                                        <input type="number" name="jumlah_kembali" min="1" max="{{ $item->barang_retur }}"
                                            value="{{ $item->barang_retur }}"
                                            class="w-full p-2 border border-gray-300 dark:border-white/10 rounded-lg text-sm dark:bg-transparent">
                                        <button type="submit"
                                            class="w-full py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition">Simpan</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div
                        class="border border-black/10 dark:border-white/10 rounded-lg p-6 text-center text-gray-500 bg-white dark:bg-white/5">
                        Belum ada data retur.
                    </div>
                @endforelse
                <div class="mt-4">
                    {{ $returs->links('vendor.pagination.clean') }}
                </div>
            </div>
        </div>

        <div class="mt-0" x-show="activeTab === 'alur'" x-transition.opacity>
            <div class="mb-4">
                <p class="text-sm font-semibold">Alur Kembali ke Stok</p>
                <p class="text-xs text-black/60 dark:text-white/60">Riwayat barang retur yang sudah diproses masuk kembali
                    ke stok.</p>
            </div>

            <div class="hidden md:block">
                <div class="clean-table-container">
                    <div class="table-responsive">
                        <table class="clean-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Waktu</th>
                                    <th>Transaksi</th>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Keterangan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($alurRetur as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="font-medium text-blue-600 dark:text-blue-400">
                                            {{ $log->referensi }}
                                        </td>
                                        <td>
                                            {{ $log->produk->nama_produk ?? $log->kode_produk }}
                                        </td>
                                        <td class="text-center font-semibold text-green-700 dark:text-green-400">
                                            {{ number_format($log->jumlah, 0, ',', '.') }}
                                        </td>
                                        <td>{{ $log->keterangan }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('transaksi.retur.batal-kembalikan', $log->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengembalian ini? Stok akan dikurangi dan barang akan masuk kembali ke daftar retur pending.');">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 border border-red-200 dark:border-red-500/20 rounded-lg text-xs font-bold transition">
                                                    Batalkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-8 text-center text-gray-500">Belum ada riwayat
                                            pengembalian stok
                                            dari retur.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $alurRetur->links('vendor.pagination.clean') }}
                    </div>
                </div>
            </div>

            <div class="md:hidden space-y-3">
                @forelse ($alurRetur as $log)
                    <div class="border border-black/10 dark:border-white/10 rounded-lg p-4 bg-white dark:bg-white/5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold">{{ $log->produk->nama_produk ?? $log->kode_produk }}</p>
                                <p class="text-xs text-gray-500">{{ $log->referensi }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full bg-green-50 text-green-700 text-xs font-semibold">
                                {{ number_format($log->jumlah, 0, ',', '.') }} masuk
                            </span>
                        </div>
                        <div class="mt-3 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                            <p>Waktu: {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</p>
                            <p>Keterangan: {{ $log->keterangan }}</p>
                        </div>
                        <form action="{{ route('transaksi.retur.batal-kembalikan', $log->id) }}" method="POST"
                            class="mt-3 border-t border-black/5 dark:border-white/5 pt-3"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengembalian ini? Stok akan dikurangi dan barang akan masuk kembali ke daftar retur pending.');">
                            @csrf
                            <button type="submit"
                                class="w-full py-2 flex items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500/20 border border-red-200 dark:border-red-500/20 rounded-lg text-xs font-bold transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Batalkan
                            </button>
                        </form>
                    </div>
                @empty
                    <div
                        class="border border-black/10 dark:border-white/10 rounded-lg p-6 text-center text-gray-500 bg-white dark:bg-white/5">
                        Belum ada riwayat pengembalian stok dari retur.
                    </div>
                @endforelse
                <div class="mt-4">
                    {{ $alurRetur->links('vendor.pagination.clean') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.select2-filter').select2({
                width: '100%',
                placeholder: "Cari...",
                allowClear: true,
                dropdownParent: $('#filterForm')
            });
        });
    </script>
@endsection