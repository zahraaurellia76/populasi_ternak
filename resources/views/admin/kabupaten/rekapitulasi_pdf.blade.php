<!DOCTYPE html>
<html>
<head>
    <title>Rekapitulasi Data Populasi Ternak Tahun {{ $tahunSelected }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header h4 { margin: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; }
        th { background-color: #113d2f; color: #fff; text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #d1e7dd; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAPITULASI DATA POPULASI TERNAK</h2>
        <h4>
            KABUPATEN KEDIRI 
            {{ $triwulanSelected ? 'TRIWULAN ' . $triwulanSelected : '' }} 
            TAHUN {{ $tahunSelected }}
        </h4>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Kecamatan</th>
                @foreach($jenisTernaks as $jt)
                    <th>{{ $jt->nama_ternak }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0); @endphp
            @foreach($rekap as $index => $kc)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td><strong>{{ strtoupper($kc->nama_kecamatan) }}</strong></td>
                    @foreach($jenisTernaks as $jt)
                        @php
                            $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                            $grandTotals[$jt->id] += $jumlah;
                        @endphp
                        <td class="text-right">{{ number_format($jumlah, 0, ',', '.') }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" class="text-center">TOTAL KABUPATEN KEDIRI</td>
                @foreach($jenisTernaks as $jt)
                    <td class="text-right">{{ number_format($grandTotals[$jt->id] ?? 0, 0, ',', '.') }}</td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>