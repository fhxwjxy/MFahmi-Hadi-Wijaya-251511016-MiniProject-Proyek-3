<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}
