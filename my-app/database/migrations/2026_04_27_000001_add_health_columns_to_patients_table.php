<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom data kesehatan STATIS ke tabel patients.
     * Data kehamilan dinamis (HPHT, HPL, gravida, dll.) akan di tabel terpisah (Step 2).
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable()->after('tanggal_lahir');
            $table->integer('tinggi_badan')->nullable()->comment('dalam cm')->after('golongan_darah');
            $table->text('riwayat_penyakit')->nullable()->after('tinggi_badan');
            $table->text('alergi')->nullable()->after('riwayat_penyakit');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['golongan_darah', 'tinggi_badan', 'riwayat_penyakit', 'alergi', 'user_id']);
        });
    }
};
