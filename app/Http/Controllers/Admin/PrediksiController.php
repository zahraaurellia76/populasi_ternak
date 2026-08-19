<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisTernak;
use App\Models\Kecamatan;
use App\Models\PopulasiKecamatan;

class PrediksiController extends Controller
{
    public function index(Request $request)
    {
        $jenisTernaks = JenisTernak::all();
        $kecamatans   = Kecamatan::all();

        $selectedJenisId     = $request->input('jenis_ternak_id');
        $selectedKecamatanId = $request->input('kecamatan_id');

        $dataCukup  = false;
        $prediksi   = null;
        $persamaan  = null;
        $chartLabels = [];
        $chartValues = [];

        if ($selectedJenisId) {
            $query = PopulasiKecamatan::where('jenis_ternak_id', $selectedJenisId)
                ->where('status_validasi', 'disetujui')
                ->orderBy('tahun', 'asc')
                ->orderBy('triwulan', 'asc');

            if ($selectedKecamatanId) {
                $query->where('kecamatan_id', $selectedKecamatanId);
            }

            $populasiData = $query->get();

            // Minimal butuh 2 data historis untuk Regresi Linier
            if ($populasiData->count() >= 2) {
                $dataCukup = true;
                $n = $populasiData->count();

                $sumX  = 0;
                $sumY  = 0;
                $sumXY = 0;
                $sumX2 = 0;

                $i = 1;
                foreach ($populasiData as $item) {
                    $x = $i;
                    $y = $item->jumlah;

                    $sumX  += $x;
                    $sumY  += $y;
                    $sumXY += ($x * $y);
                    $sumX2 += ($x * $x);

                    $chartLabels[] = "Thn " . $item->tahun . " TW " . $item->triwulan;
                    $chartValues[] = $y;

                    $i++;
                }

                // Hitung Kemiringan (b) dan Intersept (a)
                $denominator = ($n * $sumX2) - ($sumX * $sumX);
                if ($denominator != 0) {
                    $b = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
                    $a = ($sumY - ($b * $sumX)) / $n;

                    // Proyeksi untuk periode berikutnya (X = n + 1)
                    $nextX    = $n + 1;
                    $prediksi = round($a + ($b * $nextX));
                    
                    $persamaan = "Y = " . round($a, 2) . " + " . round($b, 2) . "X";

                    // Tambahkan titik prediksi ke grafik
                    $chartLabels[] = "Prediksi TW Berikutnya";
                    $chartValues[] = max(0, $prediksi);
                }
            }
        }

        return view('admin.prediksi', compact(
            'jenisTernaks',
            'kecamatans',
            'selectedJenisId',
            'selectedKecamatanId',
            'dataCukup',
            'prediksi',
            'persamaan',
            'chartLabels',
            'chartValues'
        ));
    }
}