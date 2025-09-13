 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    body{
        font-family: 'Roboto', sans-serif;
    }
    h1, h2, h3 {
        font-family: 'Poppins', sans-serif;
    }
    @page {
        size: A4;
        margin: 2.54cm;
        background-color: white;
    }

    .garis-atas {
        border: 2px solid black;
        margin: 0;
    }

    .garis-bawah {
        border: 1px solid black;
        margin: 2px 0 10px 0;
    }

    .address {
        word-wrap: break-word;
        /* Optionally add a max width for better control */
        max-width: 300px;
        /* Or whatever max-width you want */
    }

    input:focus {
        outline: none;
        border: none;
    }

    /* Gaya untuk elemen melayang */
    .floating-element {
        position: fixed;
        /* Posisi tetap */
        top: 80px;
        /* Jarak dari atas */
        left: 10px;
        /* Jarak dari kiri */
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        /* Latar belakang transparan */
        color: white;
        border-radius: 5px;
        font-size: 16px;
        z-index: 1000;
        /* Pastikan elemen di atas elemen lainnya */
        display: flex;
        /* Agar tombol close berada di dalam elemen melayang */
        align-items: center;
        justify-content: space-between;
        width: auto;
    }

    .close-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        margin-left: 10px;
    }

    .close-btn:hover {
        color: red;
    }

    .tooltip {
        display: none;
        /* Menyembunyikan tombol hapus secara default */
        position: absolute;
        top: 50%;
        left: 100%;
        /* Tombol akan muncul di luar sisi kanan input */
        transform: translateY(-50%);
    }

    .relative:hover .tooltip {
        display: block;
        /* Menampilkan tombol hapus saat hover */
    }
</style>

@if ($errors->any())
<div class="floating-element">
    <ul>
        <li>
            <h3>Mohon diperhatikan!!</h3>
        </li>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('transaksi.nota.add') }}" method="post" id="myForm">
    @csrf
    <div class="max-w-[900px] mx-auto p-6 text-black">
        <div class="flex justify-between items-center mb-2">
            <div class="w-36">
                    @php
                        $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id);
                        $nama = $perusahaan->nama_perusahaan ?? 'Perusahaan tidak ditemukan';
                        $logo = $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png');
                @endphp
                <img alt="logo" class="w-full h-auto"
                    height="70" src="{{ $logo }}" width="120" />
            </div>

            <div class="text-right">
                <input type="hidden" 
                name="type" 
                value="{{ Request::is('transaksi/invoice*') 
                          ? 'invoice' 
                          : (Request::is('transaksi/nota*') 
                              ? 'nota_konsinyasi' 
                              : (Request::is('transaksi/kwitansi*') 
                                  ? 'nota_pembayaran' 
                                  : '' )) }}">
         
                <h1 class="text-2xl font-normal mb-1"><b>
                        <input type="text" class="w-full text-right" name="judul" placeholder="Nota Konsinyasi" required
                            value="{{ $nota->judul ?? 'NOTA KONSINYASI' ?? old('judul') }}">
                    </b></h1>
                <table class="border border-gray-400 text-sm w-[320px] mx-auto text-black">
                    <thead>
                        <tr class="bg-gray-300 text-center text-xs font-semibold">
                            <th class="border border-gray-400 px-2 py-1">Nomor Nota</th>
                            <th class="border border-gray-400 px-2 py-1">Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center text-xs font-bold">
                            <td class="border border-gray-400 px-2 py-1">
                                <input type="text" class="w-full text-center" name="kode_transaksi"
                                    placeholder="B200511" required value="{{ $id ?? old('kode_transaksi') }}">
                            </td>
                            <td class="border border-gray-400 px-2 py-1">
                                <input type="text" class="w-full text-center flatpickr-input" name="tanggal" placeholder="Tanggal Transaksi"
                                    value="{{ $nota->tanggal ?? old('tanggal') }}" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
     @php
        $alamat = ($perusahaan->alamat ?? '-') . ', ' .
                'Kelurahan/Desa ' . ucwords(strtolower($perusahaan->desa->name ?? '-')) . ', ' .
                'Kecamatan ' . ucwords(strtolower($perusahaan->kecamatan->name ?? '-')) . ', ' .
                'Kota/Kab. ' . ucwords(strtolower( $perusahaan->kota->name ?? '-'));
     @endphp

        <div class="mb-1 text-[11px] leading-[13px]">
            <textarea class="address" name="alamat_company" required
                oninput="autoResize(this)">{{ $alamat ?? old('alamat_company') ?? $nota->alamat_company ?? '' }}</textarea>
            <p><input type="text" class="w-full text-left" name="telp_company" placeholder="P. 0877366644" required
                    value="P : {{ $perusahaan->telp_perusahaan ?? $nota->telp_company ?? old('telp_company') }}"></p>
            <p><input type="text" class="w-full text-left" name="email_company" placeholder="E. example@email.com"
                    value="E : {{ $perusahaan->email ?? $nota->email_company ?? old('email_company') }}" required></p>
        </div>
        <div class="mt-2">
            <hr class="garis-atas">
            <hr class="garis-bawah">
        </div>

        <div class="text-[12px] leading-[14px] mb-3">
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20 font-bold">Kepada</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal"> <input type="text" class="w-full text-left" name="kepada"
                        placeholder="PT.INOMARCO INDONESIA " required value="{{$nota->kepada ?? old('kepada') }}"></td>
                </div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20  font-bold">Alamat</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal capitalize"><input type="text" class="w-full text-left" name="kota"
                        placeholder="Jl.Khoerlun Tanjung No.41 Kota Tasikmalaya" required
                        value="{{$nota->kota ??  old('kota') }}">
                </div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20 font-bold">Telepon</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal"><input type="text" class="w-full text-left" name="telp"
                        value="{{ $nota->telp ?? old('telp') }}" placeholder="082 82x xxxxxxx " required></div>
            </div>
        </div>

        <table class="w-full border-collapse border border-black text-[12px] leading-[14px] mb-2" id="myTable">
            <thead>
                <tr class="border border-black bg-white">
                    <th class="border-collapse border-black px-1 text-center w-[30px] font-semibold">No</th>
                    <th class="border-collapse border-black px-1 text-left font-semibold">Nama Barang</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Qty</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Unit</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Rp</th>
                    <th class="border-collapse border-black px-1 text-center w-[100px] font-semibold">Harga Unit</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Rp</th>
                    <th class="border-collapse border-black px-1 text-center w-[110px] font-semibold">Sub Total Harga</th>

                </tr>
            </thead>
            <tbody>
                @if($nota)
                @foreach($nota->produk as $index => $produk)
                <tr class="border border-black">
                    <td class="border-collapse border-black px-1 text-center">{{ $index + 1 }}</td>
                    <td class="border-collapse border-black px-1">
                        <input type="hidden" value="{{ $produk->id ?? ''}}" name="id_item[]">
                        <div class="relative">
                            <input name="nama_barang[]" class="w-full"
                                value="{{ old('nama_barang.'.$index, $produk->nama_barang) }}"
                                placeholder="Nama Barang atau Produk">
                            <div class="tooltip">
                                <a href="{{ route('transaksi.item.delete',$produk->id) }}"><button type="button"
                                        onclick="deleteRow(this)"
                                        class="bg-red-500 text-white px-2 py-1 rounded">X</button></a>
                            </div>
                        </div>
                    </td>
                    <td class="border-collapse border-black px-1 text-center">
                        <input name="qty[]" class="w-full text-center" value="{{ old('qty.'.$index, $produk->qty) }}"
                            oninput="calculateTotal(this)" placeholder="Qty">
                    </td>
                    <td class="border-collapse border-black px-1 text-center">
                        <input name="unit[]" class="w-full text-center" value="{{ old('unit.'.$index, $produk->unit) }}"
                            placeholder="Pcs">
                    </td>
                    <td class="border-collapse border-black px-1 text-center">
                        Rp.
                    </td>
                    <td class="border-collapse border-black px-1 text-right">
                        <input name="harga[]" class="w-full text-right"
                            value="{{ old('harga.'.$index, number_format($produk->harga, 0, ',', '.')) }}"
                            oninput="formatCurrency(this); calculateTotal(this)" placeholder="Harga">
                    </td>
                    <td class="border-collapse border-black px-1 text-center">
                        Rp.
                    </td>
                    <td class="border-collapse border-black px-1 text-right">
                        <input name="total[]" class="w-full text-right"
                            value="{{ old('total.'.$index, number_format($produk->total, 0, ',', '.')) }}" readonly
                            placeholder="Total">
                    </td>

                </tr>
                @endforeach
                @endif
            </tbody>
            <tr class="border border-black" >
                <td colspan="6"></td>
                <td class="border-collapse border-black px-1 font-semibold text-right">
                    TOTAL
                </td>
                <td class="border-collapse border-black px-1 font-semibold text-right">
                    <input type="text" name="grandtotal" id="grandtotal" class="w-full text-right"
                        value="{{ number_format($nota->grandtotal ?? 0, 0, ',', '.') }}"   oninput="formatCurrency(this); calculateTotal(this)">
                </td>
            </tr>
        </table>
        <p class="text-[11px] leading-[13px] ">
            <textarea name="keterangan" id="autoTextarea" oninput="autoResize(this)" name="keterangan" class="w-full">{{ $note->keterangan ?? $perusahaan->keterangan_pembayaran ?? 'Pembayaran dilakukan melalui transfer ke no. Rekeneing xxxxxxxx atas nama xxxxxxxxx setelah diterima informasi penjualan.' }}
            </textarea>
            <style>
                textarea {
                    overflow: hidden;
                    /* sembunyikan scroll */
                    resize: none;
                    /* nonaktifkan drag resize */
                    width: 100%;
                    box-sizing: border-box;
                }

                textarea:focus {
                    border: none;
                    /* hilangkan border saat fokus */
                    outline: none;
                    /* hilangkan outline biru default */
                    box-shadow: none;
                    /* hilangkan efek shadow jika ada */
                }
            </style>
            <script>
                function autoResize(textarea) {
                    textarea.style.height = 'auto'; // reset height
                    textarea.style.height = textarea.scrollHeight + 'px'; // set sesuai content
                }

                // Optional: inisialisasi tinggi saat halaman load
                window.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('textarea').forEach(autoResize);
                });
            </script>
        </p>
        <table class="w-full border-collapse text-[12px] leading-[14px]">
            <tbody>
                <tr>
                    <!-- Kolom Penerima -->
                    <td class="text-center align-middle" style="height: 180px;">
                        <div class="flex flex-col justify-center items-center h-full">
                            <p class="font-bold">Penerima</p>
                        </div>
                    </td>

                    <!-- Kolom Hormat Kami -->
                    <td class="text-center align-middle" style="height: 150px;">
                        <div class="flex flex-col justify-center items-center h-full">
                            <p class="font-bold">Hormat Kami</p>

                            <div class="relative flex justify-center items-center"
                                style="height: 10px; width: 180px; margin-top: 8px;">
                                <!-- Stempel di belakang -->
                                  <img src="{{ optional($perusahaan)->stamp ? asset('storage/' . $perusahaan->stamp) : asset('assets/stamp-default.png') }}"
                                        alt="Stemple" class="object-contain absolute z-10" width="140"
                                          style="top: -20px; left: 50%; transform: translateX(-30%); display: none;"  id="stamp" hidden="">                 
                                <!-- Tanda tangan di atas -->
                                <img src="{{ optional($perusahaan)->ttd ? asset('storage/' . $perusahaan->ttd) : asset('assets/ttd-default.png') }}"
                                        alt="tanda Tangan" class="object-contain absolute z-20" width="150"
                                         style="top: 0px; left: 50%; transform: translateX(-50%); display: none;"  id="signature" hidden="">
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Baris Garis Tanda Tangan & Nama -->
                <tr>
                    <td class="text-center">
                        <div class="mx-auto border-t border-black w-36 mt-8"></div>
                        <p class="mt-2"></p>
                    </td>
                    <td class="text-center">
                        <div class="mx-auto border-t border-black w-36 mt-8"></div>
                        <p class="mt-2">{{  $nama }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <script>
            // Fungsi untuk menampilkan tooltip saat input diklik
            function showTooltip(inputElement) {
                // Temukan div .tooltip yang berdekatan dengan inputElement
                const tooltip = inputElement.closest('td').querySelector('.tooltip');

                if (tooltip) {
                    tooltip.classList.add('show-tooltip');

                    // Menambahkan event listener untuk menutup tooltip jika area lain di luar input dan tooltip diklik
                    document.addEventListener('click', function (event) {
                        // Pastikan klik bukan di dalam input atau tooltip
                        if (!inputElement.contains(event.target) && !tooltip.contains(event.target)) {
                            tooltip.classList.remove('show-tooltip');
                        }
                    });
                }
            }
        </script>
        {{-- <script>
            @if($nota)
                let getnomor = @json(optional($nota->where('kode_transaksi', $id))->count() ?: 0);

                // Jika ada lebih dari satu baris, rowCount akan bertambah 1, jika tidak ada baris, rowCount dimulai dari 1
                let rowCount = getnomor > 1 ? getnomor : 1;
            @else
                let rowCount = 0
            @endif
            let grandTotal = 0; // Total keseluruhan

            // Fungsi untuk menambah baris
            function addRow() {
                const table = document.getElementById('myTable');
                const tbody = table.getElementsByTagName('tbody')[0];

                // Buat baris baru
                const newRow = document.createElement('tr');

                // Tentukan nomor baris baru
                const newRowIndex = tbody.rows.length + 1; // Ini akan menentukan nomor urut berdasarkan jumlah baris di tbody

                // Tambahkan sel ke baris baru dengan input
                newRow.innerHTML = `

                    <td class="border border-black px-1 text-center font-normal">${newRowIndex}</td>
                    <td class="border border-black px-1 font-normal">
                         <div class="relative">
                                <input name="nama_barang[]" class="w-full"
                                    placeholder="Nama Barang atau Produk"  onclick="showTooltip(this)">
                                    <div class="tooltip">
                                        <button type="button" onclick="deleteRow(this)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                                    </div>
                                </div>
                    </td>
                    <td class="border border-black px-1 text-center font-normal"><input name="qty[]" class="w-full text-center" oninput="calculateTotal(this)" placeholder="Qty"></td>
                    <td class="border border-black px-1 text-center font-normal"><input name="unit[]" class="w-full text-center" placeholder="Pcs"></td>
                    <td class="border border-black px-1 text-center font-normal">Rp.</td>
                    <td class="border border-black px-1 text-right font-normal"><input name="harga[]" placeholder="Harga" class="w-full text-right" oninput="formatCurrency(this); calculateTotal(this)"></td>
                    <td class="border border-black px-1 text-center font-normal">Rp.</td>
                    <td class="border border-black px-1 text-right font-normal"><input name="total[]" class="w-full text-right" placeholder="Total" readonly></td>
                `;

                // Tambahkan baris ke dalam tabel
                tbody.appendChild(newRow);

                // Increment nomor baris secara internal
                rowCount++;
            }

            // Fungsi untuk format mata uang
            function formatCurrency(input) {
                let value = input.value.replace(/\D/g, ''); // Menghapus karakter selain angka
                if (value) {
                    input.value = '' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format dengan titik ribuan
                }
            }

            // Fungsi untuk menghitung total
            function calculateTotal(input) {
                const row = input.closest('tr');
                const qty = row.querySelector('input[name="qty[]"]').value;
                let harga = row.querySelector('input[name="harga[]"]').value;

                // Menghilangkan simbol 'Rp.' dan titik dari harga
                harga = harga.replace(/[^0-9]/g, '');

                // Validasi untuk memastikan qty dan harga adalah angka
                if (!isNaN(qty) && qty !== '' && !isNaN(harga) && harga !== '') {
                    const total = parseFloat(qty) * parseFloat(harga);
                    row.querySelector('input[name="total[]"]').value = formatCurrencyToDisplay(total);
                } else {
                    row.querySelector('input[name="total[]"]').value = '';
                }

                // Perbarui grand total
                updateGrandTotal();
            }

            // Fungsi untuk memformat total menjadi tampilan uang
            function formatCurrencyToDisplay(amount) {
                // Format hanya angka dengan pemisah ribuan, tanpa desimal
                return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Fungsi untuk menghitung grand total
            function updateGrandTotal() {
                grandTotal = 0;
                const rows = document.querySelectorAll('#myTable tbody tr');
                rows.forEach(row => {
                    const total = row.querySelector('input[name="total[]"]');
                    if (total && total.value) {
                        const totalValue = total.value.replace(/[^0-9]/g, ''); // Hapus simbol Rp.
                        grandTotal += parseFloat(totalValue);
                    }
                });

                // Menampilkan grand total
                document.getElementById('grandtotal').value = '' + formatCurrencyToDisplay(grandTotal);
            }

            // Fungsi untuk menghapus baris
            function deleteRow(button) {
                const row = button.closest('tr');
                row.remove(); // Menghapus baris yang terkait dengan tombol Hapus
                updateGrandTotal(); // Perbarui grand total setelah baris dihapus
            }

            // Menambahkan baris pertama secara otomatis saat halaman dimuat
            @if($nota)
            @else
            document.addEventListener('DOMContentLoaded', function() {
                addRow(); // Menambah 1 baris secara otomatis
            });
            @endif
        </script> --}}
        <script>
            @php

            // Ambil data lama dari Laravel
            $id_item = old('id_item', []);
            $namaBarang = old('nama_barang', []);
            $qty = old('qty', []);
            $unit = old('unit', []);
            $harga = old('harga', []);
            $total = old('total', []);
        @endphp

        // Menambahkan baris berdasarkan data lama
        document.addEventListener('DOMContentLoaded', function() {
            let data = {
                id_item: @json($id_item),
                nama_barang: @json($namaBarang),
                qty: @json($qty),
                unit: @json($unit),
                harga: @json($harga),
                total: @json($total)
            };

            // Jika ada data lama, tambahkan baris-baris tersebut
            data.nama_barang.forEach((item, index) => {
                addRow(item, data.qty[index], data.unit[index], data.harga[index], data.total[index]);
            });
        });

        // Fungsi untuk menambah baris
        function addRow(namaBarang = '', qty = '', unit = '', harga = '', total = '') {
            const table = document.getElementById('myTable');
            const tbody = table.getElementsByTagName('tbody')[0];

            // Buat baris baru
            const newRow = document.createElement('tr');
            newRow.classList.add('border', 'border-black');
            

            // Tentukan nomor baris baru
            const newRowIndex = tbody.rows.length + 1;

            // Tambahkan sel ke baris baru dengan input dan data lama
            newRow.innerHTML = `
                <td class="border-collapse border-black px-1 text-center font-normal">${newRowIndex}</td>
                <td class="border-collapse border-black px-1 font-normal">
                    <div class="relative">
                        <input name="nama_barang[]" class="w-full"
                            placeholder="Nama Barang atau Produk" onclick="showTooltip(this)" value="${namaBarang}">
                        <div class="tooltip">
                            <button type="button" onclick="deleteRow(this)" class="bg-red-500 text-white px-2 py-1 rounded">X</button>
                        </div>
                    </div>
                </td>
                <td class="border-collapse border-black px-1 text-center font-normal">
                    <input name="qty[]" class="w-full text-center" oninput="calculateTotal(this)" placeholder="Qty" value="${qty}">
                </td>
                <td class="border-collapse border-black px-1 text-center font-normal">
                    <input name="unit[]" class="w-full text-center" placeholder="Pcs" value="${unit}">
                </td>
                <td class="border-collapse border-black px-1 text-center font-normal">Rp.</td>
                <td class="border-collapse border-black px-1 text-right font-normal">
                    <input name="harga[]" placeholder="Harga" class="w-full text-right" oninput="formatCurrency(this); calculateTotal(this)" value="${harga}">
                </td>
                <td class="border-collapse border-black px-1 text-center font-normal">Rp.</td>
                <td class="border-collapse border-black px-1 text-right font-normal">
                    <input name="total[]" class="w-full text-right" placeholder="Total" readonly value="${total}">
                </td>
            `;

            // Tambahkan baris ke dalam tabel
            tbody.appendChild(newRow);
        }

        // Fungsi untuk format mata uang
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, ''); // Menghapus karakter selain angka
            if (value) {
                input.value = '' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format dengan titik ribuan
            }
        }

        // Fungsi untuk menghitung total
        function calculateTotal(input) {
            const row = input.closest('tr');
            const qty = row.querySelector('input[name="qty[]"]').value;
            let harga = row.querySelector('input[name="harga[]"]').value;

            // Menghilangkan simbol 'Rp.' dan titik dari harga
            harga = harga.replace(/[^0-9]/g, '');

            // Validasi untuk memastikan qty dan harga adalah angka
            if (!isNaN(qty) && qty !== '' && !isNaN(harga) && harga !== '') {
                const total = parseFloat(qty) * parseFloat(harga);
                row.querySelector('input[name="total[]"]').value = formatCurrencyToDisplay(total);
            } else {
                row.querySelector('input[name="total[]"]').value = '';
            }

            // Perbarui grand total
            updateGrandTotal();
        }

        // Fungsi untuk memformat total menjadi tampilan uang
        function formatCurrencyToDisplay(amount) {
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Fungsi untuk menghitung grand total
        function updateGrandTotal() {
            let grandTotal = 0;
            const rows = document.querySelectorAll('#myTable tbody tr');
            rows.forEach(row => {
                const total = row.querySelector('input[name="total[]"]');
                if (total && total.value) {
                    const totalValue = total.value.replace(/[^0-9]/g, ''); // Hapus simbol Rp.
                    grandTotal += parseFloat(totalValue);
                }
            });

            // Menampilkan grand total
            document.getElementById('grandtotal').value = '' + formatCurrencyToDisplay(grandTotal);
        }

        // Fungsi untuk menghapus baris
        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove(); // Menghapus baris yang terkait dengan tombol Hapus
            updateGrandTotal(); // Perbarui grand total setelah baris dihapus
        }
        @if($nota)
            @else
            document.addEventListener('DOMContentLoaded', function() {
                addRow(); // Menambah 1 baris secara otomatis
            });
            @endif
        </script>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
    const closeButton = document.getElementById("closeButton");
    const floatingElement = document.getElementById("floatingElement");

    // Pastikan elemen ditemukan sebelum menambahkan event listener
    if (closeButton) {
        closeButton.addEventListener("click", function() {
            // Menyembunyikan elemen melayang saat tombol close diklik
            floatingElement.style.display = "none";
        });
    } else {
        console.log("Tombol Close tidak ditemukan!");
    }
});

        </script>
</form>
</div>
