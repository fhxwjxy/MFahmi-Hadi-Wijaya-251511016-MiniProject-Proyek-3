<?php
namespace App\Http\Controllers;

use App\Models\PihakTujuan;
use Illuminate\Http\Request;

class PihakTujuanController extends Controller
{
    public function index()
    {
        return view('pihak-tujuan.index', ['items' => PihakTujuan::orderBy('nama_instansi')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_instansi' => ['required', 'string', 'max:100'],
            'kontak' => ['nullable', 'string', 'max:100'],
        ]);

        PihakTujuan::create($data);

        return back()->with('success', 'Pihak tujuan ditambahkan.');
    }

    public function destroy(PihakTujuan $pihakTujuan)
    {
        if ($pihakTujuan->penerusan()->exists()) {
            return back()->withErrors(['hapus' => 'Tidak dapat dihapus karena sudah dipakai pada penerusan laporan.']);
        }
        $pihakTujuan->delete();
        return back()->with('success', 'Pihak tujuan dihapus.');
    }
}