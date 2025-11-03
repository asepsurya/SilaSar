@extends('layout.main')
@section('title', 'Data Akun')
@section('container')
    <style>
    @media (max-width: 640px) {
  .p-7 {
    padding: 10px !important;
  }
}
        .select2-container--default .select2-selection--single {
            margin-left: -10px;
            border: none;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(0, 0, 0, 0);
            margin-left: -10px;
            border: none;
        }
        #loading {
      display: none;
      margin-top: 10px;
      font-style: italic;
      color: gray;
    }

    /* Optional spinner style */
    .spinner {
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
      display: inline-block;
      vertical-align: middle;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    </style>
    <style>
        /* Tabel responsif */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }



        /* Media Query untuk Mobile (zoom-out effect) */
        @media (max-width: 768px) {
            body {
                zoom: 0.9; /* Zoom-out untuk layar kecil */
            }

            table {
                font-size: 14px; /* Mengurangi ukuran font untuk tampilan kecil */
            }

            .search-icon {
                width: 24px;
                height: 24px;
            }

            /* Menyesuaikan ukuran tabel agar lebih baik di mobile */
            th, td {
                padding: 5px;
            }
        }

        /* Lebih responsif jika ukuran lebih kecil dari 500px */
        @media (max-width: 500px) {
            body {
                zoom: 0.8; /* Lebih kecil lagi di perangkat lebih kecil */
            }

            table {
                font-size: 12px; /* Ukuran font semakin kecil */
            }
        }
        
    </style>
    <div class="px-2 py-1 mb-4">
        <div class="px-2 py-1 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Data Akun</h2>
            
            <div x-data="modals">
                <div class="flex gap-3">
                    <div x-data="{ open: false }">
                        <button type="button" @click="open = true" class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 256 256">
                                <path d="M208,24H72A32,32,0,0,0,40,56V224a8,8,0,0,0,8,8H192a8,8,0,0,0,0-16H56a16,16,0,0,1,16-16H208a8,8,0,0,0,8-8V32A8,8,0,0,0,208,24ZM120,40h48v72L148.79,97.6a8,8,0,0,0-9.6,0L120,112Zm80,144H72a31.82,31.82,0,0,0-16,4.29V56A16,16,0,0,1,72,40h32v88a8,8,0,0,0,12.8,6.4L144,114l27.21,20.4A8,8,0,0,0,176,136a8,8,0,0,0,8-8V40h16Z"></path>
                            </svg>
                        </button>

                        <!-- Overlay -->
                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/50 z-40" @click="open = false" x-cloak>
                        </div>

                        <!-- Modal -->
                        <div x-show="open" x-transition x-cloak class="fixed inset-0 flex items-center justify-center z-50 px-4">
                            <div class="bg-white dark:bg-black relative shadow-3xl rounded-lg overflow-hidden w-full max-w-lg" @click.outside="open = false">
                                <!-- Header -->
                                <div class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                    <h5 class="font-semibold text-lg text-black dark:text-white">Data Kategori</h5>
                                    <button type="button" class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white" @click="open = false">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 
                        1 0 1 1 1.414 1.414L11.414 10l4.293 
                        4.293a1 1 0 0 1-1.414 1.414L10 
                        11.414l-4.293 4.293a1 1 0 0 
                        1-1.414-1.414L8.586 10 4.293 
                        5.707a1 1 0 0 1 0-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Table Container dengan Scroll -->
                                <div class="max-h-80 overflow-y-auto">
                                    <table class="min-w-full text-sm text-left">
                                        <thead class="bg-gray-100 dark:bg-white/10 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-2">#</th>
                                                <th class="px-4 py-2">Nama Kategori</th>
                                                <th class="px-4 py-2">Tipe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kategori as $i => $item)
                                            <tr class="border-b border-gray-200 dark:border-white/10  dark:hover:bg-gray-800/50">
                                                <td class="px-4 py-2">{{ $i+1 }}</td>
                                                <td class="px-4 py-2">{{ $item->nama_kategori }}</td>
                                                <td class="px-4 py-2 capitalize">{{ $item->tipe }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Footer optional -->
                                <div class="flex justify-end border-t border-black/10 dark:border-white/10 px-5 py-3">
                                    <button @click="open = false" class="btn w-full px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-lg">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>


                    <button type="button" @click="toggle"
                        class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        + Tambah Akun Baru
                    </button>
                </div>
                
                <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                   :class="open &amp;&amp; '!block'">
                    <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                        <div x-show="open" x-transition="" x-transition.duration.300=""
                            class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                            style="display: none;">
                            <div
                                class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                <h5 class="font-semibold text-lg">Tambah Akun Baru</h5>
                                <button type="button"
                                    class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                    @click="toggle">
                                    <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                            fill="currentcolor"></path>
                                        <path
                                            d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                            fill="currentcolor"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-5">
                                <form action="{{ route('akun.create') }}" method="POST">
                                    @csrf
                                    <div class="mb-3 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Kode Akun<span style="color: red">*</span></label>
                                        <div class="flex items-center gap-2 mb-2">
                                            <!-- 2 Select Kiri -->
                                            <div class="flex gap-2">
                                                <input type="number" name="id" placeholder="1" class="form-input py-2.5 px-4 w-full text-black dark:text-white border dark:bg-black border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" required="">
                                            </div>
                                            <span> - </span>
                                            <!-- Input Teks di Kanan -->
                                            <input type="number" name="kode_akun" placeholder="10001" class="form-input py-2.5 px-4 w-full text-black dark:bg-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;" required="">
                                        </div>
                                    </div>

                                    <div
                                        class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Akun <span style="color: red">*</span></label>
                                        <input type="text" name="nama_akun" placeholder="Nama Akun" class="form-input">
                                    </div>
                                    <div
                                        class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Kategori<span style="color: red">*</span></label>
                                        <select name="kategori_id" id="kategori"
                                            class="kategori select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                                            <option value="">-Pilih Kategori-</option>
                                            @foreach ($kategori as $item)
                                                <option value="{{ $item->id }}" data-jenis="{{ $item->tipe }}"> {{ $item->nama_kategori }}</option>
                                            @endforeach
                                        </select>
                                        
                                        <input type="hidden" id="jenis" name="jenis_akun" class="jenis" placeholder="Jenis akan muncul di sini" > 
                                     
                                    </div>

                                    <button type="submit"
                                        class=" w-full px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1  gap-7">
        <div class="">
            <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-3 rounded-md">
                @if ($errors->any())
                <div class="flex items-center rounded bg-lightred/50 p-3 dark:bg-lightred text-black/80 dark:text-black">
                    <svg class="w-5 h-5 mr-2" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16 3C16 3 18.6442 3 21.0605 4.02201C21.0605 4.02201 23.3936 5.00884 25.1924 6.80761C25.1924 6.80761 26.9912 8.60638 27.978 10.9395C27.978 10.9395 29 13.3558 29 16C29 16 29 18.6442 27.978 21.0605C27.978 21.0605 26.9912 23.3936 25.1924 25.1924C25.1924 25.1924 23.3936 26.9912 21.0605 27.978C21.0605 27.978 18.6442 29 16 29C16 29 13.3558 29 10.9395 27.978C10.9395 27.978 8.60638 26.9912 6.80761 25.1924C6.80761 25.1924 5.00884 23.3936 4.02202 21.0605C4.02202 21.0605 3 18.6442 3 16C3 16 3 13.3558 4.02202 10.9395C4.02202 10.9395 5.00885 8.60638 6.80761 6.80761C6.80761 6.80761 8.60638 5.00884 10.9395 4.02201C10.9395 4.02201 13.3558 3 16 3ZM16 5C16 5 13.7614 5 11.7186 5.86402C11.7186 5.86402 9.74476 6.69889 8.22183 8.22182C8.22183 8.22182 6.6989 9.74476 5.86402 11.7186C5.86402 11.7186 5 13.7614 5 16C5 16 5 18.2386 5.86402 20.2814C5.86402 20.2814 6.69889 22.2552 8.22183 23.7782C8.22183 23.7782 9.74476 25.3011 11.7186 26.136C11.7186 26.136 13.7614 27 16 27C16 27 18.2386 27 20.2814 26.136C20.2814 26.136 22.2552 25.3011 23.7782 23.7782C23.7782 23.7782 25.3011 22.2552 26.136 20.2814C26.136 20.2814 27 18.2386 27 16C27 16 27 13.7614 26.136 11.7186C26.136 11.7186 25.3011 9.74476 23.7782 8.22183C23.7782 8.22183 22.2552 6.69889 20.2814 5.86402C20.2814 5.86402 18.2386 5 16 5Z" fill="currentColor"></path>
                        <path d="M6.80546 8.21968L23.7803 25.1946C23.9679 25.3821 24.2222 25.4874 24.4874 25.4874C24.7527 25.4875 25.007 25.3821 25.1946 25.1946C25.3821 25.007 25.4875 24.7527 25.4875 24.4874C25.4874 24.2222 25.3821 23.9679 25.1946 23.7803L8.21968 6.80546C8.03202 6.61781 7.77767 6.51245 7.51245 6.51245C7.24723 6.51245 6.99288 6.61781 6.80534 6.80534C6.61781 6.99288 6.51245 7.24723 6.51245 7.51245C6.51245 7.77767 6.61781 8.03202 6.80546 8.21968Z" fill="currentColor"></path>
                    </svg>
                    <span class="pr-2">
                        <strong>Terjadi error:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </span>
                    <button type="button" class="ml-auto hover:opacity-50 rotate-0 hover:rotate-180 transition-all duration-300">
                        <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z" fill="currentcolor"></path>
                            <path d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z" fill="currentcolor"></path>
                        </svg>
                    </button>
                </div>
                @endif

                <div class="mb-4">
                    <label class="mt-1.5 flex -space-x-px">
                        <div class="flex items-center justify-center rounded-l-lg border border-black/10 dark:border-white/10 px-3.5 font-inter">
                            <svg class="text-black/20 dark:text-white/20" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14.3496 14.3563C14.2563 14.4483 14.1306 14.4999 13.9996 14.5001C13.8668 14.4995 13.7393 14.4481 13.6434 14.3563L10.9434 11.6501C9.80622 12.6052 8.34425 13.0845 6.86236 12.9879C5.38046 12.8913 3.99306 12.2264 2.98951 11.1317C1.98596 10.0371 1.44375 8.59729 1.47597 7.1126C1.50818 5.62791 2.11233 4.21298 3.16241 3.1629C4.21249 2.11282 5.62743 1.50867 7.11212 1.47645C8.59681 1.44424 10.0366 1.98645 11.1313 2.99C12.2259 3.99355 12.8908 5.38095 12.9874 6.86285C13.084 8.34474 12.6047 9.80671 11.6496 10.9438L14.3496 13.6438C14.3969 13.6904 14.4344 13.7458 14.46 13.807C14.4856 13.8681 14.4988 13.9338 14.4988 14.0001C14.4988 14.0664 14.4856 14.132 14.46 14.1932C14.4344 14.2544 14.3969 14.3098 14.3496 14.3563ZM7.24961 12.0001C8.18907 12.0001 9.10743 11.7215 9.88857 11.1996C10.6697 10.6776 11.2785 9.93579 11.638 9.06784C11.9976 8.19989 12.0916 7.24483 11.9083 6.32342C11.7251 5.40201 11.2727 4.55564 10.6084 3.89134C9.94407 3.22704 9.0977 2.77465 8.17629 2.59137C7.25488 2.40809 6.29981 2.50215 5.43186 2.86167C4.56391 3.22119 3.82206 3.83001 3.30013 4.61114C2.77819 5.39227 2.49961 6.31064 2.49961 7.2501C2.50126 8.50937 3.00224 9.71659 3.89268 10.607C4.78312 11.4975 5.99034 11.9984 7.24961 12.0001Z" fill="currentColor"></path>
                            </svg>
                        </div>
                        <input  id="filterInput" class="form-input w-full rounded-r-lg border border-black/10 dark:border-white/10 bg-transparent dark:bg-black px-3 py-2.5 placeholder:text-black/60 dark:placeholder:text-white/60 hover:z-10 hover:border-black/10 dark:hover:border-white/10 focus:z-10 dark:focus:border-white/10" placeholder="Cari Akun..." type="text" onkeyup="filterTable()">
                        <div id="loading"><span class="spinner"></span> Memfilter data...</div>
                    </label>
                </div>
                <div class="table-responsive">
                    <table id="dataTable">
                        <thead>
                            <tr>
                                <th>Nama Akun</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @php $no=1; @endphp
                            @foreach ($akun as $data)
                                <tr class="border-b dark:border-white/10">
                                    <td >
                                        <div x-data="modals">
                                            <div style="display:flex;flex-direction:column;line-height:1.3;cursor:pointer" @click="toggle">
                                                <span style="font-weight:600;margin-bottom:5px;">{{ $data->nama_akun }}</span>
                                                <span style="font-size:12px;color:#999;">{{ $data->kategori->nama_kategori ?? 'Tidak terdefinisi' }}</span>
                                            </div>
                                            <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                                             :class="open &amp;&amp; '!block'">
                                                <div class="flex items-center justify-center min-h-screen px-4"
                                                    @click.self="open = false">
                                                    <div x-show="open" x-transition="" x-transition.duration.300=""
                                                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                                                        style="display: none;">
                                                        <div
                                                            class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                            <h5 class="font-semibold text-lg">Edit Akun</h5>
                                                            <button type="button"
                                                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                                                @click="toggle">
                                                                <svg class="w-5 h-5" width="32" height="32"
                                                                    viewBox="0 0 32 32" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                    <path
                                                                        d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-5">
                                                            <form action="{{ route('akun.update') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="akun_id" value="{{ $data->id }}">
                                                                    <div class="mb-3 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Kode Akun<span style="color: red">*</span></label>
                                                                      @php
                                                                            // Pisahkan kode_akun berdasarkan tanda '-'
                                                                            $parts = explode('-', $data->kode_akun);
                                                                            $prefix = $parts[0] ?? '';
                                                                            $suffix = $parts[1] ?? '';
                                                                        @endphp

                                                                        <div class="flex items-center gap-2 mb-2">
                                                                            <!-- Input Kiri -->
                                                                            <div class="flex gap-2 w-1/2">
                                                                                <input type="number" name="id" value="{{ $prefix }}" placeholder="1"
                                                                                    class="form-input py-2.5 px-4 w-full text-black dark:text-white border dark:bg-black border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none" required>
                                                                            </div>

                                                                            <span class="text-black dark:text-white"> - </span>

                                                                            <!-- Input Kanan -->
                                                                            <div class="flex gap-2 w-1/2">
                                                                                <input type="number" name="kode_akun" value="{{ $suffix }}" placeholder="10001"
                                                                                    class="form-input py-2.5 px-4 w-full text-black dark:bg-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none" required>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Akun <span style="color: red">*</span></label>
                                                                        <input type="text" name="nama_akun" placeholder="Nama Akun" class="form-input" value="{{ $data->nama_akun }}">
                                                                    </div>
                                                                    <div class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Kategori<span style="color: red">*</span></label>
                                                                        <select name="kategori_id" class="kategori select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                                                                            <option value="">-Pilih Kategori-</option>
                                                                            @foreach ($kategori as $a)
                                                                                <option value="{{ $a->id }}" data-jenis="{{ $a->tipe }}" {{ $data->kategori_id === $a->id ? 'selected' : '' }}>
                                                                                    {{ $a->nama_kategori }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <input type="hidden" name="jenis_akun" class="jenis" placeholder="Jenis akan muncul di sini" value="{{ $data->jenis_akun }}">
                                                                    </div>
                                                                    <button type="submit"
                                                                        class=" w-full px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                                                        Simpan
                                                                    </button>
                                                                </form>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $data->kode_akun }}</td>
                                </tr>
                            @endforeach
                            @if ($akun->where('jenis_akun', 'pemasukan')->count() == 0)
                                <tr>
                                    <td>Data Tdak Ditemukan</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
    <script>
        let filterTimeout;

        function startFilter() {
          clearTimeout(filterTimeout); // Reset jika mengetik cepat
          document.getElementById("loading").style.display = "inline-block";

          filterTimeout = setTimeout(() => {
            filterTable();
            document.getElementById("loading").style.display = "none";
          }, 300); // Delay 300ms
        }

        function filterTable() {
          const input = document.getElementById("filterInput");
          const filter = input.value.toLowerCase();
          const table = document.getElementById("dataTable");
          const tr = table.getElementsByTagName("tr");

          for (let i = 1; i < tr.length; i++) {
            let visible = false;
            const td = tr[i].getElementsByTagName("td");

            for (let j = 0; j < td.length; j++) {
              if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().includes(filter)) {
                  visible = true;
                  break;
                }
              }
            }

            // Smooth transition (optional)
            tr[i].style.transition = "opacity 0.2s ease";
            tr[i].style.opacity = visible ? "1" : "0";
            setTimeout(() => {
              tr[i].style.display = visible ? "" : "none";
            }, 200); // Cocok dengan transition
          }
        }
        </script>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                width: '100%'
            });
        });
    </script>
     <script>
         $(document).ready(function() {
             $('.kategori').select2({
                 width: '100%', 
                 placeholder: 'Pilih Kategori Akun', // teks placeholder
             });
             $('.kategori').on('change', function() {
                 let selectedOption = $(this).find('option:selected');
                 let jenis = selectedOption.data('jenis') || '';
                 let transaksi = '';

                 switch (jenis) {
                     case 'aset':
                     case 'pendapatan':
                         transaksi = 'Pemasukan';
                         break;
                     case 'beban':
                     case 'pengeluaran': // jika ada
                         transaksi = 'Pengeluaran';
                         break;
                     case 'liabilitas':
                     case 'ekuitas':
                         transaksi = 'Pengeluaran'; // bisa disesuaikan aturan akuntansi
                         break;
                     default:
                         transaksi = '';
                 }

                 $('.jenis').val(transaksi);
             });
         });

     </script>

@endsection
