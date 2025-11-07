@extends('layout.main')

@section('title', 'Transaksi')

@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/simple-datatables.css') }}" />
@endsection
<style>
    .select2-container--classic .select2-search--dropdown .select2-search__field {
        background-color: transparent !important;
        border: 1px solid #d1d5db !important;
        /* border-gray-300 */
        color: #111827 !important;
        /* text-gray-900 */
        border-radius: 0.375rem !important;
        /* rounded-md */
        padding: 0.375rem 0.5rem !important;
        /* py-1.5 px-2 */
        width: 100% !important;
    }

    .select2-container--classic .select2-search--dropdown .select2-search__field:focus {
        border-color: #2563eb !important;
        /* blue-600 */
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.25);
    }

    /* === DARK MODE Search Box === */
    .dark .select2-container--classic .select2-search--dropdown {
        background-color: transparent !important;
        padding: 0.5rem !important;
    }

    .dark .select2-container--classic .select2-search--dropdown .select2-search__field {
        background-color: rgba(31, 41, 55, 0.6) !important;
        /* bg-gray-800/60 */
        border: 1px solid #4b5563 !important;
        /* border-gray-600 */
        color: #f3f4f6 !important;
        /* text-gray-100 */
        border-radius: 0.375rem !important;
        width: 100% !important;
        padding: 0.375rem 0.5rem !important;
        transition: all 0.2s ease-in-out;
        backdrop-filter: blur(4px);
    }

    .dark .select2-container--classic .select2-search--dropdown .select2-search__field::placeholder {
        color: #9ca3af !important;
        /* text-gray-400 */
    }

    .dark .select2-container--classic .select2-search--dropdown .select2-search__field:focus {
        border-color: #3b82f6 !important;
        /* blue-500 */
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
        outline: none !important;
    }

    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        text-color: #000000;
        /* Warna teks hitam untuk mode terang */
    }

    /* === Select2 Dark Mode Override === */
    .dark .select2-container--classic .select2-selection--single {
        background-color: #000000 !important;
        /* bg-gray-900 */
        border: 1px solid #374151 !important;
        /* border-gray-700 */
        color: #f9fafb !important;
        /* text-gray-100 */
    }

    .dark .select2-container--classic .select2-selection__rendered {
        color: #000000 !important;
    }

    .dark .select2-container--classic .select2-selection__arrow b {
        border-color: #f9fafb transparent transparent transparent !important;
    }

    .dark .select2-dropdown {
        background-color: rgb(28 28 28 / var(--tw-bg-opacity)) !important;
        /* bg-gray-800 */
        border: 1px solid #ffffff1a !important;
        /* border-gray-700 */
        color: #f9fafb !important;
    }

    .dark .select2-results__option--highlighted[aria-selected] {
        background-color: #2563eb !important;
        /* bg-blue-600 */
        color: #fff !important;
    }

    .dark .select2-results__option[aria-selected=true] {
        background-color: #374151 !important;
        /* bg-gray-700 */
        color: #f9fafb !important;
    }

    /* Scrollbar styling (opsional) */
    .dark .select2-results__options::-webkit-scrollbar {
        width: 6px;
    }

    .dark .select2-results__options::-webkit-scrollbar-thumb {
        background-color: #4b5563;
        border-radius: 3px;
    }

</style>

@section('container')


