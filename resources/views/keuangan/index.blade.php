@extends('layout.main')
@section('title', 'Data Keuangan')
@section('container')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .select2-container--default .select2-selection--single {
        margin-left: -10px;
        border: none;
    }

    .dark .select2-container--default .select2-selection--single {
        background-color: rgba(0, 0, 0, 0);
        margin-left: -10px;
        border: none;
    }

    @media (max-width: 768px) {
        .p-7 {
            padding: 9px;
        }
    }
    .mobile_view{
        display: none;
    }
    .akun-nama {
        white-space: normal;
        /* default desktop: teks normal */
    }

    @media (max-width: 768px) {
        .dekstop_view{
            visibility: none;
            display: none;
        }
        .mobile_view{
        display:block;
    }
        /* hanya mobile */
        .akun-nama {
            display: inline-block;
            max-width: 150px;
            /* sesuaikan lebar maksimal */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }

    .select2-dropdown {
    z-index: 99999 !important;
    }
    /* Fancybox container selalu di atas modal */
    .fancybox__container {
    z-index: 10050 !important; /* lebih tinggi dari modal */
    }

</style>
@endsection
<div x-data="{ showSearch: false }" class="space-y-3">
    <div class="flex items-center justify-between mb-4 gap-2">
        <p class="px-2 text-lg font-bold">Pengelola Keuangan</p>

        <div class="flex items-center gap-2">
            <!-- PDF (bulat icon) -->
            <a href="{{ route('keuangan.pdf', request()->query()) }}" target="_blank"
               class="p-2 rounded-full bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 flex items-center justify-center"
               title="Download PDF">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-6 0h6v4H6v-4h6z" />
            </svg>
            </a>

            <!-- Kalender -->
            <a href="{{ route('keuangan.kalender') }}" title="Lihat Kalender"
               class="block sm:hidden p-2 rounded-full bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 flex items-center justify-center">
               <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" fill="none"/>
                <path d="M16 3v4M8 3v4M3 11h18" stroke="currentColor"/>
            </svg>
            </a>

            <!-- Search toggle (DI DALAM x-data) -->
            <button @click="showSearch = !showSearch"
                    class="block sm:hidden p-2 rounded-full bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 flex items-center justify-center"
                    title="Cari Transaksi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke="currentColor"/>
                    </svg>
            </button>
        </div>
    </div>

    <!-- Search box -->
    <div x-show="showSearch" x-cloak x-transition class="mb-4 w-full">
        <div class="relative">
            <!-- Input -->
            <input type="text" id="produkTableSearch" placeholder="Cari transaksi..." class="w-full rounded-full border border-gray-300 pl-10 pr-4 py-2.5
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          placeholder-gray-400 text-sm sm:text-base
                          shadow-sm transition" autocomplete="off">
        </div>
</div>

  </div>

<!-- Box Search (toggle muncul setelah klik icon search) -->
<div x-show="showSearch" x-transition
     class="mb-4 w-full">
    <input type="text" id="produkTableSearch"
           placeholder="Cari transaksi..."
           class="w-full form-input !rounded-full py-2.5 px-4 text-black dark:text-white border border-black/10 dark:border-white/10 placeholder:text-black/40 dark:placeholder:text-white/40 focus:border-blue-500 dark:focus:border-white/20 focus:ring focus:ring-blue-100 dark:focus:ring-white/5 shadow-sm"
           autocomplete="off">
</div>


<div class="grid grid-cols-3 gap-4 mb-2 dekstop_view">
    <!-- Pemasukan -->
    <div class="bg-lightblue-100 rounded-2xl p-4 sm:p-6">
      <p class="text-xs sm:text-sm font-semibold text-black mb-1 sm:mb-2">Pemasukan</p>
      <h2 class="text-base sm:text-2xl font-semibold text-black">
        Rp.{{ number_format($transaksi->where('tipe','pemasukan')->sum('total'), 0, ',', '.') }}
      </h2>
    </div>

    <!-- Pengeluaran -->
    <div class="bg-lightpurple-100 rounded-2xl p-4 sm:p-6">
      <p class="text-xs sm:text-sm font-semibold text-black mb-1 sm:mb-2">Pengeluaran</p>
      <h2 class="text-base sm:text-2xl font-semibold text-black">
        Rp.{{ number_format($transaksi->where('tipe','pengeluaran')->sum('total'), 0, ',', '.') }}
      </h2>
    </div>

    <!-- Saldo -->
    <div class="bg-lightblue-100 rounded-2xl p-4 sm:p-6">
      <p class="text-xs sm:text-sm font-semibold text-black mb-1 sm:mb-2">Saldo</p>
      <h2 class="text-base sm:text-2xl font-semibold text-black">
        @php
          $totalPemasukan = $transaksi->where('tipe','pemasukan')->sum('total');
          $totalPengeluaran = $transaksi->where('tipe','pengeluaran')->sum('total');
          $labaBersih = $totalPemasukan - $totalPengeluaran;
        @endphp
        Rp.{{ number_format($labaBersih, 0, ',', '.') }}
      </h2>
    </div>
  </div>

  <div class="w3-content w3-display-container relative max-w-xl mx-auto mb-3 mobile_view">
   <!-- Pemasukan -->
<div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center">
    <p class="text-sm font-semibold text-black mb-2">Pemasukan</p>
    <div class="flex items-center gap-2">
      <h2 id="pemasukanValue" class="text-2xl font-bold text-black">
        <span x-show="show" x-cloak>
          Rp.{{ number_format($transaksi->where('tipe','pemasukan')->sum('total'), 0, ',', '.') }}
        </span>
        <span x-show="!show" x-cloak>
          Rp.•••••••
        </span>
      </h2>
      <!-- Tombol toggle -->
      <button @click="show = !show" class="p-2 rounded-full bg-white hover:bg-gray-100 shadow">
        <!-- Icon mata tertutup -->
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-5 0-9.27-3.11-11-7.5a11.3 11.3 0 012.172-3.364m4.473-2.469A9.956 9.956 0 0112 5c5 0 9.27 3.11 11 7.5-.446.93-1.037 1.78-1.736 2.52M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <!-- Icon mata terbuka -->
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Pengeluaran -->
  <div x-data="{ show: false }" class="bg-lightpurple-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center">
    <p class="text-sm font-semibold text-black mb-2">Pengeluaran</p>
    <div class="flex items-center gap-2">
      <h2 id="pengeluaranValue" class="text-2xl font-bold text-black">
        <span x-show="show" x-cloak>
          Rp.{{ number_format($transaksi->where('tipe','pengeluaran')->sum('total'), 0, ',', '.') }}
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
          <!-- Icon mata terbuka -->
          <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
          </svg>
      </button>
    </div>
  </div>

  <!-- Saldo -->
  <div x-data="{ show: false }" class="bg-lightblue-100 rounded-2xl p-6 mySlides w-full h-[200px] flex flex-col justify-center">
    <p class="text-sm font-semibold text-black mb-2">Saldo</p>
    <div class="flex items-center gap-2">
      <h2 id="saldoValue" class="text-2xl font-bold text-black">
        <span x-show="show" x-cloak>
          @php
            $totalPemasukan = $transaksi->where('tipe','pemasukan')->sum('total');
            $totalPengeluaran = $transaksi->where('tipe','pengeluaran')->sum('total');
            $labaBersih = $totalPemasukan - $totalPengeluaran;
          @endphp
          Rp.{{ number_format($labaBersih, 0, ',', '.') }}
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
          <!-- Icon mata terbuka -->
          <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M1.5 12C3.27 7.61 7.5 4.5 12 4.5s8.73 3.11 10.5 7.5c-1.77 4.39-6 7.5-10.5 7.5s-8.73-3.11-10.5-7.5zM12 15a3 3 0 100-6 3 3 0 000 6z" />
          </svg>
      </button>
    </div>
  </div>


    <!-- Tombol panah -->
    <button class="absolute left-0 top-1/2 -translate-y-1/2  text-white text-3xl px-3 py-2 rounded-r-lg" onclick="plusDivs(-1)">&#10094;</button>
    <button class="absolute right-0 top-1/2 -translate-y-1/2  text-white text-3xl px-3 py-2 rounded-l-lg" onclick="plusDivs(1)">&#10095;</button>

    <!-- Tombol show/hide angka -->
    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 hidden">
      <button onclick="toggleAngka()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">
        Show/Hide Angka
      </button>
    </div>
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

  // --- Swipe support ---
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
    if (Math.abs(diffX) > 50) { // minimal jarak usap 50px
      if (diffX > 0) {
        // geser kiri → slide berikutnya
        plusDivs(1);
      } else {
        // geser kanan → slide sebelumnya
        plusDivs(-1);
      }
    }
  }

    // fitur show/hide angka
    var angkaVisible = true;
    function toggleAngka() {
      const values = document.querySelectorAll("#pemasukanValue, #pengeluaranValue, #saldoValue");
      angkaVisible = !angkaVisible;
      values.forEach(el => {
        el.style.visibility = angkaVisible ? "visible" : "hidden";
      });
    }
