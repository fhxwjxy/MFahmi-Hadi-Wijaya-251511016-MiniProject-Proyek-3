<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'pelapor_id',
        'lokasi_id',
        'deskripsi',
        'tingkat_kondisi',
        'waktu_kejadian',
        'status_laporan',
        'bukti_path',
        'bukti_nama',
    ];

    protected $casts = ['waktu_kejadian' => 'datetime'];

    public const STATUS = ['baru', 'perlu_perbaikan', 'ditinjau', 'perlu_diteruskan', 'diteruskan', 'selesai'];

    public const AKTIF = ['baru', 'perlu_perbaikan', 'ditinjau', 'perlu_diteruskan', 'diteruskan'];
    public const TRANSISI = [
        'baru' => ['ditinjau', 'perlu_perbaikan'],
        'perlu_perbaikan' => ['baru'],
        'ditinjau' => ['perlu_diteruskan', 'selesai'],
        'perlu_diteruskan' => ['diteruskan'],
        'diteruskan' => ['selesai'],
        'selesai' => [],
    ];
    public const PERAN_TUJUAN = [
        'baru' => ['pelapor'],
        'ditinjau' => ['relawan'],
        'perlu_perbaikan' => ['relawan'],
        'perlu_diteruskan' => ['relawan'],
        'diteruskan' => ['koordinator'],
        'selesai' => ['koordinator'],
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function tinjau()
    {
        return $this->hasMany(TinjauLaporan::class);
    }

    public function penerusan()
    {
        return $this->hasMany(PenerusanLaporan::class);
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatStatus::class)->orderByDesc('diubah_pada')->orderByDesc('id');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status_laporan', self::AKTIF);
    }

    public function bisaPindahKe(string $baru): bool
    {
        return in_array($baru, self::TRANSISI[$this->status_laporan] ?? [], true);
    }

    public function ubahStatus(string $baru, User $user): void
    {
        $peranBoleh = in_array($user->role->nama_role, self::PERAN_TUJUAN[$baru] ?? [], true);

        if (!$this->bisaPindahKe($baru) || !$peranBoleh) {
            throw ValidationException::withMessages([
                'status_laporan' => "Perubahan status dari {$this->status_laporan} ke {$baru} tidak diizinkan.",
            ]);
        }

        $this->status_laporan = $baru;
        $this->save();
    }
}