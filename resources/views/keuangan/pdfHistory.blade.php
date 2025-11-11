<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan History Rekening</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            margin: 25px;
        }
        .header {
            border-bottom: 3px solid #ffc107;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .logo {
            float: right;
            width: 120px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            color: #0056a3;
        }
        .subtitle {
            font-size: 11px;
            color: #333;
            margin-bottom: 8px;
        }
        .info-section {
            margin-top: 10px;
            font-size: 11px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 4px;
        }
        .info-section p {
            margin: 3px 0;
        }
        .table-container {
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
        }
        th {
            background-color: #0056a3;
            color: #fff;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .saldo {
            font-weight: bold;
            color: #0056a3;
        }
        .footer {
            margin-top: 25px;
            border-top: 2px solid #ffc107;
            padding-top: 8px;
            font-size: 10px;
            text-align: right;
        }
        .highlight {
            background-color: #f5faff;
        }
        .total-row {
            background-color: #e6f0ff;
            font-weight: bold;
        }
        .periode {
            font-size: 10px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header" style="display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #ffc107; padding-bottom: 8px; margin-bottom: 15px;">
    @php
        $perusahaan = \App\Models\Perusahaan::find(auth()->user()->perusahaanUser->id ?? null);
        $logoPath = $perusahaan && $perusahaan->logo
            ? public_path('storage/' . $perusahaan->logo)
            : public_path('assets/default_logo.png');
        $logoPerusahaan = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

    @endphp

  
    {{-- Kanan: Logo Aplikasi + Judul --}}
    <div style="flex: 0 0 70%; text-align: left;">
          @if($logoPerusahaan)
            <img src="data:image/png;base64,{{ $logoPerusahaan }}" alt="Logo Perusahaan"
                 style="max-width: 120px; max-height: 60px; width: auto; height: auto; object-fit: contain;">
        @endif
       
        <div style="text-align: left;">
            <div style="font-size: 16px; font-weight: bold; color: #0056a3;">LAPORAN HISTORY REKENING</div>
            <div style="font-size: 11px; color: #333; margin-top: 2px;">
                @if(request('periode') == 'bulanan' && request('bulan') && request('tahun_bulan'))
                    Periode: Bulan {{ request('bulan') }} Tahun {{ request('tahun_bulan') }}
                @elseif(request('periode') == 'tahunan' && request('tahun_tahun'))
                    Periode: Tahun {{ request('tahun_tahun') }}
                @elseif(request('tanggal_awal') && request('tanggal_akhir'))
                    Periode: {{ request('tanggal_awal') }} s/d {{ request('tanggal_akhir') }}
                @else
                    Periode: Semua Data
                @endif
            </div>
        </div>
    </div>
</div>


    <div class="info-section">
        <p><strong>Nama Rekening:</strong> {{ $name->nama_rekening }}</p>
        <p><strong>Kode Rekening:</strong> {{ $name->kode_rekening }}</p>
        <p><strong>Jenis Akun:</strong> {{ $name->jenis_akun ?? '-' }}</p>
        <p><strong>Keterangan:</strong> {{ $name->keterangan ?? '-' }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Tanggal</th>
                    <th width="35%">Keterangan</th>
                    <th width="15%">Debit</th>
                    <th width="15%">Kredit</th>
                    <th width="15%">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $i => $item)
                <tr class="{{ $loop->even ? 'highlight' : '' }}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td class="text-left">{{ $item->keterangan }}</td>
                    <td class="text-right">{{ $item->debit > 0 ? 'Rp ' . number_format($item->debit, 0, ',', '.') : '-' }}</td>
                    <td class="text-right">{{ $item->kredit > 0 ? 'Rp ' . number_format($item->kredit, 0, ',', '.') : '-' }}</td>
                    <td class="text-right saldo">Rp {{ number_format($item->saldo, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data untuk periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            @if($histories->count() > 0)
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>Saldo Akhir:</strong></td>
                    <td class="text-right saldo"><strong>Rp {{ number_format($histories->last()->saldo ?? 0, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <div class="footer">
        Dicetak oleh: {{ auth()->user()->name ?? 'System' }} — {{ now()->format('d/m/Y') }}
    </div>
</body>
</html>
