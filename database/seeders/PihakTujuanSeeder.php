<?php
namespace Database\Seeders;

use App\Models\PihakTujuan;
use Illuminate\Database\Seeder;

class PihakTujuanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['BPBD', 'bpbd@contoh.test'],
            ['Manggala Agni', 'manggala@contoh.test'],
            ['Damkar', 'damkar@contoh.test'],
            ['Pemerintah Desa/Kelurahan', 'desa@contoh.test'],
            ['Pengelola Kawasan', 'kawasan@contoh.test'],
        ];

        foreach ($data as [$nama, $kontak]) {
            PihakTujuan::create(['nama_instansi' => $nama, 'kontak' => $kontak]);
        }
    }
}