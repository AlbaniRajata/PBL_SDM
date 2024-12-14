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
            font-family: 'Source Sans Pro', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('img/background.png') no-repeat center center;
            background-size: cover;
            color: white;
            overflow: hidden;
        }

        .content {
            display: flex;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            max-width: 1000px;
            width: 90%;
        }

        .intro {
            max-width: 50%;
            padding: 20px;
            color: white;
        }

        .intro img {
            margin-bottom: 15px;
        }

        .intro h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .intro p {
            font-size: 1.2rem;
            margin-top: 15px;
            line-height: 1.6;
        }

        .login-box {
            width: 400px;
            background-color: white;
            border-radius: 15px;
            padding: 20px 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .login-box .card-header {
            background: #3498db;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 1.5rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .login-box .card-body {
            padding: 20px;
        }

        .login-box .form-control {
            border-radius: 30px;
            padding: 10px 15px;
        }

        .btn-primary {
            background: #3498db;
            border: none;
            border-radius: 30px;
            padding: 10px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #8e44ad, #3498db);
        }

        .btn-link {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #3498db;
            font-size: 0.9rem;
        }

        .btn-link:hover {
            text-decoration: underline;
            color: #8e44ad;
        }
    </style>
</head>

<div class="content">
    <div class="intro">
        <div class="text mb-3">
            <img src="{{ asset('/polinema.png') }}" alt="Logo Polinema" style="width: 75px;">
            <img src="{{ asset('/jti.png') }}" alt="Logo JTI" style="width: 75px;">
        </div>
        <h1>Selamat datang<br>Dosen Jurusan Teknologi Informasi</h1>
        <p>di <strong>Sistem Manajemen SDM</strong><br>Silahkan isi form login yang untuk masuk.</p>
    </div>
    <div class="login-box">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>SI - </b>SDM</a>
            </div>
            <div class="card-body">
                <p class="text-center text-muted mb-3">Silakan masuk</p>
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
                    <div class="row justify-content-center align-items-center">
                        <div class="col-6 text-center ">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </div>
                </form>
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
                        minlength: "Masukkan username dengan benar",
                        maxlength: "Username maksimal 20 karakter"
                    },
                    password: {
                        required: "Password wajib diisi",  // Pesan jika kolom password kosong
                        minlength: "Isikan password dengan benar"
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