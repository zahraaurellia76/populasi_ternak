@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <!-- Form Pilih Jenis Ternak -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kecamatan.prediksi') }}" method="GET" id="prediksiForm">
                <label class="form-label fw-bold text-success mb-2">Pilih Jenis Ternak untuk Diprediksi</label>
                <select name="jenis_ternak_id" class="form-select border-success" onchange="document.getElementById('prediksiForm').submit()">
                    @foreach($jenisTernaks as $jt)
                        <option value="{{ $jt->id }}" {{ $selectedJenisId == $jt->id ? 'selected' : '' }}>
                            {{ $jt->nama_ternak }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Card Kiri: Hasil Prediksi Regresi Linier -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body">
                    <h5 class="fw-bold text-dark mb-4">Hasil Prediksi Regresi Linier</h5>

                    @if($n < 2)
                        <div class="text-center py-4">
                            <div class="text-warning display-4 mb-3">
                                <i class="fa-solid fa-circle-exclamation"></i>
                            </div>
                            <p class="text-muted mb-0">
                                Minimal dibutuhkan <strong>2 data terverifikasi</strong> untuk menghitung regresi linier.
                            </p>
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <small class="text-muted d-block fw-semibold mb-2">Estimasi Total Populasi Periode Berikutnya</small>
                            <div class="d-flex align-items-baseline justify-content-center">
                                <span class="display-3 fw-bold text-emerald" style="color: #198754;">
                                    {{ number_format($prediksiNext, 0, ',', '.') }}
                                </span>
                                <span class="fs-4 fw-bold text-dark ms-2">ekor</span>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mt-4">
                            <h6 class="fw-bold text-danger mb-3">Persamaan Regresi Linier:</h6>
                            <h5 class="fw-bold text-dark mb-3">
                                Y = {{ round($a) }} {{ $b >= 0 ? '+ ' . round(abs($b)) : '- ' . round(abs($b)) }}X
                            </h5>
                            <hr class="text-muted">
                            <div class="small text-muted space-y-1">
                                <div><strong>n (Jumlah Periode Valid):</strong> {{ $n }}</div>
                                <div><strong>Konstanta (a):</strong> {{ round($a) }}</div>
                                <div><strong>Koefisien Regresi (b):</strong> {{ round($b) }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card Kanan: Grafik Tren Histori & Prediksi -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold text-dark mb-4">Grafik Tren Histori & Prediksi</h5>
                    
                    @if($n < 2)
                        <div class="my-auto text-center text-muted">
                            Belum ada histori data terverifikasi.
                        </div>
                    @else
                        <div class="flex-grow-1 w-100 position-relative" style="min-height: 280px;">
                            <canvas id="chartPrediksi"></canvas>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($n >= 2)
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('chartPrediksi').getContext('2d');

        const chartLabels = {!! json_encode($chartLabels) !!};
        const dataHistori = {!! json_encode($dataHistori) !!};
        const dataPrediksi = {!! json_encode($dataPrediksi) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [
                    {
                        label: 'Data Histori Populasi',
                        data: dataHistori,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.08)',
                        fill: true,
                        tension: 0,
                        pointBackgroundColor: '#198754',
                        pointBorderColor: '#198754',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    },
                    {
                        label: 'Proyeksi Prediksi',
                        data: dataPrediksi,
                        borderColor: '#dc3545',
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#dc3545',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: {
                            color: '#e9ecef'
                        }
                    },
                    x: {
                        grid: {
                            color: '#e9ecef'
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection