<form action="{{ route('admin.jabatan.store_ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Jabatan Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-tambah">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jabatan_nama">Nama Jabatan</label>
                        <input type="text" class="form-control" id="jabatan_nama" name="jabatan_nama" required>
                        <small id="error-jabatan_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="poin">Poin</label>
                        <input type="number" class="form-control" id="poin" name="poin" step="0.1" required>
                        <small id="error-poin" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>

<script>
    function modalAction(url) {
        $.get(url, function(data) {
            $('#modal-master .modal-content').html(data);
            $('#modal-master').modal('show');
        });
    }

    document.getElementById('poin').addEventListener('input', function (e) {
        this.value = this.value.replace(',', '.');
    });

    $(document).ready(function() {
        $('#form-tambah').validate({
            submitHandler: function(form) {
                var poinInput = document.getElementById('poin');
                poinInput.value = poinInput.value.replace(',', '.');

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            table.ajax.reload();
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
            }
        });
    });
</script>