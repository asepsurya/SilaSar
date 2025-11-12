@extends('layout.main')
@section('title', 'Data Produk')
@section('container')
<!-- Fancybox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

<!-- Fancybox JS -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>

    <style>
     @media (max-width: 768px) {
  

    .mobile {
        display: none;
    }
}
    </style>
<style>
/* === Select2 Minimal Clean Style === */
.select2-container--default .select2-selection--single {
border: 1px solid #e5e7eb !important; /* border-gray-200 */
}
.dark .select2-container--default .select2-selection--single {
border: 1px solid #242424 !important; /* border-gray-700 */
background-color: #000000 !important; /* bg-black */
color: #ffffff !important; /* text-white */
}


.select2-container--default .select2-selection--single:focus,
.select2-container--default.select2-container--focus .select2-selection--single {
border-color: #9ca3af !important; /* focus border-gray-400 */
box-shadow: none !important;
}
.select2-container--default .select2-results__option--highlighted[aria-selected] {
background-color: #f3f4f6 !important; /* bg-gray-100 */
color: #111827 !important; /* text-gray-900 */
}
.a {
display: flex;
flex-wrap: wrap;
gap: 10px;
}

/* Default (Desktop) */
.cari {
width: 88%;
}

.filter {
width: 10%;
}

/* Mobile (<768px) */ @media (max-width: 768px) { .cari, .filter { width: 100%; } .mobile { display: none; } }

</style>
    <div class="px-2 py-1 mb-2 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Produk Saya <span class="px-1 bg-lightgreen-100 text-xs text-black rounded ml-1">{{ $produk->count() }}</span></h2>
            <a href="{{ route('index.create.produk') }}" class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                + Tambah Produk Baru
            </a>  
    </div>
 <div class="a flex flex-col md:flex-row items-center gap-3 py-3">
    <!-- Input Cari Produk (90%) -->
    <div class="w-full cari">
        <input 
            type="text" 
            id="searchInput" 
            placeholder="Cari Produk..." 
            class="form-input py-2.5 px-4 w-full text-sm text-gray-700 
                   border border-gray-200 rounded-md placeholder:text-gray-400 
                   focus:border-gray-400 focus:ring-0 focus:shadow-none"
        >
    </div>

    <!-- Tombol Filter (10%) -->
    <form action="" method="GET" class="w-full filter ">
        <button 
            type="button" 
            @click="window.dispatchEvent(new CustomEvent('filter'))"
            class=" w-full flex items-center justify-center gap-x-2 
                   px-3 py-2.5 text-sm font-medium
                 btn  rounded-md transition duration-150 ease-in-out" >
            @if(request('kategori'))
                {{ optional($kategori->firstWhere('id', request('kategori')))->name ?? 'Filter' }}
            @else
                Filter
            @endif

        </button>
    </form>
</div>
<script>
    $(document).ready(function() {
        $('#kategoriSelect').select2({
            placeholder: "Kategori",
            width: '100%'
        });
    });
</script>

   <!-- Modal -->
        <div x-data="{ open: false }" @filter.window="open = true" @close-modal.window="open = false">
            <!-- Overlay -->
            <div
                class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                :class="{ 'block': open, 'hidden': !open }"
            >
                <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                    <!-- Modal Box -->
                    <div
                        x-show="open"
                        x-transition
                        x-transition.duration.300
                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                        style="display: none;"
                    >
                        <!-- Header -->
                        <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                            <h5 class="font-semibold text-lg">Filter</h5>
                            <button
                                type="button"
                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                @click="open = false"
                            >
                                <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                                    <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-5">
                            <form method="GET" class="flex flex-col gap-5">
                    
                                <!-- Filter Kategori -->
                                <div>
                                    <label class="block text-xs font-semibold text-black/60 dark:text-white/60 mb-1">Kategori Produk</label>
                                    <select name="kategori" class="form-select py-2 px-4 rounded-lg border border-black/10 dark:border-white/10 text-sm w-full bg-transparent dark:bg-transparent text-black dark:text-white">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>{{ $kat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300">
                                        Terapkan Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
    
    <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
        <div class="table-responsive">
            <table class="w-full border-collapse text-sm table-auto" id="produkTable">
                <thead class="hidden lg:table-header-group">
                    <tr class="text-gray-400">
                        <th class="text-left pl-6 py-3 font-normal w-1/2">Product</th>
                        <th class="text-left font-normal w-1/6">Created at</th>
                        <th class="text-left font-normal w-1/6 pr-6">Amount</th>
                        <th class="w-6"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $item)
                        <tr class="hover:bg-gray-50 border-b border-black/10 dark:border-white/10 cursor-pointer transition">
                            <!-- Produk -->
                            <td class="py-4 pl-6 flex items-start gap-3">
                                <a data-fancybox="gallery" data-caption="{{ $item->nama_produk }}" 
                                href="{{ optional($item)->gambar ? asset('storage/' . $item->gambar) : asset('assets/images/404.jpg') }}">
                                    <img src="{{ optional($item)->gambar ? asset('storage/' . $item->gambar) : asset('assets/images/404.jpg') }}"
                                        alt="Produk"
                                        class="h-12 w-12 rounded-lg object-cover flex-shrink-0"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" />
                                </a>

                                <div class="flex flex-col">
                                    <span class="font-semibold leading-tight">
                                        <a href="{{ route('index.update.produk',$item->id) }}">{{ $item->nama_produk }}</a>
                                    </span>
                                    <span class="text-gray-400 leading-tight truncate max-w-[50px]">
                                        {{ \Illuminate\Support\Str::limit($item->deskripsi, 30, '...') }}
                                    </span>

                                    <!-- Detail mobile -->
                                    <div class="lg:hidden mt-2 text-xs text-gray-500 space-y-1">
                                        <div><strong>Created at:</strong> {{ $item->created_at->format('d M Y') }}</div>
                                        <div><strong>Amount:</strong> Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                                        <div><strong>Stok:</strong> ({{$item->stok ?? '0'}}) {{ $item->satuanObj->nama ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom desktop -->
                            <td class="py-4 font-normal mobile lg:table-cell">
                                {{ $item->kode_produk }} 
                                <b>Stok ({{$item->stok ?? '0'}}) {{ $item->satuanObj->nama ?? '-' }}</b>
                            </td>

                            <td class="py-4 font-semibold mobile lg:table-cell">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </td>

                            <!-- Dropdown menu -->
                            <td class="text-right pr-4 relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 14a1.5 1.5 0 110 3 1.5 1.5 0 010-3z" />
                                    </svg>
                                </button>

                                <!-- Dropdown content -->
                            <div x-show="open" @click.away="open = false"
                                    class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-black/10 dark:border-white/10 rounded-lg shadow-lg py-1 z-20 text-left">
                                    <form action="{{ route('produk.log.detail') }}" method="GET">
                                        @csrf
                                        <input type="text" name="produk_id" value="{{ $item->kode_produk }}" hidden>
                                        <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 
                                            hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                            Kartu Stok
                                        </button>

                                    </form>

                                  
                                    <a href="{{ route('index.update.produk',$item->id) }}"
                                    class="block w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 
                                            hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                        Edit Produk
                                    </a>

                                    <button type="button" onclick="hapusProduk('{{ route('action.delete',$item->id) }}')"
                                        class="block w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-200 
                                            hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                        Hapus
                                    </button>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                    @if ($produk->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-500 items-center flex flex-col justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7.5V6a1.5 1.5 0 011.5-1.5H9l2-2h2l2 2h4.5A1.5 1.5 0 0121 6v1.5M3 7.5h18M3 7.5v10.5A1.5 1.5 0 004.5 19.5h15A1.5 1.5 0 0021 18V7.5M9 12h6m-6 4h3" />
                                </svg>
                                Tidak ada produk yang tersedia.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

                <script>
                function hapusProduk(url) {
                    Swal.fire({
                        title: 'Yakin hapus produk ini?',
                        text: "Data tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                        reverseButtons: true, // tombol batal di kiri
                        customClass: {
                            popup: 'rounded-xl border border-black/10 dark:border-white/10 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 shadow-lg',
                            title: 'text-lg font-semibold',
                            htmlContainer: 'text-sm text-gray-600 dark:text-gray-300',
                            confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition mx-2',
                            cancelButton: 'bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 transition',
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }
                </script>

            <p id="noDataMessage" class="hidden text-center text-gray-500 py-4">
                Data tidak ditemukan.
            </p>
        </div>
        <div class="mt-3">
            {{ $produk->links() }}
        </div>
        
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const keyword = this.value.toLowerCase();
            const rows = document.querySelectorAll('#produkTable tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(keyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataMessage = document.getElementById('noDataMessage');
            if (visibleCount === 0) {
                noDataMessage.classList.remove('hidden');
            } else {
                noDataMessage.classList.add('hidden');
            }
        });
    </script>
@if (session('produk_error'))
@php $err = session('produk_error'); @endphp

<div x-data="{ open: true }" @close-modal.window="open = false">
    <!-- Overlay -->
    <div
        class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
        :class="{ 'block': open, 'hidden': !open }"
    >
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <!-- Modal Box -->
            <div
                x-show="open"
                x-transition
                x-transition.duration.300
                class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                style="display: none;"
            >
                <!-- Header -->
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg text-red-600">Produk Tidak Dapat Dihapus</h5>
                    <button
                        type="button"
                        class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                        @click="open = false"
                    >
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor" />
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-5 space-y-5">
                    <p class="text-sm  dark:text-gray-300">
                        Produk <strong>{{ $err['nama'] }}</strong> tidak dapat dihapus karena masih digunakan pada data berikut:
                    </p>

                    <!-- Tabel Penawaran -->
                    @if (count($err['penawaran']) > 0)
                    <div>
                        <h6 class="font-semibold  dark:text-gray-200 mb-2">Penawaran</h6>
                        <div class="overflow-x-auto rounded-md border border-black/10 dark:border-white/10">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Kode Mitra</th>
                                        <th class="px-3 py-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($err['penawaran'] as $p)
                                    <tr class="border-t border-black/10 dark:border-white/10">
                                        <td class="px-3 py-2">{{ $p['kode'] }}</td>
                                        <td class="px-3 py-2">{{ $p['tanggal'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Tabel Transaksi -->
                    @if (count($err['transaksi']) > 0)
                    <div>
                        <h6 class="font-semibold  dark:text-gray-200 mb-2">Transaksi</h6>
                        <div class="overflow-x-auto rounded-md border border-black/10 dark:border-white/10">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Kode Transaksi</th>
                                        <th class="px-3 py-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($err['transaksi'] as $t)
                                    <tr class="border-t border-black/10 dark:border-white/10">
                                        <td class="px-3 py-2">{{ $t['kode'] }}</td>
                                        <td class="px-3 py-2">{{ $t['tanggal'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="flex justify-end bg-gray-50 dark:bg-black/30 px-5 py-3 border-t border-black/10 dark:border-white/10">
                    <button
                        type="button"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                        @click="open = false"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif


@endsection
