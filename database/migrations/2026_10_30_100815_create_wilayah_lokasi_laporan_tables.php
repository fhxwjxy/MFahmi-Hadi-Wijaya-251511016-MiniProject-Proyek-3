<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('provinsi', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('kota_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('provinsi')->restrictOnDelete();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('kota_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('provinsi')->restrictOnDelete();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kota_kabupaten_id')->constrained('kota_kabupaten')->restrictOnDelete();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('desa_kelurahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->restrictOnDelete();
            $table->string('nama', 100);
            $table->timestamps();
        });

        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_kelurahan_id')->constrained('desa_kelurahan')->restrictOnDelete();
            $table->string('alamat_spesifik');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamps();
        });

        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasi')->restrictOnDelete();
            $table->text('deskripsi');
            $table->string('tingkat_kondisi', 10);
            $table->dateTime('waktu_kejadian');
            $table->string('status_laporan', 20)->default('baru')->index();
            $table->string('bukti_path')->nullable();
            $table->string('bukti_nama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['laporan', 'lokasi', 'desa_kelurahan', 'kecamatan', 'kota_kabupaten', 'provinsi'] as $tabel) {
            Schema::dropIfExists($tabel);
        }
    }
};
