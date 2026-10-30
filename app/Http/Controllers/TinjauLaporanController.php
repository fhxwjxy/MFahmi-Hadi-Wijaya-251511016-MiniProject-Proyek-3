<?php
namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TinjauLaporanController extends Controller
{
    public function store(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'hasil_pemeriksaan' => ['required', Rule::in(['lengkap', 'kurang_lengkap', 'tidak_wajar'])],
            'catatan' => ['required', 'string', 'max:1000'],
            'status_baru' => ['required', Rule::in(['ditinjau', 'perlu_perbaikan', 'perlu_diteruskan'])],
        ]);

        DB::transaction(function () use ($request, $laporan, $data) {
            // melempar ValidationException jika melanggar alur atau kewenangan
            $laporan->ubahStatus($data['status_baru'], $request->user());

            $laporan->tinjau()->create([
                'relawan_id' => $request->user()->id,
                'tgl_pemeriksaan' => now(),
                'hasil_pemeriksaan' => $data['hasil_pemeriksaan'],
                'catatan' => $data['catatan'],
            ]);
        });

        return redirect()->route('laporan.show', $laporan)->with('success', 'Hasil peninjauan tersimpan.');
    }
}