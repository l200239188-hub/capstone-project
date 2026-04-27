<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    /**
     * Kolom yang boleh diisi secara mass assignment.
     * Data demografis + data kesehatan statis.
     */
    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'alamat',
        'no_hp',
        'tanggal_lahir',
        'golongan_darah',
        'tinggi_badan',
        'riwayat_penyakit',
        'alergi',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tinggi_badan'  => 'integer',
        ];
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Relasi ke akun User (jika pasien ini punya akun login).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel pregnancies dan anc_checkups akan ditambahkan di Step 2.
    public function pregnancies()
    {
        return $this->hasMany(Pregnancy::class);
    }
}
