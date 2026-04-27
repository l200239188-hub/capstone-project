{{-- resources/views/anc/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Input Kunjungan ANC - ' . $pregnancy->patient->nama_lengkap)

@push('styles')
<style>
    /* ── Palette & Variables ── */
    :root {
        --mediva-teal:    #0d9488;
        --mediva-teal-lt: #ccfbf1;
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

    /* ── Page Header ── */
    .anc-header {
        background: linear-gradient(135deg, var(--mediva-teal) 0%, #0f766e 100%);
        border-radius: var(--radius-card);
        padding: 1.5rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(13,148,136,.3);
    }
    .anc-header .patient-name { font-size: 1.35rem; font-weight: 700; margin: 0; }
    .anc-header .patient-meta { font-size: .85rem; opacity: .85; margin-top: .25rem; }
    .anc-header .uk-badge {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3);
        border-radius: 2rem; padding: .25rem .85rem; font-size: .8rem;
        backdrop-filter: blur(4px);
    }

    /* ── Section Cards ── */
    .section-card {
        background: var(--mediva-card);
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        box-shadow: var(--shadow-card);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: box-shadow .2s;
    }
    .section-card:focus-within { box-shadow: 0 0 0 3px rgba(13,148,136,.15), var(--shadow-card); }

    .section-header {
        display: flex; align-items: center; gap: .75rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--mediva-border);
        background: #f8fafc;
    }
    .section-icon {
        width: 2.25rem; height: 2.25rem; border-radius: .5rem;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .icon-teal  { background: var(--mediva-teal-lt); color: var(--mediva-teal); }
    .icon-blue  { background: #dbeafe; color: #1d4ed8; }
    .icon-green { background: #dcfce7; color: #15803d; }

    .section-title { font-size: .95rem; font-weight: 700; color: var(--mediva-navy); margin: 0; }
    .section-subtitle { font-size: .76rem; color: var(--mediva-muted); margin: 0; }
    .section-body { padding: 1.5rem; }

    /* ── Form Controls ── */
    .form-label {
        font-size: .8rem; font-weight: 600;
        color: var(--mediva-slate); text-transform: uppercase;
        letter-spacing: .04em; margin-bottom: .35rem;
    }
    .form-label .req { color: var(--mediva-danger); margin-left: .2rem; }

    .form-control, .form-select {
        border: 1.5px solid var(--mediva-border);
        border-radius: .5rem; font-size: .9rem;
        padding: .55rem .85rem;
        transition: border-color .18s, box-shadow .18s;
        background: #fff;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--mediva-teal);
        box-shadow: 0 0 0 3px rgba(13,148,136,.12);
        outline: none;
    }
    .form-control.is-invalid, .form-select.is-invalid {
        border-color: var(--mediva-danger);
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220,38,38,.12);
    }
    .invalid-feedback { font-size: .78rem; color: var(--mediva-danger); }

    .input-unit {
        background: #f1f5f9; border: 1.5px solid var(--mediva-border);
        border-left: none; border-radius: 0 .5rem .5rem 0;
        padding: .55rem .8rem; font-size: .8rem;
        color: var(--mediva-muted); white-space: nowrap;
    }
    .input-group .form-control { border-radius: .5rem 0 0 .5rem; }

    /* ── Textarea ── */
    .form-control[rows] { resize: vertical; min-height: 7rem; }

    /* ── Helper text ── */
    .form-hint { font-size: .76rem; color: var(--mediva-muted); margin-top: .25rem; }

    /* ── Usia Kehamilan Preview ── */
    #uk-preview {
        display: inline-flex; align-items: center; gap: .4rem;
        background: var(--mediva-teal-lt); color: var(--mediva-teal);
        border-radius: .35rem; padding: .2rem .65rem;
        font-size: .82rem; font-weight: 700; margin-top: .4rem;
        transition: all .2s;
    }
    #uk-preview.hidden { display: none; }

    /* ── Status TT pills ── */
    .tt-group { display: flex; flex-wrap: wrap; gap: .5rem; }
    .tt-group input[type="radio"] { display: none; }
    .tt-group label {
        cursor: pointer; padding: .4rem 1rem; border-radius: 2rem;
        border: 1.5px solid var(--mediva-border); font-size: .82rem;
        font-weight: 600; color: var(--mediva-slate);
        transition: all .15s; user-select: none;
    }
    .tt-group input[type="radio"]:checked + label {
        background: var(--mediva-teal); border-color: var(--mediva-teal); color: #fff;
    }

    /* ── TTD Slider ── */
    .ttd-range { accent-color: var(--mediva-teal); }
    #ttd-value {
        display: inline-block; min-width: 2.5rem; text-align: center;
        background: var(--mediva-teal); color: #fff;
        border-radius: .35rem; padding: .15rem .5rem;
        font-size: .85rem; font-weight: 700;
    }

    /* ── Hb status indicator ── */
    #hb-status {
        font-size: .78rem; font-weight: 700; margin-top: .3rem;
        display: none;
    }
    #hb-status.normal  { color: var(--mediva-success); }
    #hb-status.anemia  { color: var(--mediva-warn); }
    #hb-status.severe  { color: var(--mediva-danger); }

    /* ── Submit bar ── */
    .submit-bar {
        background: var(--mediva-card);
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        padding: 1.25rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 1rem;
        box-shadow: var(--shadow-card);
    }
    .btn-simpan {
        background: var(--mediva-teal); color: #fff;
        border: none; border-radius: .5rem;
        padding: .65rem 2rem; font-size: .95rem; font-weight: 700;
        letter-spacing: .02em;
        transition: background .18s, transform .1s, box-shadow .18s;
        display: inline-flex; align-items: center; gap: .5rem;
    }
    .btn-simpan:hover {
        background: #0f766e; color: #fff;
        box-shadow: 0 4px 12px rgba(13,148,136,.35);
        transform: translateY(-1px);
    }
    .btn-simpan:active { transform: translateY(0); }
    .btn-batal {
        color: var(--mediva-slate); background: transparent;
        border: 1.5px solid var(--mediva-border);
        border-radius: .5rem; padding: .65rem 1.5rem;
        font-size: .9rem; font-weight: 600;
        transition: all .15s;
        display: inline-flex; align-items: center; gap: .5rem;
    }
    .btn-batal:hover { background: #f1f5f9; color: var(--mediva-navy); }

    /* ── Riwayat mini table ── */
    .riwayat-card {
        border: 1px solid var(--mediva-border);
        border-radius: var(--radius-card);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .riwayat-table th {
        font-size: .73rem; text-transform: uppercase; letter-spacing: .05em;
        color: var(--mediva-muted); background: #f8fafc;
        border-bottom: 1px solid var(--mediva-border);
        padding: .6rem 1rem; font-weight: 600;
    }
    .riwayat-table td { font-size: .83rem; padding: .6rem 1rem; color: var(--mediva-slate); }
    .riwayat-table tbody tr:hover { background: #f8fafc; }
    .badge-uk {
        background: var(--mediva-teal-lt); color: var(--mediva-teal);
        font-size: .73rem; font-weight: 700; border-radius: .35rem;
        padding: .15rem .5rem;
    }

    /* ── Progress steps ── */
    .progress-steps { display: flex; gap: 0; margin-bottom: 1.75rem; }
    .step {
        flex: 1; display: flex; flex-direction: column; align-items: center;
        position: relative;
    }
    .step:not(:last-child)::after {
        content: ''; position: absolute;
        top: 1rem; left: 50%; width: 100%; height: 2px;
        background: var(--mediva-border); z-index: 0;
    }
    .step-dot {
        width: 2rem; height: 2rem; border-radius: 50%;
        border: 2px solid var(--mediva-border);
        background: #fff; z-index: 1;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700; color: var(--mediva-muted);
        transition: all .2s;
    }
    .step.active .step-dot {
        background: var(--mediva-teal); border-color: var(--mediva-teal); color: #fff;
        box-shadow: 0 0 0 4px rgba(13,148,136,.2);
    }
    .step-label { font-size: .7rem; color: var(--mediva-muted); margin-top: .4rem; text-align: center; }
    .step.active .step-label { color: var(--mediva-teal); font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width:920px;">

    {{-- ── Alert Errors ── --}}
    @if ($errors->any())
    <div class="alert alert-danger d-flex align-items-start gap-2 mb-4 rounded-3 border-0 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0 mt-1"></i>
        <div>
            <strong>Terdapat kesalahan pada form:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach ($errors->all() as $error)
                    <li style="font-size:.88rem;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="anc-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <p class="patient-name">
                    <i class="bi bi-person-heart me-1"></i>
                    {{ $pregnancy->patient->nama_lengkap }}
                </p>
                <p class="patient-meta">
                    No. Rekam Medis: <strong>{{ $pregnancy->patient->no_rekam_medis ?? '-' }}</strong>
                    &nbsp;·&nbsp;
                    Kehamilan ke-<strong>{{ $pregnancy->kehamilan_ke }}</strong>
                    &nbsp;·&nbsp;
                    HPHT: <strong>{{ \Carbon\Carbon::parse($pregnancy->hpht)->translatedFormat('d M Y') }}</strong>
                    &nbsp;·&nbsp;
                    HPL: <strong>{{ \Carbon\Carbon::parse($pregnancy->hpl)->translatedFormat('d M Y') }}</strong>
                </p>
            </div>
            <span class="uk-badge">
                <i class="bi bi-calendar-heart"></i>
                UK Saat Ini ≈ <strong>{{ $usiaKehamilanSaatIni }} Minggu</strong>
            </span>
        </div>
    </div>

    {{-- ── Progress Indicator ── --}}
    <div class="progress-steps">
        <div class="step active">
            <div class="step-dot">1</div>
            <span class="step-label">Pemeriksaan<br>Fisik</span>
        </div>
        <div class="step active">
            <div class="step-dot">2</div>
            <span class="step-label">Lab &amp;<br>Tindakan</span>
        </div>
        <div class="step active">
            <div class="step-dot">3</div>
            <span class="step-label">Kesimpulan</span>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- FORM UTAMA                                            --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <form method="POST"
          action="{{ route('anc.store', $pregnancy->id) }}"
          id="ancForm"
          novalidate>
        @csrf

        {{-- ┌───────────────────────────────────────────────────┐ --}}
        {{-- │  BAGIAN 1 · PEMERIKSAAN FISIK                     │ --}}
        {{-- └───────────────────────────────────────────────────┘ --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon icon-teal">
                    <i class="bi bi-clipboard2-pulse-fill"></i>
                </div>
                <div>
                    <p class="section-title">Bagian 1 — Pemeriksaan Fisik</p>
                    <p class="section-subtitle">Tanda vital, antropometri, dan pemeriksaan obstetri</p>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3">

                    {{-- Tanggal Periksa --}}
                    <div class="col-md-6">
                        <label for="tanggal_periksa" class="form-label">
                            Tanggal Periksa <span class="req">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_periksa"
                               name="tanggal_periksa"
                               class="form-control @error('tanggal_periksa') is-invalid @enderror"
                               value="{{ old('tanggal_periksa', date('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal_periksa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- Preview Usia Kehamilan dihitung dari HPHT --}}
                        <div id="uk-preview" class="hidden">
                            <i class="bi bi-clock-history"></i>
                            UK: <span id="uk-value">—</span> minggu
                        </div>
                    </div>

                    {{-- Spacer --}}
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="p-3 rounded-3 w-100" style="background:#f8fafc;border:1px dashed var(--mediva-border);">
                            <small class="text-muted d-block" style="font-size:.76rem;">
                                <i class="bi bi-info-circle text-primary me-1"></i>
                                Usia kehamilan akan dihitung <strong>otomatis</strong> berdasarkan
                                HPHT (<strong>{{ \Carbon\Carbon::parse($pregnancy->hpht)->format('d/m/Y') }}</strong>).
                            </small>
                        </div>
                    </div>

                    {{-- Berat Badan --}}
                    <div class="col-md-4">
                        <label for="berat_badan" class="form-label">
                            Berat Badan <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   id="berat_badan"
                                   name="berat_badan"
                                   class="form-control @error('berat_badan') is-invalid @enderror"
                                   value="{{ old('berat_badan') }}"
                                   placeholder="65.5"
                                   step="0.1" min="20" max="200"
                                   required>
                            <span class="input-unit">kg</span>
                        </div>
                        @error('berat_badan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tekanan Darah --}}
                    <div class="col-md-4">
                        <label for="tekanan_darah" class="form-label">
                            Tekanan Darah <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text"
                                   id="tekanan_darah"
                                   name="tekanan_darah"
                                   class="form-control @error('tekanan_darah') is-invalid @enderror"
                                   value="{{ old('tekanan_darah') }}"
                                   placeholder="120/80"
                                   pattern="\d{2,3}\/\d{2,3}"
                                   maxlength="7"
                                   required>
                            <span class="input-unit">mmHg</span>
                        </div>
                        <p class="form-hint">Format: sistol/diastol (contoh: 120/80)</p>
                        @error('tekanan_darah')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- LILA --}}
                    <div class="col-md-4">
                        <label for="lila" class="form-label">
                            LILA <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   id="lila"
                                   name="lila"
                                   class="form-control @error('lila') is-invalid @enderror"
                                   value="{{ old('lila') }}"
                                   placeholder="23.5"
                                   step="0.1" min="10" max="50"
                                   required>
                            <span class="input-unit">cm</span>
                        </div>
                        <p class="form-hint">Lingkar Lengan Atas (normal ≥23.5 cm)</p>
                        @error('lila')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tinggi Fundus Uteri --}}
                    <div class="col-md-6">
                        <label for="tinggi_fundus" class="form-label">
                            Tinggi Fundus Uteri <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   id="tinggi_fundus"
                                   name="tinggi_fundus"
                                   class="form-control @error('tinggi_fundus') is-invalid @enderror"
                                   value="{{ old('tinggi_fundus') }}"
                                   placeholder="28"
                                   min="0" max="50"
                                   required>
                            <span class="input-unit">cm</span>
                        </div>
                        @error('tinggi_fundus')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DJJ --}}
                    <div class="col-md-6">
                        <label for="djj" class="form-label">
                            Denyut Jantung Janin (DJJ) <span class="req">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number"
                                   id="djj"
                                   name="djj"
                                   class="form-control @error('djj') is-invalid @enderror"
                                   value="{{ old('djj') }}"
                                   placeholder="140"
                                   min="60" max="200"
                                   required>
                            <span class="input-unit">bpm</span>
                        </div>
                        <p class="form-hint">Normal: 110–160 bpm</p>
                        @error('djj')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /section-body --}}
        </div>{{-- /section-card --}}


        {{-- ┌───────────────────────────────────────────────────┐ --}}
        {{-- │  BAGIAN 2 · LABORATORIUM & TINDAKAN               │ --}}
        {{-- └───────────────────────────────────────────────────┘ --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon icon-blue">
                    <i class="bi bi-droplet-half"></i>
                </div>
                <div>
                    <p class="section-title">Bagian 2 — Laboratorium &amp; Tindakan</p>
                    <p class="section-subtitle">Imunisasi, suplementasi, dan hasil pemeriksaan laboratorium</p>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-4">

                    {{-- Status Imunisasi TT --}}
                    <div class="col-12">
                        <label class="form-label d-block">
                            Status Imunisasi TT <span class="req">*</span>
                        </label>
                        <div class="tt-group">
                            @php
                                $ttOptions = ['Belum', 'TT1', 'TT2', 'TT3', 'TT4', 'TT5', 'TT Lupa'];
                                $oldTT     = old('status_imunisasi_tt', '');
                            @endphp
                            @foreach($ttOptions as $tt)
                            <div>
                                <input type="radio"
                                       id="tt_{{ $loop->index }}"
                                       name="status_imunisasi_tt"
                                       value="{{ $tt }}"
                                       {{ $oldTT === $tt ? 'checked' : '' }}>
                                <label for="tt_{{ $loop->index }}">{{ $tt }}</label>
                            </div>
                            @endforeach
                        </div>
                        @error('status_imunisasi_tt')
                            <p class="form-hint" style="color:var(--mediva-danger);margin-top:.4rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Pemberian TTD --}}
                    <div class="col-md-8">
                        <label for="pemberian_ttd" class="form-label">
                            Pemberian Tablet Tambah Darah (TTD) <span class="req">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="range"
                                   id="pemberian_ttd"
                                   name="pemberian_ttd"
                                   class="form-range ttd-range flex-grow-1"
                                   value="{{ old('pemberian_ttd', 30) }}"
                                   min="0" max="90" step="1">
                            <span id="ttd-value">{{ old('pemberian_ttd', 30) }}</span>
                            <span style="font-size:.82rem;color:var(--mediva-muted);">tablet</span>
                        </div>
                        <p class="form-hint">Jumlah tablet yang diberikan (0 – 90 tablet)</p>
                        @error('pemberian_ttd')
                            <p class="form-hint" style="color:var(--mediva-danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hasil Lab Hb --}}
                    <div class="col-md-4">
                        <label for="hasil_lab_hb" class="form-label">Hasil Lab Hemoglobin (Hb)</label>
                        <div class="input-group">
                            <input type="number"
                                   id="hasil_lab_hb"
                                   name="hasil_lab_hb"
                                   class="form-control @error('hasil_lab_hb') is-invalid @enderror"
                                   value="{{ old('hasil_lab_hb') }}"
                                   placeholder="11.5"
                                   step="0.1" min="1" max="25">
                            <span class="input-unit">g/dL</span>
                        </div>
                        <div id="hb-status"></div>
                        <p class="form-hint">Opsional. Normal ibu hamil ≥11 g/dL</p>
                        @error('hasil_lab_hb')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /section-body --}}
        </div>{{-- /section-card --}}


        {{-- ┌───────────────────────────────────────────────────┐ --}}
        {{-- │  BAGIAN 3 · KESIMPULAN                            │ --}}
        {{-- └───────────────────────────────────────────────────┘ --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon icon-green">
                    <i class="bi bi-journal-medical"></i>
                </div>
                <div>
                    <p class="section-title">Bagian 3 — Kesimpulan</p>
                    <p class="section-subtitle">Tatalaksana klinis dan materi konseling yang diberikan</p>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3">

                    {{-- Tatalaksana --}}
                    <div class="col-12">
                        <label for="tatalaksana" class="form-label">
                            Tatalaksana <span class="req">*</span>
                        </label>
                        <textarea id="tatalaksana"
                                  name="tatalaksana"
                                  rows="4"
                                  class="form-control @error('tatalaksana') is-invalid @enderror"
                                  placeholder="Contoh: Anjurkan istirahat cukup, konsumsi makanan bergizi seimbang, minum TTD setiap malam…"
                                  required>{{ old('tatalaksana') }}</textarea>
                        @error('tatalaksana')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="form-hint">
                            <i class="bi bi-magic text-teal"></i> 
                            Sistem akan otomatis menambahkan rekomendasi skrining anemia berdasarkan kadar Hb.
                        </p>
                    </div>

                    {{-- Konseling --}}
                    <div class="col-12">
                        <label for="konseling" class="form-label">
                            Konseling <span class="req">*</span>
                        </label>
                        <textarea id="konseling"
                                  name="konseling"
                                  rows="4"
                                  class="form-control @error('konseling') is-invalid @enderror"
                                  placeholder="Contoh: Konseling tanda bahaya kehamilan, persiapan persalinan, KB pasca salin…"
                                  required>{{ old('konseling') }}</textarea>
                        @error('konseling')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /section-body --}}
        </div>{{-- /section-card --}}


        {{-- ── Submit Bar ── --}}
        <div class="submit-bar">
            <a href="{{ route('pregnancies.show', $pregnancy->id) }}"
               class="btn-batal text-decoration-none">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
            <div class="d-flex align-items-center gap-3">
                <p class="mb-0" style="font-size:.78rem;color:var(--mediva-muted);">
                    <span class="req">*</span> Wajib diisi
                </p>
                <button type="submit" class="btn-simpan" id="btnSimpan">
                    <i class="bi bi-floppy2-fill"></i>
                    Simpan Kunjungan ANC
                </button>
            </div>
        </div>

    </form>{{-- /ancForm --}}


    {{-- ── Riwayat Kunjungan Sebelumnya ── --}}
    @if($riwayatKunjungan->count() > 0)
    <div class="riwayat-card mt-4">
        <div class="section-header">
            <div class="section-icon" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <p class="section-title">Riwayat Kunjungan ANC</p>
                <p class="section-subtitle">{{ $riwayatKunjungan->count() }} kunjungan tercatat</p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 riwayat-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>UK</th>
                        <th>BB (kg)</th>
                        <th>TD (mmHg)</th>
                        <th>DJJ (bpm)</th>
                        <th>Hb (g/dL)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatKunjungan as $kunjungan)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_periksa)->format('d/m/Y') }}</td>
                        <td><span class="badge-uk">{{ $kunjungan->usia_kehamilan_minggu }} mgg</span></td>
                        <td>{{ $kunjungan->berat_badan }}</td>
                        <td>{{ $kunjungan->tekanan_darah }}</td>
                        <td>{{ $kunjungan->djj }}</td>
                        <td>{{ $kunjungan->hasil_lab_hb ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>{{-- /container --}}
@endsection


@push('scripts')
<script>
(function () {
    'use strict';

    // ── Konstanta HPHT dari PHP ──────────────────────────────
    const HPHT = new Date('{{ \Carbon\Carbon::parse($pregnancy->hpht)->format('Y-m-d') }}');

    // ── Elemen ──────────────────────────────────────────────
    const inputTanggal = document.getElementById('tanggal_periksa');
    const ukPreview    = document.getElementById('uk-preview');
    const ukValue      = document.getElementById('uk-value');
    const inputTTD     = document.getElementById('pemberian_ttd');
    const ttdValue     = document.getElementById('ttd-value');
    const inputHb      = document.getElementById('hasil_lab_hb');
    const hbStatus     = document.getElementById('hb-status');
    const inputTD      = document.getElementById('tekanan_darah');
    const form         = document.getElementById('ancForm');
    const btnSimpan    = document.getElementById('btnSimpan');

    // ── 1. Auto-hitung Usia Kehamilan saat tanggal berubah ──
    function hitungUK() {
        const tgl = new Date(inputTanggal.value);
        if (isNaN(tgl.getTime()) || tgl < HPHT) {
            ukPreview.classList.add('hidden');
            return;
        }
        const selisihMs      = tgl - HPHT;
        const selisihMinggu  = Math.floor(selisihMs / (1000 * 60 * 60 * 24 * 7));
        ukValue.textContent  = selisihMinggu;
        ukPreview.classList.remove('hidden');
    }
    inputTanggal.addEventListener('change', hitungUK);
    hitungUK(); // jalankan saat halaman load

    // ── 2. Update tampilan nilai slider TTD ─────────────────
    inputTTD.addEventListener('input', function () {
        ttdValue.textContent = this.value;
    });

    // ── 3. Indikator status Hb ──────────────────────────────
    function cekHb() {
        const hb = parseFloat(inputHb.value);
        if (!inputHb.value || isNaN(hb)) {
            hbStatus.style.display = 'none';
            return;
        }
        hbStatus.style.display = 'block';
        if (hb >= 11) {
            hbStatus.className = 'normal';
            hbStatus.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Normal (≥11 g/dL)';
        } else if (hb >= 8) {
            hbStatus.className = 'anemia';
            hbStatus.innerHTML = '<i class="bi bi-exclamation-circle-fill me-1"></i>Anemia Ringan–Sedang';
        } else {
            hbStatus.className = 'severe';
            hbStatus.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Anemia Berat (<8 g/dL)';
        }
    }
    inputHb.addEventListener('input', cekHb);
    cekHb();

    // ── 4. Auto-format tekanan darah (hanya angka dan /) ────
    inputTD.addEventListener('input', function () {
        // Hapus semua karakter selain angka dan /
        let val = this.value.replace(/[^\d/]/g, '');
        // Tambahkan / otomatis setelah 3 digit pertama (opsional)
        if (/^\d{3}$/.test(val) && !val.includes('/')) {
            val = val + '/';
        }
        this.value = val;
    });

    // ── 5. Validasi TD saat blur ─────────────────────────────
    inputTD.addEventListener('blur', function () {
        const re = /^\d{2,3}\/\d{2,3}$/;
        if (this.value && !re.test(this.value)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // ── 6. Pastikan minimal 1 TT dipilih sebelum submit ─────
    form.addEventListener('submit', function (e) {
        const ttChecked = document.querySelector('input[name="status_imunisasi_tt"]:checked');
        if (!ttChecked) {
            e.preventDefault();
            const ttGroup = document.querySelector('.tt-group');
            ttGroup.style.outline = '2px solid var(--mediva-danger)';
            ttGroup.style.borderRadius = '.5rem';
            ttGroup.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Loading state pada tombol submit
        btnSimpan.disabled = true;
        btnSimpan.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
    });

})();
</script>
@endpush