<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Data Populasi Ternak</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="text-center">
        <h2 style="margin-bottom: 5px;">REKAPITULASI DATA POPULASI TERNAK</h2>
        <p style="margin-top: 0;">
            Tahun: <strong>{{ $tahun }}</strong> | 
            Triwulan: <strong>{{ $triwulan ? 'Triwulan '.$triwulan : 'Semua Triwulan' }}</strong>
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Jenis Ternak</th>
                <th style="width: 150px;">Jumlah (Ekor)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jenisTernaks as $jt)
                @php
                    $query = $populasi->where('jenis_ternak_id', $jt->id);
                    if ($triwulan) {
                        $query = $query->where('triwulan', $triwulan);
                    }
                    $jumlah = $query->sum('jumlah');
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $jt->nama_ternak }}</td>
                    <td class="text-end fw-bold">{{ number_format($jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>