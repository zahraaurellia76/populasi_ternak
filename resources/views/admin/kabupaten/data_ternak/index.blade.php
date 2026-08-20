@extends('layouts.admin_kabupaten')

@section('title', 'Data Ternak - Simnak')

@section('content')
<style>
    /* Stat Cards Gradasi Khas Dashboard */
    .card-stat-emerald {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stat-emerald:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(16, 185, 129, 0.25) !important;
    }

    .card-stat-blue {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stat-blue:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(2, 132, 199, 0.25) !important;
    }

    .card-stat-amber {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stat-amber:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(217, 119, 6, 0.25) !important;
    }

    .icon-box-stat {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(8px);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff !important;
        flex-shrink: 0;
    }

    /* Header Tabel: Gradasi Emerald Cerah Khas Dashboard */
    .table-custom thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.88rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px !important;
        border: none !important;
        vertical-align: middle;
    }

    /* Isi Tabel */
    .table-custom tbody td {
        font-size: 0.85rem !important;
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
        border: 1.5px solid #a7f3d0 !important;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }
    
    .form-select-custom:focus, .form-control-custom:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Card Elevation Khas Dashboard */
    .card-dashboard-modern {
        background-color: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.12) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-dashboard-modern:hover {
        box-shadow: 0 10px 25px rgba(4, 120, 87, 0.08) !important;
    }

    /* Tombol Utama (Gradasi Emerald) */
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
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
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
        box-shadow: 0 2px 5px rgba(21, 128, 61, 0.08);
    }
</style>

