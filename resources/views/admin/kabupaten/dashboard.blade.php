@extends('layouts.admin_kabupaten')

@section('title', 'Dashboard Admin Kabupaten - Simnak')

@section('content')
<style>
    /* Welcome Banner Styling */
    .welcome-banner {
        background: linear-gradient(135deg, #047857 0%, #10b981 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
    }

    /* Badge Tag khusus di dalam Banner */
    .banner-badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(4px);
    }

    /* Custom Styling Stat Card Gradasi Modern */
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

    .card-stat-amber {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        color: #ffffff;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-stat-amber:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(217, 119, 6, 0.25) !important;
    }

    /* Styling Box Ikon Transparan */
    .icon-box-stat {
        width: 54px;
        height: 54px;
        background: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(8px);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ffffff !important;
        flex-shrink: 0;
    }

    /* Styling Card Modern Elevation */
    .card-dashboard-modern {
        background-color: #ffffff;
        border: 1px solid rgba(16, 185, 129, 0.12) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-dashboard-modern:hover {
        box-shadow: 0 10px 25px rgba(4, 120, 87, 0.08) !important;
    }

    /* Header Tabel Rekapitulasi */
    .table-rekap-dashboard thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.88rem !important;
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border: none !important;
        white-space: nowrap;
        padding: 14px 16px !important;
        vertical-align: middle;
    }

    .table-rekap-dashboard tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-rekap-dashboard tbody tr:hover {
        background-color: #ecfdf5 !important;
    }

    .table-rekap-dashboard td {
        white-space: nowrap;
        font-size: 0.85rem !important;
        padding: 12px 16px !important;
        vertical-align: middle;
    }

    /* Style Baris Total Keseluruhan Footer */
    .tfoot-total-dashboard {
        background-color: #dcfce7 !important;
        color: #15803d !important;
        font-weight: 800;
        border-top: 2px solid #10b981 !important;
    }

    .tfoot-total-dashboard td {
        font-size: 0.9rem !important;
        padding: 14px 16px !important;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Soft Button Detail */
    .btn-detail-emerald {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 600;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-detail-emerald:hover {
        background-color: #10b981;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
    }
</style>

<div class="container-fluid py-3">

    <!-- Welcome Banner Header -->
    <div class="welcome-banner mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <span class="badge banner-badge fw-bold px-3 py-2 rounded-pill mb-2 fs-8">
                <i class="fa-solid fa-shield-halved me-1 text-warning"></i> Admin Kabupaten Portal
            </span>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">
                Selamat Datang, Administrator!
            </h3>
            <p class="text-white-50 small mb-0">Pusat kendali & integrasi data statistik populasi ternak Kabupaten Kediri</p>
        </div>
        <div>
            <span class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-3 border fw-semibold fs-7">
                <i class="fa-solid fa-calendar-check text-success me-2"></i>Periode: <strong>Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }}</strong>
            </span>
        </div>
    </div>

    @php 
        // Hitung Grand Total Keseluruhan untuk Stat Card
        $grandTotalsByJenis = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
        $totalEkorKeseluruhan = 0;
        foreach($rekap as $kc) {
            foreach($jenisTernaks as $jt) {
                $jml = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                $grandTotalsByJenis[$jt->id] += $jml;
                $totalEkorKeseluruhan += $jml;
            }
        }
    @endphp

    <!-- Stat Cards Section (4 Cards Ringkasan) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat-emerald p-2 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Wilayah</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ $totalKecamatan }} <span class="fs-7 fw-normal text-white-50">Kecamatan</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat-dark p-2 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Jenis Ternak</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ $totalJenisTernak }} <span class="fs-7 fw-normal text-white-50">Kategori</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat-blue p-2 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Populasi</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            {{ number_format($totalEkorKeseluruhan, 0, ',', '.') }} <span class="fs-7 fw-normal text-white-50">Ekor</span>
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-paw"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 card-stat-amber p-2 h-100">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Status Periode</div>
                        <h3 class="fw-bold my-0" style="letter-spacing: -0.5px;">
                            TW {{ $triwulanSelected }} - {{ $tahunSelected }}
                        </h3>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. TABEL REKAPITULASI POPULASI PER KECAMATAN -->
    <div class="card card-dashboard-modern border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="fa-solid fa-table-list text-success me-2 fs-5"></i>
                Rekapitulasi Populasi per Kecamatan (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})
            </h6>
            <a href="{{ route('admin.kabupaten.rekapitulasi') }}" class="btn btn-detail-emerald btn-sm rounded-3 px-3 py-2 d-flex align-items-center gap-1">
                <span>Lihat Detail Rekap</span>
                <i class="fa-solid fa-arrow-right fs-8"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle table-rekap-dashboard mb-0">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 60px;">NO</th>
                            <th class="text-start">KECAMATAN</th>
                            @foreach($jenisTernaks as $jt)
                                <th>{{ strtoupper($jt->nama_ternak) }}</th>
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
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25"></i>
                                    <p class="mb-0 small fw-semibold">Belum ada data kecamatan yang terdaftar.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    
                    <!-- FOOTER TOTAL KESELURUHAN -->
                    <tfoot class="tfoot-total-dashboard">
                        <tr>
                            <td colspan="2" class="text-center text-uppercase py-3 px-3">
                                <i class="fa-solid fa-calculator me-2"></i>TOTAL KESELURUHAN
                            </td>
                            @foreach($jenisTernaks as $jt)
                                <td class="text-end py-3 px-3">
                                    {{ number_format($grandTotalsByJenis[$jt->id] ?? 0, 0, ',', '.') }}
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- 2. GRAFIK TOTAL POPULASI PER JENIS TERNAK -->
    <div class="card card-dashboard-modern border-0 shadow-sm rounded-4 mb-4 bg-white" style="border-left: 5px solid #10b981 !important;">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center">
                <i class="fa-solid fa-chart-column text-success me-2 fs-5"></i>
                Grafik Total Populasi per Jenis Ternak (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})
            </h6>
            <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill small fw-semibold">
                Bar Chart Distribution
            </span>
        </div>
        <div class="card-body p-4">
            <div style="height: 340px; width: 100%;">
                <canvas id="grafikPopulasi"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('grafikPopulasi').getContext('2d');
        
        // Gradient fill hijau untuk bar
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.15)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Populasi (Ekor)',
                    data: {!! json_encode($chartValues) !!},
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
                    legend: {
                        display: false
                    },
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
                            precision: 0,
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { weight: '600', size: 11 }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection