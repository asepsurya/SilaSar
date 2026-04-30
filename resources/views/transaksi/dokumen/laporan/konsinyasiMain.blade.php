 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body{
        font-family: 'Inter', sans-serif;
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
        white-space: pre-wrap;
        overflow: hidden;
        resize: none;
        height: auto;
        line-height: 1.2;
    }

    /* PDF Optimization */
    @media print {
        .paper {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 12mm !important;
        }
        .no-print {
            display: none !important;
        }
    }

    table {
        border-collapse: collapse !important;
    }

    .border-thin {
        border: 0.5px solid black !important;
    }

    input, textarea {
        background: transparent;
        border: none;
        outline: none;
    }

    input:focus, textarea:focus {
        outline: none;
        border: none;
        box-shadow: none;
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
        <!-- Header Table for PDF Compatibility -->
        <table class="w-full mb-4 border-none">
            <tr>
                <!-- Left Header: Logo & Company Info (50%) -->
                <td class="w-1/2 align-top text-left pr-4">
                    <div class="w-36 mb-3">
                        @php
                            $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id);
                            $nama = $perusahaan->nama_perusahaan ?? 'Perusahaan tidak ditemukan';
                            $logo = $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png');
                        @endphp
                        <img alt="logo" class="w-full h-auto" height="70" src="{{ $logo }}" width="120" />
                    </div>
                    
                    @php
                        $alamat = ($perusahaan->alamat ?? '-'). ', ' .
                        'Kelurahan/Desa ' . ucwords(strtolower($perusahaan->desa->name ?? '-')) . ', ' .
                        'Kecamatan ' . ucwords(strtolower($perusahaan->kecamatan->name ?? '-')) . ', ' .
                        'Kota/Kab. ' . ucwords(strtolower( $perusahaan->kota->name ?? '-'));
                    @endphp
                    <div class="text-[11px] leading-[13px] space-y-0.5">
                        <textarea class="address w-full font-medium" name="alamat_company" required oninput="autoResize(this)">{{ $alamat ?? old('alamat_company') ?? $nota->alamat_company ?? '' }}</textarea>
                        <div class="flex items-center gap-1">
                            <span class="font-bold w-4">P</span>
                            <span>:</span>
                            <input type="text" class="flex-1 text-left" name="telp_company" placeholder="0877366644" required value="{{ str_replace(['P : ', 'P:'], '', $nota->telp_company ?? $perusahaan->telp_perusahaan ?? old('telp_company')) }}">
                        </div>
                        <div class="flex items-center gap-1">
                            <span class="font-bold w-4">E</span>
                            <span>:</span>
                            <input type="text" class="flex-1 text-left" name="email_company" placeholder="example@email.com" value="{{ str_replace(['E : ', 'E:'], '', $nota->email_company ?? $perusahaan->email ?? old('email_company')) }}" required>
                        </div>
                    </div>
                </td>

                <!-- Right Header: Title & Meta Info (50%) -->
                <td class="w-1/2 align-top text-right">
                    <h1 class="text-2xl font-bold mb-4">
                        <input type="text" name="judul" value="{{ 
                            $nota->judul 
                                ?? (Request::is('transaksi/invoice*') ? 'INVOICE' 
                                : (Request::is('transaksi/nota*') ? 'NOTA KONSINYASI' 
                                : (Request::is('transaksi/kwitansi*') ? 'NOTA PEMBAYARAN' 
                                : ''))) 
                        }}" class="form-input w-full text-right font-bold uppercase"/>
                    </h1>
                    <input type="hidden" name="type" value="{{ Request::is('transaksi/invoice*') 
                                            ? 'invoice' 
                                            : (Request::is('transaksi/nota*') 
                                                ? 'nota_konsinyasi' 
                                                : (Request::is('transaksi/kwitansi*') 
                                                    ? 'nota_pembayaran' 
                                                    : '' )) }}">

                    <table class="border-thin text-sm w-[300px] ml-auto text-black">
                        <thead>
                            <tr class="bg-gray-100 text-center text-[10px] font-bold uppercase">
                                <th class="border-thin px-2 py-1">Nomor Nota</th>
                                <th class="border-thin px-2 py-1">Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center text-xs font-bold">
                                <td class="border-thin px-2 py-1">
                                    <input type="text" class="w-full text-center" name="kode_transaksi" placeholder="B200511" required value="{{ $id ?? old('kode_transaksi') }}">
                                </td>
                                <td class="border-thin px-2 py-1">
                                    <input type="text" class="w-full text-center flatpickr-input" name="tanggal" placeholder="25-jun-2025" value="{{ $nota->tanggal ?? old('tanggal') }}" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <div class="mt-2">
            <hr class="garis-atas">
            <hr class="garis-bawah">
        </div>
        <div class="text-[12px] leading-[14px] mb-3 space-y-1">
            <div class="flex items-center text-[11px] leading-[13px]">
                <div class="w-32 font-bold">Telah Diterima dari</div>
                <div class="w-4 text-center">:</div>
                <div class="flex-1 font-normal">
                    <input type="text" class="w-full text-left" name="kepada" placeholder="Nama Penerima" required value="{{$nota->kepada ?? old('kepada') }}">
                </div>
            </div>
            <div class="flex items-center text-[11px] leading-[13px]">
                <div class="w-32 font-bold">Alamat</div>
                <div class="w-4 text-center">:</div>
                <div class="flex-1 font-normal capitalize">
                    <input type="text" class="w-full text-left" name="kota" placeholder="Alamat Lengkap" required value="{{$nota->kota ??  old('kota') }}">
                </div>
            </div>
            <div class="flex items-center text-[11px] leading-[13px]">
                <div class="w-32 font-bold">Telepon</div>
                <div class="w-4 text-center">:</div>
                <div class="flex-1 font-normal">
                    <input type="text" class="w-full text-left" name="telp" value="{{ $nota->telp ?? old('telp') }}" placeholder="082xxxxxxx" required>
                </div>
            </div>
        </div>

        <p class="text-[12px]">Pembayaran sejumlah</p>
        <table class="w-full border-collapse border-thin text-[12px] leading-[14px] mb-2" id="myTable">
            <thead>
                <tr class="bg-gray-50 border-b border-black">
                    <th class="border-thin px-2 py-2 text-center w-[35px] font-bold uppercase tracking-tight">No</th>
                    <th class="border-thin px-2 py-2 text-left font-bold uppercase tracking-tight">Nama Barang / Deskripsi</th>
                    <th class="border-thin px-2 py-2 text-center w-[50px] font-bold uppercase tracking-tight">Qty</th>
                    <th class="border-thin px-2 py-2 text-center w-[50px] font-bold uppercase tracking-tight">Unit</th>
                    <th class="border-thin px-2 py-2 text-center w-[40px] font-bold uppercase tracking-tight">Rp</th>
                    <th class="border-thin px-2 py-2 text-center w-[110px] font-bold uppercase tracking-tight">Harga Satuan</th>
                    <th class="border-thin px-2 py-2 text-center w-[40px] font-bold uppercase tracking-tight">Rp</th>
                    <th class="border-thin px-2 py-2 text-center w-[120px] font-bold uppercase tracking-tight">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @if($nota)
                @foreach($nota->produk as $index => $produk)
                <tr class="border-b border-black">
                    <td class="border-thin px-2 py-1.5 text-center text-gray-500">{{ $index + 1 }}</td>
                    <td class="border-thin px-2 py-1.5">
                        <input type="hidden" value="{{ $produk->id ?? ''}}" name="id_item[]">
                        <div class="relative group">
                            <input name="nama_barang[]" class="w-full font-medium" value="{{ old('nama_barang.'.$index, $produk->nama_barang) }}" placeholder="Nama Barang atau Produk">
                            <div class="tooltip no-print">
                                <a href="{{ route('transaksi.item.delete',$produk->id) }}" onclick="return confirm('Hapus item ini?')">
                                    <button type="button" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 shadow-sm transition-all">
                                        <i class="ph ph-x text-xs"></i>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="border-thin px-2 py-1.5">
                        <input name="qty[]" class="w-full text-center" value="{{ old('qty.'.$index, $produk->qty) }}" oninput="calculateTotal(this)" placeholder="0">
                    </td>
                    <td class="border-thin px-2 py-1.5">
                        <input name="unit[]" class="w-full text-center uppercase" value="{{ old('unit.'.$index, $produk->unit) }}" placeholder="Pcs">
                    </td>
                    <td class="border-thin px-2 py-1.5 text-center text-gray-400">Rp.</td>
                    <td class="border-thin px-2 py-1.5">
                        <input name="harga[]" class="w-full text-right" value="{{ old('harga.'.$index, number_format($produk->harga, 0, ',', '.')) }}" oninput="formatCurrency(this); calculateTotal(this)" placeholder="0">
                    </td>
                    <td class="border-thin px-2 py-1.5 text-center text-gray-400">Rp.</td>
                    <td class="border-thin px-2 py-1.5">
                        <input name="total[]" class="w-full text-right font-bold" value="{{ old('total.'.$index, number_format($produk->total, 0, ',', '.')) }}" readonly placeholder="0">
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tr>
                <td colspan="5" class="border-l border-white"></td>
                <td class="border-thin px-2 py-2 font-bold text-right uppercase tracking-wider bg-gray-50">
                    TOTAL
                </td>
                <td class="border-thin px-2 py-2 font-bold text-center bg-gray-50"> Rp. </td>
                <td class="border-thin px-2 py-2 font-bold text-right bg-gray-50">
                    <input type="text" name="grandtotal" id="grandtotal" class="w-full text-right font-bold" value="{{ number_format($nota->grandtotal ?? 0, 0, ',', '.') }}">
                </td>
            </tr>
        </table>

        <div style="display: flex; justify-content: center; align-items: center;">
            <table class="w-full border-collapse text-[12px] leading-[14px]">
                <tbody>
                    <tr>
                        <!-- Kolom Penerima -->
                        <td width="90%" class="" style="height: 180px;">
                            <p class="text-[11px] leading-[13px] ">
                                <textarea  id="autoTextarea" oninput="autoResize(this)" name="keterangan" class="w-full">{{ $nota->keterangan ?? $perusahaan->keterangan_pembayaran ?? 'Pembayaran dilakukan melalui transfer ke no. Rekeneing xxxxxxxx atas nama xxxxxxxxx setelah diterima informasi penjualan.' }}

                                </textarea>
                            </p>
                            <p class="py-2"><strong>Catatan</strong></p>
                            <p class="p-3 border border-black">
                                <input type="hidden" name="id" value="{{ $id }}">
                                <textarea name="notes" oninput="autoResize(this)" class="w-full" placeholder="Masukan Catatan disini">{{ $nota->notes ?? '' }}</textarea>
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

                        </td>

                        <!-- Kolom Hormat Kami -->
                        <td class="text-center align-middle" style="height: 150px;">
                            <div class="flex flex-col justify-center items-center h-full">
                                <p class="font-bold">Hormat Kami</p>

                                <div class="relative flex justify-center items-center" style="height: 10px; width: 180px; margin-top: 8px;">
                                    <!-- Stempel di belakang -->
                                    <img src="{{ optional($perusahaan)->stamp ? asset('storage/' . $perusahaan->stamp) : asset('assets/stamp-default.png') }}" alt="Stemple" class="object-contain absolute z-10" width="140" style="top: -20px; left: 50%; transform: translateX(-30%); display: none;" id="stamp" hidden="">
                                    <!-- Tanda tangan di atas -->
                                    <img src="{{ optional($perusahaan)->ttd ? asset('storage/' . $perusahaan->ttd) : asset('assets/ttd-default.png') }}" alt="tanda Tangan" class="object-contain absolute z-20" width="150" style="top: 0px; left: 50%; transform: translateX(-50%); display: none;" id="signature" hidden="">
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Baris Garis Tanda Tangan & Nama -->
                    <tr>
                        <td class="">
                            Dicetak pada : {{ now()->format("d-m-Y") }}
                        </td>
                        <td class="text-center">
                            <div class="mx-auto border-t border-black w-36 mt-2"></div>
                            <p class="mt-2">{{ $perusahaan->nama_perusahaan }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>

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
                id_item: @json($id_item)
                , nama_barang: @json($namaBarang)
                , qty: @json($qty)
                , unit: @json($unit)
                , harga: @json($harga)
                , total: @json($total)
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
            newRow.classList.add('border-b', 'border-black');
            // Tentukan nomor baris baru
            const newRowIndex = tbody.rows.length + 1;

            // Tambahkan sel ke baris baru dengan input dan data lama
            newRow.innerHTML = `
                <td class="border-thin px-2 py-1.5 text-center text-gray-500 font-normal">${newRowIndex}</td>
                <td class="border-thin px-2 py-1.5 font-normal">
                    <div class="relative group">
                        <input name="nama_barang[]" class="w-full font-medium"
                            placeholder="Nama Barang atau Produk" onclick="showTooltip(this)" value="${namaBarang}" required>
                        <div class="tooltip no-print">
                            <button type="button" onclick="deleteRow(this)" class="bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center hover:bg-red-600 shadow-sm transition-all">
                                <i class="ph ph-x text-xs"></i>
                            </button>
                        </div>
                    </div>
                </td>
                <td class="border-thin px-2 py-1.5 text-center font-normal">
                    <input name="qty[]" class="w-full text-center" oninput="calculateTotal(this)" placeholder="0" value="${qty}">
                </td>
                <td class="border-thin px-2 py-1.5 text-center font-normal">
                    <input name="unit[]" class="w-full text-center uppercase" placeholder="Pcs" value="${unit}" required>
                </td>
                <td class="border-thin px-2 py-1.5 text-center text-gray-400 font-normal">Rp.</td>
                <td class="border-thin px-2 py-1.5 text-right font-normal">
                    <input name="harga[]" placeholder="0" class="w-full text-right" oninput="formatCurrency(this); calculateTotal(this)" value="${harga}" required>
                </td>
                <td class="border-thin px-2 py-1.5 text-center text-gray-400 font-normal">Rp.</td>
                <td class="border-thin px-2 py-1.5 text-right font-normal">
                    <input name="total[]" class="w-full text-right font-bold" placeholder="0" readonly value="${total}" required>
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
