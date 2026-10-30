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
        Schema::create('tinjau_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->restrictOnDelete();
            $table->foreignId('relawan_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('tgl_pemeriksaan');
            $table->string('hasil_pemeriksaan', 20);
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tinjau_laporan');
    }
};
