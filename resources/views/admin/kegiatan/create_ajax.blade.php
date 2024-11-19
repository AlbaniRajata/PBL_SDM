<form action="{{ route('admin.storeAjax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kegiatan</label>
                    <input value="" type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kegiatan</label>
                    <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-control" required>
                        <option value="">- Pilih Jenis Kegiatan -</option>
                        <option value="Kegiatan JTI">Kegiatan JTI</option>
                        <option value="Kegiatan Non-JTI">Kegiatan Non-JTI</option>
                    </select>
                    <small id="error-jenis_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" required></textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Acara</label>
                    <input value="" type="datetime-local" name="tanggal_acara" id="tanggal_acara" class="form-control" required>
                    <small id="error-tanggal_acara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input value="" type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="form-control" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input value="" type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="form-control" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div id="jabatan-anggota-container">
                    <div class="form-row jabatan-anggota-item">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jabatan</label>
                                <select name="jabatan_id[]" class="form-control jabatan-select" required>
                                    <option value="">- Pilih Jabatan -</option>
                                    @foreach($jabatan as $j)
                                        <option value="{{ $j->id_jabatan_kegiatan }}">{{ $j->jabatan_nama }}</option>
                                    @endforeach
                                </select>
                                <small id="error-jabatan_id" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Anggota</label>
                                <select name="anggota_id[]" class="form-control anggota-select" required>
                                    <option value="">- Pilih Anggota -</option>
                                    @foreach($anggota as $a)
                                        <option value="{{ $a->id_user }}">{{ $a->nama }}</option>
                                    @endforeach
                                </select>
                                <small id="error-anggota_id" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        function tambahInputJabatanAnggota() {
            let newItem = $('.jabatan-anggota-item:first').clone();
            newItem.find('select').val('');
            newItem.find('.error-text').text('');
            $('#jabatan-anggota-container').append(newItem);
        }

        $(document).on('change', '.anggota-select', function() {
            if ($(this).val()) {
                // Check if this is the last input
                if ($(this).closest('.jabatan-anggota-item').is(':last-child')) {
                    tambahInputJabatanAnggota();
                }
            }
        });

        $("#form-tambah").validate({
            rules: {
                nama_kegiatan: { required: true, minlength: 3, maxlength: 255 },
                jenis_kegiatan: { required: true },
                deskripsi: { required: true, minlength: 3 },
                tanggal_acara: { required: true },
                tanggal_mulai: { required: true },
                tanggal_selesai: { required: true },
                tempat_acara: { required: true, minlength: 3, maxlength: 255 },
                'jabatan_id[]': { required: true },
                'anggota_id[]': { required: true }
            },
            submitHandler: function(form) {
                let formData = $(form).serializeArray();
                let filteredData = formData.filter(item => {
                    return !(item.name === 'jabatan_id[]' && item.value === '') && !(item.name === 'anggota_id[]' && item.value === '');
                });
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $.param(filteredData),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(function() {
                                dataKegiatan.ajax.reload();
                            });
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