@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('container')
<form action="{{ route('manajemenStok.create') }}" method="POST">
    @csrf
@if ($errors->any())
<div class="flex items-start rounded bg-lightyellow/50 dark:bg-lightyellow p-3 text-black/80 dark:text-black mb-5">
    <!-- Icon -->
    <svg class="w-5 h-5 mr-2" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" 
            d="M11.4012 27.0849C11.4012 27.0849 10.9664 26.9028 9.6139 26.8843C9.6139 26.8843 8.4575 26.8685 7.88867 26.7645C7.88867 26.7645 6.77082 26.56 6.10539 25.8946C6.10539 25.8946 5.43594 25.2252 5.22844 24.0882C5.22844 24.0882 5.12294 23.5102 5.10465 22.3366C5.10465 22.3366 5.08389 21.0046 4.91418 20.5965C4.91418 20.5965 4.74093 20.18 3.79698 19.1924C3.79698 19.1924 2.98525 18.3431 2.6547 17.8655C2.6547 17.8655 2 16.9195 2 16C2 16 2 15.0846 2.64417 14.1522C2.64417 14.1522 2.96978 13.6809 3.77243 12.8434C3.77243 12.8434 4.7293 11.8449 4.91512 11.4012C4.91512 11.4012 5.09721 10.9664 5.1157 9.6139C5.1157 9.6139 5.13151 8.4575 5.23553 7.88867C5.23553 7.88867 5.43996 6.77082 6.10539 6.10539C6.10539 6.10539 6.77484 5.43594 7.91181 5.22844C7.91181 5.22844 8.48983 5.12294 9.66342 5.10465C9.66342 5.10465 10.9954 5.08389 11.4035 4.91418C11.4035 4.91418 11.82 4.74093 12.8076 3.79698C12.8076 3.79698 13.6569 2.98525 14.1345 2.6547C14.1345 2.6547 15.0805 2 16 2C16 2 16.9154 2 17.8478 2.64417C17.8478 2.64417 18.3191 2.96978 19.1566 3.77243C19.1566 3.77243 20.1551 4.7293 20.5988 4.91512C20.5988 4.91512 21.0336 5.09721 22.3861 5.1157C22.3861 5.1157 23.5425 5.13151 24.1113 5.23553C24.1113 5.23553 25.2292 5.43996 25.8946 6.10539C25.8946 6.10539 26.5641 6.77484 26.7716 7.91181C26.7716 7.91181 26.8771 8.48985 26.8953 9.66342C26.8953 9.66342 26.9161 10.9954 27.0858 11.4035C27.0858 11.4035 27.2591 11.82 28.203 12.8076C28.203 12.8076 29.0148 13.6569 29.3453 14.1345C29.3453 14.1345 30 15.0805 30 16C30 16 30 16.9154 29.3558 17.8478C29.3558 17.8478 29.0302 18.3191 28.2276 19.1566C28.2276 19.1566 27.2707 20.1551 27.0849 20.5988C27.0849 20.5988 26.9028 21.0336 26.8843 22.3861C26.8843 22.3861 26.8685 23.5425 26.7645 24.1113C26.7645 24.1113 26.56 25.2292 25.8946 25.8946C25.8946 25.8946 25.2252 26.5641 24.0882 26.7716C24.0882 26.7716 23.5102 26.8771 22.3366 26.8953C22.3366 26.8953 21.0046 26.9161 20.5965 27.0858C20.5965 27.0858 20.18 27.2591 19.1924 28.203C19.1924 28.203 18.3431 29.0148 17.8655 29.3453C17.8655 29.3453 16.9195 30 16 30C16 30 15.0846 30 14.1522 29.3558C14.1522 29.3558 13.6809 29.0302 12.8434 28.2276C12.8434 28.2276 11.8449 27.2707 11.4012 27.0849ZM12.1738 25.2401C12.1738 25.2401 12.9603 25.5695 14.2272 26.7836C14.2272 26.7836 15.4965 28 16 28C16 28 16.5103 28 17.8105 26.7572C17.8105 26.7572 19.0676 25.5556 19.8285 25.2392C19.8285 25.2392 20.5903 24.9223 22.3054 24.8956C22.3054 24.8956 24.0931 24.8677 24.4804 24.4804C24.4804 24.4804 24.8607 24.1001 24.8845 22.3588C24.8845 22.3588 24.9083 20.6186 25.2401 19.8262C25.2401 19.8262 25.5695 19.0397 26.7836 17.7728C26.7836 17.7728 28 16.5035 28 16C28 16 28 15.4897 26.7572 14.1895C26.7572 14.1895 25.5556 12.9324 25.2392 12.1715C25.2392 12.1715 24.9223 11.4097 24.8956 9.69459C24.8956 9.69459 24.8677 7.90694 24.4804 7.51961C24.4804 7.51961 24.1001 7.13932 22.3588 7.11551C22.3588 7.11551 20.6186 7.09172 19.8262 6.75988C19.8262 6.75988 19.0397 6.43046 17.7728 5.2164C17.7728 5.2164 16.5035 4 16 4C16 4 15.4897 4 14.1895 5.24278C14.1895 5.24278 12.9324 6.44437 12.1715 6.76082C12.1715 6.76082 11.4097 7.07767 9.69459 7.10441C9.69459 7.10441 7.90694 7.13227 7.51961 7.51961C7.51961 7.51961 7.13932 7.8999 7.11551 9.64124C7.11551 9.64124 7.09172 11.3814 6.75988 12.1738C6.75988 12.1738 6.43047 12.9603 5.2164 14.2272C5.2164 14.2272 4 15.4965 4 16C4 16 4 16.5103 5.24278 17.8105C5.24278 17.8105 6.44437 19.0676 6.76082 19.8285C6.76082 19.8285 7.07767 20.5903 7.10441 22.3054C7.10441 22.3054 7.13227 24.0931 7.51961 24.4804C7.51961 24.4804 7.8999 24.8607 9.64124 24.8845C9.64124 24.8845 11.3814 24.9083 12.1738 25.2401Z" 
            fill="currentColor"></path>
        <path d="M15 10V17C15 17.5523 15.4477 18 16 18C16.5523 18 17 17.5523 17 17V10C17 9.44772 16.5523 9 16 9C15.4477 9 15 9.44772 15 10Z" fill="currentColor"></path>
        <path d="M17.5 21.5C17.5 22.3284 16.8284 23 16 23C15.1716 23 14.5 22.3284 14.5 21.5C14.5 20.6716 15.1716 20 16 20C16.8284 20 17.5 20.6716 17.5 21.5Z" fill="currentColor"></path>
    </svg>

    <!-- Pesan Error -->
    <div class="flex-1">
        <strong class="font-semibold">Terjadi kesalahan:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Tombol Tutup -->
    <button type="button" class="ml-auto hover:opacity-50 rotate-0 hover:rotate-180 transition-all duration-300">
        <svg class="w-5 h-5" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
        </svg>
    </button>