{{-- ------------------------------------------------------------------------- --}}
<div x-data="modals">
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Data Transaksi</h2>
        <button class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" @click="toggle">
            + Tambah Transaksi Baru
        </button>
    </div>
       
    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="open && '!block'">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition="" x-transition.duration.300="" class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none; height: 500px; max-height: 500px;">
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Transaksi Baru</h5>
                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="toggle">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-5 max-h-[420px] overflow-y-auto">
                    <div class="text-sm text-black dark:text-white">
                        <form action="{{ route('transaksi.create') }}" method="POST">
                            <!-- CSRF Token (Laravel) -->
                            @csrf
                            <!-- Nama Mitra -->
                            <div class="mb-4" hidden>
                                <input type="text" placeholder="User Name" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" readonly name="kode_transaksi" id="kode_mitra">
                            </div>
                            <div class="mb-4">
                                <input type="text" id="searchMitra" placeholder="Cari Nama Mitra / Toko... " class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" >
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const searchInput = document.getElementById('searchMitra');
                                    const table = document.getElementById('tableMitra');
                                    if (searchInput && table) {
                                        searchInput.addEventListener('keyup', function() {
                                            const filter = searchInput.value.toLowerCase();
                                            const rows = table.querySelectorAll('tbody tr');
                                            rows.forEach(row => {
                                                const nama = row.cells[1]?.textContent.toLowerCase() || '';
                                                row.style.display = nama.includes(filter) ? '' : 'none';
                                            });
                                        });
                                    }
                                });
                            </script>
                            <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md mb-4">
                                <label class="block text-xs text-black/40 dark:text-white/40 mb-2">Pilih Mitra</label>
                                <div style="height:220px;overflow-y:auto;">
                                    <table class="min-w-full bg-white dark:bg-transparent border border-black/10 rounded-lg" id="tableMitra">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border-b text-left text-xs font-semibold">Pilih</th>
                                                <th class="py-2 px-4 border-b text-left text-xs font-semibold">Nama Mitra</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($mitra as $item)
                                            <tr>
                                                <td class="py-2 px-4 border-b">
                                                    <input type="radio" name="kode_mitra" value="{{ $item->kode_mitra }}" required>
                                                </td>
                                                <td class="py-2 px-4 border-b">{{ $item->nama_mitra }}</td>
                                            </tr>
                                            @endforeach
                                            @if($mitra->isEmpty())
                                            <tr>
                                                <td colspan="2" class="py-4 px-4 text-center text-gray-500">Tidak ada data mitra tersedia.</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Tombol Submit -->
                            <div class="flex justify-end mt-3">
                              <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 w-full">
                                Buat Transaksi Baru
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
<!-- Mobile: Slider -->
<div class="relative w-full mb-6 block md:hidden">
    <!-- Slide 1: Total Transaksi -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center" style="display:flex;">
        <p class="text-sm font-semibold text-black mb-2">Total Transaksi</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $transaksi->count() }}
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
        <p class="text-xs text-black mt-2">Transaksi</p>
    </div>
    <!-- Slide 2: Total Pembayaran Diterima -->
    <div x-data="{ show: false }" class="bg-lightpurple-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center" style="display:none;">
        <p class="text-sm font-semibold text-black mb-2">Total Pembayaran Diterima</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    Rp.{{ number_format($totalTransaksi, 0, ',', '.') }}
                </span>
                <span x-show="!show" x-cloak>
                    Rp.•••••••
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
    </div>
    <!-- Slide 3: Total Barang Diluar -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center" style="display:none;">
        <p class="text-sm font-semibold text-black mb-2">Total Barang Diluar</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    Rp.{{ number_format($totalTransaksiluar, 0, ',', '.') }}
                </span>
                <span x-show="!show" x-cloak>
                    Rp.•••••••
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
    </div>
    <!-- Tombol panah -->
    <button class="absolute left-0 top-1/2 -translate-y-1/2 text-black text-3xl px-3 py-2 rounded-r-lg" onclick="plusDivs(-1)">&#10094;</button>
    <button class="absolute right-0 top-1/2 -translate-y-1/2 text-black text-3xl px-3 py-2 rounded-l-lg" onclick="plusDivs(1)">&#10095;</button>
</div>
<script>
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

<!-- Desktop: 3 cards side by side -->
<div class="w-full mb-6 hidden md:flex gap-6">
    <!-- Card 1: Total Transaksi -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 flex-1 flex flex-col justify-center">
        <p class="text-sm font-semibold text-black mb-2">Total Transaksi</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    {{ $transaksi->count() }}
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
        <p class="text-xs text-black mt-2">Transaksi</p>
    </div>
    <!-- Card 2: Total Pembayaran Diterima -->
    <div x-data="{ show: false }" class="bg-lightpurple-100 rounded-2xl p-6 flex-1 flex flex-col justify-center">
        <p class="text-sm font-semibold text-black mb-2">Total Pembayaran Diterima</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    Rp.{{ number_format($totalTransaksi, 0, ',', '.') }}
                </span>
                <span x-show="!show" x-cloak>
                    Rp.•••••••
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
    </div>
    <!-- Card 3: Total Barang Diluar -->
    <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 flex-1 flex flex-col justify-center">
        <p class="text-sm font-semibold text-black mb-2">Total Barang Diluar</p>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-black">
                <span x-show="show" x-cloak>
                    Rp.{{ number_format($totalTransaksiluar, 0, ',', '.') }}
                </span>
                <span x-show="!show" x-cloak>
                    Rp.•••••••
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
    </div>
</div>
@foreach($errors->all() as $error)
<div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
    <p class="text-sm">{{ $error }}</p>
