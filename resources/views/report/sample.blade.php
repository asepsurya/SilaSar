<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Konsinyasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 40px;
        }
        .header, .contact, .invoice-info, .recipient {
            width: 100%;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: #333;
            border-radius: 50%;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
        }
        .company-address {
            font-size: 11px;
            margin-top: 5px;
        }
        hr {
            border: 1px solid black;
            margin: 10px 0;
        }
        .invoice-info table, .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-info td, .items th, .items td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .items th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .signature {
            width: 100%;
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature div {
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">LOGO</div>
    <div>
        <h2>NOTA KONSINYASI</h2>
        <div class="invoice-info">
            <table>
                <tr>
                    <td><strong>Nomor Nota</strong></td>
                    <td><strong>Tanggal Transaksi</strong></td>
                </tr>
                <tr>
                    <td>B2025212</td>
                    <td>23-Sep-25</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="company-address">
    Tasikmalaya, Kelurahan/Desa Supayang, Kecamatan Payung Sekaki, Kota/Kab. Kabupaten Solok<br>
    P. 0874541122544<br>
    E. sample2025@admin.com
</div>

<hr>

<div>
    <strong>Kepada</strong><br>
    PT ALIM RUGI<br>
    Alamat: <br>
    Telepon:
</div>

<div class="items">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Harga Unit</th>
                <th>Sub Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Keripik Singkong</td>
                <td>1</td>
                <td>Pcs</td>
                <td class="text-right">Rp. 15.000,00</td>
                <td class="text-right">Rp. 15.000,00</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Kering Kentang Mustofa</td>
                <td>1</td>
                <td>Pcs</td>
                <td class="text-right">Rp. 2.000,00</td>
                <td class="text-right">Rp. 2.000,00</td>
            </tr>
            <tr>
                <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp. 17.000,00</strong></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="signature">
    <div>
        Penerima<br><br><br><br>
        ___________________
    </div>
    <div>
        Hormat Kami<br><br><br><br>
        ___________________<br>
        Perusahaan Admin
    </div>
</div>

</body>
</html>
