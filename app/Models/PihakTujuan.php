<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PihakTujuan extends Model
{
    protected $table = 'pihak_tujuan';
    protected $fillable = ['nama_instansi', 'kontak'];

    public function penerusan()
    {
        return $this->hasMany(PenerusanLaporan::class);
    }
}