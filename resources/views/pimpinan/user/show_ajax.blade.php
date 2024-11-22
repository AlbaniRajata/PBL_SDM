@empty($user)
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
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Data User</h5>
                    Berikut adalah detail dari data user
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID :</th>
                        <td class="col-9">{{ $user->id_user }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username :</th>
                        <td class="col-9">{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap:</th>
                        <td class="col-9">{{ $user->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal Lahir :</th>
                        <td class="col-9">{{ $user->tanggal_lahir->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email :</th>
                        <td class="col-9">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIP :</th>
                        <td class="col-9">{{ $user->NIP }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level :</th>
                        <td class="col-9">{{ $user->level }}</td>
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
        var dataUser = $('#table_user').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ route('pimpinan.user.list') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: function (d) {
                    // Add any additional parameters here if needed
                }
            },
            columns: [
                { data: 'id_user', name: 'id_user', className: "text-center", orderable: true, searchable: true },
                { data: 'username', name: 'username', className: "text-center", orderable: true, searchable: true },
                { data: 'nama', name: 'nama', className: "text-center", orderable: true, searchable: true },
                { data: 'tanggal_lahir', name: 'tanggal_lahir', className: "text-center", orderable: true, searchable: true },
                { data: 'email', name: 'email', className: "text-center", orderable: true, searchable: true },
                { data: 'NIP', name: 'NIP', className: "text-center", orderable: true, searchable: true },
                { data: 'level', name: 'level', className: "text-center", orderable: true, searchable: true },
                { data: 'aksi', name: 'aksi', className: "text-center", orderable: false, searchable: false }
            ],
        });

        // Load user details in modal
        $('#table_user').on('click', '.view-user', function() {
            var userId = $(this).data('id');
            modalAction("{{ route('pimpinan.user.show_ajax', '') }}/" + userId);
        });
    });
</script>
@endpush