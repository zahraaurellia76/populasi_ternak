@extends('layouts.app')

@section('title', 'Data Populasi Ternak Kabupaten Kediri')

@section('content')
<style>
    /* Hero Section Gradient dengan Glow & Soft Elevation */
    .hero-section {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        padding: 3.5rem 1rem 4.5rem 1rem;
        border-radius: 0 0 32px 32px;
        margin-bottom: -2.5rem;
        box-shadow: 0 12px 32px rgba(16, 185, 129, 0.25);
        position: relative;
    }

    /* Stat Card Overlay di Atas Hero */
    .stat-card-hero {
        background: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.15) !important;
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05) !important;
    }
    .stat-card-hero:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.18) !important;
    }

    /* Card Modern Elevation */
    .card-modern {
        background-color: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.12) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(4, 120, 87, 0.1) !important;
    }

    /* Filter Inputs Border & Focus Glow */
    .form-select-custom, .form-control-custom {
        border: 1.5px solid #a7f3d0 !important;
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
    }
    .form-select-custom:focus, .form-control-custom:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Header Tabel: Gradasi Emerald Cerah */
    .table-public thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 0.9rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 16px 18px !important;
        vertical-align: middle;
    }

    /* Isi Tabel (Font & Hover Interactive) */
    .table-public tbody td {
        font-size: 0.85rem !important;
        padding: 14px 18px !important;
        vertical-align: middle;
        transition: background-color 0.2s ease;
    }

    .table-public tbody tr {
        transition: all 0.2s ease;
    }

    .table-public tbody tr:hover {
        background-color: #ecfdf5 !important;
    }

    /* Custom Badge Periode Soft */
    .badge-periode-soft {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(21, 128, 61, 0.08);
    }

    /* Custom Button Filter Emerald Elevation */
    .btn-filter-custom {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease-in-out;
    }

    .btn-filter-custom:hover {
        background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35) !important;
    }

    /* Highlight Card Grafik */
    .card-chart-accent {
        border-left: 5px solid #10b981 !important;
    }

    /* Icon Box Soft Color */
    .icon-box-soft {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* CSS Khusus Cetak Halaman (Ctrl + P) */
    @media print {
        .hero-section,
        .stat-section,
        .card:has(form),
        .btn,
        nav,
        footer,
        .no-print {
            display: none !important;
        }

        body {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .table-public thead th {
            background-color: #10b981 !important;
            color: #ffffff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<!-- Hero Section Header -->
<div class="hero-section text-center">
    <div class="container py-2">
        <span class="badge bg-white text-success fw-bold px-3 py-2 rounded-pill shadow-sm mb-3">
            <i class="fa-solid fa-chart-line me-1"></i> Portal Informasi Resmi Publik
        </span>
        <h1 class="fw-bold display-5 mb-2" style="letter-spacing: -0.8px;">
            <i class="fa-solid fa-cow me-2 text-warning"></i>Data Populasi Ternak Kabupaten Kediri
        </h1>
        <p class="lead fs-6 text-white-50 mb-0 mx-auto" style="max-width: 700px;">
            Laporan rekapitulasi data dan statistik perkembangan populasi ternak resmi per kecamatan di wilayah Kabupaten Kediri.
        </p>
    </div>
</div>

<div class="container my-4" style="position: relative; z-index: 10;">

    @php
        // Perhitungan Total Terdata yang presisi (menangani Paginator maupun Collection)
        $totalTerdata = 0;
        if (isset($populasi)) {
            if (method_exists($populasi, 'items')) {
                $totalTerdata = collect($populasi->items())->sum('jumlah');
            } elseif (method_exists($populasi, 'sum')) {
                $totalTerdata = $populasi->sum('jumlah');
            }
        }
    @endphp

    <!-- Stat Cards Summary (Daftar Ringkasan Metric) -->
    <div class="row g-3 mb-4 stat-section">
        <div class="col-6 col-md-3">
            <div class="stat-card-hero d-flex align-items-center gap-3">
                <div class="icon-box-soft" style="background-color: #dcfce7; color: #059669;">
                    <i class="fa-solid fa-paw"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Terdata</div>
                    <div class="fs-5 fw-bold text-dark">
                        {{ number_format($totalTerdata, 0, ',', '.') }} <span class="fs-8 text-muted fw-normal">Ekor</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-hero d-flex align-items-center gap-3">
                <div class="icon-box-soft" style="background-color: #e0f2fe; color: #0284c7;">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Kecamatan</div>
                    <div class="fs-5 fw-bold text-dark">{{ isset($kecamatans) ? count($kecamatans) : 0 }} <span class="fs-8 text-muted fw-normal">Wilayah</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-hero d-flex align-items-center gap-3">
                <div class="icon-box-soft" style="background-color: #fef3c7; color: #d97706;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Tahun Rekap</div>
                    <div class="fs-5 fw-bold text-dark">{{ $selectedTahun ?? date('Y') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card-hero d-flex align-items-center gap-3">
                <div class="icon-box-soft" style="background-color: #ffe4e6; color: #e11d48;">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Triwulan</div>
                    <div class="fs-5 fw-bold text-dark">Ke-{{ $selectedTriwulan ?? '1' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Form Filter Data -->
    <div class="card card-modern shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="fa-solid fa-sliders text-success fs-5"></i>
                <h6 class="fw-bold mb-0 text-dark">Filter Pencarian Data Populasi</h6>
            </div>
            <form action="{{ route('public.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-location-dot me-1"></i>Pilih Kecamatan
                    </label>
                    <select name="kecamatan_id" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2">
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ (isset($selectedKecamatanId) && $selectedKecamatanId == $kec->id) ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar me-1"></i>Tahun Rekap
                    </label>
                    <input type="number" name="tahun" class="form-control form-control-custom text-dark fw-semibold fs-7 py-2" value="{{ $selectedTahun ?? date('Y') }}" placeholder="Masukkan Tahun">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar-quarter me-1"></i>Periode Triwulan
                    </label>
                    <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7 py-2">
                        <option value="1" {{ (isset($selectedTriwulan) && $selectedTriwulan == '1') ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                        <option value="2" {{ (isset($selectedTriwulan) && $selectedTriwulan == '2') ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                        <option value="3" {{ (isset($selectedTriwulan) && $selectedTriwulan == '3') ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                        <option value="4" {{ (isset($selectedTriwulan) && $selectedTriwulan == '4') ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter-custom w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Filter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Tabel Data Rekapitulasi -->
    <div class="card card-modern shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-0 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                <i class="fa-solid fa-table-list me-2 text-success"></i>Tabel Rekapitulasi Populasi Ternak
            </h6>
            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill fs-8">
                <i class="fa-solid fa-circle-check me-1"></i>Data Terverifikasi
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-public mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">NO</th>
                            <th class="px-3">KECAMATAN</th>
                            <th class="px-3">JENIS TERNAK</th>
                            <th class="text-center" style="width: 200px;">PERIODE</th>
                            <th class="text-end px-4" style="width: 220px;">JUMLAH POPULASI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($populasi as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ method_exists($populasi, 'firstItem') ? $populasi->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    <i class="fa-solid fa-building-flag me-2 text-success opacity-75"></i>
                                    {{ $item->kecamatan->nama_kecamatan ?? '-' }}
                                </td>
                                <td class="px-3 text-dark fw-semibold">
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-2 me-1">
                                        <i class="fa-solid fa-paw text-warning me-1"></i>
                                        {{ $item->jenisTernak->nama_ternak ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-periode-soft">
                                        • Triwulan {{ $item->triwulan }} - {{ $item->tahun }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold fs-6 px-4" style="color: #047857;">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25" style="color: #047857;"></i>
                                    <p class="mb-0 fw-semibold">Belum ada data populasi resmi untuk kecamatan ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($populasi, 'hasPages') && $populasi->hasPages())
            <div class="card-footer bg-white border-0 d-flex justify-content-end py-3 px-4">
                {{ $populasi->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Card Grafik Visualisasi -->
    <div class="card card-modern card-chart-accent shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent py-3 px-4 border-0 d-flex align-items-center justify-content-between">
            <h6 class="card-title mb-0 fw-bold text-dark">
                <i class="fa-solid fa-chart-column me-2 text-success"></i>Grafik Visualisasi Populasi Ternak
            </h6>
            <small class="text-muted fw-semibold">Grafik Batang Komparasi Data</small>
        </div>
        <div class="card-body p-4">
            @if(isset($chartData) && count($chartData) > 0)
                <div style="position: relative; height: 360px; width: 100%;">
                    <canvas id="populasiChart"></canvas>
                </div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-chart-line fa-2x mb-2 text-secondary opacity-50"></i>
                    <p class="mb-0 small fw-semibold">Grafik tidak dapat ditampilkan karena belum ada data.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = @json($chartLabels ?? []);
        const dataValues = @json($chartData ?? []);

        if (labels.length > 0) {
            const ctx = document.getElementById('populasiChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.15)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Populasi (Ekor)',
                        data: dataValues,
                        backgroundColor: gradient,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            backgroundColor: '#047857',
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    return 'Total: ' + new Intl.NumberFormat('id-ID').format(context.raw) + ' ekor';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush