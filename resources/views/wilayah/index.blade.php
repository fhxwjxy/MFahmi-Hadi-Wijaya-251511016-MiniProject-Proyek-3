<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Wilayah</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>@endif
            @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded">{{ $errors->first() }}</div>@endif

            <form method="POST" action="{{ route('wilayah.store') }}"
                class="bg-white shadow sm:rounded-lg p-4 flex gap-2">
                @csrf
                <select name="kecamatan_id" required class="border-gray-300 rounded-md">
                    @foreach ($kecamatan as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>@endforeach
                </select>
                <x-text-input name="nama" placeholder="Nama desa/kelurahan" required />
                <x-primary-button>Tambah</x-primary-button>
            </form>

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full text-sm">
                    @foreach ($desa as $d)
                        <tr class="border-t">
                            <td class="p-3">{{ $d->nama }}</td>
                            <td class="p-3">{{ $d->kecamatan->nama }}</td>
                            <td class="p-3">{{ $d->kecamatan->kotaKabupaten->nama }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>