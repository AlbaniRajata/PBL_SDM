<!-- Form untuk penginputan agenda -->
<form action="{{ route('agenda.store') }}" method="POST" id="form-tambah-agenda">
    @csrf

    <input type="hidden" name="id_agenda" value="{{ $id_agenda }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Agenda Anggota</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Kegiatan -->
                <h4 class="text-center font-weight-bold mb-4">{{ $nama_kegiatan }}</h4>

                <!-- Container Input Agenda untuk Anggota -->
                <div id="agenda-anggota-container">
                    @foreach ($anggota as $index => $a)
                        <div class="agenda-anggota-item">
                        <h5>Agenda untuk {{ $a->user->nama }}</h5> <!-- Nama anggota dari tabel t_user -->
                            <input type="hidden" name="id_anggota[]" value="{{ $a->id_anggota }}"> <!-- ID anggota -->
                            <div class="form-group">
                                <label>Nama Agenda</label>
                                <input type="text" name="agenda[{{ $index }}]" class="form-control" placeholder="Masukkan nama agenda" required>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>

                <!-- Tombol Aksi -->
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
        $("#form-tambah-agenda").validate({
            rules: {
                'agenda[][nama]': { required: true, minlength: 3, maxlength: 255 },
            },
            submitHandler: function(form) {
                let formData = $(form).serializeArray();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(function() {
                                location.reload(); // Reload data jika perlu
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan : Agenda sudah diset',
                                text: response.message
                            });
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menyimpan data!'
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
