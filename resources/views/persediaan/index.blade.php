@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('css')
<link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/simple-datatables.css') }}" />
@endsection

@section('container')

<div class="px-2 py-1 mb-4 flex items-center justify-between">
    <h2 class="text-lg font-semibold">Management Stok</h2>
    <a href="{{ route('manajemenStok') }}"
       class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
        + Tambah Stok Produk
    </a>
</div>

<div x-data="main" x-init="init()" class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-2 rounded-md">

    <div class="clean-table-container hidden md:block">
        <div class="overflow-auto">
            <table id="myTable" class="whitespace-nowrap w-full">
                <thead>
                    <tr class="text-center">
                        <th class="px-2 py-2 w-10">No</th>
                        <th class="px-2 py-2 ">Tanggal</th>
                        <th class="px-2 py-2 ">Nomor Transaksi</th>
                        <th class="px-2 py-2 "width="50%">Catatan</th>
                        <th class="px-2 py-2 ">Dibuat pada</th>
                        <th class="px-2 py-2 ">Di Update</th>
                        <th class="px-2 py-2">Aksi</th>
                    </tr>
                </thead>
            <tbody class="text-sm">
                @php $no=1; @endphp
               @forelse ($stok as $item)
                    <tr onclick="window.location='{{ route('manajemenStok.update', $item->id) }}'"
                        class="border dark:border-white/10 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5 transition">
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->no_transaksi }}</td>
                        <td class="text-left">{{ $item->deskripsi ?? '-' }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{ route('manajemenStok.deleteItem',$item->id) }}"  onclick="event.stopPropagation();" type="button"
                                    class="hapus px-2 py-1 text-xs rounded text-red-600 hover:bg-red-50">
                                Hapus
                            </a>
                        </td>
                    </tr>
                @empty
                    <!-- Optional: datatables should handle empty state automatically, but we can keep it for safety -->
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- LIST MOBILE -->
    <div class="block md:hidden">
        <div class="divide-y dark:divide-white/10">
        @php 
            $currentPage = request('page', 1);
            $itemsPerPage = 10;
            $mobileStok = collect($stok)->slice(($currentPage - 1) * $itemsPerPage, $itemsPerPage);
            $totalPages = ceil(count($stok) / $itemsPerPage);
            $no = ($currentPage - 1) * $itemsPerPage + 1; 
        @endphp
        @forelse ($mobileStok as $item)
            <div class="p-3 mb-3 border rounded-lg dark:border-white/10 bg-white dark:bg-white/5 shadow-sm">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold">#{{ $no++ }} - {{ $item->no_transaksi }}</span>
                    <a href="{{ route('manajemenStok.deleteItem',$item->id) }}" type="button"
                            class="hapus px-2 py-1 text-xs rounded text-red-600 hover:bg-red-50">
                        Hapus
                    </a>
                </div>
                <p class="text-xs text-gray-500">Tanggal: {{ $item->tanggal }}</p>
                <p class="text-xs text-gray-500">Catatan: {{ $item->deskripsi ?? '-'}}</p>
                <p class="text-xs text-gray-500">Dibuat: {{ $item->created_at }}</p>
                <p class="text-xs text-gray-500">Update: {{ $item->updated_at }}</p>
                <a href="{{ route('manajemenStok.update',$item->id ) }}"
                class="inline-block mt-2 text-xs text-blue-600 hover:underline">
                    Lihat / Edit
                </a>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400 border rounded-lg dark:border-white/10 bg-gray-50 dark:bg-black/20">
                     <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                Belum ada data stok yang tersedia
            </div>
        @endforelse
        </div>
        
        {{-- Paging for mobile --}}
        @if($totalPages > 1)
            <div class="datatable-pagination flex justify-center mt-4 mb-4">
                <ul>
                    @if($currentPage > 1)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage - 1]) }}">&laquo;</a>
                        </li>
                    @endif
                    @php
                        // Limit pagination links to max 5 around current page
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                    @endphp
                    @for($i = $start; $i <= $end; $i++)
                        <li class="{{ $i == $currentPage ? 'active' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                                {{ $i }}
                            </a>
                        </li>
                    @endfor
                    @if($currentPage < $totalPages)
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['page' => $currentPage + 1]) }}">&raquo;</a>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>

</div>

@section('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
<script>
document.addEventListener("alpine:init", () => {
    Alpine.data('main', () => ({
        init() {
            const tableEl = document.querySelector("#myTable");
            if (!tableEl) return;
            
            const table = new simpleDatatables.DataTable(tableEl, {
                sortable: false,
                searchable: true,
                perPage: 25,
                perPageSelect: [5, 10, 20, 50, 100],
                firstLast: false,
                labels: {
                    placeholder: 'Cari transaksi...',
                    searchTitle: 'Cari transaksi',
                    perPage: '',
                    noRows: 'Tidak ada data stok ditemukan',
                    info: 'Menampilkan {start} sampai {end} dari {rows} data',
                    previous: '<i class="fas fa-chevron-left text-[10px]"></i>',
                    next: '<i class="fas fa-chevron-right text-[10px]"></i>'
                },
                layout: {
                    top: '{select}{search}',
                    bottom: '<div class="datatable-info-container">{info}</div>{pager}'
                }
            });
            
            const wrapper = tableEl.closest('.datatable-wrapper');
            if (wrapper) wrapper.classList.add('clean-datatable-wrapper');
        }
    }));
});
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".hapus").forEach(function (btn) {
        btn.addEventListener("click", function (e) {
            e.preventDefault(); // cegah langsung redirect
            let link = this.getAttribute("href");

            Swal.fire({
                title: 'Yakin hapus data?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded',
                    cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        });
    });
});
</script>
@endsection
@endsection
