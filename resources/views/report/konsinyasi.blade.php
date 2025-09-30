<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota Konsinyasi</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }
        h1, h2, h3 {
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
        .table-bordered th, .table-bordered td {
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
          .address, .address p {
            font-size: 11px;
            font-weight: normal;   /* jangan bold */
            line-height: 1.4;      /* atur jarak antar baris */
            margin: 0;             /* hilangkan margin default <p> */
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
        table-layout: fixed; /* penting biar width bekerja */
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
    .col-no { width: 30px; }
    .col-qty { width: 40px; }
    .col-unit { width: 40px; }
    .col-empty { width: 40px; }
    .col-harga { width: 100px; }
    .col-empty2 { width: 40px; }
    .col-subtotal { width: 110px; }

    /* Nama Barang fleksibel + wrap */
    .col-nama {
        word-wrap: break-word;
        word-break: break-word;
    }

    /* Rata teks */
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    @page {
        margin: 100px 40px 100px 40px; /* margin atas kanan bawah kiri */
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

    h1, h2, h3 {
        font-family: 'DejaVu Sans', sans-serif;
        font-weight: bold;
    }

  
    </style>
</head>
<body>
    <div>
        <table>
            <tr>
                <td style="width: 50%;">
                 <img src="{{ $logo
                    ? public_path('storage/' . $logo) 
                    : public_path('assets/default_logo.png') }}" 
                    alt="Logo" style="width:120px;">
                      <p class="address">{{ $alamat }}</p>
        <p class="address">P. {{ auth()->user()->perusahaanUser->telp_perusahaan }}</p>
        <p class="address" style="margin-bottom: 10px;">E. {{ auth()->user()->perusahaanUser->email }}</p>
                </td>
                <td class="text-right">
                    <h1>#NOTA KONSINYASI</h1>
                    <table class="table-bordered" style="width: 320px; margin-left:auto;">
                        <thead>
                            <tr class="text-center">
                                <th>Nomor Nota</th>
                                <th>Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $transaksi->kode_transaksi }}</td>
                                <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d-M-y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

     

        <hr class="garis-atas">
        <hr class="garis-bawah">

        <table style="margin-bottom: 10px;">
            <tr>
                <td style="width:80px;">Kepada</td>
                <td style="width:10px;">:</td>
                <td>{{ $transaksi->mitra->nama_mitra }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ ucwords(strtolower($transaksi->mitra->id_kota)) }}</td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>:</td>
                <td>{{ $transaksi->mitra->no_telp_mitra }}</td>
            </tr>
        </table>

        <table class="table-bordered">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th style="width:30%;">Nama Barang</th>
                    <th style="width:40px;">Qty</th>
                    <th style="width:40px;">Unit</th>
                    <th style="width:40px;"></th>
                    <th style="width:100px;">Harga Unit</th>
                    <th  style="width:40px;" ></th>
                    <th style="width:110px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php 
                    $grandTotal = 0;
                @endphp
                @foreach ($transaksi->ProdukTransaksi as $index => $item)
                    @php
                        $total = $item->barang_keluar * $item->penawaran->harga;
                        $grandTotal += $total;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index+1 }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td class="text-center">{{ $item->barang_keluar }}</td>
                        <td class="text-center">Pcs</td>
                        <td class="text-right">Rp.</td>
                        <td class="text-right">{{ number_format($item->penawaran->harga,2,',','.') }}</td>
                        <td class="text-right">Rp.</td>
                        <td class="text-right">{{ number_format($total,2,',','.') }}</td>
                    </tr>
                @endforeach
                @if($transaksi->ongkir > 0)
                <tr>
                    <td colspan="7" class="text-right">Ongkos Kirim (Rp)</td>
                    <td class="text-right">{{ number_format($transaksi->ongkir,2,',','.') }}</td>
                </tr>
                @endif
                @if($transaksi->diskon > 0)
                <tr>
                    <td colspan="7" class="text-right">Diskon (Rp)</td>
                    <td class="text-right">{{ number_format($transaksi->diskon,2,',','.') }}</td>
                </tr>
                @endif
                <tr>
                    <td colspan="7" class="text-right"><b>TOTAL</b></td>
                    <td class="text-right"><b>{{ number_format($grandTotal + $transaksi->ongkir - $transaksi->diskon,2,',','.') }}</b></td>
                </tr>
            </tbody>
        </table>

        <p style="font-size:11px; margin-top:10px;">
            {{ $perusahaan->keterangan_pembayaran }}
        </p>

        <table style="margin-top:40px; width:100%;">
            <tr>
                <td class="text-center" style="height:150px;">
                    <p><b>Penerima</b></p>
                    <div style="margin-top:100px; border-top:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                </td>
                <td class="text-center" style="height:150px; position:relative;">
                    <p><b>Hormat Kami</b></p>
                
                    <div style="margin-top:110px; border-top:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                    <p>{{ auth()->user()->perusahaanUser->nama_perusahaan }}</p>
                </td>
            </tr>
        </table>
    </div>
     <footer>
        <hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 4px;">
        Dicetak dengan aplikasi <b>SILASAR</b> — Halaman <span class="pagenum"></span>
    </footer>
</body>
</html>
