@extends('layouts.app')

@section('title', 'Data Populasi Ternak Kabupaten Kediri')

@section('content')
<div class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold display-5">Data Populasi Ternak Kabupaten Kediri</h1>
        <p class="lead">Informasi rekapitulasi jumlah populasi ternak triwulanan per kecamatan.</p>
    </div>
</div>

<div class="container my-5">
    <!-- Form Filter Data -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('public.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-success">Kecamatan</label>
                    <select name="kecamatan_id" class="form-select border-success">
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $selectedKecamatanId == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-success">Tahun</label>
                    <input type="number" name="tahun" class="form-control border-success" value="{{ $selectedTahun ?? '2026' }}" placeholder="Masukkan Tahun">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-success">Triwulan</label>
                    <select name="triwulan" class="form-select border-success">
                        <option value="1" {{ (isset($selectedTriwulan) && $selectedTriwulan == '1') ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                        <option value="2" {{ (isset($selectedTriwulan) && $selectedTriwulan == '2') ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                        <option value="3" {{ (isset($selectedTriwulan) && $selectedTriwulan == '3') ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                        <option value="4" {{ (isset($selectedTriwulan) && $selectedTriwulan == '4') ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-emerald w-100 fw-bold">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-success text-white py-3">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fa-solid fa-table me-2"></i>Tabel Rekapitulasi Populasi Ternak
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>Kecamatan</th>
                            <th>Jenis Ternak</th>
                            <th class="text-center">Periode</th>
                            <th class="text-center fw-bold">Jumlah Populasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($populasi as $index => $item)
                            <tr>
                                <td class="text-center">{{ $populasi->firstItem() + $index }}</td>
                                <td class="fw-bold text-success">{{ $item->kecamatan->nama_kecamatan }}</td>
                                <td>{{ $item->jenisTernak->nama_ternak }}</td>
                                <td class="text-center">Triwulan {{ $item->triwulan }} - {{ $item->tahun }}</td>
                                <td class="text-center fw-bold text-success fs-6">{{ number_format($item->jumlah) }} ekor</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-inbox fa-2x mb-2 d-block"></i>
                                    Belum ada data populasi resmi untuk kecamatan ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($populasi->hasPages())
            <div class="card-footer bg-white d-flex justify-content-end py-3">
                {{ $populasi->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white py-3">
            <h5 class="card-title mb-0 fw-bold">
                <i class="fa-solid fa-chart-bar me-2"></i>Grafik Visualisasi Populasi Ternak
            </h5>
        </div>
        <div class="card-body">
            @if(count($chartData) > 0)
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="populasiChart"></canvas>
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fa-solid fa-chart-line fa-2x mb-2 d-block"></i>
                    Grafik tidak dapat ditampilkan karena belum ada data.
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
        const labels = @json($chartLabels);
        const dataValues = @json($chartData);

        if (labels.length > 0) {
            const ctx = document.getElementById('populasiChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Populasi (Ekor)',
                        data: dataValues,
                        backgroundColor: 'rgba(46, 125, 50, 0.7)',
                        borderColor: 'rgba(27, 77, 62, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true, title: { display: true, text: 'Jumlah (Ekor)' } }
                    }
                }
            });
        }
    });
</script>
@endpush