@extends('layouts.app')

@section('title', 'Data Populasi Ternak Kabupaten Kediri')

@section('content')
<style>
    /* Hero Section (Gradasi Emerald Cerah Match Theme) */
    .hero-section {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        padding: 3.5rem 1rem;
        border-radius: 0 0 24px 24px;
        margin-bottom: 2rem;
    }

    /* Filter Inputs */
    .form-select-custom, .form-control-custom {
        border: 1px solid #10b981 !important;
        border-radius: 8px;
    }
    .form-select-custom:focus, .form-control-custom:focus {
        border-color: #047857 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.2) !important;
    }

    /* Header Tabel: Gradasi Emerald Cerah & Font Judul Lebih Besar */
    .table-public thead th {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%) !important;
        color: #ffffff !important;
        border: none !important;
        font-size: 0.9rem !important; /* Font Judul Lebih Besar dari Isi */
        font-weight: 700 !important;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px !important;
        vertical-align: middle;
    }

    /* Isi Tabel (Font Lebih Kecil) */
    .table-public tbody td {
        font-size: 0.85rem !important; /* Font Isi Lebih Kecil */
        padding: 12px 16px !important;
        vertical-align: middle;
    }

    .table-public tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .table-public tbody tr:hover {
        background-color: #ecfdf5 !important;
    }

    /* Custom Badge Periode */
    .badge-periode-soft {
        background-color: #dcfce7;
        color: #15803d;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-block;
    }

    /* Custom Button Filter Emerald */
    .btn-filter-custom {
        background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        color: #ffffff;
        border: none;
        transition: all 0.3s ease-in-out;
    }

    .btn-filter-custom:hover {
        background: linear-gradient(135deg, #34d399 0%, #059669 100%);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
    }
</style>

<!-- Hero Section Header -->
<div class="hero-section text-center shadow-sm">
    <div class="container py-2">
        <h1 class="fw-bold display-6 mb-2" style="letter-spacing: -0.5px;">
            <i class="fa-solid fa-cow me-2 text-warning"></i>Data Populasi Ternak Kabupaten Kediri
        </h1>
        <p class="lead fs-6 text-white-50 mb-0">
            Informasi rekapitulasi jumlah populasi ternak resmi triwulanan per kecamatan.
        </p>
    </div>
</div>

<div class="container my-4">

    <!-- Card Form Filter Data -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('public.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-location-dot me-1"></i>Kecamatan
                    </label>
                    <select name="kecamatan_id" class="form-select form-select-custom text-dark fw-semibold fs-7">
                        @foreach($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ $selectedKecamatanId == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar me-1"></i>Tahun
                    </label>
                    <input type="number" name="tahun" class="form-control form-control-custom text-dark fw-semibold fs-7" value="{{ $selectedTahun ?? '2026' }}" placeholder="Masukkan Tahun">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold small mb-1" style="color: #047857;">
                        <i class="fa-solid fa-calendar-quarter me-1"></i>Triwulan
                    </label>
                    <select name="triwulan" class="form-select form-select-custom text-dark fw-semibold fs-7">
                        <option value="1" {{ (isset($selectedTriwulan) && $selectedTriwulan == '1') ? 'selected' : '' }}>Triwulan I (Jan - Mar)</option>
                        <option value="2" {{ (isset($selectedTriwulan) && $selectedTriwulan == '2') ? 'selected' : '' }}>Triwulan II (Apr - Jun)</option>
                        <option value="3" {{ (isset($selectedTriwulan) && $selectedTriwulan == '3') ? 'selected' : '' }}>Triwulan III (Jul - Sep)</option>
                        <option value="4" {{ (isset($selectedTriwulan) && $selectedTriwulan == '4') ? 'selected' : '' }}>Triwulan IV (Okt - Des)</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-filter-custom w-100 fw-bold rounded-3 shadow-sm py-2">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Tabel Data Rekapitulasi -->
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 px-4 border-0 d-flex align-items-center">
            <h6 class="card-title mb-0 fw-bold text-dark">
                <i class="fa-solid fa-table me-2" style="color: #047857;"></i>Tabel Rekapitulasi Populasi Ternak
            </h6>
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
                            <th class="text-end px-4" style="width: 200px;">JUMLAH POPULASI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($populasi as $index => $item)
                            <tr>
                                <td class="text-center fw-semibold text-secondary">
                                    {{ method_exists($populasi, 'firstItem') ? $populasi->firstItem() + $index : $index + 1 }}
                                </td>
                                <td class="fw-bold text-dark px-3">
                                    {{ $item->kecamatan->nama_kecamatan ?? '-' }}
                                </td>
                                <td class="px-3 text-secondary fw-semibold">
                                    {{ $item->jenisTernak->nama_ternak ?? '-' }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-periode-soft">
                                        • Triwulan {{ $item->triwulan }} - {{ $item->tahun }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold fs-6 px-4" style="color: #047857;">
                                    {{ number_format($item->jumlah) }} <span class="fs-7 text-muted fw-normal">ekor</span>
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
    <div class="card shadow-sm border-0 rounded-4 bg-white">
        <div class="card-header bg-transparent py-3 px-4 border-0">
            <h6 class="card-title mb-0 fw-bold text-dark">
                <i class="fa-solid fa-chart-column me-2" style="color: #047857;"></i>Grafik Visualisasi Populasi Ternak
            </h6>
        </div>
        <div class="card-body p-4">
            @if(count($chartData) > 0)
                <div style="position: relative; height: 350px; width: 100%;">
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
        const labels = @json($chartLabels);
        const dataValues = @json($chartData);

        if (labels.length > 0) {
            const ctx = document.getElementById('populasiChart').getContext('2d');
            
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(16, 185, 129, 0.85)');
            gradient.addColorStop(1, 'rgba(16, 185, 129, 0.2)');

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