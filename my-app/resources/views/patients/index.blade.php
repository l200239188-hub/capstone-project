{{-- resources/views/patients/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Daftar Pasien')

@push('styles')
<style>
    /* ── Root Variables ── */
    :root {
        --mediva-teal:    #0d9488;
        --mediva-teal-lt: #ccfbf1;
        --mediva-teal-dk: #0f766e;
        --mediva-navy:    #0f172a;
        --mediva-slate:   #475569;
        --mediva-muted:   #94a3b8;
        --mediva-border:  #e2e8f0;
        --mediva-bg:      #f8fafc;
        --mediva-card:    #ffffff;
        --mediva-danger:  #dc2626;
        --mediva-warn:    #d97706;
        --mediva-success: #059669;
        --radius-card:    .875rem;
        --shadow-card:    0 1px 3px rgba(0,0,0,.08), 0 4px 16px rgba(0,0,0,.06);
    }

    body { background: var(--mediva-bg); }

    /* ── Fade-in animation ── */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp .4s ease both; }
    .fade-up:nth-child(1) { animation-delay: .05s; }
    .fade-up:nth-child(2) { animation-delay: .12s; }
    .fade-up:nth-child(3) { animation-delay: .19s; }
    .fade-up:nth-child(4) { animation-delay: .26s; }

    /* ── Page Header ── */
    .page-header {
        background: linear-gradient(135deg, var(--mediva-teal) 0%, var(--mediva-teal-dk) 100%);
        border-radius: var(--radius-card);
        padding: 1.75rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(13,148,136,.28);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .page-header::after {
        content: '';
        position: absolute; bottom: -60px; right: 80px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.04);
        border-radius: 50%;
    }
    .page-title { font-size: 1.4rem; font-weight: 800; margin: 0; letter-spacing: -.02em; }
    .page-sub   { font-size: .84rem; opacity: .82; margin-top: .25rem; margin-bottom: 0; }

    .btn-tambah {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,.18); color: #fff;
        border: 1.5px solid rgba(255,255,255,.35);
        border-radius: .5rem; padding: .55rem 1.25rem;
        font-size: .88rem; font-weight: 700; text-decoration: none;
        backdrop-filter: blur(4px);
        transition: background .18s, transform .1s;
        white-space: nowrap;
    }
    .btn-tambah:hover {
        background: rgba(255,255,255,.28); color: #fff;
        transform: translateY(-1px);
    }

    /* ── Stat Strip ── */
    .stat-strip {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .stat-box {
        background: var(--mediva-card);
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        padding: 1.1rem 1.25rem;
        box-shadow: var(--shadow-card);
        display: flex; align-items: center; gap: .9rem;
    }
    .stat-icon {
        width: 2.5rem; height: 2.5rem; border-radius: .6rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .stat-label { font-size: .72rem; color: var(--mediva-muted); text-transform: uppercase; letter-spacing: .05em; font-weight: 600; }
    .stat-value { font-size: 1.4rem; font-weight: 800; color: var(--mediva-navy); line-height: 1.1; }

    /* ── Search & Filter Bar ── */
    .search-bar {
        background: var(--mediva-card);
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        padding: 1rem 1.25rem;
        box-shadow: var(--shadow-card);
        margin-bottom: 1.25rem;
    }
    .search-bar .form-control {
        border: 1.5px solid var(--mediva-border);
        border-radius: .5rem 0 0 .5rem;
        font-size: .9rem; padding: .55rem .9rem;
    }
    .search-bar .form-control:focus {
        border-color: var(--mediva-teal);
        box-shadow: 0 0 0 3px rgba(13,148,136,.12);
    }
    .btn-cari {
        background: var(--mediva-teal); color: #fff;
        border: none; border-radius: 0 .5rem .5rem 0;
        padding: .55rem 1.1rem; font-size: .88rem; font-weight: 700;
        transition: background .18s;
    }
    .btn-cari:hover { background: var(--mediva-teal-dk); color: #fff; }
    .btn-reset {
        background: transparent; color: var(--mediva-slate);
        border: 1.5px solid var(--mediva-border);
        border-radius: .5rem; padding: .52rem .9rem;
        font-size: .85rem; font-weight: 600;
        transition: all .15s; text-decoration: none;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .btn-reset:hover { background: #f1f5f9; color: var(--mediva-navy); }

    /* ── Table Card ── */
    .table-card {
        background: var(--mediva-card);
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }
    .table-card-header {
        padding: .9rem 1.25rem;
        border-bottom: 1px solid var(--mediva-border);
        background: #f8fafc;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title {
        font-size: .85rem; font-weight: 700;
        color: var(--mediva-navy); margin: 0;
    }
    .result-count {
        font-size: .78rem; color: var(--mediva-muted);
        background: #f1f5f9; border-radius: 2rem;
        padding: .15rem .65rem; font-weight: 600;
    }

    /* ── Table Styles ── */
    .table-mediva { margin: 0; }
    .table-mediva thead tr {
        background: #f8fafc;
        border-bottom: 2px solid var(--mediva-border);
    }
    .table-mediva th {
        font-size: .72rem; text-transform: uppercase;
        letter-spacing: .05em; color: var(--mediva-muted);
        font-weight: 700; padding: .75rem 1rem;
        white-space: nowrap; border: none;
    }
    .table-mediva td {
        font-size: .87rem; color: var(--mediva-slate);
        padding: .85rem 1rem; border-color: var(--mediva-border);
        vertical-align: middle;
    }
    .table-mediva tbody tr { transition: background .12s; }
    .table-mediva tbody tr:hover { background: #f8fafc; }
    .table-mediva tbody tr:last-child td { border-bottom: none; }

    /* Nama pasien styling */
    .patient-name-cell {
        font-weight: 700; color: var(--mediva-navy);
        font-size: .9rem;
    }
    .patient-nik {
        font-size: .75rem; color: var(--mediva-muted);
        margin-top: .1rem;
    }

    /* Avatar inisial */
    .patient-avatar {
        width: 2.1rem; height: 2.1rem; border-radius: 50%;
        background: var(--mediva-teal-lt); color: var(--mediva-teal);
        font-size: .78rem; font-weight: 800;
        display: inline-flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* Golongan darah badge */
    .gol-darah {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.9rem; height: 1.9rem; border-radius: 50%;
        background: #ede9fe; color: #7c3aed;
        font-size: .72rem; font-weight: 800;
    }

    /* ── Action Buttons ── */
    .btn-edit {
        display: inline-flex; align-items: center; gap: .3rem;
        background: #fef9c3; color: #854d0e;
        border: 1.5px solid #fde047;
        border-radius: .4rem; padding: .3rem .7rem;
        font-size: .78rem; font-weight: 700; text-decoration: none;
        transition: all .15s;
    }
    .btn-edit:hover { background: #fde047; color: #713f12; }

    .btn-hapus {
        display: inline-flex; align-items: center; gap: .3rem;
        background: #fee2e2; color: var(--mediva-danger);
        border: 1.5px solid #fca5a5;
        border-radius: .4rem; padding: .3rem .7rem;
        font-size: .78rem; font-weight: 700;
        transition: all .15s; cursor: pointer;
    }
    .btn-hapus:hover { background: #fca5a5; color: #7f1d1d; }

    .btn-kehamilan {
        display: inline-flex; align-items: center; gap: .3rem;
        background: var(--mediva-teal-lt); color: var(--mediva-teal);
        border: 1.5px solid #99f6e4;
        border-radius: .4rem; padding: .3rem .7rem;
        font-size: .78rem; font-weight: 700; text-decoration: none;
        transition: all .15s;
    }
    .btn-kehamilan:hover {
        background: var(--mediva-teal); color: #fff;
        border-color: var(--mediva-teal);
    }

    /* ── Empty State ── */
    .empty-state {
        padding: 4rem 2rem; text-align: center;
    }
    .empty-icon {
        width: 4.5rem; height: 4.5rem; border-radius: 50%;
        background: var(--mediva-teal-lt);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: var(--mediva-teal);
        margin: 0 auto 1.25rem;
    }

    /* ── Pagination ── */
    .pagination .page-link {
        border-radius: .4rem !important;
        margin: 0 .1rem;
        font-size: .82rem; font-weight: 600;
        color: var(--mediva-slate);
        border-color: var(--mediva-border);
        transition: all .15s;
    }
    .pagination .page-link:hover {
        background: var(--mediva-teal-lt);
        border-color: var(--mediva-teal);
        color: var(--mediva-teal);
    }
    .pagination .page-item.active .page-link {
        background: var(--mediva-teal);
        border-color: var(--mediva-teal);
        color: #fff;
    }

    /* ── Search highlight ── */
    mark {
        background: #fef08a; color: var(--mediva-navy);
        border-radius: .2rem; padding: 0 .15rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1100px;">

    {{-- ── Page Header ── --}}
    <div class="page-header fade-up">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="page-title">
                    <i class="bi bi-people-fill me-2"></i>Daftar Pasien
                </p>
                <p class="page-sub">Kelola data pasien Klinik Mediva</p>
            </div>
            <a href="{{ route('patients.create') }}" class="btn-tambah">
                <i class="bi bi-plus-lg"></i> Pasien Baru
            </a>
        </div>
    </div>

    {{-- ── Alert Sukses ── --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 rounded-3 border-0 shadow-sm mb-4 fade-up" role="alert">
        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ── Stat Strip ── --}}
    @php
        $totalPasien  = $patients->total();
        $hasilCari    = request('search') ? $patients->count() : null;
    @endphp
    <div class="stat-strip fade-up">
        <div class="stat-box">
            <div class="stat-icon" style="background:var(--mediva-teal-lt);color:var(--mediva-teal);">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-label">Total Pasien</div>
                <div class="stat-value">{{ $patients->total() }}</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;">
                <i class="bi bi-person-check-fill"></i>
            </div>
            <div>
                <div class="stat-label">Halaman Ini</div>
                <div class="stat-value">{{ $patients->count() }}</div>
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-icon" style="background:#dcfce7;color:var(--mediva-success);">
                <i class="bi bi-journals"></i>
            </div>
            <div>
                <div class="stat-label">Halaman</div>
                <div class="stat-value">{{ $patients->currentPage() }} / {{ $patients->lastPage() }}</div>
            </div>
        </div>
        @if(request('search'))
        <div class="stat-box">
            <div class="stat-icon" style="background:#fef9c3;color:#854d0e;">
                <i class="bi bi-search"></i>
            </div>
            <div>
                <div class="stat-label">Hasil Pencarian</div>
                <div class="stat-value">{{ $patients->total() }}</div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Search Bar ── --}}
    <div class="search-bar fade-up">
        <form action="{{ route('patients.index') }}" method="GET">
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <div class="input-group flex-grow-1" style="max-width:480px;">
                    <span class="input-group-text bg-white border-end-0"
                          style="border:1.5px solid var(--mediva-border);border-right:none;border-radius:.5rem 0 0 .5rem;">
                        <i class="bi bi-search text-muted" style="font-size:.85rem;"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           style="border-left:none;"
                           placeholder="Cari nama pasien atau NIK..."
                           value="{{ request('search') }}">
                    <button class="btn-cari" type="submit">Cari</button>
                </div>
                @if(request('search'))
                <a href="{{ route('patients.index') }}" class="btn-reset">
                    <i class="bi bi-x-lg"></i> Reset
                </a>
                <span style="font-size:.8rem;color:var(--mediva-muted);">
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                </span>
                @endif
            </div>
        </form>
    </div>

    {{-- ── Table Card ── --}}
    <div class="table-card fade-up">
        <div class="table-card-header">
            <p class="table-card-title">
                <i class="bi bi-table me-2" style="color:var(--mediva-teal);"></i>
                Data Pasien
            </p>
            <span class="result-count">{{ $patients->total() }} pasien</span>
        </div>

        <div class="table-responsive">
            <table class="table table-mediva">
                <thead>
                    <tr>
                        <th style="width:3rem;">#</th>
                        <th>Pasien</th>
                        <th>No. HP</th>
                        <th class="text-center">Tgl Lahir</th>
                        <th class="text-center">Gol. Darah</th>
                        <th class="text-center">Tinggi (cm)</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($patients->isEmpty())
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-person-x"></i>
                                </div>
                                <h6 class="fw-bold" style="color:var(--mediva-navy);">
                                    @if(request('search'))
                                        Pasien "{{ request('search') }}" Tidak Ditemukan
                                    @else
                                        Belum Ada Data Pasien
                                    @endif
                                </h6>
                                <p class="text-muted mb-3" style="font-size:.88rem;">
                                    @if(request('search'))
                                        Coba gunakan kata kunci lain atau reset pencarian.
                                    @else
                                        Mulai dengan menambahkan data pasien pertama.
                                    @endif
                                </p>
                                @if(request('search'))
                                    <a href="{{ route('patients.index') }}" class="btn-reset">
                                        <i class="bi bi-arrow-left"></i> Kembali ke Semua Pasien
                                    </a>
                                @else
                                    <a href="{{ route('patients.create') }}" class="btn-tambah"
                                       style="background:var(--mediva-teal);border-color:var(--mediva-teal);">
                                        <i class="bi bi-plus-lg"></i> Tambah Pasien Pertama
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @else
                        @foreach($patients as $index => $patient)
                        @php
                            // Inisial untuk avatar
                            $words    = explode(' ', $patient->nama_lengkap);
                            $inisial  = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

                            // Highlight teks pencarian
                            $search   = request('search');
                            $nama     = $patient->nama_lengkap;
                            $nik      = $patient->nik ?? '-';
                            if ($search) {
                                $nama = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $nama);
                                $nik  = preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $nik);
                            }
                        @endphp
                        <tr>
                            <td class="text-center text-muted" style="font-size:.78rem;">
                                {{ $patients->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="patient-avatar">{{ $inisial }}</div>
                                    <div>
                                        <div class="patient-name-cell">{!! $nama !!}</div>
                                        <div class="patient-nik">NIK: {!! $nik !!}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $patient->no_hp ?? '-' }}</td>
                            <td class="text-center">
                                {{ $patient->tanggal_lahir
                                    ? \Carbon\Carbon::parse($patient->tanggal_lahir)->format('d/m/Y')
                                    : '-' }}
                            </td>
                            <td class="text-center">
                                @if($patient->golongan_darah)
                                    <span class="gol-darah">{{ $patient->golongan_darah }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $patient->tinggi_badan ?? '-' }}</td>
                            <td class="text-center text-nowrap">
                                <div class="d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                    {{-- Riwayat Kehamilan --}}
                                    <a href="{{ route('pregnancies.index', $patient->id) }}"
                                       class="btn-kehamilan"
                                       title="Riwayat Kehamilan">
                                        <i class="bi bi-heart-pulse"></i> ANC
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                       class="btn-edit"
                                       title="Edit Pasien">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    {{-- Hapus --}}
                                    <form action="{{ route('patients.destroy', $patient->id) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data {{ $patient->nama_lengkap }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-hapus" title="Hapus Pasien">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if($patients->hasPages())
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2"
             style="border-color:var(--mediva-border)!important;">
            <small class="text-muted">
                Menampilkan <strong>{{ $patients->firstItem() }}–{{ $patients->lastItem() }}</strong>
                dari <strong>{{ $patients->total() }}</strong> pasien
            </small>
            {{ $patients->appends(request()->query())->links() }}
        </div>
        @endif

    </div>{{-- /table-card --}}

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Auto-focus search input jika ada query
    @if(request('search'))
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
        }
    @endif
});
</script>
@endpush