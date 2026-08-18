@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <h2 class="fw-bold text-dark">Dashboard Admin Kecamatan</h2>
        <p class="text-muted">Pusat Kendali Data Populasi Ternak Wilayah: <span class="fw-bold text-dark">{{ $wilayah ?? 'Kecamatan' }}</span></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-white" style="background-color: #113d2f;">
                <div class="card-body p-3">
                    <div class="text-uppercase small fw-bold text-white-50">Total Jenis Ternak</div>
                    <h2 class="fw-bold my-1">{{ count($rekapitulasi) }} <span class="fs-6 fw-normal">Kategori</span></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-white" style="background-color: #198754;">
                <div class="card-body p-3">
                    <div class="text-uppercase small fw-bold text-white-50">Informasi Periode</div>
                    <h2 class="fw-bold my-1">{{ $namaTriwulanText }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3 text-success d-flex justify-content-between align-items-center">
            <span><i class="fa-solid fa-table me-2"></i>Rekapitulasi Populasi Ternak ({{ $namaTriwulanText }})</span>
            <a href="{{ url('/admin-kecamatan/rekapitulasi') }}" class="btn btn-success btn-sm text-white fw-semibold">
                Lihat Detail Rekap <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Jenis Ternak</th>
                            <th class="text-end">Jumlah (Ekor)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapitulasi as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $item['nama_ternak'] }}</td>
                                <td class="text-end fw-bold text-success">{{ number_format($item['jumlah'], 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data rekapitulasi untuk periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold py-3 text-success">
            <i class="fa-solid fa-chart-column me-2"></i>Grafik Populasi Ternak ({{ $namaTriwulanText }})
        </div>
        <div class="card-body">
            <div style="height: 320px;">
                <canvas id="populasiChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('populasiChart').getContext('2d');
        
        const chartLabels = {!! json_encode(array_column($rekapitulasi, 'nama_ternak')) !!};
        const chartData = {!! json_encode(array_column($rekapitulasi, 'jumlah')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Populasi (Ekor)',
                    data: chartData,
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection