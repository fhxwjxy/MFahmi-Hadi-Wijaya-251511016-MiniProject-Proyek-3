<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinjauLaporan extends Model
{
    protected $table = 'tinjau_laporan';
    protected $fillable = ['laporan_id', 'relawan_id', 'tgl_pemeriksaan', 'hasil_pemeriksaan', 'catatan'];
    protected $casts = ['tgl_pemeriksaan' => 'datetime'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function relawan()
    {
        return $this->belongsTo(User::class, 'relawan_id');
    }
}
