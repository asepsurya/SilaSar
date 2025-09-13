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
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-7 mb-4">
    <div class="bg-lightblue-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Mitra</p>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl leading-9 font-semibold text-black">{{ $mitra->count() }}</h2>
            <div class="flex items-center gap-1">
                <p class="text-xs leading-[18px] text-black">Toko/Mitra</p>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z" fill="#1C1C1C"></path>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-lightpurple-100 rounded-2xl p-6">
        <p class="text-sm font-semibold text-black mb-2">Jumlah Kota</p>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl leading-9 font-semibold text-black">{{ $totalKota }}</h2>
            <div class="flex items-center gap-1">
                <p class="text-xs leading-[18px] text-black">Kota</p>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.45488 5.60777L14 4L12.6198 9.6061L10.898 7.9532L8.12069 10.8463C8.02641 10.9445 7.89615 11 7.76 11C7.62385 11 7.49359 10.9445 7.39931 10.8463L5.36 8.72199L2.36069 11.8463C2.16946 12.0455 1.85294 12.0519 1.65373 11.8607C1.45453 11.6695 1.44807 11.3529 1.63931 11.1537L4.99931 7.65373C5.09359 7.55552 5.22385 7.5 5.36 7.5C5.49615 7.5 5.62641 7.55552 5.72069 7.65373L7.76 9.77801L10.1766 7.26067L8.45488 5.60777Z" fill="#1C1C1C"></path>
                </svg>
            </div>
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
    <div x-data="main" x-init="init()" class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
        <div class="mb-4">
            <p class="text-sm font-semibold">Daftar Mitra dan Toko</p>
            <p class="text-xs text-black/60 dark:text-white/60">Berikut adalah daftar mitra dan toko yang telah terdaftar di sistem.</p>
        </div>
        <div class="overflow-auto">
            <table id="myTable" class="whitespace-nowrap table-hover table-bordered w-full"></table>
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
                const year = String(now.getFullYear()).slice(-2); // 2 digit terakhir tahun
                const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

                kodeInput.value = `MTR-${month}${year}/${random}`;
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
                        headings: ["Kode Mitra", "Nama Mitra", "Alamat", "Kota", "No. Telepon"]
                        , data: @json($mitra)
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
