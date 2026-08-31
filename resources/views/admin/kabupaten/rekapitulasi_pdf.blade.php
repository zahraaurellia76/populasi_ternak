<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Data Populasi Ternak Tahun {{ $tahunSelected }}</title>
    <style>
        /* Mengatur ukuran kertas Landscape agar kolom muat kesamping */
        @page {
            size: A4 landscape;
            margin: 10mm 8mm;
        }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 8pt; 
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px; 
        }
        .header h2 { 
            font-size: 13pt; 
            margin: 0 0 3px 0; 
            color: #113d2f;
            text-transform: uppercase;
        }
        .header h4 { 
            font-size: 9pt; 
            margin: 0; 
            color: #555; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; /* Memaksa lebar tabel proporsional */
        }
        th, td { 
            border: 1px solid #bbb; 
            padding: 4px 3px; 
            overflow: hidden; 
            word-wrap: break-word; 
        }
        th { 
            background-color: #113d2f; 
            color: #fff; 
            text-align: center; 
            font-size: 7.5pt; 
            font-weight: bold;
            line-height: 1.1;
        }
        td { 
            font-size: 8pt; 
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { 
            background-color: #d1e7dd; 
            font-weight: bold; 
        }
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
                <th style="width: 25px;">No</th>
                <th style="width: 85px;">Nama Kecamatan</th>
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