@extends('layout.main')
@section('title', 'Nota dan Kwitansi')
@section('container')
    <div class="space-y-6 animate-in fade-in duration-500">
        <!-- Header Section -->
        <div
            class="bg-lightwhite dark:bg-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white/50 dark:bg-white/5 backdrop-blur-xl p-6 rounded-3xl border border-black/5 dark:border-white/10 shadow-sm transition-all duration-300">
            <div>
                <h3 class=" font-bold   text-dark dark:text-white">
                    Nota & Kwitansi
                </h3>
                <p class="text-sm text-dark dark:white mt-1">Kelola dokumen transaksi manual Anda di sini.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @php
                    function generateTransactionCode()
                    {
                        return 'B' . rand(1000000, 9999999);
                    }
                    $transactionCode = generateTransactionCode();
                @endphp

                <a href="{{ route('transaksi.nota.manual', $transactionCode) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5  text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-blue-600/20">
                    <i class="ph ph-file-plus text-lg"></i>
                    Konsinyasi
                </a>

                <a href="{{ route('transaksi.invoice.manual', $transactionCode) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5  text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-emerald-600/20">
                    <i class="ph ph-receipt text-lg"></i>
                    Invoice
                </a>

                <a href="{{ route('transaksi.kwitansi.manual', $transactionCode) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5  text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-rose-600/20">
                    <i class="ph ph-wallet text-lg"></i>
                    Kwitansi
                </a>
            </div>
        </div>

        <!-- Table Section -->
        <div
            class="bg-white dark:bg-white/5 rounded-3xl border border-black/5 dark:border-white/10 overflow-hidden shadow-sm transition-all duration-300">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50/50 dark:bg-white/5 border-b border-black/5 dark:border-white/10 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4 w-16">#</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Jenis Dokumen</th>
                            <th class="px-6 py-4">Mitra / Pelanggan</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/5">
                        @foreach($data as $index => $item)
                            @php
                                $link = '#';
                                if ($item->type == 'nota_konsinyasi') {
                                    $link = route('transaksi.nota.manual', $item->kode_transaksi);
                                } elseif ($item->type == 'invoice') {
                                    $link = route('transaksi.invoice.manual', $item->kode_transaksi);
                                } elseif ($item->type == 'nota_pembayaran') {
                                    $link = route('transaksi.kwitansi.manual', $item->kode_transaksi);
                                }
                            @endphp
                            <tr onclick="window.location='{{ $link }}'"
                                class="group hover:bg-gray-50 dark:hover:bg-white/5 transition-colors cursor-pointer">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                            <i class="ph ph-calendar-blank"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-white">
                                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $item->kode_transaksi }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        @if($item->type == 'nota_konsinyasi') bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400
                                        @elseif($item->type == 'invoice') bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400
                                        @else bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400 @endif">
                                        {{ str_replace('_', ' ', $item->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-white">{{ $item->kepada }}</span>
                                        <span class="text-[11px] text-gray-400">{{ $item->kota }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->grandtotal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ $link }}"
                                            class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                            title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <a href="/nota/delete/{{$item->id}}"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')"
                                            class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors"
                                            title="Hapus">
                                            <i class="ph ph-trash text-lg"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        @if($data->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <div
                                            class="p-6 rounded-full bg-gray-50 dark:bg-white/5 text-gray-200 dark:text-gray-700">
                                            <i class="ph ph-file-x text-6xl"></i>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada nota yang dibuat</p>
                                        <p class="text-xs text-gray-400">Gunakan tombol di atas untuk membuat dokumen baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection