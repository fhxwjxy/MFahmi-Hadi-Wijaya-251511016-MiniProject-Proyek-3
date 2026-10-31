<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pihak Tujuan</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>@endif
            @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-3 rounded">{{ $errors->first() }}</div>@endif

            <form method="POST" action="{{ route('pihak-tujuan.store') }}"
                class="bg-white shadow sm:rounded-lg p-4 flex gap-2">
                @csrf
                <x-text-input name="nama_instansi" placeholder="Nama instansi" required />
                <x-text-input name="kontak" placeholder="Kontak" />
                <x-primary-button>Tambah</x-primary-button>
            </form>

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full text-sm">
                    @foreach ($items as $i)
                        <tr class="border-t">
                            <td class="p-3">{{ $i->nama_instansi }}</td>
                            <td class="p-3">{{ $i->kontak }}</td>
                            <td class="p-3 text-right">
                                <form method="POST" action="{{ route('pihak-tujuan.destroy', $i) }}"
                                    onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</x-app-layout>