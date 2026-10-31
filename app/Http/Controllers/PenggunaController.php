<?php
namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $relawan = User::whereHas('role', fn($q) => $q->where('nama_role', 'relawan'))->orderBy('name')->get();

        return view('pengguna.index', compact('relawan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => Role::where('nama_role', 'relawan')->value('id'),
            'status_akun' => 'aktif',
        ]);

        return back()->with('success', 'Akun relawan dibuat.');
    }

    public function toggleStatus(User $user)
    {
        abort_unless($user->punyaRole('relawan'), 403);
        $user->update(['status_akun' => $user->isAktif() ? 'nonaktif' : 'aktif']);
        return back()->with('success', 'Status akun diperbarui.');
    }
}