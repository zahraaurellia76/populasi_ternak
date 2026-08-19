@extends('layouts.admin')

@section('content')
<style>
    /* Stat Cards Emerald & Dark Theme */
    .card-stat-emerald {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
    }
    .card-stat-dark {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
    }

    /* Box Ikon Transparan */
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

    /* Header Tabel: Gradasi Emerald & Font Judul Lebih Besar dari Isi */
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

    /* Soft Button Detail Emerald */
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

<div class="container-fluid py-3 px-4">

    <!-- Header Judul & Wilayah Active -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
                <i class="fa-solid fa-gauge-high text-warning me-2"></i>Dashboard Admin Kecamatan
            </h3>
            <p class="text-muted small mb-0">Pusat kendali data populasi ternak wilayah: <strong class="text-dark">{{ $wilayah ?? 'Kecamatan' }}</strong></p>
        </div>
        <div>
            <span class="badge rounded-pill bg-white text-dark shadow-sm px-3 py-2 border fw-semibold fs-7">
                <i class="fa-solid fa-calendar-check text-success me-2"></i>Periode: <strong>{{ $namaTriwulanText }}</strong>
            </span>
        </div>
    </div>

    <!-- Stat Cards Section -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 card-stat-emerald p-2">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Total Jenis Ternak</div>
                        <h2 class="fw-bold my-0 display-6" style="letter-spacing: -1px;">
                            {{ count($rekapitulasi) }} <span class="fs-6 fw-normal text-white-50">Kategori</span>
                        </h2>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 card-stat-dark p-2">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small fw-bold text-white-50 tracking-wider mb-1">Informasi Periode</div>
                        <h2 class="fw-bold my-0 fs-3" style="letter-spacing: -0.5px;">
                            {{ $namaTriwulanText }}
                        </h2>
                    </div>
                    <div class="icon-box-stat">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Populasi -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-table-list text-success me-2"></i>Rekapitulasi Populasi Ternak ({{ $namaTriwulanText }})
            </h6>
            <a href="{{ url('/admin-kecamatan/rekapitulasi') }}" class="btn btn-detail-emerald btn-sm rounded-3 px-3 py-2">
                <span>Lihat Detail Rekap</span>
                <i class="fa-solid fa-arrow-right ms-1 fs-8"></i>
            </a>
        </div>
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
                        @forelse($rekapitulasi as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    {{ $item['nama_ternak'] }}
                                </td>
                                <td class="text-end fw-bold fs-6 px-4" style="color: #047857;">
                                    {{ number_format($item['jumlah'], 0, ',', '.') }} <span class="fs-7 text-muted fw-normal">ekor</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted bg-white">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 opacity-25" style="color: #047857;"></i>
                                    <p class="mb-0 small fw-semibold">Belum ada data rekapitulasi untuk periode ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik Visualisasi Populasi Ternak -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-chart-column text-success me-2"></i>Grafik Populasi Ternak ({{ $namaTriwulanText }})
            </h6>
        </div>
        <div class="card-body p-4">
            <div style="height: 320px; width: 100%;">
                <canvas id="populasiChart"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('populasiChart').getContext('2d');
        
        const chartLabels = {!! json_encode(array_column($rekapitulasi, 'nama_ternak')) !!};
        const chartData = {!! json_encode(array_column($rekapitulasi, 'jumlah')) !!};

        // Gradient fill hijau emerald untuk bar
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.25)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Populasi (Ekor)',
                    data: chartData,
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