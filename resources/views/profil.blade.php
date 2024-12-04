@extends('layouts.template')
<link rel="stylesheet" href="{{ asset('css/style.profil.css') }}">

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit"></i> Profil Kamu
                    </h4>
                </div>
                <div class="card-body px-5 py-4">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Bagian Gambar dan Detail -->
                    <div class="row mb-5">
                        <div class="col-md-4 text-center">
                            <div class="position-relative">
                                <img id="profile-pic-preview" 
                                    src="{{ $user->profile_image ? asset('storage/photos/' . $user->profile_image) : asset('/public/img/polinema-bw.png') }}"
                                    class="img-fluid rounded-circle shadow-lg profile-img">
                                <div class="overlay-hover rounded-circle">
                                    <i class="fas fa-camera text-white"></i>
                                </div>
                            </div>
                            <h5 class="mt-3 font-weight-bold">{{ $user->nama }}</h5>
                            <p class="text-muted">{{ $user->level }}</p>
                        </div>

                        <!-- Form Input File -->
                        <div class="col-md-8">
                            <form method="POST" action="{{ route('profil.update', $user->id_user) }}" enctype="multipart/form-data">
                                @method('PATCH')
                                @csrf

                                <div class="form-group mb-3">
                                    <label for="profile_image" class="form-label">{{ __('Ganti Foto Profil') }}</label>
                                    <input id="profile_image" type="file" class="form-control shadow-sm" name="profile_image"
                                        accept="image/*" onchange="previewImage(event)">
                                </div>
                        </div>
                    </div>

                    <!-- Bagian Form -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">{{ __('Username') }}</label>
                            <input id="username" type="text" class="form-control shadow-sm @error('username') is-invalid @enderror"
                                name="username" value="{{ $user->username }}" required placeholder="Masukkan username Anda">
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">{{ __('Nama') }}</label>
                            <input id="nama" type="text" class="form-control shadow-sm @error('nama') is-invalid @enderror"
                                name="nama" value="{{ old('nama', $user->nama) }}" required placeholder="Masukkan nama lengkap Anda">
                            @error('nama')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="old_password" class="form-label">{{ __('Password Lama') }}</label>
                            <input id="old_password" type="password" class="form-control shadow-sm @error('old_password') is-invalid @enderror"
                                name="old_password" placeholder="Masukkan password lama Anda">
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('Password Baru') }}</label>
                            <input id="password" type="password" class="form-control shadow-sm @error('password') is-invalid @enderror"
                                name="password" placeholder="Masukkan password baru">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Konfirmasi Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control shadow-sm"
                                name="password_confirmation" placeholder="Konfirmasi password baru Anda">
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="text-end">
                        <button type="submit" class="btn btn-gradient px-4">
                            <i class="fas fa-save"></i> {{ __('Update Profil') }}
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('profile-pic-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
