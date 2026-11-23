@extends('layouts.main')

@section('title', 'Profil')

@section('content')
<div class="section-header">
    <h1>Profil Saya</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="/">Dashboard</a></div>
        <div class="breadcrumb-item">Profil</div>
    </div>
</div>

<div class="section-body">
    <h2 class="section-title">Informasi Akun</h2>
    <p class="section-lead">Kelola informasi profil akun dan pembaruan kata sandi Anda di Sistem Inventaris Aset.</p>

    <div class="row">
        <div class="col-12 col-md-5 col-lg-4">
            <div class="card profile-widget">
                <div class="profile-widget-header text-center pt-3">
                    <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle profile-widget-picture" style="width: 85px; height: 85px; object-fit: cover; border: 3px solid #6777ef;">
                </div>
                <div class="profile-widget-description text-center pb-4">
                    <div class="profile-widget-name font-weight-bold mb-1" style="font-size: 1.1rem;">{{ auth()->user()->name }}</div>
                    <div class="text-muted small mb-2"><i class="fas fa-envelope mr-1"></i> {{ auth()->user()->email }}</div>
                    <span class="badge badge-primary px-3 py-1 font-weight-bold">
                        {{ auth()->user()->role->role == 'manager' ? 'Manager' : 'Admin' }}
                    </span>
                    <hr>
                    <div class="text-left text-small text-muted px-2">
                        <div class="font-weight-bold text-dark mb-1"><i class="fas fa-shield-alt text-success mr-1"></i> Tips Keamanan:</div>
                        <ul class="pl-3 mb-0">
                            <li>Minimal 8 karakter.</li>
                            <li>Gunakan kombinasi huruf, angka, atau simbol.</li>
                            <li>Jangan bagikan kata sandi Anda kepada orang lain.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7 col-lg-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h4><i class="fas fa-key text-primary mr-2"></i> Pengaturan Kata Sandi</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible show fade">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                                <strong><i class="fas fa-exclamation-triangle mr-1"></i> Perhatian:</strong>
                                <ul class="mb-0 mt-1 pl-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="current_password" class="font-weight-bold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       placeholder="Masukkan kata sandi saat ini"
                                       required
                                       autocomplete="current-password">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-pw" type="button" data-target="current_password" title="Lihat/Sembunyikan kata sandi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="font-weight-bold">Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimal 8 karakter"
                                       required
                                       autocomplete="new-password">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-pw" type="button" data-target="password" title="Lihat/Sembunyikan kata sandi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="font-weight-bold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Ulangi kata sandi baru"
                                       required
                                       autocomplete="new-password">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-toggle-pw" type="button" data-target="password_confirmation" title="Lihat/Sembunyikan kata sandi">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-right px-0 pb-0 pt-3">
                            <a href="/" class="btn btn-secondary mr-2">
                                <i class="fas fa-arrow-left mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-toggle-pw').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var targetId = btn.getAttribute('data-target');
                var input = document.getElementById(targetId);
                var icon = btn.querySelector('i');
                if (!input || !icon) return;

                if (input.getAttribute('type') === 'password') {
                    input.setAttribute('type', 'text');
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.setAttribute('type', 'password');
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endsection
