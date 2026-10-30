<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi';
    protected $fillable = ['desa_kelurahan_id', 'alamat_spesifik', 'latitude', 'longitude'];

    public function desaKelurahan()
    {
        return $this->belongsTo(DesaKelurahan::class);
    }

    public function laporan()
    {
        return $this->hasMany(Laporan::class);
    }
}
