@extends('layout.main')
@section('title', 'Tambah Produk')
@section('container')
<style>
    .select2-container--default .select2-selection--single {
   
        margin-left: -10px;
        border: none;
    }

    .dark .select2-container--default .select2-selection--single {
        margin-left: -10px;
             background: transparent;
        border: none;
    }

    .p-7 {
        padding: 0px !important;
    }

    footer {
        margin: 30px;
    }

</style>
<style>
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

</style>

<div x-data="{ tab: 'tab1' }">
    <!-- Tab Header -->
    <div class="flex border-b border-gray-200 dark:border-white/10">
        <button @click="tab = 'tab1'" :class="tab === 'tab1' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm">
            Data Produk
        </button>

        <button @click="tab = 'tab3'" :class="tab === 'tab3' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'" class="px-4 py-2 font-medium text-sm">
            Akunting
        </button>
    </div>

    <!-- Tab Content -->
    <div class="p-4  border-t-0 border-gray-200 dark:border-gray-700 rounded-b-lg">
        <div x-show="tab === 'tab1'">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" id="addProdukForm">
                @csrf
                <div class="px-2 py-1 mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Produk Saya</h2>
                    <button type="submit" id="submitForm" class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Simpan Produk Baru
                    </button>
                </div>
                @if ($errors->any())
                <div class=" bg-lightyellow/50 dark:bg-lightyellow border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline">Ada kesalahan pada input Anda:</span>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif


                <div class="grid grid-cols-1 gap-7 lg:grid-cols-2">
                    <!-- SECTION FORM KIRI -->
                    <div class="">

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div class="space-y-4">
                            <!-- Kode Produk -->
                            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kode Produk <span style="color: red">*</span></label>
                                <input type="text" name="kode_produk" id="kode_produk" class="form-input" readonly />
                            </div>

                            <!-- Nama Produk -->
                            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Nama Produk <span style="color: red">*</span></label>
                                <input type="text" name="nama_produk" placeholder="Nama Produk" class="form-input" value="{{ old('nama_produk') }}" />
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                                <!-- Harga -->

                                <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Harga Produk <span style="color: red">*</span></label>
                                    <input type="number" name="harga" placeholder="Harga Produk" class="form-input" oninput="formatCurrency(this)" value="{{ old('harga') }}" />
                                    <small><span id="formattedHarga" class="text-gray-500">Rp 0</span></small>
                                    <script>
                                        function formatCurrency(input) {
                                            const value = input.value.replace(/\D/g, '');
                                            const formatted = new Intl.NumberFormat('id-ID', {
                                                style: 'currency'
                                                , currency: 'IDR'
                                            , }).format(value || 0);
                                            document.getElementById('formattedHarga').textContent = formatted;
                                        }

                                    </script>
                                </div>

                                <!-- Stok -->
                                <div hidden class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Stok Produk <span style="color: red">*</span></label>
                                    <input type="number" name="stok" placeholder="Stok Produk" class="form-input" value="9999" />

                                </div>

                                <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Berat Bersih <span style="color: red">*</span></label>
                                    <div class="flex items-center">
                                        <input type="number" name="berat" placeholder="Berat Bersih" class="form-input" value="{{ old('berat') }}" />
                                        <select name="satuan" class="form-select rounded-l-none border-l-0">
                                            <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>gram</option>
                                            <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                                            <option value="ons" {{ old('satuan') == 'ons' ? 'selected' : '' }}>ons</option>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">

                                <!-- Stok -->
                                <div hidden class=" px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Status Keterdiaan <span style="color: red">*</span></label>
                                    <select name="status" class="status form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                                        <option value="">Pilih status</option>
                                        <option value="available" selected>Tersedia</option>
                                        <option value="out_of_stock">Habis Stok</option>
                                        <option value="pre_order">Pre Order</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Deskripsi Produk</label>
                                <textarea name="deskripsi" placeholder="Deskripsi Singkat Produk Anda" class="form-input resize-none overflow-hidden" oninput="autoResize(this)">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div x-data="{ openModal: false }" class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                    Kategori Produk <span class="text-red-500">*</span>
                                </label>

                                <div class="flex gap-2">
                                    <select id="select-kategori" name="kategori" class="form-select w-full">
                                        <option value="" disabled {{ old('kategori') === null ? 'selected' : '' }}>Pilih Kategori</option>

                                        @foreach ($category as $item)
                                        <option value="{{ $item->id }}" {{ old('kategori') == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                        @endforeach

                                        <option value="other" {{ old('kategori') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>

                                    <!-- Tombol buka modal -->
                                    <button type="button"  @click="$dispatch('produk')" class="btn px-3 py-2 text-sm rounded-md bg-blue-600  text-white hover:bg-blue-600">
                                        +
                                    </button>
                                </div>


                            </div>
                        </div>
                    </div>
                    <!-- SECTION KANAN: GAMBAR DAN KATEGORI -->
                    <div>
                        <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Gambar Produk</label>

                            <!-- Input file -->
                            <input id="fileInput" name="gambar" type="file" accept="image/*" class="form-input" onchange="previewImage(event)" />

                            <!-- Kotak preview -->
                            <div class="mt-4 border border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden relative" style="width: 200px; height: 200px;">
                                <img id="imgPreview" src="" alt="Preview" class="w-full h-full object-cover hidden" />
                                <span id="imgPlaceholder" class="text-gray-400 text-sm">Belum ada gambar</span>

                                <!-- Tombol hapus -->
                                <button 
                                    type="button" 
                                    id="btnRemove" 
                                    onclick="removeImage()"
                                    class="absolute top-1 right-1 bg-red-500 text-white text-xs px-2 py-1 rounded-md shadow hidden hover:bg-red-600">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

{{-- Modal --}}
<div x-data="categoryModal()" 
     @produk.window="open = true" 
     @close-modal.window="open = false">

    <!-- Overlay -->
    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999]" 
         x-show="open" 
         x-transition.opacity   @click.self="open = false">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false" >

            <!-- Modal Box -->
            <div class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                 x-show="open" 
                >

                <!-- Header -->
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Tambah Transaksi Baru</h5>
                    <button type="button" 
                            class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" 
                            @click="open = false">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"/>
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5" >

                    <!-- Table kategori -->
                    <div class="flex-1 overflow-y-auto" style="max-height: 300px; overflow-y: auto;">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-white/10 sticky top-0">
                                <tr>
                                    <th class="px-3 py-2 w-20 text-left">ID</th>
                                    <th class="px-3 py-2 text-left">Nama</th>
                                    <th class="px-3 py-2 w-28 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in categories" :key="item.id">
                                    <tr class="border-t border-gray-200 dark:border-white/10">
                                        <td class="px-3 py-2" x-text="item.id"></td>

                                        <!-- Nama -->
                                        <td class="px-3 py-2">
                                            <template x-if="editRow !== item.id">
                                                <span x-text="item.name"></span>
                                            </template>
                                            <template x-if="editRow === item.id">
                                                <div class="flex gap-2">
                                                    <input type="text" x-model="item.name" class="w-full px-2 py-1 border rounded-md text-sm dark:bg-gray-800 dark:border-gray-700">
                                                    <button @click="updateCategory(item)" class="px-2 py-1 bg-green-500 text-white rounded-md text-xs hover:bg-green-600">✔</button>
                                                </div>
                                            </template>
                                        </td>

                                        <!-- Tombol -->
                                        <td class="px-3 py-2 text-center">
                                            <button x-show="editRow !== item.id" @click="editRow = item.id" class="px-2 py-1 text-xs bg-yellow-500 text-white rounded-md hover:bg-yellow-600">Edit</button>
                                            <button x-show="editRow === item.id" @click="editRow = null" class="px-2 py-1 text-xs bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tambah kategori -->
                    <div class="mt-4 flex gap-2">
                        <input type="text" placeholder="Nama kategori baru" x-model="newName" class="form-input mt-2 py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                        <button @click.prevent="addCategory()" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Tambah
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

{{-- Alpine.js Script --}}
<script>
function categoryModal() {
    return {
        open: false,
        categories: [],
        editRow: null,
        newName: '',

        async fetchCategories() {
            let res = await fetch("{{ route('category.list') }}");
            this.categories = await res.json();
        },

        async addCategory() {
            if (!this.newName.trim()) return;
            let res = await fetch("{{ route('category.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ name: this.newName })
            });
            if (res.ok) {
                this.newName = '';
                this.fetchCategories();
            }
        },

        async updateCategory(item) {
            if (!item.name.trim()) return;
            let res = await fetch("{{ route('category.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id: item.id, name: item.name })
            });
            if (res.ok) {
                this.editRow = null;
                this.fetchCategories();
            }
        },

        init() {
            this.fetchCategories();
        }
    }
}
</script>


        <div x-show="tab === 'tab3'" x-cloak>
            <div class="max-w-4xl">
             

                <div class=" rounded-lg px-5 space-y-4">

                    <!-- Harga Pokok Penjualan -->
                    <div class="grid grid-cols-2 gap-4 items-center mb-3">
                        <label class="text-sm dark:text-gray-300">
                            Harga Pokok Penjualan
                        </label>
                        <select class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                            <option>5-1100</option>
                        </select>
                    </div>

                    <!-- Pendapatan Jual -->
                    <div class="grid grid-cols-2 gap-4 items-center mb-3">
                        <label class="text-sm font-medium  dark:text-gray-300">
                            Pendapatan Jual
                        </label>
                        <select class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                            <option>4-1100</option>
                        </select>
                    </div>

                    <!-- Pendapatan Jasa -->
                    <div class="grid grid-cols-2 gap-4 items-center mb-3">
                        <label class="text-sm font-medium  dark:text-gray-300">
                            Pendapatan Jasa
                        </label>
                        <select class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                            <option>4-2000</option>
                        </select>
                    </div>

                    <!-- Persediaan -->
                    <div class="grid grid-cols-2 gap-4 items-center mb-3">
                        <label class="text-sm font-medium  dark:text-gray-300">
                            Persediaan
                        </label>
                        <select class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                            <option>1-1301</option>
                        </select>
                    </div>

                    <!-- Biaya Non Inventory -->
                    <div class="grid grid-cols-2 gap-4 items-center mb-3">
                        <label class="text-sm font-medium  dark:text-gray-300">
                            Biaya Non Inventory
                        </label>
                        <select class="form-select py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;"">
                            <option>6-9001</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#select-kategori').select2({
            placeholder: "Pilih Kategori"
            , width: '100%'
        });
        $('.status').select2({
            placeholder: "Status Ketersediaan"
            , width: '100%'
        });
    });

</script>
<script>
    // Preview gambar
    function previewImage(event) {
    const file = event.target.files[0];
    const img = document.getElementById('imgPreview');
    const placeholder = document.getElementById('imgPlaceholder');
    const btnRemove = document.getElementById('btnRemove');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            img.classList.remove('hidden');
            placeholder.classList.add('hidden');
            btnRemove.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const img = document.getElementById('imgPreview');
    const placeholder = document.getElementById('imgPlaceholder');
    const fileInput = document.getElementById('fileInput');
    const btnRemove = document.getElementById('btnRemove');

    img.src = '';
    img.classList.add('hidden');
    placeholder.classList.remove('hidden');
    btnRemove.classList.add('hidden');
    fileInput.value = ''; // reset input file
}

    // Generate kode produk otomatis
    document.addEventListener('DOMContentLoaded', function() {
        const kodeInput = document.getElementById('kode_produk');
        if (kodeInput) {
            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Bulan (01-12)
            const year = String(now.getFullYear()).slice(-2); // 2 digit terakhir tahun
            const random = Math.floor(1000 + Math.random() * 9000); // 4 digit acak

            kodeInput.value = `PRD-${month}${year}/${random}`;
        }
    });
    // Toggle function for sidebar    
    // Auto resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

</script>
<script>
    document.getElementById('submitAddCategory').addEventListener('click', function () {
        document.getElementById('addCategoryForm').submit();
    });

    document.getElementById('submitEditCategory').addEventListener('click', function () {
        document.getElementById('editCategoryForm').submit();
    });
    document.getElementById('submitForm').addEventListener('click', function () {
        document.getElementById('addProdukForm').submit();
    });
</script>
@endsection
