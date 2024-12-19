@if($details->count() > 0)
    <div class="table-responsive">
        @if($year)
            <div class="alert alert-info">
                Menampilkan kegiatan tahun {{ $year }}
            </div>
        @endif
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nama Kegiatan</th>
                    <th>Tanggal Acara</th>
                    <th>Jabatan</th>
                    <th>Poin</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $detail)
                    <tr>
                        <td>{{ $detail->nama_kegiatan }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_acara)->format('d M Y') }}</td>
                        <td>{{ $detail->jabatan_nama }}</td>
                        <td>{{ $detail->poin }}</td>
                    </tr>
                @endforeach
                <tr class="table-info font-weight-bold">
                    <td colspan="3" class="text-right">Total Poin:</td>
                    <td>{{ $details->sum('poin') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info text-center">
        @if($year)
            Tidak ada kegiatan ditemukan untuk tahun {{ $year }}.
        @else
            Tidak ada kegiatan yang ditemukan.
        @endif
    </div>
@endif