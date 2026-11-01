<div class="bg-white shadow sm:rounded-lg p-4">
    <label for="area" class="font-medium text-sm">Cek laporan aktif per wilayah</label>
    <select id="area" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- pilih desa/kelurahan --</option>
        @foreach ($desa as $d)
            <option value="{{ $d->id }}">{{ $d->nama }} - {{ $d->kecamatan->nama }}</option>
        @endforeach
    </select>
    <div id="area-info" class="mt-3 text-sm text-gray-700"></div>
</div>

<script>
    const baseUrl = "{{ url('/wilayah') }}";

    document.getElementById('area').addEventListener('change', async (e) => {
        const box = document.getElementById('area-info');
        box.textContent = '';
        if (!e.target.value) return;

        box.textContent = 'Memuat...';
        try {
            const res = await fetch(`${baseUrl}/${e.target.value}/laporan-aktif`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const data = await res.json();

            box.textContent = '';
            const p = document.createElement('p');
            p.textContent = `${data.wilayah}: ${data.jumlah_aktif} laporan aktif`;
            box.appendChild(p);

            const ul = document.createElement('ul');
            ul.className = 'list-disc ml-5';
            data.laporan.forEach((l) => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = l.url;
                a.className = 'text-indigo-600 underline';
                a.textContent = `#${l.id} (${l.status}, ${l.kondisi})`;
                li.appendChild(a);
                ul.appendChild(li);
            });
            box.appendChild(ul);
        } catch (err) {
            box.textContent = 'Gagal memuat data wilayah.';
        }
    });
</script>