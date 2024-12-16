<form action="{{ url('admin/user/ajax') }}" method="POST" id="createUserForm">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                    <small id="error-username" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                    <small id="error-nama" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    <small id="error-tanggal_lahir" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <small id="error-email" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <small id="error-password" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="NIP">NIP</label>
                    <input type="number" class="form-control" id="NIP" name="NIP" required>
                    <small id="error-NIP" class="error-text"></small>
                </div>
                <div class="form-group">
                    <label for="level">Level</label>
                    <select class="form-control" id="level" name="level" required>
                        <option value="">Pilih Level</option>
                        <option value="admin">Admin</option>
                        <option value="dosen">Dosen</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>
                    <small id="error-level" class="error-text"></small>
                </div>
                <div class="text-right">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Tambahkan CSS untuk pesan error -->
<style>
    .error-text {
        color: red;
        font-size: 0.9em;
        margin-top: 0.25rem;
    }
</style>

<script>
    $(document).ready(function() {
        $('#createUserForm').validate({
            rules: {
                username: {
                    required: true,
                },
                nama: {
                    required: true,
                },
                tanggal_lahir: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                NIP: {
                    required: true,
                    digits: true
                },
                level: {
                    required: true
                }
            },
            messages: {
                username: {
                    required: "Username wajib diisi."
                },
                nama: {
                    required: "Nama Lengkap wajib diisi."
                },
                tanggal_lahir: {
                    required: "Tanggal Lahir wajib diisi."
                },
                email: {
                    required: "Email wajib diisi.",
                    email: "Masukkan alamat email yang valid."
                },
                password: {
                    required: "Password wajib diisi.",
                    minlength: "Password harus minimal 6 karakter."
                },
                NIP: {
                    required: "NIP wajib diisi.",
                    digits: "NIP harus berupa angka."
                },
                level: {
                    required: "Level wajib dipilih."
                }
            },
            errorPlacement: function(error, element) {
                let errorContainer = element.next('.error-text');
                if (errorContainer.length) {
                    errorContainer.text(error.text());
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
                            dataUser.ajax.reload();
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
                    error: function() {
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