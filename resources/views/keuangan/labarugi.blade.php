@extends('layout.main')
@section('title', 'Laporan Laba Rugi')
@section('container')
<div class="max-w-6xl mx-auto py-6">

   

    {{-- Laporan --}}
    <div class="max-w-6xl mx-auto border rounded-xl overflow-hidden">
{{-- Header Laporan --}}
<div class="flex flex-col md:flex-row justify-between items-center border-b px-6 py-4 gap-4">
    {{-- Judul di kiri --}}
    <h2 class="text-2xl font-semibold text-gray-800">
        Laporan Laba Rugi 
        <span class="text-gray-500 text-base font-normal">(Dalam Rupiah)</span>
    </h2>

    {{-- Filter Bulan & Tahun berdempetan --}}
    <form method="GET" class="flex gap-0 items-center">
        <select name="bulan"  style="width: 200px;"
                class="form-input w-36 border border-gray-300 rounded-l-md px-3 py-2 focus:ring-2 focus:ring-blue-400" 
                onchange="this.form.submit()">
            @foreach(range(1, 12) as $b)
                <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($b)->format('F') }}
                </option>
            @endforeach
        </select>

        <input type="number" name="tahun" value="{{ $tahun }}" placeholder="Tahun"
               class="form-input w-24 border border-gray-300 border-l-0 rounded-r-md px-3 py-2 focus:ring-2 focus:ring-blue-400"
               onchange="this.form.submit()">
    </form>
</div>


        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 border-b">
            <div class="p-4 rounded-lg bg-emerald-50 text-center">
                <div class="text-sm text-gray-600">Total Laba Kotor</div>
                <div class="text-lg font-bold text-emerald-600">
                    Rp {{ number_format($labaRugi['laba_kotor'] ?? 0,0,',','.') }}
                </div>
            </div>
            <div class="p-4 rounded-lg bg-blue-50 text-center">
                <div class="text-sm text-gray-600">Total Laba Operasional</div>
                <div class="text-lg font-bold text-blue-600">
                    Rp {{ number_format($labaRugi['laba_operasional'] ?? 0,0,',','.') }}
                </div>
            </div>
            <div class="p-4 rounded-lg bg-purple-50 text-center">
                <div class="text-sm text-gray-600">Total Laba Bersih</div>
                <div class="text-lg font-bold text-purple-600">
                    Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,0,',','.') }}
                </div>
            </div>
        </div>

        {{-- Tabel Detail --}}
        @foreach(['pendapatan','hpp','beban_operasional','pendapatan_lainnya','beban_lainnya'] as $section)
            @php
                $titles = [
                    'pendapatan' => 'Pendapatan',
                    'hpp' => 'Harga Pokok Penjualan',
                    'beban_operasional' => 'Beban Operasional',
                    'pendapatan_lainnya' => 'Pendapatan Lainnya',
                    'beban_lainnya' => 'Beban Lainnya'
                ];
                $totalSection = ($labaRugi[$section] ?? collect())->sum('saldo');
            @endphp
            <div class="divide-y">
                <div class="bg-gray-100 px-6 py-2 font-semibold">{{ $titles[$section] }}</div>
                @foreach($labaRugi[$section] ?? [] as $item)
                    <div class="flex justify-between px-6 py-2 text-sm">
                        <span>{{ $item->nama_akun }}</span>
                        <span>Rp {{ number_format($item->saldo,0,',','.') }}</span>
                    </div>
                @endforeach
                <div class="flex justify-between px-6 py-3 border-t font-bold bg-gray-50">
                    <span>Total {{ $titles[$section] }}</span>
                    <span>Rp {{ number_format($totalSection,0,',','.') }}</span>
                </div>
            </div>
        @endforeach

        {{-- Laba Bersih --}}
        <div class="flex justify-between px-6 py-4 border-t text-base font-extrabold bg-gray-100">
            <span>Laba (Rugi) Bersih</span>
            <span>Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,0,',','.') }}</span>
        </div>

    </div>
</div>
@endsection
