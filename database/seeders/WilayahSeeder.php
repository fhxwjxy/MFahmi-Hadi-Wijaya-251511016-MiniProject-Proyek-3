<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Provinsi;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Kota Bandung' => [
                'Coblong' => ['Dago', 'Lebak Gede'],
                'Lengkong' => ['Turangga', 'Cijagra'],
            ],
            'Kabupaten Bandung Barat' => [
                'Lembang' => ['Lembang', 'Cikole'],
            ],
        ];

        $provinsi = Provinsi::create(['nama' => 'Jawa Barat']);

        foreach ($data as $kota => $kecamatans) {
            $k = $provinsi->kotaKabupaten()->create(['nama' => $kota]);
            foreach ($kecamatans as $kecamatan => $desas) {
                $kc = $k->kecamatan()->create(['nama' => $kecamatan]);
                foreach ($desas as $desa) {
                    $kc->desaKelurahan()->create(['nama' => $desa]);
                }
            }
        }
    }
}
