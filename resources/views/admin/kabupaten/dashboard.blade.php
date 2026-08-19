@extends('layouts.admin_kabupaten')

@section('title', 'Dashboard Admin Kabupaten - Simnak')

@section('content')
<style>
    /* Custom Styling Stat Card */
    .card-stat-emerald {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
    }
    .card-stat-dark {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
    }

    /* Styling Box Ikon Transparan */
    .icon-box-stat {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2) !important;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #ffffff !important;
        flex-shrink: 0;
    }

    /* Styling Table Rekapitulasi Dashboard (Font Judul Lebih Besar) */
    .table-rekap-dashboard thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        font-size: 0.9rem !important; /* Font Judul Lebih Besar dari Isi */
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
        font-size: 0.85rem !important; /* Font Isi Lebih Kecil */
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

    <!-- Header Judul & Periode Active -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-gauge-high text-warning me-2"></i>Dashboard Admin Kabupaten
            </h3>
            <p class="text-muted small mb-0">Pusat kendali & pemantauan data populasi ternak Kabupaten Kediri</p>
        </div>
        <div>
            <span class="badge rounded-pill bg-white text-dark shadow-sm px-3 py-2 border fw-semibold fs-7">
                <i class="fa-solid fa-calendar-check text-success me-2"></i>Periode: <strong>Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }}</strong>
            </span>
        </div>
    </div>

    <!-- Stat Cards Section (2 Cards Utama) -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 card-stat-emerald p-2">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Wilayah Kecamatan</div>
                        <h2 class="fw-bold my-0 display-6" style="letter-spacing: -1px;">
                            {{ $totalKecamatan }} <span class="fs-6 fw-normal text-white-50">Kecamatan</span>
                        </h2>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 card-stat-dark p-2">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Jenis Hewan Ternak</div>
                        <h2 class="fw-bold my-0 display-6" style="letter-spacing: -1px;">
                            {{ $totalJenisTernak }} <span class="fs-6 fw-normal text-white-50">Kategori</span>
                        </h2>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. TABEL REKAPITULASI POPULASI PER KECAMATAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-table-list text-success me-2"></i>Rekapitulasi Populasi per Kecamatan (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})
            </h6>
            <a href="{{ route('admin.kabupaten.rekapitulasi') }}" class="btn btn-detail-emerald btn-sm rounded-3 px-3 py-2">
                <span>Lihat Detail Rekap</span>
                <i class="fa-solid fa-arrow-right ms-1 fs-8"></i>
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
                        @php 
                            $grandTotalsByJenis = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
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
                                        $grandTotalsByJenis[$jt->id] += $jumlah;
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
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-chart-column text-success me-2"></i>Grafik Total Populasi per Jenis Ternak (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})
            </h6>
        </div>
        <div class="card-body p-4">
            <div style="height: 320px; width: 100%;">
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
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.25)');

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