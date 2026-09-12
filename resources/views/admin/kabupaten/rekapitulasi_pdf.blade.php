<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Data Populasi Ternak Tahun {{ $tahunSelected }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 6mm 5mm;
        }
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 7pt; 
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header { 
            text-align: center; 
            margin-bottom: 6px; 
        }
        .header h2 { 
            font-size: 11pt; 
            margin: 0 0 2px 0; 
            color: #113d2f;
            text-transform: uppercase;
        }
        .header h4 { 
            font-size: 8pt; 
            margin: 0; 
            color: #555; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; 
        }
        th, td { 
            border: 1px solid #bbb; 
            padding: 2px 1px; 
            overflow: hidden; 
            word-wrap: break-word; 
        }
        th { 
            background-color: #113d2f; 
            color: #fff; 
            text-align: center; 
            font-size: 6.5pt; 
            font-weight: bold;
            line-height: 1.0;
        }
        td { 
            font-size: 6.5pt; 
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
                <!-- Gunakan persentase sempit (2.5%) untuk kolom No -->
                <th style="width: 2.5%; padding: 2px 0;">No</th> 
                <!-- Alokasikan 14% untuk Nama Kecamatan agar cukup lebar -->
                <th style="width: 8%;">Nama Kecamatan</th> 
                @foreach($jenisTernaks as $jt)
                    <!-- Kolom sisa akan terbagi rata secara otomatis -->
                    <th>{{ $jt->nama_ternak }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0); @endphp
            @foreach($rekap as $index => $kc)
                <tr>
                    <td class="text-center" style="padding: 2px 0;">{{ $loop->iteration }}</td>
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
                <td colspan="2" class="text-center">Jumlah</td>
                @foreach($jenisTernaks as $jt)
                    <td class="text-right">{{ number_format($grandTotals[$jt->id] ?? 0, 0, ',', '.') }}</td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>