</div>
@endif


    <div class="space-y-4 text-xs font-sans">
        <h2 class="text-lg font-semibold mb-5">Management Stok Produk</h2>
        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col lg:flex-row items-start justify-between gap-6 mb-4 border border-black/10 dark:border-white/10 p-5 rounded-md">

            {{-- Kiri: Form Transaksi --}}
            <div class="w-full lg:w-1/2 space-y-3">
                <div class="mb-3">
                    <label class="block mb-1 ">No Transaksi</label>
                    <input type="text" value="{{ $transaksi->no_transaksi }}" name="no_transaksi" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block mb-1 ">Tanggal</label>
                    <input type="date" value="2025-09-19" name="tanggal" value="{{ $transaksi->tanggal }}" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
            </div>
            <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
            <input type="text" name="jenis" value="in" hidden>

            {{-- Kanan: Search Produk --}}
            <div class="w-full lg:w-1/2"></div>
        </div>
        <div x-data="produkSearch()" class="mb-5">
            <label class="block mb-1 ">Cari Produk</label>
            <input type="text" x-model="keyword" @keydown.enter.prevent="search()" placeholder="Cari Produk..." class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                <button 
                type="button" 
                @click="search()" 
                class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Cari Produk
            </button>
            {{-- Modal --}}
            <div x-show="open" x-transition @click.self="open = false" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 ">
                <div class="bg-white dark:bg-black rounded-xl shadow-2xl p-5 w-full max-w-lg dark:border-white/10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Hasil Pencarian</h2>
                        <button type="button" @click="open = false" class="text-gray-500 hover:text-black">&times;</button>
                    </div>

                    <input type="text" x-model="keyword" @keydown.enter.prevent="search()" placeholder="Cari Produk..." class="mb-3 form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">

                    <template x-if="filtered.length > 0">
                        <div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg">
                            <table class="w-full border dark:border-white/10 text-sm min-w-[500px]">
                                <thead class="bg-gray-100  dark:bg-white/5 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Kode</th>
                                        <th class="px-3 py-2 text-left">Nama Produk</th>
                                        <th class="px-3 py-2 text-right">Stok</th>
                                        <th class="px-3 py-2 text-right">Harga Jual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <template x-for="item in filtered" :key="item.kode_produk">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer" @click="pilihProduk(item)">
                                            <td class="px-3 py-2" x-text="item.kode_produk"></td>
                                            <td class="px-3 py-2" x-text="item.nama_produk"></td>
                                            <td class="px-3 py-2 text-right" x-text="item.stok"></td>
                                            <td class="px-3 py-2 text-right" x-text="item.harga_jual"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>

                    <template x-if="filtered.length === 0">
                        <p class="text-center text-gray-400">Tidak ada produk ditemukan</p>
                    </template>
                </div>
            </div>
        </div>
        {{-- ================= TABEL ITEM ================= --}}
        <div>
            <!-- Mobile Cards -->
            <div class="block md:hidden space-y-3 border p-2 rounded dark:border-white/10 bg-lightwhite dark:bg-white/5">
                <div class="mb-3">
                    <span> Daftar Item </span>
                </div>

                <div id="itemCards">
                    <tr class="empty-row">
                        <td colspan="9" class="border  border-gray-300 dark:border-white/10">
                            <div class="h-[300px] flex items-center justify-center" style="height:300px;">
                                <span class="text-gray-400">Belum ada item</span>
                            </div>
                        </td>
                    </tr>
                </div>
            </div>

            <!-- Desktop Table -->
            <div class="hidden md:block bg-white dark:bg-black">
                <div class="border dark:border-white/10 rounded-lg overflow-hidden">

                    <!-- Scroll wrapper fix height -->
                    <div class="overflow-y-auto" style="height: 300px;">
                        <table class="w-full min-w-[800px] border-collapse" id="itemTable">
                            <thead class="sticky top-0 bg-gray-100 dark:bg-black z-10">
                                <tr class="text-center">
                                   <th class="border dark:border-white/10 px-2 py-1 w-10">No</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-40">Kode Item</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-40 text-center">Nama Produk</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-28 text-center">Stok</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-24 text-center">Satuan</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-32 text-center">Harga</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-32 text-center">Total</th>
                                    <th class="border dark:border-white/10 px-2 py-1 w-14 text-center">Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                   
                              @foreach ($transaksi->items as $i => $item)
                                <tr>
                                    <td class="border dark:border-white/10 px-2 py-1 nomor">{{ $i + 1 }}</td>
                                    <td hidden><input type="text" class="kode_item2" value="{{ $item->id }}" ></td>
                                    <td class="border dark:border-white/10 px-2 py-1">
                                        <input type="text" name="items[{{ $i }}][kode_produk]" value="{{ $item->kode_produk }}"
                                            class="kode_produk border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0" readonly>
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1">
                                        <input type="text" name="items[{{ $i }}][nama_produk]" value="{{ $item->nama_produk }}"
                                            class="nama_produk w-full border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0" readonly>
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1">
                                        <input type="number" name="items[{{ $i }}][jumlah]" value="{{ $item->jumlah }}"
                                            class="jumlah text-center w-full border-0 dark:bg-transparent dark:text-white">
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1">
                                        <select name="items[{{ $i }}][satuan_id]" class="form-select" required>
                                            <option value="">-- Pilih Satuan --</option>
                                            @foreach ($satuans as $satuan)
                                                <option value="{{ $satuan->id }}" {{ $satuan->id == $item->satuan ? 'selected' : '' }}>
                                                    {{ $satuan->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1">
                                        <input type="number" 
                                            name="items[{{ $i }}][harga]" 
                                            value="{{ number_format($item->harga, 0, '', '') }}" 
                                            class="harga w-full border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0">

                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1" hidden>
                                        <input type="number" name="items[{{ $i }}][pot]" value="{{ $item->pot }}"
                                            class="pot w-full text-center border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0">
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1 text-right total">
                                        {{ number_format($item->total, 2) }}
                                    </td>

                                    <td class="border dark:border-white/10 px-2 py-1 text-center">
                                        <a href="{{ route('manajemenStok.delete',$item->id) }}" 
                                        class="hapus p-1 rounded text-red-600"
                                        data-id="{{ $item->id }}">
                                        Hapus
                                        </a>
                                    </td>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            document.querySelectorAll(".hapus").forEach(function(button) {
                                                button.addEventListener("click", function(e) {
                                                    e.preventDefault();
                                                    let url = this.getAttribute("href");

                                                    Swal.fire({
                                                    title: "Yakin hapus data ini?",
                                                    text: "Data stok akan berkurang sesuai jumlah!",
                                                    icon: "warning",
                                                    showCancelButton: true,
                                                    confirmButtonText: "Ya, hapus!",
                                                    cancelButtonText: "Batal",
                                                    customClass: {
                                                        confirmButton: "bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500",
                                                        cancelButton: "bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400"
                                                    },
                                                    buttonsStyling: false // supaya class custom di atas kepake
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = url;
                                                    }
                                                });

                                                });
                                            });
                                        });
                                        </script>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

                <script>
                function parseAngka(str) {
                    return parseFloat(String(str).replace(/\D/g, '')) || 0;
                }
                function formatRupiah(angka) {
                    return angka.toLocaleString("id-ID", { minimumFractionDigits: 0 });
                }

                function hitungSemua() {
                    let subtotal = 0;
                    document.querySelectorAll("#itemTable tbody tr").forEach((row) => {
                        let jumlah = parseFloat(row.querySelector(".jumlah")?.value) || 0;
                        let harga  = parseFloat(row.querySelector(".harga")?.value) || 0;
                        let pot    = parseFloat(row.querySelector(".pot")?.value) || 0;

                        let total = (jumlah * harga) - pot;
                        row.querySelector(".total").textContent = formatRupiah(total);
                        subtotal += total;
                    });

                    // tampilkan subtotal
                    let subtotalEl = document.getElementById("subtotal");
                    if (subtotalEl) subtotalEl.value = formatRupiah(subtotal);

                    // ambil potongan global
                    let potongan = parseFloat(document.getElementById("potongan")?.value) || 0;

                    // ambil pajak global (%)
                    let pajak = parseFloat(document.getElementById("pajak")?.value) || 0;

                    // hitung total akhir
                    let grand = (subtotal - potongan) * (1 + pajak / 100);
                    let totalAkhirEl = document.getElementById("totalAkhir");
                    if (totalAkhirEl) totalAkhirEl.value = formatRupiah(grand);
                }

                // trigger setiap ada input berubah
                document.addEventListener("input", function(e) {
                    if (e.target.classList.contains("jumlah") || 
                        e.target.classList.contains("harga")  || 
                        e.target.classList.contains("pot")    || 
                        e.target.id === "potongan"            || 
                        e.target.id === "pajak") {
                        hitungSemua();
                    }
                });

                // hitung saat halaman pertama kali load
                document.addEventListener("DOMContentLoaded", hitungSemua);
                </script>


            {{-- ================= FOOTER ================= --}}
            <div class="flex flex-col lg:flex-row gap-6 mt-4">

                <!-- Kiri: Deskripsi & Tombol -->
                <div class="w-full lg:w-1/2 space-y-5">

                    <!-- Deskripsi Produk -->
                    <div class="bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-lg p-4">
                        <label class="block mb-1 text-sm text-black/60 dark:text-white/60 font-medium">
                            Catatan
                        </label>
                    <textarea name="deskripsi" placeholder="Masukan Catatan disini.." 
                        class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" 
                        rows="3" oninput="autoResize(this)">{{ $transaksi->deskripsi }}</textarea>

                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                            Simpan
                        </button>
                    </div>
                </div>

                <!-- Kanan: Ringkasan Total -->
                <div class="w-full lg:w-1/2 bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-lg p-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">

                        <label class="text-sm text-black/70 dark:text-white/70 sm:text-right">Sub Total :</label>
                        <input id="subtotal" type="text" readonly name="subtotal" class="form-input py-2 px-4 w-full text-right rounded-lg border border-black/10 dark:border-white/10 dark:bg-transparent text-black dark:text-white" value="0,00">

                        <label class="text-sm text-black/70 dark:text-white/70 sm:text-right">Potongan :</label>
                        <input id="potongan" type="text" name="potongan" value="{{ $transaksi->potongan }}" class="form-input py-2 px-4 w-full text-right rounded-lg border border-black/10 dark:border-white/10 dark:bg-transparent text-black dark:text-white" value="0">

                        <div hidden>
                            <label class="text-sm text-black/70 dark:text-white/70 sm:text-right">Pajak (%) :</label>
                            <input id="pajak" type="text" name="pajak" class="form-input py-2 px-4 w-full text-right rounded-lg border border-black/10 dark:border-white/10 dark:bg-transparent text-black dark:text-white" value="0">
                        </div>

                        <label class="font-semibold sm:text-right">Total Akhir :</label>
                        <input id="totalAkhir" type="text" readonly name="total_akhir" class="form-input py-2 px-4 w-full text-right font-semibold rounded-lg border border-black/10 dark:border-white/10 dark:bg-transparent text-black dark:text-white" value="0,00">
                    </div>
                </div>
            </div>


            {{-- Contoh Card Item (opsional) --}}
            <div id="cards" class="space-y-3 mt-6"></div>

        </div>


    {{-- ================= SCRIPT ================= --}}
