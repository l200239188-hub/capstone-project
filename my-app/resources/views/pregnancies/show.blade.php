@extends('layouts.app')

@section('title', 'Detail Kehamilan - ' . $pregnancy->patient->nama_lengkap)

@push('styles')
<style>
    .card-stats {
        border: none; border-radius: 1rem;
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: white; padding: 1.5rem;
    }
    .table-mediva thead { background: #f8fafc; }
    .table-mediva th { font-size: 0.75rem; text-transform: uppercase; color: #64748b; }
    .status-badge { padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; }
    .bg-anemia-ringan  { background: #fef3c7; color: #92400e; }
    .bg-anemia-berat   { background: #fee2e2; color: #991b1b; }
    .bg-normal         { background: #dcfce7; color: #166534; }
    .bg-tidak-diperiksa{ background: #f1f5f9; color: #64748b; }
</style>
@endpush

@section('content')
<div class="container py-4">

    {{-- ── Breadcrumb + Tombol ── --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('patients.index') }}">Pasien</a>
                    </li>
                    <li class="breadcrumb-item active">Detail Kehamilan</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0">Riwayat Pemeriksaan ANC</h3>
        </div>
        <a href="{{ route('anc.create', $pregnancy->id) }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Periksa Baru (10T)
        </a>
    </div>

    <div class="row g-4">

        {{-- ── Kolom Kiri: Info Pasien ── --}}
        <div class="col-md-4">
            <div class="card card-stats shadow-sm">
                <p class="mb-1 opacity-75 small text-uppercase fw-bold">Informasi Pasien</p>
                <h4 class="mb-3">{{ $pregnancy->patient->nama_lengkap }}</h4>
                <div class="d-flex flex-column gap-2 small">
                    <span>
                        <i class="bi bi-calendar-event me-2"></i>
                        HPHT: {{ \Carbon\Carbon::parse($pregnancy->hpht)->format('d/m/Y') }}
                    </span>
                    <span>
                        <i class="bi bi-calendar-check me-2"></i>
                        HPL: {{ \Carbon\Carbon::parse($pregnancy->hpl)->format('d/m/Y') }}
                    </span>
                    <span>
                        <i class="bi bi-info-circle me-2"></i>
                        Kehamilan Ke-{{ $pregnancy->kehamilan_ke }}
                    </span>
                    <span>
                        <i class="bi bi-activity me-2"></i>
                        Status:
                        <strong>{{ $pregnancy->status }}</strong>
                    </span>
                    <span>
                        <i class="bi bi-clipboard2-check me-2"></i>
                        Total Kunjungan:
                        {{-- ✅ Perbaikan 1: nama relasi ancCheckups --}}
                        <strong>{{ $pregnancy->ancCheckups->count() }} kali</strong>
                    </span>
                </div>
            </div>
        </div>

        {{-- ── Kolom Kanan: Tabel Kunjungan ── --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Daftar Kunjungan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-mediva align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Tgl Periksa</th>
                                <th>UK</th>
                                <th>BB / TD</th>
                                <th>Hb</th>
                                <th>Skrining Anemia</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- ✅ Perbaikan 1: nama relasi ancCheckups --}}
                            @forelse($pregnancy->ancCheckups as $checkup)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($checkup->tanggal_periksa)->format('d M Y') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $checkup->usia_kehamilan_minggu }} mgg
                                    </span>
                                </td>
                                <td>
                                    {{ $checkup->berat_badan }} kg
                                    <br>
                                    <small class="text-muted">{{ $checkup->tekanan_darah }} mmHg</small>
                                </td>
                                <td>
                                    <strong>{{ $checkup->hasil_lab_hb ?? '-' }}</strong>
                                    @if($checkup->hasil_lab_hb)
                                        <small class="text-muted d-block">g/dL</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        {{-- ✅ Perbaikan 2: null → 'Tidak Diperiksa' --}}
                                        if ($checkup->hasil_lab_hb === null) {
                                            $status = 'Tidak Diperiksa';
                                            $class  = 'bg-tidak-diperiksa';
                                        } elseif ($checkup->hasil_lab_hb < 8) {
                                            $status = 'Anemia Berat';
                                            $class  = 'bg-anemia-berat';
                                        } elseif ($checkup->hasil_lab_hb < 11) {
                                            $status = 'Anemia Ringan';
                                            $class  = 'bg-anemia-ringan';
                                        } else {
                                            $status = 'Normal';
                                            $class  = 'bg-normal';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $class }}">{{ $status }}</span>
                                </td>
                                <td>
                                    {{-- ✅ Perbaikan 3: tombol trigger modal --}}
                                    <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailAnc{{ $checkup->id }}">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Belum ada riwayat kunjungan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- ✅ Perbaikan 3: Modal Detail per Kunjungan                  --}}
{{-- ════════════════════════════════════════════════════════════ --}}
@foreach($pregnancy->ancCheckups as $checkup)
<div class="modal fade" id="detailAnc{{ $checkup->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">

            <div class="modal-header" style="background:linear-gradient(135deg,#0d9488,#0f766e);">
                <h6 class="modal-title text-white fw-bold">
                    <i class="bi bi-clipboard2-pulse me-2"></i>
                    Detail Kunjungan —
                    {{ \Carbon\Carbon::parse($checkup->tanggal_periksa)->format('d M Y') }}
                    <span class="badge bg-white text-dark ms-2" style="font-size:.75rem;">
                        UK {{ $checkup->usia_kehamilan_minggu }} minggu
                    </span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Pemeriksaan Fisik --}}
                    <div class="col-12">
                        <p class="text-uppercase fw-bold small text-muted mb-2">
                            <i class="bi bi-clipboard2-pulse me-1"></i> Pemeriksaan Fisik
                        </p>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">Berat Badan</div>
                            <div class="fw-bold fs-5">{{ $checkup->berat_badan }}</div>
                            <div class="small text-muted">kg</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">Tekanan Darah</div>
                            <div class="fw-bold fs-5">{{ $checkup->tekanan_darah }}</div>
                            <div class="small text-muted">mmHg</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">LILA</div>
                            <div class="fw-bold fs-5">{{ $checkup->lila }}</div>
                            <div class="small text-muted">cm</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">Tinggi Fundus</div>
                            <div class="fw-bold fs-5">{{ $checkup->tinggi_fundus }}</div>
                            <div class="small text-muted">cm</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">DJJ</div>
                            <div class="fw-bold fs-5">{{ $checkup->djj }}</div>
                            <div class="small text-muted">bpm</div>
                        </div>
                    </div>

                    {{-- Lab & Tindakan --}}
                    <div class="col-12 mt-2">
                        <p class="text-uppercase fw-bold small text-muted mb-2">
                            <i class="bi bi-droplet-half me-1"></i> Lab &amp; Tindakan
                        </p>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">Imunisasi TT</div>
                            <div class="fw-bold">{{ $checkup->status_imunisasi_tt }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">TTD Diberikan</div>
                            <div class="fw-bold fs-5">{{ $checkup->pemberian_ttd }}</div>
                            <div class="small text-muted">tablet</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="p-3 rounded-3 bg-light text-center">
                            <div class="small text-muted">Hemoglobin</div>
                            <div class="fw-bold fs-5">{{ $checkup->hasil_lab_hb ?? '—' }}</div>
                            @if($checkup->hasil_lab_hb)
                                <div class="small text-muted">g/dL</div>
                            @endif
                        </div>
                    </div>

                    {{-- Kesimpulan --}}
                    <div class="col-12 mt-2">
                        <p class="text-uppercase fw-bold small text-muted mb-2">
                            <i class="bi bi-journal-medical me-1"></i> Kesimpulan
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-semibold">Tatalaksana</label>
                        <p class="mb-0 p-3 bg-light rounded-3" style="font-size:.88rem;white-space:pre-line;">
                            {{ $checkup->tatalaksana }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-semibold">Konseling</label>
                        <p class="mb-0 p-3 bg-light rounded-3" style="font-size:.88rem;white-space:pre-line;">
                            {{ $checkup->konseling }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@endforeach

@endsection