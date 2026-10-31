<?php

namespace App\Http\Controllers;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenerusanLaporanController extends Controller
{
    public function store(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'pihak_tujuan_id' => ['required', 'exists:pihak_tujuan,id'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($laporan->penerusan()->where('pihak_tujuan_id', $data['pihak_tujuan_id'])->exists()) {
            throw ValidationException::withMessages([
                'pihak_tujuan_id' => 'Laporan ini sudah dicatat diteruskan ke pihak tersebut.',
            ]);
        }

        DB::transaction(function () use ($request, $laporan, $data) {
            if ($laporan->status_laporan === 'perlu_diteruskan') {
                $laporan->ubahStatus('diteruskan', $request->user());
            } elseif ($laporan->status_laporan !== 'diteruskan') {
                throw ValidationException::withMessages([
                    'status_laporan' => 'Penerusan hanya untuk laporan berstatus perlu_diteruskan atau diteruskan.',
                ]);
            }

            $laporan->penerusan()->create([
                'koordinator_id' => $request->user()->id,
                'pihak_tujuan_id' => $data['pihak_tujuan_id'],
                'tgl_penerusan' => now(),
                'status_penerusan' => 'tercatat',
                'catatan' => $data['catatan'] ?? null,
            ]);
        });

        return redirect()->route('laporan.show', $laporan)->with('success', 'Penerusan berhasil dicatat.');
    }
}
