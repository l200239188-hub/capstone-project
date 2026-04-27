{{-- resources/views/pregnancies/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Riwayat Kehamilan - ' . $patient->nama_lengkap)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">

        {{-- ============================================================ --}}
        {{-- HEADER: Info Pasien                                          --}}
        {{-- ============================================================ --}}
        <div class="card shadow mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-person-heart me-2"></i>Riwayat Kehamilan
                </h5>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-bold text-muted" style="width:40%">Nama Lengkap</td>
                                <td>: <strong>{{ $patient->nama_lengkap }}</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">NIK</td>
                                <td>: {{ $patient->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">No. Rekam Medis</td>
                                <td>: {{ $patient->no_rekam_medis ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="fw-bold text-muted" style="width:40%">Tanggal Lahir</td>
                                <td>: {{ $patient->tanggal_lahir ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d/m/Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">No. Handphone</td>
                                <td>: {{ $patient->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Alamat</td>
                                <td>: {{ $patient->alamat ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- RINGKASAN STATISTIK                                          --}}
        {{-- ============================================================ --}}
        @php
            $totalKehamilan   = $pregnancies->count();
            $kehamilanAktif   = $pregnancies->where('status', 'Aktif')->count();
            $kehamilanSelesai = $pregnancies->where('status', 'Selesai')->count();
            $totalKunjungan   = $pregnancies->sum(fn($p) => $p->ancCheckups->count());
        @endphp

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-primary">{{ $totalKehamilan }}</div>
                        <div class="small text-muted">Total Kehamilan</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-success">{{ $kehamilanAktif }}</div>
                        <div class="small text-muted">Sedang Aktif</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-secondary">{{ $kehamilanSelesai }}</div>
                        <div class="small text-muted">Selesai</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body py-3">
                        <div class="fs-3 fw-bold text-info">{{ $totalKunjungan }}</div>
                        <div class="small text-muted">Total Kunjungan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- DAFTAR KEHAMILAN                                             --}}
        {{-- ============================================================ --}}
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Daftar Kehamilan
                </h6>
                {{-- Aktifkan jika route pregnancies.create sudah dibuat --}}
                {{-- <a href="{{ route('pregnancies.create', $patient->id) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Kehamilan
                </a> --}}
            </div>
            <div class="card-body p-0">

                @if($pregnancies->isEmpty())

                    {{-- Empty State --}}
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="mb-0">Belum ada data kehamilan untuk pasien ini.</p>
                    </div>

                @else

                    @foreach($pregnancies as $pregnancy)
                    @php
                        // Badge warna status
                        $statusBadge = match($pregnancy->status) {
                            'Aktif'     => 'success',
                            'Selesai'   => 'secondary',
                            'Keguguran' => 'danger',
                            default     => 'secondary',
                        };

                        // Usia kehamilan saat ini
                        $hpht     = \Carbon\Carbon::parse($pregnancy->hpht);
                        $ukMinggu = $hpht->diffInWeeks(\Carbon\Carbon::today());
                        $ukPersen = min(100, round(($ukMinggu / 40) * 100));

                        // Data kunjungan
                        $jmlKunjungan = $pregnancy->ancCheckups->count();
                        $lastCheckup  = $pregnancy->ancCheckups->sortByDesc('tanggal_periksa')->first();
                    @endphp

                    <div class="border-bottom px-4 py-3">

                        {{-- Baris atas: judul + status badge --}}
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <h6 class="fw-bold mb-0">
                                <i class="bi bi-heart-pulse text-danger me-1"></i>
                                Kehamilan ke-{{ $pregnancy->kehamilan_ke }}
                            </h6>
                            <span class="badge bg-{{ $statusBadge }}">{{ $pregnancy->status }}</span>
                        </div>

                        {{-- Baris info --}}
                        <div class="row row-cols-2 row-cols-md-4 g-2 small text-muted mb-2">
                            <div class="col">
                                <i class="bi bi-calendar-event me-1"></i>
                                HPHT: <strong class="text-dark">{{ $hpht->format('d/m/Y') }}</strong>
                            </div>
                            <div class="col">
                                <i class="bi bi-calendar-check me-1"></i>
                                HPL: <strong class="text-dark">{{ \Carbon\Carbon::parse($pregnancy->hpl)->format('d/m/Y') }}</strong>
                            </div>
                            <div class="col">
                                <i class="bi bi-clipboard2-check me-1"></i>
                                Kunjungan: <strong class="text-dark">{{ $jmlKunjungan }}x</strong>
                            </div>
                            <div class="col">
                                <i class="bi bi-clock-history me-1"></i>
                                @if($lastCheckup)
                                    Terakhir: <strong class="text-dark">{{ \Carbon\Carbon::parse($lastCheckup->tanggal_periksa)->format('d/m/Y') }}</strong>
                                @else
                                    <span class="fst-italic">Belum ada kunjungan</span>
                                @endif
                            </div>
                        </div>

                        {{-- Progress bar UK (hanya jika Aktif) --}}
                        @if($pregnancy->status === 'Aktif')
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small text-muted mb-1">
                                <span>Usia Kehamilan Saat Ini</span>
                                <span class="fw-bold text-success">{{ $ukMinggu }} / 40 minggu</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success"
                                     role="progressbar"
                                     style="width: {{ $ukPersen }}%"
                                     aria-valuenow="{{ $ukMinggu }}"
                                     aria-valuemin="0"
                                     aria-valuemax="40">
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Tombol Aksi --}}
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('pregnancies.show', $pregnancy->id) }}"
                               class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i> Lihat Riwayat ANC
                            </a>

                            @if($pregnancy->status === 'Aktif')
                            <a href="{{ route('anc.create', $pregnancy->id) }}"
                               class="btn btn-sm btn-success">
                                <i class="bi bi-plus-circle me-1"></i> Kunjungan Baru
                            </a>
                            @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="bi bi-lock me-1"></i> Kunjungan Baru
                            </button>
                            @endif
                        </div>

                    </div>{{-- /border-bottom --}}
                    @endforeach

                @endif

            </div>{{-- /card-body --}}
        </div>{{-- /card --}}

    </div>
</div>
@endsection