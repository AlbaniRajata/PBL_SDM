<div id="modal-master" class="modal-dialog lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Kegiatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Data Kegiatan</h5>
                Berikut adalah detail dari data kegiatan
            </div>
            <table class="table table-sm table-bordered table-stripped">
                <tr>
                    <th class="text-right col-3">Nama Kegiatan : </th>
                    <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Jenis Kegiatan : </th>
                    <td class="col-9">{{ $kegiatan->jenis_kegiatan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tempat Kegiatan : </th>
                    <td class="col-9">{{ $kegiatan->tempat_kegiatan }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Mulai : </th>
                    <td class="col-9">{{ $kegiatan->tanggal_mulai }}</td>
                </tr>
                <tr>
                    <th class="text-right col-3">Tanggal Selesai : </th>
                    <td class="col-9">{{ $kegiatan->tanggal_selesai }}</td>
                </tr>
            </table>

            <div class="alert alert-info mt-3">
                <h5><i class="icon fas fa-info"></i> Data Agenda</h5>
                Berikut adalah agenda terkait kegiatan ini
            </div>
            <table class="table table-sm table-bordered table-stripped">
                <thead>
                    <tr>
                        <th class="text-center">Nama Agenda</th>
                        <th class="text-center">Dokumen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agendaAnggota as $a)
                        <tr>
                            <td class="text-center">{{ $a->nama_agenda }}</td>
                            
                            <td class="text-center">
                                @if ($a->file_path == null)
                                    <a href="{{ storage_path('app/public/' . $a->file_path) }}" 
                                       class="btn btn-sm btn-primary" 
                                       target="_blank">Unduh</a>
                                @else
                                    <span class="badge badge-warning">Belum Ada Dokumen</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    {{-- @if ($a->file_path != null)
                        <a href="{{ asset('storage/' . $a->file_path) }}" 
                        class="btn btn-sm btn-primary" 
                        target="_blank">Unduh</a>
                    @else
                        <span class="badge badge-warning">Belum Ada Dokumen</span>
                    @endif --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('css')
@endpush

@push('js')
<script>
    function updateFileName() {
        var input = document.getElementById('file');
        var fileName = input.files[0].name;
        var label = document.getElementById('file_label');
        label.textContent = fileName;
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function modalAction(url) {
    $.ajax({
        url: "{{ route('dosenPIC.agendaAnggota.listAgendaAnggota') }}",
        type: 'GET',
        success: function(response) {
            $('#myModal').html(response);
            $('#myModal').modal('show');
        },
        error: function(xhr) {
            console.log('hello')
            alert('Terjadi kesalahan saat mengambil data.');
        }
    });
}    
</script>
@endpush