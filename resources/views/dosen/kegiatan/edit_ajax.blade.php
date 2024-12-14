<form action="{{ url('/dosen/kegiatan/'.$kegiatan->id_kegiatan.'/update_ajax', ) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Edit Kegiatan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan</label>
                    <input type="text" class="form-control" id="nama_kegiatan" name="nama_kegiatan" value="{{ $kegiatan->nama_kegiatan }}" required>
                    <small id="error-nama_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="jenis_kegiatan">Jenis Kegiatan</label>
                    <input type="text" class="form-control" id="jenis_kegiatan" name="jenis_kegiatan" value="{{ $kegiatan->jenis_kegiatan }}" required>
                    <small id="error-jenis_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="deskripsi_kegiatan">Deskripsi Kegiatan</label>
                    <textarea class="form-control" id="deskripsi_kegiatan" name="deskripsi_kegiatan" required>{{ $kegiatan->deskripsi_kegiatan }}</textarea>
                    <small id="error-deskripsi_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal Mulai</label>
                    <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->format('Y-m-d\TH:i') }}" required>
                    <small id="error-tanggal_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="tanggal_selesai">Tanggal Selesai</label>
                    <input type="datetime-local" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->format('Y-m-d\TH:i') }}" required>
                    <small id="error-tanggal_selesai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="tanggal_acara">Tanggal Acara</label>
                    <input type="datetime-local" class="form-control" id="tanggal_acara" name="tanggal_acara" value="{{ \Carbon\Carbon::parse($kegiatan->tanggal_acara)->format('Y-m-d\TH:i') }}" required>
                    <small id="error-tanggal_acara" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="tempat_kegiatan">Tempat Kegiatan</label>
                    <input type="text" class="form-control" id="tempat_kegiatan" name="tempat_kegiatan" value="{{ $kegiatan->tempat_kegiatan }}" required>
                    <small id="error-tempat_kegiatan" class="error-text form-text text-danger"></small>
                </div>
                <div id="jabatan-anggota-container">
                    @foreach ($anggota_kegiatan as $ag)
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select name="jabatan_id[]" class="form-control jabatan-select" required>
                                <option value="">- Pilih Jabatan -</option>
                                @foreach($jabatan as $j)
                                    <option value="{{ $j->id_jabatan_kegiatan }}" {{ $ag->id_jabatan_kegiatan == $j->id_jabatan_kegiatan ? 'selected' : '' }}>{{ $j->jabatan_nama }}</option>
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
                                    <option value="{{ $a->id_user }}" {{ $ag->id_user == $a->id_user ? 'selected' : '' }}>{{ $a->nama }}</option>
                                @endforeach
                            </select>
                            <small id="error-anggota_id" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                    @endforeach
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
                nama_kegiatan: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                jenis_kegiatan: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
                deskripsi_kegiatan: {
                    required: true,
                    minlength: 3
                },
                tanggal_mulai: {
                    required: true,
                    date: true
                },
                tanggal_selesai: {
                    required: true,
                    date: true
                },
                tempat_kegiatan: {
                    required: true,
                    minlength: 3,
                    maxlength: 255
                },
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
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            $('#kegiatan-table').DataTable().ajax.reload();
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