<?php

namespace App\Http\Controllers;

use App\Models\PopulasiKecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PopulasiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'jenis_ternak_id' => 'required|exists:jenis_ternaks,id',
            'tahun' => 'required|numeric',
            'jumlah' => 'required|numeric|min:0',
        ]);

        PopulasiKecamatan::create([
            'kecamatan_id'    => $request->kecamatan_id,
            'jenis_ternak_id' => $request->jenis_ternak_id,
            'tahun'           => $request->tahun,
            'jumlah'          => $request->jumlah,
            'status_validasi' => 'disetujui', // Otomatis disetujui / valid
        ]);

        return redirect()->back()->with('success', 'Data populasi ternak berhasil ditambahkan!');
    }

    public function prediksi(Request $request)
    {
        $kecamatanId = $request->kecamatan_id ?? Auth::user()->kecamatan_id;
        $jenisTernakId = $request->jenis_ternak_id;

        $histori = PopulasiKecamatan::where('kecamatan_id', $kecamatanId)
            ->where('jenis_ternak_id', $jenisTernakId)
            ->where('status_validasi', 'disetujui')
            ->orderBy('tahun', 'asc')
            ->orderBy('triwulan', 'asc')
            ->get();

        $prediksiStok = 0;
        if ($histori->count() >= 4) {
            $prediksiStok = $histori->take(-4)->avg('jumlah');
        } elseif ($histori->count() > 0) {
            $prediksiStok = $histori->avg('jumlah');
        }

        return view('admin.prediksi', compact('histori', 'prediksiStok'));
    }
}