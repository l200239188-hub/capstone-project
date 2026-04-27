<?php

namespace App\Http\Controllers;

use App\Models\AncCheckup;
use App\Models\Patient;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AncController extends Controller
{
    /**
     * Tampilkan form input kunjungan ANC untuk kehamilan tertentu.
     *
     * @param  int  $pregnancyId
     * @return \Illuminate\View\View
     */
    public function create($pregnancyId)
    {
        // Ambil data kehamilan beserta relasi pasiennya
        $pregnancy = Pregnancy::with('patient')->findOrFail($pregnancyId);

        // Hitung HPL jika belum ada (estimasi 40 minggu dari HPHT)
        $hpht = Carbon::parse($pregnancy->hpht);
        $today = Carbon::today();

        // Estimasi usia kehamilan saat ini (untuk ditampilkan di info)
        $usiaKehamilanSaatIni = $hpht->diffInWeeks($today);

        // Kunjungan ANC sebelumnya (untuk referensi)
        $riwayatKunjungan = AncCheckup::where('pregnancy_id', $pregnancyId)
            ->orderByDesc('tanggal_periksa')
            ->get();

        return view('anc.create', compact(
            'pregnancy',
            'usiaKehamilanSaatIni',
            'riwayatKunjungan'
        ));
    }

    /**
     * Validasi dan simpan data kunjungan ANC ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $pregnancyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $pregnancyId)
    {
        // Pastikan kehamilan ada
        $pregnancy = Pregnancy::findOrFail($pregnancyId);

        // Validasi input
        $validated = $request->validate([
            'tanggal_periksa'     => 'required|date|before_or_equal:today',
            'berat_badan'         => 'required|numeric|min:20|max:200',
            'tekanan_darah'       => ['required', 'string', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'lila'                => 'required|numeric|min:10|max:50',
            'tinggi_fundus'       => 'required|integer|min:0|max:50',
            'djj'                 => 'required|integer|min:60|max:200',
            'status_imunisasi_tt' => 'required|string|max:50',
            'pemberian_ttd'       => 'required|integer|min:0|max:90',
            'hasil_lab_hb'        => 'nullable|numeric|min:1|max:25',
            'tatalaksana'         => 'required|string|max:2000',
            'konseling'           => 'required|string|max:2000',
        ], [
            'tanggal_periksa.before_or_equal' => 'Tanggal periksa tidak boleh melebihi hari ini.',
            'tekanan_darah.regex'             => 'Format tekanan darah harus seperti: 120/80.',
            'berat_badan.min'                 => 'Berat badan minimal 20 kg.',
            'djj.min'                         => 'DJJ minimal 60 bpm.',
            'djj.max'                         => 'DJJ maksimal 200 bpm.',
        ]);

        // ============================================================
        // PERHITUNGAN OTOMATIS: Usia Kehamilan dalam Minggu
        // Rumus: selisih hari antara tanggal_periksa dan hpht, dibagi 7
        // ============================================================
        $hpht           = Carbon::parse($pregnancy->hpht);
        $tanggalPeriksa = Carbon::parse($validated['tanggal_periksa']);
        $usiaKehamilanMinggu = (int) $hpht->diffInWeeks($tanggalPeriksa);

        // Simpan ke database
        AncCheckup::create([
            'pregnancy_id'          => $pregnancy->id,
            'tanggal_periksa'       => $validated['tanggal_periksa'],
            'usia_kehamilan_minggu' => $usiaKehamilanMinggu,
            'berat_badan'           => $validated['berat_badan'],
            'tekanan_darah'         => $validated['tekanan_darah'],
            'lila'                  => $validated['lila'],
            'tinggi_fundus'         => $validated['tinggi_fundus'],
            'djj'                   => $validated['djj'],
            'status_imunisasi_tt'   => $validated['status_imunisasi_tt'],
            'pemberian_ttd'         => $validated['pemberian_ttd'],
            'hasil_lab_hb'          => $validated['hasil_lab_hb'],
            'tatalaksana'           => $validated['tatalaksana'],
            'konseling'             => $validated['konseling'],
        ]);

        return redirect()
            ->route('pregnancies.show', $pregnancy->id)
            ->with('success', 'Data kunjungan ANC berhasil disimpan.');

        // ... (kode validasi dan perhitungan usia kehamilanmu yang sudah ada)

        // ============================================================
        // STEP 3: ALGORITMA SKRINING ANEMIA (STANDAR BUKU KIA 2024)
        // ============================================================
        $hb = $validated['hasil_lab_hb'];
        $statusAnemia = 'Normal'; // Default
        $rekomendasi = $validated['tatalaksana']; // Ambil dari input awal dulu

        if ($hb) {
            // Tentukan Trimester
            if ($usiaKehamilanMinggu < 12 || $usiaKehamilanMinggu > 28) {
                // Trimester 1 & 3: Ambang batas Hb < 11
                if ($hb < 11) {
                    $statusAnemia = ($hb < 8) ? 'Anemia Berat' : 'Anemia Ringan';
                }
            } else {
                // Trimester 2: Ambang batas Hb < 10.5
                if ($hb < 10.5) {
                    $statusAnemia = ($hb < 8) ? 'Anemia Berat' : 'Anemia Ringan';
                }
            }

            // Tambahkan Rekomendasi Otomatis jika Anemia
            if ($statusAnemia !== 'Normal') {
                $rekomendasi .= "\n\n[Saran Sistem]: Pasien terdeteksi " . $statusAnemia . ". ";
                $rekomendasi .= ($statusAnemia == 'Anemia Ringan') 
                    ? "Berikan TTD 60-120 mg/hari." 
                    : "Rujuk ke RS/Dokter Spesialis segera.";
            }
        }

        // Simpan ke database (tambahkan status anemia ke kolom tatalaksana atau konseling)
        AncCheckup::create([
            'pregnancy_id'          => $pregnancy->id,
            'tanggal_periksa'       => $validated['tanggal_periksa'],
            'usia_kehamilan_minggu' => $usiaKehamilanMinggu,
            'berat_badan'           => $validated['berat_badan'],
            'tekanan_darah'         => $validated['tekanan_darah'],
            'lila'                  => $validated['lila'],
            'tinggi_fundus'         => $validated['tinggi_fundus'],
            'djj'                   => $validated['djj'],
            'status_imunisasi_tt'   => $validated['status_imunisasi_tt'],
            'pemberian_ttd'         => $validated['pemberian_ttd'],
            'hasil_lab_hb'          => $validated['hasil_lab_hb'],
            'tatalaksana'           => $rekomendasi, // Simpan hasil skrining di sini
            'konseling'             => $validated['konseling'],
        ]);

        return redirect()
            ->route('patients.index') // Balik ke daftar pasien biar gampang ngeceknya
            ->with('success', 'Data ANC disimpan. Status: ' . $statusAnemia);
    }
}