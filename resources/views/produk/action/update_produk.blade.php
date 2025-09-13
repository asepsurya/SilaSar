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
    @media (max-width: 768px) {
        .tombol-dekstop {
            display: none;
        }
        .tombol-mobile {
            display: flex;
        }
    }   
</style>

<form action="{{ route('action.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Produk Saya</h2>
        <!-- Floating buttons: hanya mobile -->
        <div class="fixed bottom-0 right-4 z-50 space-y-2  flex-col tombol-mobile hidden" style="margin-bottom: 90px;">
            <!-- Tombol Simpan -->
            <button type="submit"
                class="w-12 h-12 flex items-center justify-center bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>
                </svg>
            </button>

            <!-- Tombol Hapus -->
            <button type="button" onclick="confirmDelete('{{ route('action.delete', $id) }}')"
                class="w-12 h-12 flex items-center justify-center bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 tombol-dekstop">
            <button type="submit"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150">
                Simpan
            </button>

            <button type="button" onclick="confirmDelete('{{ route('action.delete', $id) }}')"
                class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg shadow transition duration-150">
                Hapus
            </button>
        </div>
        <script>
            function confirmDelete(url) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Tindakan ini tidak dapat dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg mx-2 focus:outline-none',
                        cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg mx-2 focus:outline-none'
                    },
                    buttonsStyling: false // penting agar customClass dipakai
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            }
        </script>
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

    @foreach ($produk as $item)
    <div class="grid grid-cols-1 gap-7 lg:grid-cols-2">
        <!-- SECTION FORM KIRI -->
        <div class="">

            <input type="hidden" name="id" value="{{ $item->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <div class="space-y-4">
                <!-- Kode Produk -->
                <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kode Produk</label>
                    <input type="text" name="kode_produk" id="kode_produk" class="form-input" readonly
                        value="{{ $item->kode_produk }}" />
                </div>

                <!-- Nama Produk -->
                <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Nama Produk</label>
                    <input type="text" name="nama_produk" placeholder="Nama Produk" class="form-input"
                        value="{{ $item->nama_produk }}" />
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                    <!-- Harga -->
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Harga Produk</label>
                        <input type="number" name="harga" placeholder="Harga Produk" class="form-input" oninput="formatCurrency(this)" value="{{ $item->harga }}" />
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
                      <!-- Harga -->
                       <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Berat Bersih <span
                                    style="color: red">*</span></label>
                            <div class="flex items-center">
                                <input type="number" name="berat" placeholder="Berat Bersih" class="form-input"  value="{{ $item->berat }}"  />
                                <select name="satuan" class="form-select rounded-l-none border-l-0">
                                    <option value="gram" {{ $item->satuan == 'gram' ? 'selected' : '' }}>gram</option>
                                    <option value="kg"  {{ $item->satuan == 'kg' ? 'selected' : '' }}>kg</option>
                                    <option value="ons"  {{ $item->satuan == 'ons' ? 'selected' : '' }}>ons</option>
                                </select>
                            </div>
                        </div>
                
                   <input type="text" name="stok" value="{{ $item->stok }}" hidden>
                     
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                  
                    <!-- Stok -->
                    <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5" hidden>
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Status Keterdiaan</label>
                        <select name="status" class="status form-select w-full">
                            <option value="" selected>Pilih status</option>
                            <option value="available" {{ $item->status == 'available' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="out_of_stock" {{ $item->status == 'out_of_stock' ? 'selected' : '' }}>Habis
                                Stok</option>
                            <option value="pre_order" {{ $item->status == 'pre_order' ? 'selected' : '' }}>Pre Order
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Deskripsi Produk</label>
                    <textarea name="deskripsi" placeholder="Deskripsi Singkat Produk Anda"
                        class="form-input resize-none overflow-hidden"
                        oninput="autoResize(this)">{{ $item->deskripsi }}</textarea>
                </div>
            </div>

        </div>

        <!-- SECTION KANAN: GAMBAR DAN KATEGORI -->
        <div>
            <div
                class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md space-y-4">
                    <!-- Kategori -->
                    <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                        <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kategori Produk</label>
                        <select id="select-kategori" name="kategori" class="form-select w-full">
                            <option value="" selected>Pilih Kategori</option>
                            @foreach ($category as $item2)
                            <option value="{{ $item2->id }}" {{ $item->kategori == $item2->id ? 'selected' : '' }}>{{
                                $item2->name }}</option>
                            @endforeach
                            <option value="other" {{ $item->kategori == 'other' ? 'selected' : '' }}> Lainnya</option>
                        </select>
                    </div>
                <!-- Upload Gambar -->
                <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Gambar Produk</label>
                    <input name="gambar" type="file" accept="image/*" class="form-input"
                        onchange="previewImage(event)" />
                    @if($item->gambar)
                    <img id="imgPreview" src="{{ asset('/storage/' . $item->gambar) }}" alt="Preview"
                        class="mt-4 w-full rounded-lg"
                        onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" width="50" />
                    @else
                    <img id="imgPreview" src="#" alt="Preview" class="mt-4 w-full rounded-lg hidden" width="50" />
                    @endif
                </div>

            
            </div>
        </div>
    </div>
    @endforeach
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

      
        function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    // Resize otomatis saat halaman dimuat
    window.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('textarea').forEach(function (textarea) {
            autoResize(textarea);
        });
    });
</script>


@endsection