@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('container')
<form action="{{ route('manajemenStok.create') }}" method="POST">
    @csrf
    @if ($errors->any())
    <div class="flex items-start rounded bg-lightyellow/50 dark:bg-lightyellow p-3 text-black/80 dark:text-black mb-5">
        <div class="flex-1">
            <strong class="font-semibold">Terjadi kesalahan:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="space-y-4 text-xs font-sans">
        <h2 class="text-lg font-semibold mb-5 text-black dark:text-white">Management Stok Produk</h2>
        
        <div class="flex flex-col lg:flex-row items-start justify-between gap-6 mb-4 border border-black/10 dark:border-white/10 p-5 rounded-md">
            <div class="w-full lg:w-1/2 space-y-3">
                <div class="mb-3">
                    <label class="block mb-1 text-black/60 dark:text-white/60">No Transaksi</label>
                    <input type="text" name="no_transaksi" readonly value="{{ 'STK-' . date('Ymd') . '-' . rand(1000, 9999) }}" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg bg-gray-50 dark:bg-white/5">
                </div>
                <div class="mb-3">
                    <label class="block mb-1 text-black/60 dark:text-white/60">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg bg-white dark:bg-white/5">
                </div>
            </div>
            <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
            <input type="text" name="jenis" value="in" hidden>
        </div>

        <div x-data="produkSearch()" class="mb-5">
            <label class="block mb-1 text-black/60 dark:text-white/60">Cari Produk</label>
            <div class="flex gap-2">
                <input type="text" x-model="keyword" @keydown.enter.prevent="search()" placeholder="Ketik nama atau kode produk..." class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg">
                <button type="button" @click="search()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Cari
                </button>
            </div>

            <div x-show="open" x-transition @click.self="open = false" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
                <div class="bg-white dark:bg-black rounded-xl shadow-2xl p-5 w-full max-w-lg border dark:border-white/10">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-black dark:text-white">Hasil Pencarian</h2>
                        <button type="button" @click="open = false" class="text-gray-500 hover:text-black dark:text-white text-2xl">&times;</button>
                    </div>
                    <template x-if="filtered.length > 0">
                        <div class="overflow-x-auto max-h-[400px] border rounded-lg">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-white/5 sticky top-0">
                                    <tr class="text-black dark:text-white">
                                        <th class="px-3 py-2 text-left">Kode</th>
                                        <th class="px-3 py-2 text-left">Produk</th>
                                        <th class="px-3 py-2 text-right">Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-white/5">
                                    <template x-for="item in filtered" :key="item.kode_produk">
                                        <tr class="hover:bg-gray-50 dark:hover:bg-white/10 cursor-pointer text-black dark:text-white/80" @click="pilihProduk(item)">
                                            <td class="px-3 py-2" x-text="item.kode_produk"></td>
                                            <td class="px-3 py-2" x-text="item.nama_produk"></td>
                                            <td class="px-3 py-2 text-right" x-text="item.stok"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="border dark:border-white/10 rounded-lg overflow-hidden bg-white dark:bg-black">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] border-collapse" id="itemTable">
                    <thead class="bg-gray-100 dark:bg-white/5">
                        <tr class="text-black dark:text-white/80">
                            <th class="border dark:border-white/10 px-4 py-2 w-12">No</th>
                            <th class="border dark:border-white/10 px-4 py-2 text-left">Kode Produk</th>
                            <th class="border dark:border-white/10 px-4 py-2 text-left">Nama Produk</th>
                            <th class="border dark:border-white/10 px-4 py-2 w-32">Jumlah</th>
                            <th class="border dark:border-white/10 px-4 py-2 w-32">Satuan</th>
                            <th class="border dark:border-white/10 px-4 py-2 w-40 text-right">Harga</th>
                            <th class="border dark:border-white/10 px-4 py-2 w-44 text-right">Total</th>
                            <th class="border dark:border-white/10 px-4 py-2 w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr class="empty-row">
                            <td colspan="8" class="px-4 py-10 text-center text-gray-400">Belum ada item ditambahkan</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 mt-6">
            <div class="w-full lg:w-1/2">
                <div class="bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-lg p-5">
                    <label class="block mb-2 text-sm font-medium text-black/60 dark:text-white/60">Keterangan / Catatan</label>
                    <textarea name="deskripsi" placeholder="Tambahkan catatan di sini..." class="form-input w-full p-4 border border-black/10 dark:border-white/10 rounded-lg bg-transparent text-black dark:text-white" rows="3"></textarea>
                </div>
                <div class="mt-4">
                    <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transition duration-200">
                        Simpan Transaksi Stok
                    </button>
                </div>
            </div>

            <div class="w-full lg:w-1/2 bg-white dark:bg-white/5 border border-black/10 dark:border-white/10 rounded-lg p-5 space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-black/60 dark:text-white/60">Sub Total</span>
                    <input type="text" id="subtotal" name="subtotal" readonly class="text-right bg-transparent border-0 font-semibold text-black dark:text-white focus:ring-0" value="Rp 0">
                </div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg text-black dark:text-white">Total Akhir</span>
                    <input type="text" id="totalAkhir" name="total_akhir" readonly class="text-right bg-transparent border-0 font-bold text-xl text-black dark:text-white focus:ring-0" value="Rp 0">
                </div>
            </div>
        </div>
    </div>

<script>
    function produkSearch() {
        return {
            open: false,
            keyword: '',
            products: @json($produk),
            filtered: [],
            search() {
                const key = this.keyword.toLowerCase();
                if (!key) return;
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
            }
        }
    }

    let itemCount = 0;
    const tableBody = document.getElementById('tableBody');
    const subtotalEl = document.getElementById('subtotal');
    const totalAkhirEl = document.getElementById('totalAkhir');
    const satuans = @json($satuans);

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
    }

    function addItemToTable(item) {
        const emptyRow = tableBody.querySelector('.empty-row');
        if (emptyRow) emptyRow.remove();

        itemCount++;
        const tr = document.createElement('tr');
        tr.className = "text-black dark:text-white/90";
        
        let satuanOptions = satuans.map(s => `<option value="${s.id}" ${s.id == item.satuan_id ? 'selected' : ''}>${s.nama}</option>`).join('');

        tr.innerHTML = `
            <td class="border dark:border-white/10 px-4 py-2 text-center row-no">${itemCount}</td>
            <td class="border dark:border-white/10 px-4 py-2">
                <input type="text" name="items[${itemCount}][kode_produk]" value="${item.kode_produk}" readonly class="bg-transparent border-0 w-full focus:ring-0">
            </td>
            <td class="border dark:border-white/10 px-4 py-2">
                <input type="text" name="items[${itemCount}][nama_produk]" value="${item.nama_produk}" readonly class="bg-transparent border-0 w-full focus:ring-0">
            </td>
            <td class="border dark:border-white/10 px-4 py-2">
                <input type="number" name="items[${itemCount}][jumlah]" value="1" class="jumlah-input w-full text-center border-black/10 dark:border-white/10 rounded bg-transparent focus:ring-blue-500">
            </td>
            <td class="border dark:border-white/10 px-4 py-2">
                <select name="items[${itemCount}][satuan_id]" class="w-full border-black/10 dark:border-white/10 rounded bg-transparent text-sm">
                    ${satuanOptions}
                </select>
            </td>
            <td class="border dark:border-white/10 px-4 py-2">
                <input type="number" name="items[${itemCount}][harga]" value="${item.harga || 0}" class="harga-input w-full text-right border-black/10 dark:border-white/10 rounded bg-transparent focus:ring-blue-500">
            </td>
            <td class="border dark:border-white/10 px-4 py-2 text-right font-semibold row-total">
                ${formatRupiah(item.harga || 0)}
            </td>
            <td class="border dark:border-white/10 px-4 py-2 text-center">
                <button type="button" onclick="this.closest('tr').remove(); updateTotals();" class="text-red-500 hover:text-red-700">Hapus</button>
            </td>
        `;

        tr.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        tableBody.appendChild(tr);
        updateTotals();
    }

    function updateTotals() {
        let grandTotal = 0;
        const rows = tableBody.querySelectorAll('tr:not(.empty-row)');
        
        rows.forEach((row, index) => {
            row.querySelector('.row-no').textContent = index + 1;
            const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const total = jumlah * harga;
            row.querySelector('.row-total').textContent = formatRupiah(total);
            grandTotal += total;
        });

        subtotalEl.value = formatRupiah(grandTotal);
        totalAkhirEl.value = formatRupiah(grandTotal);

        if (rows.length === 0) {
            tableBody.innerHTML = '<tr class="empty-row"><td colspan="8" class="px-4 py-10 text-center text-gray-400">Belum ada item ditambahkan</td></tr>';
            itemCount = 0;
        }
    }
</script>
</form>
@endsection
