@extends('layouts.app-auth')

@section('title', 'Masuk')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold fs-4 text-dark mb-1" style="letter-spacing: -0.3px;">Selamat Datang</h2>
        <p class="text-secondary small mb-0">Masuk menggunakan email dan kata sandi akun Anda untuk mengakses dasbor.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-modern-danger d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill mt-1 flex-shrink-0"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-modern-success d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group-custom">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Masukkan Email  Anda"
                    required
                    autofocus
                    autocomplete="username"
                >
                <i class="bi bi-envelope input-icon"></i>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="password" class="form-label mb-1">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link small">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>
            <div class="input-group-custom">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Masukkan kata sandi Anda"
                    required
                    autocomplete="current-password"
                >
                <i class="bi bi-lock input-icon"></i>
                <button type="button" class="btn-toggle-pass" data-target="password" aria-label="Tampilkan kata sandi">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small text-secondary" for="remember">
                    Ingat sesi saya di perangkat ini
                </label>
            </div>
        </div>

        <button type="submit"
                class="btn btn-pln-primary w-100 d-flex align-items-center justify-content-center gap-2"
                data-loading="Memverifikasi...">
            <span>Masuk ke Dasbor</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </form>
@endsection