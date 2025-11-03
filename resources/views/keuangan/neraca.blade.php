@extends('layout.main')
@section('title', 'Laporan Neraca')
@section('container')
<div class="max-w-6xl mx-auto  border border-black/10 dark:border-white/10 rounded-xl overflow-hidden">

    {{-- Header --}}
    <div class="flex justify-between items-center border-b  dark:border-white/10 px-6 py-4">
        <h2 class="text-xl font-bold">Laporan Neraca <span class="text-gray-500">(Dalam Rupiah)</span></h2>
  <form id="filterForm" method="GET" class="flex gap-0 items-center">
    <select name="bulan" id="bulanSelect" style="width: 200px;"
        class="form-select w-36 border dark:border-white/10 border-gray-300 rounded-l-md px-3 py-2 focus:ring-2 focus:ring-blue-400"
        onchange="this.form.submit()">
        @foreach(range(1, 12) as $b)
        <option value="{{ $b }}" {{ $b == $bulan ? 'selected' : '' }}>
         {{ \Carbon\Carbon::createFromDate(null, (int)$b, 1)->translatedFormat('F') }}

        </option>
        @endforeach
    </select>

    <input type="number" id="tahunInput" name="tahun" value="{{ $tahun }}" placeholder="Tahun"
        class="form-input w-24 border dark:border-white/10 border-gray-300 border-l-0 rounded-r-md px-3 py-2 focus:ring-2 focus:ring-blue-400"
        onchange="this.form.submit()">

    <a id="pdfLink" href="{{ route('laporan.neracaPdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}"
       class="btn ms-3 m-5">Cetak PDF</a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bulanSelect = document.getElementById('bulanSelect');
    const tahunInput = document.getElementById('tahunInput');
    const pdfLink = document.getElementById('pdfLink');

    function updatePdfLink() {
        const bulan = bulanSelect.value;
        const tahun = tahunInput.value;
        pdfLink.href = `{{ route('laporan.neracaPdf') }}?bulan=${bulan}&tahun=${tahun}`;
    }

    bulanSelect.addEventListener('change', updatePdfLink);
    tahunInput.addEventListener('change', updatePdfLink);
});
</script>



    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-6 py-4 border-b border-black/10 dark:border-white/10">
        <div class="p-4 rounded-lg bg-lightblue-100 text-center">
            <div class="text-sm text-gray-600">Total Aset</div>
            <div class="text-2xl leading-9 font-semibold text-black">
                Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}
            </div>
        </div>
        <div class="p-4 rounded-lg bg-lightpurple-100 text-center">
            <div class="text-sm text-gray-600">Hutang</div>
            <div class="text-2xl leading-9 font-semibold text-black">
                Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}
            </div>
        </div>
        <div class="p-4 rounded-lg bg-lightblue-100  text-center">
            <div class="text-sm text-gray-600">Ekuitas</div>
            <div class="text-2xl leading-9 font-semibold text-black">
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
    <div class="bg-gray-100 dark:bg-white/5 px-6 py-2 font-semibold">ASET</div>

    <div class="">
        @foreach ($neraca['aset'] ?? [] as $akun)
            <div class="border-b dark:border-white/10 flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="border-b dark:border-white/10 flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH ASET</span>
        <span>
            Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== HUTANG =================== --}}
    <div class="bg-gray-100 dark:bg-white/5 px-6 py-2 font-semibold">HUTANG</div>

    <div class="">
        @foreach ($neraca['liabilitas'] ?? [] as $akun)
            <div class="border-b dark:border-white/10 flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="border-b dark:border-white/10 flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH HUTANG</span>
        <span>
            Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== EKUITAS =================== --}}
    <div class="bg-gray-100 dark:bg-white/5 px-6 py-2 font-semibold">EKUITAS</div>

    <div class="">
        @foreach ($neraca['ekuitas'] ?? [] as $akun)
            <div class="border-b dark:border-white/10 flex justify-between px-6 py-2 text-sm">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
    </div>

    <div class="border-b dark:border-white/10 flex justify-between px-6 py-3 border-t font-bold">
        <span>JUMLAH EKUITAS</span>
        <span>
            Rp {{ number_format(($neraca['ekuitas'] ?? collect())->sum('saldo'),0,',','.') }}
        </span>
    </div>

    {{-- =================== TOTAL =================== --}}
    <div class="flex justify-between px-6 py-4 border-t dark:border-white/10 text-base font-extrabold bg-gray-100 dark:bg-white/5">
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
