<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Akun Relawan</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>@endif
            @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded">{{ $errors->first() }}</div>@endif

            <form method="POST" action="{{ route('pengguna.store') }}"
                class="bg-white shadow sm:rounded-lg p-4 flex gap-2">
                @csrf
                <x-text-input name="name" placeholder="Nama" required />
                <x-text-input name="email" type="email" placeholder="Email" required />
                <x-text-input name="password" type="password" placeholder="Password" required />
                <x-primary-button>Buat</x-primary-button>
            </form>

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full text-sm">
                    @foreach ($relawan as $r)
                        <tr class="border-t">
                            <td class="p-3">{{ $r->name }}</td>
                            <td class="p-3">{{ $r->email }}</td>
                            <td class="p-3">{{ $r->status_akun }}</td>
                            <td class="p-3 text-right">
                                <form method="POST" action="{{ route('pengguna.status', $r) }}">
                                    @csrf @method('PATCH')
                                    <button
                                        class="text-indigo-600 underline">{{ $r->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>