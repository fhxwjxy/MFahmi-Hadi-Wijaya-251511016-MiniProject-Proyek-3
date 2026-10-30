<?php
// app/Http/Controllers/LaporanController.php
namespace App\Http\Controllers;

use App\Http\Requests\LaporanRequest;
use App\Models\DesaKelurahan;
use App\Models\Laporan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $laporans = Laporan::with('lokasi.desaKelurahan')
            ->where('pelapor_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function create()
    {
        return view('laporan.create', [
            'desa' => DesaKelurahan::with('kecamatan')->orderBy('nama')->get(),
        ]);
    }

    public function store(LaporanRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $request) {
            $lokasi = Lokasi::create([
                'desa_kelurahan_id' => $data['desa_kelurahan_id'],
                'alamat_spesifik' => $data['alamat_spesifik'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ]);

            Laporan::create([
                'pelapor_id' => $request->user()->id,
                'lokasi_id' => $lokasi->id,
                'deskripsi' => $data['deskripsi'],
                'tingkat_kondisi' => $data['tingkat_kondisi'],
                'waktu_kejadian' => $data['waktu_kejadian'],
                'status_laporan' => 'baru',
            ]);
        });

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }
}