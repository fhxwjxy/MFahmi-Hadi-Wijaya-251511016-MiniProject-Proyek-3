<?php
namespace App\Http\Controllers;

use App\Models\DesaKelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        return view('wilayah.index', [
            'desa' => DesaKelurahan::with('kecamatan.kotaKabupaten')->orderBy('nama')->get(),
            'kecamatan' => Kecamatan::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kecamatan_id' => ['required', 'exists:kecamatan,id'],
            'nama' => ['required', 'string', 'max:100'],
        ]);

        DesaKelurahan::create($data);

        return back()->with('success', 'Desa/kelurahan ditambahkan.');
    }
}