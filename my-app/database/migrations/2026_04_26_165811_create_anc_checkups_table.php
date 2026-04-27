<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anc_checkups', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel pregnancies
            $table->foreignId('pregnancy_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal_periksa');
            $table->integer('usia_kehamilan_minggu');
            
            // Komponen 10T
            $table->decimal('berat_badan', 5, 2); // 1. Timbang BB
            $table->string('tekanan_darah'); // 2. Ukur TD (misal: '120/80')
            $table->decimal('lila', 4, 1)->nullable(); // 3. Ukur LILA
            $table->integer('tinggi_fundus')->nullable(); // 4. Ukur TFU
            $table->integer('djj')->nullable(); // 5. Detak Jantung Janin
            $table->string('status_imunisasi_tt')->nullable(); // 6. Status TT
            $table->integer('pemberian_ttd')->nullable(); // 7. Jumlah Tablet Tambah Darah
            $table->decimal('hasil_lab_hb', 4, 1)->nullable(); // 8. Tes Lab Hb
            $table->text('tatalaksana')->nullable(); // 9. Tatalaksana/Terapi
            $table->text('konseling')->nullable(); // 10. Temu Wicara
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anc_checkups');
    }
};