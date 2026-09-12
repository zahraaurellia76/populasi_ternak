<table>
    <tr>
        <td colspan="3" style="font-weight: bold; font-size: 14px; text-align: center;">REKAPITULASI DATA POPULASI TERNAK</td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: center;">
            Kecamatan: {{ $user->kecamatan->nama_kecamatan ?? '-' }} | Tahun: {{ $tahun }} | Triwulan: {{ $triwulan ? 'Triwulan '.$triwulan : 'Semua Triwulan' }}
        </td>
    </tr>
    <tr><td colspan="3"></td></tr>
    <thead>
        <tr style="background-color: #10b981; color: #ffffff;">
            <th style="border: 1px solid #000000; font-weight: bold; text-align: center;">NO</th>
            <th style="border: 1px solid #000000; font-weight: bold;">JENIS TERNAK</th>
            <th style="border: 1px solid #000000; font-weight: bold; text-align: right;">JUMLAH (EKOR)</th>
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
                <td style="border: 1px solid #cccccc; text-align: center;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid #cccccc;">{{ $jt->nama_ternak }}</td>
                <td style="border: 1px solid #cccccc; text-align: right;">{{ $jumlah }}</td>
            </tr>
        @endforeach
    </tbody>
</table>