<script>
    function produkSearch() {
        return {
            open: false,
            keyword: '',
            products: @json($produk),
            filtered: [],
            search() {
                const key = (this.keyword || '').toLowerCase();
                this.filtered = this.products.filter(p =>
                    p.nama_produk.toLowerCase().includes(key) ||
                    p.kode_produk.toLowerCase().includes(key)
                );
                this.open = true;
            },
            pilihProduk(item) {
                addItemToTable(item);
                this.open = false;
                this.keyword = '';
                this.filtered = [];
            }
        }
    }

    const tableBody = document.querySelector('#itemTable tbody');
    const itemCards = document.getElementById('itemCards');
    const subtotalEl = document.getElementById('subtotal');
    const potonganEl = document.getElementById('potongan');
    const pajakEl = document.getElementById('pajak');
    const grandTotalEl = document.getElementById('totalAkhir');
    const satuans = @json($satuans); // [{id:1,nama:'pcs'},...]

    // 🔹 helper format Rupiah
    function formatRupiah(angka) {
        angka = parseFloat(angka) || 0;
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    function hitungTotal() {
        let total = 0;
        tableBody.querySelectorAll('tr:not(.empty-row)').forEach((tr, i) => {
            tr.querySelector('.nomor').textContent = i + 1;
            const jumlah = parseFloat(tr.querySelector('.jumlah')?.value) || 0;
            const harga = parseFloat(tr.querySelector('.harga')?.value) || 0;
            const pot = parseFloat(tr.querySelector('.pot')?.value) || 0;
            const subtotal = jumlah * harga * (1 - pot / 100);
            tr.querySelector('.total').textContent = formatRupiah(subtotal);
            total += subtotal;
        });

        if (subtotalEl) subtotalEl.value = formatRupiah(total);
        const potongan = parseFloat(potonganEl?.value) || 0;
        const pajak = parseFloat(pajakEl?.value) || 0;
        if (grandTotalEl) {
            const grand = (total - potongan) * (1 + pajak / 100);
            grandTotalEl.value = formatRupiah(grand);
        }

        syncCards();
        checkEmptyRow();
    }

    function updateNomor() {
        tableBody.querySelectorAll('tr:not(.empty-row)').forEach((tr, i) => {
            tr.querySelector('.nomor').textContent = i + 1;
        });
    }

    // ✅ fungsi cek data kosong
    function checkEmptyRow() {
        if (tableBody.querySelectorAll('tr:not(.empty-row)').length === 0) {
            if (!tableBody.querySelector('.empty-row')) {
                const tr = document.createElement('tr');
                tr.className = "empty-row";
                tr.innerHTML = `
                    <td colspan="9" class="border-x border-b border-gray-300 dark:border-white/10">
                        <div class="h-[300px] flex items-center justify-center">
                            <span class="text-gray-400">Belum ada item</span>
                        </div>
                    </td>
                `;
                tableBody.appendChild(tr);
            }
        } else {
            const emptyRow = tableBody.querySelector('.empty-row');
            if (emptyRow) emptyRow.remove();
        }
    }

    function addItemToTable(item) {
        const kosong = [...tableBody.querySelectorAll('tr:not(.empty-row)')].find(tr => {
            const kodeInput = tr.querySelector('.kode_produk');
            return kodeInput && kodeInput.value.trim() === '';
        });

        if (kosong) {
            kosong.querySelector('.kode_produk').value = item.kode_produk;
            kosong.querySelector('.nama_produk').value = item.nama_produk;
            kosong.querySelector('.jumlah').value = 1;
            kosong.querySelector('.satuan').value = item.satuan_id ?? '';
            kosong.querySelector('.harga').value = item.harga ?? 0;
        } else {
            buatBaris(
                tableBody.querySelectorAll('tr:not(.empty-row)').length + 1,
                {
                    kode_produk: item.kode_produk,
                    nama_produk: item.nama_produk,
                    jumlah: 1,
                    satuan_id: item.satuan_id,
                    harga: item.harga ?? 0,
                }
            );
        }
        hitungTotal();
    }

    function buatBaris(no, data = {}) {
        const tr = document.createElement('tr');
        tr.className = "text-center ";

        // isi dropdown satuan
        let satuanOptions = ``;
        satuans.forEach(s => {
            const selected = (data.satuan_id == s.id) ? "selected" : "";
            satuanOptions += `<option value="${s.id}" ${selected}>${s.nama}</option>`;
        });

        tr.innerHTML = `
            <td class="border px-2 py-1 nomor">${no}</td>
            <td class="border px-2 py-1">
                <input type="text" name="items[${no - 1}][kode_produk]" 
                    value="${data.kode_produk ?? ''}" 
                    class="kode_produk border-0 w-full" readonly>
            </td>
            <td class="border px-2 py-1">
                <input type="text" name="items[${no - 1}][nama_produk]" 
                    value="${data.nama_produk ?? ''}" 
                    class="nama_produk border-0 w-full" readonly>
            </td>
            <td class="border px-2 py-1">
                <input type="number" name="items[${no - 1}][jumlah]" 
                    value="${data.jumlah ?? 1}" 
                    class="jumlah text-center border-0 w-full">
            </td>
            <td class="border px-2 py-1">
                <select name="items[${no - 1}][satuan_id]" 
                        class="satuan border-0 w-full ">
                    ${satuanOptions}
                </select>
            </td>
            <td class="border px-2 py-1">
                <input type="number" name="items[${no - 1}][harga]" 
                    value="${data.harga ?? 0}"
                    class="harga border-0 w-full">
            </td>
            <td class="border px-2 py-1 total text-center">0</td>
            <td class="border px-2 py-1 text-center">
                <button type="button" class="hapus p-1 rounded text-red-600" >Hapus</button>
            </td>
        `;

        tr.querySelectorAll('.jumlah, .harga').forEach(input => {
            input.addEventListener('input', hitungTotal);
        });

        tr.querySelector('.hapus').addEventListener('click', () => {
            tr.remove();
            updateNomor();
            hitungTotal();
        });

        tableBody.appendChild(tr);
        hitungTotal();
    }

  function syncCards() {
    if (!itemCards) return;

    // ✅ Simpan elemen aktif & posisi kursor (jika input sedang fokus)
    const active = document.activeElement;
    const activeIndex = active?.dataset?.index || null;
    const caretPos = active?.selectionStart || 0;

    itemCards.innerHTML = '';

    tableBody.querySelectorAll('tr:not(.empty-row)').forEach((tr, i) => {
        const kode = tr.querySelector('.kode_produk')?.value || '';
        const nama = tr.querySelector('.nama_produk')?.value || '';
        const kode_item = tr.querySelector('.kode_item2')?.value || ''; // id item database
        const jumlah = tr.querySelector('.jumlah')?.value || 0;
        const harga = parseFloat(tr.querySelector('.harga')?.value) || 0;
        const total = tr.querySelector('.total')?.textContent || 'Rp 0';

        // buat card mobile
        const card = document.createElement('div');
        card.className = "p-3 border rounded-lg dark:border-white/10 bg-white dark:bg-black space-y-2 mb-3";

        card.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="font-semibold text-base">${nama || '-'}</h3>
                    <p class="text-xs text-gray-500">Kode: ${kode}</p>
                </div>
                <button type="button" class="hapusMobile text-red-500 hover:text-red-700 transition-colors"
                        data-index="${i}" data-id="${kode_item}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                            stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Jumlah</label>
                    <input type="number" value="${jumlah}" 
                        class="jumlahMobile form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg"
                        data-index="${i}">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Harga</label>
                    <input type="text" value="${formatRupiah(harga)}" readonly
                        class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
                <div class="col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">Total</label>
                    <input type="text" value="${total}" readonly
                        class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
            </div>
        `;

        // event: ubah jumlah di mobile sync ke tabel
        card.querySelector('.jumlahMobile').addEventListener('input', (e) => {
            const idx = e.target.dataset.index;
            const trTarget = tableBody.querySelectorAll('tr:not(.empty-row)')[idx];
            if (trTarget) {
                trTarget.querySelector('.jumlah').value = e.target.value;
                hitungTotal(); // fungsi hitung total utama
            }
        });

        // event: hapus item di mobile
        card.querySelector('.hapusMobile').addEventListener('click', (e) => {
            const idx = e.currentTarget.dataset.index;
            const id = e.currentTarget.dataset.id;

            Swal.fire({
                title: "Yakin hapus data ini?",
                text: "Data stok akan berkurang sesuai jumlah!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg",
                    cancelButton: "bg-gray-300 hover:bg-gray-400 text-black font-medium px-4 py-2 rounded-lg"
                },
                buttonsStyling: false
            }).then(async (result) => {
                if (result.isConfirmed) {
                     if (id) {
                        window.location.href = `/management/stok/delete/${id}`;
                    } else {
                        hapusRow(idx);
                        card.remove();
                    }
                }
            });
        });

        itemCards.appendChild(card);
    });


    // ✅ Restore fokus dan posisi kursor setelah rebuild
    if (activeIndex !== null) {
        const restoredInput = itemCards.querySelector(`.jumlahMobile[data-index="${activeIndex}"]`);
        if (restoredInput) {
            restoredInput.focus();

            // restore posisi kursor (biar gak balik ke depan)
            if (caretPos !== null && restoredInput.setSelectionRange) {
                const length = restoredInput.value.length;
                const pos = Math.min(caretPos, length);
                restoredInput.setSelectionRange(pos, pos);
            }
        }
    }
}

    function hapusRow(index) {
        const tr = tableBody.querySelectorAll('tr:not(.empty-row)')[index];
        if (tr) {
            tr.remove();
            updateNomor();
            hitungTotal();
        }
    }

    potonganEl?.addEventListener('input', hitungTotal);
    pajakEl?.addEventListener('input', hitungTotal);

    document.addEventListener('DOMContentLoaded', () => {
        checkEmptyRow();
        hitungTotal();
    });
</script>

</form>
@endsection
