@extends('layout.main')
@section('title', 'Laporan Neraca')
@section('container')
<div class="max-w-6xl mx-auto  border rounded-xl overflow-hidden">

    {{-- Header --}}
    <div class="flex justify-between items-center border-b  dark:border-white/10 px-6 py-4">
        <h2 class="text-xl font-bold">Laporan Neraca <span class="text-gray-500">(Dalam Rupiah)</span></h2>
        <button class="bg-emerald-500 text-white text-sm px-4 py-2 rounded-md shadow hover:bg-emerald-600">
            📥 Ekspor Laporan
        </button>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 border-b dark:border-white/10">
        <div class="p-4 rounded-lg bg-lightblue-100 text-center">
            <div class="text-sm text-gray-600">Total Aset</div>
            <div class="text-lg font-bold text-emerald-600">
                Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}
            </div>
        </div>
        <div class="p-4 rounded-lg bg-lightpurple-100 text-center">
            <div class="text-sm text-gray-600">Hutang</div>
            <div class="text-lg font-bold text-red-600">
                Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}
            </div>
        </div>
        <div class="p-4 rounded-lg bg-lightblue-100  text-center">
            <div class="text-sm text-gray-600">Ekuitas</div>
            <div class="text-lg font-bold text-blue-600">
                Rp {{ number_format(($neraca['ekuitas'] ?? collect())->sum('saldo'),0,',','.') }}
            </div>
        </div>
    </div>

    {{-- Table header --}}
    <div class=" dark:bg-gray-700 px-6 py-2 font-semibold flex justify-between text-sm">
        <span>DESKRIPSI</span>
        <span>SALDO</span>
    </div>

    {{-- =================== ASET =================== --}}
    <div class="bg-gray-100 dark:bg-gray-800 px-6 py-2 font-semibold">ASET</div>

    <div class="divide-y dark:divide-y/10">
        @foreach ($neraca['aset'] ?? [] as $akun)
            <div class="flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH ASET</span>
        <span>
            Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== HUTANG =================== --}}
    <div class="bg-gray-100 dark:bg-gray-800 px-6 py-2 font-semibold">HUTANG</div>

    <div class="divide-y">
        @foreach ($neraca['liabilitas'] ?? [] as $akun)
            <div class="flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH HUTANG</span>
        <span>
            Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== EKUITAS =================== --}}
    <div class="bg-gray-100 dark:bg-gray-800 px-6 py-2 font-semibold">EKUITAS</div>

    <div class="divide-y">
        @foreach ($neraca['ekuitas'] ?? [] as $akun)
            <div class="flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH EKUITAS</span>
        <span>
            Rp {{ number_format(($neraca['ekuitas'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== TOTAL =================== --}}
    <div class="flex justify-between px-6 py-4 border-t text-base font-extrabold bg-gray-100 dark:bg-gray-700">
        <span>JUMLAH HUTANG DAN EKUITAS</span>
        <span>
            Rp {{ number_format(
                ($neraca['liabilitas'] ?? collect())->sum('saldo') +
                ($neraca['ekuitas'] ?? collect())->sum('saldo')
            ,0,',','.') }}
        </span>
    </div>
</div>


@endsection
