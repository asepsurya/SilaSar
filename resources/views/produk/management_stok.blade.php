@extends('layout.main')
@section('title', 'Management Stok Produk')
@section('container')
<div class="">
    <h1 class="text-xl font-bold mb-4">Manajemen Stok</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 dark:border-white/10">
            <thead>
                <tr class="bg-gray-100 dark:bg-white/10">
                    <th class="px-4 py-2 border">Tanggal</th>
                    <th class="px-4 py-2 border">Produk</th>
                    <th class="px-4 py-2 border">Tipe</th>
                    <th class="px-4 py-2 border">Jumlah</th>
                    <th class="px-4 py-2 border">Sumber</th>
                    <th class="px-4 py-2 border">Referensi</th>
                    <th class="px-4 py-2 border">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stokLogs as $log)
                    <tr>
                        <td class="px-4 py-2 border">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2 border">{{ $log->kode_produk }}</td>
                        <td class="px-4 py-2 border capitalize">{{ $log->tipe }}</td>
                        <td class="px-4 py-2 border">{{ $log->jumlah }}</td>
                        <td class="px-4 py-2 border">{{ $log->sumber }}</td>
                        <td class="px-4 py-2 border">{{ $log->referensi }}</td>
                        <td class="px-4 py-2 border">{{ $log->keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection