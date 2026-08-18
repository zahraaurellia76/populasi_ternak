@extends('layouts.admin_kabupaten')

@section('title', 'Kelola Jenis Ternak')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-emerald mb-0">Kelola Data Jenis Ternak</h3>
    <button class="btn btn-emerald text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahJenisTernak">
        + Tambah Jenis Ternak
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 70px;">No</th>
                    <th>Nama Jenis Ternak</th>
                    <th>Kategori</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisTernaks as $index => $jt)
                    <tr>
                        <td class="text-center">{{ $jenisTernaks->firstItem() + $index }}</td>
                        <td class="fw-bold">{{ $jt->nama_ternak }}</td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $jt->kategori }}</span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditJenisTernak{{ $jt->id }}">Edit</button>
                            <form action="{{ route('admin.kabupaten.jenis_ternak.destroy', $jt->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jenis ternak ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Jenis Ternak -->
                    <div class="modal fade" id="modalEditJenisTernak{{ $jt->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.kabupaten.jenis_ternak.update', $jt->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Jenis Ternak</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Jenis Ternak</label>
                                            <input type="text" name="nama_ternak" class="form-control" value="{{ $jt->nama_ternak }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kategori</label>
                                            <select name="kategori" class="form-select" required>
                                                <option value="Ternak Besar" {{ $jt->kategori == 'Ternak Besar' ? 'selected' : '' }}>Ternak Besar</option>
                                                <option value="Ternak Kecil" {{ $jt->kategori == 'Ternak Kecil' ? 'selected' : '' }}>Ternak Kecil</option>
                                                <option value="Unggas" {{ $jt->kategori == 'Unggas' ? 'selected' : '' }}>Unggas</option>
                                            </select>
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
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data jenis ternak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jenisTernaks->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $jenisTernaks->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah Jenis Ternak -->
<div class="modal fade" id="modalTambahJenisTernak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kabupaten.jenis_ternak.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Jenis Ternak Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis Ternak</label>
                        <input type="text" name="nama_ternak" class="form-control" placeholder="Contoh: Sapi Potong" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Ternak Besar">Ternak Besar</option>
                            <option value="Ternak Kecil">Ternak Kecil</option>
                            <option value="Unggas">Unggas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald text-white fw-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection