<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AncCheckup extends Model
{
    protected $fillable = [
        'pregnancy_id', 'tanggal_periksa', 'usia_kehamilan_minggu',
        'berat_badan', 'tekanan_darah', 'lila', 'tinggi_fundus',
        'djj', 'status_imunisasi_tt', 'pemberian_ttd',
        'hasil_lab_hb', 'tatalaksana', 'konseling'
    ];

    public function pregnancy()
    {
        return $this->belongsTo(Pregnancy::class);
    }
}