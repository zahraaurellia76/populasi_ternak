@extends('layouts.admin_kabupaten')

@section('title', 'Rekapitulasi Data - Simnak')

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

    .card-stat-dark {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stat-dark:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(30, 41, 59, 0.25) !important;
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

    /* Header Tabel: Gradasi Emerald Cerah */
    .table-rekap thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.88rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px !important;
        border: none !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Isi Tabel */
    .table-rekap tbody td {
        font-size: 0.85rem !important;
        padding: 12px 16px !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    .table-rekap tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-rekap tbody tr:hover {
        background-color: #ecfdf5 !important;
    }

    /* Baris Total Kabupaten */
    .tfoot-total {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        font-weight: 800;
        border-top: 2px solid #10b981 !important;
    }

    .tfoot-total td {
        font-size: 0.9rem !important;
        padding: 14px 16px !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Form Filter Inputs */
    .form-select-custom {
        border: 1.5px solid #a7f3d0 !important;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .form-select-custom:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Card Elevation */
    .card-dashboard-modern {
        background-color: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.12) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-dashboard-modern:hover {
        box-shadow: 0 10px 25px rgba(4, 120, 87, 0.08) !important;
    }

    /* Export Buttons Soft Modern */
    .btn-pdf-soft {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 18px;
        transition: all 0.2s ease;
    }
    .btn-pdf-soft:hover {
        background-color: #fca5a5 !important;
        color: #991b1b !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
    }

    .btn-excel-soft {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        border: none;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 18px;
        transition: all 0.2s ease;
    }
    .btn-excel-soft:hover {
        background-color: #86efac !important;
        color: #166534 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
    }
</style>

<div class="container-fluid py-3">

    <!-- Header Judul -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-chart-column text-warning me-2"></i>Rekapitulasi Data Populasi Ternak
            </h3>
            <p class="text-muted small mb-0">Matriks rekapitulasi sebaran populasi ternak per kecamatan di Kabupaten Kediri</p>
        </div>
        <div>
            <span class="badge rounded-pill bg-white text-dark shadow-sm px-3 py-2 border fw-semibold fs-7">
                <i class="fa-solid fa-calendar-check text-success me-2"></i>Periode Laporan: <strong>Triwulan {{ $triwulanSelected ?? 'Semua' }} - {{ $tahunSelected }}</strong>
            </span>
        </div>
    </div>

    @php 
        $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
        $totalEkorKeseluruhan = 0;
        foreach($rekap as $kc) {
            foreach($jenisTernaks as $jt) {
                $jml = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                $grandTotals[$jt->id] += $jml;
                $totalEkorKeseluruhan += $jml;
            }
        }
    @endphp

    <!-- Stat Cards Gradasi Khas Dashboard Admin -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-emerald p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Wilayah</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ count($rekap) }} <span class="fs-7 fw-normal text-white-50">Kecamatan</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-dark p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Jenis Ternak</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ count($jenisTernaks) }} <span class="fs-7 fw-normal text-white-50">Kategori</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 card-stat-blue p-1 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Populasi Kabupaten</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ number_format($totalEkorKeseluruhan, 0, ',', '.') }} <span class="fs-7 fw-normal text-white-50">Ekor</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-calculator"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Filter & Tombol Action -->
    <div class="card card-dashboard-modern border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                
                <form method="GET" action="{{ route('admin.kabupaten.rekapitulasi') }}" class="d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">
                            <i class="fa-solid fa-calendar-quarter me-1"></i>Pilih Triwulan
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

                    <div>
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">
                            <i class="fa-solid fa-calendar me-1"></i>Pilih Tahun Rekap
                        </label>
                        <select name="tahun" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2" onchange="this.form.submit()">
                            @for($t = date('Y'); $t >= 2018; $t--)
                                <option value="{{ $t }}" {{ (isset($tahunSelected) && $tahunSelected == $t) ? 'selected' : '' }}>
                                    Tahun {{ $t }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    @if(!empty($triwulanSelected))
                        <div>
                            <a href="{{ route('admin.kabupaten.rekapitulasi', ['tahun' => $tahunSelected]) }}" class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold py-2">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                            </a>
                        </div>
                    @endif
                </form>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.kabupaten.rekapitulasi.pdf', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
                       class="btn btn-pdf-soft d-flex align-items-center gap-2 shadow-sm" target="_blank">
                        <i class="fa-solid fa-file-pdf fs-6"></i>
                        <span>Cetak PDF</span>
                    </a>
                    <a href="{{ route('admin.kabupaten.rekapitulasi.excel', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
                       class="btn btn-excel-soft d-flex align-items-center gap-2 shadow-sm">
                        <i class="fa-solid fa-file-excel fs-6"></i>
                        <span>Export Excel</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi -->
    <div class="card card-dashboard-modern border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="border-left: 5px solid #10b981 !important;">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="fa-solid fa-table-list text-success me-2 fs-5"></i>Matriks Rekapitulasi Populasi Ternak
            </h6>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1.5 rounded-pill fs-8">
                <i class="fa-solid fa-circle-check me-1"></i>Laporan Resmi
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-rekap mb-0">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 60px;" class="py-3 px-2">NO</th>
                            <th class="py-3 px-3 text-start">NAMA KECAMATAN</th>
                            @foreach($jenisTernaks as $jt)
                                <th class="py-3 px-3">{{ strtoupper($jt->nama_ternak) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($rekap as $index => $kc)
                            <tr>
                                <td class="text-center fw-semibold text-secondary px-2">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    <i class="fa-solid fa-building-flag text-success opacity-75 me-2"></i>
                                    {{ strtoupper($kc->nama_kecamatan) }}
                                </td>
                                @foreach($jenisTernaks as $jt)
                                    @php
                                        $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                                    @endphp
                                    <td class="text-end px-3 fw-semibold text-secondary">
                                        {{ number_format($jumlah, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($jenisTernaks) + 2 }}" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 text-success opacity-25"></i>
                                    <p class="mb-0 fw-semibold">Data rekapitulasi populasi tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <tfoot class="tfoot-total">
                        <tr>
                            <td colspan="2" class="text-center text-uppercase py-3 px-3">
                                <i class="fa-solid fa-calculator me-2"></i>TOTAL KABUPATEN KEDIRI
                            </td>
                            @foreach($jenisTernaks as $jt)
                                <td class="text-end py-3 px-3">
                                    {{ number_format($grandTotals[$jt->id] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection