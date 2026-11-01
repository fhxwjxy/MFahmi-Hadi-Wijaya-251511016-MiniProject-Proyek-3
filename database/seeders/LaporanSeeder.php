<?php

namespace Database\Seeders;

use App\Models\DesaKelurahan;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\PenerusanLaporan;
use App\Models\PihakTujuan;
use App\Models\RiwayatStatus;
use App\Models\TinjauLaporan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $pelapor = User::whereHas('role', fn($q) => $q->where('nama_role', 'pelapor'))->get();
        $relawan = User::where('email', 'relawan@karhutla.test')->first();
        $koordinator = User::where('email', 'koordinator@karhutla.test')->first();
        $desa = DesaKelurahan::all();
        $pihak = PihakTujuan::first();
        $kondisi = ['ringan', 'sedang', 'berat'];

        $statuses = [
            'baru',
            'baru',
            'baru',
            'perlu_perbaikan',
            'ditinjau',
            'ditinjau',
            'perlu_diteruskan',
            'perlu_diteruskan',
            'diteruskan',
            'diteruskan',
            'selesai',
            'selesai',
        ];

        foreach ($statuses as $i => $status) {
            $lokasi = Lokasi::create([
                'desa_kelurahan_id' => $desa[$i % $desa->count()]->id,
                'alamat_spesifik' => 'Lahan contoh nomor ' . ($i + 1) . ' (data dummy)',
                'latitude' => -6.85 - ($i * 0.01),
                'longitude' => 107.60 + ($i * 0.01),
            ]);

            $laporan = Laporan::create([
                'pelapor_id' => $pelapor[$i % $pelapor->count()]->id,
                'lokasi_id' => $lokasi->id,
                'deskripsi' => 'Terlihat asap di area lahan, laporan dummy nomor ' . ($i + 1) . '.',
                'tingkat_kondisi' => $kondisi[$i % 3],
                'waktu_kejadian' => now()->subDays(12 - $i),
                'status_laporan' => $status,
            ]);

            if ($status !== 'baru') {
                $statusTinjau = $status === 'perlu_perbaikan' ? 'perlu_perbaikan' : 'ditinjau';

                TinjauLaporan::create([
                    'laporan_id' => $laporan->id,
                    'relawan_id' => $relawan->id,
                    'tgl_pemeriksaan' => now()->subDays(11 - $i),
                    'hasil_pemeriksaan' => $status === 'perlu_perbaikan' ? 'kurang_lengkap' : 'lengkap',
                    'catatan' => 'Catatan peninjauan dummy.',
                ]);

                RiwayatStatus::create([
                    'laporan_id' => $laporan->id,
                    'pencatat_id' => $relawan->id,
                    'status_sebelumnya' => 'baru',
                    'status_baru' => $statusTinjau,
                    'diubah_pada' => now()->subDays(11 - $i),
                ]);
            }

            if (in_array($status, ['diteruskan', 'selesai'], true)) {
                PenerusanLaporan::create([
                    'laporan_id' => $laporan->id,
                    'koordinator_id' => $koordinator->id,
                    'pihak_tujuan_id' => $pihak->id,
                    'tgl_penerusan' => now()->subDays(10 - $i),
                    'status_penerusan' => 'tercatat',
                    'catatan' => 'Diteruskan (data dummy).',
                ]);
            }
        }
    }
}