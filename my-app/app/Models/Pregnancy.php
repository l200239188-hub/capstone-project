<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    protected $fillable = [
        'patient_id', 'kehamilan_ke', 'hpht', 'hpl', 'status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function checkups()
    {
        return $this->hasMany(AncCheckup::class);
    }
}