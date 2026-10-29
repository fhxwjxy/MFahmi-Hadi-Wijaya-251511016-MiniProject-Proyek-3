<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['pelapor', 'relawan', 'koordinator'] as $nama) {
            Role::firstOrCreate(['nama_role' => $nama]);
        }
    }
}