<div class="container-fluid py-3">

    <!-- Header Judul & Tombol Tambah Data -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-cow text-warning me-2"></i>Data Populasi Ternak
            </h3>
            <p class="text-muted small mb-0">Kelola dan pantau seluruh data rekapitulasi populasi ternak tingkat kecamatan</p>
        </div>
        <button class="btn btn-emerald fw-bold px-4 py-2.5 rounded-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTambahData">
            <i class="fa-solid fa-plus-circle fs-6"></i>
            <span>Tambah Data Ternak</span>
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert" style="background-color: #dcfce7; color: #15803d;">
            <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i>
            <span class="fw-semibold align-middle">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $totalRecord = method_exists($populasi, 'total') ? $populasi->total() : count($populasi);
        $totalEkorTernak = method_exists($populasi, 'items') ? collect($populasi->items())->sum('jumlah') : $populasi->sum('jumlah');
    @endphp

    <!-- Stat Cards Gradasi Khas Dashboard Admin -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-emerald p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Record Data</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ number_format($totalRecord) }} <span class="fs-7 fw-normal text-white-50">Baris</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-database"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-blue p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Populasi (Halaman Ini)</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ number_format($totalEkorTernak, 0, ',', '.') }} <span class="fs-7 fw-normal text-white-50">Ekor</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-paw"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-amber p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Filter Terpasang</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ $tahunSelected ?? date('Y') }} <span class="fs-7 fw-normal text-white-50">{{ $triwulanSelected ? '• TW '.$triwulanSelected : '• Semua TW' }}</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-filter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Form Filter Data -->
    <div class="card card-dashboard-modern border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-sliders text-success fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Filter Pencarian Data Ternak</h6>
            </div>
            <form method="GET" action="{{ route('admin.kabupaten.data_ternak') }}" class="row g-3 align-items-end">
                
                <div class="col-md-4">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-location-dot me-1"></i>Pilih Kecamatan
                    </label>
                    <select name="kecamatan_id" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2" onchange="this.form.submit()">
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
                        <i class="fa-solid fa-calendar-quarter me-1"></i>Periode Triwulan
                    </label>
                    <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2" onchange="this.form.submit()">
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
                        <i class="fa-solid fa-calendar me-1"></i>Tahun Rekap
                    </label>
                    <select name="tahun" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2" onchange="this.form.submit()">
                        @for($t = date('Y'); $t >= 2018; $t--)
                            <option value="{{ $t }}" {{ (isset($tahunSelected) && $tahunSelected == $t) ? 'selected' : '' }}>
                                Tahun {{ $t }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('admin.kabupaten.data_ternak') }}" class="btn btn-outline-secondary w-100 fw-semibold rounded-3 py-2 d-flex align-items-center justify-content-center gap-1">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset</span>
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Card Tabel Data Rekapitulasi -->
    <div class="card card-dashboard-modern shadow-sm border-0 rounded-4 mb-4 overflow-hidden bg-white" style="border-left: 5px solid #10b981 !important;">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="fa-solid fa-table-list text-success me-2 fs-5"></i>Daftar Master Data Populasi Ternak
            </h6>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1.5 rounded-pill fs-8">
                <i class="fa-solid fa-database me-1"></i>Simnak Database
            </span>
        </div>
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
                                    <i class="fa-solid fa-building-flag text-success opacity-75 me-2"></i>
                                    {{ strtoupper($item->kecamatan->nama_kecamatan ?? '-') }}
                                </td>
                                <td class="text-dark fw-semibold">
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-2">
                                        <i class="fa-solid fa-paw text-warning me-1"></i>
                                        {{ $item->jenisTernak->nama_ternak ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-soft-emerald">
                                        • Triwulan {{ $item->triwulan ?? 1 }}
                                    </span>
                                </td>
                                <td class="text-center text-secondary font-monospace fw-bold">
                                    {{ $item->tahun }}
                                </td>
                                <td class="text-end fw-bold fs-6" style="color: #047857; padding-right: 24px !important;">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn btn-action-edit btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditData{{ $item->id }}" title="Edit Data">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>

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
                                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Data Populasi Ternak
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Wilayah Kecamatan</label>
                                                    <select name="kecamatan_id" class="form-select rounded-3 py-2 fs-7" required>
                                                        @foreach($kecamatans as $kc)
                                                            <option value="{{ $kc->id }}" {{ $item->kecamatan_id == $kc->id ? 'selected' : '' }}>
                                                                {{ $kc->nama_kecamatan }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Jenis Hewan Ternak</label>
                                                    <select name="jenis_ternak_id" class="form-select rounded-3 py-2 fs-7" required>
                                                        @foreach($jenisTernaks as $jt)
                                                            <option value="{{ $jt->id }}" {{ $item->jenis_ternak_id == $jt->id ? 'selected' : '' }}>
                                                                {{ $jt->nama_ternak }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="row g-2 mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small text-dark">Periode Triwulan</label>
                                                        <select name="triwulan" class="form-select rounded-3 py-2 fs-7" required>
                                                            @for($tw = 1; $tw <= 4; $tw++)
                                                                <option value="{{ $tw }}" {{ ($item->triwulan ?? 1) == $tw ? 'selected' : '' }}>
                                                                    Triwulan {{ $tw }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label fw-bold small text-dark">Tahun Rekap</label>
                                                        <input type="number" name="tahun" class="form-control rounded-3 py-2 fs-7" value="{{ $item->tahun }}" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Jumlah Populasi (Ekor)</label>
                                                    <input type="number" name="jumlah" class="form-control rounded-3 py-2 fs-7" value="{{ $item->jumlah }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                                                <button type="button" class="btn btn-light rounded-3 fw-semibold small px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-emerald rounded-3 fw-semibold small px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25" style="color: #047857;"></i>
                                    <p class="mb-0 fw-semibold">Belum ada data populasi ternak yang ditemukan.</p>
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
                        <i class="fa-solid fa-plus-circle me-2"></i>Tambah Data Populasi Ternak Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Wilayah Kecamatan</label>
                        <select name="kecamatan_id" class="form-select rounded-3 py-2 fs-7" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kc)
                                <option value="{{ $kc->id }}">{{ $kc->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Jenis Hewan Ternak</label>
                        <select name="jenis_ternak_id" class="form-select rounded-3 py-2 fs-7" required>
                            <option value="">-- Pilih Jenis Ternak --</option>
                            @foreach($jenisTernaks as $jt)
                                <option value="{{ $jt->id }}">{{ $jt->nama_ternak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-dark">Periode Triwulan</label>
                            <select name="triwulan" class="form-select rounded-3 py-2 fs-7" required>
                                <option value="1">Triwulan 1 (Jan - Mar)</option>
                                <option value="2">Triwulan 2 (Apr - Jun)</option>
                                <option value="3">Triwulan 3 (Jul - Sep)</option>
                                <option value="4">Triwulan 4 (Okt - Des)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-dark">Tahun Rekap</label>
                            <input type="number" name="tahun" class="form-control rounded-3 py-2 fs-7" value="{{ date('Y') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Jumlah Populasi (Ekor)</label>
                        <input type="number" name="jumlah" class="form-control rounded-3 py-2 fs-7" placeholder="Contoh: 1500" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 fw-semibold small px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-emerald rounded-3 fw-semibold small px-4">Simpan Data Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection