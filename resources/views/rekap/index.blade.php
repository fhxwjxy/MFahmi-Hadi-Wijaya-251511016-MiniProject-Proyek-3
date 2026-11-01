<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekap Laporan</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <form method="GET" action="{{ route('rekap.index') }}" class="bg-white shadow sm:rounded-lg p-4 flex gap-2">
                <select name="kecamatan" class="border-gray-300 rounded-md">
                    <option value="">Semua kecamatan</option>
                    @foreach ($kecamatan as $k)
                        <option value="{{ $k->id }}" @selected((string) request('kecamatan') === (string) $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
                <x-primary-button>Terapkan</x-primary-button>
            </form>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($ringkasan as $label => $nilai)
                    <div class="bg-white shadow sm:rounded-lg p-4">
                        <div class="text-sm text-gray-500">{{ $label }}</div>
                        <div class="text-2xl font-semibold">{{ $nilai }}</div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="p-3">Kecamatan</th>
                            <th class="p-3">Total</th>
                            @foreach ($statuses as $s)
                            <th class="p-3">{{ $s }}</th>@endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tabel as $row)
                            <tr class="border-t">
                                <td class="p-3">{{ $row->kecamatan }}</td>
                                <td class="p-3">{{ $row->total }}</td>
                                @foreach ($statuses as $s)
                                <td class="p-3">{{ $row->$s }}</td>@endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($statuses) + 2 }}" class="p-4 text-center text-gray-500">Belum ada
                                    data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>