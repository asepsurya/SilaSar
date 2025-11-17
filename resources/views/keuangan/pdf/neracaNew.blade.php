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

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0 15px;
            border-bottom: 2px solid #1e40af;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-left img {
            height: 50px;
            width: auto;
            border-radius: 6px;
        }
        .header-title {
            text-align: right;
        }
        .header-title h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #1a237e;
        }
        .header-title span {
            font-size: 12px;
            color: #555;
        }

        /* ===== SECTION TITLE ===== */
        h3 {
            margin: 18px 0 8px;
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 12px;
        }
        th {
            background-color: #f5f9ff;
            color: #333;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ccc;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
        }
        td:last-child, th:last-child {
            text-align: right;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tfoot td {
            font-weight: bold;
            background: #f0f4ff;
            border-top: 1px solid #ccc;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
       <div class="header">
            <div class="header-title">
              
            </div>

            <div class="header-left">
                
                @php
                    $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id ?? null);
                    $logoPerusahaan = $perusahaan && $perusahaan->logo
                        ? public_path('storage/' . $perusahaan->logo)
                        : public_path('assets/default_logo.png');
                @endphp
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPerusahaan)) }}" alt="Logo Perusahaan">
                      <h2>LAPORAN NERACA</h2>
                @php
                    $periode = request('periode') ?? null;

                    // Bulanan
                    $bulan        = request('bulan');
                    $tahun_bulan  = request('tahun_bulan');

                    // Tahunan
                    $tahun_tahun  = request('tahun_tahun');

                    // Rentang tanggal
                    $awal  = request('tanggal_awal');
                    $akhir = request('tanggal_akhir');
                @endphp

                <span>
                    @if ($periode === 'rentang' && $awal && $akhir)
                        Periode: {{ $awal }} s/d {{ $akhir }}

                    @elseif ($periode === 'bulanan' && $bulan && $tahun_bulan)
                        Periode: {{ $bulan }}/{{ $tahun_bulan }}

                    @elseif ($periode === 'tahunan' && $tahun_tahun)
                        Periode: Tahun {{ $tahun_tahun }}

                    @else
                        Periode: Semua Data
                    @endif
                </span><br>

                <span>(Dalam Rupiah)</span>
            </div>
        </div>

    {{-- TABLES PER TIPE --}}
    @foreach($neraca as $tipe => $akunGroup)
        <h3>{{ strtoupper($tipe) }}</h3>
        <table>
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($akunGroup as $akun)
                    <tr>
                        <td>{{ $akun->nama_akun }}</td>
                        <td>Rp {{ number_format($akun->saldo, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total {{ ucfirst($tipe) }}</strong></td>
                    <td><strong>Rp {{ number_format($akunGroup->sum('saldo'), 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    @endforeach

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'System' }} <br>
        Tanggal cetak: {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
