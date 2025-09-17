@extends('layout.main')
@section('title', 'Laporan Transaksi')
@section('container')

<div class=" dark:border-white/10 md:px-6 mb-4">
    {{-- Judul --}}
    <h2 class="text-xl md:text-2xl font-semibold text-gray-800 dark:text-white mb-4 md:mb-3">
        Laporan Transaksi
    </h2>

    {{-- Filter + Tombol PDF (selalu sejajar) --}}
    <div class="flex flex-row items-center justify-between gap-3 w-full">

        {{-- Filter Bulan & Tahun --}}
        <form method="GET" class="flex flex-1">
            <select name="bulan"
                class="flex-1 sm:w-40 border dark:border-white/10 border-gray-300 dark:bg-white/10 rounded-l-md px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm"
                onchange="this.form.submit()">
                @foreach(range(1, 12) as $b)
                    <option value="{{ $b }}" {{ $b==$bulan ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="tahun" value="{{ $tahun }}" placeholder="Tahun"
                class="w-24 border dark:border-white/10 border-gray-300  dark:bg-white/10 border-l-0 rounded-r-md px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm"
                onchange="this.form.submit()">
        </form>

        {{-- Tombol PDF --}}
        <a href="{{ route('keuangan.pdf', request()->query()) }}" target="_blank"
            class="shrink-0 p-2 rounded-md bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 dark:border-white/10 flex items-center justify-center"
            title="Download PDF">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0h6v4H6v-4h6z" />
            </svg>
        </a>
    </div>
</div>


        <div class="border border-black/10 dark:border-white/10 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gray-50 dark:bg-white/10 border-b border-black/10 dark:border-white/10">
                        <tr class="text-xs md:text-sm">
                            <th class="px-3 py-3 text-left sticky left-0 bg-gray-50 dark:bg-white/10">Waktu</th>
                            <th class="px-3 py-3 text-left">Deskripsi</th>
                            <th class="px-3 py-3 text-left">Jenis</th>
                            <th colspan="2" class="px-3 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                        @php 
                            use Carbon\Carbon;
                            $lastTanggal = null; 
                            $grandMasuk = 0;
                            $grandKeluar = 0;
                        @endphp

                        @forelse($transaksi as $t)
                            {{-- HEADER TANGGAL --}}
                            @if($t->tanggal !== $lastTanggal)
                                @php
                                    $hari = Carbon::createFromFormat('d/m/Y', $t->tanggal)
                                            ->locale('id')
                                            ->translatedFormat('l');
                                    $harianMasuk = $transaksi->where('tanggal',$t->tanggal)->where('tipe','pemasukan')->sum('total');
                                    $harianKeluar = $transaksi->where('tanggal',$t->tanggal)->where('tipe','pengeluaran')->sum('total');
                                    $lastTanggal = $t->tanggal;
                                @endphp

                                <tr class="bg-gray-100 dark:bg-white/10 border-t border-black/20 dark:border-white/20">
                                    <td colspan="3" class="px-4 py-3">
                                        <div class="font-semibold text-black/80 dark:text-white/80 text-sm">
                                            {{ $hari }}, {{ $t->tanggal }}
                                        </div>
                                    </td>
                                    <td colspan="2" class="px-4 py-3">
                                        <div class="flex flex-col md:flex-row justify-end md:gap-6 gap-1 text-xs md:text-sm text-right">
                                            <div class="text-green-600 dark:text-green-400 font-medium">
                                                Pemasukan: Rp {{ number_format($harianMasuk,0,',','.') }}
                                            </div>
                                            <div class="text-red-600 dark:text-red-400 font-medium">
                                                Pengeluaran: Rp {{ number_format($harianKeluar,0,',','.') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endif

                            {{-- BARIS TRANSAKSI --}}
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-3 py-2 text-xs md:text-sm text-black/70 dark:text-white/70 whitespace-nowrap">
                                    {{ $t->waktu }}
                                </td>
                                <td class="px-3 py-2 text-xs md:text-sm break-words max-w-[220px]">
                                    {{ $t->deskripsi }}
                                </td>
                                <td class="px-3 py-2 text-xs md:text-sm capitalize">
                                    {{ $t->tipe }}
                                </td>
                                <td colspan="2" class="px-3 py-2 text-xs md:text-sm text-right font-medium whitespace-nowrap">
                                    Rp {{ number_format($t->total, 0, ',', '.') }}
                                </td>
                            </tr>

                            @php 
                                if ($t->tipe === 'pemasukan') {
                                    $grandMasuk += $t->total;
                                } else {
                                    $grandKeluar += $t->total;
                                }
                            @endphp
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-500 text-sm">
                                    Tidak ada transaksi untuk bulan ini
                                </td>
                            </tr>
                        @endforelse

                        {{-- GRAND TOTAL --}}
                        @if($grandMasuk + $grandKeluar > 0)
                            <tr class="bg-gray-200 dark:bg-white/10 font-bold border-t border-black/20 dark:border-white/20">
                                <td colspan="4" class="px-4 py-3 text-right text-black/80 dark:text-white/80 text-xs md:text-sm">
                                    
                                </td>
                                <td  class="px-4 py-3 text-right text-green-600 dark:text-green-400 text-xs md:text-sm">
                                    Total Pemasukan :  Rp {{ number_format($grandMasuk, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="bg-gray-200 dark:bg-white/10 font-bold border-t border-black/20 dark:border-white/20">
                                <td colspan="4" class="px-4 py-3 text-right text-black/80 dark:text-white/80 text-xs md:text-sm">
                                    
                                </td>
                                <td  class="px-4 py-3 text-right text-red-600 dark:text-red-400 text-xs md:text-sm">
                                    Total Pengeluaran :  Rp {{ number_format($grandKeluar, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>


@endsection