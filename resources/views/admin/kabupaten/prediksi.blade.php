@extends('layouts.admin_kabupaten')

@section('title', 'Prediksi Regresi Linier - Simnak')

@section('content')
<style>
    /* Gradient Stat Card */
    .stat-card-gradient {
        background: linear-gradient(135deg, #ffffff 0%, #ecfdf5 100%);
        border: 1px solid #dcfce7 !important;
    }

    /* Code Box Formula Regresi */
    .formula-box {
        background-color: #ffffff;
        border-left: 4px solid #10b981;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* Badge Indicators */
    .badge-stat {
        background-color: #e2e8f0;
        color: #334155;
        font-weight: 700;
        font-size: 0.8rem;
        padding: 4px 12px;
        border-radius: 6px;
    }

    /* Custom Form Filter Inputs (Uniform Green Border & Focus) */
    .form-select-custom {
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }
    
    .form-select-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }
</style>

<div class="container-fluid py-3">

    <!-- Header Judul -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: #047857; letter-spacing: -0.5px;">
            <i class="fa-solid fa-chart-line text-warning me-2"></i>Prediksi Populasi Ternak (Regresi Linier)
        </h3>
        <p class="text-muted small mb-0">Estimasi dan proyeksi populasi ternak periode mendatang menggunakan metode regresi linier</p>
    </div>

    <!-- Filter Form Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form method="GET" action="{{ url('/admin-kabupaten/prediksi') }}" class="row g-3 align-items-center">
                
                <!-- Pilih Jenis Ternak -->
                <div class="col-md-6">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-paw me-1"></i>Pilih Jenis Ternak
                    </label>
                    <select name="jenis_ternak_id" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
                        @foreach($jenisTernaks as $jt)
                            <option value="{{ $jt->id }}" {{ $jenisTernakId == $jt->id ? 'selected' : '' }}>
                                {{ $jt->nama_ternak }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Spesifik Kecamatan (Disamakan Style & Warnanya) -->
                <div class="col-md-6">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-location-dot me-1"></i>Filter Spesifik Kecamatan (Opsional)
                    </label>
                    <select name="kecamatan_id" class="form-select form-select-custom text-dark fw-semibold fs-7" onchange="this.form.submit()">
                        <option value="">-- Semua Kecamatan (Skala Kabupaten) --</option>
                        @foreach($kecamatans as $kc)
                            <option value="{{ $kc->id }}" {{ $kecamatanId == $kc->id ? 'selected' : '' }}>
                                {{ strtoupper($kc->nama_kecamatan) }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </form>
        </div>
    </div>

    <!-- Main Section -->
    <div class="row g-4">
        <!-- Left: Hasil Prediksi -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card-gradient">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-calculator text-success me-2"></i>Hasil Prediksi Regresi Linier
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center text-center p-4">
                    @if($n >= 2 && !is_null($prediksi))
                        <span class="text-muted fw-semibold small text-uppercase tracking-wider">Estimasi Total Populasi Periode Berikutnya</span>
                        <h1 class="display-4 fw-bold my-3" style="color: #047857; letter-spacing: -1px;">
                            {{ number_format($prediksi, 0, ',', '.') }} <span class="fs-5 text-dark fw-normal">ekor</span>
                        </h1>
                        
                        <!-- Box Formula -->
                        <div class="p-3 formula-box text-start mt-2">
                            <span class="fw-bold text-danger small d-block mb-1">
                                <i class="fa-solid fa-square-root-variable me-1"></i>Persamaan Regresi Linier:
                            </span>
                            <div class="bg-light p-2 rounded-3 border mb-3 text-center">
                                <code class="fs-6 fw-bold text-dark">Y = {{ round($a, 2) }} {{ $b >= 0 ? '+ ' . round($b, 2) : '- ' . abs(round($b, 2)) }}X</code>
                            </div>

                            <div class="row g-2 pt-1 border-top">
                                <div class="col-12 d-flex justify-content-between align-items-center small py-1">
                                    <span class="text-muted">Jumlah Periode Valid (n):</span>
                                    <span class="badge-stat">{{ $n }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between align-items-center small py-1">
                                    <span class="text-muted">Konstanta (a):</span>
                                    <span class="fw-semibold text-dark">{{ round($a, 2) }}</span>
                                </div>
                                <div class="col-12 d-flex justify-content-between align-items-center small py-1">
                                    <span class="text-muted">Koefisien Regresi (b):</span>
                                    <span class="fw-semibold {{ $b >= 0 ? 'text-success' : 'text-danger' }}">{{ round($b, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="py-5 text-muted">
                            <div class="fs-1 text-warning mb-2"><i class="fa-solid fa-triangle-exclamation"></i></div>
                            <p class="mb-0 px-3 small fw-semibold">Minimal dibutuhkan <strong>2 data historis</strong> untuk menghitung proyeksi regresi linier.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right: Grafik Tren & Prediksi -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-chart-area text-success me-2"></i>Grafik Tren Histori & Proyeksi
                    </h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    @if($n >= 2)
                        <div style="width: 100%; min-height: 290px;">
                            <canvas id="prediksiChart"></canvas>
                        </div>

                        <!-- Keterangan Jenis Garis Grafik -->
                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 mt-3 pt-3 border-top">
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 22px; height: 3px; background-color: #10b981; display: inline-block; border-radius: 2px;"></span>
                                <span class="small text-secondary">
                                    <strong class="text-dark">Garis Hijau Lurus:</strong> Data Histori (Aktual)
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="width: 22px; height: 0; border-top: 3px dashed #ef4444; display: inline-block;"></span>
                                <span class="small text-secondary">
                                    <strong class="text-dark">Garis Merah Putus-putus:</strong> Proyeksi Prediksi (N+1)
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="text-muted text-center py-5 my-auto">
                            <i class="fa-solid fa-chart-line fa-2x mb-2 text-secondary opacity-50"></i>
                            <p class="mb-0 small fw-semibold">Grafik belum dapat ditampilkan karena data histori belum mencukupi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js & Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($n >= 2)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('prediksiChart').getContext('2d');
        
        const labelsHistori = {!! json_encode($labels) !!};
        const dataHistori = {!! json_encode($values) !!};
        const nilaiPrediksi = {{ $prediksi }};
        
        const labelsFull = [...labelsHistori, 'Prediksi N+1'];
        
        const datasetHistori = [...dataHistori, null];
        
        const datasetPrediksi = new Array(dataHistori.length - 1).fill(null);
        datasetPrediksi.push(dataHistori[dataHistori.length - 1]);
        datasetPrediksi.push(nilaiPrediksi);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsFull,
                datasets: [
                    {
                        label: 'Data Histori Populasi (Aktual)',
                        data: datasetHistori,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Proyeksi Prediksi (Estimasi N+1)',
                        data: datasetPrediksi,
                        borderColor: '#ef4444',
                        borderDash: [6, 6],
                        backgroundColor: 'transparent',
                        pointRadius: 7,
                        pointBackgroundColor: '#ef4444',
                        pointHoverRadius: 9
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('id-ID').format(context.raw) + ' ekor';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
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
    });
</script>
@endif
@endsection