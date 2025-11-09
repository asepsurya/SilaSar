@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('container')

<div class="px-2 py-1 mb-4 flex items-center justify-between">
    <h2 class="text-lg font-semibold">Management Stok</h2>
    <a href="{{ route('manajemenStok') }}"
       class="px-3 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
        + Tambah Stok Produk
    </a>
</div>

<div class=" dark:border-white/10 rounded-lg overflow-hidden">

    <!-- TABEL DESKTOP -->
    <div class="overflow-y-auto hidden md:block" style="max-height: 80vh;">
        <table class="w-full min-w-[800px] border-collapse">
            <thead class="sticky top-0 bg-gray-100 dark:bg-black z-10 text-xs">
                <tr class="text-center">
                    <th class="border dark:border-white/10 px-2 py-2 w-10">No</th>
                    <th class="border dark:border-white/10 px-2 py-2 ">Tanggal</th>
                    <th class="border dark:border-white/10 px-2 py-2 ">Nomor Transaksi</th>
                    <th class="border dark:border-white/10 px-2 py-2 "width="50%">Catatan</th>
                    <th class="border dark:border-white/10 px-2 py-2 ">Dibuat pada</th>
                    <th class="border dark:border-white/10 px-2 py-2 ">Di Update</th>
                    <th class="border dark:border-white/10 px-2 py-2">Aksi</th>
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
                    <tr>
                        <td colspan="7" class="border dark:border-white/10 text-center py-6 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Belum ada riwayat data stok
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- LIST MOBILE -->
    <div class="block md:hidden divide-y dark:divide-white/10">
        @php $no = 1; @endphp
        @forelse ($stok as $item)
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
</div>
<script>
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
