@extends('layouts.admin_kabupaten')

@section('title', 'Rekapitulasi Data - Simnak')

@section('content')
<style>
    /* Header Tabel: Gradasi Emerald Cerah & Font Judul Lebih Besar */
    .table-rekap thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.9rem !important; /* Font Judul Lebih Besar */
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px !important;
        border: none !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Isi Tabel (Font Lebih Kecil) */
    .table-rekap tbody td {
        font-size: 0.85rem !important; /* Font Isi Lebih Kecil */
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
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }

    .form-select-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Export Buttons Soft Modern */
    .btn-pdf-soft {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.2s ease;
    }
    .btn-pdf-soft:hover {
        background-color: #fca5a5 !important;
        color: #991b1b !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
    }

    .btn-excel-soft {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.2s ease;
    }
    .btn-excel-soft:hover {
        background-color: #86efac !important;
        color: #166534 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.2);
    }
</style>

<div class="container-fluid py-3">

    <!-- Header Judul -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
            <i class="fa-solid fa-chart-column text-warning me-2"></i>Rekapitulasi Data Populasi Ternak
        </h3>
        <p class="text-muted small mb-0">Matriks rekapitulasi sebaran populasi ternak per kecamatan di Kabupaten Kediri</p>
    </div>

    <!-- Card Filter & Tombol Action -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3">
                
                <!-- Form Filter -->
                <form method="GET" action="{{ route('admin.kabupaten.rekapitulasi') }}" class="d-flex flex-wrap align-items-end gap-3">
                    <div>
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">
                            <i class="fa-solid fa-calendar-quarter me-1"></i>Pilih Triwulan
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

                    <div>
                        <label class="form-label fw-bold small mb-1" style="color: #047857;">
                            <i class="fa-solid fa-calendar me-1"></i>Pilih Tahun Rekap
                        </label>
                        <select name="tahun" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
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

                <!-- Tombol Cetak PDF & Excel -->
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.kabupaten.rekapitulasi.pdf', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
                       class="btn btn-pdf-soft d-flex align-items-center gap-2" target="_blank">
                        <i class="fa-solid fa-file-pdf fs-6"></i>
                        <span>Cetak PDF</span>
                    </a>
                    <a href="{{ route('admin.kabupaten.rekapitulasi.excel', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
                       class="btn btn-excel-soft d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-excel fs-6"></i>
                        <span>Export Excel</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
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
                        @php 
                            $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
                        @endphp

                        @forelse($rekap as $index => $kc)
                            <tr>
                                <td class="text-center fw-semibold text-secondary px-2">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    {{ strtoupper($kc->nama_kecamatan) }}
                                </td>
                                @foreach($jenisTernaks as $jt)
                                    @php
                                        $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                                        $grandTotals[$jt->id] += $jumlah;
                                    @endphp
                                    <td class="text-end px-3 fw-semibold text-secondary">
                                        {{ number_format($jumlah, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($jenisTernaks) + 2 }}" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-semibold">Data rekapitulasi populasi tidak ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <!-- Footer Total Kabupaten -->
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