</script>







@if ($errors->any())
<div class="mb-4">
    <div class="p-5 rounded bg-lightred/50  dark:bg-lightred text-black/80 dark:text-black" role="alert">
        <strong class="font-bold">Terjadi kesalahan:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif
<div class="p-2  rounded-lg flex items-center gap-2 mb-2 hidden md:flex">
    <!-- Tombol Tambah (width kecil) -->
    <div class="flex-shrink-0">
        <button
            type="button"
            @click="$dispatch('transaksi')"
            class="flex items-center gap-x-2 px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
        >
            <span class="text-lg font-bold">+</span>
            <span>Catatan Baru</span>
        </button>
    </div>


</div>


<!-- Script pencarian -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('produkTableSearch');
        const table = document.getElementById('produkTable');

        input.addEventListener('keyup', () => {
            const filter = input.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    });
</script>

{{-- modal tambah  metide @click="$dispatch('transaksi')"--}}
<div x-data="{ open: false }" @transaksi.window="open = true" @close-modal.window="open = false">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="{ 'block': open, 'hidden': !open }">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <!-- Modal Box -->
            <div x-show="open" x-transition x-transition.duration.300 class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                <!-- Header -->
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Tambah Transaksi Baru</h5>
                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="open = false">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <div x-data="{ tab: 'pengeluaran' }">
                        <div class="flex mb-4 w-full max-w-md mx-auto">
                            <!-- Tombol Pengeluaran -->
                            <button
                                :class="tab === 'pengeluaran'
                                    ? 'bg-purple-600 text-white'
                                    : 'bg-gray-100 text-black'"
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-l-lg focus:outline-none transition-all duration-200"
                                @click="tab = 'pengeluaran'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                <span>Pengeluaran</span>
                            </button>

                            <!-- Tombol Pemasukan -->
                            <button
                                :class="tab === 'pemasukan'
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-gray-100 text-black'"
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-r-lg focus:outline-none transition-all duration-200"
                                @click="tab = 'pemasukan'"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Pemasukan</span>
                            </button>
                        </div>


                        <div x-show="tab === 'pengeluaran'">
                            <form action="{{ route('keuangan.add') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="myForm">
                                @csrf
                                <input type="hidden" name="tipe" value="pengeluaran">
                                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">

                                    <div style="width: 70%;">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Tanggal
                                        </label>
                                        <input type="text" name="tanggal"  class="tanggal form-input w-full">
                                    </div>

                                    {{-- Kolom Waktu (lebih sempit) --}}
                                    <div style="width: 30%;">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Waktu
                                        </label>
                                        <input type="text" id="waktu_display" class="waktu_display form-input w-full cursor-not-allowed" disabled>
                                        <input type="hidden" name="waktu" id="waktu" class="waktu">
                                    </div>

                                </div>


                                <div class="mb-4 relative bg-white dark:bg-white/5 py-2 px-3 rounded-lg border border-black/10 flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Catatan
                                            <span style="color: red">*</span></label>
                                        <textarea name="deskripsi" class="form-input" rows="2" placeholder="Deskripsi Transaksi"></textarea>
                                    </div>
                                    <div x-data="{ fileName2: '', fileUrl2: '' }">
                                        <label for="foto-upload-2" :class="fileName2 ? 'bg-green-100 border-green-500' : 'bg-gray-100'" class="cursor-pointer flex flex-col items-center justify-center w-24 h-24 border-2 border-dashed rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                            <template x-if="fileUrl2">
                                                <img :src="fileUrl2" alt="Preview" class="w-16 h-16 object-cover rounded mb-1" />
                                            </template>
                                            <template x-if="!fileUrl2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828M7 7h.01M21 21H3V3h18v18z" />
                                                </svg>
                                            </template>
                                            <span x-text="fileName2 ? fileName2 : 'Upload Foto'" class="text-xs text-gray-500 mt-1 text-center"></span>
                                            <input id="foto-upload-2" type="file" name="foto" class="hidden" @change="
                                                        fileName2 = $event.target.files[0]?.name || '';
                                                        if ($event.target.files[0]) {
                                                            const reader = new FileReader();
                                                            reader.onload = e => fileUrl2 = e.target.result;
                                                            reader.readAsDataURL($event.target.files[0]);
                                                        } else {
                                                            fileUrl2 = '';
                                                        }
                                                    " />
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4 relative bg-white dark:bg-white/5 rounded-lg border border-black/10">

                                    {{-- Untuk Biaya (Debit) --}}
                                    <div class="py-4 px-5">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Untuk Biaya (Debit) <span style="color:red">*</span>
                                        </label>
                                        <select name="id_akun" class="select-form kategori_keluar w-full" style="width: 100%;"
                                            data-placeholder="Pilih Kategori Akun">
                                            <option></option>
                                            {{-- Beban, HPP, Beban Lainnya, Depresiasi --}}
                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'beban') as $item)
                                            <option value="{{ $item->id }}" data-kategori="{{ $item->kategori->nama_kategori ?? '' }}">
                                                {{ $item->kode_akun }} | {{ $item->nama_akun }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr class="dark:border-white/10 border-black/10">

                                    <div class="py-4 px-5">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Diambil dari (Credit) <span style="color:red">*</span>
                                        </label>
                                        <select name="id_akun_second" class="select-form kategori_masuk w-full" style="width: 100%;"
                                            data-placeholder="Pilih Kategori Akun">
                                            <option></option>
                                            {{-- Kas & Bank --}}
                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'aset') as $item)
                                            <option value="{{ $item->id }}" data-kategori="{{ $item->kategori->nama_kategori ?? '' }}">
                                                {{ $item->kode_akun }} | {{ $item->nama_akun }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nominal
                                            (Rp) <span style="color: red">*</span></label>
                                        <input type="text" name="total" class="form-input" placeholder="Rp 0" autocomplete="off" inputmode="numeric" id="total_pengeluaran">
                                    </div>
                                    <script>
                                        // Format input as currency (Rp. 2.000.000) but submit as plain number (2000000)
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const input = document.getElementById('total_pengeluaran');
                                            if (input) {
                                                input.addEventListener('input', function(e) {
                                                    // Remove all non-digit characters
                                                    let value = this.value.replace(/\D/g, '');
                                                    // Format as currency
                                                    if (value) {
                                                        this.value = 'Rp. ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                    } else {
                                                        this.value = '';
                                                    }
                                                });

                                                // On form submit, remove formatting so only number is sent
                                                input.form && input.form.addEventListener('submit', function() {
                                                    input.value = input.value.replace(/\D/g, '');
                                                });
                                            }
                                        });

                                    </script>
                                    <!-- Kotak Rekening dengan Icon -->
                                    <div x-data="{ openRekening: false, selectedRekening: '', selectedRekeningName: '' }" class="relative">
                                        <button type="button" @click="openRekening = !openRekening" class="flex items-center gap-2 px-3 py-2 border rounded-lg bg-gray-100 dark:bg-black dark:border hover:bg-gray-200 transition">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" fill="none" />
                                                <path d="M16 3v4M8 3v4M3 11h18" stroke="currentColor" />
                                            </svg>
                                            <span class="hidden md:inline" x-text="selectedRekeningName
                                                        ? selectedRekeningName
                                                        : `{{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->nama_rekening ?? ($rekening->first()->nama_rekening ?? '') }}`">
                                                {{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->nama_rekening ?? ($rekening->first()->nama_rekening ?? '') }}
                                            </span>
                                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <!-- Dropdown Pilihan Rekening -->
                                        <div x-show="openRekening" @click.away="openRekening = false" class="fixed inset-0 z-[1001] flex items-center justify-center" style="background: rgba(0,0,0,0.2);">
                                            <div class="bg-white dark:bg-black dark:border:border-black border border-gray-200 rounded-lg shadow-lg w-80 max-w-full" @click.stop>
                                                <div class="p-3 border-b font-semibold">Pilih Rekening
                                                </div>
                                                <ul>
                                                    @if ($rekening->isEmpty())
                                                    <li class="px-4 py-2 text-gray-400 text-center">
                                                        Tidak ada rekening tersedia.</li>
                                                    @else
                                                    @foreach ($rekening as $item)
                                                    <li>
                                                        <button type="button" @click="selectedRekening = '{{ $item->id }}'; selectedRekeningName = '{{ \Illuminate\Support\Str::limit($item->nama_rekening, 10, '...') }}'; openRekening = false" class="w-full text-left px-4 py-2 hover:bg-blue-100 transition">
                                                            <span class="block truncate max-w-[200px] flex items-center">
                                                                {{ $item->nama_rekening }}
                                                                <template x-if="selectedRekening === '{{ $item->id }}'
                                                                            || (!selectedRekening && '{{ $item->id }}' == '{{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->id ?? ($rekening->first()->id ?? '') }}')">

                                                                    <svg class="w-4 h-4 text-green-500 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </template>
                                                            </span>
                                                        </button>
                                                    </li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                                <div class="p-2 flex justify-end">
                                                    <button type="button" @click="openRekening = false" class="text-sm px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 dark:bg-black">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_rekening" :value="selectedRekening">
                                    </div>
                                </div>
                                <div class="flex ">
                                    <button id="submitBtnPengeluaran" type="submit" class="submitBtn w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                                        <span class="btn-text">Simpan</span>
                                        <span class="btn-spinner hidden ml-2">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div x-show="tab === 'pemasukan'">
                            <form action="{{ route('keuangan.add') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="formPemasukan">
                                @csrf
                                <input type="hidden" name="tipe" value="pemasukan">
                                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">

                                    <div style="width: 70%;">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Tanggal
                                        </label>
                                        <input type="text" name="tanggal" class="tanggal form-input w-full">
                                    </div>

                                    {{-- Kolom Waktu (lebih sempit) --}}
                                    <div style="width: 30%;">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Waktu
                                        </label>
                                        <input type="text" id="waktu_display" class="form-input w-full cursor-not-allowed waktu_display" disabled>
                                        <input type="hidden" name="waktu" id="waktu" class="waktu">
                                    </div>

                                </div>



                                <div class="mb-4 relative bg-white dark:bg-white/5 py-2 px-3 rounded-lg border border-black/10 flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Catatan
                                            <span style="color: red">*</span></label>
                                        <textarea name="deskripsi" class="form-input" rows="2" placeholder="Deskripsi Transaksi"></textarea>
                                    </div>
                                    <div x-data="{ fileName2: '', fileUrl2: '' }">
                                        <label for="foto-upload-3" :class="fileName2 ? 'bg-green-100 border-green-500' : 'bg-gray-100'" class="cursor-pointer flex flex-col items-center justify-center w-24 h-24 border-2 border-dashed rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                            <template x-if="fileUrl2">
                                                <img :src="fileUrl2" alt="Preview" class="w-16 h-16 object-cover rounded mb-1" />
                                            </template>
                                            <template x-if="!fileUrl2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828M7 7h.01M21 21H3V3h18v18z" />
                                                </svg>
                                            </template>
                                            <span x-text="fileName2 ? fileName2 : 'Upload Foto'" class="text-xs text-gray-500 mt-1 text-center"></span>
                                            <input id="foto-upload-3" type="file" name="foto" class="hidden" @change="
                                                        fileName2 = $event.target.files[0]?.name || '';
                                                        if ($event.target.files[0]) {
                                                            const reader = new FileReader();
                                                            reader.onload = e => fileUrl2 = e.target.result;
                                                            reader.readAsDataURL($event.target.files[0]);
                                                        } else {
                                                            fileUrl2 = '';
                                                        }
                                                    " />
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4 relative bg-white dark:bg-white/5 rounded-lg border border-black/10">

                                    {{-- Untuk Biaya (Debit) --}}
                                    <div class="py-4 px-5">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Simpan ke (Debit) <span style="color:red">*</span>
                                        </label>
                                        <select name="id_akun" class="select-form kategori_keluar w-full" style="width: 100%;" data-placeholder="Pilih Kategori Akun">
                                            <option></option>
                                        @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'aset') as $item)
                                            <option value="{{ $item->id }}" data-kategori="{{ $item->kategori->nama_kategori ?? '' }}">
                                                {{ $item->kode_akun }} | {{ $item->nama_akun }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr class="dark:border-white/10 border-black/10">

                                    {{-- Diambil dari (Credit) --}}
                                    <div class="py-4 px-5">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                            Diterima dari (Credit) <span style="color:red">*</span>
                                        </label>
                                        <select name="id_akun_second" class="select-form kategori_masuk w-full" style="width: 100%;" data-placeholder="Pilih Kategori Akun">
                                            <option></option>
                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'pendapatan') as $item)
                                            <option value="{{ $item->id }}" data-kategori="{{ $item->kategori->nama_kategori ?? '' }}">
                                                {{ $item->kode_akun }} | {{ $item->nama_akun }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nominal
                                            (Rp) <span style="color: red">*</span></label>
                                        <input type="text" name="total" class="form-input" placeholder="Rp 0" autocomplete="off" inputmode="numeric" id="total_pemasukan">
                                    </div>
                                    <script>
                                        // Format input as currency (Rp. 2.000.000) but submit as plain number (2000000)
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const input = document.getElementById('total_pemasukan');
                                            if (input) {
                                                input.addEventListener('input', function(e) {
                                                    // Remove all non-digit characters
                                                    let value = this.value.replace(/\D/g, '');
                                                    // Format as currency
                                                    if (value) {
                                                        this.value = 'Rp. ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                    } else {
                                                        this.value = '';
                                                    }
                                                });

                                                // On form submit, remove formatting so only number is sent
                                                input.form && input.form.addEventListener('submit', function() {
                                                    input.value = input.value.replace(/\D/g, '');
                                                });
                                            }
                                        });

                                    </script>
                                    <!-- Kotak Rekening dengan Icon -->
                                    <div x-data="{ openRekening: false, selectedRekening: '', selectedRekeningName: '' }" class="relative">
                                        <button type="button" @click="openRekening = !openRekening" class="flex items-center gap-2 px-3 py-2 border rounded-lg bg-gray-100 dark:bg-black dark:border hover:bg-gray-200 transition">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" fill="none" />
                                                <path d="M16 3v4M8 3v4M3 11h18" stroke="currentColor" />
                                            </svg>
                                            <span class="hidden md:inline" x-text="selectedRekeningName
                                                        ? selectedRekeningName
                                                        : `{{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->nama_rekening ?? ($rekening->first()->nama_rekening ?? '') }}`">
                                                {{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->nama_rekening ?? ($rekening->first()->nama_rekening ?? '') }}
                                            </span>
                                            <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <!-- Dropdown Pilihan Rekening -->
                                        <div x-show="openRekening" @click.away="openRekening = false" class="fixed inset-0 z-[1001] flex items-center justify-center" style="background: rgba(0,0,0,0.2);">
                                            <div class="bg-white dark:bg-black dark:border:border-black border border-gray-200 rounded-lg shadow-lg w-80 max-w-full" @click.stop>
                                                <div class="p-3 border-b font-semibold">Pilih Rekening
                                                </div>
                                                <ul>
                                                    @if ($rekening->isEmpty())
                                                    <li class="px-4 py-2 text-gray-400 text-center">
                                                        Tidak ada rekening tersedia.</li>
                                                    @else
                                                    @foreach ($rekening as $item)
                                                    <li>
                                                        <button type="button" @click="selectedRekening = '{{ $item->id }}'; selectedRekeningName = '{{ \Illuminate\Support\Str::limit($item->nama_rekening, 10, '...') }}'; openRekening = false" class="w-full text-left px-4 py-2 hover:bg-blue-100 transition">
                                                            <span class="block truncate max-w-[200px] flex items-center">
                                                                {{ $item->nama_rekening }}
                                                                <template x-if="selectedRekening === '{{ $item->id }}'
                                                                            || (!selectedRekening && '{{ $item->id }}' == '{{ optional($rekening->firstWhere('kode_rekening', app('settings')['default_rekening'] ?? null))->id ?? ($rekening->first()->id ?? '') }}')">

                                                                    <svg class="w-4 h-4 text-green-500 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                </template>
                                                            </span>
                                                        </button>
                                                    </li>
                                                    @endforeach
                                                    @endif
                                                </ul>
                                                <div class="p-2 flex justify-end">
                                                    <button type="button" @click="openRekening = false" class="text-sm px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 dark:bg-black">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_rekening" :value="selectedRekening">
                                    </div>
                                </div>
                                <div class="flex ">
                                    <button id="submitBtn" type="submit" class="submitBtn w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                                        <span class="btn-text">Simpan</span>
                                        <span class="btn-spinner hidden ml-2">
                                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10  rounded-md mb-5" style="height: 100vh;">
    @php
    // Ambil parameter sort dan filter tanggal dari request
    $sort = request('sort', 'desc');
    $from = request('from');
    $to = request('to');

    // Filter transaksi berdasarkan rentang tanggal jika ada
    $filteredTransaksi = $transaksi;
    if ($from && $to) {
    try {
        $fromDate = \Carbon\Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
        $toDate = \Carbon\Carbon::createFromFormat('d/m/Y', $to)->endOfDay();
        $filteredTransaksi = $filteredTransaksi->filter(function ($item) use ($fromDate, $toDate) {
        $itemDate = \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal);
    return $itemDate->between($fromDate, $toDate);
    });
    } catch (\Exception $e) {
    // Jika format salah, tampilkan semua
    }
    }

    // Urutkan transaksi berdasarkan tanggal sesuai sort
    $filteredTransaksi =
    $sort === 'asc' ? $filteredTransaksi->sortBy(function ($item) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
    }) : $filteredTransaksi->sortByDesc(function ($item) {
        return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
    });
    @endphp

<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3 p-2">

    {{-- Tombol Filter --}}
    <div class="flex flex-row md:flex-row gap-2 w-full md:w-auto">
        <!-- Filter -->
        <div x-data="{ openFilter: false }" class="w-auto md:w-auto relative">
            <button type="button"
                    @click="openFilter = !openFilter"
                    class="p-3 rounded-lg bg-gray-100 hover:bg-blue-100 dark:bg-black border border-gray-200 dark:border-white/10 flex items-center justify-center md:justify-start gap-1 w-full md:w-auto">
                <i class="fas fa-filter"></i>
                <span class="hidden sm:inline">Filter</span>
            </button>

            <!-- Dropdown Filter -->
            <div x-show="openFilter"
                 @click.away="openFilter = false"
                 x-transition
                 class="absolute z-50 mt-2 left-0 bg-white dark:bg-black border border-gray-200 dark:border-white/10 rounded-lg shadow-lg p-4 min-w-[320px]">
                <form method="GET" id="filterForm" class="flex flex-col gap-3">
                    <h3 class="font-semibold text-sm">Filter Transaksi</h3>

                    <div class="flex items-center gap-2">
                        <label class="text-sm w-16">Dari:</label>
                        <input type="text" name="from" id="from_date"
                               value="{{ $from }}"
                               class="form-input py-1 px-2 rounded border border-black/10 dark:border-white/10 w-full"
                               placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>

                    <div class="flex items-center gap-2">
                        <label class="text-sm w-16">Sampai:</label>
                        <input type="text" name="to" id="to_date"
                               value="{{ $to }}"
                               class="form-input py-1 px-2 rounded border border-black/10 dark:border-white/10 w-full"
                               placeholder="dd/mm/yyyy" autocomplete="off">
                    </div>

                    <div class="flex gap-2 mt-3">
                        <button type="submit"
                                class="submitBtn flex items-center justify-center gap-2 bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition w-full">
                            <span class="btn-text">Terapkan</span>
                            <span class="btn-spinner hidden animate-spin border-2 border-white border-t-transparent rounded-full w-4 h-4"></span>
                        </button>

                        <a href="{{ route('index.keuangan') }}"
                           class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 transition w-full text-center">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Pilih Bulan -->
        <form method="GET" class="flex-1 md:w-auto">
            <input type="month"
                   name="periode"
                   value="{{ $tahun }}-{{ str_pad($bulan, 2, '0', STR_PAD_LEFT) }}"
                   onchange="this.form.submit()"
                   class="bg-white dark:bg-black form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-100 dark:focus:border-white/20 dark:focus:ring-white/5">
        </form>
    </div>
