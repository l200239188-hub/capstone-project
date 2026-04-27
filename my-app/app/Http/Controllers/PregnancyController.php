<?php

namespace App\Http\Controllers;

use App\Models\Pregnancy;
use App\Models\Patient;
use Illuminate\Http\Request;

class PregnancyController extends Controller
{
    /**
     * Tampilkan semua kehamilan milik satu pasien.
     */
    public function index($patientId)
    {
        $patient     = Patient::findOrFail($patientId);
        $pregnancies = Pregnancy::where('patient_id', $patientId)
                                ->orderByDesc('created_at')
                                ->get();

        return view('pregnancies.index', compact('patient', 'pregnancies'));
    }

    /**
     * Tampilkan detail satu kehamilan beserta riwayat ANC-nya.
     */
    public function show($id)
    {
        // ✅ Perbaikan 1: nama relasi disesuaikan → 'ancCheckups'
        // ✅ Perbaikan 2: nama view diperbaiki → string biasa tanpa markdown link
        $pregnancy = Pregnancy::with([
            'patient',
            'ancCheckups' => function ($query) {
                $query->orderBy('tanggal_periksa', 'desc');
            }
        ])->findOrFail($id);

        return view('pregnancies.show', compact('pregnancy'));
    }
}