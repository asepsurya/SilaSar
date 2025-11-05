@extends('layout.main')
@section('title', 'Laporan Neraca Saldo')
@section('container')
<style>
/* 🔹 Efek scale tabel hanya di layar kecil */

  @media (max-width: 768px) {
            body {
                zoom: 0.9; /* Zoom-out untuk layar kecil */
            }

            table {
                font-size: 14px; /* Mengurangi ukuran font untuk tampilan kecil */
            }

            .search-icon {
                width: 24px;
                height: 24px;
            }

            /* Menyesuaikan ukuran tabel agar lebih baik di mobile */
            th, td {
                padding: 5px;
            }
        }

        /* Lebih responsif jika ukuran lebih kecil dari 500px */
        @media (max-width: 500px) {
            body {
                zoom: 0.7; /* Lebih kecil lagi di perangkat lebih kecil */
            }

            table {
                font-size: 12px; /* Ukuran font semakin kecil */
            }
        }
</style>

<h2 class="text-2xl font-semibold text-gray-800 mb-3">
   Neraca Saldo
    <span class="text-gray-500 text-base font-normal">(Dalam Rupiah)</span>
</h2>

<form method="GET" action="{{ route('laporan.neraca_saldo') }}"
      class="flex flex-wrap items-end gap-3 mb-6 bg-white dark:bg-white/5 
             dark:border-white/10 p-4 rounded-lg shadow-sm border border-black/10">

    {{-- Pilih Bulan --}}
    <div class="flex flex-col">
        <select name="bulan" id="bulan" style="width: 200px;"
                onchange="this.form.submit()"
                class="form-input mt-2 py-2.5 px-4 w-full text-black dark:text-white 
                       border border-black/10 dark:border-white/10 rounded-lg 
                       placeholder:text-black/20 dark:placeholder:text-white/20 
                       focus:border-black dark:focus:border-white/10 focus:ring-0 
                       focus:shadow-none">
            @foreach(range(1,12) as $b)
                <option value="{{ sprintf('%02d',$b) }}" {{ $b == $bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Pilih Tahun --}}
    <div class="flex flex-col">
        <select name="tahun" id="tahun" style="width: 100px;"
                onchange="this.form.submit()"
                class="form-input mt-2 py-2.5 px-4 w-full text-black dark:text-white 
                       border border-black/10 dark:border-white/10 rounded-lg 
                       placeholder:text-black/20 dark:placeholder:text-white/20 
                       focus:border-black dark:focus:border-white/10 focus:ring-0 
                       focus:shadow-none">
            @foreach(range(now()->year-2, now()->year+2) as $t)
                <option value="{{ $t }}" {{ $t == $tahun ? 'selected' : '' }}>
                    {{ $t }}
                </option>
            @endforeach
        </select>
    </div>

</form>



     @php
        $totalDebit = $data->sum('saldo_debit');
        $totalKredit = $data->sum('saldo_kredit');
    @endphp

<div class="w-full overflow-x-auto">
<div class="responsive-table-scale">
    <table class="min-w-[600px] w-full border dark:border-white/10 text-xs">
        <thead>
            <tr class="bg-gray-100 dark:bg-white/5">
                <th class="border dark:border-white/10 px-2 py-1 text-left w-[20%]">Kode</th>
                <th class="border dark:border-white/10 px-2 py-1 text-left w-[40%]">Akun</th>
                <th class="border dark:border-white/10 px-2 py-1 text-right w-[20%]">Saldo Debit</th>
                <th class="border dark:border-white/10 px-2 py-1 text-right w-[20%]">Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $akun)
            <tr>
                <td class="border dark:border-white/10 px-2 py-1">{{ $akun->kode_akun }}</td>
                <td class="border dark:border-white/10 px-2 py-1 truncate">{{ $akun->nama_akun }}</td>
                <td class="border dark:border-white/10 px-2 py-1 text-right">{{ number_format($akun->saldo_debit,0,',','.') }}</td>
                <td class="border dark:border-white/10 px-2 py-1 text-right">{{ number_format($akun->saldo_kredit,0,',','.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4">Tidak ada transaksi bulan ini</td>
            </tr>
            @endforelse

            {{-- Total --}}
            <tr class="bg-gray-100 font-semibold dark:bg-white/5">
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-right">Total</td>
                <td class="border dark:border-white/10 px-2 py-2 text-right">{{ number_format($totalDebit,0,',','.') }}</td>
                <td class="border dark:border-white/10 px-2 py-2 text-right">{{ number_format($totalKredit,0,',','.') }}</td>
            </tr>

            {{-- Balance --}}
            <tr class="bg-blue-50 font-bold dark:bg-white/5">
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-right">Balance</td>
                <td colspan="2" class="border dark:border-white/10 px-2 py-2 text-center">
                    @if($totalDebit == $totalKredit)
                        ✅ Seimbang
                    @else
                        ⚠️ Tidak Seimbang (Selisih: {{ number_format($totalDebit - $totalKredit,0,',','.') }})
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>





@endsection
