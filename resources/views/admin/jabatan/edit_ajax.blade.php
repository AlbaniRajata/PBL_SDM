<form action="{{ route('admin.jabatan.update_ajax', $jabatan->id_jabatan_kegiatan) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Edit Jabatan Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="jabatan_nama">Nama Jabatan</label>
                    <input type="text" class="form-control" id="jabatan_nama" name="jabatan_nama" value="{{ $jabatan->jabatan_nama }}" required>
                    <small id="error-jabatan_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="poin">Poin</label>
                    <input type="number" class="form-control" id="poin" name="poin" step="0.1" value="{{ $jabatan->poin }}" required>
                    <small id="error-poin" class="error-text form-text text-danger"></small>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#form-edit').validate({
            rules: {
                jabatan_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                poin: {
                    required: true,
                    number: true,
                    min: 0.5,
                    max: 2
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#jabatan-kegiatan-table').DataTable().ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'An error occurred. Please try again.'
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