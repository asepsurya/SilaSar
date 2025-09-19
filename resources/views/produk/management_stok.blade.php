@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('container')
<form action="{{ route('manajemenStok.create') }}" method="POST">
    @csrf
    <div class="space-y-4 text-xs font-sans">
        <h2 class="text-lg font-semibold mb-5">Management Stok Produk</h2>

        {{-- ================= HEADER ================= --}}
        <div class="flex flex-col lg:flex-row items-start justify-between gap-6 mb-4 border border-black/10 dark:border-white/10 p-5 rounded-md">

            {{-- Kiri: Form Transaksi --}}
            <div class="w-full lg:w-1/2 space-y-3">
                <div class="mb-3">
                    <label class="block mb-1 ">No Transaksi</label>
                    <input type="text" value="0001/BL/LTM/0925"
                        class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
                <div class="mb-3">
                    <label class="block mb-1 ">Tanggal</label>
                    <input type="date" value="2025-09-19"
                        class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                </div>
            </div>

            {{-- Kanan: Search Produk --}}
            <div class="w-full lg:w-1/2" x-data="produkSearch()">
                <label class="block mb-1 ">Search Produk</label>
                <input type="text" x-model="keyword" @keydown.enter.prevent="search()" placeholder="Cari Produk..."
                    class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">

                {{-- Modal --}}
                <div x-show="open" x-transition @click.self="open = false"
                    class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-black rounded-xl shadow-2xl p-5 w-full max-w-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-black dark:text-white">Hasil Pencarian</h2>
                            <button @click="open = false" class="text-gray-500 hover:text-black">&times;</button>
                        </div>

                        <input type="text" x-model="keyword" @keydown.enter.prevent="search()"
                            placeholder="Cari Produk..."
                            class="mb-3 form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">

                        <template x-if="filtered.length > 0">
                            <div class="overflow-x-auto max-h-[500px] overflow-y-auto border rounded-lg">
                                <table class="w-full border text-sm min-w-[500px]">
                                    <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0">
                                        <tr>
                                            <th class="px-3 py-2 text-left">Kode</th>
                                            <th class="px-3 py-2 text-left">Nama Produk</th>
                                            <th class="px-3 py-2 text-right">Stok</th>
                                            <th class="px-3 py-2 text-right">Harga Jual</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        <template x-for="item in filtered" :key="item.kode_produk">
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer"
                                                @click="pilihProduk(item)">
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
        </div>

        {{-- ================= TABEL ITEM ================= --}}
        <div class=" overflow-hidden border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
            <div class="overflow-x-auto bg-white dark:bg-black">
                <table class="w-full min-w-[800px]" id="itemTable">
                    <thead class=" dark:border-none">
                        <tr class="text-center">
                            <th class="border   dark:border-white/10 px-2 py-1 w-8">No</th>
                            <th class="border  dark:border-white/10 px-2 py-1 " width="10%">Kode Item</th>
                            <th class="border  dark:border-white/10 px-2 py-1">Nama Produk</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-20">Stok</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-20">Satuan</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-29">Harga</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-20">Pot (%)</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-28">Total</th>
                            <th class="border  dark:border-white/10 px-2 py-1 w-10">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="flex flex-col lg:flex-row gap-6 mt-7">

            <!-- Kiri: Keterangan + Tombol -->
            <div class="w-full sm:w-1/2 space-y-4">
                <!-- Keterangan -->
                <div>
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Deskripsi Produk</label>
                        <textarea name="deskripsi" placeholder="Deskripsi Singkat Produk Anda"
                            class="form-input resize-none overflow-hidden w-full" rows="3" oninput="autoResize(this)"></textarea>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex flex-wrap gap-3 mt-7">
                    <a id="tambahBaris"
                        class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150">
                        Tambah Baris Baru
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow "
                       >
                        Simpan Transaksi
                    </a>
                    <a href="#" target="_BLANK"
                        class="inline-flex items-center px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow transition duration-150 opacity-50 pointer-events-none"
                        id="btn-kwitansi2">
                        Buat Kwitansi
                    </a>
                </div>
            </div>

            <!-- Kanan: Total -->
            <div class="lg:w-1/2 grid grid-cols-2 gap-3 items-center">
                <label class="text-right">Sub Total :</label>
                <input id="subtotal" type="text" readonly
                    class="form-input py-2.5 px-4 w-full text-right text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg"
                    value="0,00">

                <label class="text-right">Potongan :</label>
                <input id="potongan" type="text"
                    class="form-input py-2.5 px-4 w-full text-right text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg"
                    value="0">

                <label class="text-right">Pajak (%) :</label>
                <input id="pajak" type="text"
                    class="form-input py-2.5 px-4 w-full text-right text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg"
                    value="0">

                <label class="font-semibold text-right">Total Akhir :</label>
                <input id="totalAkhir" type="text" readonly
                    class="form-input py-2.5 px-4 w-full text-right text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg"
                    value="0,00">
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
        const subtotalEl = document.getElementById('subtotal');
        const potonganEl = document.getElementById('potongan');
        const pajakEl = document.getElementById('pajak');
        const grandTotalEl = document.getElementById('totalAkhir');
        const btnTambah = document.getElementById('tambahBaris');

        function hitungTotal() {
            let total = 0;
            tableBody.querySelectorAll('tr').forEach((tr, i) => {
                tr.querySelector('.nomor').textContent = i + 1;
                const jumlah = parseFloat(tr.querySelector('.jumlah')?.value) || 0;
                const harga = parseFloat(tr.querySelector('.harga')?.value) || 0;
                const pot = parseFloat(tr.querySelector('.pot')?.value) || 0;
                const subtotal = jumlah * harga * (1 - pot / 100);
                tr.querySelector('.total').textContent = subtotal.toFixed(2);
                total += subtotal;
            });
            subtotalEl.value = total.toFixed(2);
            const potongan = parseFloat(potonganEl.value) || 0;
            const pajak = parseFloat(pajakEl.value) || 0;
            grandTotalEl.value = ((total - potongan) * (1 + pajak / 100)).toFixed(2);
        }

        function updateNomor() {
            tableBody.querySelectorAll('tr').forEach((tr, i) => {
                tr.querySelector('.nomor').textContent = i + 1;
            });
        }

        function buatBaris(no, data = {}) {
            const tr = document.createElement('tr');
            tr.className = "text-center";
            tr.innerHTML = `
                <td class="border dark:border-white/10 px-2 py-1 nomor">${no}</td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="text" value="${data.kode_produk ?? ''}" class=" border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0"></td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="text" value="${data.nama_produk ?? ''}" class="w-full border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0" readonly></td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="number" value="${data.jumlah ?? 0}" class="jumlah text-center w-full border-0 dark:bg-transparent dark:text-white"></td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="text" value="${data.satuan ?? ''}" class="w-full text-center border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0"></td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="number" value="${data.harga_jual ?? 0}" class="harga  w-full border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0"></td>
                <td class="border dark:border-white/10 px-2 py-1"><input type="number" value="${data.pot ?? 0}" class="pot w-full text-center border-0 dark:bg-transparent dark:text-white focus:outline-none focus:ring-0"></td>
                <td class="border dark:border-white/10 px-2 py-1 text-right total text-center">0.00</td>
                <td class="border dark:border-white/10 px-2 py-1 text-center">
                    <button type="button" class="hapus p-1 rounded text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0h8l-1-2h-6l-1 2z" />
                        </svg>
                    </button>
                </td>
            `;
            tr.querySelectorAll('.jumlah, .harga, .pot').forEach(input => input.addEventListener('input', hitungTotal));
            tr.querySelector('.hapus').addEventListener('click', () => {
                tr.remove();
                updateNomor();
                hitungTotal();
            });
            tableBody.appendChild(tr);
            hitungTotal();
        }

        function addItemToTable(item) {
            const kosong = [...tableBody.querySelectorAll('tr')].find(tr => {
                const kodeInput = tr.querySelector('input[type="text"]');
                return kodeInput && kodeInput.value.trim() === '';
            });

            if (kosong) {
                const inputs = kosong.querySelectorAll('input');
                inputs[0].value = item.kode_produk;
                inputs[1].value = item.nama_produk;
                inputs[2].value = 1;
                inputs[3].value = item.satuan ?? '';
                inputs[4].value = item.harga_jual;
            } else {
                buatBaris(tableBody.querySelectorAll('tr').length + 1, item);
            }
            hitungTotal();
        }

        btnTambah.addEventListener('click', () => {
            buatBaris(tableBody.querySelectorAll('tr').length + 1);
        });

        potonganEl.addEventListener('input', hitungTotal);
        pajakEl.addEventListener('input', hitungTotal);

        document.addEventListener('DOMContentLoaded', () => {
            for (let i = 1; i <= 10; i++) {
                buatBaris(i);
            }
        });
    </script>
</form>
@endsection
