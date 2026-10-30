<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';
    protected $fillable = ['kota_kabupaten_id', 'nama'];

    public function kotaKabupaten()
    {
        return $this->belongsTo(KotaKabupaten::class);
    }

    public function desaKelurahan()
    {
        return $this->hasMany(DesaKelurahan::class);
    }
}
