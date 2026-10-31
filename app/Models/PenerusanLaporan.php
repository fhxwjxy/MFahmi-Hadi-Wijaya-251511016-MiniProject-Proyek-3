<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerusanLaporan extends Model
{
    protected $table = 'penerusan_laporan';
    protected $fillable = ['laporan_id', 'koordinator_id', 'pihak_tujuan_id', 'tgl_penerusan', 'status_penerusan', 'catatan'];
    protected $casts = ['tgl_penerusan' => 'datetime'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function koordinator()
    {
        return $this->belongsTo(User::class, 'koordinator_id');
    }

    public function pihakTujuan()
    {
        return $this->belongsTo(PihakTujuan::class);
    }
}
