@php($lokasi = $laporan?->lokasi)
<div class="space-y-4">
    <div>
        <x-input-label for="desa_kelurahan_id" value="Desa/Kelurahan" />
        <select id="desa_kelurahan_id" name="desa_kelurahan_id" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">-- pilih wilayah --</option>
            @foreach ($desa as $d)
                <option value="{{ $d->id }}" @selected(old('desa_kelurahan_id', $lokasi?->desa_kelurahan_id) == $d->id)>
                    {{ $d->nama }} - {{ $d->kecamatan->nama }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('desa_kelurahan_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="alamat_spesifik" value="Alamat / patokan lokasi" />
        <x-text-input id="alamat_spesifik" name="alamat_spesifik" type="text" class="mt-1 block w-full"
            :value="old('alamat_spesifik', $lokasi?->alamat_spesifik)" required />
        <x-input-error :messages="$errors->get('alamat_spesifik')" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <x-input-label for="latitude" value="Latitude" />
            <x-text-input id="latitude" name="latitude" type="number" step="any" class="mt-1 block w-full"
                :value="old('latitude', $lokasi?->latitude)" required />
            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="longitude" value="Longitude" />
            <x-text-input id="longitude" name="longitude" type="number" step="any" class="mt-1 block w-full"
                :value="old('longitude', $lokasi?->longitude)" required />
            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="tingkat_kondisi" value="Tingkat kondisi" />
        <select id="tingkat_kondisi" name="tingkat_kondisi" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @foreach (['ringan' => 'Ringan (asap tipis / api kecil)', 'sedang' => 'Sedang', 'berat' => 'Berat (api besar / asap tebal)'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(old('tingkat_kondisi', $laporan?->tingkat_kondisi) === $nilai)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tingkat_kondisi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="waktu_kejadian" value="Waktu kejadian" />
        <x-text-input id="waktu_kejadian" name="waktu_kejadian" type="datetime-local" class="mt-1 block w-full"
            :value="old('waktu_kejadian', $laporan?->waktu_kejadian?->format('Y-m-d\TH:i'))" required />
        <x-input-error :messages="$errors->get('waktu_kejadian')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="deskripsi" value="Kondisi yang dilihat" />
        <textarea id="deskripsi" name="deskripsi" rows="4" required
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('deskripsi', $laporan?->deskripsi) }}</textarea>
        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
    </div>

    <x-primary-button>Kirim</x-primary-button>
</div>