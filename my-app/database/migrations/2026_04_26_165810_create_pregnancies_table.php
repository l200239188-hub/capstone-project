<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel patients
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->integer('kehamilan_ke');
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->enum('status', ['Aktif', 'Selesai', 'Keguguran'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancies');
    }
};