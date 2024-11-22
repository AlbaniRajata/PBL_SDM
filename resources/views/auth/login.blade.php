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
            position: relative; /* Position relative for the pseudo-element */
            height: 100vh; /* Full viewport height */
            margin: 0; /* Removes default margin */
            overflow: hidden; /* Prevents any overflow */
        }

        body::before {
            content: ''; /* Required to create a pseudo-element */
            position: absolute; /* Position it absolutely within the body */
            top: 0; /* Align to the top */
            left: 0; /* Align to the left */
            right: 0; /* Align to the right */
            bottom: 0; /* Align to the bottom */
            background-image: url('img/jtiblur.png'); /* Background image */
            background-size: cover; /* Ensures the image covers the entire area */
            background-position: center; /* Centers the background image */
            background-repeat: no-repeat; /* Prevents the image from repeating */
            filter: blur(5px); /* Adjust blur amount here (increase or decrease as needed) */
            z-index: -1; /* Send to the back */
        }

        .content {
            display: flex; /* Use flexbox for positioning */
            justify-content: flex-end; /* Align items to the right */
            align-items: center; /* Center vertically */
            height: 100vh; /* Full height */
            color: white; /* Change text color for better visibility */
            position: relative; /* Ensures content appears above the blurred background */
        }

        .login-box {
            width: 400px; /* Set a fixed width for the login box */
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            margin-right: 20px; /* Optional: add some spacing from the right edge */
        }

        .card {
            border-radius: 0;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(to right, #007bff, #6f42c1);
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .error-text {
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .login-box-msg {
            margin-bottom: 15px;
        }

        /* Animasi input focus */
        .input-group input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        @media (max-width: 576px) {
    .login-box {
        width: 95%; /* Slightly increase width on small screens */
    }
}

    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ url('/') }}" class="h1"><b>SI - </b>SDM</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Masuk untuk memulai sesi Anda</p>
                <form action="{{ url('login') }}" method="POST" id="form-login">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">Ingat Saya</label>
                        </div>
                    </div>
                    <div class="row mt-12">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success btn-block">Masuk</button>
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
