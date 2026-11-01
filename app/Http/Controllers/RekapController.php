<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['kecamatan' => ['nullable', 'integer', 'exists:kecamatan,id']]);
        $kecamatanId = $request->input('kecamatan');

        $base = Laporan::query()->when($kecamatanId, fn($q) => $q->whereHas(
            'lokasi.desaKelurahan',
            fn($d) => $d->where('kecamatan_id', $kecamatanId)
        ));

        $ringkasan = [
            'Total laporan' => (clone $base)->count(),
            'Laporan aktif' => (clone $base)->aktif()->count(),
            'Laporan diteruskan' => (clone $base)->where('status_laporan', 'diteruskan')->count(),
            'Laporan selesai' => (clone $base)->where('status_laporan', 'selesai')->count(),
        ];
        $kolom = collect(Laporan::STATUS)
            ->map(fn($s) => "SUM(laporan.status_laporan = '$s') as $s")
            ->implode(', ');

        $tabel = DB::table('laporan')
            ->join('lokasi', 'laporan.lokasi_id', '=', 'lokasi.id')
            ->join('desa_kelurahan', 'lokasi.desa_kelurahan_id', '=', 'desa_kelurahan.id')
            ->join('kecamatan', 'desa_kelurahan.kecamatan_id', '=', 'kecamatan.id')
            ->when($kecamatanId, fn($q) => $q->where('kecamatan.id', $kecamatanId))
            ->selectRaw("kecamatan.nama as kecamatan, COUNT(*) as total, $kolom")
            ->groupBy('kecamatan.id', 'kecamatan.nama')
            ->orderBy('kecamatan.nama')
            ->get();

        return view('rekap.index', [
            'ringkasan' => $ringkasan,
            'tabel' => $tabel,
            'statuses' => Laporan::STATUS,
            'kecamatan' => Kecamatan::orderBy('nama')->get(),
        ]);
    }
}