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
        Schema::create('pihak_tujuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi', 100);
            $table->string('kontak', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('penerusan_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->restrictOnDelete();
            $table->foreignId('koordinator_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('pihak_tujuan_id')->constrained('pihak_tujuan')->restrictOnDelete();
            $table->dateTime('tgl_penerusan');
            $table->string('status_penerusan', 20)->default('tercatat');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['laporan_id', 'pihak_tujuan_id']); // BR-03: tidak ganda ke pihak yang sama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerusan_laporan');
        Schema::dropIfExists('pihak_tujuan');
    }
};
