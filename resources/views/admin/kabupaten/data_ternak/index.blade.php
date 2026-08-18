@extends('layouts.admin_kabupaten')

@section('title', 'Data Ternak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-emerald mb-0">Kelola Data Populasi Ternak</h3>
    <button class="btn btn-emerald text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahData">
        + Tambah Data Ternak
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Data -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.kabupaten.data_ternak') }}" class="row g-3 align-items-center">
            <div class="col-md-4">
                <label class="form-label fw-bold">Pilih Kecamatan</label>
                <select name="kecamatan_id" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Kecamatan --</option>
                    @foreach($kecamatans as $kc)
                        <option value="{{ $kc->id }}" {{ $kecamatanSelected == $kc->id ? 'selected' : '' }}>
                            {{ $kc->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Triwulan</label>
                <select name="triwulan" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Triwulan --</option>
                    @for($t = 1; $t <= 4; $t++)
                        <option value="{{ $t }}" {{ $triwulanSelected == $t ? 'selected' : '' }}>Triwulan {{ $t }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Tahun</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @for($t = date('Y'); $t >= 2018; $t--)
                        <option value="{{ $t }}" {{ $tahunSelected == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <a href="{{ route('admin.kabupaten.data_ternak') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Populasi -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kecamatan</th>
                    <th>Jenis Ternak</th>
                    <th style="width: 110px;">Triwulan</th>
                    <th style="width: 90px;">Tahun</th>
                    <th class="text-end">Jumlah (Ekor)</th>
                    <th style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($populasi as $index => $item)
                    <tr>
                        <td class="text-center">{{ $populasi->firstItem() + $index }}</td>
                        <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td>{{ $item->jenisTernak->nama_ternak ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-info text-dark">Triwulan {{ $item->triwulan ?? 1 }}</span>
                        </td>
                        <td class="text-center">{{ $item->tahun }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditData{{ $item->id }}">Edit</button>
                            <form action="{{ route('admin.kabupaten.data_ternak.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Data -->
                    <div class="modal fade" id="modalEditData{{ $item->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.kabupaten.data_ternak.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Data Populasi Ternak</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Kecamatan</label>
                                            <select name="kecamatan_id" class="form-select" required>
                                                @foreach($kecamatans as $kc)
                                                    <option value="{{ $kc->id }}" {{ $item->kecamatan_id == $kc->id ? 'selected' : '' }}>
                                                        {{ $kc->nama_kecamatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Jenis Ternak</label>
                                            <select name="jenis_ternak_id" class="form-select" required>
                                                @foreach($jenisTernaks as $jt)
                                                    <option value="{{ $jt->id }}" {{ $item->jenis_ternak_id == $jt->id ? 'selected' : '' }}>
                                                        {{ $jt->nama_ternak }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Triwulan</label>
                                            <select name="triwulan" class="form-select" required>
                                                @for($tw = 1; $tw <= 4; $tw++)
                                                    <option value="{{ $tw }}" {{ ($item->triwulan ?? 1) == $tw ? 'selected' : '' }}>
                                                        Triwulan {{ $tw }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Tahun</label>
                                            <input type="number" name="tahun" class="form-control" value="{{ $item->tahun }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Jumlah (Ekor)</label>
                                            <input type="number" name="jumlah" class="form-control" value="{{ $item->jumlah }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data populasi ternak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($populasi->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $populasi->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambahData" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kabupaten.data_ternak.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Data Populasi Ternak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kecamatan</label>
                        <select name="kecamatan_id" class="form-select" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kc)
                                <option value="{{ $kc->id }}">{{ $kc->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jenis Ternak</label>
                        <select name="jenis_ternak_id" class="form-select" required>
                            <option value="">-- Pilih Jenis Ternak --</option>
                            @foreach($jenisTernaks as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_ternak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Triwulan</label>
                        <select name="triwulan" class="form-select" required>
                            <option value="1">Triwulan 1</option>
                            <option value="2">Triwulan 2</option>
                            <option value="3">Triwulan 3</option>
                            <option value="4">Triwulan 4</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah (Ekor)</label>
                        <input type="number" name="jumlah" class="form-control" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald text-white fw-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection