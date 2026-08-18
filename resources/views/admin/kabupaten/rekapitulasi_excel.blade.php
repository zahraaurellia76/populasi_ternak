<html xmlns:o="urn:schemas-microsoft-com:office:office" 
      xmlns:x="urn:schemas-microsoft-com:office:excel" 
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Guarantees Gridlines in Excel without triggering Laravel Blade Component parser -->
    <!--[if gte mso 9]>
    <xml>
        <@x:ExcelWorkbook>
            <@x:ExcelWorksheets>
                <@x:ExcelWorksheet>
                    <@x:Name>Rekapitulasi</@x:Name>
                    <@x:WorksheetOptions>
                        <@x:Print>
                            <@x:ValidPrinterInfo/>
                        </@x:Print>
                        <@x:ShowGridlines/>
                    </@x:WorksheetOptions>
                </@x:ExcelWorksheet>
            </@x:ExcelWorksheets>
        </@x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        table { border-collapse: collapse; }
        th, td { border: 1px solid #cccccc; vertical-align: middle; }
        .th-header { background-color: #113d2f; color: #ffffff; font-weight: bold; text-align: center; }
        .td-total { background-color: #d1e7dd; font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <thead>
            <!-- Baris 1: Judul Utama -->
            <tr style="height: 30px;">
                <th colspan="{{ count($jenisTernaks) + 2 }}" 
                    style="font-size: 14pt; font-weight: bold; text-align: center; border: none;">
                    REKAPITULASI DATA POPULASI TERNAK KABUPATEN KEDIRI
                </th>
            </tr>
            <!-- Baris 2: Sub-Judul Triwulan & Tahun -->
            <tr style="height: 25px;">
                <th colspan="{{ count($jenisTernaks) + 2 }}" 
                    style="font-size: 12pt; font-weight: bold; text-align: center; border: none;">
                    {{ $triwulanSelected ? 'TRIWULAN ' . $triwulanSelected : '' }} TAHUN {{ $tahunSelected }}
                </th>
            </tr>
            <!-- Baris Kosong Pemisah -->
            <tr style="height: 15px;">
                <td colspan="{{ count($jenisTernaks) + 2 }}" style="border: none;"></td>
            </tr>
            
            <!-- Header Kolom Tabel -->
            <tr style="height: 30px;">
                <th class="th-header" style="width: 40px;">No</th>
                <th class="th-header" style="width: 180px;">Nama Kecamatan</th>
                @foreach($jenisTernaks as $jt)
                    <th class="th-header" style="width: 120px;">{{ $jt->nama_ternak }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php 
                $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0); 
            @endphp
            
            @foreach($rekap as $index => $kc)
                <tr style="height: 22px;">
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td>{{ strtoupper($kc->nama_kecamatan) }}</td>
                    @foreach($jenisTernaks as $jt)
                        @php
                            $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                            $grandTotals[$jt->id] += $jumlah;
                        @endphp
                        <td style="text-align: right; mso-number-format:'\#\,\#\#0';">{{ $jumlah }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="height: 25px;">
                <td colspan="2" class="td-total" style="text-align: center;">TOTAL KABUPATEN KEDIRI</td>
                @foreach($jenisTernaks as $jt)
                    <td class="td-total" style="text-align: right; mso-number-format:'\#\,\#\#0';">
                        {{ $grandTotals[$jt->id] ?? 0 }}
                    </td>
                @endforeach
            </tr>
        </tfoot>
    </table>
</body>
</html>