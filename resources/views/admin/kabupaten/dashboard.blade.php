@extends('layouts.admin_kabupaten')

@section('title', 'Dashboard Admin Kabupaten')

@section('content')
<!-- Header -->
<div class="mb-4">
    <h3 class="fw-bold text-emerald mb-1">Dashboard Admin Kabupaten</h3>
    <p class="text-muted">Pusat Kendali Data Populasi Ternak Kabupaten (Periode: <strong>Triwulan {{ $triwulanSelected }} - Tahun {{ $tahunSelected }}</strong>)</p>
</div>

<!-- Stat Cards (Menampilkan 2 Card) -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body p-3">
                <div class="text-uppercase small fw-bold text-white-50">Total Kecamatan</div>
                <h2 class="fw-bold my-1">{{ $totalKecamatan }} <span class="fs-6 fw-normal">Kecamatan</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-dark text-white">
            <div class="card-body p-3">
                <div class="text-uppercase small fw-bold text-white-50">Jenis Ternak</div>
                <h2 class="fw-bold my-1">{{ $totalJenisTernak }} <span class="fs-6 fw-normal">Kategori</span></h2>
            </div>
        </div>
    </div>
</div>

<!-- 1. TABEL REKAPITULASI (TANPA KOLOM TOTAL DI DISAMPING) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold py-3 text-emerald d-flex justify-content-between align-items-center">
        <span><i class="fa-solid fa-table me-2"></i>Rekapitulasi Populasi per Kecamatan (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})</span>
        <a href="{{ route('admin.kabupaten.rekapitulasi') }}" class="btn btn-emerald btn-sm text-white">Lihat Detail Rekap</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Kecamatan</th>
                        @foreach($jenisTernaks as $jt)
                            <th>{{ $jt->nama_ternak }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php 
                        // Inisialisasi Array Total per Jenis Ternak ke Bawah
                        $grandTotalsByJenis = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
                    @endphp

                    @forelse($rekap as $index => $kc)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $kc->nama_kecamatan }}</td>
                            @foreach($jenisTernaks as $jt)
                                @php
                                    $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                                    $grandTotalsByJenis[$jt->id] += $jumlah;
                                @endphp
                                <td class="text-end">{{ number_format($jumlah, 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($jenisTernaks) + 2 }}" class="text-center py-4 text-muted">Belum ada data kecamatan.</td>
                        </tr>
                    @endforelse
                </tbody>
                
                <!-- FOOTER: TOTAL KE BAWAH SAJA -->
                <tfoot class="table-secondary fw-bold">
                    <tr>
                        <td colspan="2" class="text-center text-uppercase">Total Keseluruhan</td>
                        @foreach($jenisTernaks as $jt)
                            <td class="text-end text-primary fs-6">
                                {{ number_format($grandTotalsByJenis[$jt->id] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- 2. GRAFIK (DI BAWAH) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold py-3 text-emerald">
        <i class="fa-solid fa-chart-column me-2"></i>Grafik Total Populasi per Jenis Ternak (Triwulan {{ $triwulanSelected }} - {{ $tahunSelected }})
    </div>
    <div class="card-body">
        <div style="height: 320px;">
            <canvas id="grafikPopulasi"></canvas>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('grafikPopulasi').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Jumlah Populasi (Ekor)',
                    data: {!! json_encode($chartValues) !!},
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