<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Neraca</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .container {
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid #ccc;
        }
        .header h2 {
            font-size: 18px;
            margin: 0;
        }
        .header span {
            font-size: 12px;
            color: #666;
        }
        .ringkasan {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid #ccc;
        }
        .box {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-radius: 6px;
            background: #f5f9ff;
        }
        .box:nth-child(2) { background: #f8f5ff; }
        .box .label {
            font-size: 12px;
            color: #666;
        }
        .box .angka {
            font-size: 16px;
            font-weight: bold;
            margin-top: 4px;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 12px;
            padding: 8px 20px;
            background: #f3f3f3;
            border-bottom: 1px solid #ccc;
        }
        .section-title {
            padding: 8px 20px;
            background: #eaeaea;
            font-weight: bold;
            font-size: 12px;
        }
        .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 20px;
            font-size: 12px;
            border-bottom: 1px solid #eee;
        }
        .total {
            display: flex;
            justify-content: space-between;
            padding: 8px 20px;
            font-weight: bold;
            border-top: 1px solid #ccc;
            background: #fafafa;
        }
        .grand-total {
            display: flex;
            justify-content: space-between;
            padding: 12px 20px;
            font-weight: bold;
            font-size: 13px;
            background: #f3f3f3;
            border-top: 2px solid #999;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <h2>Laporan Neraca <span>(Dalam Rupiah)</span></h2>
            <small>Periode: {{ $bulan }}/{{ $tahun }}</small>
        </div>

        {{-- Ringkasan --}}
        <div class="ringkasan">
            <div class="box">
                <div class="label">Total Aset</div>
                <div class="angka">Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}</div>
            </div>
            <div class="box">
                <div class="label">Hutang</div>
                <div class="angka">Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}</div>
            </div>
            <div class="box">
                <div class="label">Ekuitas</div>
                <div class="angka">Rp {{ number_format(($neraca['ekuitas'] ?? collect())->sum('saldo'),0,',','.') }}</div>
            </div>
        </div>

        {{-- Table header --}}
        <div class="table-header">
            <span>DESKRIPSI</span>
            <span>SALDO</span>
        </div>

        {{-- =================== ASET =================== --}}
        <div class="section-title">ASET</div>
        @foreach ($neraca['aset'] ?? [] as $akun)
            <div class="row">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
        <div class="total">
            <span>JUMLAH ASET</span>
            <span>Rp {{ number_format(($neraca['aset'] ?? collect())->sum('saldo'),0,',','.') }}</span>
        </div>

        {{-- =================== HUTANG =================== --}}
        <div class="section-title">HUTANG</div>
        @foreach ($neraca['liabilitas'] ?? [] as $akun)
            <div class="row">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
        <div class="total">
            <span>JUMLAH HUTANG</span>
            <span>Rp {{ number_format(($neraca['liabilitas'] ?? collect())->sum('saldo'),0,',','.') }}</span>
        </div>

        {{-- =================== EKUITAS =================== --}}
        <div class="section-title">EKUITAS</div>
        @foreach ($neraca['ekuitas'] ?? [] as $akun)
            <div class="row">
                <span>{{ $akun->nama_akun }}</span>
                <span>Rp {{ number_format($akun->saldo,0,',','.') }}</span>
            </div>
        @endforeach
        <div class="total">
            <span>JUMLAH EKUITAS</span>
            <span>Rp {{ number_format(($neraca['ekuitas'] ?? collect())->sum('saldo'),0,',','.') }}</span>
        </div>

        {{-- =================== TOTAL =================== --}}
        <div class="grand-total">
            <span>JUMLAH HUTANG DAN EKUITAS</span>
            <span>Rp {{ number_format(
                ($neraca['liabilitas'] ?? collect())->sum('saldo') +
                ($neraca['ekuitas'] ?? collect())->sum('saldo')
            ,0,',','.') }}</span>
        </div>
    </div>
</body>
</html>
