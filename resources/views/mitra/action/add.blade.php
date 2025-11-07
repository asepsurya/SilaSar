@extends('layout.main')
@section('css')

@section('title', 'Form Tambah Mitra')
@section('container')
    <style>
        /* Membatasi tinggi dropdown dan menambah overflow auto untuk scroll jika terlalu panjang */
        #kota-recommendations {
            max-height: 200px;
            /* Atur tinggi maksimal dropdown */
            width: 100%;
            /* Menyesuaikan lebar dropdown dengan lebar input */
            position: absolute;
            top: 100%;
            /* Menampilkan di bawah input */
            left: 0;
            z-index: 10;
        }

        /* Agar dropdown terlihat lebih rapi saat tampil */
        #kota-recommendations li {
            padding: 8px 10px;
            cursor: pointer;
        }

        #kota-recommendations li:hover {
            background-color: #f0f0f0;
        }

        /* Dark mode hover effect */
        .dark #kota-recommendations li:hover {
            background-color: #374151;
            /* dark hover */
        }

        .dark #kota-recommendations li {
            color: #e5e7eb;
            /* warna teks dark mode */
        }

        .dark #kota-recommendations {
            background-color: #1c1c1c;
            /* dark background */
            border-color: #4b5563;
            /* dark border */
        }
        .select2-container--default .select2-selection--single{
            border:none;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none !important;
        }
        /* Teks di dalam input select2 (yang terpilih) */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-weight: bold;
        }
        /* Default desktop: full width */
        .select2-container--default .select2-selection--single {
            width: 100% !important;
        }
        .select2-container--default .select2-dropdown {
            width: 200px !important;
        }
        
        /* Mobile: kecil */
        @media (max-width: 768px) {
            .select2-container--default .select2-selection--single {
                width: 160px !important; /* ukuran mobile */
            }
            .select2-container--default .select2-dropdown {
                width: 160px !important; /* samakan dropdown */
            }
        }

        @media (min-width: 1024px) {
            .lg\:grid-cols-2 {
                /* grid-template-columns: repeat(2, minmax(0, 1fr)); */
                grid-template-columns: 1fr 1.5fr;
            }
        }
       
        @media (max-width: 640px) { /* misal max-width 640px untuk mobile */
            .select2 {
                width: 90% !important; /* paksa lebar 50% di mobile */
            }
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 0px;
            padding-right: 0px;
            overflow: auto;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <form action="{{ route('update.mitra') }}" method="POST">
        @csrf
        <div class="px-2 py-1 mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Detail Mitra / Toko</h2>
          <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <!-- Tombol Simpan -->
                <button type="submit"
                    class="hidden sm:block w-full sm:w-auto inline-flex items-center justify-center px-4 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow transition duration-150">
                    Simpan
                </button>
                <button
                    type="submit"
                    class="fixed right-4 bottom-0 z-50 
                           w-12 h-12 sm:w-auto sm:h-auto 
                           flex items-center justify-center 
                           bg-blue-600 hover:bg-blue-700 text-white font-medium 
                           rounded-full sm:rounded-lg shadow-lg transition duration-150
                           px-0 sm:px-4 py-0 sm:py-2 text-sm sm:text-base"
                style="margin-bottom:100px;" >
                    <span class="sm:inline hidden">Simpan</span>
                    <svg class="w-6 h-6 sm:hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </button>

                 <div class="relative inline-block text-left w-full sm:w-auto">
                <!-- Button Utama -->
                <button type="button" onclick="toggleDropdown()"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-1.5 text-sm font-medium  btn rounded-lg shadow transition duration-150">
                    Tindakan
                    <svg class="ml-2 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu"
                    class="hidden absolute z-50 mt-2 w-full sm:w-40 rounded-md shadow-lg bg-white ring-1 ring-black/10 focus:outline-none">
                    <div class="py-1">
                        <!-- Tombol Hapus -->
                        <button type="button"
                            onclick="confirmDelete('{{ route('mitra.delete', $mitra->id) }}')"
                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Hapus
                        </button>

                        {{-- <!-- Tombol Transaksi -->
                        <button type="button"
                            onclick="checkTransaction('{{ route('transaksi.detail', $mitra->id) }}')"
                            class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100">
                            Transaksi
                        </button> --}}
                    </div>
                </div>
                </div>
                <script>
                    function toggleDropdown() {
                        const dropdown = document.getElementById('dropdownMenu');
                        dropdown.classList.toggle('hidden');
                    }

                    // Optional: close dropdown when clicking outside
                    document.addEventListener('click', function (e) {
                        const dropdown = document.getElementById('dropdownMenu');
                        const button = document.querySelector('button[onclick="toggleDropdown()"]');
                        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });
                </script>

            </div>
             <script>
                function checkTransaction(url) {
                    @if ($mitra->transaksi == null)
                        window.location.href = "{{ route('transaksi.index') }}";
                    @else
                        Swal.fire({
                            title: 'Transaksi sudah ada',
                            text: 'Apakah Anda ingin melanjutkan transaksi yang ada?',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, lanjutkan transaksi',
                            cancelButtonText: 'Batal',
                            customClass: {
                                confirmButton: 'bg-green-600 hover:bg-green-700 text-white px-4 py-1.5 rounded-lg mx-2 focus:outline-none',
                                cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-1.5 rounded-lg mx-2 focus:outline-none'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    @endif
                }
            </script>

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
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg mx-2 focus:outline-none',
                    cancelButton: 'bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-1.5 rounded-lg mx-2 focus:outline-none'
                },
                buttonsStyling: false
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
                });
            }

            function redirectToTransaction(url) {
                window.location.href = url;
            }
            </script>
        </div>
    

        <div class="grid grid-cols-1 gap-7 lg:grid-cols-2">
              <div class="border border-black/10 dark:border-white/10 rounded-lg mb-2">
            <button type="button" class="w-full flex justify-between items-center px-4 py-3 bg-gray-100 dark:bg-white/10 rounded-t-lg focus:outline-none" data-accordion-target="#accordion-mitra" aria-expanded="true" aria-controls="accordion-mitra" onclick="toggleAccordion('accordion-mitra')">
                <span class="font-semibold text-sm">Data Mitra / Toko</span>
                <svg class="w-4 h-4 transition-transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="accordion-mitra" class="px-4 py-3 bg-white dark:bg-black rounded-b-lg">
                <div>
                    <input type="text" name="id" value="{{ $mitra->id }}" hidden>
                    <div class="space-y-4">
                        <!-- Mita -->
                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                Kode Mitra
                            </label>
                            <input type="text" placeholder="Kode Mitra" class="form-input" name="kode_mitra" id="kode_mitra" value="{{ $mitra->kode_mitra }}" readonly />
                        </div>

                        <!-- Nama Mitra -->
                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                Nama Mitra / Toko
                            </label>
                            <input type="text" placeholder="Nama Mitra atau Toko" class="form-input" name="nama_mitra" id="nama_mitra" value="{{ $mitra->nama_mitra }}" />
                        </div>
                        <!-- Telp Mitra -->
                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                Nomor Telepon Mitra
                            </label>
                            <input type="text" placeholder="Nomor Telepon" class="form-input" name="no_telp_mitra" id="no_telp_mitra" value="{{ $mitra->no_telp_mitra }}" />
                        </div>
                        <!-- Alamat -->
                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                Alamat Mitra/Toko
                            </label>
                            <textarea type="text" class="form-input" placeholder="Alamat Mitra" name="alamat_mitra">{{ $mitra->alamat_mitra }}</textarea>
                        </div>

                        <!-- Provinsi -->

                        <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                            <label class="block mb-1 text-xs text-black/40 dark:text-white/40">
                                Kota
                            </label>
                            <input type="text" id="kota-input" name="id_kota" placeholder="Masukan Nama Kota Mitra " class="form-input" oninput="showRecommendations()" value="{{ $mitra->id_kota }}" />
                            <!-- Dropdown for Recommendations -->
                            <ul id="kota-recommendations" class="absolute w-full mt-1 bg-white dark:bg-dark  dark:border-white/10 p-5  border border-gray-200  shadow-lg hidden max-h-40 overflow-y-auto z-10">
                                <!-- Data recommendations will be injected here -->
                            </ul>

                        </div>
                        <div class="border border-black/10 dark:border-white/10 p-5 rounded-md hidden sm:block">
                            <p class="text-sm font-semibold mb-3">Titik Lokasi</p>
                            <div class="mb-4">
                                <label class="block mb-1">Tempel Link Google Maps</label>
                                <input type="text" id="gmaps-link" placeholder="Paste link Google Maps di sini" class="form-input mt-2 py-2.5 px-4 w-full text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg placeholder:text-black/20 dark:placeholder:text-white/20 focus:border-black dark:focus:border-white/10 focus:ring-0 focus:shadow-none;">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Longitude Field -->
                                <div class="bg-white dark:bg-white/5 border border-black/10 rounded-lg px-5 py-4">
                                    <label for="longitude" class="block mb-1 text-xs font-medium text-black/40 dark:text-white/40">
                                        Longitude
                                    </label>
                                    <input type="text" id="longitude" name="longitude" placeholder="Longitude" value="{{ $mitra->longitude }}" class="form-input w-full text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                                </div>

                                <!-- Latitude Field -->
                                <div class="bg-white dark:bg-white/5 border border-black/10 rounded-lg px-5 py-4">
                                    <label for="latitude" class="block mb-1 text-xs font-medium text-black/40 dark:text-white/40">
                                        Latitude
                                    </label>
                                    <input type="text" id="latitude" name="latitude" placeholder="Latitude" value="{{ $mitra->latitude }}" class="form-input w-full text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                                </div>
                            </div>

                        </div>
                        <!-- Submit Button -->
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <div>
                <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-3 rounded-md mb-5">
                    <div class="">
                        <div class="px-2 py-1 mb-4 flex items-center justify-between">
                            <p class="text-sm font-semibold">Daftar Barang yang Dijual</p>
                            <button type="button"  id="btnTambahProduk" class="btn py-2 px-5 text-[15px]"  >
                                + Tambah Produk
                            </button>
                        </div>
                      <div id="productList" class="space-y-2">
                        @forelse ($penawaran as $index => $row)
                            <div class="flex items-center justify-between border rounded-lg p-3 bg-white dark:bg-white/5 dark:border-white/10 shadow-sm" data-index="{{ $index }}">
                                <!-- Kiri: Hapus + Nama Produk -->
                                <div class="flex items-center gap-3">
                                   <button type="button" class="text-red-600 hover:text-red-800" onclick="removeRow(this)" data-id="{{ $row->id }}">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                           <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6
                            m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z"></path>
                                       </svg>
                                   </button>

                                   
                                    <select class="select2 text-sm w-full " onchange="updateHarga(this)" name="kode_produk[]" required>
                                        <option value="">Pilih Produk</option>
                                        @foreach ($produk as $item)
                                            <option value="{{ $item->kode_produk }}" data-harga="{{ $item->harga }}"
                                                @selected($item->kode_produk == $row->kode_produk)>
                                                {{ $item->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                              <div class="flex items-center gap-2">
                                    <div class="text-sm ">Rp.</div>
                                    <input type="number" name="harga[]"
                                        value="{{ $row->harga ? number_format($row->harga, 0, ',', '.') : '' }}"
                                        oninput="formatCurrency(this)"
                                        class="harga-input text-sm rounded border border-black/10 px-2 py-1 dark:bg-white/5 " style="width: 90px;"
                                        placeholder="0">
                                </div>

                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">Belum ada produk yang ditawarkan.</div>
                        @endforelse
                    </div>

                        {{-- <div class="table-responsive">
                            <table id="productTable" class="table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th width="80%">Nama Produk</th>
                                        <th>Harga Penawaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penawaran as $index => $row)
                                        <tr class="group text-xs border-b border-black/20">
                                            <td class="row-number">{{ $index + 1 }}</td>
                                            <td>
                                                <select class="select2 w-full" name="kode_produk[]"
                                                    onchange="updateHarga(this)">
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($produk as $item)
                                                        <option value="{{ $item->kode_produk }}"
                                                            data-harga="{{ $item->harga }}" @selected($item->kode_produk == $row->kode_produk)>
                                                            {{ $item->nama_produk }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                Rp.
                                                <input type="text" name="harga[]"
                                                    value="{{ number_format($row->harga, 0, ',', '.') }}"
                                                    oninput="formatCurrency(this)" class="form-input harga-input" style="border:none;">
                                            </td>
                                            <td><button type="button" class="text-red-600 hover:text-red-800"
                                                    onclick="removeRow(this)" data-id="{{ $row->id }}">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="noDataRow">
                                            <td colspan="4" class="text-center text-gray-500">Belum ada produk yang
                                                ditawarkan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </form>
   
    @if(session('reload'))
    <script>
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    </script>
    @endif
    <script>
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field')?.focus();
        });
    </script>

    <script>
        // Inisialisasi select2 pada semua select2 yang sudah ada
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Produk",
                width: 'resolve'
            });
        });

        // Fungsi update harga otomatis saat pilih produk
        function updateHarga(selectElem) {
            // ambil parent baris paling atas yang ada class "flex items-center justify-between"
            const rowDiv = selectElem.closest('div.flex.items-center.justify-between');

            if (!rowDiv) return;

            // cari input harga di row ini
            const hargaInput = rowDiv.querySelector('input[name="harga[]"]');

            const selectedOption = selectElem.selectedOptions[0];
            const harga = selectedOption ? selectedOption.getAttribute('data-harga') : 0;

            if (harga && harga != 0) {
                hargaInput.value = '' + formatNumber(harga);
            } else {
                hargaInput.value = '';
            }
        }

        // Format angka jadi Rp. dengan titik ribuan
        function formatNumber(num) {
            return Number(num).toLocaleString('id-ID');
        }

        // Format input harga saat user mengetik
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            if (value === '') {
                input.value = '';
                return;
            }
            input.value = formatNumber(value);
        }

        // Fungsi hapus row produk
        function hapusItem(button) {
            const rowDiv = button.closest('div.flex.items-center.justify-between');
            if (rowDiv) {
                rowDiv.remove();
            }
        }

        // Fungsi tambah row produk baru
        document.getElementById('btnTambahProduk').addEventListener('click', function() {
            const productList = document.getElementById('productList');

            // Buat elemen div baru dengan class dan isi sama seperti row produk
            const newIndex = productList.children.length;

            // Buat elemen div baru
            const div = document.createElement('div');
            div.className = "flex items-center justify-between border rounded-lg p-3 bg-white  dark:bg-white/5 dark:border-white/10 shadow-sm";
            div.setAttribute('data-index', newIndex);

            // Buat inner HTML dari row baru dengan select option dari produk
            // Supaya select option selalu up-to-date, kamu bisa render ulang di JS dengan data produk dari server (kalau ada)
            // Di contoh ini, saya copy langsung dari blade, kamu bisa sesuaikan kalau produk dinamis

            const options = `@foreach ($produk as $item)
                <option value="{{ $item->kode_produk }}" data-harga="{{ $item->harga_jual }}">{{ $item->nama_produk }}</option>
            @endforeach`;

            div.innerHTML = `
                <div class="flex items-center gap-3">
                    <button type="button" onclick="hapusItem(this)" class="text-red-600 hover:text-red-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6
                            m2 0a2 2 0 00-2-2H9a2 2 0 00-2 2h10z"></path>
                        </svg>
                    </button>
                    <select class="select2 w-60" onchange="updateHarga(this)" name="kode_produk[]" required>
                        <option value="">Pilih Produk</option>
                        ${options}
                    </select>
                </div>
                  <div class="flex items-center gap-2">
                                    <div class="text-sm ">Rp.</div>
                                    <input type="text" name="harga[]"
                                        value="" oninput="formatCurrency(this)"
                                        class="harga-input text-sm rounded border border-black/10 px-2 py-1 dark:bg-white/5" style="width: 90px;"
                                        placeholder="0">
                                </div>
                
            `;

            productList.appendChild(div);

            // Re-init select2 pada elemen select baru
            $(div).find('.select2').select2({
                placeholder: "Pilih Produk",
                width: 'resolve'
            });
        });

    </script>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
            if (value) {
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Add thousand separators
            }
            input.value = value;
        }
    </script>

    <script>
        const kotaData = @json($kota);

        async function showRecommendations() {
            const input = document.getElementById('kota-input');
            const dropdown = document.getElementById('kota-recommendations');
            const query = input.value.toLowerCase();

            const filteredKota = kotaData.filter(kota =>
                kota.name.toLowerCase().includes(query)
            );

            dropdown.innerHTML = '';

            if (filteredKota.length > 0 && query !== '') {
                dropdown.classList.remove('hidden');

                filteredKota.forEach(kota => {
                    const li = document.createElement('li');
                    li.classList.add('px-4', 'py-2', 'cursor-pointer', 'hover:bg-gray-100');
                    li.textContent = kota.name;

                    li.onclick = function() {
                        input.value = kota.name;
                        dropdown.classList.add('hidden');
                    };

                    dropdown.appendChild(li);
                });
            } else {
                dropdown.classList.add('hidden');
            }
        }

        document.getElementById('kota-input').addEventListener('input', showRecommendations);

        document.addEventListener('click', function(event) {
            const input = document.getElementById('kota-input');
            const dropdown = document.getElementById('kota-recommendations');
            if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

    <script>
        function removeRow(button) {
            const row = button.closest('div[data-index]');
            const penawaranId = button.getAttribute('data-id');

            if (penawaranId) {
                // Hapus dari database via API
                fetch(`/mitra/produk/delete/${penawaranId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        row.remove();
                        renumberRows();
                        console.log('Data penawaran telah dihapus.');
                    } else {
                        alert('Gagal menghapus data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus.');
                });
            } else {
                // Jika belum tersimpan ke DB, cukup hapus dari UI
                row.remove();
                renumberRows();
            }
        }


        function renumberRows() {
            document.querySelectorAll('#productTable tbody tr .row-number').forEach((td, index) => {
                td.textContent = index + 1;
            });
        }
    </script>

    <script>
        document.getElementById('gmaps-link').addEventListener('input', function () {
            const url = this.value;

            // Kalau link kosong, skip
            if (!url) return;

            fetch('/resolve-maps-link', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ url: url })
            })
            .then(response => response.json())
            .then(data => {
                if (data.latitude && data.longitude) {
                    document.getElementById('latitude').value = data.latitude;
                    document.getElementById('longitude').value = data.longitude;
                } else {
                    console.warn(data.error);
                }
            })
            .catch(error => {
                console.error('Gagal resolve:', error);
            });
        });
    </script>

@endsection
