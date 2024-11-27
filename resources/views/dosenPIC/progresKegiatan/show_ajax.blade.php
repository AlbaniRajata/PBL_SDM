@empty($progresKegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
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
                <a href="{{ url('/progresKegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Progress Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Data Progres Kegiatan</h5>
                    Berikut adalah detail dari data progres kegiatan
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID :</th>
                        <td class="col-9">{{ $progresKegiatan->id_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Kegiatan :</th>
                        <td class="col-9">{{ $progresKegiatan->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Presentase:</th>
                        <td class="col-9">{{ $progresKegiatan->progress }}%</td>
                    </tr>
                </table>
                <div class="text-right">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endempty

@push('css')
@endpush

@push('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function() {
        var dataProgres = $('#progress-kegiatan-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('dosenPIC.progresKegiatan.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    // Add any additional parameters here if needed
                }
            },
            columns: [
                { data: 'id_kegiatan', name: 'id_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'nama_kegiatan', name: 'nama_kegiatan', className: "text-center", orderable: true, searchable: true },
                { data: 'progress', name: 'progress', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });

        // Load kegiatan details in modal
        $('#progress-kegiatan-table').on('click', '.view-progresKegiatan', function() {
            var progresKegiatanId = $(this).data('id');
            modalAction("{{ route('progresKegiatan.detail', '') }}/" + progresKegiatanId);
        });

        // Load kegiatan edit form in modal
        $('#progress-kegiatan-table').on('click', '.edit-progresKegiatan', function() {
            var progresKegiatanId = $(this).data('id');
            modalAction("{{ route('progresKegiatan.edit', '') }}/" + progresKegiatanId);
        });
    });
</script>
@endpush