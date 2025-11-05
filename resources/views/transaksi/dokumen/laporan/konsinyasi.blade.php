<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Editor Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.4;
        }
        h1, h2, h3 {
            font-family: 'DejaVu Sans', sans-serif;
            font-weight: bold;
        }
        .kop-surat { width: 100%; text-align: center; margin-bottom: 10px; }
        .garis-atas { border: 2px solid black; margin: 0; }
        .garis-bawah { border: 1px solid black; margin: 2px 0 10px 0; }
        .address, .address p {
            max-width: 300px;
            word-wrap: break-word;
            font-size: 11px;
            font-weight: normal;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        .table-bordered th, .table-bordered td {
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            border-left: none;
            border-right: none;
            padding: 6px 8px;
            font-size: 12px;
        }
        .table-bordered thead th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .col-no { width: 30px; }
        .col-qty { width: 40px; }
        .col-unit { width: 40px; }
        .col-empty { width: 40px; }
        .col-harga { width: 100px; }
        .col-empty2 { width: 40px; }
        .col-subtotal { width: 110px; }
        .col-nama { word-wrap: break-word; word-break: break-word; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-box { height: 150px; text-align: center; vertical-align: bottom; }
        .stamp { position: absolute; width: 120px; opacity: 0.7; left: 50%; transform: translateX(-50%); }
        .ttd { position: absolute; width: 120px; top: 20px; left: 50%; transform: translateX(-50%); }
        .relative { position: relative; height: 120px; margin-top: 10px; }
        @page { size: A4; margin: 100px 40px 100px 40px; }
        footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .pagenum:before { content: counter(page); }
    </style>
</head>
<body>
    <div class="max-w-[900px] mx-auto p-6 text-black">
        <div class="flex justify-between items-start mb-4">
            <!-- Logo & Alamat Perusahaan -->
            <div class="flex flex-col" style="width: 50%;">
                <div style="width: 90px; height: 90px;" class="mb-2">
                    @php
                        $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id);
                        $logo = $perusahaan->logo ? asset('storage/' . $perusahaan->logo) : asset('assets/default_logo.png');
                    @endphp
                    <img alt="logo" class="w-full h-auto" src="{{ $logo }}" />
                </div>
                @php
                    $alamat = ($perusahaan->alamat ?? '') . ', ' .
                              'Kelurahan/Desa ' . ($perusahaan->desa->name ?? '-') . ', ' .
                              'Kecamatan ' . ($perusahaan->kecamatan->name ?? '-') . ', ' .
                              'Kota/Kab. ' . ($perusahaan->kota->name ?? '-');
                @endphp
                <div class="text-[11px] leading-[13px]">
                    <p class="address">{{ $alamat }}</p>
                    <p>P. {{ auth()->user()->perusahaanUser->telp_perusahaan ?? '-' }}</p>
                    <p>E. {{ auth()->user()->perusahaanUser->email ?? '-' }}</p>
                </div>
            </div>
            <!-- Judul & Info Nota -->
            <div class="text-right" style="width: 50%;">
                <h1 class="text-2xl font-bold mb-3">NOTA KONSINYASI</h1>
                <table class="border border-gray-400 text-sm w-full mx-auto text-black">
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
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-M-y') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-2">
            <hr class="garis-atas">
            <hr class="garis-bawah">
        </div>
        <div class="text-[12px] leading-[14px] mb-3">
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20 font-medium">Kepada</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal">{{ $transaksi->mitra->nama_mitra }}</div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20 font-medium">Alamat</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal capitalize">{{ ucwords(strtolower($transaksi->mitra->id_kota)) }}</div>
            </div>
            <div class="flex space-x-2 text-[11px] leading-[13px]">
                <div class="w-20 font-bold">Telepon</div>
                <div class="w-1">:</div>
                <div class="flex-1 font-normal">{{ $transaksi->mitra->no_telp_mitra }}</div>
            </div>
        </div>
        <table class="table-bordered w-full border-collapse border border-black text-[12px] leading-[14px] mb-2">
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
                @foreach ($transaksi->ProdukTransaksi as $index => $item)
                    @php
                        $total = $item->barang_keluar * $item->penawaran->harga;
                        $grandTotal += $total;
                    @endphp
                    <tr class="group text-xs border-b border-black">
                        <td class="border-collapse border-black px-1 text-center font-normal">{{ $index + 1 }}</td>
                        <td class="border-collapse border-black px-1 font-normal">{{ $item->produk->nama_produk }}</td>
                        <td class="border-collapse border-black px-1 text-center font-normal">{{ $item->barang_keluar }}</td>
                        <td class="border-collapse border-black px-1 text-center font-normal">Pcs</td>
                        <td class="border-collapse border-black px-1 text-right font-normal">Rp.</td>
                        <td class="border-collapse border-black px-1 text-right font-normal">{{ number_format($item->penawaran->harga, 2, ',', '.') }}</td>
                        <td class="border-collapse border-black px-1 text-right font-normal">Rp.</td>
                        <td class="border-collapse border-black px-1 text-right font-normal">{{ number_format($total, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                @if($transaksi->ongkir > 0)
                <tr class="group text-xs border-b border-black">
                    <td class="border-collapse border-b border-black px-1 text-right font-normal"></td>
                    <td colspan="2" class="border-collapse border-black px-1 font-normal">Ongkos Kirim</td>
                    <td class="border-collapse border-black px-1 text-right font-normal">Paket</td>
                    <td colspan="3" class="border-collapse border-black px-1 text-right font-normal">Rp.</td>
                    <td colspan="4" class="border-collapse border-black px-1 text-right font-normal">{{ number_format($transaksi->ongkir, 2, ',', '.') }}</td>
                </tr>
                @endif
                @if($transaksi->diskon > 0)
                <tr class="group text-xs border-b border-black">
                    <td class="border-b border-black px-1 text-right font-normal"></td>
                    <td colspan="5" class="border-collapse border-black px-1 font-normal">Discount</td>
                    <td class="border-b border-black px-1 text-right font-normal">Rp.</td>
                    <td colspan="4" class="border-collapse border-black px-1 text-right font-normal">{{ number_format($diskon, 2, ',', '.') }}</td>
                </tr>
                @endif
                <tr class="group text-xs border-b border-black">
                    <td colspan="5"></td>
                    <td class="text-right">TOTAL</td>
                    <td class="border-collapse border-black px-1 text-right">Rp.</td>
                    <td class="border-collapse border-black px-1 font-semibold text-right">
                        {{ number_format($grandTotal + $ongkir - $diskon, 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="text-[11px] leading-[13px]">
            {{ $perusahaan->keterangan_pembayaran }}
        </p>
        <table class="w-full border-collapse text-[12px] leading-[14px]">
            <tbody>
                <tr>
                    <td class="text-center align-middle" style="height: 180px;">
                        <div class="flex flex-col justify-center items-center h-full">
                            <p class="font-bold">Penerima</p>
                        </div>
                    </td>
                    <td class="text-center align-middle" style="height: 150px;">
                        <div class="flex flex-col justify-center items-center h-full">
                            <p class="font-bold">Hormat Kami</p>
                            <div class="relative flex justify-center items-center" style="height: 10px; width: 180px; margin-top: -20px;">
                                <img src="{{ optional($perusahaan)->stamp ? asset('storage/' . $perusahaan->stamp) : asset('assets/stamp-default.png') }}"
                                    alt="Stemple" class="object-contain absolute z-10" width="140"
                                    style="top: -20px; left: 50%; transform: translateX(-30%); display: none;" id="stamp" hidden="">
                                <img src="{{ optional($perusahaan)->ttd ? asset('storage/' . $perusahaan->ttd) : asset('assets/ttd-default.png') }}"
                                    alt="tanda Tangan" class="object-contain absolute z-20"
                                    style="top: 0px; left: 50%; transform: translateX(-50%); display: none;margin-top:-30px;" id="signature" hidden="">
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <div class="mx-auto border-t border-black w-36 mt-8"></div>
                        <p class="mt-2"></p>
                    </td>
                    <td class="text-center">
                        <div class="mx-auto border-t border-black w-36 mt-8"></div>
                        <p class="mt-2">{{ auth()->user()->perusahaanUser->nama_perusahaan }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
