<?php

namespace App\Observers;

use App\Models\Laporan;
use App\Models\RiwayatStatus;

class LaporanObserver
{
    public function updated(Laporan $laporan): void
    {
        if ($laporan->wasChanged('status_laporan')) {
            RiwayatStatus::create([
                'laporan_id' => $laporan->id,
                'pencatat_id' => auth()->id(),
                'status_sebelumnya' => $laporan->getOriginal('status_laporan'),
                'status_baru' => $laporan->status_laporan,
                'diubah_pada' => now(),
            ]);
        }
    }
}