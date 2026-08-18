<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\JenisTernak;
use App\Models\PopulasiKecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class KecamatanController extends Controller
{
    // ==========================================
    // MENU ADMIN KABUPATEN (Kelola Kecamatan)
    // ==========================================

    public function index()
    {
        $kecamatans = Kecamatan::orderBy('id', 'desc')->paginate(10);
        return view('admin.kabupaten.kecamatan.index', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kecamatan' => 'required|string|max:50|unique:kecamatans,kode_kecamatan',
            'nama_kecamatan' => 'required|string|max:255|unique:kecamatans,nama_kecamatan',
        ]);

        Kecamatan::create([
            'kode_kecamatan' => $request->kode_kecamatan,
            'nama_kecamatan' => $request->nama_kecamatan,
        ]);

        return redirect()->back()->with('success', 'Data kecamatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $kecamatan = Kecamatan::findOrFail($id);

        $request->validate([
            'kode_kecamatan' => 'required|string|max:50|unique:kecamatans,kode_kecamatan,'.$id,
            'nama_kecamatan' => 'required|string|max:255|unique:kecamatans,nama_kecamatan,'.$id,
        ]);

        $kecamatan->update([
            'kode_kecamatan' => $request->kode_kecamatan,
            'nama_kecamatan' => $request->nama_kecamatan,
        ]);

        return redirect()->back()->with('success', 'Data kecamatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->back()->with('success', 'Data kecamatan berhasil dihapus!');
    }


    // ==========================================
    // MENU ADMIN KECAMATAN (Kelola Populasi Ternak)
    // ==========================================

    public function indexPopulasi()
    {
        $user = Auth::user();
        $jenisTernaks = JenisTernak::all();

        // Ambil riwayat populasi berdasarkan kecamatan milik admin yang sedang login
        $populasiList = PopulasiKecamatan::with('jenisTernak')
            ->where('kecamatan_id', $user->kecamatan_id)
            ->orderBy('tahun', 'desc')
            ->orderBy('triwulan', 'desc')
            ->get();

        return view('admin.kecamatan.populasi', compact('jenisTernaks', 'populasiList'));
    }

    public function storePopulasi(Request $request)
    {
        $request->validate([
            'jenis_ternak_id' => 'required|exists:jenis_ternaks,id',
            'tahun'           => 'required|numeric',
            'triwulan'        => 'required|numeric|between:1,4',
            'jumlah'          => 'required|numeric|min:0',
        ]);

        $user = Auth::user();

        // Menyimpan data atau memperbarui jika entri periode & jenis ternak tersebut sudah pernah dibuat
        PopulasiKecamatan::updateOrCreate(
            [
                'kecamatan_id'    => $user->kecamatan_id,
                'jenis_ternak_id' => $request->jenis_ternak_id,
                'tahun'           => $request->tahun,
                'triwulan'        => $request->triwulan,
            ],
            [
                'jumlah' => $request->jumlah,
                'status' => 'disetujui', // Otomatis disetujui tanpa proses peninjauan/validasi
            ]
        );

        return redirect()->back()->with('success', 'Data populasi berhasil disimpan!');
    }

    public function updatePopulasi(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|numeric|min:0',
        ]);

        $populasi = PopulasiKecamatan::findOrFail($id);
        $populasi->update([
            'jumlah' => $request->jumlah,
            'status' => 'disetujui',
        ]);

        return redirect()->back()->with('success', 'Data populasi berhasil diperbarui!');
    }

    public function destroyPopulasi($id)
    {
        $populasi = PopulasiKecamatan::findOrFail($id);
        $populasi->delete();

        return redirect()->back()->with('success', 'Data populasi berhasil dihapus!');
    }

    public function rekapitulasiKecamatan(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->get('tahun', date('Y'));
        $triwulan = $request->get('triwulan', 1); // Default ke Triwulan I

        // Ambil data populasi berdasarkan kecamatan, tahun, dan triwulan
        $populasi = PopulasiKecamatan::with('jenisTernak')
            ->where('kecamatan_id', $user->kecamatan_id)
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->get();

        $jenisTernaks = JenisTernak::all();

        return view('admin.kecamatan.rekapitulasi', compact('populasi', 'jenisTernaks', 'tahun', 'triwulan'));
    }

    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->get('tahun', date('Y'));
        $triwulan = $request->get('triwulan');

        $populasiQuery = PopulasiKecamatan::with('jenisTernak')
            ->where('kecamatan_id', $user->kecamatan_id)
            ->where('tahun', $tahun);

        if ($triwulan) {
            $populasiQuery->where('triwulan', $triwulan);
        }

        $populasi = $populasiQuery->get();
        $jenisTernaks = JenisTernak::all();

        $pdf = Pdf::loadView('admin.kecamatan.rekapitulasi_pdf', compact('populasi', 'jenisTernaks', 'tahun', 'triwulan', 'user'));
        
        return $pdf->stream('Rekapitulasi_Populasi_Ternak_'.$tahun.'.pdf');
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        $tahun = $request->get('tahun', date('Y'));
        $triwulan = $request->get('triwulan');

        $populasiQuery = PopulasiKecamatan::with('jenisTernak')
            ->where('kecamatan_id', $user->kecamatan_id)
            ->where('tahun', $tahun);

        if ($triwulan) {
            $populasiQuery->where('triwulan', $triwulan);
        }

        $populasi = $populasiQuery->get();
        $jenisTernaks = JenisTernak::all();

        $filename = "Rekapitulasi_Populasi_Ternak_{$tahun}.xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        return view('admin.kecamatan.rekapitulasi_pdf', compact('populasi', 'jenisTernaks', 'tahun', 'triwulan', 'user'));
    }

    public function prediksi(Request $request)
    {
        $user = Auth::user();
        $jenisTernaks = JenisTernak::all();

        $selectedJenisId = $request->get('jenis_ternak_id', $jenisTernaks->first()->id ?? null);

        $historiData = PopulasiKecamatan::where('kecamatan_id', $user->kecamatan_id)
            ->where('jenis_ternak_id', $selectedJenisId)
            ->orderBy('tahun', 'asc')
            ->orderBy('triwulan', 'asc')
            ->get();

        $n = $historiData->count();
        $labels = [];
        $dataY = [];
        $a = 0;
        $b = 0;
        $prediksiNext = 0;

        $chartLabels = [];
        $dataHistori = [];
        $dataPrediksi = [];

        if ($n >= 2) {
            $sumX = 0;
            $sumY = 0;
            $sumXY = 0;
            $sumX2 = 0;

            foreach ($historiData as $index => $item) {
                $x = $index + 1;
                $y = (float) $item->jumlah;

                $labels[] = "Th " . $item->tahun . " TW" . $item->triwulan;
                $dataY[] = $y;

                $sumX += $x;
                $sumY += $y;
                $sumXY += ($x * $y);
                $sumX2 += ($x * $x);
            }

            $pembagi = ($n * $sumX2 - pow($sumX, 2));

            if ($pembagi != 0) {
                $b = ($n * $sumXY - $sumX * $sumY) / $pembagi;
                $a = ($sumY - $b * $sumX) / $n;
            }

            $xNext = $n + 1;
            $prediksiNext = round($a + ($b * $xNext));

            if ($prediksiNext < 0) {
                $prediksiNext = 0;
            }

            // Penyiapan data khusus grafik
            $chartLabels = $labels;
            $chartLabels[] = "Prediksi N+1";

            $dataHistori = $dataY;
            $dataHistori[] = null; // Titik prediksi diisi null pada histori

            $dataPrediksi = array_fill(0, count($dataY) - 1, null);
            $dataPrediksi[] = end($dataY); // Titik sambung dari histori terakhir
            $dataPrediksi[] = $prediksiNext;
        }

        return view('admin.kecamatan.prediksi', compact(
            'jenisTernaks',
            'selectedJenisId',
            'historiData',
            'n',
            'labels',
            'dataY',
            'a',
            'b',
            'prediksiNext',
            'chartLabels',
            'dataHistori',
            'dataPrediksi'
        ));
    }
}