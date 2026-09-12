<html xmlns:o="urn:schemas-microsoft-com:office:excel" 
      xmlns:x="urn:schemas-microsoft-com:office:excel" 
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--[if gte mso 9]>
    <xml>
        <@x:ExcelWorkbook>
            <@x:ExcelWorksheets>
                <@x:ExcelWorksheet>
                    <@x:Name>Rekapitulasi</@x:Name>
                    <@x:WorksheetOptions>
                        <@x:Print>
                            <@x:ValidPrinterInfo/>
                            <@x:Orientation>Landscape</@x:Orientation>
                            <@x:FitToPage/>
                            <@x:FitWidth>1</@x:FitWidth>
                            <@x:FitHeight>0</@x:FitHeight>
                        </@x:Print>
                        <@x:ShowGridlines/>
                    </@x:WorksheetOptions>
                </@x:ExcelWorksheet>
            </@x:ExcelWorksheets>
        </@x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        @page {
            size: A4 landscape;
            margin: 0.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            table-layout: fixed;
        }
        th, td { 
            border: 1px solid #cccccc; 
            vertical-align: middle; 
            padding: 3px 2px; 
            overflow: hidden;
            word-wrap: break-word;
        }
        .th-header { 
            background-color: #113d2f; 
            color: #ffffff; 
            font-weight: bold; 
            text-align: center; 
            font-size: 7.5pt;
        }
        .td-total { 
            background-color: #d1e7dd; 
            font-weight: bold; 
            font-size: 7.5pt; /* Menyesuaikan ukuran font kolom Jumlah ke 7.5pt */
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr style="height: 25px;">
                <th colspan="{{ count($jenisTernaks) + 2 }}" 
                    style="font-size: 12pt; font-weight: bold; text-align: center; border: none;">
                    REKAPITULASI DATA POPULASI TERNAK KABUPATEN KEDIRI
                </th>
            </tr>
            <tr style="height: 20px;">
                <th colspan="{{ count($jenisTernaks) + 2 }}" 
                    style="font-size: 10pt; font-weight: bold; text-align: center; border: none;">
                    {{ $triwulanSelected ? 'TRIWULAN ' . $triwulanSelected : '' }} TAHUN {{ $tahunSelected }}
                </th>
            </tr>
            <tr style="height: 10px;">
                <td colspan="{{ count($jenisTernaks) + 2 }}" style="border: none;"></td>
            </tr>
            <!-- Baris ke-4: height diperbesar dari 28px menjadi 45px -->
            <tr style="height: 45px;">
                <th class="th-header" style="width: 25px;">No</th>
                <th class="th-header" style="width: 110px;">Nama Kecamatan</th>
                @foreach($jenisTernaks as $jt)
                    <th class="th-header" style="width: 68px;">{{ $jt->nama_ternak }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php 
                $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0); 
            @endphp
            
            @foreach($rekap as $index => $kc)
                <tr style="height: 20px;">
                    <td style="text-align: center; font-size: 7.5pt; font-weight: normal;">{{ $loop->iteration }}</td>
                    <td style="font-size: 7.5pt;">{{ strtoupper($kc->nama_kecamatan) }}</td>
                    @foreach($jenisTernaks as $jt)
                        @php
                            $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                            $grandTotals[$jt->id] += $jumlah;
                        @endphp
                        <td style="text-align: right; font-size: 7.5pt; mso-number-format:'\#\,\#\#0';">{{ $jumlah }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="height: 22px;">
                <!-- Kolom Jumlah diset berukuran 7.5pt -->
                <td colspan="2" class="td-total" style="text-align: center; font-size: 7.5pt;">Jumlah</td>
                @foreach($jenisTernaks as $jt)
                    <td class="td-total" style="text-align: right; font-size: 7.5pt; mso-number-format:'\#\,\#\#0';">
                        {{ $grandTotals[$jt->id] ?? 0 }}
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>