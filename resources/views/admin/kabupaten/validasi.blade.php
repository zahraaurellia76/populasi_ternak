@extends('layouts.admin_kabupaten')

@section('title', 'Validasi Data Kecamatan')

@section('content')
<h3 class="fw-bold text-emerald mb-3">Validasi Data Populasi Ternak</h3>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Data -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('admin.kabupaten.validasi') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label class="form-label fw-bold">Filter Kecamatan</label>
                <select name="kecamatan_id" class="form-select">
                    <option value="">-- Semua Kecamatan --</option>
                    @foreach($kecamatans as $kc)
                        <option value="{{ $kc->id }}" {{ request('kecamatan_id') == $kc->id ? 'selected' : '' }}>
                            {{ $kc->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Filter Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft (Menunggu)</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-emerald w-100 fw-bold"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Table Data -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">No</th>
                    <th>Kecamatan</th>
                    <th>Jenis Ternak</th>
                    <th>Periode</th>
                    <th class="text-center">Jumlah</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi Validasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataPopulasi as $index => $item)
                    <tr>
                        <td class="text-center">{{ $dataPopulasi->firstItem() + $index }}</td>
                        <td class="fw-bold">{{ $item->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $item->jenisTernak->nama_ternak }}</td>
                        <td>Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</td>
                        <td class="text-center fw-bold text-success">{{ number_format($item->jumlah) }} ekor</td>
                        <td class="text-center">
                            @if($item->status_validasi == 'disetujui')
                                <span class="badge bg-success">Disetujui</span>
                            @elseif($item->status_validasi == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.kabupaten.proses_validasi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status_validasi" value="disetujui">
                                <button type="submit" class="btn btn-success btn-sm" {{ $item->status_validasi == 'disetujui' ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.kabupaten.proses_validasi', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status_validasi" value="ditolak">
                                <button type="submit" class="btn btn-danger btn-sm" {{ $item->status_validasi == 'ditolak' ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Data populasi tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dataPopulasi->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $dataPopulasi->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection