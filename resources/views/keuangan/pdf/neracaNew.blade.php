<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>NERACA - {{ \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->format('F') }} {{ $tahun }}
</h2>

  @foreach($neraca as $tipe => $akunGroup)
    <h3 class="mt-4">{{ strtoupper($tipe) }}</h3>
    <table>
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th style="text-align:right;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($akunGroup as $akun)
                <tr>
                    <td>{{ $akun->nama_akun }}</td>
                    <td style="text-align:right;">Rp {{ number_format($akun->saldo, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <!-- Tambah total -->
            <tr>
                <td><strong>Total {{ ucfirst($tipe) }}</strong></td>
                <td style="text-align:right;">
                    <strong>Rp {{ number_format($akunGroup->sum('saldo'), 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
@endforeach


</body>
</html>
