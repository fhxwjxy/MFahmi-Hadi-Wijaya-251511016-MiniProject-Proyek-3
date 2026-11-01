<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>
            @endif

            @include('laporan._area-info')

            <form method="GET" action="{{ route('laporan.index') }}"
                class="bg-white shadow sm:rounded-lg p-4 grid grid-cols-1 md:grid-cols-5 gap-2">
                <x-text-input name="q" :value="request('q')" placeholder="Cari deskripsi / alamat" />
                <select name="status" class="border-gray-300 rounded-md">
                    <option value="">Semua status</option>
                    @foreach (App\Models\Laporan::STATUS as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
                <select name="kondisi" class="border-gray-300 rounded-md">
                    <option value="">Semua kondisi</option>
                    @foreach (['ringan', 'sedang', 'berat'] as $k)
                        <option value="{{ $k }}" @selected(request('kondisi') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
                <select name="kecamatan" class="border-gray-300 rounded-md">
                    <option value="">Semua kecamatan</option>
                    @foreach ($kecamatan as $k)
                        <option value="{{ $k->id }}" @selected((string) request('kecamatan') === (string) $k->id)>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <x-primary-button>Terapkan</x-primary-button>
                    <a href="{{ route('laporan.index') }}" class="px-3 py-2 border rounded text-sm">Reset</a>
                </div>
            </form>

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="p-3">Waktu kejadian</th>
                            <th class="p-3">Pelapor</th>
                            <th class="p-3">Wilayah</th>
                            <th class="p-3">Kondisi</th>
                            <th class="p-3">Status</th>
                            <th class="p-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporans as $l)
                            <tr class="border-t">
                                <td class="p-3">{{ $l->waktu_kejadian->format('d M Y H:i') }}</td>
                                <td class="p-3">{{ $l->pelapor->name }}</td>
                                <td class="p-3">{{ $l->lokasi->desaKelurahan->nama }},
                                    {{ $l->lokasi->desaKelurahan->kecamatan->nama }}
                                </td>
                                <td class="p-3">{{ $l->tingkat_kondisi }}</td>
                                <td class="p-3">{{ $l->status_laporan }}</td>
                                <td class="p-3"><a class="text-indigo-600 underline"
                                        href="{{ route('laporan.show', $l) }}">Detail</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada laporan yang sesuai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $laporans->links() }}
        </div>
    </div>
</x-app-layout>