<?php

namespace App\Http\Controllers;

use App\Models\DesaKelurahan;
use App\Models\Laporan;
use Illuminate\Http\Request;

class AreaInfoController extends Controller
{
    public function laporanAktif(Request $request, DesaKelurahan $desa)
    {
        $user = $request->user();

        $query = Laporan::aktif()
            ->whereHas('lokasi', fn($q) => $q->where('desa_kelurahan_id', $desa->id));

        $jumlah = (clone $query)->count();

        $daftar = (clone $query)
            ->when($user->punyaRole('pelapor'), fn($q) => $q->where('pelapor_id', $user->id))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($l) => [
                'id' => $l->id,
                'status' => $l->status_laporan,
                'kondisi' => $l->tingkat_kondisi,
                'url' => route('laporan.show', $l),
            ]);

        return response()->json([
            'wilayah' => $desa->nama,
            'jumlah_aktif' => $jumlah,
            'laporan' => $daftar,
        ]);
    }
}