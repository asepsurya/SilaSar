@extends('layout.main')
@section('title', 'Data Akun')
@section('container')


    <h1 class="text-xl font-bold mb-4"> Neraca Saldo</h1>

   <form method="GET" action="{{ route('laporan.neraca_saldo') }}" 
      class="flex flex-wrap items-end gap-3 mb-6 bg-white dark:bg-black/10 p-4 rounded-lg shadow-sm border border-black/10">

    {{-- Pilih Bulan --}}
    <div class="flex flex-col">
        <label for="bulan" class="text-sm text-gray-600 dark:text-gray-300 mb-1">Bulan</label>
        <select name="bulan" id="bulan"   style="width: 200px;"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach(range(1,12) as $b)
                <option value="{{ sprintf('%02d',$b) }}" {{ $b == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Pilih Tahun --}}
    <div class="flex flex-col">
        <label for="tahun" class="text-sm text-gray-600 dark:text-gray-300 mb-1">Tahun</label>
        <select name="tahun" id="tahun"  style="width: 100px;"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach(range(now()->year-2, now()->year+2) as $t)
                <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Tombol Submit --}}
    <div class="flex">
        <button type="submit"
                class="h-[42px] px-5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors duration-200">
            Tampilkan
        </button>
    </div>
</form>


     @php
        $totalDebit = $data->sum('saldo_debit');
        $totalKredit = $data->sum('saldo_kredit');
    @endphp

    <table class="w-full border text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-1 text-left">Kode</th>
                <th class="border px-2 py-1 text-left">Akun</th>
                <th class="border px-2 py-1 text-right">Saldo Debit</th>
                <th class="border px-2 py-1 text-right">Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $akun)
            <tr>
                <td class="border px-2 py-1">{{ $akun->kode_akun }}</td>
                <td class="border px-2 py-1">{{ $akun->nama_akun }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($akun->saldo_debit,0,',','.') }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($akun->saldo_kredit,0,',','.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Tidak ada transaksi bulan ini</td>
            </tr>
            @endforelse

            {{-- Total --}}
            <tr class="bg-gray-100 font-semibold">
                <td colspan="2" class="border px-2 py-2 text-right">Total</td>
                <td class="border px-2 py-2 text-right">{{ number_format($totalDebit,0,',','.') }}</td>
                <td class="border px-2 py-2 text-right">{{ number_format($totalKredit,0,',','.') }}</td>
            </tr>

            {{-- Balance --}}
            <tr class="bg-blue-50 font-bold">
                <td colspan="2" class="border px-2 py-2 text-right">Balance</td>
                <td colspan="2" class="border px-2 py-2 text-center">
                    @if($totalDebit == $totalKredit)
                        ✅ Seimbang
                    @else
                        ⚠️ Tidak Seimbang (Selisih: {{ number_format($totalDebit - $totalKredit,0,',','.') }})
                    @endif
                </td>
            </tr>
        </tbody>
    </table>



@endsection