<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akun = [
            ['Pak Andi', 'pelapor@karhutla.test', 'pelapor', 'aktif'],
            ['Bu Dewi', 'pelapor2@karhutla.test', 'pelapor', 'aktif'],
            ['Bu Sari', 'relawan@karhutla.test', 'relawan', 'aktif'],
            ['Relawan Nonaktif', 'relawan.nonaktif@karhutla.test', 'relawan', 'nonaktif'],
            ['Budi', 'koordinator@karhutla.test', 'koordinator', 'aktif'],
        ];

        foreach ($akun as [$nama, $email, $role, $status]) {
            User::create([
                'name' => $nama,
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => Role::where('nama_role', $role)->value('id'),
                'status_akun' => $status,
            ]);
        }
    }
}
