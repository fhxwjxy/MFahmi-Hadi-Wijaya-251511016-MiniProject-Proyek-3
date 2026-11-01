<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @include('laporan._area-info')

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="p-3">Waktu kejadian</th>
                            <th class="p-3">Wilayah</th>
                            <th class="p-3">Kondisi</th>
                            <th class="p-3">Status</th>

                            {{-- Tambahkan kolom Detail di sini --}}
                            <th class="p-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($laporans as $l)
                            <tr class="border-t">
                                <td class="p-3">
                                    {{ $l->waktu_kejadian->format('d M Y H:i') }}
                                </td>

                                <td class="p-3">
                                    {{ $l->lokasi->desaKelurahan->nama }}
                                </td>

                                <td class="p-3">
                                    {{ $l->tingkat_kondisi }}
                                </td>

                                <td class="p-3">
                                    {{ $l->status_laporan }}
                                </td>

                                {{-- Tambahkan tautan Detail di sini --}}
                                <td class="p-3">
                                    <a class="text-indigo-600 underline" href="{{ route('laporan.show', $l) }}">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-gray-500">
                                    Belum ada laporan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $laporans->links() }}
        </div>
    </div>
</x-app-layout>