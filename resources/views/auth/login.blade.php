<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pengguna</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ url('/plugins/fontawesome-free/css/all.min.css') }}">
    <script src="https://kit.fontawesome.com/f2110b96b9.js" crossorigin="anonymous"></script>
    {{-- fontawesome --}}
    <link rel="stylesheet" href="">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ url('/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ url('/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url('/dist/css/adminlte.min.css') }}">
    <link rel="icon" href="{{ url('polinema.png') }}" type="image/png">
    <style>
        body {
        margin: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Source Sans Pro', sans-serif;
        background: url('img/background.png') no-repeat center center; /* Mengatur gambar sebagai latar */
        background-size: cover; /* Menyesuaikan gambar agar mencakup seluruh area */
    }

    .content {
        display: flex;
        justify-content: space-between;
        width: 90%;
        max-width: 1200px;
        color: white;
    }

    .intro {
        max-width: 50%;
        padding: 20px;
    }

    .intro h1 {
        font-size: 2.5rem;
        font-weight: bold;
    }

    .intro p {
        font-size: 1.2rem;
        margin-top: 10px;
    }

    .login-box {
        width: 400px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .card-header {
        background: #3498db;
        color: white;
        text-align: center;
        padding: 15px 0;
        font-size: 1.5rem;
    }

    .card-body {
        padding: 20px;
    }

    .login-box-msg {
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: #555;
    }

    .input-group .form-control {
        border-radius: 20px;
    }

    .btn-primary {
        background-color: #3498db;
        border: none;
        border-radius: 20px;
    }

    .btn-primary:hover {
        background-color: #2980b9;
    }

    .btn-link {
        text-decoration: none;
        color: #3498db;
    }

    .btn-link:hover {
        text-decoration: underline;
        color: #2980b9;
    }
    </style>
</head>

<div class="content">
    <div class="intro">
        <h1>Selamat datang<br>Dosen Jurusan Teknologi Informasi</h1>
        <p>di <strong>Sistem Manajemen SDM</strong><br>Silahkan isi formulir yang ada untuk masuk.</p>
    </div>
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>SI - </b>SDM</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Please Login</p>
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Isi Username Anda" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Isi Password Anda" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="#" class="btn btn-link">Lupa Password?</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- jQuery -->
    <script src="{{ url('/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ url('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ url('/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ url('/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url('/dist/js/adminlte.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $("#form-login").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 4,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    }
                },
                messages: {
                    username: {
                        required: "Username wajib diisi",  // Pesan jika kolom username kosong
                        minlength: "Username minimal harus 4 karakter",
                        maxlength: "Username maksimal 20 karakter"
                    },
                    password: {
                        required: "Password wajib diisi",  // Pesan jika kolom password kosong
                        minlength: "Password minimal harus 5 karakter"
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('error-text text-danger'); // Tambahkan kelas untuk error text
                    error.insertAfter(element.closest('.input-group')); // Tampilkan error tepat setelah elemen input
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                }).then(function() {
                                    window.location = response.redirect; // Arahkan ke halaman login
                                });
                            } else {
                                $('.error-text').text(''); // Bersihkan error sebelumnya
                                $.each(response.errors, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]); // Tampilkan error baru
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
                                title: 'Login Gagal',
                                text: 'Terjadi kesalahan pada server.',
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>