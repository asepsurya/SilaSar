@extends('layout.main')
@section('title','Daftar Satuan')
@section('container')

<div class="">
   <div x-data="modals">
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Satuan Produk </h2>
        <button class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition" @click="toggle">
            + Tambah Satuan
        </button>
    </div>

    <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="open &amp;&amp; '!block'">
        <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
            <div x-show="open" x-transition="" x-transition.duration.300="" class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                    <h5 class="font-semibold text-lg">Kategori</h5>
                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="toggle">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <div class="text-sm text-black dark:text-white">
                        <form action="{{ route('satuan.add') }}" method="POST">
                            <!-- CSRF Token (Laravel) -->
                            @csrf
                            <div class="mb-4">
                                <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                    <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Satuan</label>
                                    <input type="text" name="nama" placeholder="Nama Kategori " class="form-input">
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 w-full">
                                    Tambah
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    @if(session('success'))
        <div class="mb-2 p-2 bg-green-200 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-5 rounded-md">
    <table class="w-full border bg-white border-gray-200 rounded">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-1">No</th>
                <th class="border px-2 py-1">Nama</th>
            
            </tr>
        </thead>
        <tbody>
            @foreach($satuans as $index => $satuan)
            <tr class="searchable-row">
                    <td class="w-10">{{ $index + 1 }}.</td>
                    <td>
                        <div x-data="modals">
                            <button @click="toggle">{{ $satuan->nama }}</button>
                            <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto" :class="open &amp;&amp; '!block'">
                                <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                                    <div x-show="open" x-transition="" x-transition.duration.300="" class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8" style="display: none;">
                                        <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                            <h5 class="font-semibold text-lg">Satuan Produk</h5>
                                            <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="toggle">
                                                <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                                                    <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-5">
                                            <div class="text-sm text-black dark:text-white">
                                                <form action="{{ route('satuan.update') }}" method="POST">
                                                    <!-- CSRF Token (Laravel) -->
                                                    @csrf
                                                    <input type="text" name="id" value="{{ $satuan->id }}" hidden>
                                                    <div class="mb-4">
                                                        <div class="relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                            <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Satuan</label>
                                                            <input type="text" name="nama" placeholder="Nama Kategori " class="form-input" value="{{ $satuan->nama }}">
                                                        </div>
                                                    </div>
                                                    <!-- Tombol Submit -->
                                                    <div class="flex justify-end">
                                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 w-full">
                                                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-300 ">
                                                                Update
                                                            </button>
                                                            <a href="{{ route('satuan.delete',$satuan->id) }}" type="button" class="btn text-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300">
                                                                Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                   
                </tr>
                
           
            @endforeach
        </tbody>
    </table>
    </div>
</div>

@endsection
