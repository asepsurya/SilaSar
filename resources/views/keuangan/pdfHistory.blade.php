<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>History Rekening</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
        }
        th {
            background-color: #ddd;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>History Rekening {{ $name->nama_rekening }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $history)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $history->tanggal}}</td>
                <td>{{ $history->keterangan }}</td>
                <td>{{ number_format($history->debit, 0, ',', '.') }}</td>
                <td>{{ number_format($history->kredit, 0, ',', '.') }}</td>
                <td>{{ number_format($history->saldo, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data history rekening.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