</div>


{{-- Script --}}
<script>
    // Flatpickr Init
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#from_date", {
            dateFormat: "d/m/Y",
            locale: "id",
            allowInput: true
        });
        flatpickr("#to_date", {
            dateFormat: "d/m/Y",
            locale: "id",
            allowInput: true
        });
    });

    // Spinner Button Global
    document.addEventListener('submit', function (e) {
        const submitBtn = e.target.querySelector('.submitBtn');
        if (!submitBtn) return;

        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');

        btnText.textContent = 'Menyimpan...';
        btnSpinner.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.classList.add('bg-blue-400', 'cursor-not-allowed');
        submitBtn.classList.remove('hover:bg-blue-700');
    }, true);
</script>

    <div class="table-responsive p-2">

        <table class="w-full border-collapse text-sm table-auto " id="produkTable">

            @php
            // Group transaksi by tanggal (format: Y-m-d)
            $groupedTransaksi = $transaksi->groupBy(function ($item) {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $item->tanggal)->format('Y-m-d');
            });
            @endphp

            @foreach ($groupedTransaksi as $tanggal => $items)
            @php
            // Format tanggal untuk header
            $carbonTanggal = \Carbon\Carbon::createFromFormat('Y-m-d', $tanggal);
            $tanggalFormatted = $carbonTanggal->translatedFormat('d F Y - l');
            // Hitung total pemasukan & pengeluaran untuk tanggal ini
            $pemasukan = $items->where('tipe', 'pemasukan')->sum('total');
            $pengeluaran = $items->where('tipe', 'pengeluaran')->sum('total');
            @endphp
            <thead>
                <!-- Untuk Desktop -->
                <tr >
                    <th width="70%" class="text-left font-normal">
                        {{ $tanggalFormatted }}
                    </th>
                    <th width="30%" class="text-right">
                        <div class="hidden sm:flex flex-col lg:flex-row gap-6 text-gray-600 justify-end">
                            <div>
                                Masuk:
                                <span class="text-green-600">
                                    Rp {{ number_format($pemasukan, 0, ',', '.') }}
                                </span>
                            </div>
                            <div>
                                Keluar:
                                <span class="text-red-600">
                                    Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </th>
                </tr>

                <!-- Untuk Mobile -->
                <tr class="text-gray-400 lg:hidden">
                    <th width="50%" class="text-left font-normal">
                        <div>Masuk: <span class="text-green-600">Rp {{ number_format($pemasukan, 0, ',', '.') }}</span></div>
                    </th>
                    <th class="text-left">
                        <div>Keluar: <span class="text-red-600">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</span></div>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($items as $item)
                <tr class="border rounded-lg p-3 bg-white dark:bg-black shadow-sm  dark:border-white/10" x-data="{ openDetail: false }">
                    <!-- Produk / Transaksi -->
                    <td class="py-4 pl-6 flex items-start gap-3">
                        <div class="flex flex-col">
                            <!-- Trigger Modal -->
                            <span class="font-semibold leading-tight">
                                <a href="#"
                                   @click.prevent="openDetail = true; $nextTick(() => { document.dispatchEvent(new Event('open-detail')); })"
                                   class="hover:underline">
                                    <span class="akun-nama">
                                        {{ $item->deskripsi ?? '-' }}
                                    </span>
                                </a>
                            </span>

                            <span class="text-gray-400 leading-tight truncate max-w-[120px] akun-nama">
                                {{ $item->akun->nama_akun ?? '-' }}
                            </span>
                            <!-- Modal Detail -->
                            <div x-show="openDetail" x-transition
                                class="fixed inset-0 flex items-center justify-center bg-black/60"
                                style="display: none; z-index:9999;"
                                @click.outside="
                                    // Cek apakah klik berasal dari select2 (dropdown / searchbox)
                                    if (!$event.target.closest('.select2-container')
                                        && !$event.target.closest('.select2-dropdown')) {
                                        openDetail = false;
                                    }
                                ">

                                <div class="bg-white dark:bg-black rounded-lg shadow-lg w-full max-w-lg relative m-5"
                                     @click.away="openDetail = false" style="margin:10px;" >

                                    <!-- Header -->
                                    <div class="flex justify-between items-center border-b px-5 py-3 ">
                                        <h3 class="font-semibold text-lg">Detail Transaksi</h3>

                                        <!-- Tombol Hapus -->
                                        <a href="#"
   onclick="event.preventDefault(); Swal.fire({
       title: 'Yakin ingin menghapus?',
       text: 'Data transaksi yang dihapus tidak bisa dikembalikan.',
       icon: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#dc2626',
       cancelButtonColor: '#6b7280',
       confirmButtonText: 'Ya, hapus!',
       cancelButtonText: 'Batal',
       customClass: {
           title: 'mb-4 text-lg font-bold',
           htmlContainer: 'mb-4 text-sm text-gray-600',
           confirmButton: 'bg-red-600 text-white px-4 py-2 rounded mr-2 hover:bg-red-700',
           cancelButton: 'bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300'
       },
       buttonsStyling: false,
       didOpen: () => {
           document.querySelector('.swal2-container').style.zIndex = 10000;
       }
   }).then((result) => {
       if (result.isConfirmed) {
           window.location.href = '{{ route('keuangan.delete', $item->id) }}';
       }
   });"
   title="Hapus Data"
   class="p-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition flex items-center justify-center">
    <!-- Ikon trash -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>
</a>

                                    </div>

                                    <!-- Content -->
                                    <div class="p-5 space-y-4">
                                        <form action="{{ route('keuangan.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $item->id }}">
                                            <input type="hidden" name="tipe" value="{{ $item->tipe }}">

                                            <!-- Tanggal -->
                                            <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">

                                                {{-- Kolom Tanggal --}}
                                                <div style="width: 70%;">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                                        Tanggal
                                                    </label>
                                                    <input type="text"
                                                           name="tanggal"
                                                           class="tanggal form-input w-full"
                                                           value="{{ $item->tanggal }}">
                                                </div>

                                                {{-- Kolom Waktu --}}
                                                <div style="width: 30%;">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                                        Waktu
                                                    </label>
                                                    <input type="text" id="waktu_display_{{ $item->id }}"
                                                           class="form-input w-full cursor-not-allowed"
                                                           value="{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}"
                                                           disabled>
                                                    <input type="hidden" name="waktu"
                                                           id="waktu_{{ $item->id }}"
                                                           value="{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }}">
                                                </div>
                                            </div>


                                            <!-- Deskripsi + Foto -->
                                            <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">
                                                <!-- Deskripsi -->
                                                <div class="flex-1">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-input" rows="2">{{ $item->deskripsi }}</textarea>
                                                </div>

                                                <!-- Upload Foto -->
                                                <div x-data="{ fileName: '', fileUrl: '' }">
                                                    <label for="foto-upload-edit-{{ $item->id }}"
                                                           class="cursor-pointer flex flex-col items-center justify-center w-24 h-24 border-2 border-dashed rounded-lg hover:bg-gray-200 transition-colors duration-200"
                                                           :class="fileName ? 'bg-green-100 border-green-500' : 'bg-gray-100'">

                                                        <!-- Preview file baru (kalau ada) -->
                                                        <template x-if="fileUrl">
                                                            <img :src="fileUrl" alt="Preview"
                                                                 class="w-20 h-20 object-cover rounded mb-1" />
                                                        </template>


                                                        <!-- Preview foto lama dari database -->
                                                        <template x-if="!fileUrl">
                                                            @if ($item->foto)
                                                                <!-- Fancybox preview -->
                                                                <a data-fancybox="gallery-{{ $item->id }}"
                                                                    data-caption="Foto {{ $item->id }}"
                                                                    href="{{ asset('storage/' . $item->foto) }}">
                                                                     <img src="{{ asset('storage/' . $item->foto) }}"
                                                                          class="w-20 h-20 object-cover rounded mb-1" />
                                                                 </a>

                                                            @else
                                                                <!-- Icon default kalau tidak ada foto -->
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     class="w-10 h-10 text-gray-400"
                                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                          d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828L18 9.828M7 7h.01M21 21H3V3h18v18z" />
                                                                </svg>
                                                            @endif
                                                        </template>


                                                        <!-- Nama file -->
                                                        <span x-text="fileName ? fileName : 'Upload Foto'"
                                                              class="text-xs text-gray-500 mt-1 text-center"></span>

                                                        <!-- Input file -->
                                                        <input id="foto-upload-edit-{{ $item->id }}" type="file" name="foto"
                                                               class="hidden"
                                                               accept="image/*"
                                                               @change="
                                                                   fileName = $event.target.files[0]?.name || '';
                                                                   if ($event.target.files[0]) {
                                                                       const reader = new FileReader();
                                                                       reader.onload = e => fileUrl = e.target.result;
                                                                       reader.readAsDataURL($event.target.files[0]);
                                                                   } else {
                                                                       fileUrl = '';
                                                                   }
                                                               " />
                                                    </label>
                                                </div>

                                            </div>

                                            <!-- Kategori Transaksi -->
                                            <div class="mb-4 relative bg-white dark:bg-white/5 rounded-lg border border-black/10">
                                                <!-- Debit -->
                                                <div class="py-4 px-5">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                                        {{ $item->tipe == 'pemasukan' ? 'Masuk ke (Debit)' : 'Untuk Biaya (Debit)' }}
                                                    </label>
                                                    <select name="id_akun" class="select-form w-full" style="width: 100%;" data-placeholder="Pilih Akun">
                                                        <option></option>
                                                        @if ($item->tipe == 'pemasukan')
                                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'aset') as $akunItem)
                                                                <option value="{{ $akunItem->id }}"
                                                                        {{ $item->id_akun == $akunItem->id ? 'selected' : '' }}
                                                                        data-kategori="{{ $akunItem->kategori->nama_kategori ?? '' }}">
                                                                    {{ $akunItem->kode_akun }} | {{ $akunItem->nama_akun }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'beban') as $akunItem)
                                                                <option value="{{ $akunItem->id }}"
                                                                        {{ $item->id_akun == $akunItem->id ? 'selected' : '' }}
                                                                        data-kategori="{{ $akunItem->kategori->nama_kategori ?? '' }}">
                                                                    {{ $akunItem->kode_akun }} | {{ $akunItem->nama_akun }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                                <hr class="dark:border-white/10 border-black/10">

                                                <!-- Credit -->
                                                <div class="py-4 px-5">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                                        {{ $item->tipe == 'pemasukan' ? 'Diterima dari (Credit)' : 'Diambil dari (Credit)' }}
                                                    </label>
                                                    <select name="id_akun_second" class="select-form w-full" style="width: 100%;" data-placeholder="Pilih Akun">
                                                        <option></option>
                                                        @if ($item->tipe == 'pemasukan')
                                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'pendapatan') as $akunItem)
                                                                <option value="{{ $akunItem->id }}" data-kategori="{{ $akunItem->kategori->nama_kategori ?? '' }}"
                                                                        {{ $item->id_akun_second == $akunItem->id ? 'selected' : '' }}>
                                                                    {{ $akunItem->kode_akun }} | {{ $akunItem->nama_akun }}
                                                                </option>
                                                            @endforeach
                                                        @else
                                                            @foreach ($akun->filter(fn($a) => $a->kategori->tipe === 'aset') as $akunItem)
                                                                <option value="{{ $akunItem->id }}" data-kategori="{{ $akunItem->kategori->nama_kategori ?? '' }}"
                                                                        {{ $item->id_akun_second == $akunItem->id ? 'selected' : '' }}>
                                                                    {{ $akunItem->kode_akun }} | {{ $akunItem->nama_akun }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Nominal & Rekening -->
                                            <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 flex items-center gap-4">
                                                <!-- Nominal -->

                                                <div class="flex-1">
                                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">
                                                        Nominal (Rp) <span style="color: red">*</span>
                                                    </label>
                                                    <input type="text"
                                                           name="total"
                                                           value="Rp. {{ number_format($item->total, 0, ',', '.') }}"
                                                           class="form-input currency-input"
                                                           placeholder="Rp 0"
                                                           autocomplete="off"
                                                           inputmode="numeric">
                                                </div>

                                                <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    // Ambil semua input dengan class currency-input
                                                    const inputs = document.querySelectorAll('.currency-input');

                                                    inputs.forEach(function(input) {
                                                        input.addEventListener('input', function() {
                                                            // Ambil angka mentah
                                                            let value = this.value.replace(/\D/g, '');

                                                            // Format ke rupiah
                                                            if (value) {
                                                                this.value = 'Rp. ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                            } else {
                                                                this.value = '';
                                                            }
                                                        });

                                                        // Saat submit, hapus format "Rp." & titik
                                                        if (input.form) {
                                                            input.form.addEventListener('submit', function() {
                                                                inputs.forEach(function(i) {
                                                                    i.value = i.value.replace(/\D/g, '');
                                                                });
                                                            });
                                                        }
                                                    });
                                                });
                                                </script>


                                                <!-- Rekening Dropdown (Alpine.js) -->
                                                <div x-data="{ openRekening: false, selectedRekening: '{{ $item->id_rekening }}', selectedRekeningName: '{{ $item->rekening->nama_rekening ?? '' }}' }"
                                                     class="relative">
                                                    <button type="button"
                                                            @click="openRekening = !openRekening"
                                                            class="flex items-center gap-2 px-3 py-2 border rounded-lg bg-gray-100 hover:bg-gray-200 transition">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <rect x="3" y="7" width="18" height="13" rx="2" />
                                                            <path d="M16 3v4M8 3v4M3 11h18" />
                                                        </svg>
                                                        <span
                                                            class=" hidden sm:block block max-w-full text-ellipsis overflow-hidden whitespace-nowrap"
                                                            x-text="selectedRekeningName ? selectedRekeningName : '{{ $item->rekening->nama_rekening ?? 'Rekening' }}'">
                                                        </span>

                                                        <svg class="w-4 h-4 ml-1 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>

                                                    <!-- List Rekening -->
                                                    <div x-show="openRekening" @click.away="openRekening = false" class="fixed inset-0  flex items-center justify-center bg-black/20" style="z-index:1001;">
                                                        <div class="bg-white dark:bg-black border rounded-lg shadow-lg w-80 max-w-full" @click.stop>
                                                            <div class="p-3 border-b font-semibold">Pilih Rekening</div>
                                                            <ul>
                                                                @foreach ($rekening as $rek)
                                                                    <li>
                                                                        <button type="button"
                                                                            @click="selectedRekening = '{{ $rek->id }}'; selectedRekeningName = '{{ \Illuminate\Support\Str::limit($rek->nama_rekening, 10, '...') }}'; openRekening = false"
                                                                            class="w-full text-left px-4 py-2 hover:bg-blue-100 transition flex items-center justify-between">

                                                                            <span class="block truncate">{{ $rek->nama_rekening }}</span>

                                                                            <template x-if="selectedRekening === '{{ $rek->id }}'">
                                                                                <svg class="w-4 h-4 text-green-500 ml-2 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                                </svg>
                                                                            </template>
                                                                        </button>

                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            <div class="p-2 flex justify-end">
                                                                <button type="button" @click="openRekening = false"
                                                                        class="text-sm px-3 py-1 rounded bg-gray-100 hover:bg-gray-200">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="id_rekening" :value="selectedRekening">
                                                </div>
                                            </div>

                                            <!-- Tombol Aksi -->
                                            <div class="flex gap-5">
                                                <button type="button"
                                                        @click.prevent="openDetail = false"
                                                        class="w-full bg-red-100 text-red-600 px-4 py-2 rounded-lg hover:bg-red-200">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>


                    <td class="py-4 font-semibold mobile lg:table-cell">
                        <span class="{{ $item->tipe == 'pemasukan' ? 'text-green-600' : 'text-red-600' }}">
                            Rp. {{ number_format($item->total, 0, ',', '.') }}
                        </span>
                    </td>
                </tr>

                @endforeach
            </tbody>

            @endforeach

        </table>
        @if ($transaksi->isEmpty())
        <tbody>
            <tr>
                <td colspan="10" class="py-20 text-center align-middle">
                    <div class="flex flex-col items-center justify-center text-gray-400 py-5">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <span class="text-lg font-medium">
                            Tidak ada transaksi ditemukan.
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    @endif



    </div>
