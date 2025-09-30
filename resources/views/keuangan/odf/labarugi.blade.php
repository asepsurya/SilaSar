<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Laba Rugi</title>
    <style>
        .laporan-container {
            max-width: 1000px;
            margin: 20px auto;
            border: 1px solid #ddd;
            border-radius: 12px;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background: #fff;
        }
        .laporan-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding:16px 24px;
            border-bottom: 1px solid #ddd;
            flex-wrap: wrap;
            gap: 12px;
        }
        .laporan-header h2 {
            font-size: 22px;
            margin: 0;
        }
        .laporan-header .subtitle {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        .filter-form select,
        .filter-form input {
            padding: 6px 10px;
            border:1px solid #ccc;
            border-radius:4px;
            margin:0;
        }
        .ringkasan {
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
            gap:16px;
            padding:16px 24px;
            background:#f9f9f9;
        }
        .ringkasan .box {
            padding:16px;
            border-radius:8px;
            text-align:center;
        }
        .box.biru { background:#e6f2ff; }
        .box.ungu { background:#f0e6ff; }
        .label { font-size:14px; color:#666; }
        .angka { font-size:20px; font-weight:bold; color:#000; margin-top:4px; }
        .tabel-section { border-top:1px solid #ddd; }
        .tabel-header {
            background:#f3f3f3;
            font-weight:bold;
            padding:8px 24px;
        }
        .tabel-row, .tabel-total {
            display:flex;
            justify-content:space-between;
            padding:6px 24px;
            font-size:14px;
        }
        .tabel-total {
            font-weight:bold;
            background:#fafafa;
            border-top:1px solid #ddd;
        }
        .laba-bersih {
            display:flex;
            justify-content:space-between;
            font-weight:bold;
            font-size:16px;
            padding:12px 24px;
            background:#f3f3f3;
            border-top:2px solid #ccc;
        }
        </style>
</head>
<body>
    <div class="laporan-container">

    <!-- Header -->
    <div class="laporan-header">
        <h2>
            Laporan Laba Rugi
            <span class="subtitle">(Dalam Rupiah)</span>
        </h2>

        <form method="GET" class="filter-form">
            <select name="bulan" onchange="this.form.submit()">
                @foreach(range(1, 12) as $b)
                <option value="{{ $b }}" {{ $b==$bulan ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($b)->format('F') }}
                </option>
                @endforeach
            </select>
            <input type="number" name="tahun" value="{{ $tahun }}" placeholder="Tahun"
                   onchange="this.form.submit()">
        </form>
    </div>

    <!-- Ringkasan -->
    <div class="ringkasan">
        <div class="box biru">
            <div class="label">Total Laba Kotor</div>
            <div class="angka">Rp {{ number_format($labaRugi['laba_kotor'] ?? 0,0,',','.') }}</div>
        </div>
        <div class="box ungu">
            <div class="label">Total Laba Operasional</div>
            <div class="angka">Rp {{ number_format($labaRugi['laba_operasional'] ?? 0,0,',','.') }}</div>
        </div>
        <div class="box biru">
            <div class="label">Total Laba Bersih</div>
            <div class="angka">Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,0,',','.') }}</div>
        </div>
    </div>

    <!-- Tabel Detail -->
    @foreach(['pendapatan','hpp','beban_operasional','pendapatan_lainnya','beban_lainnya'] as $section)
    @php
    $titles = [
        'pendapatan' => 'Pendapatan',
        'hpp' => 'Harga Pokok Penjualan',
        'beban_operasional' => 'Beban Operasional',
        'pendapatan_lainnya' => 'Pendapatan Lainnya',
        'beban_lainnya' => 'Beban Lainnya'
    ];
    $totalSection = ($labaRugi[$section] ?? collect())->sum('saldo');
    @endphp
    <div class="tabel-section">
        <div class="tabel-header">{{ $titles[$section] }}</div>
        @foreach($labaRugi[$section] ?? [] as $item)
        <div class="tabel-row">
            <span>{{ $item->nama_akun }}</span>
            <span>Rp {{ number_format($item->saldo,0,',','.') }}</span>
        </div>
        @endforeach
        <div class="tabel-total">
            <span>Total {{ $titles[$section] }}</span>
            <span>Rp {{ number_format($totalSection,0,',','.') }}</span>
        </div>
    </div>
    @endforeach

    <!-- Laba Bersih -->
    <div class="laba-bersih">
        <span>Laba (Rugi) Bersih</span>
        <span>Rp {{ number_format($labaRugi['laba_bersih'] ?? 0,0,',','.') }}</span>
    </div>
</div>



</body>
</html>