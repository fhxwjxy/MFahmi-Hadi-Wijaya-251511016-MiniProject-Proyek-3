<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesaKelurahan extends Model
{
    protected $table = 'desa_kelurahan';
    protected $fillable = ['kecamatan_id', 'nama'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function lokasi()
    {
        return $this->hasMany(Lokasi::class);
    }
}
