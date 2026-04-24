@extends('layout.main')
@section('title', 'Data Retur')

@section('css')
    <style>
        /* Add any view-specific overrides if necessary */
    </style>
@endsection

@section('container')
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Data Retur</h2>
        <div class="flex gap-2 text-xs">
            <div class="px-3 py-2 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                Total Retur Pending: <b>{{ number_format($totalRetur, 0, ',', '.') }}</b>
            </div>
            <div class="px-3 py-2 rounded-lg bg-green-50 text-green-700 border border-green-200">
                Total Sudah Kembali ke Stok: <b>{{ number_format($totalDikembalikan, 0, ',', '.') }}</b>
            </div>
            <div class="px-3 py-2 rounded-lg bg-slate-50 text-slate-700 border border-slate-200">
                Baris Data: <b>{{ number_format($totalBaris, 0, ',', '.') }}</b>
            </div>
        </div>
    </div>

    <div class="border border-black/10 dark:border-white/10 rounded-lg p-4 mb-4 bg-white dark:bg-white/5">
        <form method="GET" action="{{ route('transaksi.retur') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <select name="kode_mitra"
                class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent">
                <option value="">Semua Mitra</option>
                @foreach ($mitra as $item)
                    <option value="{{ $item->kode_mitra }}" {{ request('kode_mitra') == $item->kode_mitra ? 'selected' : '' }}>
                        {{ $item->nama_mitra }}
                    </option>
                @endforeach
            </select>

            <select name="kode_transaksi"
                class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent">
                <option value="">Semua Transaksi</option>
                @foreach ($transaksiList as $item)
                    <option value="{{ $item->kode_transaksi }}" {{ request('kode_transaksi') == $item->kode_transaksi ? 'selected' : '' }}>
                        {{ $item->kode_transaksi }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent">

            <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent">

            <div class="md:col-span-4 flex gap-2">
                <button type="submit"
                    class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    Filter
                </button>
                <a href="{{ route('transaksi.retur') }}"
                    class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div x-data="{ activeTab: 'pending' }">
        <div class="mb-4 flex flex-wrap gap-2 border-b border-black/10 dark:border-white/10">
            <button type="button" @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-white/5 text-black dark:text-white border-black/10 dark:border-white/10'"
                class="px-4 py-2 text-sm rounded-t-lg border transition">
                Retur Pending
            </button>
            <button type="button" @click="activeTab = 'alur'"
                :class="activeTab === 'alur' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-white/5 text-black dark:text-white border-black/10 dark:border-white/10'"
                class="px-4 py-2 text-sm rounded-t-lg border transition">
                Alur Kembali ke Stok
            </button>
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
                            <tbody>
                                @forelse ($returs as $index => $batch)
                                    <tr>
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
                                            <div x-data="{ openBatch: false }" class="flex items-center gap-2">
                                                <button type="button" @click="openBatch = true"
                                                    class="px-3 py-2 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                                                    Proses Retur
                                                </button>

                                                {{-- Modal Batch --}}
                                                <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                                                    :class="{ 'block': openBatch, 'hidden': !openBatch }">
                                                    <div class="flex items-center justify-center min-h-screen px-4"
                                                        @click.self="openBatch = false">
                                                        <div x-show="openBatch" x-transition x-transition.duration.300
                                                            class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-2xl my-8"
                                                            style="display: none;">
                                                            <div
                                                                class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                                <div>
                                                                    <h5 class="font-semibold text-lg">Detail Retur:
                                                                        {{ $batch->no_retur }}</h5>
                                                                    <p class="text-xs text-gray-500">
                                                                        {{ $batch->items->first()->transaksi->mitra->nama_mitra ?? $batch->kode_mitra }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($batch->tanggal)->format('d/m/Y') }}
                                                                    </p>
                                                                </div>
                                                                <button type="button"
                                                                    class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                                                    @click="openBatch = false">
                                                                    <svg class="w-5 h-5" width="32" height="32"
                                                                        viewBox="0 0 32 32" fill="none">
                                                                        <path
                                                                            d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                                                            fill="currentcolor"></path>
                                                                        <path
                                                                            d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                                                            fill="currentcolor"></path>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            <div class="overflow-y-auto max-h-[70vh]">
                                                                <table class="w-full text-sm">
                                                                    <thead class="bg-gray-50 dark:bg-white/5">
                                                                        <tr>
                                                                            <th class="p-2 text-left">Produk</th>
                                                                            <th class="p-2 text-center">Retur</th>
                                                                            <th class="p-2 text-center">Aksi</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="divide-y divide-black/5 dark:divide-white/5">
                                                                        @foreach($batch->items as $item)
                                                                            <tr>
                                                                                <td class="p-2">
                                                                                    <div class="font-medium">
                                                                                        {{ $item->produk->nama_produk ?? $item->kode_produk }}
                                                                                    </div>
                                                                                    <div class="text-[10px] text-gray-400">
                                                                                        {{ $item->kode_transaksi }}</div>
                                                                                </td>
                                                                                <td
                                                                                    class="p-2  font-bold text-amber-700">
                                                                                    {{ $item->barang_retur }}</td>
                                                                                <td class="p-2 ">
                                                                                    <div x-data="{ openSingle: false }">
                                                                                        <button @click="openSingle = true"
                                                                                            class="px-3 py-2 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg transition cursor-pointer">Kembalikan ke Stok</button>

                                                                                        {{-- Modal Single Item --}}
                                                                                        <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[1000] hidden overflow-y-auto"
                                                                                            :class="{ 'block': openSingle, 'hidden': !openSingle }">
                                                                                            <div class="flex items-center justify-center min-h-screen px-4"
                                                                                                @click.self="openSingle = false">
                                                                                                <div x-show="openSingle"
                                                                                                    x-transition
                                                                                                    class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-md my-8">
                                                                                                    <div
                                                                                                        class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                                                                        <h5 class="font-semibold">
                                                                                                            Kembalikan ke Stok</h5>
                                                                                                        <button type="button"
                                                                                                            @click="openSingle = false"
                                                                                                            class="text-black/40"><svg
                                                                                                                class="w-4 h-4"
                                                                                                                fill="none"
                                                                                                                viewBox="0 0 24 24"
                                                                                                                stroke="currentColor">
                                                                                                                <path
                                                                                                                    stroke-linecap="round"
                                                                                                                    stroke-linejoin="round"
                                                                                                                    stroke-width="2"
                                                                                                                    d="M6 18L18 6M6 6l12 12" />
                                                                                                            </svg></button>
                                                                                                    </div>
                                                                                                    <form
                                                                                                        action="{{ route('transaksi.retur.kembalikan') }}"
                                                                                                        method="POST"
                                                                                                        class="p-5 space-y-4 text-left">
                                                                                                        @csrf
                                                                                                        <input type="hidden"
                                                                                                            name="transaksi_product_id"
                                                                                                            value="{{ $item->id }}">
                                                                                                        <div
                                                                                                            class="p-3 bg-amber-50 rounded-lg">
                                                                                                            <p
                                                                                                                class="text-xs font-bold text-amber-900">
                                                                                                                {{ $item->produk->nama_produk ?? $item->kode_produk }}
                                                                                                            </p>
                                                                                                            <p
                                                                                                                class="text-[10px] text-amber-700">
                                                                                                                {{ $item->kode_transaksi }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <label
                                                                                                                class="block text-xs font-semibold mb-1">Jumlah</label>
                                                                                                            <input type="number"
                                                                                                                name="jumlah_kembali"
                                                                                                                min="1"
                                                                                                                max="{{ $item->barang_retur }}"
                                                                                                                value="{{ $item->barang_retur }}"
                                                                                                                class="w-full form-input text-sm border-gray-300 rounded">
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <label
                                                                                                                class="block text-xs font-semibold mb-1">Keterangan</label>
                                                                                                            <textarea
                                                                                                                name="keterangan_lain"
                                                                                                                rows="2"
                                                                                                                class="w-full form-input text-sm border-gray-300 rounded"
                                                                                                                placeholder="Opsional"></textarea>
                                                                                                        </div>
                                                                                                        <div class="flex gap-2">
                                                                                                            <button type="submit"
                                                                                                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded text-sm font-semibold">Simpan</button>
                                                                                                            <button type="button"
                                                                                                                @click="openSingle = false"
                                                                                                                class="px-4 py-2 bg-gray-100 rounded text-sm">Batal</button>
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
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-3 py-8 text-center text-gray-500">
                                            Belum ada data retur.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <span class="mt-4">{{ $returs->links('vendor.pagination.clean') }}</span>
                </div>
            </div>

            <div class="md:hidden space-y-3">
                @forelse ($returs as $batch)
                    <div class="border border-black/10 dark:border-white/10 rounded-lg p-4 bg-white dark:bg-white/5">
                        <div class="flex items-start justify-between gap-3 mb-2">
                            <div>
                                <p class="text-[10px] font-bold text-blue-600 uppercase">{{ $batch->no_retur }}</p>
                                <p class="text-sm font-semibold">
                                    {{ $batch->items->first()->transaksi->mitra->nama_mitra ?? $batch->kode_mitra }}</p>
                            </div>
                            <span
                                class="px-2 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 text-xs font-semibold">
                                {{ number_format($batch->total_barang_retur, 0, ',', '.') }} item
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-gray-500">
                            <span>{{ \Carbon\Carbon::parse($batch->tanggal)->format('d/m/Y') }}</span>
                            <div x-data="{ openBatch: false }">
                                <button type="button" @click="openBatch = true"
                                    class="text-green-600 font-bold underline">Kelola Retur</button>

                                {{-- Mobile Modal Batch --}}
                                <div class="fixed inset-0 bg-black/60 z-[999] hidden overflow-y-auto"
                                    :class="{ 'block': openBatch, 'hidden': !openBatch }">
                                    <div class="flex items-end sm:items-center justify-center min-h-screen px-0 sm:px-4"
                                        @click.self="openBatch = false">
                                        <div x-show="openBatch" x-transition.origin.bottom
                                            class="bg-white dark:bg-black relative rounded-t-2xl sm:rounded-lg overflow-hidden w-full max-w-lg">
                                            <div class="p-4 border-b flex justify-between items-center">
                                                <h6 class="font-bold">Kelola Retur</h6>
                                                <button @click="openBatch = false" class="text-gray-400"><svg class="w-6 h-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12" />
                                                    </svg></button>
                                            </div>
                                            <div class="p-4 space-y-3 max-h-[80vh] overflow-y-auto">
                                                @foreach($batch->items as $item)
                                                    <div class="p-3 border rounded-lg bg-gray-50 dark:bg-white/5">
                                                        <p class="text-xs font-bold">
                                                            {{ $item->produk->nama_produk ?? $item->kode_produk }}</p>
                                                        <p class="text-[10px] text-gray-500 mb-2">{{ $item->kode_transaksi }} |
                                                            Pending: {{ $item->barang_retur }}</p>

                                                        <div x-data="{ openSingle: false }">
                                                            <button @click="openSingle = true"
                                                                class="w-full py-2 bg-green-600 text-white rounded text-xs font-bold">Kembalikan
                                                                ke Stok</button>

                                                            <div class="fixed inset-0 bg-black/80 z-[1000] hidden"
                                                                :class="{ 'block': openSingle, 'hidden': !openSingle }">
                                                                <div class="flex items-center justify-center h-full p-4"
                                                                    @click.self="openSingle = false">
                                                                    <div
                                                                        class="bg-white dark:bg-black p-5 rounded-lg w-full max-w-xs">
                                                                        <h6 class="font-bold text-sm mb-4">Kembalikan Produk</h6>
                                                                        <form action="{{ route('transaksi.retur.kembalikan') }}"
                                                                            method="POST" class="space-y-4">
                                                                            @csrf
                                                                            <input type="hidden" name="transaksi_product_id"
                                                                                value="{{ $item->id }}">
                                                                            <input type="number" name="jumlah_kembali" min="1"
                                                                                max="{{ $item->barang_retur }}"
                                                                                value="{{ $item->barang_retur }}"
                                                                                class="w-full p-2 border rounded text-sm">
                                                                            <textarea name="keterangan_lain"
                                                                                placeholder="Keterangan (Opsional)"
                                                                                class="w-full p-2 border rounded text-xs"
                                                                                rows="2"></textarea>
                                                                            <div class="flex gap-2">
                                                                                <button type="submit"
                                                                                    class="flex-1 py-2 bg-green-600 text-white rounded text-xs">Simpan</button>
                                                                                <button type="button" @click="openSingle = false"
                                                                                    class="flex-1 py-2 bg-gray-200 rounded text-xs">Batal</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-3 py-8 text-center text-gray-500">Belum ada riwayat
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