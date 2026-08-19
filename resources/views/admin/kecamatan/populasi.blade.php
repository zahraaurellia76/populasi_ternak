@extends('layouts.admin')

@section('content')
<style>
    /* Custom Input Form Focus & Border */
    .form-select-custom, .form-control-custom {
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }
    .form-select-custom:focus, .form-control-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Header Tabel: Gradasi Emerald Cerah & Font Judul Lebih Besar */
    .table-custom thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.9rem !important; /* Font Judul Lebih Besar dari Isi */
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

    /* Tombol Utama Emerald */
    .btn-emerald {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        border: none;
        transition: all 0.25s ease;
    }
    .btn-emerald:hover {
        background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        color: #ffffff;
        transform: translateY(-1px);
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

    /* Badge Periode Soft */
    .badge-periode-soft {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
    }
</style>

<div class="container-fluid py-3 px-4">

    <!-- Header Judul -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
            <i class="fa-solid fa-pen-to-square text-warning me-2"></i>Kelola Data Populasi Ternak
        </h3>
        <p class="text-muted small mb-0">Pencatatan dan pembaruan data jumlah populasi ternak berkala per periode</p>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #15803d;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Input Data Baru -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3" style="color: #047857;">
                <i class="fa-solid fa-circle-plus me-1"></i> Tambah Data Populasi Baru
            </h6>
            <form action="{{ route('admin.kecamatan.populasi.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold" style="color: #047857;">Jenis Ternak</label>
                        <select name="jenis_ternak_id" class="form-select form-select-custom text-dark fw-semibold fs-7" required>
                            @foreach($jenisTernaks as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_ternak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-bold" style="color: #047857;">Tahun</label>
                        <input type="number" name="tahun" class="form-control form-control-custom text-dark fw-semibold fs-7" value="{{ date('Y') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold" style="color: #047857;">Triwulan</label>
                        <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7" required>
                            <option value="1">Triwulan I (Jan - Mar)</option>
                            <option value="2">Triwulan II (Apr - Jun)</option>
                            <option value="3">Triwulan III (Jul - Sep)</option>
                            <option value="4">Triwulan IV (Okt - Des)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold" style="color: #047857;">Jumlah (Ekor)</label>
                        <div class="input-group">
                            <input type="number" name="jumlah" class="form-control form-control-custom text-dark fw-semibold fs-7" placeholder="Contoh: 1500" min="0" required>
                            <button type="submit" class="btn btn-emerald fw-bold px-4 rounded-end-3">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data & Fitur CRUD -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-clock-rotate-left text-success me-2"></i>Riwayat Input Populasi
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th class="px-3">JENIS TERNAK</th>
                            <th class="text-center" style="width: 200px;">PERIODE</th>
                            <th class="text-end px-4" style="width: 200px;">JUMLAH POPULASI</th>
                            <th class="text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($populasiList as $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    {{ $item->jenisTernak->nama_ternak ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-periode-soft">
                                        Triwulan {{ $item->triwulan }} - {{ $item->tahun }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold fs-6 px-4" style="color: #047857;">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <button class="btn btn-action-edit btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="Edit Data">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-action-delete btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus Data">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <form action="{{ route('admin.kecamatan.populasi.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                                <h5 class="modal-title fw-bold fs-6">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Populasi - {{ $item->jenisTernak->nama_ternak }}
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                                    <span class="small text-muted d-block">Periode Terdaftar:</span>
                                                    <strong class="text-dark">Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</strong>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Jumlah (Ekor)</label>
                                                    <input type="number" name="jumlah" class="form-control form-control-custom rounded-3 fw-bold text-dark" value="{{ $item->jumlah }}" min="0" required>
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

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <form action="{{ route('admin.kecamatan.populasi.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header border-0 bg-danger text-white p-4">
                                                <h5 class="modal-title fw-bold fs-6">
                                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <p class="mb-0 text-secondary">
                                                    Apakah Anda yakin ingin menghapus data populasi <strong>{{ $item->jenisTernak->nama_ternak }}</strong> periode <strong>Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</strong>?
                                                </p>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-semibold small" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger rounded-3 fw-semibold small">Hapus Data</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25" style="color: #047857;"></i>
                                    <p class="mb-0 small fw-semibold">Belum ada data populasi yang diinputkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection