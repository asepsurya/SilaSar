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
    
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Produk Saya <span class="px-1 bg-lightgreen-100 text-xs text-black rounded ml-1">{{ $produk->count() }}</span></h2>
        <a href="{{ route('index.create.produk') }}" class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" >
            + Tambah Produk Baru
        </a>
    </div>
    <div class="py-3">
        <input type="text" id="searchInput" placeholder="Cari Produk..."
            class="form-input py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;"
            required="" data-listener-added_87712baf="true">
    </div>
    
    <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">
        <div class="table-responsive">
            <table class="w-full border-collapse text-sm table-auto" id="produkTable">
                <thead class="hidden lg:table-header-group">
                    <tr class="text-gray-400">
                        <th class="text-left pl-6 py-3 font-normal w-1/2">Product</th>
                        <th class="text-left font-normal w-1/6">Created at</th>
                        {{-- <th class="text-left font-normal w-1/6">Status</th> --}}
                        <th class="text-left font-normal w-1/6 pr-6">Amount</th>
                        <th class="w-6"></th>
                    </tr>
                </thead>
                <tbody class="">
                    @foreach ($produk as $item)
                        <tr class="hover:bg-gray-50 border-b border-black/10 dark:border-white/10 cursor-pointer transition">
                            <!-- Produk -->
                            <td class="py-4 pl-6 flex items-start gap-3">
                              <a data-fancybox="gallery" data-caption="{{ $item->nama_produk }}" 
                                   href="{{ optional($item)->gambar ? asset('storage/' . $item->gambar) : asset('assets/images/404.jpg') }}">
                                    <img 
                                        src="{{ optional($item)->gambar ? asset('storage/' . $item->gambar) : asset('assets/images/404.jpg') }}" 
                                        alt="Produk"
                                        class="h-12 w-12 rounded-lg object-cover flex-shrink-0"
                                        onerror="this.onerror=null;this.src='{{ asset('assets/images/404.jpg') }}';" 
                                    />
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
                                        {{-- <div><strong>Status:</strong>{{ $item->status }}({{ $item->stok }} pcs)</div> --}}
                                        <div><strong>Amount:</strong> Rp{{ number_format($item->harga, 0, ',', '.') }}</div>
                                        <div><strong>Stok:</strong> ({{$item->stok ?? '0'}}) {{ $item->satuanObj->nama ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom desktop (disembunyikan di mobile) -->
                            <td class="py-4  font-normal mobile lg:table-cell">{{ $item->kode_produk }} <b>Stok ({{$item->stok ?? '0'}}) {{ $item->satuanObj->nama ?? '-' }}</b></td>
                            {{-- <td class="py-4 mobile lg:table-cell">
                                <span class="inline-block rounded px-2 py-0.5 text-xs font-semibold">{{ $item->status }} ({{ $item->stok }} pcs)</span>
                            </td> --}}
                            <td class="py-4 font-semibold mobile lg:table-cell">Rp
                                {{ number_format($item->harga, 0, ',', '.') }}</td>

                        </tr>
                    @endforeach
                    @if ($produk->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada produk yang tersedia.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <p id="noDataMessage" class="hidden text-center text-gray-500 py-4">
                Data tidak ditemukan.
            </p>

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

@endsection
