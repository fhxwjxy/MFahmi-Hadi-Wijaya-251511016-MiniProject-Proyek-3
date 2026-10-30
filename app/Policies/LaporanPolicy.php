<?php
// app/Policies/LaporanPolicy.php
namespace App\Policies;

use App\Models\Laporan;
use App\Models\User;

class LaporanPolicy
{
    public function view(User $user, Laporan $laporan): bool
    {
        return $user->punyaRole('relawan', 'koordinator')
            || $laporan->pelapor_id === $user->id;
    }

    public function update(User $user, Laporan $laporan): bool
    {
        // 
        return $laporan->pelapor_id === $user->id;
    }
}