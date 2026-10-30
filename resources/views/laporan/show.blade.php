<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan #{{ $laporan->id }}</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 p-3 rounded">{{ $errors->first() }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
                <dl class="grid grid-cols-3 gap-y-2 text-sm">
                    <dt class="font-medium">Status</dt>
                    <dd class="col-span-2"><span
                            class="px-2 py-1 rounded bg-gray-100">{{ $laporan->status_laporan }}</span></dd>
                    <dt class="font-medium">Pelapor</dt>
                    <dd class="col-span-2">{{ $laporan->pelapor->name }}</dd>
                    <dt class="font-medium">Waktu kejadian</dt>
                    <dd class="col-span-2">{{ $laporan->waktu_kejadian->format('d M Y H:i') }}</dd>
                    <dt class="font-medium">Tingkat kondisi</dt>
                    <dd class="col-span-2">{{ $laporan->tingkat_kondisi }}</dd>
                    <dt class="font-medium">Wilayah</dt>
                    <dd class="col-span-2">
                        {{ $laporan->lokasi->desaKelurahan->nama }},
                        {{ $laporan->lokasi->desaKelurahan->kecamatan->nama }},
                        {{ $laporan->lokasi->desaKelurahan->kecamatan->kotaKabupaten->nama }},
                        {{ $laporan->lokasi->desaKelurahan->kecamatan->kotaKabupaten->provinsi->nama }}
                    </dd>
                    <dt class="font-medium">Alamat</dt>
                    <dd class="col-span-2">{{ $laporan->lokasi->alamat_spesifik }}</dd>
                    <dt class="font-medium">Koordinat</dt>
                    <dd class="col-span-2">{{ $laporan->lokasi->latitude }}, {{ $laporan->lokasi->longitude }}</dd>
                    <dt class="font-medium">Kondisi dilihat</dt>
                    <dd class="col-span-2">{{ $laporan->deskripsi }}</dd>
                    <dt class="font-medium">Bukti</dt>
                    <dd class="col-span-2">
                        @if ($laporan->bukti_path)
                            <a class="text-indigo-600 underline" target="_blank"
                                href="{{ route('laporan.bukti', $laporan) }}">{{ $laporan->bukti_nama }}</a>
                        @else
                            -
                        @endif
                    </dd>
                </dl>

                @can('update', $laporan)
                    <a href="{{ route('laporan.edit', $laporan) }}"
                        class="inline-block px-3 py-2 bg-gray-800 text-white rounded text-sm">Perbaiki laporan</a>
                @endcan
            </div>

        </div>
    </div>
</x-app-layout>