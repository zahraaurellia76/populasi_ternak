@extends('layouts.admin_kabupaten')

@section('title', 'Kelola Kecamatan - Simnak')

@section('content')
<style>
    /* Styling Header Tabel Matching Theme & Font Lebih Besar */
    .table-custom thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.9rem !important; /* Font Judul Lebih Besar */
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px !important;
        border: none !important;
        vertical-align: middle;
    }

    /* Isi Tabel (Font Lebih Kecil) */
    .table-custom tbody td {
        font-size: 0.85rem !important; /* Font Isi Lebih Kecil */
        padding: 12px 16px !important;
        vertical-align: middle;
    }

    .table-custom tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-custom tbody tr:hover {
        background-color: #ecfdf5 !important;
    }

    /* Tombol Utama (Gradasi Cerah) */
    .btn-emerald {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        border: none;
        transition: all 0.25s ease;
    }

    .btn-emerald:hover {
        background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3) !important;
    }

    /* Action Buttons (Edit & Delete) */
    .btn-action-edit {
        background-color: #fef3c7 !important;
        color: #d97706 !important;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 12px;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-edit:hover {
        background-color: #fde68a !important;
        color: #b45309 !important;
        transform: translateY(-2px);
    }

    .btn-action-delete {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 6px 12px;
        transition: all 0.2s ease-in-out;
    }
    .btn-action-delete:hover {
        background-color: #fca5a5 !important;
        color: #991b1b !important;
        transform: translateY(-2px);
    }

    /* Badge Kode Kecamatan */
    .badge-kode {
        background-color: #e0f2fe;
        color: #0369a1;
        font-family: monospace;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 4px 12px;
        border-radius: 20px;
    }
</style>

<div class="container-fluid py-3">

    <!-- Header & Tombol Tambah Kecamatan -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-map-location-dot text-warning me-2"></i>Kelola Data Kecamatan
            </h4>
            <p class="text-muted small mb-0">Kelola daftar wilayah kecamatan yang terdaftar di Kabupaten Kediri</p>
        </div>
        <button class="btn btn-emerald fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahKecamatan">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Kecamatan</span>
        </button>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #15803d;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Tabel Data Kecamatan -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th class="text-center" style="width: 200px;">KODE KECAMATAN</th>
                            <th>NAMA KECAMATAN</th>
                            <th class="text-center" style="width: 120px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($kecamatans as $index => $kc)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ method_exists($kecamatans, 'firstItem') ? $kecamatans->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-kode">{{ $kc->kode_kecamatan ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ strtoupper($kc->nama_kecamatan) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-action-edit btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditKecamatan{{ $kc->id }}" title="Edit Kecamatan">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('admin.kabupaten.kecamatan.destroy', $kc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kecamatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete btn-sm" title="Hapus Kecamatan">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Kecamatan -->
                            <div class="modal fade" id="modalEditKecamatan{{ $kc->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <form action="{{ route('admin.kabupaten.kecamatan.update', $kc->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                                <h5 class="modal-title fw-bold fs-6">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Kecamatan
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Kode Kecamatan</label>
                                                    <input type="text" name="kode_kecamatan" class="form-control rounded-3" value="{{ $kc->kode_kecamatan }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Nama Kecamatan</label>
                                                    <input type="text" name="nama_kecamatan" class="form-control rounded-3" value="{{ $kc->nama_kecamatan }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-semibold small" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-emerald rounded-3 fw-semibold small">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25"></i>
                                    <p class="mb-0 small fw-semibold">Belum ada data kecamatan yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($kecamatans, 'hasPages') && $kecamatans->hasPages())
        <div class="d-flex justify-content-end mt-3">
            {{ $kecamatans->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

<!-- Modal Tambah Kecamatan Baru -->
<div class="modal fade" id="modalTambahKecamatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.kabupaten.kecamatan.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <h5 class="modal-title fw-bold fs-6">
                        <i class="fa-solid fa-plus-circle me-2"></i>Tambah Kecamatan Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Kode Kecamatan</label>
                        <input type="text" name="kode_kecamatan" class="form-control rounded-3" placeholder="Contoh: 35.06.01" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Nama Kecamatan</label>
                        <input type="text" name="nama_kecamatan" class="form-control rounded-3" placeholder="Contoh: MOJO" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold small" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald rounded-3 fw-semibold small">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection