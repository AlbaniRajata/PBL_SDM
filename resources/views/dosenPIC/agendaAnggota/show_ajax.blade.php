@empty($kegiatan)
    <div id="modal-master" class="modal-dialog lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/agendaAnggota') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
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
                                <td class="text-center">{{ $a->dokumen }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endempty

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



    $(document).ready(function() {
        var dataKegiatan = $('#table_kegiatan').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('dosenPIC.agendaAnggota.listAgendaAnggota') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    // Add any additional parameters here if needed
                }
            },
            columns: [
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'jenis_kegiatan', name: 'jenis_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'tempat_kegiatan', name: 'tempat_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_mulai', name: 'tanggal_mulai', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_selesai', name: 'tanggal_selesai', className: "text-center", orderable: true, searchable: true },
                { data: 'anggota', name: 'pic', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });
    });
</script>
@endpush
