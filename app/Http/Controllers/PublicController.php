<?php

namespace App\Http\Controllers;

use App\Models\PopulasiKecamatan;
use App\Models\JenisTernak;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $kecamatans = Kecamatan::orderBy('id', 'asc')->get();
        $jenisTernaks = JenisTernak::all();

        // 1. Tentukan Default Kecamatan "Mojo"
        $mojoKecamatan = Kecamatan::where('nama_kecamatan', 'LIKE', '%Mojo%')->first();
        $defaultKecamatanId = $mojoKecamatan ? $mojoKecamatan->id : $kecamatans->first()?->id;
        
        $selectedKecamatanId = $request->input('kecamatan_id', $defaultKecamatanId);

        // 2. Set Default Tahun = 2026 dan Triwulan = 1 jika belum dipilih di form
        $selectedTahun = $request->input('tahun', '2026');
        $selectedTriwulan = $request->input('triwulan', '1');

        // 3. Query Data Utama
        $query = PopulasiKecamatan::with(['kecamatan', 'jenisTernak'])
            ->where('status_validasi', 'disetujui');

        if ($selectedKecamatanId) {
            $query->where('kecamatan_id', $selectedKecamatanId);
        }

        if ($selectedTahun) {
            $query->where('tahun', $selectedTahun);
        }

        if ($selectedTriwulan) {
            $query->where('triwulan', $selectedTriwulan);
        }

        // 4. Ambil data dengan paginasi
        $populasi = $query->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->paginate(10)
            ->withQueryString();

        // 5. Olah data Grafik
        $chartLabels = [];
        $chartData = [];

        foreach ($populasi as $item) {
            $chartLabels[] = $item->jenisTernak->nama_ternak . ' (T' . $item->triwulan . '-' . $item->tahun . ')';
            $chartData[] = $item->jumlah;
        }

        return view('public.index', compact(
            'populasi', 
            'kecamatans', 
            'jenisTernaks', 
            'chartLabels', 
            'chartData', 
            'selectedKecamatanId',
            'selectedTahun',
            'selectedTriwulan'
        ));
    }
}