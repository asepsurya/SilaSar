<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Editor Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        color: #000;
    }

    h1,
    h2,
    h3 {
        font-weight: bold;
    }

    .kop-surat {
        width: 100%;
        text-align: center;
        margin-bottom: 10px;
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
        max-width: 300px;
        word-wrap: break-word;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid black;
        padding: 4px;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .signature-box {
        height: 150px;
        text-align: center;
        vertical-align: bottom;
    }

    .stamp {
        position: absolute;
        width: 120px;
        opacity: 0.7;
        left: 50%;
        transform: translateX(-50%);
    }

    .ttd {
        position: absolute;
        width: 120px;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
    }

    .relative {
        position: relative;
        height: 120px;
        margin-top: 10px;
    }

    @page {
        size: A4;
        margin: 2.54cm;
    }

    .address,
    .address p {
        font-size: 11px;
        font-weight: normal;
        /* jangan bold */
        line-height: 1.4;
        /* atur jarak antar baris */
        margin: 0;
        /* hilangkan margin default <p> */
        padding: 0;
    }

    .table-bordered {
        width: 100%;
        border-collapse: collapse;
    }

    /* Hanya garis horizontal */
    .table-bordered {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* penting biar width bekerja */
    }

    /* Hanya garis horizontal */
    .table-bordered th,
    .table-bordered td {
        padding: 6px 8px;
        font-size: 12px;
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
        border-left: none;
        border-right: none;
    }

    /* Header abu-abu */
    .table-bordered thead th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }

    /* Atur kolom fixed */
    .col-no {
        width: 30px;
    }

    .col-qty {
        width: 40px;
    }

    .col-unit {
        width: 40px;
    }

    .col-empty {
        width: 40px;
    }

    .col-harga {
        width: 100px;
    }

    .col-empty2 {
        width: 40px;
    }

    .col-subtotal {
        width: 110px;
    }

    /* Nama Barang fleksibel + wrap */
    .col-nama {
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Rata teks */
    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    @page {
        margin: 100px 40px 100px 40px;
        /* margin atas kanan bawah kiri */
    }

    footer {
        position: fixed;
        bottom: -60px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 11px;
        color: #666;
    }

    .pagenum:before {
        content: counter(page);
    }

    body {
        font-family: 'DejaVu Sans', sans-serif;

        line-height: 1.4;
    }

    h1,
    h2,
    h3 {
        font-family: 'DejaVu Sans', sans-serif;
        font-weight: bold;
    }
    textarea {
        overflow: hidden; /* sembunyikan scroll */
        resize: none; /* nonaktifkan drag resize */
        width: 100%;
        box-sizing: border-box;
    }
    textarea:focus {
        border: none;
        outline: none;
        box-shadow: none;
    }

</style>

<head>

<body>

    <div class="max-w-[900px] mx-auto p-6 text-black">
        <div class="flex justify-between items-center mb-2">
            <div style="width: 90px; height: 90px; ">
                @php
                $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id);
                $nama = $perusahaan->nama_perusahaan ?? 'Perusahaan tidak ditemukan';
                $logo = $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png');
                @endphp
                <img alt="logo" class="w-full h-auto" height="70" src="{{ $logo }}" width="150" />
            </div>

            <div class="text-right">
                <h1 class="text-2xl font-normal  mb-5"><b>
                        @if(Request('type')== 'kwitansi')
                        NOTA PEMBAYARAN
                        @else
                        INVOICE
                        @endif
                    </b>
                </h1>
                <table class="border border-gray-400 text-sm w-[320px] mx-auto text-black">
                    <thead>
                        <tr class="bg-gray-300 text-center text-xs font-semibold">
                            <th class="border border-gray-400 px-2 py-1">Nomor Nota</th>
                            <th class="border border-gray-400 px-2 py-1">Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center text-xs font-bold">
                            <td class="border border-gray-400 px-2 py-1">{{ $transaksi->kode_transaksi }}</td>
                            <td class="border border-gray-400 px-2 py-1">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-M-y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @php
        $alamat = ($transaksi->perusahaan->alamat) . ', ' .
        'Kelurahan/Desa ' . ucwords(strtolower($transaksi->perusahaan->desa->name)) . ', ' .
        'Kecamatan ' . ucwords(strtolower($transaksi->perusahaan->kecamatan->name)) . ', ' .
        'Kota/Kab. ' . ucwords(strtolower( $transaksi->perusahaan->kota->name));
        @endphp
        <div class="mb-1 text-[11px] leading-[13px]">
            <p class="address">{{ $alamat }}</p>
            <p>P. {{ $transaksi->perusahaan->telp_perusahaan }}</p>
            <p>E. {{ $transaksi->perusahaan->email }}</p>
        </div>
        <div class="mt-2">
            <hr class="garis-atas">
            <hr class="garis-bawah">
        </div>

        <div class="text-[12px] leading-[14px] mb-3">
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-25 font-bold" style="{{ request('type')=='invoice' ? 'width:100px' : '' }}">{{ request('type')=='kwitansi' ? 'Telah Diterima dari' : 'Kepada' }}</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal">{{ $transaksi->mitra->nama_mitra }}</div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="font-bold" style="width: 99px">Alamat</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal capitalize">{{ ucwords(strtolower($transaksi->mitra->id_kota)) }}</div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="font-bold" style="width: 99px">Telepon</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal">{{ $transaksi->mitra->no_telp_mitra }}</div>
            </div>

        </div>
        <p class="text-[12px]">Pembayaran sejumlah</p>
        <table class="table-bordered w-full border-collapse border border-black text-[12px] leading-[14px] mb-2 ">
            <thead>
                <tr class="border border-black bg-white">
                    <th class="border-collapse px-1 text-center w-[30px] font-semibold">No</th>
                    <th class="border-collapse border-black px-1 text-left font-semibold">Nama Barang</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Qty</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold">Unit</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold"></th>
                    <th class="border-collapse border-black px-1 text-right w-[100px] font-semibold">Harga Unit</th>
                    <th class="border-collapse border-black px-1 text-center w-[40px] font-semibold"></th>
                    <th class="border-collapse border-black px-1 text-right w-[110px] font-semibold">Sub Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                $grandTotal = 0;
                $ongkir = $transaksi->ongkir;
                $diskon = $transaksi->diskon;
                @endphp

                {{-- Loop Produk --}}
                @foreach ($transaksi->ProdukTransaksi as $index => $item)
                @php
                $produkTransaksi = $transaksi->ProdukTransaksi->firstWhere('kode_produk', $item->kode_produk);
                $produkjual = $transaksi->penawaran->firstWhere('kode_produk', $item->kode_produk);
                @endphp
                @php
                $total = $item->barang_terjual * $item->penawaran->harga;
                $grandTotal += $total;
                @endphp


                <tr class="group text-xs border-b border-black">
                    <td class="border-collapse border-black px-1 text-center font-normal">{{ $index + 1 }}</td>
                    <td class="border-collapse border-black px-1 font-normal">{{ $item->produk->nama_produk }}</td>
                    <td class="border-collapse border-black px-1 text-center font-normal">{{ $produkTransaksi->barang_terjual ?? '-' }}</td>
                    <td class="border-collapse border-black px-1 text-center font-normal">Pcs</td>
                    <td class="border-collapse border-black px-1   text-right font-normal">Rp.</td>
                    <td class="border-collapse border-black px-1 text-right font-normal">{{ number_format($item->penawaran->harga, 2, ',', '.') }}</td>
                    <td class="border-collapse border-black px-1  text-right font-normal">Rp.</td>
                    <td class="border-collapse border-black px-1 text-right font-normal">{{ number_format($total, 2, ',', '.') }}</td>
                </tr>
                @endforeach
                @if($transaksi->ongkir > 0)
                {{-- Ongkir --}}
                <tr class="group text-xs border-b border-black">
                    <td class="border-collapse border-b border-black px-1 text-right font-normal"></td>
                    <td colspan="2" class="border-collapse border-black px-1 font-normal">Ongkos Kirim</td>
                    <td class="border-collapse border-black px-1 text-right font-normal">Paket</td>
                    <td colspan="3" class="border-collapse border-black px-1 text-right font-normal">Rp.</td>
                    <td colspan="4" class="border-collapse border-black px-1 text-right font-normal">{{ number_format($ongkir, 2, ',', '.') }}</td>
                </tr>
                @endif
                {{-- Diskon --}}
                @if($transaksi->diskon > 0)
                <tr class="group text-xs border-b border-black">
                    <td class="border-b border-black px-1 text-right font-normal"></td>
                    <td colspan="5" class=" border-collapse border-black px-1 font-normal">Discount</td>
                    <td class="border-b border-black px-1 text-right font-normal">Rp.</td>
                    <td colspan="4" class=" border-collapse border-black px-1 text-right font-normal">{{ number_format($diskon, 2, ',', '.') }}</td>
                </tr>
                @endif
                {{-- Grand Total --}}

                <tr class="group text-xs border-b border-black">
                    <td colspan="5"></td>
                    <td class="text-right">TOTAL</td>
                    <td class=" border-collapse border-black px-1 text-right">Rp.</td>
                    <td class=" border-collapse border-black px-1 font-semibold text-right">
                        {{ number_format($grandTotal + $ongkir - $diskon, 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="flex justify-center items-center">
            <table class="w-full border-collapse text-[12px] leading-[14px]">
                <tbody>
                    <tr>
                        <!-- Kolom Penerima -->
                        <td style="height: 180px; vertical-align: top;">
                            <p class="text-[11px] leading-[13px]">
                                {{ $transaksi->perusahaan->keterangan_pembayaran }}
                            </p>

                            <p class="py-2 font-semibold">Catatan</p>

                            <form action="{{ route('transaksi.notes') }}" method="post" id="myForm">
                                @csrf
                                <p class="p-3 border border-black">
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <textarea name="notes" oninput="autoResize(this)" class="w-full" placeholder="Masukan Catatan disini">{{ $transaksi->notes ?? '' }}</textarea>
                                </p>
                            </form>
                            <script>
                            function autoResize(textarea) {
                                textarea.style.height = 'auto'; // reset height
                                textarea.style.height = textarea.scrollHeight + 'px'; // set sesuai content
                            }

                            // Auto-resize saat input
                            document.querySelectorAll('textarea').forEach(textarea => {
                                textarea.addEventListener('input', () => autoResize(textarea));
                                // Resize langsung saat load
                                autoResize(textarea);
                            });
                            </script>
                        </td>

                        <!-- Kolom Hormat Kami -->
                        <td class="text-center align-middle" style="height: 150px; vertical-align: top;">
                            <div class="flex flex-col justify-center items-center h-full">
                                <p class="font-bold mb-2">Hormat Kami</p>

                                <div class="relative flex justify-center items-center" style="height: 80px; width: 180px;">
                                    <!-- Stempel di bawah -->
                                    <img src="{{ optional($perusahaan)->stamp ? asset('storage/' . $perusahaan->stamp) : asset('assets/stamp-default.png') }}" alt="Stempel" class="object-contain absolute z-10" width="140" style="top: 30px; left: 50%; transform: translateX(-50%);" id="stamp" hidden>

                                    <!-- Tanda tangan di atas -->
                                    <img src="{{ optional($perusahaan)->ttd ? asset('storage/' . $perusahaan->ttd) : asset('assets/ttd-default.png') }}" alt="Tanda Tangan" class="object-contain absolute z-20" width="150" style="top: 0; left: 50%; transform: translateX(-50%);" id="signature" hidden>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Baris Garis Tanda Tangan & Nama Perusahaan -->
                    <tr>
                        <td>
                            Dicetak pada : {{ now()->format("d-m-Y") }}
                        </td>
                        <td class="text-center">
                            <div class="mx-auto border-t border-black w-36 mt-2"></div>
                            <p class="mt-2">{{ auth()->user()->perusahaanUser->nama_perusahaan }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let grandTotal = 0;

            // Loop untuk menghitung total per item dan grand total
            document.querySelectorAll('.item-row').forEach((row) => {
                let qty = parseFloat(row.querySelector('.qty').innerText);
                let harga = parseFloat(row.querySelector('.harga').innerText.replace('Rp. ', '').replace('.', '').replace(',', '.'));
                let total = qty * harga;

                // Update Sub Total Harga
                row.querySelector('.sub-total').innerText = 'Rp. ' + total.toLocaleString('id-ID', {
                    style: 'currency'
                    , currency: 'IDR'
                });

                // Update grand total
                grandTotal += total;
            });

            // Update Ongkir
            let ongkir = document.querySelector('.ongkir');

            // Update Grand Total
            let totalAmount = grandTotal + ongkir;
            document.querySelector('.total-amount').innerText = 'Rp. ' + totalAmount.toLocaleString('id-ID', {
                style: 'currency'
                , currency: 'IDR'
            });
        });

    </script>
</body>

</html>
