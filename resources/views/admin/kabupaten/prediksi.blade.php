@extends('layouts.admin_kabupaten')
@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-success">Prediksi Populasi Ternak Kabupaten Kediri</h3>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ url('/admin-kabupaten/prediksi') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-success">Pilih Jenis Ternak</label>
                    <select name="jenis_ternak_id" class="form-select" onchange="this.form.submit()">
                        @foreach($jenisTernaks as $jt)
                            <option value="{{ $jt->id }}" {{ $jenisTernakId == $jt->id ? 'selected' : '' }}>
                                {{ $jt->nama_ternak }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted">Filter Spesifik Kecamatan (Opsional)</label>
                    <select name="kecamatan_id" class="form-select" onchange="this.form.submit()">
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
        <!-- Card Left: Hasil Prediksi -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold text-muted mb-0"><i class="bi bi-calculator me-2"></i>Hasil Prediksi Regresi Linier</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    @if($n >= 2 && !is_null($prediksi))
                        <small class="text-muted fw-semibold">Estimasi Total Populasi Periode Berikutnya</small>
                        <h1 class="display-3 fw-bold text-success my-2">{{ number_format($prediksi, 0, ',', '.') }} <span class="fs-5 text-dark">ekor</span></h1>
                        
                        <div class="p-3 bg-light rounded text-start mt-3">
                            <span class="fw-bold text-danger d-block mb-1">Persamaan Regresi Linier:</span>
                            <code class="fs-6 fw-bold text-dark">Y = {{ round($a, 2) }} {{ $b >= 0 ? '+ ' . round($b, 2) : '- ' . abs(round($b, 2)) }}X</code>
                            <hr class="my-2">
                            <ul class="list-unstyled mb-0 small text-secondary">
                                <li><strong>n (Jumlah Periode Valid):</strong> {{ $n }}</li>
                                <li><strong>Konstanta (a):</strong> {{ round($a, 2) }}</li>
                                <li><strong>Koefisien Regresi (b):</strong> {{ round($b, 2) }}</li>
                            </ul>
                        </div>
                    @else
                        <div class="py-5 text-muted">
                            <div class="fs-1 text-warning mb-2"><i class="bi bi-exclamation-circle-fill"></i></div>
                            <p class="mb-0 px-3">Minimal dibutuhkan <strong>2 data</strong> untuk menghitung prediksi regresi.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Right: Grafik Tren & Prediksi -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-3">
                    <h6 class="fw-bold text-muted mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Grafik Tren Histori & Prediksi</h6>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    @if($n >= 2)
                        <div style="width: 100%; min-height: 280px;">
                            <canvas id="prediksiChart"></canvas>
                        </div>
                    @else
                        <div class="text-muted text-center py-5">
                            Grafik belum dapat ditampilkan karena data tidak mencukupi.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($n >= 2)
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('prediksiChart').getContext('2d');
        
        const labelsHistori = {!! json_encode($labels) !!};
        const dataHistori = {!! json_encode($values) !!};
        const nilaiPrediksi = {{ $prediksi }};
        
        // Buat label & data gabungan untuk titik prediksi
        const labelsFull = [...labelsHistori, 'Prediksi N+1'];
        
        // Dataset Histori (berhenti di data terakhir)
        const datasetHistori = [...dataHistori, null];
        
        // Dataset Prediksi (menghubungkan dari titik terakhir histori ke titik prediksi)
        const datasetPrediksi = new Array(dataHistori.length - 1).fill(null);
        datasetPrediksi.push(dataHistori[dataHistori.length - 1]); // Titik awal sambungan
        datasetPrediksi.push(nilaiPrediksi);                      // Titik hasil prediksi

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labelsFull,
                datasets: [
                    {
                        label: 'Data Histori Populasi',
                        data: datasetHistori,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true,
                        tension: 0.2,
                        pointRadius: 5,
                        pointBackgroundColor: '#198754'
                    },
                    {
                        label: 'Proyeksi Prediksi',
                        data: datasetPrediksi,
                        borderColor: '#dc3545',
                        borderDash: [6, 6], // Garis putus-putus
                        backgroundColor: 'transparent',
                        pointRadius: 6,
                        pointBackgroundColor: '#dc3545'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
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
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection