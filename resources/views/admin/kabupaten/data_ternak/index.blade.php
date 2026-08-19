@extends('layouts.admin_kabupaten')

@section('title', 'Data Ternak')

@section('content')
<style>
    /* Header Tabel: Gradasi Emerald Cerah (Persis Dashboard Admin) */
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

    /* Form Filter Inputs */
    .form-select-custom, .form-control-custom {
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }
    
    .form-select-custom:focus, .form-control-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
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

    /* Soft Badge Triwulan */
    .badge-soft-emerald {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
    }
</style>

<div class="container-fluid py-3">

    <!-- Header & Tombol Tambah Data -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-cow text-warning me-2"></i>Data Populasi Ternak
            </h4>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh data populasi ternak tingkat kecamatan</p>
        </div>
        <button class="btn btn-emerald fw-bold px-4 py-2 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahData">
            <i class="fa-solid fa-plus-circle"></i>
            <span>Tambah Data Ternak</span>
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #15803d;">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Form Filter Data -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.kabupaten.data_ternak') }}" class="row g-3 align-items-end">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-location-dot me-1"></i>Kecamatan
                    </label>
                    <select name="kecamatan_id" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
                        <option value="">-- Semua Kecamatan --</option>
                        @foreach($kecamatans as $kc)
                            <option value="{{ $kc->id }}" {{ (isset($kecamatanSelected) && $kecamatanSelected == $kc->id) ? 'selected' : '' }}>
                                {{ $kc->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar-quarter me-1"></i>Triwulan
                    </label>
                    <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
                        <option value="">-- Semua Triwulan --</option>
                        @for($t = 1; $t <= 4; $t++)
                            <option value="{{ $t }}" {{ (isset($triwulanSelected) && $triwulanSelected == $t) ? 'selected' : '' }}>
                                Triwulan {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar me-1"></i>Tahun
                    </label>
                    <select name="tahun" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
                        @for($t = date('Y'); $t >= 2018; $t--)
                            <option value="{{ $t }}" {{ (isset($tahunSelected) && $tahunSelected == $t) ? 'selected' : '' }}>
                                Tahun {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.kabupaten.data_ternak') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3 py-2">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Card Tabel Data Rekapitulasi -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th>KECAMATAN</th>
                            <th>JENIS TERNAK</th>
                            <th class="text-center" style="width: 160px;">PERIODE</th>
                            <th class="text-center" style="width: 110px;">TAHUN</th>
                            <th class="text-end" style="padding-right: 24px !important;">JUMLAH POPULASI</th>
                            <th class="text-center" style="width: 120px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($populasi as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ method_exists($populasi, 'firstItem') ? $populasi->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="fw-bold text-dark">
                                    {{ strtoupper($item->kecamatan->nama_kecamatan ?? '-') }}
                                </td>
                                <td class="text-secondary fw-semibold">
                                    {{ $item->jenisTernak->nama_ternak ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-soft-emerald">
                                        • Triwulan {{ $item->triwulan ?? 1 }}
                                    </span>
                                </td>
                                <td class="text-center text-secondary font-monospace">
                                    {{ $item->tahun }}
                                </td>
                                <td class="text-end fw-bold fs-6" style="color: #047857; padding-right: 24px !important;">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <!-- Edit Button -->
                                        <button class="btn btn-action-edit btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditData{{ $item->id }}" title="Edit Data">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.kabupaten.data_ternak.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-action-delete btn-sm" title="Hapus Data">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Edit Data -->
                            <div class="modal fade" id="modalEditData{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                        <form action="{{ route('admin.kabupaten.data_ternak.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                                                <h5 class="modal-title fw-bold fs-6">
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Populasi
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Kecamatan</label>
                                                    <select name="kecamatan_id" class="form-select rounded-3" required>
                                                        @foreach($kecamatans as $kc)
                                                            <option value="{{ $kc->id }}" {{ $item->kecamatan_id == $kc->id ? 'selected' : '' }}>
                                                                {{ $kc->nama_kecamatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Jenis Ternak</label>
                                                    <select name="jenis_ternak_id" class="form-select rounded-3" required>
                                                        @foreach($jenisTernaks as $jt)
                                                            <option value="{{ $jt->id }}" {{ $item->jenis_ternak_id == $jt->id ? 'selected' : '' }}>
                                                                {{ $jt->nama_ternak }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold small text-muted">Triwulan</label>
                                                        <select name="triwulan" class="form-select rounded-3" required>
                                                            @for($tw = 1; $tw <= 4; $tw++)
                                                                <option value="{{ $tw }}" {{ ($item->triwulan ?? 1) == $tw ? 'selected' : '' }}>
                                                                    Triwulan {{ $tw }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-semibold small text-muted">Tahun</label>
                                                        <input type="number" name="tahun" class="form-control rounded-3" value="{{ $item->tahun }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Jumlah (Ekor)</label>
                                                    <input type="number" name="jumlah" class="form-control rounded-3" value="{{ $item->jumlah }}" required>
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
                                <td colspan="7" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25"></i>
                                    <p class="mb-0 small fw-semibold">Belum ada data populasi ternak yang ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(method_exists($populasi, 'hasPages') && $populasi->hasPages())
        <div class="d-flex justify-content-end mt-3">
            {{ $populasi->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="modalTambahData" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.kabupaten.data_ternak.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <h5 class="modal-title fw-bold fs-6">
                        <i class="fa-solid fa-plus-circle me-2"></i>Tambah Data Populasi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Kecamatan</label>
                        <select name="kecamatan_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kc)
                                <option value="{{ $kc->id }}">{{ $kc->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Jenis Ternak</label>
                        <select name="jenis_ternak_id" class="form-select rounded-3" required>
                            <option value="">-- Pilih Jenis Ternak --</option>
                            @foreach($jenisTernaks as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_ternak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-muted">Triwulan</label>
                            <select name="triwulan" class="form-select rounded-3" required>
                                <option value="1">Triwulan 1</option>
                                <option value="2">Triwulan 2</option>
                                <option value="3">Triwulan 3</option>
                                <option value="4">Triwulan 4</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-muted">Tahun</label>
                            <input type="number" name="tahun" class="form-control rounded-3" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Jumlah (Ekor)</label>
                        <input type="number" name="jumlah" class="form-control rounded-3" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold small" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald rounded-3 fw-semibold small">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection