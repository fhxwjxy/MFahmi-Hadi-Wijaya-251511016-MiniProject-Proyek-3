<?php
namespace App\Http\Controllers;

use App\Http\Requests\LaporanRequest;
use App\Models\DesaKelurahan;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\PihakTujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Laporan::with('lokasi.desaKelurahan')->latest();

        if ($request->user()->punyaRole('pelapor')) {
            $query->where('pelapor_id', $request->user()->id);
        }

        return view('laporan.index', [
            'laporans' => $query->paginate(10),
            'desa' => DesaKelurahan::with('kecamatan')->orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('laporan.create', [
            'desa' => DesaKelurahan::with('kecamatan')->orderBy('nama')->get(),
        ]);
    }

    public function show(Laporan $laporan)
    {
        Gate::authorize('view', $laporan);

        $laporan->load('pelapor', 'lokasi.desaKelurahan.kecamatan.kotaKabupaten.provinsi', 'tinjau.relawan', 'penerusan.pihakTujuan');

        $user = auth()->user();
        $terkait = collect();
        if ($user->punyaRole('relawan', 'koordinator')) {
            $terkait = Laporan::with('lokasi')
                ->where('id', '!=', $laporan->id)
                ->whereHas('lokasi', fn($q) => $q->where('desa_kelurahan_id', $laporan->lokasi->desa_kelurahan_id))
                ->latest()->limit(10)->get();
        }

        $pihak = $user->punyaRole('koordinator') ? PihakTujuan::orderBy('nama_instansi')->get() : collect();

        return view('laporan.show', compact('laporan', 'terkait', 'pihak'));
    }

    public function edit(Laporan $laporan)
    {
        Gate::authorize('update', $laporan);
        return view('laporan.edit', [
            'laporan' => $laporan->load('lokasi'),
            'desa' => DesaKelurahan::with('kecamatan')->orderBy('nama')->get(),
        ]);
    }

    public function update(LaporanRequest $request, Laporan $laporan)
    {
        Gate::authorize('update', $laporan);

        $data = $request->validated();
        $lama = null;

        DB::transaction(function () use ($request, $laporan, $data, &$lama) {
            $laporan->lokasi->update(Arr::only($data, [
                'desa_kelurahan_id',
                'alamat_spesifik',
                'latitude',
                'longitude',
            ]));

            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $lama = $laporan->bukti_path;
                $laporan->bukti_path = $file->store('bukti');
                $laporan->bukti_nama = $file->getClientOriginalName();
            }

            $laporan->fill(Arr::only($data, ['deskripsi', 'tingkat_kondisi', 'waktu_kejadian']));

            if ($laporan->status_laporan === 'perlu_perbaikan') {
                $laporan->status_laporan = 'baru'; // dikirim ulang setelah diperbaiki
            }

            $laporan->save();
        });

        if ($lama) {
            Storage::delete($lama);
        }

        return redirect()->route('laporan.show', $laporan)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function bukti(Laporan $laporan)
    {
        Gate::authorize('view', $laporan);

        abort_unless($laporan->bukti_path && Storage::exists($laporan->bukti_path), 404);

        return Storage::response($laporan->bukti_path, $laporan->bukti_nama);
    }
    public function store(LaporanRequest $request)
    {
        $data = $request->validated();
        $path = $nama = null;

        if ($request->validated()) {
            $file = $request->file('bukti');
            $path = $file->store('bukti');
            $nama = $file->getClientOriginalName();
        }

        try {
            DB::transaction(function () use ($data, $request, $path, $nama) {
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
                    'bukti_path' => $path,
                    'bukti_nama' => $nama,
                ]);
            });
        } catch (\Throwable $e) {
            if ($path) {
                Storage::delete($path);
            }
            throw $e;
        }
        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim.');
    }
}