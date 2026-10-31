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

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Hasil peninjauan</h3>
                @forelse ($laporan->tinjau->sortByDesc('tgl_pemeriksaan') as $t)
                    <div class="border-t py-2 text-sm">
                        <div class="text-gray-500">{{ $t->tgl_pemeriksaan->format('d M Y H:i') }} oleh
                            {{ $t->relawan->name }} ({{ $t->hasil_pemeriksaan }})
                        </div>
                        <div>{{ $t->catatan }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada peninjauan.</p>
                @endforelse
            </div>

            @if (auth()->user()->punyaRole('relawan'))
            @php($opsi = array_values(array_intersect(App\Models\Laporan::TRANSISI[$laporan->status_laporan] ?? [], ['ditinjau', 'perlu_perbaikan', 'perlu_diteruskan'])))
            @if ($opsi)
                <form method="POST" action="{{ route('laporan.tinjau', $laporan) }}"
                    class="bg-white shadow sm:rounded-lg p-6 space-y-3">
                    @csrf
                    <h3 class="font-semibold">Tinjau laporan</h3>
                    <select name="hasil_pemeriksaan" required class="block w-full border-gray-300 rounded-md">
                        <option value="lengkap">Informasi lengkap dan wajar</option>
                        <option value="kurang_lengkap">Informasi kurang lengkap</option>
                        <option value="tidak_wajar">Informasi tidak wajar</option>
                    </select>
                    <select name="status_baru" required class="block w-full border-gray-300 rounded-md">
                        @foreach ($opsi as $o)
                            <option value="{{ $o }}">{{ $o }}</option>
                        @endforeach
                    </select>
                    <textarea name="catatan" rows="3" required placeholder="Catatan peninjauan"
                        class="block w-full border-gray-300 rounded-md"></textarea>
                    <x-primary-button>Simpan peninjauan</x-primary-button>
                </form>
            @endif
            @endif

            @if ($terkait->isNotEmpty())
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="font-semibold mb-2">Laporan lain di desa/kelurahan yang sama</h3>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($terkait as $t)
                            <li><a class="text-indigo-600 underline" href="{{ route('laporan.show', $t) }}">#{{ $t->id }}</a>
                                ({{ $t->status_laporan }}, {{ $t->tingkat_kondisi }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="font-semibold mb-2">Penerusan ke pihak terkait</h3>
                @forelse ($laporan->penerusan as $p)
                    <div class="border-t py-2 text-sm">{{ $p->tgl_penerusan->format('d M Y H:i') }} ke
                        {{ $p->pihakTujuan->nama_instansi }} ({{ $p->status_penerusan }}) {{ $p->catatan }}</div>
                @empty
                    <p class="text-sm text-gray-500">Belum ada penerusan.</p>
                @endforelse

                @if (auth()->user()->punyaRole('koordinator'))
                    @if (in_array($laporan->status_laporan, ['perlu_diteruskan', 'diteruskan']))
                        <form method="POST" action="{{ route('laporan.penerusan', $laporan) }}" class="mt-3 space-y-2">
                            @csrf
                            <select name="pihak_tujuan_id" required class="block w-full border-gray-300 rounded-md">
                                @foreach ($pihak as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_instansi }}</option>
                                @endforeach
                            </select>
                            <x-text-input name="catatan" class="block w-full" placeholder="Catatan penerusan (opsional)" />
                            <x-primary-button>Catat penerusan</x-primary-button>
                        </form>
                    @endif

                    @if (in_array('selesai', App\Models\Laporan::TRANSISI[$laporan->status_laporan] ?? []))
                        <form method="POST" action="{{ route('laporan.tutup', $laporan) }}" class="mt-3">
                            @csrf
                            @method('PATCH')
                            <button class="px-3 py-2 bg-green-700 text-white rounded text-sm">Tandai selesai</button>
                        </form>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>