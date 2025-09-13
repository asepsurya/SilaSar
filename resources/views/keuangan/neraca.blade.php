@extends('layout.main')
@section('title', 'Data Akun')
@section('container')
<div class="max-w-6xl mx-auto">

    {{-- =================== NERACA =================== --}}
    <div class=" dark:bg-gray-800 shadow-lg rounded-2xl">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            📊 <span>Laporan Neraca</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach (['aset','liabilitas','ekuitas'] as $tipe)
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold uppercase text-gray-700 dark:text-gray-200 border-b pb-2">{{ $tipe }}</h3>

                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($neraca[$tipe] ?? [] as $akun)
                            <li class="flex justify-between py-1 text-sm">
                                <span class="text-gray-600 dark:text-gray-300">{{ $akun->nama_akun }}</span>
                                <span class="font-medium">{{ number_format($akun->saldo,0,',','.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between font-semibold text-gray-800 dark:text-gray-100">
                        <span>Total</span>
                        <span>{{ number_format(($neraca[$tipe] ?? collect())->sum('saldo'),0,',','.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- =================== LABA RUGI =================== --}}
    <div class=" dark:bg-gray-800  rounded-2xl p-8">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            📈 <span>Laporan Laba Rugi</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach (['pendapatan','beban'] as $tipe)
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold uppercase text-gray-700 dark:text-gray-200 border-b pb-2">{{ $tipe }}</h3>

                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($labaRugi[$tipe] ?? [] as $akun)
                            <li class="flex justify-between py-1 text-sm">
                                <span class="text-gray-600 dark:text-gray-300">{{ $akun->nama_akun }}</span>
                                <span class="font-medium">{{ number_format($akun->saldo,0,',','.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between font-semibold text-gray-800 dark:text-gray-100">
                        <span>Total</span>
                        <span>{{ number_format(($labaRugi[$tipe] ?? collect())->sum('saldo'),0,',','.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between mt-10 border-t border-gray-200 dark:border-gray-700 pt-6 text-xl font-bold text-gray-900 dark:text-gray-100">
            <span>Laba Bersih</span>
            <span class="text-green-600 dark:text-green-400">
                {{ number_format(($labaRugi['pendapatan'] ?? collect())->sum('saldo') - ($labaRugi['beban'] ?? collect())->sum('saldo'),0,',','.') }}
            </span>
        </div>
    </div>

</div>
@endsection
