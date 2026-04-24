@extends('layout.main')
@section('title', 'Tambah Produk')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
@endsection
@section('container')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: transparent !important;
            border: none !important;
            height: auto !important;
            padding: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            margin-left: 0 !important;
            color: inherit !important;
        }

        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
        }

        @media (max-width: 768px) {
            .tombol-dekstop {
                display: none;
            }

            .tombol-mobile {
                display: flex;
            }
        }

        .cropper-container {
            max-height: 500px !important;
        }
    </style>

    <div x-data="productForm()" class="pb-20">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data" id="produkForm">
            @csrf

            <div class="px-2 py-1 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-black dark:text-white tracking-tight">Tambah Produk Baru</h2>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Lengkapi informasi untuk
                        mendaftarkan produk baru ke sistem</p>
                </div>

                <!-- Tab Navigation -->
                <div
                    class="flex items-center gap-1 bg-gray-100 dark:bg-white/5 p-1 rounded-xl w-fit border border-black/5 dark:border-white/5 shadow-inner">
                    <button type="button" @click="activeTab = 'informasi'"
                        :class="activeTab === 'informasi' ? 'bg-white dark:bg-white/10 text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="px-5 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                        Informasi Produk
                    </button>
                    <button type="button" @click="activeTab = 'akuntansi'"
                        :class="activeTab === 'akuntansi' ? 'bg-white dark:bg-white/10 text-blue-600 shadow-sm ring-1 ring-black/5' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300'"
                        class="px-5 py-2 text-xs font-bold rounded-lg transition-all duration-200">
                        Pemetaan Akun
                    </button>
                </div>

                <!-- Desktop Action Button -->
                <div class="tombol-dekstop">
                    <button type="submit"
                        class="p-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/20 transition duration-150 border-b-4 border-blue-800 active:border-b-0 active:translate-y-1 flex items-center gap-2">
                        <span>Simpan Produk</span>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Action Button -->
            <div class="fixed bottom-0 right-4 z-[99] space-y-3 flex flex-col tombol-mobile hidden"
                style="margin-bottom: 90px; margin-top:10px;">
                <button type="submit"
                    class="w-14 h-14 flex items-center justify-center bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition transform active:scale-95 shadow-blue-500/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 shadow-sm"
                    role="alert">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong class="font-bold">Terjadi kesalahan!</strong>
                    </div>
                    <ul class="mt-1 list-disc list-inside text-sm opacity-90">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tab Content: Informasi Produk -->
            <div x-show="activeTab === 'informasi'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 gap-7 lg:grid-cols-2">
                    <!-- Kolom Kiri: Informasi Produk -->
                    <div class="space-y-6 ">
                        <div
                            class="mb-4 border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm">
                            <p
                                class="text-sm font-bold mb-6 text-black dark:text-white uppercase tracking-widest border-l-4 border-blue-600 pl-3">
                                Informasi Dasar</p>

                            <!-- Nama Produk -->
                            <div
                                class="py-4 px-5 mb-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                <label
                                    class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Nama
                                    Produk <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_produk" placeholder="Masukkan nama produk"
                                    class="form-input font-bold text-base" required value="{{ old('nama_produk') }}" />
                            </div>

                            <!-- Kode Produk -->
                            <div
                                class="py-4 px-5 mb-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                <label
                                    class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Kode
                                    Produk</label>
                                <input type="text" name="kode_produk" placeholder="Otomatis jika kosong"
                                    class="form-input font-bold text-blue-600 dark:text-blue-400"
                                    value="{{ old('kode_produk', $generatedCode) }}" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <!-- Kategori -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Kategori
                                        <span class="text-red-500">*</span></label>
                                    <select name="kategori" class="select2 w-full" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($category as $cat)
                                            <option value="{{ $cat->id }}" {{ old('kategori') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Satuan -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Satuan
                                        <span class="text-red-500">*</span></label>
                                    <select name="satuan" class="select2 w-full" required id="satuan_select">
                                        <option value="">Pilih Satuan</option>
                                        @foreach($satuans as $sat)
                                            <option value="{{ $sat->nama }}" data-id="{{ $sat->id }}" {{ old('satuan') == $sat->nama ? 'selected' : '' }}>{{ $sat->nama }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="satuan_id" id="satuan_id" value="{{ old('satuan_id') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <!-- Stok -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Stok
                                        Awal <span class="text-red-500">*</span></label>
                                    <input type="number" name="stok" placeholder="0" class="form-input font-bold" required
                                        value="{{ old('stok', 0) }}" />
                                </div>

                                <!-- Berat -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Berat
                                        (gram)</label>
                                    <input type="number" name="berat" placeholder="0" class="form-input"
                                        value="{{ old('berat', 0) }}" />
                                </div>
                            </div>
                        </div>

                        <div
                            class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm">
                            <p
                                class="text-sm font-bold mb-6 text-black dark:text-white uppercase tracking-widest border-l-4 border-blue-600 pl-3">
                                Informasi Tambahan</p>
                            <!-- Deskripsi -->
                            <div
                                class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                <label
                                    class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Deskripsi
                                    Produk</label>
                                <textarea name="deskripsi" placeholder="Keterangan tambahan produk..."
                                    class="form-input h-32 resize-none leading-relaxed"
                                    oninput="autoResize(this)">{{ old('deskripsi') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Harga & Gambar -->
                    <div class="space-y-6">
                        <div
                            class="mb-4 border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm">
                            <p
                                class="text-sm font-bold mb-6 text-black dark:text-white uppercase tracking-widest border-l-4 border-blue-600 pl-3">
                                Harga & Gambar</p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                <!-- Harga Pokok (HPP) -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Harga
                                        Pokok (HPP) <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <span class="text-sm mr-2 text-black/40 dark:text-white/40 font-bold">Rp</span>
                                        <input type="number" name="harga" placeholder="0"
                                            class="form-input bg-transparent font-bold" required value="{{ old('harga') }}"
                                            @input="updateFormattedPrice($event, 'formattedHPP')" />
                                    </div>
                                    <small id="formattedHPP"
                                        class="text-[10px] text-blue-600 dark:text-blue-400 font-bold"></small>
                                </div>

                                <!-- Harga Jual -->
                                <div
                                    class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Harga
                                        Jual <span class="text-red-500">*</span></label>
                                    <div class="flex items-center">
                                        <span class="text-sm mr-2 text-black/40 dark:text-white/40 font-bold">Rp</span>
                                        <input type="number" name="harga_jual" placeholder="0"
                                            class="form-input bg-transparent font-bold" required
                                            value="{{ old('harga_jual') }}"
                                            @input="updateFormattedPrice($event, 'formattedJual')" />
                                    </div>
                                    <small id="formattedJual"
                                        class="text-[10px] text-blue-600 dark:text-blue-400 font-bold"></small>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="py-4 px-5 mb-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                                <label
                                    class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Status
                                    Produk</label>
                                <select name="status"
                                    class="form-select dark:bg-black font-bold text-blue-600 dark:text-blue-400">
                                    <option value="aktif">Aktif</option>
                                    <option value="non-aktif">Non-Aktif</option>
                                </select>
                            </div>

                            <!-- Gambar Produk (Multi) -->
                            <div
                                class="py-6 px-5 mb-5 bg-white rounded-2xl border border-black/10 relative dark:bg-white/5">
                                <label
                                    class="block mb-4 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black italic">Gambar
                                    Produk</label>

                                <div class="flex flex-wrap gap-4">
                                    <!-- Image Thumbnails -->
                                    <template x-for="(img, index) in images" :key="index">
                                        <div
                                            class="relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-blue-500/50 group shadow-lg">
                                            <img :src="img" class="relative z-0 w-full h-full object-cover">
                                            <button type="button" @click="removeImage(index)"
                                                class="absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition-colors z-10">
                                                <i class="fas fa-trash text-[10px]"></i>
                                            </button>
                                            <input type="hidden" name="cropped_gambar[]" :value="img">
                                        </div>
                                    </template>

                                    <!-- Add Button -->
                                    <button type="button" @click="$refs.imageInput.click()"
                                        class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-300 dark:border-white/20 flex flex-col items-center justify-center bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 hover:border-blue-500 transition-all duration-300 group">
                                        <div
                                            class="w-10 h-10 rounded-full bg-blue-600/10 text-blue-600 flex items-center justify-center mb-1 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <span
                                            class="text-[9px] font-black uppercase text-gray-400 dark:text-white/40 group-hover:text-blue-600">Tambah</span>
                                    </button>
                                </div>
                                <input type="file" x-ref="imageInput" @change="handleImageUpload" class="hidden"
                                    accept="image/*">
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="bg-blue-600/5 border border-blue-600/10 p-5 rounded-2xl flex gap-4">
                            <div
                                class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-xl flex-shrink-0 shadow-lg shadow-blue-600/20">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div>
                                <p class="text-xs text-blue-800 dark:text-blue-300 font-bold mb-1">Tips Fotografi Produk</p>
                                <p class="text-[10px] text-blue-700/60 dark:text-blue-400/60 leading-relaxed">Gunakan
                                    background polos dan pencahayaan yang cukup. Pastikan produk berada di tengah frame
                                    sebelum melakukan cropping.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Pemetaan Akun -->
            <div x-show="activeTab === 'akuntansi'" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="max-w-3xl mx-auto space-y-6 pb-10">
                    <div
                        class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-8 rounded-2xl shadow-xl">
                        <div class="flex items-center gap-4 mb-10 border-b border-black/5 dark:border-white/5 pb-6">
                            <div
                                class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center rounded-2xl shadow-lg shadow-blue-600/30">
                                <i class="fas fa-file-invoice-dollar fa-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-black dark:text-white tracking-tight">Konfigurasi
                                    Akuntansi</h3>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-1">Pemetaan akun
                                    untuk otomatisasi penjurnalan transaksi</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Akun Persediaan -->
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] text-black/50 dark:text-white/50 uppercase tracking-widest font-black ml-1">Akun
                                    Persediaan (Asset)</label>
                                <div
                                    class="py-3 px-4 bg-white rounded-xl border border-black/10 dark:bg-white/5 hover:border-blue-500/50 transition duration-200">
                                    <select name="persediaan_id" class="select2 w-full">
                                        <option value="">Pilih Akun Persediaan</option>
                                        @foreach($akun as $ak)
                                            <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Akun Pendapatan -->
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] text-black/50 dark:text-white/50 uppercase tracking-widest font-black ml-1">Akun
                                    Pendapatan (Sales)</label>
                                <div
                                    class="py-3 px-4 bg-white rounded-xl border border-black/10 dark:bg-white/5 hover:border-blue-500/50 transition duration-200">
                                    <select name="pendapatan_id" class="select2 w-full">
                                        <option value="">Pilih Akun Pendapatan</option>
                                        @foreach($akun as $ak)
                                            <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Akun HPP -->
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] text-black/50 dark:text-white/50 uppercase tracking-widest font-black ml-1">Akun
                                    HPP (COGS)</label>
                                <div
                                    class="py-3 px-4 bg-white rounded-xl border border-black/10 dark:bg-white/5 hover:border-blue-500/50 transition duration-200">
                                    <select name="hpp_id" class="select2 w-full">
                                        <option value="">Pilih Akun HPP</option>
                                        @foreach($akun as $ak)
                                            <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Akun Beban Non-Inventory -->
                            <div class="space-y-2">
                                <label
                                    class="block text-[10px] text-black/50 dark:text-white/50 uppercase tracking-widest font-black ml-1">Akun
                                    Beban (Non-Inventory)</label>
                                <div
                                    class="py-3 px-4 bg-white rounded-xl border border-black/10 dark:bg-white/5 hover:border-blue-500/50 transition duration-200">
                                    <select name="beban_non_inventory_id" class="select2 w-full">
                                        <option value="">Pilih Akun Beban</option>
                                        @foreach($akun as $ak)
                                            <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-6 bg-gray-100 dark:bg-white/5 border border-black/5 dark:border-white/5 p-5 rounded-2xl flex items-start gap-4">
                        <div
                            class="w-8 h-8 bg-blue-600/10 dark:bg-blue-400/10 text-blue-600 dark:text-blue-400 flex items-center justify-center rounded-lg flex-shrink-0">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 leading-relaxed font-medium">
                            Pemetaan akun ini digunakan untuk otomatisasi laporan Neraca dan Laba Rugi setiap terjadi
                            transaksi produk ini. Pastikan Anda memilih akun yang sesuai dengan klasifikasi akuntansi
                            perusahaan Anda.
                        </p>
                    </div>
                </div>
            </div>
        </form>

        <!-- Cropping Modal -->
        <div id="cropperModal"
            class="fixed inset-0 bg-black/80 z-[9999] hidden flex flex-col items-center justify-center p-4">
            <div
                class="bg-white dark:bg-zinc-900 w-full max-w-4xl rounded-2xl overflow-hidden shadow-2xl flex flex-col h-[90vh]">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-black dark:text-white">Crop Gambar Produk (1080x1080)</h3>
                    <button type="button" @click="closeCropper()"
                        class="text-gray-400 hover:text-black dark:hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-hidden bg-gray-100 dark:bg-black p-4 relative">
                    <img id="cropperImage" class="max-w-full">
                </div>
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-white/5 border-t border-gray-100 dark:border-white/5 flex items-center justify-between gap-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium italic">Gunakan kursor untuk menyesuaikan
                        bagian gambar yang ingin ditampilkan.</p>
                    <div class="flex gap-3">
                        <button type="button" @click="closeCropper()"
                            class="px-5 py-2 text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-white/10 rounded-xl transition">Batal</button>
                        <button type="button" @click="applyCrop()"
                            class="px-8 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-lg shadow-blue-500/20 transition border-b-4 border-blue-800 active:border-b-0 active:translate-y-1">Simpan
                            Crop</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function productForm() {
            return {
                activeTab: 'informasi',
                images: [],
                cropper: null,

                init() {
                    $(document).ready(() => {
                        $('.select2').select2({
                            width: '100%',
                            dropdownAutoWidth: true
                        });

                        $('#satuan_select').on('change', (e) => {
                            var selectedOption = $(e.target).find('option:selected');
                            $('#satuan_id').val(selectedOption.data('id'));
                        });
                    });
                },

                handleImageUpload(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            const cropperImage = document.getElementById('cropperImage');
                            cropperImage.src = event.target.result;
                            document.getElementById('cropperModal').classList.remove('hidden');
                            document.getElementById('cropperModal').classList.add('flex');

                            if (this.cropper) this.cropper.destroy();
                            this.cropper = new Cropper(cropperImage, {
                                aspectRatio: 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 1,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                },

                applyCrop() {
                    const canvas = this.cropper.getCroppedCanvas({
                        width: 1080,
                        height: 1080,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    const croppedDataUrl = canvas.toDataURL('image/jpeg', 0.9);
                    this.images.push(croppedDataUrl);
                    this.closeCropper();
                },

                closeCropper() {
                    document.getElementById('cropperModal').classList.add('hidden');
                    document.getElementById('cropperModal').classList.remove('flex');
                    this.$refs.imageInput.value = '';
                    if (this.cropper) this.cropper.destroy();
                },

                removeImage(index) {
                    this.images.splice(index, 1);
                },

                updateFormattedPrice(e, targetId) {
                    const value = e.target.value;
                    const target = document.getElementById(targetId);
                    if (value) {
                        target.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    } else {
                        target.innerText = '';
                    }
                }
            }
        }

        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }
    </script>
@endsection