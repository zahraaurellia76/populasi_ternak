@extends('layouts.admin_kabupaten')

@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-emerald mb-0">Kelola Data User</h3>
    <button class="btn btn-emerald text-white fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
        + Tambah User
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark text-center">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Kecamatan</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $u)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $index }}</td>
                        <td class="fw-bold">{{ $u->nama }}</td>
                        <td><code>{{ $u->username }}</code></td>
                        <td class="text-center">
                            @if($u->role == 'admin_kabupaten')
                                <span class="badge bg-success">Admin Kabupaten</span>
                            @else
                                <span class="badge bg-info text-dark">Petugas Kecamatan</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $u->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}">Edit</button>
                            <form action="{{ route('admin.kabupaten.users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit User -->
                    <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.kabupaten.users.update', $u->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $u->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" value="{{ $u->username }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select" required>
                                                <option value="admin_kabupaten" {{ $u->role == 'admin_kabupaten' ? 'selected' : '' }}>Admin Kabupaten</option>
                                                <option value="petugas_kecamatan" {{ $u->role == 'petugas_kecamatan' ? 'selected' : '' }}>Petugas Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kecamatan <small class="text-muted">(Khusus Petugas Kecamatan)</small></label>
                                            <select name="kecamatan_id" class="form-select">
                                                <option value="">-- Pilih Kecamatan --</option>
                                                @foreach($kecamatans as $kc)
                                                    <option value="{{ $kc->id }}" {{ $u->kecamatan_id == $kc->id ? 'selected' : '' }}>{{ $kc->nama_kecamatan }}</option>
                                                @endforeach
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
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada data user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white d-flex justify-content-end">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.kabupaten.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="petugas_kecamatan">Petugas Kecamatan</option>
                            <option value="admin_kabupaten">Admin Kabupaten</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kecamatan <small class="text-muted">(Khusus Petugas Kecamatan)</small></label>
                        <select name="kecamatan_id" class="form-select">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kc)
                                <option value="{{ $kc->id }}">{{ $kc->nama_kecamatan }}</option>
                            @endforeach
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