<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanStatusController extends Controller
{
    public function tutup(Request $request, Laporan $laporan)
    {
        $laporan->ubahStatus('selesai', $request->user());
        return redirect()->route('laporan.show', $laporan)->with('success', 'Laporan ditandai selesai.');
    }
}
