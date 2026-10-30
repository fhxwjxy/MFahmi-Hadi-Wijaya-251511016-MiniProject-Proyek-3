<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotaKabupaten extends Model
{
    protected $table = 'kota_kabupaten';
    protected $fillable = ['provinsi', 'nama'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class);
    }
}
