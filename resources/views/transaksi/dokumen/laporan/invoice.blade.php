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
    @page {
        size: A4;
        margin: 2.54cm;
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
        word-wrap: break-word;
        /* Optionally add a max width for better control */
        max-width: 300px;
        /* Or whatever max-width you want */
    }
</style>

<head>

<body>

    <div class="max-w-[900px] mx-auto p-6 text-black">
        <div class="flex justify-between items-center mb-2">
            <div class="w-36">
                     @php
                        $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id);
                        $nama = $perusahaan->nama_perusahaan ?? 'Perusahaan tidak ditemukan';
                        $logo = $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png');
                @endphp
                <img alt="logo" class="w-full h-auto"
                    height="70" src="{{ $logo }}" width="150" />
            </div>

            <div class="text-right">
                <h1 class="text-2xl font-normal text-center mb-1"><b>
                        INVOICE
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
        <div class="mb-1 text-[11px] leading-[13px]">
            <p class="address">{{ $transaksi->perusahaan->alamat }}</p>
            <p>P. {{ $transaksi->perusahaan->telp_perusahaan }}</p>
            <p>E. {{ $transaksi->perusahaan->email }}</p>
        </div>
        <div class="mt-2">
            <hr class="garis-atas">
            <hr class="garis-bawah">
        </div>

        <div class="text-[12px] leading-[14px] mb-3">
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-25 font-bold">Telah Diterima dari</div>
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
        <p class="text-[12px]">Pembayaran Sejumlah</p>
        <table class="w-full border-collapse border border-black text-[12px] leading-[14px] mb-2 ">
            <thead>
                <tr class="border border-black bg-white">
                    <th class="border border-black px-1 text-center w-[30px] font-semibold">No</th>
                    <th class="border border-black px-1 text-left font-semibold">Nama Barang</th>
                    <th class="border border-black px-1 text-center w-[40px] font-semibold">Qty</th>
                    <th class="border border-black px-1 text-center w-[40px] font-semibold">Unit</th>
                    <th class="border border-black px-1 text-center w-[40px] font-semibold">Rp</th>
                    <th class="border border-black px-1 text-center w-[100px] font-semibold">Harga Unit</th>
                    <th class="border border-black px-1 text-center w-[40px] font-semibold">Rp</th>
                    <th class="border border-black px-1 text-center w-[110px] font-semibold">Sub Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                <!-- Variabel untuk grand total -->
                @foreach ($transaksi->penawaran as $index => $item)
                @php
                $total = $item->barang_terjual * $item->harga;
                $grandTotal += $total;
                @endphp
                <tr class="border border-black">
                    <td class="border border-black px-1 text-center font-normal">
                        {{ intval($index) + 1 }}
                    </td>
                    <td class="border border-black px-1 font-normal">
                        {{ $item->produk->nama_produk }}
                    </td>
                    <td class="border border-black px-1 text-center font-normal">
                        {{ $item->barang_terjual }}
                    </td>
                    <td class="border border-black px-1 text-center font-normal">
                        Pcs
                    </td>
                    <td class="border border-black px-1 text-center font-normal">Rp.</td>
                    <td class="border border-black px-1 text-right font-normal">
                        {{ number_format(floatval($item->harga), 2, ',', '.') }}
                    </td>
                    <td class="border border-black px-1 text-center font-normal">Rp.</td>
                    <td class="border border-black px-1 text-right font-normal">
                        {{ number_format($total, 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                <tr>
                    <td class="border-b border-black px-1 text-right font-normal"></td>
                    <td colspan="2" class="border border-black px-1  font-normal">Ongkos Kirim</td>
                    <td class="border border-black px-1 text-right font-normal">Paket</td>
                    <td colspan="7" class="border border-black px-1 text-right font-normal">
                        {{ $transaksi->ongkir }}
                    </td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td class="border border-black px-1 font-semibold text-right">
                        TOTAL
                    </td>
                    <td class="border border-black px-1 font-semibold text-right">
                        Rp. {{ number_format($grandTotal + $transaksi->ongkir, 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="display: flex; justify-content: center; align-items: center;">
            <table class="w-full border-collapse text-[12px] leading-[14px]">
                <tbody>
                    <tr>
                        <!-- Kolom Penerima -->
                        <td class="" style="height: 180px;">
                            <p class="text-[11px] leading-[13px] ">
                                Pembayaran dilakukan melalui transfer ke no. Rek Mandiri. 131 000 7197603 atas nama Afin
                                Nurfahmi Mufreni
                                Demikian informasi yang dapat kami sampaikan. Terimakasih telah bekerja sama dengan CV
                                INOVATE Corpora
                            </p>
                            <p class="py-2"><strong>Catatan</strong></p>
                            <form action="{{ route('transaksi.notes') }}" method="post" id="myForm">
                                @csrf
                                <p class="p-3 border border-black">

                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <textarea name="notes" oninput="autoResize(this)" class="w-full"
                                        placeholder="Masukan Catatan disini">{{ $transaksi->notes ?? '' }}</textarea>
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
                            </form>
                        </td>

                        <!-- Kolom Hormat Kami -->
                        <td class="text-center align-middle" style="height: 180px;position: relative;">
                            <div class="flex flex-col justify-center items-center h-full">
                                <p class="font-bold">Hormat Kami</p>

                                <div class="relative flex justify-center items-center"
                                    style="height: 20px; width: 180px; margin-top: 8px;">
                                    <!-- Stempel di belakang -->
                                    <img src="{{ asset('assets/images/stamp.png') }}" alt="Stempel" width="140"
                                        class="object-contain absolute z-10"
                                        style="top: -40px; left: 50%;transform: translateX(-30%);" id="stamp" hidden>

                                    <!-- Tanda tangan di atas -->
                                    <img src="{{ asset('assets/images/ttd.png') }}" alt="Tanda Tangan" width="140"
                                        class="object-contain absolute z-20"
                                        style="top: 0; left: 50%;transform: translateX(-50%);" id="signature" hidden>
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
                            <p class="mt-2">(Nama Pengirim)</p>
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
            row.querySelector('.sub-total').innerText = 'Rp. ' + total.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

            // Update grand total
            grandTotal += total;
        });

        // Update Ongkir
        let ongkir = parseFloat(document.querySelector('.ongkir').innerText.replace('Rp. ', '').replace('.', '').replace(',', '.'));

        // Update Grand Total
        let totalAmount = grandTotal + ongkir;
        document.querySelector('.total-amount').innerText = 'Rp. ' + totalAmount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    });
    </script>
</body>

</html>