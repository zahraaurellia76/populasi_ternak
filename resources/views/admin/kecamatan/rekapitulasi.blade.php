@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold text-success mb-4">Rekapitulasi Data Populasi Ternak</h3>

    <!-- Card Filter & Tombol Export -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kecamatan.rekapitulasi') }}" method="GET" id="filterForm">
                <div class="row align-items-end g-3">
                    <!-- Dropdown Pilih Triwulan -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark mb-1">Pilih Triwulan</label>
                        <select name="triwulan" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="" {{ request('triwulan') == '' ? 'selected' : '' }}>-- Semua Triwulan --</option>
                            <option value="1" {{ request('triwulan') == '1' ? 'selected' : '' }}>Triwulan I</option>
                            <option value="2" {{ request('triwulan') == '2' ? 'selected' : '' }}>Triwulan II</option>
                            <option value="3" {{ request('triwulan') == '3' ? 'selected' : '' }}>Triwulan III</option>
                            <option value="4" {{ request('triwulan') == '4' ? 'selected' : '' }}>Triwulan IV</option>
                        </select>
                    </div>

                    <!-- Dropdown Pilih Tahun Rekap -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-dark mb-1">Pilih Tahun Rekap</label>
                        <select name="tahun" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Tombol Export PDF & Excel di Sisi Kanan -->
                    <div class="col-md-6 d-flex justify-content-end gap-2 ms-auto">
                        <a href="{{ route('admin.kecamatan.rekapitulasi.pdf', ['tahun' => $tahun, 'triwulan' => request('triwulan')]) }}" 
                        target="_blank" 
                        class="btn btn-danger fw-semibold px-3">
                            <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
                        </a>
                        <a href="{{ route('admin.kecamatan.rekapitulasi.excel', ['tahun' => $tahun, 'triwulan' => request('triwulan')]) }}" 
                        class="btn btn-success fw-semibold px-3">
                            <i class="fa-solid fa-file-excel me-1"></i> Export Excel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Rekapitulasi -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th>Jenis Ternak</th>
                            <th class="text-end">Jumlah (Ekor)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisTernaks as $jt)
                            @php
                                $query = $populasi->where('jenis_ternak_id', $jt->id);
                                if (request('triwulan')) {
                                    $query = $query->where('triwulan', request('triwulan'));
                                }
                                $jumlah = $query->sum('jumlah');
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $jt->nama_ternak }}</td>
                                <td class="text-end fw-bold text-success">{{ number_format($jumlah, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Data rekapitulasi belum tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection