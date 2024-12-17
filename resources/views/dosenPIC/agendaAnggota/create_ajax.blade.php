<form action="{{ route('agenda.store') }}" method="POST" id="form-tambah-agenda">
    @csrf

    <input type="hidden" name="id_agenda" value="{{ $id_agenda }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">{{ $agenda_sudah_ada ? 'Edit' : 'Tambah' }} Agenda Anggota</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nama Kegiatan -->
                <h4 class="text-center font-weight-bold mb-4">{{ $nama_kegiatan }}</h4>

                <div id="agenda-anggota-container">
                    @foreach ($anggota as $index => $a)
                        <div class="agenda-anggota-item" data-index="{{ $index }}">
                            <h5>Agenda untuk {{ $a->user->nama }}</h5>
                            <input type="hidden" name="id_anggota[]" value="{{ $a->id_anggota }}">

                            <!-- Wrapper untuk beberapa agenda -->
                            <div class="agenda-list">
                                @if ($a->agenda_sudah_dibuat)
                                    @foreach ($a->agenda_detail as $agenda)
                                        <div class="form-group agenda-item">
                                            <input type="text" 
                                                name="agenda[{{ $index }}][]" 
                                                class="form-control" 
                                                value="{{ $agenda }}" 
                                                placeholder="Masukkan nama agenda" 
                                                disabled>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group agenda-item">
                                        <input type="text" 
                                            name="agenda[{{ $index }}][]" 
                                            class="form-control" 
                                            placeholder="Masukkan nama agenda" 
                                            required>
                                    </div>
                                @endif
                            </div>

                            <!-- Tombol untuk menambah agenda -->
                            <button type="button" class="btn btn-info btn-secondary btn-add-agenda" data-index="{{ $index }}" {{ $agenda_sudah_ada ? 'disabled' : '' }}>
                            <i class="fa fa-plus"></i>
                            </button>
                            <hr>
                        </div>
                    @endforeach
                </div>
                <div class="text-right">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-primary btn-edit-agenda" {{ !$agenda_sudah_ada ? 'disabled' : '' }}>
                        Edit
                    </button>
                    <!-- Tombol Simpan -->
                    <button type="submit" class="btn btn-success btn-save-agenda" {{ $agenda_sudah_ada ? 'disabled' : '' }}>
                        Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        // Klik tombol Edit untuk mengaktifkan mode edit
        $('.btn-edit-agenda').on('click', function () {
            $('.agenda-item input').prop('disabled', false);
            $('.btn-add-agenda').prop('disabled', false);
            $('.btn-save-agenda').prop('disabled', false);
            $(this).prop('disabled', true); // Disable tombol edit setelah diklik
        });

        // Tambah agenda untuk anggota tertentu
        $(document).on('click', '.btn-add-agenda', function () {
            let index = $(this).data('index');
            let agendaContainer = $(this).siblings('.agenda-list');

            // Template input agenda baru
            let newAgenda = `
                <div class="form-group agenda-item">
                    <input type="text" 
                           name="agenda[${index}][]" 
                           class="form-control" 
                           placeholder="Masukkan nama agenda" 
                           required>
                    <button type="button" class="btn btn-sm btn-danger btn-remove-agenda"><i class="fas fa-trash"></i></button>
                </div>
            `;

            agendaContainer.append(newAgenda);
        });

        // Hapus agenda
        $(document).on('click', '.btn-remove-agenda', function () {
            $(this).closest('.agenda-item').remove();
        });

        // Validasi form
        $("#form-tambah-agenda").validate({
            rules: {
                'agenda[][nama]': { required: true, minlength: 3, maxlength: 255 },
            },
            submitHandler: function (form) {
                let formData = $(form).serializeArray();
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function (err) {
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
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>