@extends('layouts.admin')

@section('content')
<style>
    /* Custom Input Form Filter Focus & Border */
    .form-select-custom {
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }
    .form-select-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Header Tabel: Gradasi Emerald Cerah & Font Judul Lebih Besar */
    .table-custom thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.9rem !important;
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

    /* Soft Button Style Sesuai Gambar Contoh */
    .btn-export-pdf {
        background-color: #ffe4e6 !important; /* Soft Red/Pink Background */
        color: #dc2626 !important;            /* Teks Merah */
        border: none;
        font-weight: 600;
        border-radius: 12px;
        padding: 8px 18px;
        transition: all 0.2s ease-in-out;
    }
    .btn-export-pdf:hover {
        background-color: #fecdd3 !important;
        color: #b91c1c !important;
        transform: translateY(-1px);
    }

    .btn-export-excel {
        background-color: #dcfce7 !important; /* Soft Green Background */
        color: #15803d !important;            /* Teks Hijau */
        border: none;
        font-weight: 600;
        border-radius: 12px;
        padding: 8px 18px;
        transition: all 0.2s ease-in-out;
    }
    .btn-export-excel:hover {
        background-color: #bbf7d0 !important;
        color: #166534 !important;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid py-3 px-4">

    <!-- Header Judul -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
            <i class="fa-solid fa-file-invoice text-warning me-2"></i>Rekapitulasi Data Populasi Ternak
        </h3>
        <p class="text-muted small mb-0">Laporan ringkasan rekapitulasi data populasi ternak per kategori dan periode</p>
    </div>

    <!-- Card Filter & Tombol Export -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('admin.kecamatan.rekapitulasi') }}" method="GET" id="filterForm">
                <div class="row align-items-end g-3">
                    <!-- Dropdown Pilih Triwulan -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">Pilih Triwulan</label>
                        <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="document.getElementById('filterForm').submit()">
                            <option value="" {{ request('triwulan') == '' ? 'selected' : '' }}>-- Semua Triwulan --</option>
                            <option value="1" {{ request('triwulan') == '1' ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                            <option value="2" {{ request('triwulan') == '2' ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                            <option value="3" {{ request('triwulan') == '3' ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                            <option value="4" {{ request('triwulan') == '4' ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                        </select>
                    </div>

                    <!-- Dropdown Pilih Tahun Rekap -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">Pilih Tahun Rekap</label>
                        <select name="tahun" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="document.getElementById('filterForm').submit()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Tombol Export Soft Style Sesuai Gambar -->
                    <div class="col-md-6 d-flex justify-content-md-end gap-2 ms-auto">
                        <a href="{{ route('admin.kecamatan.rekapitulasi.pdf', ['tahun' => $tahun, 'triwulan' => request('triwulan')]) }}" 
                           target="_blank" 
                           class="btn btn-export-pdf d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf fs-6"></i>
                            <span>Cetak PDF</span>
                        </a>
                        <a href="{{ route('admin.kecamatan.rekapitulasi.excel', ['tahun' => $tahun, 'triwulan' => request('triwulan')]) }}" 
                           class="btn btn-export-excel d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-excel fs-6"></i>
                            <span>Export Excel</span>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Rekapitulasi -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-custom mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th class="px-3">JENIS TERNAK</th>
                            <th class="text-end px-4" style="width: 250px;">JUMLAH POPULASI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($jenisTernaks as $jt)
                            @php
                                $query = $populasi->where('jenis_ternak_id', $jt->id);
                                if (request('triwulan')) {
                                    $query = $query->where('triwulan', request('triwulan'));
                                }
                                $jumlah = $query->sum('jumlah');
                            @endphp
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    {{ $jt->nama_ternak }}
                                </td>
                                <td class="text-end fw-bold fs-6 px-4" style="color: #047857;">
                                    {{ number_format($jumlah, 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25" style="color: #047857;"></i>
                                    <p class="mb-0 small fw-semibold">Data rekapitulasi belum tersedia.</p>
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