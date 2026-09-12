<?php

namespace App\Http\Controllers;

use App\Models\JenisTernak;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisTernakController extends Controller
{
    public function index()
    {
        $jenisTernaks = JenisTernak::orderBy('id', 'desc')->paginate(10);
        return view('admin.kabupaten.jenis_ternak.index', compact('jenisTernaks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ternak' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_ternaks')->where(function ($query) use ($request) {
                    return $query->where('kategori', $request->kategori);
                }),
            ],
            'kategori' => 'required|string|max:255',
        ], [
            'nama_ternak.unique' => 'Data jenis ternak sudah terdaftar!',
        ]);

        JenisTernak::create([
            'nama_ternak' => $request->nama_ternak,
            'kategori'    => $request->kategori,
        ]);

        return redirect()->back()->with('success', 'Jenis ternak berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $jenisTernak = JenisTernak::findOrFail($id);

        $request->validate([
            'nama_ternak' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_ternaks')->ignore($id)->where(function ($query) use ($request) {
                    return $query->where('kategori', $request->kategori);
                }),
            ],
            'kategori' => 'required|string|max:255',
        ], [
            'nama_ternak.unique' => 'Data jenis ternak sudah terdaftar!',
        ]);

        $jenisTernak::update([
            'nama_ternak' => $request->nama_ternak,
            'kategori'    => $request->kategori,
        ]);

        return redirect()->back()->with('success', 'Jenis ternak berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenisTernak = JenisTernak::findOrFail($id);
        $jenisTernak->delete();

        return redirect()->back()->with('success', 'Jenis ternak berhasil dihapus!');
    }
}