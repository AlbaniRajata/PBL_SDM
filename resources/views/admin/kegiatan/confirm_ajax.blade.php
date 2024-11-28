@empty($kegiatan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kegiatan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/admin/kegiatan/' . $kegiatan->id_kegiatan . '/confirm_ajax') }}" method="POST" id="form-confirm">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Kegiatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                        Apakah Anda ingin mengonfirmasi kegiatan seperti di bawah ini?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Nama Kegiatan :</th>
                            <td class="col-9">{{ $kegiatan->nama_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Deskripsi :</th>
                            <td class="col-9">{{ $kegiatan->deskripsi_kegiatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Mulai :</th>
                            <td class="col-9">{{ $kegiatan->tanggal_mulai }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Tanggal Selesai :</th>
                            <td class="col-9">{{ $kegiatan->tanggal_selesai }}</td>
                        </tr>
                    </table>
                    <div class="text-right">
                        <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-confirm").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataKegiatan.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty