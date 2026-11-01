<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatStatus extends Model
{
    protected $table = 'riwayat_status';
    public $timestamps = false;
    protected $fillable = ['laporan_id', 'pencatat_id', 'status_sebelumnya', 'status_baru', 'diubah_pada'];
    protected $casts = ['diubah_pada' => 'datetime'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'pencatat_id');
    }
}