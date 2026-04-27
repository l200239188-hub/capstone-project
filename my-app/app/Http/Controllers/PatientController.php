<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Menampilkan daftar semua pasien (dengan fitur pencarian dan pagination).
     */
    public function index(Request $request)
    {
        $search = $request->search;

        // Inisialisasi query
        $query = Patient::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%');
            });
        }

        // Ganti all() atau get() menjadi paginate()
        // Angka 10 di bawah ini artinya menampilkan 10 data per halaman
        // withQueryString() supaya saat pindah halaman, keyword pencariannya nggak hilang
        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    /**
     * Menampilkan form tambah pasien baru.
     */
    public function create()
    {
        return view('patients.create');
    }

    /**
     * Menyimpan data pasien baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik'               => 'required|numeric|digits:16|unique:patients,nik',
            'nama_lengkap'      => 'required|string|max:255',
            'no_hp'             => 'nullable|numeric',
            'tanggal_lahir'     => 'required|date',
            'alamat'            => 'nullable|string',
            'golongan_darah'    => 'nullable|in:A,B,AB,O',
            'tinggi_badan'      => 'nullable|integer|min:50|max:250',
            'riwayat_penyakit'  => 'nullable|string',
            'alergi'            => 'nullable|string',
        ], [
            'nik.required'       => 'NIK wajib diisi.',
            'nik.numeric'        => 'NIK harus berupa angka, tidak boleh huruf.',
            'nik.digits'         => 'NIK harus pas 16 angka.',
            'nik.unique'         => 'NIK ini sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_hp.numeric'      => 'Nomor HP harus berupa angka.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'golongan_darah.in'  => 'Golongan darah harus A, B, AB, atau O.',
            'tinggi_badan.integer' => 'Tinggi badan harus berupa angka.',
            'tinggi_badan.min'   => 'Tinggi badan minimal 50 cm.',
            'tinggi_badan.max'   => 'Tinggi badan maksimal 250 cm.',
        ]);

        Patient::create($validatedData);

        return redirect()->route('patients.index')->with('success', 'Mantap! Data pasien baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data pasien.
     */
    public function edit(string $id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Menyimpan perubahan data pasien ke database.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'nik'               => 'required|numeric|digits:16|unique:patients,nik,' . $id,
            'nama_lengkap'      => 'required|string|max:255',
            'no_hp'             => 'nullable|numeric',
            'tanggal_lahir'     => 'required|date',
            'alamat'            => 'nullable|string',
            'golongan_darah'    => 'nullable|in:A,B,AB,O',
            'tinggi_badan'      => 'nullable|integer|min:50|max:250',
            'riwayat_penyakit'  => 'nullable|string',
            'alergi'            => 'nullable|string',
        ], [
            'nik.required'       => 'NIK wajib diisi.',
            'nik.numeric'        => 'NIK harus berupa angka.',
            'nik.digits'         => 'NIK harus pas 16 angka.',
            'nik.unique'         => 'NIK ini sudah terdaftar di sistem.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'golongan_darah.in'  => 'Golongan darah harus A, B, AB, atau O.',
            'tinggi_badan.integer' => 'Tinggi badan harus berupa angka.',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validatedData);

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Menghapus data pasien dari database.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil dihapus dari sistem.');
    }
}
