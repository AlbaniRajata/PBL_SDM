<table class="table table-bordered table-button-closed" style="color=white">
    <thead>
        <tr>
            <th class="text-center">Nama Kegiatan</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Jenis Kegiatan</th>
            <th class="text-center">Jabatan</th>
            <th class="text-center">Poin</th>
        </tr>
    </thead>

    <tbody>
        @forelse($kegiatan as $item)
            <tr>
                <td class="text-center">{{ $item->nama_kegiatan }}</td>
                <td class="text-center">{{ $item->tanggal_acara }}</td>
                <td class="text-center">{{ $item->jenis_kegiatan }}</td>
                <td class="text-center">{{ $item->jabatan_nama }}</td>
                <td class="text-center">{{ $item->poin }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">Tidak ada kegiatan</td>
            </tr>
        @endforelse
    </tbody>
</table>

<style>
    .btn-close {
        color: white !important; /* Mengubah warna X menjadi putih */
    }
</style>