@extends('layouts.admin_kabupaten')

@section('title', 'Kelola Kecamatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-emerald mb-0">Kelola Data Kecamatan</h3>
    <button class="btn btn-emerald text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahKecamatan">
        + Tambah Kecamatan
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
                    <th style="width: 180px;">Kode Kecamatan</th>
                    <th>Nama Kecamatan</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kecamatans as $index => $kc)
                    <tr>
                        <td class="text-center">{{ $kecamatans->firstItem() + $index }}</td>
                        <td class="text-center"><code>{{ $kc->kode_kecamatan ?? '-' }}</code></td>
                        <td class="fw-bold">{{ $kc->nama_kecamatan }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditKecamatan{{ $kc->id }}">Edit</button>
                            <form action="{{ route('admin.kabupaten.kecamatan.destroy', $kc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kecamatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit Kecamatan -->
                    <div class="modal fade" id="modalEditKecamatan{{ $kc->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.kabupaten.kecamatan.update', $kc->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit Kecamatan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Kecamatan</label>
                                            <input type="text" name="kode_kecamatan" class="form-control" value="{{ $kc->kode_kecamatan }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Nama Kecamatan</label>
                                            <input type="text" name="nama_kecamatan" class="form-control" value="{{ $kc->nama_kecamatan }}" required>
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
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data kecamatan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kecamatans->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $kecamatans->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah Kecamatan -->
<div class="modal fade" id="modalTambahKecamatan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kabupaten.kecamatan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Kecamatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kecamatan</label>
                        <input type="text" name="kode_kecamatan" class="form-control" placeholder="Contoh: 35.06.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kecamatan</label>
                        <input type="text" name="nama_kecamatan" class="form-control" placeholder="Contoh: MOJO" required>
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