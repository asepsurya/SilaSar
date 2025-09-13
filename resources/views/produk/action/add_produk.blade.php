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
            border: none;
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
    <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="px-2 py-1 mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Produk Saya</h2>
            <button class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" @click="toggle">
                Simpan Produk Baru
            </button>
        </div>
        @if ($errors->any())
            <div class=" bg-lightyellow/50 dark:bg-lightyellow border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                role="alert">
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
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kode Produk <span
                                style="color: red">*</span></label>
                        <input type="text" name="kode_produk" id="kode_produk" class="form-input" readonly />
                    </div>

                    <!-- Nama Produk -->
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Nama Produk <span
                                style="color: red">*</span></label>
                        <input type="text" name="nama_produk" placeholder="Nama Produk" class="form-input" value="{{ old('nama_produk') }}" />
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                        <!-- Harga -->

                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Harga Produk <span
                                    style="color: red">*</span></label>
                            <input type="number" name="harga" placeholder="Harga Produk" class="form-input"
                                oninput="formatCurrency(this)" value="{{ old('harga') }}"/>
                            <small><span id="formattedHarga" class="text-gray-500">Rp 0</span></small>
                            <script>
                                function formatCurrency(input) {
                                    const value = input.value.replace(/\D/g, '');
                                    const formatted = new Intl.NumberFormat('id-ID', {
                                        style: 'currency',
                                        currency: 'IDR',
                                    }).format(value || 0);
                                    document.getElementById('formattedHarga').textContent = formatted;
                                }
                            </script>
                        </div>

                        <!-- Stok -->
                        <div hidden
                            class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Stok Produk <span
                                    style="color: red">*</span></label>
                            <input type="number" name="stok" placeholder="Stok Produk" class="form-input"
                                value="9999" />

                        </div>

                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Berat Bersih <span
                                    style="color: red">*</span></label>
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
                        <div hidden
                            class=" px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Status Keterdiaan <span
                                    style="color: red">*</span></label>
                            <select name="status" class="status form-select w-full">
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
                        <textarea name="deskripsi" placeholder="Deskripsi Singkat Produk Anda" class="form-input resize-none overflow-hidden"
                            oninput="autoResize(this)">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

            </div>

            <!-- SECTION KANAN: GAMBAR DAN KATEGORI -->
            <div>
                <div
                    class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md space-y-4">
                    <!-- Kategori -->
                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kategori Produk <span
                                style="color: red">*</span></label>
                       <select id="select-kategori" name="kategori" class="form-select w-full">
                            <option value="" disabled {{ old('kategori') === null ? 'selected' : '' }}>Pilih Kategori</option>

                            @foreach ($category as $item)
                                <option value="{{ $item->id }}" {{ old('kategori') == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach

                            <option value="other" {{ old('kategori') == 'other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <!-- Upload Gambar -->
                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Gambar Produk</label>
                        <input name="gambar" type="file" accept="image/*" class="form-input"
                            onchange="previewImage(event)" />
                        <img id="imgPreview" src="#" alt="Preview" class="mt-4 w-full rounded-lg hidden"
                            width="50" />
                    </div>


                </div>
            </div>
        </div>

        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#select-kategori').select2({
                placeholder: "Pilih Kategori",
                width: '100%'
            });
            $('.status').select2({
                placeholder: "Status Ketersediaan",
                width: '100%'
            });
        });
    </script>
    <script>
        // Preview gambar
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const img = document.getElementById('imgPreview');
                img.src = reader.result;
                img.classList.remove('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
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


@endsection
