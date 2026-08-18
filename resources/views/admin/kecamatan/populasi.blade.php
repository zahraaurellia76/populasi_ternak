@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-success">Kelola Data Populasi Ternak</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Input Data Baru -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold text-muted mb-3"><i class="bi bi-plus-circle me-1"></i> Tambah Data Populasi Baru</h6>
            <form action="{{ route('admin.kecamatan.populasi.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Jenis Ternak</label>
                        <select name="jenis_ternak_id" class="form-select" required>
                            @foreach($jenisTernaks as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_ternak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <input type="number" name="tahun" class="form-control" value="{{ date('Y') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Triwulan</label>
                        <select name="triwulan" class="form-select" required>
                            <option value="1">Triwulan I (Jan - Mar)</option>
                            <option value="2">Triwulan II (Apr - Jun)</option>
                            <option value="3">Triwulan III (Jul - Sep)</option>
                            <option value="4">Triwulan IV (Okt - Des)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Jumlah (Ekor)</label>
                        <div class="input-group">
                            <input type="number" name="jumlah" class="form-control" placeholder="Contoh: 1500" min="0" required>
                            <button type="submit" class="btn btn-success fw-semibold px-4">
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data & Fitur CRUD -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <h6 class="fw-bold text-muted mb-0"><i class="bi bi-list-task me-1"></i> Riwayat Input Populasi</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Jenis Ternak</th>
                            <th>Periode</th>
                            <th class="text-end">Jumlah (Ekor)</th>
                            <th class="text-center" style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($populasiList as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->jenisTernak->nama_ternak ?? '-' }}</td>
                                <td>Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</td>
                                <td class="text-end fw-bold text-success">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <!-- Tombol Edit dengan Teks -->
                                    <button class="btn btn-sm btn-outline-warning me-1 fw-semibold" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                    <!-- Tombol Hapus dengan Teks -->
                                    <button class="btn btn-sm btn-outline-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.kecamatan.populasi.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Populasi - {{ $item->jenisTernak->nama_ternak }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small text-muted mb-2">Periode: <strong>Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</strong></p>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jumlah (Ekor)</label>
                                                    <input type="number" name="jumlah" class="form-control" value="{{ $item->jumlah }}" min="0" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.kecamatan.populasi.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold text-danger">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah Anda yakin ingin menghapus data populasi <strong>{{ $item->jenisTernak->nama_ternak }}</strong> periode <strong>Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Hapus Data</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data populasi yang diinputkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection