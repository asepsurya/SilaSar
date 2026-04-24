@extends('layout.main')

@section('title', 'Data Mitra')

@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/simple-datatables.css') }}" />
@endsection

@section('container')



{{-- ------------------------------------------------------------------------- --}}
<div x-data="modals">
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Data Mitra</h2>
        <button class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" @click="toggle">
            + Tambah Mitra Baru
        </button>
    </div>

    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="open &amp;&amp; '!block'">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition="" x-transition.duration.300="" class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Tambah Mitra</h5>
                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="toggle">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="text-sm text-black dark:text-white">
                        <form action="{{ route('mitra.create') }}" method="POST">
                            <!-- CSRF Token (Laravel) -->
                            @csrf
                            <!-- Nama Mitra -->
                            <div class="mb-4">
                                <input type="text" placeholder="User Name" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" readonly name="kode_mitra" id="kode_mitra">
                            </div>
                            <div class="mb-4">
                                <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Mitra</label>
                                    <input type="text"  placeholder="Masukan Nama Mitra " class="form-input" name="nama_mitra" id="nama_mitra" required>
                                </div>
                            </div>
                            <!-- Tombol Submit -->
                            <div class="flex justify-end">
                              <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 w-full">
                                Tambah Mitra
                              </button>
                            </div>
                          </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
{{-- ---------------------------------------------------------------- --}}
<!-- Mobile: Carousel -->
<div class="relative w-full mb-6 block md:hidden">
    <!-- Slide 1: Jumlah Mitra -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center" style="display:flex;">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Mitra</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $mitra->count() }}
                </span>
                <span x-show="!show" x-cloak>
                    •••••••
                </span>
            </h2>
            <button @click="show = !show" class="p-2 rounded-full bg-white hover:bg-gray-100 shadow">
                <!-- Mata tertutup -->
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7.5a11.3 11.3 0 012.172-3.364m4.473-2.469A9.956 9.956 0 0112 5c5 0 9.27 3.11 11 7.5-.446.93-1.037 1.78-1.736 2.52M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <!-- Mata terbuka -->
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
            </button>
        </div>
        <p class="text-xs text-black mt-2">Toko/Mitra</p>
    </div>
    <!-- Slide 2: Jumlah Kota -->
    <div x-data="{ show: false }" class="bg-lightpurple-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center" style="display:none;">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Kota</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $totalKota }}
                </span>
                <span x-show="!show" x-cloak>
                    •••••••
                </span>
            </h2>
            <button @click="show = !show" class="p-2 rounded-full bg-white hover:bg-gray-100 shadow">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7.5a11.3 11.3 0 012.172-3.364m4.473-2.469A9.956 9.956 0 0112 5c5 0 9.27 3.11 11 7.5-.446.93-1.037 1.78-1.736 2.52M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
            </button>
        </div>
        <p class="text-xs text-black mt-2">Kota</p>
    </div>
    <!-- Tombol panah -->
    <button class="absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl px-3 py-2 rounded-r-lg" onclick="plusDivs(-1)">&#10094;</button>
    <button class="absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl px-3 py-2 rounded-l-lg" onclick="plusDivs(1)">&#10095;</button>
</div>

<!-- Desktop: 2 cards side by side -->
<div class="w-full mb-6 hidden md:flex gap-6">
    <!-- Card 1: Jumlah Mitra -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 flex-1 flex flex-col justify-center">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Mitra</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $mitra->count() }}
                </span>
                <span x-show="!show" x-cloak>
                    •••••••
                </span>
            </h2>
            <button @click="show = !show" class="p-2 rounded-full bg-white hover:bg-gray-100 shadow">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7.5a11.3 11.3 0 012.172-3.364m4.473-2.469A9.956 9.956 0 0112 5c5 0 9.27 3.11 11 7.5-.446.93-1.037 1.78-1.736 2.52M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
            </button>
        </div>
        <p class="text-xs text-black mt-2">Toko/Mitra</p>
    </div>
    <!-- Card 2: Jumlah Kota -->
    <div x-data="{ show: false }" class="bg-lightpurple-100 rounded-2xl p-6 flex-1 flex flex-col justify-center">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Kota</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $totalKota }}
                </span>
                <span x-show="!show" x-cloak>
                    •••••••
                </span>
            </h2>
            <button @click="show = !show" class="p-2 rounded-full bg-white hover:bg-gray-100 shadow">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7.5a11.3 11.3 0 012.172-3.364m4.473-2.469A9.956 9.956 0 0112 5c5 0 9.27 3.11 11 7.5-.446.93-1.037 1.78-1.736 2.52M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
                </svg>
            </button>
        </div>
        <p class="text-xs text-black mt-2">Kota</p>
    </div>
</div>


@foreach($errors->all() as $error)
<div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
    <p class="text-sm">{{ $error }}</p>
</div>
@endforeach

<div class="grid grid-cols-1 gap-7">
    {{-- Simple DataTable --}}
    <div x-data="main" x-init="init()" class="clean-table-container hidden md:block">
        <div class="p-5 border-b border-black/10 dark:border-white/5">
            <p class="text-sm font-semibold">Daftar Mitra dan Toko</p>
            <p class="text-xs text-black/60 dark:text-white/60">Berikut adalah daftar mitra dan toko yang telah terdaftar di sistem.</p>
        </div>
        <div class="overflow-auto" >
            <table id="myTable" class="whitespace-nowrap w-full"></table>
        </div>
    </div>


</div>
  {{-- Mobile Card --}}
<div class="block md:hidden border border-black/10 dark:border-white/10 bg-lightwhite dark:bg-white/5 p-2 rounded-md">
    <div class="mb-4">
        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
            Daftar Mitra dan Toko
        </h2>
        <p class="text-xs text-gray-600 dark:text-gray-400">
            Berikut adalah daftar mitra dan toko yang telah terdaftar di sistem.
        </p>
    </div>

    @foreach($mitraData as $a)
        <div class="bg-white dark:bg-black border border-black/10 dark:border-white/10 rounded-xl p-5 mb-4 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
            <!-- Header -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Kode Mitra</p>
                    <p class="text-sm font-semibold transaction-code">{{ $a->kode_mitra }}</p>
                </div>
                <div class="flex gap-2">
                    @php
                        $phone = $a->no_telp_mitra;
                        $waUrl = $phone;
                        if (str_starts_with($waUrl, '0')) {
                            $waUrl = '62' . substr($waUrl, 1);
                        }
                        $waUrl = preg_replace('/[^0-9]/', '', $waUrl);
                    @endphp
                    @if($phone)
                        <a href="https://wa.me/{{ $waUrl }}" target="_blank"
                           class="px-3 py-1.5 text-xs font-medium bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                           WA
                        </a>
                    @endif
                    <a href="{{ route('detail.mitra', $a->id) }}"
                       class="px-3 py-1.5 text-xs font-medium bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                        Detail
                    </a>
                </div>
            </div>

            <!-- Body -->
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Nama Mitra:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-100 text-right">{{ $a->nama_mitra }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Telepon:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-100">{{ $a->no_telp_mitra ?? '-' }}</span>
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Tanggal Bergabung:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-100">
                        {{ \Carbon\Carbon::parse($a->created_at)->locale('id')->translatedFormat('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    @if($mitraData->isEmpty())
    <div class="text-center py-8">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
        </svg>
        <p class="text-gray-500 dark:text-gray-400">Tidak ada data mitra tersedia.</p>
    </div>
    @else
    <div class="mt-4">
        {{ $mitraData->links('vendor.pagination.clean') }}
    </div>
    @endif
</div>


@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
   <script>
      // Generate kode produk otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInput = document.getElementById('kode_mitra');
            if (kodeInput) {
                const now = new Date();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan (01-12)
                const year = String(now.getFullYear()).slice(-2); // 2 digit terakhir tahun
                const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

                kodeInput.value = `MTR-${month}${year}/${random}`;
            }
        });

        // Carousel script
        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            showDivs(slideIndex += n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            if (n > x.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = x.length }
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            x[slideIndex - 1].style.display = "flex";
        }

        // Swipe support
        let startX = 0;
        let endX = 0;
        document.addEventListener("touchstart", function (e) {
            startX = e.touches[0].clientX;
        });
        document.addEventListener("touchend", function (e) {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });
        function handleSwipe() {
            let diffX = startX - endX;
            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    plusDivs(1);
                } else {
                    plusDivs(-1);
                }
            }
        }
   </script>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data('main', () => ({
            init() {
                this.loadData();
            }
            , loadData() {
                const table = new simpleDatatables.DataTable("#myTable", {
                    data: {
                        headings: ["Kode Mitra", "Nama Mitra", "Alamat", "Kota", "No. Telepon"]
                        , data: @json($mitra)
                    , }
                    , sortable: false
                    , searchable: true
                    , perPage: 20
                    , perPageSelect: [5, 10, 20, 50, 100]
                    , firstLast: false
                    , prevText: '<i class="fas fa-chevron-left text-[10px]"></i>'
                    , nextText: '<i class="fas fa-chevron-right text-[10px]"></i>'
                    , labels: {
                        placeholder: 'Cari transaksi, mitra, atau status'
                    , searchTitle: 'Cari data mitra'
                    , perPage: ''
                    , noRows: 'Tidak ada data mitra tersedia'
                    , info: 'Menampilkan {start} sampai {end} dari {rows} data'
                    , }
                    , layout: {
                        top: '{select}{search}'
                        , bottom: '<div class="datatable-info-container">{info}</div>{pager}'
                    , },
                    init() {
                        const wrapper = document.querySelector('#myTable')?.closest('.datatable-wrapper') || document.querySelector('#myTable')?.closest('.dataTable-wrapper');
                        if (wrapper) {
                            wrapper.classList.add('clean-datatable-wrapper');
                        }
                    }
                , });
            }
        }));
    });

</script>
@endsection
