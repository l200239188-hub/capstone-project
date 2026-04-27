{{-- resources/views/patients/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Pasien - ' . $patient->nama_lengkap)

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

    /* ── Page Header ── */
    .page-header {
        background: linear-gradient(135deg, var(--mediva-warn) 0%, #b45309 100%);
        border-radius: var(--radius-card);
        padding: 1.75rem 2rem;
        color: #fff;
        margin-bottom: 1.75rem;
        box-shadow: 0 4px 20px rgba(217,119,6,.28);
        position: relative;
        overflow: hidden;
    }
    .page-header::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,.06); border-radius: 50%;
    }
    .page-header::after {
        content: '';
        position: absolute; bottom: -60px; right: 80px;
        width: 140px; height: 140px;
        background: rgba(255,255,255,.04); border-radius: 50%;
    }
    .page-title { font-size: 1.3rem; font-weight: 800; margin: 0; letter-spacing: -.02em; }
    .page-sub   { font-size: .84rem; opacity: .82; margin-top: .25rem; margin-bottom: 0; }

    /* Breadcrumb di header */
    .breadcrumb-item a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .82rem; }
    .breadcrumb-item a:hover { color: #fff; }
    .breadcrumb-item.active { color: rgba(255,255,255,.95); font-size: .82rem; }
    .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,.4); }

    /* Avatar inisial besar di header */
    .header-avatar {
        width: 3.5rem; height: 3.5rem; border-radius: .75rem;
        background: rgba(255,255,255,.2); border: 2px solid rgba(255,255,255,.35);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; font-weight: 800; color: #fff;
        flex-shrink: 0; backdrop-filter: blur(4px);
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
    .section-card:focus-within {
        box-shadow: 0 0 0 3px rgba(13,148,136,.15), var(--shadow-card);
    }

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
    .icon-warn  { background: #fef3c7; color: var(--mediva-warn); }
    .icon-teal  { background: var(--mediva-teal-lt); color: var(--mediva-teal); }

    .section-title    { font-size: .95rem; font-weight: 700; color: var(--mediva-navy); margin: 0; }
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
    .form-hint        { font-size: .76rem; color: var(--mediva-muted); margin-top: .25rem; }

    /* Textarea */
    .form-control[rows] { resize: vertical; min-height: 5rem; }

    /* Input group unit */
    .input-unit {
        background: #f1f5f9; border: 1.5px solid var(--mediva-border);
        border-left: none; border-radius: 0 .5rem .5rem 0;
        padding: .55rem .8rem; font-size: .8rem;
        color: var(--mediva-muted); white-space: nowrap;
    }
    .input-group .form-control { border-radius: .5rem 0 0 .5rem; }

    /* ── Submit Bar ── */
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
        background: var(--mediva-warn); color: #fff;
        border: none; border-radius: .5rem;
        padding: .65rem 2rem; font-size: .95rem; font-weight: 700;
        transition: background .18s, transform .1s, box-shadow .18s;
        display: inline-flex; align-items: center; gap: .5rem;
    }
    .btn-simpan:hover {
        background: #b45309; color: #fff;
        box-shadow: 0 4px 12px rgba(217,119,6,.35);
        transform: translateY(-1px);
    }
    .btn-simpan:active { transform: translateY(0); }

    .btn-batal {
        color: var(--mediva-slate); background: transparent;
        border: 1.5px solid var(--mediva-border);
        border-radius: .5rem; padding: .65rem 1.5rem;
        font-size: .9rem; font-weight: 600;
        transition: all .15s; text-decoration: none;
        display: inline-flex; align-items: center; gap: .5rem;
    }
    .btn-batal:hover { background: #f1f5f9; color: var(--mediva-navy); }

    /* ── Changed indicator ── */
    .form-control.changed, .form-select.changed {
        border-color: var(--mediva-warn);
        background: #fffbeb;
    }

    /* ── Golongan darah pills ── */
    .gd-group { display: flex; gap: .5rem; flex-wrap: wrap; }
    .gd-group input[type="radio"] { display: none; }
    .gd-group label {
        cursor: pointer; padding: .4rem .9rem; border-radius: 2rem;
        border: 1.5px solid var(--mediva-border);
        font-size: .85rem; font-weight: 700; color: var(--mediva-slate);
        transition: all .15s; user-select: none; min-width: 2.8rem;
        text-align: center;
    }
    .gd-group input[type="radio"]:checked + label {
        background: #7c3aed; border-color: #7c3aed; color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 820px;">

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

    {{-- ── Page Header (Amber/Warning — beda dari teal agar user tahu ini mode edit) ── --}}
    @php
        $words   = explode(' ', $patient->nama_lengkap);
        $inisial = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    @endphp

    <div class="page-header">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('patients.index') }}">
                        <i class="bi bi-people me-1"></i>Pasien
                    </a>
                </li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </nav>

        <div class="d-flex align-items-center gap-3">
            <div class="header-avatar">{{ $inisial }}</div>
            <div>
                <p class="page-title">
                    <i class="bi bi-pencil-square me-2"></i>Edit Data Pasien
                </p>
                <p class="page-sub">
                    {{ $patient->nama_lengkap }}
                    &nbsp;·&nbsp;
                    NIK: <strong>{{ $patient->nik ?? '-' }}</strong>
                </p>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════ --}}
    {{-- FORM UTAMA                                            --}}
    {{-- ══════════════════════════════════════════════════════ --}}
    <form action="{{ route('patients.update', $patient->id) }}"
          method="POST"
          id="editForm"
          novalidate>
        @csrf
        @method('PUT')

        {{-- ┌───────────────────────────────────────────────┐ --}}
        {{-- │  BAGIAN 1 · DATA IDENTITAS                    │ --}}
        {{-- └───────────────────────────────────────────────┘ --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon icon-warn">
                    <i class="bi bi-person-vcard-fill"></i>
                </div>
                <div>
                    <p class="section-title">Bagian 1 — Data Identitas</p>
                    <p class="section-subtitle">Informasi kependudukan dan kontak pasien</p>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3">

                    {{-- NIK --}}
                    <div class="col-md-6">
                        <label for="nik" class="form-label">
                            NIK KTP <span class="req">*</span>
                        </label>
                        <input type="text"
                               id="nik"
                               name="nik"
                               class="form-control @error('nik') is-invalid @enderror"
                               value="{{ old('nik', $patient->nik) }}"
                               placeholder="Masukkan 16 digit NIK"
                               maxlength="16"
                               required>
                        @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <p class="form-hint">16 digit sesuai KTP</p>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="col-md-6">
                        <label for="tanggal_lahir" class="form-label">
                            Tanggal Lahir <span class="req">*</span>
                        </label>
                        <input type="date"
                               id="tanggal_lahir"
                               name="tanggal_lahir"
                               class="form-control @error('tanggal_lahir') is-invalid @enderror"
                               value="{{ old('tanggal_lahir', $patient->tanggal_lahir->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="col-12">
                        <label for="nama_lengkap" class="form-label">
                            Nama Lengkap <span class="req">*</span>
                        </label>
                        <input type="text"
                               id="nama_lengkap"
                               name="nama_lengkap"
                               class="form-control @error('nama_lengkap') is-invalid @enderror"
                               value="{{ old('nama_lengkap', $patient->nama_lengkap) }}"
                               placeholder="Nama sesuai KTP"
                               required>
                        @error('nama_lengkap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea id="alamat"
                                  name="alamat"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Contoh: Jl. Sudirman No. 10, Kelurahan…">{{ old('alamat', $patient->alamat) }}</textarea>
                    </div>

                    {{-- No HP --}}
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label">Nomor Handphone (WA)</label>
                        <div class="input-group">
                            <input type="text"
                                   id="no_hp"
                                   name="no_hp"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $patient->no_hp) }}"
                                   placeholder="0812xxxxxx">
                            <span class="input-unit"><i class="bi bi-whatsapp"></i></span>
                        </div>
                        @error('no_hp')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /section-body --}}
        </div>{{-- /section-card --}}


        {{-- ┌───────────────────────────────────────────────┐ --}}
        {{-- │  BAGIAN 2 · DATA KESEHATAN DASAR              │ --}}
        {{-- └───────────────────────────────────────────────┘ --}}
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon icon-teal">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <div>
                    <p class="section-title">Bagian 2 — Data Kesehatan Dasar</p>
                    <p class="section-subtitle">Antropometri, riwayat penyakit, dan alergi</p>
                </div>
            </div>
            <div class="section-body">
                <div class="row g-3">

                    {{-- Golongan Darah --}}
                    <div class="col-12">
                        <label class="form-label d-block">Golongan Darah</label>
                        <div class="gd-group">
                            @foreach(['A', 'B', 'AB', 'O'] as $gd)
                            <div>
                                <input type="radio"
                                       id="gd_{{ $gd }}"
                                       name="golongan_darah"
                                       value="{{ $gd }}"
                                       {{ old('golongan_darah', $patient->golongan_darah) == $gd ? 'checked' : '' }}>
                                <label for="gd_{{ $gd }}">{{ $gd }}</label>
                            </div>
                            @endforeach
                            {{-- Opsi kosong / tidak tahu --}}
                            <div>
                                <input type="radio"
                                       id="gd_none"
                                       name="golongan_darah"
                                       value=""
                                       {{ old('golongan_darah', $patient->golongan_darah) == '' ? 'checked' : '' }}>
                                <label for="gd_none" style="font-weight:500;">Tidak Tahu</label>
                            </div>
                        </div>
                        @error('golongan_darah')
                            <p class="form-hint" style="color:var(--mediva-danger);">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tinggi Badan --}}
                    <div class="col-md-6">
                        <label for="tinggi_badan" class="form-label">Tinggi Badan</label>
                        <div class="input-group">
                            <input type="number"
                                   id="tinggi_badan"
                                   name="tinggi_badan"
                                   class="form-control @error('tinggi_badan') is-invalid @enderror"
                                   value="{{ old('tinggi_badan', $patient->tinggi_badan) }}"
                                   placeholder="155"
                                   min="50" max="250">
                            <span class="input-unit">cm</span>
                        </div>
                        @error('tinggi_badan')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Riwayat Penyakit --}}
                    <div class="col-12">
                        <label for="riwayat_penyakit" class="form-label">Riwayat Penyakit</label>
                        <textarea id="riwayat_penyakit"
                                  name="riwayat_penyakit"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Contoh: Diabetes, Hipertensi, Asma…">{{ old('riwayat_penyakit', $patient->riwayat_penyakit) }}</textarea>
                        <p class="form-hint">Kosongkan jika tidak ada riwayat penyakit.</p>
                    </div>

                    {{-- Alergi --}}
                    <div class="col-12">
                        <label for="alergi" class="form-label">Alergi</label>
                        <textarea id="alergi"
                                  name="alergi"
                                  class="form-control"
                                  rows="2"
                                  placeholder="Contoh: Alergi udang, penisilin…">{{ old('alergi', $patient->alergi) }}</textarea>
                        <p class="form-hint">Kosongkan jika tidak ada alergi.</p>
                    </div>

                </div>{{-- /row --}}
            </div>{{-- /section-body --}}
        </div>{{-- /section-card --}}


        {{-- ── Submit Bar ── --}}
        <div class="submit-bar">
            <a href="{{ route('patients.index') }}" class="btn-batal">
                <i class="bi bi-arrow-left"></i> Batal
            </a>
            <div class="d-flex align-items-center gap-3">
                <p class="mb-0" style="font-size:.78rem;color:var(--mediva-muted);">
                    <span style="color:var(--mediva-danger);">*</span> Wajib diisi
                </p>
                <button type="submit" class="btn-simpan" id="btnSimpan">
                    <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    // ── Deteksi perubahan field (highlight kuning) ──────────
    const originalValues = {};
    const fields = document.querySelectorAll('#editForm input, #editForm select, #editForm textarea');

    fields.forEach(field => {
        if (field.type === 'radio') return; // skip radio
        originalValues[field.name] = field.value;

        field.addEventListener('input', function () {
            if (this.value !== originalValues[this.name]) {
                this.classList.add('changed');
            } else {
                this.classList.remove('changed');
            }
        });
    });

    // ── Loading state tombol submit ─────────────────────────
    document.getElementById('editForm').addEventListener('submit', function () {
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan…';
    });

    // ── NIK: hanya angka, max 16 digit ─────────────────────
    document.getElementById('nik').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 16);
    });

    // ── No HP: hanya angka dan + ────────────────────────────
    document.getElementById('no_hp').addEventListener('input', function () {
        this.value = this.value.replace(/[^\d+]/g, '');
    });

})();
</script>
@endpush