</div>
@endforeach

<div class="grid grid-cols-1 gap-7">
    {{-- Simple DataTable --}}
    <div x-data="main" x-init="init()" class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
            <p class="text-sm font-semibold">Daftar Mitra dan Toko</p>
            <p class="text-xs text-black/60 dark:text-white/60">Berikut adalah daftar mitra dan toko yang telah terdaftar di sistem.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2" x-data="{ filterOpen: false }">
            <!-- Filter Status Pembayaran -->
            <!-- Filter Modal Trigger Button -->
                <div x-data="{ filterOpen: false }">
                    <!-- Tombol -->
                    <button type="button"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 w-full"
                        @click="filterOpen = true">
                        Filter Transaksi
                    </button>

                    <!-- Overlay & Modal -->
                    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="{ 'block': filterOpen, 'hidden': !filterOpen }">
                        <div class="flex items-center justify-center min-h-screen px-4" @click.self="filterOpen = false">
                            <div x-show="filterOpen" x-transition x-transition.duration.300 class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                                <!-- Header -->
                                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                    <h5 class="font-semibold text-lg">Filter Transaksi</h5>
                                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="filterOpen = false">
                                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="p-5 max-h-[420px] overflow-y-auto">
                                    <form method="GET" action="{{ route('transaksi.index') }}" class="flex flex-col gap-5">
                                        <!-- Status Pembayaran -->
                                        <div>
                                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Status Pembayaran</label>
                                            <select name="status_pembayaran"
                                                class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full select2 bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Semua Status</option>
                                                <option value="belum_bayar" {{ request('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                                <option value="sudah_bayar" {{ request('status_pembayaran') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
                                            </select>
                                        </div>

                                        <!-- Mitra -->
                                        <div>
                                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Mitra</label>
                                            <select name="kode_mitra" id="mitraFilter"
                                                class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full select2 bg-transparent dark:bg-transparent text-black dark:text-white">
                                                <option value="">Semua Mitra</option>
                                                @foreach($mitra as $item)
                                                    <option value="{{ $item->kode_mitra }}" {{ request('kode_mitra') == $item->kode_mitra ? 'selected' : '' }}>
                                                        {{ $item->nama_mitra }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Filter Waktu -->
                                        <div>
                                            <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Periode Waktu</label>
                                            <div class="flex flex-col gap-2">
                                                <select name="periode" id="periodeFilter"
                                                    class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                    <option value="">Semua Waktu</option>
                                                    <option value="bulanan" {{ request('periode') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                                    <option value="tahunan" {{ request('periode') == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                                                    <option value="rentang" {{ request('periode') == 'rentang' ? 'selected' : '' }}>Rentang Tanggal</option>
                                                </select>
                                                <div id="filterBulanan" class="{{ request('periode') == 'bulanan' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <select name="bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Bulan</option>
                                                            @for($m=1;$m<=12;$m++)
                                                                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                                                                    {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        <select name="tahun_bulan" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                            <option value="">Pilih Tahun</option>
                                                            @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                                <option value="{{ $y }}" {{ request('tahun_bulan') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="filterTahunan" class="{{ request('periode') == 'tahunan' ? '' : 'hidden' }}">
                                                    <select name="tahun_tahun" class="form-select py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                                        <option value="">Pilih Tahun</option>
                                                        @for($y = date('Y')-5; $y <= date('Y'); $y++)
                                                            <option value="{{ $y }}" {{ request('tahun_tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div id="filterRentang" class="{{ request('periode') == 'rentang' ? '' : 'hidden' }}">
                                                    <div class="flex gap-2">
                                                        <input type="date" name="tanggal_awal" value="{{ request('tanggal_awal') }}"
                                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                            placeholder="Tanggal Awal">
                                                        <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                                                            class="form-input py-2 px-3 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white"
                                                            placeholder="Tanggal Akhir">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end mt-4">
                                            <button type="submit"
                                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300">
                                                Terapkan Filter
                                            </button>
                                        </div>
                                    </form>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            function showFilterFields() {
                                                var periode = document.getElementById('periodeFilter').value;
                                                document.getElementById('filterBulanan').classList.toggle('hidden', periode !== 'bulanan');
                                                document.getElementById('filterTahunan').classList.toggle('hidden', periode !== 'tahunan');
                                                document.getElementById('filterRentang').classList.toggle('hidden', periode !== 'rentang');
                                            }
                                            document.getElementById('periodeFilter').addEventListener('change', showFilterFields);
                                            showFilterFields();
                                            $('#mitraFilter').select2({
                                                width: '100%',
                                                placeholder: "Cari Mitra...",
                                                allowClear: true
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
           
        <!-- Tabel di Desktop, Card di Mobile -->
        <div class="overflow-auto hidden sm:block">
            <div class="border bg-white dark:bg-black border-black/10 dark:border-white/10 p-5 rounded-md">
            <table id="myTable" class="whitespace-nowrap table-hover table-bordered w-full"></table>
            </div>
        </div>
        <!-- Card di Mobile -->
        <div class="block md:hidden">
            @if($transaksimobile->isEmpty())
            <div class="flex flex-col items-center justify-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2a4 4 0 01-4-4V7a4 4 0 014-4h6a4 4 0 014 4v4a4 4 0 01-4 4v2m-6 0h6" />
                </svg>
                <p class="text-gray-500 text-sm">Tidak ada data transaksi ditemukan.</p>
            </div>
            @else
            @foreach($transaksimobile as $item)
            <div class="bg-white dark:bg-black border border-black/10 dark:border-white/10 rounded-lg mb-4 p-4 shadow cursor-pointer" 
                onclick="window.location='{{ route('transaksi.detail', $item->id) }}'">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs font-semibold text-gray-500">Kode Transaksi</span>
                    <span class="text-sm font-bold">
                        {{ $item->kode_transaksi }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <div>
                        <span class="block text-xs text-gray-500">Tanggal Transaksi</span>
                        <span class="block text-sm font-medium text-black dark:text-white">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Nama Mitra</span>
                        <span class="block text-sm font-medium text-black dark:text-white">
                            {{ $item->mitra->nama_mitra ?? '-' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Nilai Pesanan</span>
                        <span class="block text-sm font-medium text-blue-700 dark:text-blue-400">
                            {!! isset($item->total)
                                ? 'Rp ' . number_format($item->total, 0, ',', '.')
                                : '<span class="text-gray-500">Rp.0</span>'
                            !!}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Status Pembayaran</span>
                        <span class="inline-block rounded text-xs font-semibold
                            {{ $item->status_bayar == 'Sudah Bayar' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $item->status_bayar == 'Sudah Bayar' ? 'Sudah Bayar' : 'Belum Bayar' }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
            @endif

            {{-- Paging for mobile --}}
            @php
            $currentPage = request('page', 1);
            $totalPages = ceil($transaksimobile->count() / 5);
            @endphp
            @if($totalPages > 1)
            <div class="flex justify-center items-center gap-2 mb-4">
            @if($currentPage > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">&laquo;</a>
            @endif
            @for($i = 1; $i <= $totalPages; $i++)
            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}"
            class="px-3 py-1 rounded {{ $i == $currentPage ? 'bg-blue-700 text-white' : 'bg-gray-200 text-black hover:bg-blue-600 hover:text-white' }}">
            {{ $i }}
            </a>
            @endfor
            @if($currentPage < $totalPages)
            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">&raquo;</a>
            @endif
            </div>
            @endif
        </div>
    </div>


</div>


@endsection

@section('js')
<script src="{{ asset('assets/js/simple-datatables.js') }}"></script>
   <script>
      // Generate kode produk otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const kodeInput = document.getElementById('kode_mitra');
            if (kodeInput) {
                const now = new Date();
                const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan (01-12)
                const year = String(now.getFullYear()); // 2 digit terakhir tahun
                const random = Math.floor(100 + Math.random() * 900); // 3 digit acak

                kodeInput.value = `B${year}${random}`;
            }
        });
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
                        headings: ["Kode Transaksi", "Tanggal Transaksi", "Nama Toko", "Nilai Pesanan", "Status Pembayaran"]
                        , data: @json($transaksi)
                    , }
                    , sortable: false
                    , searchable: true
                    , perPage: 5
                    , perPageSelect: [5, 10, 20, 50, 100]
                    , firstLast: false
                    , firstText: `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
              <path opacity="0.5" d="M17 19L11 12L17 5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>`
                    , lastText: `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
              <path opacity="0.5" d="M7 19L13 12L7 5" stroke="currentColor"
                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>`
                    , prevText: `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>`
                    , nextText: `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
              <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>`
                    , labels: {
                        perPage: '{select}'
                    , }
                    , layout: {
                        top: '{select}{search}'
                        , bottom: '{info}{pager}'
                    , }
                , });
            }
        }));
    });

</script>
@endsection
