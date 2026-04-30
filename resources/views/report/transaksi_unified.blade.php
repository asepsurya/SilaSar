@extends('layout.main')

@section('title', 'Laporan Penjualan')

@section('container')
<style>
    /* Add any view-specific overrides if necessary */
    .select2-container--default .select2-selection__rendered {
        color: #333333;
        margin-top: -13px;

        margin-right: 10px;
        font-size: 14px;
    }
</style>
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
            color: #111;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            table-layout: fixed;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #666;
            padding: 4px 6px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .report-table th {
            background: #f2f2f2;
            text-align: center;
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

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .tab-active {
            border-bottom: 2px solid #3b82f6;
            color: #2563eb;
        }

        .tab-inactive {
            color: #6b7280;
        }
               /* Sidebar icon normal */
         .tombol .ph {
            font-size: 24px;
            color: #374151;
            /* gray-700 */
            transition: color .2s ease;
        }

        /* Hover */
        .tombol .ph:hover {
            color: #111827;
        }

        /* Dark mode */
      .dark  .tombol  .ph {
            color: #d1d5db;
            /* gray-300 */
        }

         .dark .tombol .ph:hover {
            color: #ffffff;
        }
    </style>
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
    <div class="bg-lightwhite dark:bg-black min-h-screen flex flex-col no-print-bg" x-data="{ tab: '{{ request('tab', 'transaksi') }}', filterOpen: false }">
        <div
            class="no-print flex items-center justify-between border dark:border-white/10 px-4 h-14 header bg-white dark:bg-white/10">
            <div class="flex items-center space-x-3">
                <span class="text-sm truncate max-w-[180px]"><b>Laporan Penjualan Dashboard</b></span>
            </div>
            <div class="flex items-center space-x-2">
            <button @click="filterOpen = true"
                class="tombol btn-secondary px-4 py-2 text-sm flex items-center space-x-2">
                <i class="ph ph-funnel text-[20px] leading-none text-gray-600 dark:text-gray-300 !text-[22px]"></i>
                <span>Filter</span>
            </button>
                <template x-if="tab === 'transaksi'">
                    <a href="{{ route('laporan.exportPDF', request()->query()) }}"
                        class="btn px-6 text-base w-full sm:w-auto text-center">Cetak PDF Detail</a>
                </template>
                <template x-if="tab === 'laporan'">
                    <a href="{{ route('laporan.exportPDFRekap', request()->query()) }}"
                        class="btn px-6 text-base w-full sm:w-auto text-center">Cetak PDF Rekap</a>
                </template>
            </div>
        </div>

        <!-- Tab Buttons -->
        <div class="no-print bg-white dark:bg-white/5 border-b dark:border-white/10 px-4">
            <div class="flex space-x-4">
                <button @click="tab='pelanggan'" :class="tab==='pelanggan' ? 'tab-active' : 'tab-inactive'"
                    class="px-4 py-3 text-sm font-medium transition-colors">
                    <i class="ph ph-users  leading-none text-gray-600 dark:text-gray-300 !text-[22px]"></i> Pelanggan
                </button>
                <button @click="tab='transaksi'" :class=    "tab==='transaksi' ? 'tab-active' : 'tab-inactive'"
                    class="px-4 py-3 text-sm font-medium transition-colors">
                    <i class="ph ph-file leading-none text-gray-600 dark:text-gray-300 !text-[22px]"></i> Transaksi
                </button>
                <button @click="tab='laporan'" :class="tab==='laporan' ? 'tab-active' : 'tab-inactive'"
                    class="px-4 py-3 text-sm font-medium transition-colors">
                    <i class="ph ph-chart-bar  leading-none text-gray-600 dark:text-gray-300 !text-[22px]"></i> Laporan
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden content">
            <div class="flex-1 overflow-auto flex items-start justify-center p-5">

                <!-- TAB: PELANGGAN -->
                <div x-show="tab==='pelanggan'" class="w-full max-w-4xl bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 text-black">
                    <h3 class="text-lg font-bold mb-4 dark:text-black">Summary Pelanggan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="p-3 dark:text-black">Nama Pelanggan</th>
                                    <th class="p-3 text-center dark:text-black">Total Transaksi</th>
                                    <th class="p-3 text-right dark:text-black">Total Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customerSummaries as $summary)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="p-3 dark:text-gray-200">{{ $summary->nama_pelanggan }}</td>
                                        <td class="p-3 text-center dark:text-gray-200">{{ $summary->total_transaksi }}</td>
                                        <td class="p-3 text-right dark:text-gray-200">Rp
                                            {{ number_format($summary->total_nominal, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="p-3 text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TAB: TRANSAKSI (DETAIL) -->
                <div x-show="tab==='transaksi'" class="report-sheet shadow-lg p-5">
                    <h2 class="text-center text-lg font-bold uppercase mb-1">Laporan Penjualan Detail
                        {{ $id_kota ? '- ' . $id_kota : '' }}</h2>
                    <p class="text-center text-sm mb-4">Periode: {{ $awal ?? '-' }} s/d {{ $akhir ?? '-' }}</p>

                    @foreach($laporan->groupBy('kode_transaksi') as $kode => $transaksi)
                        @php $header = $transaksi->first();
                        $totalAkhir = $transaksi->sum('total'); @endphp
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
                                <td style="white-space: normal; width:150px;">{{ $header->nama_pelanggan }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat / Kota</strong></td>
                                <td colspan="3" style="white-space: normal; width:150px;">{{ $header->alamat }}
                                    ({{ $header->kota_mitra }})</td>
                            </tr>
                        </table>

                        <table class="report-table mb-2">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Kode</th>
                                    <th>Nama Produk</th>
                                    <th width="8%">Keluar</th>
                                    <th width="8%">Terjual</th>
                                    <th width="8%">Retur</th>
                                    <th width="10%">Satuan</th>
                                    <th width="15%">Harga</th>
                                    <th width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi as $i => $item)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td style="white-space: normal; width:150px;">{{ $item->nama_produk }}</td>
                                        <td class="text-center">{{ number_format($item->barang_keluar ?? 0, 0) }}</td>
                                        <td class="text-center">{{ number_format($item->jumlah ?? 0, 0) }}</td>
                                        <td class="text-center">{{ number_format($item->barang_retur ?? 0, 0) }}</td>
                                        <td class="text-center">{{ $item->satuan }}</td>
                                        <td class="text-right">{{ number_format($item->harga, 0) }}</td>
                                        <td class="text-right">{{ number_format($item->total, 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex justify-end mb-4 font-bold">
                            <span>Total Akhir : Rp {{ number_format($totalAkhir, 0) }}</span>
                        </div>
                        <hr class="border-dashed mb-6">
                    @endforeach
                </div>

                <!-- TAB: LAPORAN (REKAP) -->
                <div x-show="tab==='laporan'" class="report-sheet shadow-lg p-5">
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
                        .report-table thead th {
                            background: #f4d03f !important;
                            color: #111827 !important;
                            text-align: center;
                            font-weight: 700;
                        }

                        .grand-row td {
                            background: #9fd27b !important;
                            font-weight: 700;
                        }
                    </style>
                    <h2 class="text-center text-lg font-bold uppercase mb-1">Laporan Penjualan Rekap
                        {{ $id_kota ? '- ' . $id_kota : '' }}</h2>
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
                                        @if($loop->first)
                                            <td rowspan="{{ $rowspan }}" class="text-center">{{ $loop->parent->iteration }}</td>
                                            <td rowspan="{{ $rowspan }}" class="text-center">{{ $kode }}</td>
                                            <td rowspan="{{ $rowspan }}" class="text-center">
                                                {{ \Carbon\Carbon::parse($header->tanggal_transaksi)->format('d-M-y') }}</td>
                                            <td rowspan="{{ $rowspan }}" style="white-space: normal; width:150px;">
                                                {{ $header->nama_pelanggan }}</td>
                                            <td rowspan="{{ $rowspan }}" style="white-space: normal; width:150px;">
                                                {{ $header->kota_mitra }}</td>
                                        @endif
                                        <td style="white-space: normal; width:150px;">{{ $item->nama_produk }}</td>
                                        <td class="text-center">{{ number_format($item->barang_keluar ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-right">Rp {{ number_format($item->total ?? 0, 0, ',', '.') }}</td>
                                        @if($loop->first)
                                            <td rowspan="{{ $rowspan }}" class="text-right">Rp
                                                {{ number_format($invoiceTotal, 0, ',', '.') }}</td>
                                        @endif
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

    <!-- Modal Filter -->
    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
        :class="{ 'block': filterOpen, 'hidden': !filterOpen }">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="filterOpen = false">
            <div x-show="filterOpen" x-transition x-transition.duration.300
                class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                style="display: none;">
                <!-- Header -->
                <div
                    class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Filter Transaksi</h5>
                    <button type="button"
                        class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                        @click="filterOpen = false">
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
                <div class="p-5 max-h-[420px] overflow-y-auto">
                    <form method="GET" action="{{ route('laporan.penjualan') }}" class="flex flex-col gap-5">
                        <input type="hidden" name="tab" :value="tab">
                        <!-- Status Pembayaran -->
                        <div>
                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Status
                                Pembayaran</label>
                            <select name="status_bayar"
                                class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full select2 bg-transparent dark:bg-transparent text-black dark:text-white">
                                <option value="">Semua Status</option>
                                <option value="Belum Bayar" {{ request('status_bayar') == 'Belum Bayar' ? 'selected' : '' }}>
                                    Belum Bayar</option>
                                <option value="Sudah Bayar" {{ request('status_bayar') == 'Sudah Bayar' ? 'selected' : '' }}>
                                    Sudah Bayar</option>
                            </select>
                        </div>

                        <!-- Mitra -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Mitra</label>
                            <select name="kode_mitra" id="mitraFilter"
                                class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full select2 bg-transparent dark:bg-transparent text-black dark:text-white">
                                <option value="">Semua Mitra</option>
                                @foreach ($mitras as $m)
                                    <option value="{{ $m->kode_mitra }}"
                                        {{ request('kode_mitra') == $m->kode_mitra ? 'selected' : '' }}>
                                        {{ $m->nama_mitra }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Kota --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Kota</label>
                            <select name="id_kota" id="kotaFilter"
                                class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full select2 bg-transparent dark:bg-transparent text-black dark:text-white">
                                <option value="">Semua Kota</option>
                                @foreach ($regencies as $k)
                                    <option value="{{ $k->name }}"
                                        {{ request('id_kota') == $k->name ? 'selected' : '' }}>
                                        {{ $k->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Waktu -->
                        <div>
                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode
                                Waktu</label>
                            <div class="flex flex-col gap-2">
                                <select name="periode" id="periodeFilter"
                                    class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                    <option value="">Semua Waktu</option>
                                    <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan
                                    </option>
                                    <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan
                                    </option>
                                    <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang
                                        Tanggal</option>
                                </select>
                                <div id="filterBulanan" class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                    <div class="flex gap-2">
                                        <select name="bulan"
                                            class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                            <option value="">Pilih Bulan</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}"
                                                    {{ request('bulan') == $m ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                </option>
                                            @endfor
                                        </select>
                                        <select name="tahun_bulan"
                                            class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                            <option value="">Pilih Tahun</option>
                                            @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                                                <option value="{{ $y }}"
                                                    {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div id="filterTahunan" class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                    <select name="tahun_tahun"
                                        class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                        <option value="">Pilih Tahun</option>
                                        @for ($y = date('Y') - 5; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}"
                                                {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div id="filterRentang" class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                    <div class="flex gap-2">
                                        <input type="date" name="tanggal_awal" value="{{ $awal }}"
                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                            placeholder="Tanggal Awal">
                                        <input type="date" name="tanggal_akhir" value="{{ $akhir }}"
                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                            placeholder="Tanggal Akhir">
                                    </div>
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
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            function showFilterFields() {
                                var periode = document.getElementById('periodeFilter').value;
                                document.getElementById('filterBulanan').classList.toggle('hidden', periode !==
                                    'bulanan');
                                document.getElementById('filterTahunan').classList.toggle('hidden', periode !==
                                    'tahunan');
                                document.getElementById('filterRentang').classList.toggle('hidden', periode !==
                                    'rentang');
                            }
                            document.getElementById('periodeFilter').addEventListener('change', showFilterFields);
                            showFilterFields();
                            $('#mitraFilter, #kotaFilter').select2({
                                width: '100%',
                                placeholder: "Cari...",
                                allowClear: true
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection