@extends('layouts.admin_kabupaten')

@section('title', 'Rekapitulasi Data - Simnak')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold text-emerald mb-1">Rekapitulasi Data Populasi Ternak</h3>
</div>

<!-- Card Filter & Tombol Action -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-end gap-3">
        <form method="GET" action="{{ route('admin.kabupaten.rekapitulasi') }}" class="d-flex align-items-center gap-3">
            <div>
                <label class="form-label fw-bold mb-1">Pilih Triwulan</label>
                <select name="triwulan" class="form-select" onchange="this.form.submit()">
                    <option value="">-- Semua Triwulan --</option>
                    @for($t = 1; $t <= 4; $t++)
                        <option value="{{ $t }}" {{ $triwulanSelected == $t ? 'selected' : '' }}>
                            Triwulan {{ $t }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label fw-bold mb-1">Pilih Tahun Rekap</label>
                <select name="tahun" class="form-select" onchange="this.form.submit()">
                    @for($t = date('Y'); $t >= 2018; $t--)
                        <option value="{{ $t }}" {{ $tahunSelected == $t ? 'selected' : '' }}>
                            Tahun {{ $t }}
                        </option>
                    @endfor
                </select>
            </div>
            @if($triwulanSelected)
                <div>
                    <label class="form-label d-block mb-1">&nbsp;</label>
                    <a href="{{ route('admin.kabupaten.rekapitulasi', ['tahun' => $tahunSelected]) }}" class="btn btn-outline-secondary">
                        Reset Filter
                    </a>
                </div>
            @endif
        </form>

        <!-- Tombol Cetak PDF & Excel dengan Parameter Filter -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.kabupaten.rekapitulasi.pdf', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
               class="btn btn-danger fw-bold" target="_blank">
                <i class="fa-solid fa-file-pdf me-1"></i> Cetak PDF
            </a>
            <a href="{{ route('admin.kabupaten.rekapitulasi.excel', ['tahun' => $tahunSelected, 'triwulan' => $triwulanSelected]) }}" 
               class="btn btn-success fw-bold">
                <i class="fa-solid fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>
</div>

<!-- Tabel Rekapitulasi -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Kecamatan</th>
                        @foreach($jenisTernaks as $jt)
                            <th>{{ $jt->nama_ternak }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $grandTotals = array_fill_keys($jenisTernaks->pluck('id')->toArray(), 0);
                    @endphp

                    @forelse($rekap as $index => $kc)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ strtoupper($kc->nama_kecamatan) }}</td>
                            @foreach($jenisTernaks as $jt)
                                @php
                                    $jumlah = $kc->populasiKecamatan->where('jenis_ternak_id', $jt->id)->sum('jumlah');
                                    $grandTotals[$jt->id] += $jumlah;
                                @endphp
                                <td class="text-end">{{ number_format($jumlah, 0, ',', '.') }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($jenisTernaks) + 2 }}" class="text-center py-4 text-muted">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-success fw-bold">
                    <tr>
                        <td colspan="2" class="text-center text-uppercase">TOTAL KABUPATEN KEDIRI</td>
                        @foreach($jenisTernaks as $jt)
                            <td class="text-end text-emerald fs-6">
                                {{ number_format($grandTotals[$jt->id] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection