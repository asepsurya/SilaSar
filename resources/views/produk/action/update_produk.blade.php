@extends('layout.main')
@section('title', 'Perbarui Produk')
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

    @foreach ($produk as $item)
        <div x-data="productForm({{ $item->id }})" class="pb-20">
            @php
                $gambarData = [];
                if ($item->gambar) {
                    $decoded = json_decode($item->gambar, true);
                    $gambarData = is_array($decoded) ? $decoded : [$item->gambar];
                }
            @endphp
            <form action="{{ route('action.update') }}" method="POST" enctype="multipart/form-data" id="produkForm">
                @csrf
                <input type="hidden" name="id" value="{{ $item->id }}">

                <div class="px-2 py-1 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-black dark:text-white">Perbarui Produk</h2>
                        <p class="text-xs text-gray-400 mt-1 uppercase tracking-tight">KODE: <span
                                class="font-bold text-blue-600 dark:text-blue-400">{{ $item->kode_produk }}</span></p>
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

                    <!-- Desktop Action Buttons -->
                    <div class="flex items-center gap-3 tombol-dekstop">
                        <button type="button" onclick="confirmDelete('{{ route('action.delete', $item->id) }}')"
                            class="px-4 py-2 text-sm font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 rounded-lg transition duration-150">
                            Hapus Produk
                        </button>
                        <button type="submit"
                            class="px-6 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-lg shadow-blue-500/20 transition duration-150 border-b-4 border-blue-800 active:border-b-0 active:translate-y-1">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

                <!-- Mobile Action Buttons -->
                <div class="fixed bottom-0 right-4 z-50 space-y-3 flex flex-col tombol-mobile hidden"
                    style="margin-bottom: 90px;">
                    <button type="button" onclick="confirmDelete('{{ route('action.delete', $item->id) }}')"
                        class="w-14 h-14 flex items-center justify-center bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition transform active:scale-95 shadow-red-500/50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
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
                        <div class="space-y-6">
                            <div
                                class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm mb-6">
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
                                        class="form-input font-bold text-base" required value="{{ $item->nama_produk }}" />
                                </div>

                                <!-- Kode Produk -->
                                <div
                                    class="py-4 px-5 mb-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Kode
                                        Produk</label>
                                    <input type="text" name="kode_produk" placeholder="Masukkan kode produk"
                                        class="form-input font-bold text-blue-600 dark:text-blue-400"
                                        value="{{ $item->kode_produk }}" />
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
                                                <option value="{{ $cat->id }}" {{ $item->kategori == $cat->id ? 'selected' : '' }}>
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
                                                <option value="{{ $sat->nama }}" data-id="{{ $sat->id }}" {{ $item->satuan == $sat->nama ? 'selected' : '' }}>{{ $sat->nama }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="satuan_id" id="satuan_id" value="{{ $item->satuan_id }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                    <!-- Stok -->
                                    <div
                                        class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                        <label
                                            class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Stok
                                            Tersedia <span class="text-red-500">*</span></label>
                                        <input type="number" name="stok" placeholder="0" class="form-input font-bold" required
                                            value="{{ $item->stok }}" />
                                    </div>

                                    <!-- Berat -->
                                    <div
                                        class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                        <label
                                            class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Berat
                                            (gram)</label>
                                        <input type="number" name="berat" placeholder="0" class="form-input"
                                            value="{{ $item->berat }}" />
                                    </div>
                                </div>
                            </div>

                            <div
                                class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm mb-6">
                                <p
                                    class="text-sm font-bold mb-6 text-black dark:text-white uppercase tracking-widest border-l-4 border-blue-600 pl-3">
                                    Informasi Tambahan</p>
                                <!-- Deskripsi -->
                                <div
                                    class="py-4 px-5 mb-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 focus-within:ring-2 focus-within:ring-blue-500/20 transition-all">
                                    <label
                                        class="block mb-1 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black">Deskripsi
                                        Produk</label>
                                    <textarea name="deskripsi" placeholder="Keterangan tambahan produk..."
                                        class="form-input h-32 resize-none leading-relaxed"
                                        oninput="autoResize(this)">{{ $item->deskripsi }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div
                                class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-6 rounded-xl shadow-sm mb-6">
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
                                                class="form-input bg-transparent font-bold" required value="{{ $item->harga }}"
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
                                                value="{{ $item->harga_jual }}"
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
                                        <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="non-aktif" {{ $item->status == 'non-aktif' ? 'selected' : '' }}>Non-Aktif
                                        </option>
                                    </select>
                                </div>

                                <!-- Gambar Produk (Multi) -->
                                <div
                                    class="py-6 px-5 mb-5 bg-white rounded-2xl border border-black/10 relative dark:bg-white/5">
                                    <label
                                        class="block mb-4 text-[10px] text-black/40 dark:text-white/40 uppercase tracking-widest font-black italic">Gambar
                                        Produk (1080x1080 Recommended)</label>

                                    <div class="flex flex-wrap gap-4">
                                        <!-- Existing Images -->
                                        <template x-for="(img, index) in existingImages" :key="'ex-'+index">
                                            <div
                                                class="relative w-24 h-24 rounded-2xl overflow-hidden border border-black/10 group shadow-md">
                                                <button type="button" @click="removeExistingImage(index)"
                                                    class="hapus absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition-colors z-10">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                                <img :src="'/storage/' + img" class="relative z-0 w-full h-full object-cover">
                                                <input type="hidden" name="existing_gambar[]" :value="img">
                                            </div>
                                        </template>

                                        <!-- New Cropped Images -->
                                        <template x-for="(img, index) in newImages" :key="'new-'+index">
                                            <div
                                                class="relative w-24 h-24 rounded-2xl overflow-hidden border-2 border-blue-500/50 group shadow-lg">
                                                <button type="button" @click="removeNewImage(index)"
                                                    class="hapus absolute top-1 right-1 w-6 h-6 bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-700 transition-colors z-10">
                                                    <i class="fas fa-trash text-[10px]"></i>
                                                </button>
                                                <img :src="img" class="relative z-0 w-full h-full object-cover">

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
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Pemetaan Akun -->
                <div x-show="activeTab === 'akuntansi'" x-cloak x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="max-w-3xl mx-auto space-y-6 pb-10">
                        <div
                            class="border bg-white dark:bg-white/5 border-black/10 dark:border-white/10 p-8 rounded-2xl shadow-xl mb-6">
                            <div class="flex items-center gap-4 mb-10 border-b border-black/5 dark:border-white/5 pb-6">
                                <div
                                    class="w-12 h-12 bg-blue-600 text-white flex items-center justify-center rounded-2xl shadow-lg shadow-blue-600/30">
                                    <i class="fas fa-file-invoice-dollar fa-lg"></i>
                                </div>
                                <div class="">
                                    <h3 class="text-xl font-black text-black dark:text-white tracking-tight">Konfigurasi
                                        Akuntansi</h3>
                                    <p class="text-xs text-gray-400 mt-1">Pemetaan akun untuk otomatisasi penjurnalan transaksi.
                                    </p>
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
                                                <option value="{{ $ak->id }}" {{ $item->persediaan_id == $ak->id ? 'selected' : '' }}>
                                                    {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                                </option>
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
                                                <option value="{{ $ak->id }}" {{ $item->pendapatan_id == $ak->id ? 'selected' : '' }}>
                                                    {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                                </option>
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
                                                <option value="{{ $ak->id }}" {{ $item->hpp_id == $ak->id ? 'selected' : '' }}>
                                                    {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                                </option>
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
                                                <option value="{{ $ak->id }}" {{ $item->beban_non_inventory_id == $ak->id ? 'selected' : '' }}>
                                                    {{ $ak->kode_akun }} - {{ $ak->nama_akun }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Cropping Modal -->
            <div id="cropperModal" class="fixed inset-0 bg-black/80 hidden flex flex-col items-center justify-center p-4"style="z-index: 999999;">
                        <div
                            class="bg-white dark:bg-zinc-900 w-full max-w-4xl rounded-2xl overflow-hidden shadow-2xl flex flex-col h-[90vh]">
                            <div class="px-6 py-4 border-b border-gray-100 dark:border-white/5 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-black dark:text-white">Crop Gambar Produk </h3>
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
    @endforeach
            <script>
                function productForm(productId) {
                    return {
                        productId: productId,
                        activeTab: 'informasi',
                        existingImages: @json($gambarData),
                        newImages: [],
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

                                // Initialize formatted prices
                                $('input[type="number"]').each((i, el) => {
                                    this.updateFormattedPrice({ target: el }, el.getAttribute('oninput')?.match(/'([^']+)'/)[1]);
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
                            this.newImages.push(croppedDataUrl);
                            this.closeCropper();
                        },

                        closeCropper() {
                            document.getElementById('cropperModal').classList.add('hidden');
                            document.getElementById('cropperModal').classList.remove('flex');
                            this.$refs.imageInput.value = '';
                            if (this.cropper) this.cropper.destroy();
                        },

                        async removeExistingImage(index) {
                            const imagePath = this.existingImages[index];

                            const result = await Swal.fire({
                                title: 'Hapus Gambar?',
                                text: "Gambar akan dihapus secara permanen dari database.",
                                icon: 'warning',
                                showCancelButton: true,
                                customClass: {
                                    confirmButton: 'px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all active:scale-95 mr-2',
                                    cancelButton: 'px-6 py-2.5 bg-zinc-100 dark:bg-white/10 text-zinc-600 dark:text-zinc-400 font-bold rounded-xl hover:bg-zinc-200 dark:hover:bg-white/20 transition-all active:scale-95 ml-2'
                                },
                                buttonsStyling: false,
                                background: document.documentElement.classList.contains('dark') ? '#1a1a1a' : '#fff',
                                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                            });

                            if (result.isConfirmed) {
                                try {
                                    const response = await fetch('{{ route('produk.image.delete') }}', {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({
                                            id: this.productId,
                                            image_path: imagePath
                                        })
                                    });

                                    const data = await response.json();
                                    if (data.success) {
                                        this.existingImages.splice(index, 1);
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Dihapus!',
                                            text: 'Gambar berhasil dihapus dari database.',
                                            timer: 1500,
                                            showConfirmButton: false,
                                            customClass: {
                                                popup: 'rounded-2xl border border-black/5 dark:border-white/5 shadow-2xl'
                                            },
                                            background: document.documentElement.classList.contains('dark') ? '#1a1a1a' : '#fff',
                                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: data.message || 'Terjadi kesalahan.',
                                            customClass: {
                                                confirmButton: 'px-6 py-2 bg-blue-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all active:scale-95'
                                            },
                                            buttonsStyling: false,
                                            background: document.documentElement.classList.contains('dark') ? '#1a1a1a' : '#fff',
                                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                                        });
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                    Swal.fire('Error!', 'Terjadi kesalahan saat menghubungi server.', 'error');
                                }
                            }
                        },

                        removeNewImage(index) {
                            this.newImages.splice(index, 1);
                        },

                        updateFormattedPrice(e, targetId) {
                            if (!targetId) return;
                            const value = e.target.value;
                            const target = document.getElementById(targetId);
                            if (target) {
                                if (value) {
                                    target.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                } else {
                                    target.innerText = '';
                                }
                            }
                        }
                    }
                }

                function confirmDelete(url) {
                    Swal.fire({
                        title: 'Hapus Produk?',
                        text: "Data produk akan dihapus secara permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        customClass: {
                            confirmButton: 'px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all active:scale-95 mr-2',
                            cancelButton: 'px-6 py-2.5 bg-zinc-100 dark:bg-white/10 text-zinc-600 dark:text-zinc-400 font-bold rounded-xl hover:bg-zinc-200 dark:hover:bg-white/20 transition-all active:scale-95 ml-2'
                        },
                        buttonsStyling: false,
                        background: document.documentElement.classList.contains('dark') ? '#1a1a1a' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    })
                }

                function autoResize(textarea) {
                    textarea.style.height = 'auto';
                    textarea.style.height = textarea.scrollHeight + 'px';
                }
            </script>
@endsection