<?php

namespace App\Http\Controllers;

use App\Models\PopulasiKecamatan;
use App\Models\JenisTernak;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ==========================================
    // ADMIN KECAMATAN METHODS
    // ==========================================
    public function dashboardKecamatan()
    {
        $user = Auth::user();
        $wilayah = $user->kecamatan->nama_kecamatan ?? 'Kecamatan';

        // Mendapatkan tahun saat ini (Tahun 2026)
        $tahunSelected = date('Y');
        
        // Menentukan triwulan otomatis berdasarkan bulan saat ini (Bulan Agustus = Triwulan III)
        $bulan = date('n'); 
        if ($bulan <= 3) {
            $triwulanSelected = 1;
        } elseif ($bulan <= 6) {
            $triwulanSelected = 2;
        } elseif ($bulan <= 9) {
            $triwulanSelected = 3;
        } else {
            $triwulanSelected = 4;
        }

        $jenisTernaks = \App\Models\JenisTernak::all();
        
        $rekapitulasi = [];
        foreach ($jenisTernaks as $jt) {
            // Mengambil data populasi spesifik untuk kecamatan user dan triwulan/tahun terkini
            $populasi = \App\Models\PopulasiKecamatan::where('kecamatan_id', $user->kecamatan_id)
                ->where('jenis_ternak_id', $jt->id)
                ->where('tahun', $tahunSelected)
                ->where('triwulan', $triwulanSelected)
                ->first();

            $rekapitulasi[] = [
                'nama_ternak' => $jt->nama_ternak,
                'jumlah'      => $populasi ? $populasi->jumlah : 0,
            ];
        }

        // Mengubah format angka romawi untuk tampilan periode
        $romawiTriwulan = ['1' => 'I', '2' => 'II', '3' => 'III', '4' => 'IV'];
        $namaTriwulanText = "Tahun {$tahunSelected} Triwulan " . ($romawiTriwulan[$triwulanSelected] ?? $triwulanSelected);

        return view('admin.kecamatan.dashboard', compact(
            'wilayah', 
            'rekapitulasi', 
            'tahunSelected', 
            'triwulanSelected', 
            'namaTriwulanText'
        ));
    }

    public function kelolaPopulasi(Request $request)
    {
        $user = Auth::user();
        $jenisTernaks = JenisTernak::all();

        $populasi = PopulasiKecamatan::with('jenisTernak')
            ->where('kecamatan_id', $user->kecamatan_id)
            ->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->paginate(10);

        return view('admin.kecamatan.populasi', compact('user', 'jenisTernaks', 'populasi'));
    }

    public function prediksiKecamatan(Request $request)
    {
        $user = Auth::user();
        $jenisTernaks = JenisTernak::all();
        $selectedJenisId = $request->input('jenis_ternak_id', $jenisTernaks->first()?->id);

        $histori = PopulasiKecamatan::where('kecamatan_id', $user->kecamatan_id)
            ->where('jenis_ternak_id', $selectedJenisId)
            ->where('status_validasi', 'disetujui')
            ->orderBy('tahun', 'asc')
            ->orderBy('triwulan', 'asc')
            ->get();

        $n = $histori->count();
        $prediksiBerikutnya = null;
        $a = 0; $b = 0;

        if ($n > 1) {
            $sumX = 0; $sumY = 0; $sumXY = 0; $sumX2 = 0;
            foreach ($histori as $index => $item) {
                $x = $index + 1;
                $y = $item->jumlah;
                $sumX += $x;
                $sumY += $y;
                $sumXY += ($x * $y);
                $sumX2 += ($x * $x);
            }
            $denominator = ($n * $sumX2) - ($sumX * $sumX);
            if ($denominator != 0) {
                $b = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
                $a = ($sumY - ($b * $sumX)) / $n;
                $prediksiBerikutnya = max(0, round($a + ($b * ($n + 1))));
            }
        }

        return view('admin.kecamatan.prediksi', compact(
            'user', 'jenisTernaks', 'selectedJenisId', 'histori', 'prediksiBerikutnya', 'a', 'b', 'n'
        ));
    }


    // ==========================================
    // ADMIN KABUPATEN METHODS
    // ==========================================

    // 1. Dashboard Admin Kabupaten
    public function dashboardKabupaten()
    {
        $user = Auth::user();
        
        $bulanIni = date('n'); // 1-12
        $tahunIni = date('Y');

        // Tentukan triwulan berjalan saat ini
        $currentTriwulan = ceil($bulanIni / 3);

        // Ambil triwulan terakhir yang data-nya sudah selesai/tersedia
        if ($currentTriwulan == 1) {
            $triwulanSelected = 4;
            $tahunSelected = $tahunIni - 1; // Mundur ke Triwulan 4 tahun sebelumnya
        } else {
            $triwulanSelected = $currentTriwulan - 1; // Ambil triwulan sebelumnya
            $tahunSelected = $tahunIni;
        }

        // Stats Card
        $queryPopulasi = PopulasiKecamatan::where('tahun', $tahunSelected);
        if (\Schema::hasColumn('populasi_kecamatans', 'triwulan')) {
            $queryPopulasi->where('triwulan', $triwulanSelected);
        }
        $totalPopulasi = $queryPopulasi->sum('jumlah');

        $totalKecamatan = Kecamatan::count();
        $totalJenisTernak = JenisTernak::count();

        // Data Rekapitulasi Per Kecamatan
        $rekap = Kecamatan::with(['populasiKecamatan' => function($q) use ($tahunSelected, $triwulanSelected) {
            $q->where('tahun', $tahunSelected);
            if (\Schema::hasColumn('populasi_kecamatans', 'triwulan')) {
                $q->where('triwulan', $triwulanSelected);
            }
        }])->get();

        $jenisTernaks = JenisTernak::all();

        // Data Grafik: Total Populasi per Jenis Ternak
        $chartData = JenisTernak::select('jenis_ternaks.nama_ternak', DB::raw('SUM(populasi_kecamatans.jumlah) as total'))
            ->leftJoin('populasi_kecamatans', function($join) use ($tahunSelected, $triwulanSelected) {
                $join->on('jenis_ternaks.id', '=', 'populasi_kecamatans.jenis_ternak_id')
                    ->where('populasi_kecamatans.tahun', '=', $tahunSelected);
                if (\Schema::hasColumn('populasi_kecamatans', 'triwulan')) {
                    $join->where('populasi_kecamatans.triwulan', '=', $triwulanSelected);
                }
            })
            ->groupBy('jenis_ternaks.id', 'jenis_ternaks.nama_ternak')
            ->get();

        $chartLabels = $chartData->pluck('nama_ternak');
        $chartValues = $chartData->pluck('total')->map(fn($v) => $v ?? 0);

        return view('admin.kabupaten.dashboard', compact(
            'user', 
            'totalPopulasi', 
            'totalKecamatan', 
            'totalJenisTernak', 
            'rekap', 
            'jenisTernaks', 
            'tahunSelected',
            'triwulanSelected',
            'chartLabels',
            'chartValues'
        ));
    }

    // 2. Data Ternak
    public function dataTernak(Request $request)
    {
        $user = Auth::user();
        $tahunSelected = $request->input('tahun', date('Y'));
        $triwulanSelected = $request->input('triwulan');
        $kecamatanSelected = $request->input('kecamatan_id');
        
        $kecamatans = Kecamatan::all();
        $jenisTernaks = JenisTernak::all();

        $query = PopulasiKecamatan::with(['kecamatan', 'jenisTernak']);

        if ($tahunSelected) {
            $query->where('tahun', $tahunSelected);
        }
        if ($triwulanSelected) {
            $query->where('triwulan', $triwulanSelected);
        }
        if ($kecamatanSelected) {
            $query->where('kecamatan_id', $kecamatanSelected);
        }

        $populasi = $query->orderBy('id', 'desc')->paginate(15);

        return view('admin.kabupaten.data_ternak.index', compact(
            'user', 'populasi', 'kecamatans', 'jenisTernaks', 
            'tahunSelected', 'triwulanSelected', 'kecamatanSelected'
        ));
    }

    public function storeDataTernak(Request $request)
    {
        $request->validate([
            'kecamatan_id'    => 'required|exists:kecamatans,id',
            'jenis_ternak_id' => 'required|exists:jenis_ternaks,id',
            'tahun'           => 'required|numeric',
            'triwulan'        => 'required|integer|between:1,4',
            'jumlah'          => 'required|numeric|min:0',
        ]);

        PopulasiKecamatan::create([
            'user_id'         => Auth::id(), // <-- TAMBAHKAN BARIS INI
            'kecamatan_id'    => $request->kecamatan_id,
            'jenis_ternak_id' => $request->jenis_ternak_id,
            'tahun'           => $request->tahun,
            'triwulan'        => $request->triwulan,
            'jumlah'          => $request->jumlah,
            'status_validasi' => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Data populasi ternak berhasil ditambahkan!');
    }

    public function updateDataTernak(Request $request, $id)
    {
        $data = PopulasiKecamatan::findOrFail($id);

        $request->validate([
            'kecamatan_id'    => 'required|exists:kecamatans,id',
            'jenis_ternak_id' => 'required|exists:jenis_ternaks,id',
            'tahun'           => 'required|numeric',
            'triwulan'        => 'required|integer|between:1,4',
            'jumlah'          => 'required|numeric|min:0',
        ]);

        $data->update([
            'user_id'         => Auth::id(), // <-- TAMBAHKAN BARIS INI
            'kecamatan_id'    => $request->kecamatan_id,
            'jenis_ternak_id' => $request->jenis_ternak_id,
            'tahun'           => $request->tahun,
            'triwulan'        => $request->triwulan,
            'jumlah'          => $request->jumlah,
            'status_validasi' => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Data populasi ternak berhasil diperbarui!');
    }

    public function destroyDataTernak($id)
    {
        $data = PopulasiKecamatan::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data populasi ternak berhasil dihapus!');
    }

    // 3. Rekapitulasi Data Seluruh Kecamatan
    public function rekapitulasi(Request $request)
    {
        $tahunSelected = $request->input('tahun', date('Y'));
        $triwulanSelected = $request->input('triwulan');

        $rekap = Kecamatan::with(['populasiKecamatan' => function($q) use ($tahunSelected, $triwulanSelected) {
            $q->where('tahun', $tahunSelected);
            if ($triwulanSelected) {
                $q->where('triwulan', $triwulanSelected);
            }
        }])->get();

        $jenisTernaks = JenisTernak::all();

        return view('admin.kabupaten.rekapitulasi', compact(
            'rekap', 'jenisTernaks', 'tahunSelected', 'triwulanSelected'
        ));
    }

    public function cetakRekapPdf(Request $request)
    {
        $tahunSelected = $request->input('tahun', date('Y'));
        $triwulanSelected = $request->input('triwulan');

        $rekap = Kecamatan::with(['populasiKecamatan' => function($q) use ($tahunSelected, $triwulanSelected) {
            $q->where('tahun', $tahunSelected);
            if ($triwulanSelected) {
                $q->where('triwulan', $triwulanSelected);
            }
        }])->get();

        $jenisTernaks = JenisTernak::all();

        $pdf = Pdf::loadView('admin.kabupaten.rekapitulasi_pdf', compact(
            'rekap', 'jenisTernaks', 'tahunSelected', 'triwulanSelected'
        ))->setPaper('a4', 'landscape');

        $filename = 'rekapitulasi_populasi_ternak_' . $tahunSelected . ($triwulanSelected ? '_tw' . $triwulanSelected : '') . '.pdf';
        return $pdf->download($filename);
    }

    public function cetakRekapExcel(Request $request)
    {
        $tahunSelected = $request->input('tahun', date('Y'));
        $triwulanSelected = $request->input('triwulan');

        $rekap = Kecamatan::with(['populasiKecamatan' => function($q) use ($tahunSelected, $triwulanSelected) {
            $q->where('tahun', $tahunSelected);
            if ($triwulanSelected) {
                $q->where('triwulan', $triwulanSelected);
            }
        }])->get();

        $jenisTernaks = JenisTernak::all();

        $filename = 'rekapitulasi_populasi_ternak_' . $tahunSelected . ($triwulanSelected ? '_tw' . $triwulanSelected : '') . '.xls';

        // Gunakan response()->view() bawaan Laravel
        return response()->view('admin.kabupaten.rekapitulasi_excel', compact(
            'rekap', 'jenisTernaks', 'tahunSelected', 'triwulanSelected'
        ))->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // 4. Prediksi & Regresi Linier Skala Kabupaten
    public function prediksiKabupaten(Request $request)
    {
        $jenisTernaks = JenisTernak::all();
        $kecamatans = Kecamatan::all();

        $jenisTernakId = $request->input('jenis_ternak_id', $jenisTernaks->first()->id ?? null);
        $kecamatanId = $request->input('kecamatan_id');

        // Query data populasi
        $query = PopulasiKecamatan::query()
            ->where('jenis_ternak_id', $jenisTernakId)
            ->orderBy('tahun', 'asc')
            ->orderBy('triwulan', 'asc');

        if ($kecamatanId) {
            $query->where('kecamatan_id', $kecamatanId);
        }

        $populasiData = $query->get();

        // Grouping data jika skala kabupaten (karena banyak kecamatan per periode)
        $groupedData = [];
        foreach ($populasiData as $item) {
            $label = "Th " . $item->tahun . " TW" . $item->triwulan;
            if (!isset($groupedData[$label])) {
                $groupedData[$label] = 0;
            }
            $groupedData[$label] += $item->jumlah;
        }

        $labels = array_keys($groupedData);
        $values = array_values($groupedData);
        $n = count($values);

        $prediksi = null;
        $a = 0;
        $b = 0;

        // Hitung Regresi Linier Sederhana
        if ($n >= 2) {
            $x = range(1, $n);
            $y = $values;

            $sumX = array_sum($x);
            $sumY = array_sum($y);
            
            $sumXY = 0;
            $sumX2 = 0;
            for ($i = 0; $i < $n; $i++) {
                $sumXY += ($x[$i] * $y[$i]);
                $sumX2 += ($x[$i] * $x[$i]);
            }

            $denominator = ($n * $sumX2) - ($sumX * $sumX);
            if ($denominator != 0) {
                $b = (($n * $sumXY) - ($sumX * $sumY)) / $denominator;
                $a = ($sumY - ($b * $sumX)) / $n;
                $prediksi = round($a + ($b * ($n + 1)));
            }
        }

        return view('admin.kabupaten.prediksi', compact(
            'jenisTernaks', 'kecamatans', 'jenisTernakId', 'kecamatanId',
            'labels', 'values', 'n', 'a', 'b', 'prediksi'
        ));
    }
}