</div>
@section('js')

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    flatpickr(".tanggal", {
        dateFormat: "d/m/Y"
        , defaultDate: "today"
        , locale: "id"
    });

    // Inisialisasi flatpickr
    function updateTime() {
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        const waktuSekarang = `${jam}:${menit}:${detik}`;

        // Misal class-nya adalah "waktu_display" dan "waktu"
            document.querySelectorAll(".waktu_display").forEach(function(el) {
                el.value = waktuSekarang;
            });

            document.querySelectorAll(".waktu").forEach(function(el) {
                el.value = waktuSekarang;
            });

    }

    updateTime(); // set waktu saat load
    setInterval(updateTime, 1000); // update setiap detik

</script>
<script>
    function formatAkun(option) {
        if (!option.id) return option.text;
        const kategori = $(option.element).data('kategori') || '';
        const text = option.text || '';
        return $(`
            <div style="display:flex;flex-direction:column;line-height:1.3">
                <span style="font-weight:600;margin-bottom:5px;">${text}</span>
                <span style="font-size:12px;color:#999;">${kategori}</span>
            </div>
        `);
    }

    function initSelect2(modal) {
        $('.select-form').each(function() {
            if ($(this).hasClass("select2-hidden-accessible")) {
                $(this).select2("destroy");
            }
        });
        $('.select-form').select2({
            placeholder: 'Pilih Kategori Akun',
            width: '100%',
            templateResult: formatAkun,
            templateSelection: formatAkun,
            escapeMarkup: m => m,
            dropdownParent: modal // dropdown nempel ke modal
        });
    }

    $(document).ready(function() {
        initSelect2();

        // Re-init setiap kali modal dibuka
        document.addEventListener('open-detail', function () {
            setTimeout(initSelect2, 100);
        });
    });

    </script>
  <script>
    $(document).on('select2:open', function() {
document.querySelectorAll('.select2-search__field').forEach(el => {
    el.addEventListener('mousedown', e => e.stopPropagation());
    el.addEventListener('click', e => e.stopPropagation());
});
});
 </script>

@endsection
@endsection
