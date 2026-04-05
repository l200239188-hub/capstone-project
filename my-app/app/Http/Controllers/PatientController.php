<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Jangan lupa tambahkan (Request $request) di dalam kurung
    public function index(Request $request)
        {
            // 1. Tangkap apa yang diketik user di kolom pencarian
            $search = $request->search;

            // 2. Logika percabangan (Jika ada yang dicari vs Jika tidak ada)
            if ($search) {
                // Cari data yang namanya ATAU nik-nya mirip dengan yang diketik
                $patients = \App\Models\Patient::where('nama_lengkap', 'like', '%' . $search . '%')
                                            ->orWhere('nik', 'like', '%' . $search . '%')
                                            ->get();
            } else {
                // Jika kolom pencarian kosong, ambil semua data
                $patients = \App\Models\Patient::all();
            }
            
            return view('patients.index', compact('patients'));
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
        {
            return view('patients.create');
        }

    /**
     * Store a newly created resource in storage.
     */
    // 1. Ubah baris 'return' di fungsi store yang sebelumnya berupa teks
    public function store(Request $request)
        {
            // 1. PASANG SATPAM (Validasi Data)
            $validatedData = $request->validate([
                'nik' => 'required|numeric|digits:16|unique:patients,nik',
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'nullable|numeric',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'nullable|string'
            ], [
                // 2. Siapkan terjemahan pesan error ke bahasa Indonesia
                'nik.required' => 'NIK wajib diisi.',
                'nik.numeric' => 'NIK harus berupa angka, tidak boleh huruf.',
                'nik.digits' => 'NIK harus pas 16 angka.',
                'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'no_hp.numeric' => 'Nomor HP harus berupa angka.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.'
            ]);

            // 3. Kalau lolos validasi, baru data disimpan
            $patient = new \App\Models\Patient;
            $patient->nik = $validatedData['nik'];
            $patient->nama_lengkap = $validatedData['nama_lengkap'];
            $patient->alamat = $request->alamat;
            $patient->no_hp = $request->no_hp;
            $patient->tanggal_lahir = $validatedData['tanggal_lahir'];
            $patient->save();

            return redirect()->route('patients.index')->with('success', 'Mantap! Data pasien baru berhasil ditambahkan.'); 
        }

    // 2. Isi fungsi edit untuk menampilkan form ubah data
    public function edit(string $id)
        {
            $patient = \App\Models\Patient::findOrFail($id);
            return view('patients.edit', compact('patient'));
        }

    // 3. Isi fungsi update untuk menyimpan perubahan ke database
    public function update(Request $request, string $id)
        {
            // 1. Validasi Data untuk Update
            $validatedData = $request->validate([
                // Tambahkan .$id agar NIK pasien ini tidak bentrok dengan datanya sendiri
                'nik' => 'required|numeric|digits:16|unique:patients,nik,' . $id,
                'nama_lengkap' => 'required|string|max:255',
                'no_hp' => 'nullable|numeric',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'nullable|string'
            ], [
                'nik.required' => 'NIK wajib diisi.',
                'nik.numeric' => 'NIK harus berupa angka.',
                'nik.digits' => 'NIK harus pas 16 angka.',
                'nik.unique' => 'NIK ini sudah terdaftar di sistem.',
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.'
            ]);

            // 2. Simpan Perubahan
            $patient = \App\Models\Patient::findOrFail($id);
            $patient->nik = $validatedData['nik'];
            $patient->nama_lengkap = $validatedData['nama_lengkap'];
            $patient->alamat = $request->alamat;
            $patient->no_hp = $request->no_hp;
            $patient->tanggal_lahir = $validatedData['tanggal_lahir'];
            $patient->save();

            return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
        }

    // 4. Isi fungsi destroy untuk menghapus data
    public function destroy(string $id)
        {
            $patient = \App\Models\Patient::findOrFail($id);
            $patient->delete();

            return redirect()->route('patients.index')->with('success', 'Data pasien berhasil dihapus dari sistem.');
        }
}
