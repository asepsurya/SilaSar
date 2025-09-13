@extends('layout.main')
@section('css')

@section('title', 'Detail Transaksi')
@section('container')
    <style>
        /* Membatasi tinggi dropdown dan menambah overflow auto untuk scroll jika terlalu panjang */
        #kota-recommendations {
            max-height: 200px;
            /* Atur tinggi maksimal dropdown */
            width: 100%;
            /* Menyesuaikan lebar dropdown dengan lebar input */
            position: absolute;
            top: 100%;
            /* Menampilkan di bawah input */
            left: 0;
            z-index: 10;
        }

        /* Agar dropdown terlihat lebih rapi saat tampil */
        #kota-recommendations li {
            padding: 8px 10px;
            cursor: pointer;
        }

        #kota-recommendations li:hover {
            background-color: #f0f0f0;
        }

        /* Dark mode hover effect */
        .dark #kota-recommendations li:hover {
            background-color: #374151;
            /* dark hover */
        }

        .dark #kota-recommendations li {
            color: #e5e7eb;
            /* warna teks dark mode */
        }

        .dark #kota-recommendations {
            background-color: #1c1c1c;
            /* dark background */
            border-color: #4b5563;
            /* dark border */
        }

        /* Style untuk desktop (default) */
        #produkTable .table-responsive {
            width: 100%;
            overflow-x: hidden;
            /* Sembunyikan scroll horizontal di desktop */
        }

        /* Style khusus untuk mobile */
        @media (max-width: 768px) {
            #produkTable .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                /* Aktifkan scroll horizontal */
                -webkit-overflow-scrolling: touch;
                /* Scroll halus di iOS */

                /* Optional: Styling tambahan untuk mobile */
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 15px;
            }

            #produkTable table {
                min-width: 600px;
                /* Lebar minimum tabel */
                white-space: nowrap;
                /* Mencegah text wrapping */
            }

            #produkTable th,
            #produkTable td {
                padding: 8px 12px;
                /* Padding lebih kecil di mobile */
            }

            /* Style scrollbar (opsional) */
            #produkTable .table-container::-webkit-scrollbar {
                height: 5px;
            }

            #produkTable .table-container::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 5px;
            }
        }
    </style>
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Transaksi Mitra / Toko</h2>
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="button"
                onclick="confirmDelete('{{ route('hapusTransksi', $transaksi->kode_transaksi) }}')"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow transition duration-150">
                Hapus Transaksi ini?
            </button>
           <script>
            function confirmDelete(url) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Tindakan ini akan menghapus seluruh data transaksi, termasuk semua produk yang terkait. Proses ini tidak dapat dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded',
                        cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            }
           </script>
        </div>
    </div>


        <div class="border border-black/10 dark:border-white/10 p-6 rounded-lg mb-6 shadow-sm bg-white dark:bg-white/5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                            Tanggal Transaksi
                        </label>
                        <input type="date" id="tanggal_transaksi"
                            class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                            name="tanggal_transaksi" value="{{ $mitra->tanggal_transaksi ?? date('Y-m-d') }}" required
                            style="appearance: none; -webkit-appearance: none; background:  url('data:image/svg+xml;utf8,<svg fill=\'%236B7280\' height=\'20\' viewBox=\'0 0 20 20\' width=\'20\' xmlns=\'http://www.w3.org/2000/svg\'><path d=\'M7.293 9.293a1 1 0 011.414 0L10 10.586l1.293-1.293a1 1 0 111.414 1.414l-2 2a1 1 0 01-1.414 0l-2-2a1 1 0 010-1.414z\'/></svg>') no-repeat right 0.75rem center/1.5em 1.5em; padding-right: 2.5rem;" />
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="block mb-1 text-xs font-semibold text-gray-600 dark:text-gray-300">
                            Nomor Transaksi
                        </label>
                        <input type="text"
                            class="form-input w-full rounded-md border-gray-300  text-gray-800 font-bold text-lg"
                            name="nomor_transaksi" value="{{ $transaksi->kode_transaksi }}" readonly />
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
            <div class="col-span-1">
                <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
                    <button type="button"
                        class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                        data-accordion-target="#accordion-mitra" aria-expanded="true" aria-controls="accordion-mitra"
                        onclick="toggleAccordion('accordion-mitra')">
                        <span class="font-semibold text-sm">Data Mitra / Toko</span>
                        <svg class="w-4 h-4 transition-transform rotate-180" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="accordion-mitra" class="px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                        <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Mitra / Toko</label>
                            <div class="flex items-center">
                                <span class="mr-2 text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7V6a1 1 0 011-1h16a1 1 0 011 1v1M5 21h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2zM16 10V5a1 1 0 00-1-1h-6a1 1 0 00-1 1v5" />
                                    </svg>
                                </span>
                                <input type="text"
                                    class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                    name="nama_mitra" value="{{ $mitra->nama_mitra }}" readonly />
                            </div>
                        </div>
                        <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Asal Kota</label>
                            <input type="text" id="kota-input" name="id_kota"
                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                placeholder="Masukan Nama Kota Mitra" value="{{ $mitra->id_kota }}" autocomplete="off"
                                oninput="showRecommendations()" readonly />
                            <ul id="kota-recommendations"
                                class="absolute w-full mt-1 bg-white dark:bg-dark dark:border-white/10 border border-gray-200 shadow-lg hidden max-h-40 overflow-y-auto z-10 rounded-md">
                                <!-- Data recommendations will be injected here -->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-1 hidden md:block">
                <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
                    <button type="button"
                        class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                        data-accordion-target="#accordion-mitra2" aria-expanded="true" aria-controls="accordion-mitra2"
                        onclick="toggleAccordion('accordion-mitra2')">
                        <span class="font-semibold text-sm">Kontak & Kode Customer</span>
                        <svg class="w-4 h-4 transition-transform rotate-180" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="accordion-mitra2" class="px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                        <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nomor Telepon Mitra</label>
                            <input type="text"
                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                name="no_telp_mitra" value="{{ $mitra->no_telp_mitra }}" readonly />
                        </div>
                        <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10 mb-4">
                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Kode Customer</label>
                            <input type="text"
                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                name="kode_mitra" value="{{ $mitra->kode_mitra }}" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accordions Start -->
        <div id="accordion-parent" class="mb-6 hidden md:block">
            <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
                <button type="button"
                    class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                    data-accordion-target="#accordion-alamat" aria-expanded="false" aria-controls="accordion-alamat"
                    onclick="toggleAccordion('accordion-alamat')">
                    <span class="font-semibold text-sm">Alamat Mitra / Toko</span>
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="accordion-alamat" class="hidden px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                    <textarea class="form-input w-full" name="alamat_mitra" placeholder="Alamat Mitra" readonly>{{ $mitra->alamat_mitra }}</textarea>
                </div>
            </div>
            <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
                <button type="button"
                    class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none"
                    data-accordion-target="#accordion-maps" aria-expanded="false" aria-controls="accordion-maps"
                    onclick="toggleAccordion('accordion-maps')">
                    <span class="font-semibold text-sm">Titik Lokasi (Google Maps)</span>
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="accordion-maps" class="hidden px-4 py-3 bg-white dark:bg-white/5 rounded-b-lg">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Longitude</label>
                            <input type="text" placeholder="Longitude" class="form-input w-full" name="longitude"
                                id="longitude" value="{{ $mitra->longitude }}" readonly />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Latitude</label>
                            <input type="text" placeholder="Latitude" class="form-input w-full" name="latitude"
                                id="latitude" value="{{ $mitra->latitude }}" readonly />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Accordions End -->

        <script>
            function toggleAccordion(id) {
                const content = document.getElementById(id);
                const btn = document.querySelector(`[data-accordion-target="#${id}"] svg`);
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    btn.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    btn.classList.remove('rotate-180');
                }
            }
        </script>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
                <div class="px-2 py-1 mb-4 flex items-center justify-between">
                    <p class="text-sm font-semibold">Daftar barang yang dijual</p>
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = !open"
                            class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            + Tambah Produk
                        </button>

                        <!-- Overlay -->
                        <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                            :class="{ 'block': open, 'hidden': !open }">
                            <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                                <!-- Modal Box -->
                                <div x-show="open" x-transition x-transition.duration.300
                                    class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                                    style="display: none;">
                                    <!-- Header -->
                                    <div
                                        class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                        <h5 class="font-semibold text-lg">Pilih Produk di Mitra {{ $mitra->nama_mitra }}
                                        </h5>
                                        <button type="button"
                                            class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                            @click="open = false">
                                            <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                                    fill="currentcolor" />
                                                <path
                                                    d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                                    fill="currentcolor" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5 ">

                                        <div class=" border-gray-300 rounded overflow-hidden">
                                            <!-- Header Table -->
                                            <table class="min-w-full border-b border-gray-300">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-3 py-2 border text-center w-12">
                                                            <input type="checkbox" id="check-all"
                                                                onclick="toggleCheckAll(this)">
                                                        </th>
                                                        <th class="px-3 py-2 border ">Kode</th>
                                                        <th class="py-2 border ">Nama</th>
                                                    </tr>
                                                </thead>
                                            </table>

                                            <!-- Body Table dengan scroll -->
                                            <div class="max-h-[100px] overflow-y-auto" style="max-height: 200px;">
                                                <table class="min-w-full">
                                                    <tbody>
                                                        @foreach ($penawaran as $produk)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-3 py-2 border text-center w-12">
                                                                    <input type="checkbox" name="kode_produk[]"
                                                                        value="{{ $produk->produk->kode_produk }}">
                                                                </td>
                                                                <td class="px-5 py-2 border w-32">
                                                                    {{ $produk->produk->kode_produk }}
                                                                </td>
                                                                <td class="px-3 py-2 border w-[300px]">
                                                                    {{ $produk->produk->nama_produk }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>


                                        <button type="button" style="margin-top: 20px;"
                                            onclick="updateKodeTransaksi('{{ $transaksi->kode_transaksi }}','{{ $mitra->kode_mitra }}')"
                                            class="w-full inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow">
                                            Tambahkan Produk
                                        </button>
                                        <a href="/mitra/detail/{{ $mitra->id }}">
                                            <center class="mt-2" style="color:blue;"><small>Tambah Penawaran
                                                    Baru?</small></center>
                                        </a>
                                        <script>
                                            function toggleCheckAll(source) {
                                                const checkboxes = document.querySelectorAll('input[name="kode_produk[]"]');
                                                checkboxes.forEach(cb => cb.checked = source.checked);
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="overflow-x-auto hidden md:block">
                    <form action="{{ route('transaksi.update') }}" method="POST" id="form-transaksi">
                        @csrf
                        {{-- ------------------------------------------- --}}
                        {{-- -----------Data -------------------------- --}}
                        <div class="hidden">
                            <input type="date" id="tanggal_transaksi_new"
                            class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                            name="tanggal_transaksi"  required />
                            <script>
                                $(document).ready(function () {
                                    $('#tanggal_transaksi_new').val($('#tanggal_transaksi').val());
                                    $('#tanggal_transaksi').on('change input', function () {
                                        $('#tanggal_transaksi_new').val($(this).val());
                                    });
                                });
                            </script>
                            <input type="text"
                            class="form-input w-full rounded-md border-gray-300  text-gray-800 font-bold text-lg"
                            name="nomor_transaksi" value="{{ $transaksi->kode_transaksi }}" readonly />
                            <input type="text"
                            class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                            name="kode_mitra" value="{{ $mitra->kode_mitra }}" readonly />
                            {{-- ------------------------------------------- --}}
                            {{-- -----------Data -------------------------- --}}
                        </div>
                        <table id="productTable"
                            class="min-w-full border bg-white border-gray-300 rounded-lg shadow-sm dark:bg-transparent">
                            <thead class="bg-gray-100 dark:bg-transparent">
                                <tr>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 w-10 text-center">
                                    </th>

                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 w-10 text-center">
                                        #</th>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 w-1/3">
                                        Nama Produk</th>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 text-center">
                                        Barang Keluar</th>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 text-center">
                                        Barang Terjual</th>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 text-center">
                                        Barang Retur</th>
                                    <th
                                        class="border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 dark:text-gray-200 text-center">
                                        Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalSemua = 0;
                                    $no = 1;
                                @endphp
                                @forelse ($product->where('kode_transaksi',$transaksi->kode_transaksi) as $index => $row)
                                    @php
                                        $barang_keluar = (int) ($row->barang_keluar ?? 0);
                                        $barang_terjual = (int) ($row->barang_terjual ?? 0);
                                        $barang_retur = (int) ($row->barang_retur ?? 0);
                                        $harga = (int) ($row->penawaran->harga ?? 0);
                                        $total_barang_keluar = $barang_terjual + $barang_retur;
                                        $total = $barang_keluar * $harga;
                                        $totalSemua += $total;
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black">
                                        <td class="border border-gray-300 px-3 py-2 text-center" width="1%">
                                            <button type="button"
                                                onclick="hapusItem('{{ $transaksi->kode_transaksi }}','{{ $row->produk->kode_produk }}')"
                                                style="color:red;">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z" />
                                                </svg>
                                            </button>
                                        </td>
                                        <td class="border border-gray-300 px-3 py-2 text-center">{{ $no++ }}</td>
                                        <td class="border border-gray-300 px-3 py-2">
                                            <button type="button"
                                                class="form-input w-full bg-gray-50 dark:bg-gray-800 border-gray-300 rounded-md text-left cursor-pointer hover:bg-blue-50 dark:hover:bg-blue-900"
                                                onclick="showProductDetail('{{ $row->produk->kode_produk }}')"
                                                style="padding: 0.5rem 0.75rem;">
                                                {{ $row->produk->nama_produk ?? '-' }}
                                            </button>
                                            <input type="hidden" name="kode_produk[]"
                                                value="{{ $row->produk->kode_produk }}">

                                            <!-- Modal for product detail -->
                                            <div id="modal-detail-{{ $row->produk->kode_produk }}"
                                                class="fixed inset-0 z-[99999] flex items-center justify-center hidden"
                                                style="z-index: 999999">
                                                <!-- Overlay -->
                                                <div class="absolute inset-0 bg-black/30"></div>
                                                <!-- Modal content -->
                                                <div
                                                    class="relative bg-white  dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3 rounded-lg shadow-lg max-w-lg w-full p-6 z-10">
                                                    <button type="button"
                                                        class="absolute top-2 right-2 text-gray-500 hover:text-red-600"
                                                        onclick="closeProductDetail('{{ $row->produk->kode_produk }}')">
                                                        &times;
                                                    </button>
                                                    <h3 class="text-lg font-bold mb-3">Detail Produk Penawran</h3>
                                                    <div class="">
                                                        <div>
                                                            <table
                                                                class="table-auto border-collapse border border-gray-300 w-full text-sm">
                                                                <tbody>
                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-3 py-2 font-semibold">
                                                                            Kode Produk</td>
                                                                        <td class="border border-gray-300 px-3 py-2">
                                                                            {{ $row->produk->kode_produk }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-3 py-2 font-semibold">
                                                                            Nama Produk</td>
                                                                        <td class="border border-gray-300 px-3 py-2">
                                                                            {{ $row->produk->nama_produk ?? '-' }}</td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td
                                                                            class="border border-gray-300 px-3 py-2 font-semibold">
                                                                            Harga Hasil Penawaran</td>
                                                                        <td class="border border-gray-300 px-3 py-2">Rp.
                                                                            {{ number_format($row->penawaran->harga ?? 0, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    <!-- Tambahkan detail lain sesuai kebutuhan -->
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                    </div>
                                                    <button type="button"
                                                        class="mt-3 w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium  bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150  right-2 text-white hover:text-red-600"
                                                        onclick="closeProductDetail('{{ $row->produk->kode_produk }}')">
                                                        Oke
                                                    </button>
                                                </div>
                                            </div>

                                            <script>
                                                function showProductDetail(kode) {
                                                    document.getElementById('modal-detail-' + kode).classList.remove('hidden');
                                                }

                                                function closeProductDetail(kode) {
                                                    document.getElementById('modal-detail-' + kode).classList.add('hidden');
                                                }
                                            </script>

                                        </td>
                                        <td class="border border-gray-300 px-3 py-2 text-center">
                                            <input type="number" name="barang_keluar[]"
                                                class="form-input w-20 text-center border-gray-300 rounded-md barang-keluar-input"
                                                value="{{ $barang_keluar }}" data-index="{{ $index }}"
                                                data-harga="{{ $harga }}">
                                        </td>
                                        <td class="border border-gray-300 px-3 py-2 text-center">
                                            <input type="number" name="barang_terjual[]"
                                                class="form-input w-20 text-center border-gray-300 rounded-md barang-terjual-input"
                                                value="{{ $barang_terjual }}" data-index="{{ $index }}">

                                        </td>
                                        <td class="border border-gray-300 px-3 py-2 text-center">
                                            <input type="number" name="barang_retur[]"
                                                class="form-input w-20 text-center border-gray-300 rounded-md barang-retur-input"
                                                value="{{ $barang_retur }}" data-index="{{ $index }}">
                                        </td>
                                        <td class="border border-gray-300 px-3 py-2 text-center">
                                            <div class="flex items-center justify-center">
                                                <span class="mr-1">Rp.</span>
                                                <input type="text" name="harga[]"
                                                    value="{{ number_format($total, 0, ',', '.') }}"
                                                    class="form-input harga-input w-24 text-right border-gray-300 rounded-md total-harga-input"
                                                    data-index="{{ $index }}" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr id="noDataRow">
                                        <td colspan="6" class="border border-gray-300 px-3 py-2 text-center text-gray-500">
                                            Belum ada
                                            produk yang ditawarkan.</td>
                                    </tr>
                                @endforelse

                                <!-- Ongkir -->
                                <tr>
                                    <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                    <td class="border border-gray-300 px-3 py-2 font-semibold text-right">
                                        Ongkir
                                    </td>
                                    <td class="border border-gray-300 px-3 py-2 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="mr-1">Rp.</span>
                                            <input type="text" name="ongkir" id="ongkir-input"
                                                class="form-input w-24 text-right border-gray-300 rounded-md"
                                                value="{{ $transaksi->ongkir ?? '0' }}">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Total -->
                                <tr>
                                    <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                    <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Total</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="mr-1">Rp.</span>
                                            <input type="text" name="total" id="total-input"
                                                class="form-input w-24 text-right border-gray-300 rounded-md"
                                                value="{{ $transaksi->total ?? number_format($totalSemua, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Discount -->
                                <tr>
                                    <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                    <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Discount</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center">
                                        <div class="flex items-center justify-center">
                                            <span class="mr-1">Rp.</span>
                                            <input type="text" name="discount" id="discount-input"
                                                class="form-input w-24 text-right border-gray-300 rounded-md"
                                                value="{{ number_format($transaksi->diskon, 0, ',', '.') }}">
                                        </div>
                                    </td>
                                </tr>
                                <!-- Grand Total -->
                                <tr>
                                    <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                    <td class="border border-gray-300 px-3 py-2 font-bold text-right">Grand Total</td>
                                    <td class="border border-gray-300 px-3 py-2 text-center font-bold">
                                        <div class="flex items-center justify-center">
                                            <span class="mr-1">Rp.</span>
                                            <input type="text" name="grand_total" id="grand-total-input"
                                                class="form-input w-24 text-right border-gray-300 rounded-md font-bold"
                                                value="{{ $transaksi->total ?? number_format($totalSemua, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Status Bayar</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <select name="status_bayar" id="status-bayar-input"
                                        class="form-input w-full text-center border-gray-300 rounded-md">
                                        <option value="Belum Bayar"
                                            {{ old('status_bayar', $transaksi->status_bayar ?? '') == 'Belum Bayar' ? 'selected' : '' }}>
                                            Belum Bayar</option>
                                        <option value="Sudah Bayar"
                                            {{ old('status_bayar', $transaksi->status_bayar ?? '') == 'Sudah Bayar' ? 'selected' : '' }}>
                                            Sudah Bayar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2" colspan="5"></td>
                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Tanggal Bayar</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <input type="date" name="tanggal_bayar" id="tanggal-bayar-input"
                                        class="form-input w-full text-center border-gray-300 rounded-md"
                                        value="{{ old('tanggal_bayar', $transaksi->tanggal_pembayaran ?? '') }}">
                                </td>
                            </tr>
                        </table>
                        <script>
                            function formatRupiah(angka) {
                                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }

                            function parseRupiah(str) {
                                return parseInt((str || '0').replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
                            }

                            function updateTotals() {
                                let total = 0;
                                document.querySelectorAll('.barang-keluar-input').forEach(function(input) {
                                    const index = input.dataset.index;
                                    const harga = parseInt(input.dataset.harga) || 0;
                                    const jumlahKeluar = parseInt(input.value) || 0;

                                    const barangTerjualInput = document.querySelector('.barang-terjual-input[data-index="' + index +
                                        '"]');
                                    const barangReturInput = document.querySelector('.barang-retur-input[data-index="' + index + '"]');
                                    const totalInput = document.querySelector('.total-harga-input[data-index="' + index + '"]');

                                    let barangTerjual = parseInt(barangTerjualInput?.value) || 0;


                                    if (barangTerjual > jumlahKeluar) {
                                        barangTerjual = jumlahKeluar;
                                        if (barangTerjualInput) {
                                            barangTerjualInput.value = barangTerjual;
                                        }
                                    }

                                    // Hitung total per baris berdasarkan kondisi
                                    let totalRow = 0;
                                    if (barangTerjual > 0) {
                                        totalRow = barangTerjual * harga; // Jika barang terjual ada, hitung berdasarkan terjual
                                    } else {
                                        totalRow = jumlahKeluar * harga; // Jika tidak ada, hitung berdasarkan keluar
                                    }

                                    if (totalInput) {
                                        totalInput.value = formatRupiah(totalRow);
                                    }

                                    // Tambah ke total keseluruhan
                                    total += totalRow;
                                });

                                // Update total keseluruhan
                                const totalInput = document.getElementById('total-input');
                                if (totalInput) {
                                    totalInput.value = formatRupiah(total);
                                }

                                // Ambil ongkir & diskon
                                const ongkir = parseRupiah(document.getElementById('ongkir-input')?.value);
                                const discount = parseRupiah(document.getElementById('discount-input')?.value);
                                const grandTotal = total + ongkir - discount;

                                // Update grand total
                                const grandTotalInput = document.getElementById('grand-total-input');
                                if (grandTotalInput) {
                                    grandTotalInput.value = formatRupiah(grandTotal);
                                }
                            }
                            document.addEventListener('input', function(e) {
                                if (e.target.classList.contains('barang-terjual-input')) {
                                    const input = e.target;
                                    const index = input.dataset.index;

                                    const barangKeluarInput = document.querySelector(`.barang-keluar-input[data-index="${index}"]`);
                                    const barangReturInput = document.querySelector(`.barang-retur-input[data-index="${index}"]`);
                                    const harga = parseInt(barangKeluarInput.dataset.harga) || 0;

                                    const jumlahKeluar = parseInt(barangKeluarInput.value) || 0;
                                    let barangTerjual = parseInt(input.value) || 0;

                                    // Batasi barang terjual maksimal ke barang keluar
                                    if (barangTerjual > jumlahKeluar) {
                                        barangTerjual = jumlahKeluar;
                                        input.value = barangTerjual;
                                    }

                                    // Hitung barang retur
                                    const barangRetur = jumlahKeluar - barangTerjual;
                                    barangReturInput.value = barangRetur;

                                    // Hitung total per baris
                                    const totalInput = document.querySelector(`.total-harga-input[data-index="${index}"]`);
                                    const totalRow = barangTerjual * harga;
                                    totalInput.value = formatRupiah(totalRow);

                                    // Update total & grand total
                                    updateTotals();
                                }
                            });

                            document.addEventListener('DOMContentLoaded', function() {
                                // Format harga awal saat halaman dimuat
                                document.querySelectorAll('.total-harga-input').forEach(function(input) {
                                    input.value = formatRupiah(parseRupiah(input.value));
                                });

                                // Event listener barang keluar
                                document.querySelectorAll('.barang-keluar-input').forEach(function(input) {
                                    input.addEventListener('input', function() {
                                        updateTotals();
                                    });
                                });

                                // Event listener barang terjual
                                document.querySelectorAll('.barang-terjual-input').forEach(function(input) {
                                    input.addEventListener('input', function () {
                                        const index = this.dataset.index;



                                        const barangKeluar = parseInt(barangKeluarInput?.value) || 0;
                                        let barangTerjual = parseInt(this.value) || 0;

                                        // Batasi barang terjual tidak boleh lebih dari barang keluar
                                        if (barangTerjual > barangKeluar) {
                                            barangTerjual = barangKeluar;
                                            this.value = barangTerjual;
                                        }

                                        // Hitung dan isi barang retur
                                        if (barangReturInput) {
                                            barangReturInput.value = barangKeluar - barangTerjual;
                                        }

                                        // Update total keseluruhan
                                        updateTotals();
                                    });
                                });

                                // Event listener ongkir
                                const ongkirInput = document.getElementById('ongkir-input');
                                if (ongkirInput) {
                                    ongkirInput.addEventListener('input', function() {
                                        this.value = formatRupiah(parseRupiah(this.value));
                                        updateTotals();
                                    });
                                }

                                // Event listener diskon
                                const discountInput = document.getElementById('discount-input');
                                if (discountInput) {
                                    discountInput.addEventListener('input', function() {
                                        this.value = formatRupiah(parseRupiah(this.value));
                                        updateTotals();
                                    });
                                }

                                // Jalankan update totals pertama kali saat halaman selesai dimuat
                                updateTotals();
                            });
                        </script>
                    </form>
                </div>
                <form action="{{ route('transaksi.update') }}" method="POST" id="form-transaksi-mobile">
                    @csrf
                        <div class="md:hidden space-y-2">
                            <div class="hidden">
                                <input type="date" id="tanggal_transaksi_new"
                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                name="tanggal_transaksi"  required />
                                <script>
                                    $(document).ready(function () {
                                        $('#tanggal_transaksi_new').val($('#tanggal_transaksi').val());
                                        $('#tanggal_transaksi').on('change input', function () {
                                            $('#tanggal_transaksi_new').val($(this).val());
                                        });
                                    });
                                </script>
                                <input type="text"
                                class="form-input w-full rounded-md border-gray-300  text-gray-800 font-bold text-lg"
                                name="nomor_transaksi" value="{{ $transaksi->kode_transaksi }}" readonly />
                                <input type="text"
                                class="form-input w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200/50 transition"
                                name="kode_mitra" value="{{ $mitra->kode_mitra }}" readonly />
                                {{-- ------------------------------------------- --}}
                                {{-- -----------Data -------------------------- --}}
                            </div>
                            @php $totalSemua_mobile = 0; @endphp

                            @forelse ($product->where('kode_transaksi', $transaksi->kode_transaksi) as $myindex => $row)

                                <!-- Modal Detail Produk -->
                                @php
                                    $barang_keluar_mobile = (int) ($row->barang_keluar ?? 0);
                                    $barang_terjual_mobile = (int) ($row->barang_terjual ?? 0);
                                    $barang_retur_mobile = (int) ($row->barang_retur ?? 0);
                                    $harga_mobile = (int) ($row->penawaran->harga ?? 0);
                                    $total_barang_keluar_mobile = $barang_terjual_mobile + $barang_retur_mobile;
                                    $total_mobile = $barang_keluar_mobile * $harga_mobile;
                                    $totalSemua_mobile += $total_mobile;
                                @endphp

                                <!-- Item Produk Mobile -->
                                <div class="flex items-center justify-between border rounded-lg p-3 bg-white shadow-sm">
                                    <!-- Kiri: Hapus + Nama Produk -->
                                    <div class="flex items-center gap-3">
                                        <!-- Tombol Hapus -->
                                        <button type="button"
                                            onclick="hapusItem('{{ $transaksi->kode_transaksi }}','{{ $row->produk->kode_produk }}')"
                                            class="text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6
                                                    m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z" />
                                            </svg>
                                        </button>

                                        <!-- Nama Produk -->
                                        <button type="button"
                                            onclick="showProductDetail2('{{ $row->produk->kode_produk }}')"
                                            class="text-sm font-semibold text-gray-800 hover:text-blue-600">
                                            {{ $row->produk->nama_produk }}
                                        </button>
                                    </div>

                                    <!-- Kanan: Harga -->
                                    <p class="text-sm text-gray-700 font-medium totalku"  data-index="{{ $myindex }}">
                                        Rp.{{ number_format($total_mobile ?? 0, 0, ',', '.') }}
                                    </p>
                                </div>



                                <div id="modal-detail2-{{ $row->produk->kode_produk }}"
                                    class="fixed inset-0 z-40 hidden overflow-y-auto bg-black/40 flex items-center justify-center">

                                    <div class="relative bg-white w-11/12 max-w-sm mx-auto rounded-lg shadow-lg p-5 z-10 space-y-3 animate-fadeIn">
                                        <!-- Header Modal -->
                                        <div class="flex justify-between items-center mb-3">
                                            <h3 class="text-lg font-bold">Detail Produk</h3>
                                            <button type="button"
                                                class="text-gray-500 hover:text-red-600 text-2xl leading-none"
                                                onclick="closeProductDetail2('{{ $row->produk->kode_produk }}')">
                                                &times;
                                            </button>
                                        </div>

                                        <!-- Tabel Detail -->
                                        <table class="w-full text-sm border border-gray-300">
                                            <tbody>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Kode</td>
                                                    <td class="border px-2 py-1">{{ $row->produk->kode_produk }}</td>
                                                    <input type="hidden" name="kode_produk[]"
                                                    value="{{ $row->produk->kode_produk }}">
                                                </tr>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Nama</td>
                                                    <td class="border px-2 py-1">{{ $row->produk->nama_produk }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Barang Keluar <span class="text-red-600">*</span></td>
                                                    <td class="border px-2 py-1">
                                                        <input type="number" name="barang_keluar[]"
                                                            class="form-input w-20 text-center border-gray-300 rounded-md barang-keluar-input-mobile"
                                                            value="{{ $barang_keluar_mobile }}"
                                                            data-harga="{{ $harga_mobile }}"
                                                            data-index="{{ $myindex }}"
                                                            min="0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Barang Terjual <span class="text-red-600">*</span></td>
                                                    <td class="border px-2 py-1">
                                                        <input type="number" name="barang_terjual[]"
                                                            class="form-input w-20 text-center border-gray-300 rounded-md barang-terjual-input-mobile"
                                                            value="{{ $barang_terjual_mobile }}"
                                                            data-index="{{ $myindex }}"
                                                            min="0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Barang Retur <span class="text-red-600">*</span></td>
                                                    <td class="border px-2 py-1">
                                                        <input type="number" name="barang_retur[]"
                                                            class="form-input w-20 text-center border-gray-300 rounded-md barang-retur-input-mobile"
                                                            value="{{ $barang_retur_mobile }}"
                                                            data-index="{{ $myindex }}"
                                                            min="0">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="border px-2 py-1 font-medium">Total</td>
                                                    <td class="border px-2 py-1">Rp.
                                                        <input type="text" name="harga[]"
                                                            value="{{ number_format($total_mobile, 0, ',', '.') }}"
                                                            class="form-input harga-input w-24 text-right border-gray-300 rounded-md total-harga-input-mobile"
                                                            data-index="{{ $myindex }}"
                                                            readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <small><span class="text-red-600">*</span> : Form yang bisa di isi</small>
                                        <!-- Tombol Tutup -->
                                        <button type="button"
                                            class="w-full mt-3 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700"
                                            onclick="closeProductDetail2('{{ $row->produk->kode_produk }}')">
                                            Simpan
                                        </button>
                                    </div>
                                </div>

                                <!-- JS Modal Handler -->
                                <script>
                                    function showProductDetail2(kode) {
                                        const modal = document.getElementById('modal-detail2-' + kode);
                                        modal.classList.remove('hidden');
                                        modal.classList.add('flex');
                                    }

                                    function closeProductDetail2(kode) {
                                        const modal = document.getElementById('modal-detail2-' + kode);
                                        modal.classList.remove('flex');
                                        modal.classList.add('hidden');
                                    }
                                </script>
                            @empty
                                <!-- Tidak Ada Produk -->
                                <div class="border border-gray-300 px-3 py-2 text-center text-gray-500 rounded-md bg-white">
                                    Belum ada produk yang ditawarkan.
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <div class="md:hidden">

                        <table class="w-full mt-6 border-collapse border border-gray-300">
                            <!-- Ongkir -->
                            <tr>

                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">
                                    Ongkir
                                </td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="mr-1">Rp.</span>
                                        <input type="text" name="ongkir" id="ongkir-input-mobile" class="form-input w-24 text-right border-gray-300 rounded-md" value="{{ $transaksi->ongkir ?? '0' }}">
                                    </div>
                                </td>
                            </tr>
                            <!-- Total -->
                            <tr>

                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Total</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="mr-1">Rp.</span>
                                        <input type="text" name="total" id="total-input-mobile" class="form-input w-24 text-right border-gray-300 rounded-md" value="{{ $transaksi->total ?? number_format($totalSemua, 0, ',', '.') }}" readonly>
                                    </div>
                                </td>
                            </tr>
                            <!-- Discount -->
                            <tr>

                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Discount</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <div class="flex items-center justify-center">
                                        <span class="mr-1">Rp.</span>
                                        <input type="text" name="discount" id="discount-input-mobile" class="form-input w-24 text-right border-gray-300 rounded-md" value="{{ number_format($transaksi->diskon, 0, ',', '.') }}">
                                    </div>
                                </td>
                            </tr>
                            <!-- Grand Total -->
                            <tr>

                                <td class="border border-gray-300 px-3 py-2 font-bold text-right">Grand Total</td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-bold">
                                    <div class="flex items-center justify-center">
                                        <span class="mr-1">Rp.</span>
                                        <input type="text" name="grand_total" id="grand-total-input-mobile" class="form-input w-24 text-right border-gray-300 rounded-md font-bold" value="{{ $transaksi->total ?? number_format($totalSemua_mobile, 0, ',', '.') }}" readonly>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Status Bayar</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <select name="status_bayar" id="status-bayar-input-mobile" class="form-input w-full text-center border-gray-300 rounded-md">
                                        <option value="Belum Bayar" {{ old('status_bayar', $transaksi->status_bayar ?? '') == 'Belum Bayar' ? 'selected' : '' }}>
                                            Belum Bayar</option>
                                        <option value="Sudah Bayar" {{ old('status_bayar', $transaksi->status_bayar ?? '') == 'Sudah Bayar' ? 'selected' : '' }}>
                                            Sudah Bayar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-3 py-2 font-semibold text-right">Tanggal Bayar</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <input type="date" name="tanggal_bayar" id="tanggal-bayar-input-mobile" class="form-input w-full text-center border-gray-300 rounded-md" value="{{ old('tanggal_bayar', $transaksi->tanggal_pembayaran ?? '') }}">
                                </td>
                            </tr>
                        </table>

                        <script>
                            // Format angka ke format Rupiah (mis: 15000 → 15.000)
                            function formatRupiah(angka) {
                                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }

                            // Ubah format Rupiah ke angka (mis: "15.000" → 15000)
                            function parseRupiah(str) {
                                return parseInt((str || '0').replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
                            }

                            // Update semua total (subtotal per produk, total, grand total)
                            function updateTotals2() {
                                let total = 0;

                                document.querySelectorAll('.barang-keluar-input-mobile').forEach(function(input) {
                                    const index = input.dataset.index;
                                    const harga = parseInt(input.dataset.harga) || 0;
                                    const jumlahKeluar = parseInt(input.value) || 0;

                                    // Ambil input terjual & retur berdasarkan index
                                    const barangTerjualInput = document.querySelector(`.barang-terjual-input-mobile[data-index="${index}"]`);
                                    const barangReturInput   = document.querySelector(`.barang-retur-input-mobile[data-index="${index}"]`);
                                    const totalInput         = document.querySelector(`.total-harga-input-mobile[data-index="${index}"]`);
                                    const totalInputku         = document.querySelector(`.totalku[data-index="${index}"]`);

                                    let barangTerjual = parseInt(barangTerjualInput?.value) || 0;

                                    // Batasi terjual tidak lebih dari keluar
                                    if (barangTerjual > jumlahKeluar) {
                                        barangTerjual = jumlahKeluar;
                                        barangTerjualInput.value = barangTerjual;
                                    }

                                    // Hitung total per produk
                                    const totalRow = (barangTerjual > 0 ? barangTerjual : jumlahKeluar) * harga;
                                    if (totalInput) totalInput.value = formatRupiah(totalRow);

                                    if (totalInputku) {
                                        totalInputku.textContent = "Rp. " + formatRupiah(totalRow);
                                    }

                                    total += totalRow;
                                });

                                // Tampilkan total semua produk
                                const totalInput2 = document.getElementById('total-input-mobile');
                                if (totalInput2) totalInput2.value = formatRupiah(total);

                                // Tambahkan ongkir & diskon
                                const ongkir   = parseRupiah(document.getElementById('ongkir-input-mobile')?.value);
                                const discount = parseRupiah(document.getElementById('discount-input-mobile')?.value);
                                const grandTotal = total + ongkir - discount;

                                const grandTotalInput2 = document.getElementById('grand-total-input-mobile');
                                if (grandTotalInput2) grandTotalInput2.value = formatRupiah(grandTotal);
                            }

                            document.addEventListener('DOMContentLoaded', function() {
                                // Format ulang total awal di semua input .total-harga-input-mobile
                                document.querySelectorAll('.total-harga-input-mobile').forEach(function(input) {
                                    input.value = formatRupiah(parseRupiah(input.value));
                                });

                                // Event input: barang keluar
                                document.querySelectorAll('.barang-keluar-input-mobile').forEach(function(input) {
                                    input.addEventListener('input', updateTotals2);
                                });

                                // Event input: barang terjual
                                document.querySelectorAll('.barang-terjual-input-mobile').forEach(function(input) {
                                    input.addEventListener('input', function () {
                                        const index = this.dataset.index;

                                        const barangKeluarInput = document.querySelector(`.barang-keluar-input-mobile[data-index="${index}"]`);
                                        const barangReturInput  = document.querySelector(`.barang-retur-input-mobile[data-index="${index}"]`);

                                        const barangKeluar = parseInt(barangKeluarInput?.value) || 0;
                                        let barangTerjual = parseInt(this.value) || 0;

                                        // Batasi terjual tidak melebihi keluar
                                        if (barangTerjual > barangKeluar) {
                                            barangTerjual = barangKeluar;
                                            this.value = barangTerjual;
                                        }

                                        // Update barang retur secara otomatis
                                        if (barangReturInput) {
                                            barangReturInput.value = barangKeluar - barangTerjual;
                                        }

                                        updateTotals2();
                                    });
                                });

                                // Event input: ongkir
                                const ongkirInput2 = document.getElementById('ongkir-input-mobile');
                                if (ongkirInput2) {
                                    ongkirInput2.addEventListener('input', function() {
                                        this.value = formatRupiah(parseRupiah(this.value));
                                        updateTotals2();
                                    });
                                }

                                // Event input: diskon
                                const discountInput2 = document.getElementById('discount-input-mobile');
                                if (discountInput2) {
                                    discountInput2.addEventListener('input', function() {
                                        this.value = formatRupiah(parseRupiah(this.value));
                                        updateTotals2();
                                    });
                                }

                                // Jalankan saat halaman dimuat pertama kali
                                updateTotals2();
                            });
                        </script>

                    </div>
                 </form>

        </div>
        <!-- Tombol Simpan -->
        <div class="md:hidden mt-6 flex justify-end">
            <button type="button"
                onclick="document.getElementById('form-transaksi-mobile').submit();"
                class="w-full inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-full shadow transition duration-150">
                <i class="fas fa-save"></i> Simpan Data
            </button>
        </div>

        <!-- Dropdown Aksi -->
        <div class="md:hidden mt-4">

            <select id="action-selector"
                class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 bg-white">
                <option selected disabled>-- Cetak Nota --</option>

                @php
                    $bolehKonsinyasi = $product->where('barang_terjual', '>', 0)->count() > 0 ||
                                        $product->where('barang_keluar', '>', 0)->count() > 0;
                    $bolehInvoice = $product->where('barang_terjual', '>', 0)->count() > 0;
                    $bolehKwitansi = $transaksi->status_bayar === 'Sudah Bayar';
                @endphp

                <option value="konsinyasi" @if(!$bolehKonsinyasi) disabled @endif>📝 Buat Nota Konsinyasi</option>
                <option value="invoice" @if(!$bolehInvoice) disabled @endif>📄 Buat Invoice</option>
                <option value="kwitansi" @if(!$bolehKwitansi) disabled @endif>🧾 Buat Kwitansi</option>
            </select>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const select = document.getElementById('action-selector');
                    const kode = @json($transaksi->kode_transaksi);

                    select?.addEventListener('change', function () {
                        const value = this.value;
                        let url = '';

                        switch (value) {
                            case 'konsinyasi':
                                url = `{{ route('transaksi.konsinyasi', ['id' => '__KODE__', 'type' => 'konsinyasi']) }}`.replace('__KODE__', kode);
                                break;
                            case 'invoice':
                                url = `{{ route('transaksi.kwitansi', ['id' => '__KODE__', 'type' => 'invoice']) }}`.replace('__KODE__', kode);
                                break;
                            case 'kwitansi':
                                url = `{{ route('transaksi.invoce', ['id' => '__KODE__', 'type' => 'kwitansi']) }}`.replace('__KODE__', kode);
                                break;
                        }

                        if (url) {
                            window.open(url, '_blank');
                        }

                        this.selectedIndex = 0;
                    });
                });
            </script>

        </div>

        <div class="flex flex-wrap gap-3 mt-6 justify-between ">

            <button type="button"
                onclick="document.getElementById('form-transaksi').submit();"
                class="hidden md:block inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150">
                Simpan Data
            </button>


            <div class="flex flex-wrap gap-3 hidden md:block">
                <a href="{{ route('transaksi.konsinyasi', ['id' => $transaksi->kode_transaksi, 'type' => 'konsinyasi']) }}"
                    target="_BLANK"
                    class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150"
                    id="btn-konsinyasi" disabled>
                    Buat Nota Konsinyasi
                </a>
                <a href="{{ route('transaksi.kwitansi', ['id' => $transaksi->kode_transaksi, 'type' => 'invoice']) }}"
                    target="_BLANK"
                    class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150"
                    id="btn-invoice"
                    @php
                    $adaTerjual = false;
                    foreach($penawaran as $row) {
                        if(($row->barang_terjual ?? 0) > 0) { $adaTerjual = true; break; }
                    } @endphp
                        @if (!$adaTerjual) disabled style="opacity:0.5;pointer-events:none;" @endif>
                        Buat Invoice
                </a>
                <a href="{{ route('transaksi.invoce', ['id' => $transaksi->kode_transaksi, 'type' => 'kwitansi']) }}"
                    target="_BLANK"
                    class="inline-flex items-center px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150"
                    id="btn-kwitansi2"
                    @if ($transaksi->status_bayar === 'Belum Bayar') disabled style="opacity:0.5; pointer-events:none;" @endif>
                    Buat Kwitansi
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function updateButtons() {
                    const status = document.getElementById('status-bayar-input')?.value; // Status bayar input
                    const tanggal = document.getElementById('tanggal-bayar-input')?.value; // Tanggal bayar input
                    const btnKwitansi = document.getElementById('btn-kwitansi'); // Tombol Kwitansi
                    const btnKwitansi2 = document.getElementById('btn-kwitansi2'); // Tombol Kwitansi2
                    const btnKonsinyasi = document.getElementById('btn-konsinyasi'); // Tombol Konsinyasi

                    // Tombol Kwitansi dan Kwitansi2
                    if (!status || !tanggal) {
                        // Jika status atau tanggal belum ada, disable kedua tombol

                        btnKwitansi2.setAttribute('disabled', true);
                        btnKwitansi2.style.opacity = 0.5;
                        btnKwitansi2.style.pointerEvents = 'none';
                    } else if (status === 'Sudah Bayar') {


                        btnKwitansi2.removeAttribute('disabled');
                        btnKwitansi2.style.opacity = 1;
                        btnKwitansi2.style.pointerEvents = '';
                    } else {


                        btnKwitansi2.setAttribute('disabled', true);
                        btnKwitansi2.style.opacity = 0.5;
                        btnKwitansi2.style.pointerEvents = 'none';
                    }

                    // Tombol Konsinyasi (aktif jika ada barang terjual yang diisi)
                    let adaTerjual = false;
                    document.querySelectorAll('.barang-terjual-input').forEach(function(input) {
                        if (parseInt(input.value) > 0) adaTerjual = true; // Jika ada barang yang terjual
                    });

                    if (adaTerjual) {
                        btnKonsinyasi.removeAttribute('disabled'); // Menghapus disabled pada tombol Konsinyasi
                        btnKonsinyasi.style.opacity = 1; // Mengatur opacity ke normal
                        btnKonsinyasi.style.pointerEvents = ''; // Mengizinkan interaksi
                    } else {
                        btnKonsinyasi.setAttribute('disabled', true); // Menonaktifkan tombol Konsinyasi
                        btnKonsinyasi.style.opacity = 0.5; // Mengatur opacity agar terlihat dinonaktifkan
                        btnKonsinyasi.style.pointerEvents = 'none'; // Menonaktifkan pointer events
                    }


                    // Tombol Konsinyasi (aktif jika ada barang keluar yang diisi)
                    const barangKeluarInput = document.querySelectorAll('.barang-keluar-input');
                    let barangKeluarTerisi = false;

                    // Pengecekan jika ada barang keluar yang diisi
                    barangKeluarInput.forEach(function(input) {
                        if (parseInt(input.value) > 0) barangKeluarTerisi =
                            true; // Jika ada barang keluar terisi
                    });

                    if (barangKeluarTerisi) {
                        btnKonsinyasi.removeAttribute('disabled'); // Menghapus disabled pada tombol Konsinyasi
                        btnKonsinyasi.style.opacity = 1; // Mengatur opacity ke normal
                        btnKonsinyasi.style.pointerEvents = ''; // Mengizinkan interaksi
                    } else {
                        btnKonsinyasi.setAttribute('disabled', true); // Menonaktifkan tombol Konsinyasi
                        btnKonsinyasi.style.opacity = 0.5; // Mengatur opacity agar terlihat dinonaktifkan
                        btnKonsinyasi.style.pointerEvents = 'none'; // Menonaktifkan pointer events
                    }

                    // Tombol Invoice (hanya aktif jika ada barang terjual)
                    let adaBarangTerjual = false;
                    document.querySelectorAll('.barang-terjual-input').forEach(function(input) {
                        if (parseInt(input.value) > 0) adaBarangTerjual = true;
                    });
                    const btnInvoice = document.getElementById('btn-invoice');
                    if (!adaBarangTerjual) {
                        btnInvoice.setAttribute('disabled', true);
                        btnInvoice.style.opacity = 0.5;
                        btnInvoice.style.pointerEvents = 'none';
                    } else {
                        btnInvoice.removeAttribute('disabled');
                        btnInvoice.style.opacity = 1;
                        btnInvoice.style.pointerEvents = '';
                    }
                }

                // Event listener ketika status bayar berubah
                document.getElementById('status-bayar-input')?.addEventListener('change', updateButtons);

                // Event listener untuk input tanggal bayar dan barang terjual
                document.getElementById('tanggal-bayar-input')?.addEventListener('input', updateButtons);
                document.querySelectorAll('.barang-terjual-input').forEach(function(input) {
                    input.addEventListener('input',
                        updateButtons); // Pengecekan setiap kali input barang terjual berubah
                });

                // Inisialisasi tombol saat halaman dimuat
                updateButtons();
            });
        </script>



    <script>
        function updateKodeTransaksi(kodeTransaksi, kodeMitra) {
            const selectedCheckboxes = document.querySelectorAll('input[name="kode_produk[]"]:checked');
            const kodeProduks = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (kodeProduks.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops!',
                    text: 'Pilih minimal 1 produk terlebih dahulu.',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded-lg px-4 py-2'
                    },
                    buttonsStyling: false
                });
                return;
            }

            fetch('/update-penawaran', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kode_transaksi: kodeTransaksi,
                        kode_produk: kodeProduks,
                        kode_mitra: kodeMitra
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.produk_sudah_ada && data.produk_sudah_ada.length > 0) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Sebagian produk sudah ada',
                                html: 'Produk berikut sudah pernah ditambahkan ke transaksi:<br><b>' + data
                                    .produk_sudah_ada.join(', ') + '</b>',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'bg-green-500 hover:bg-blue-600 text-white font-semibold rounded-lg px-4 py-2'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Berhasil menambahkan produk ke transaksi!',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg px-4 py-2'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Gagal menambahkan produk.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2'
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengirim data.',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2'
                        },
                        buttonsStyling: false
                    });
                });
        }
    </script>
    <script>
        function hapusItem(kodeTransaksi, kodeProduk) {
            Swal.fire({
                icon: 'warning',
                title: 'Yakin ingin hapus?',
                text: 'Data produk ini akan dihapus dari transaksi.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg px-4 py-2 ml-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/hapus-produk-transaksi', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content')
                            },
                            body: JSON.stringify({
                                kode_transaksi: kodeTransaksi,
                                kode_produk: kodeProduk
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Produk berhasil dihapus dari transaksi.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg px-4 py-2'
                                    },
                                    buttonsStyling: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Gagal menghapus produk.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2'
                                    },
                                    buttonsStyling: false
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menghapus data.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-semibold rounded-lg px-4 py-2'
                                },
                                buttonsStyling: false
                            });
                        });
                }
            });
        }
    </script>
    {{-- <script>
        function updateKodeTransaksi(kodeTransaksi, kodeMitra) {
            const selectedCheckboxes = document.querySelectorAll('input[name="kode_produk[]"]:checked');
            const kodeProduks = Array.from(selectedCheckboxes).map(cb => cb.value);

            if (kodeProduks.length === 0) {
                alert('Pilih minimal 1 produk terlebih dahulu.');
                return;
            }

            // Kirim via AJAX ke route Laravel
            fetch('/update-penawaran', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        kode_transaksi: kodeTransaksi,
                        kode_produk: kodeProduks,
                        kode_mitra: kodeMitra,
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Berhasil menambahkan produk ke transaksi!');
                        location.reload();
                    } else {
                        alert('Gagal menambahkan produk.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script> --}}
@endsection
