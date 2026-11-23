@extends('layouts.app-auth')

@section('title', 'Atur Ulang Kata Sandi')

@section('content')
    <div class="mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
             style="width: 44px; height: 44px; background: rgba(0, 91, 159, 0.1); color: #005B9F;">
            <i class="bi bi-shield-lock-fill fs-5"></i>
        </div>
        <h2 class="fw-bold fs-4 text-dark mb-1" style="letter-spacing: -0.3px;">Atur Ulang Kata Sandi</h2>
        <p class="text-secondary small mb-0">
            Silakan masukkan kata sandi baru untuk akun Anda. Pastikan kata sandi baru minimal 8 karakter.
        </p>
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

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group-custom">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ $email ?? old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email"
                    required
                    autocomplete="email"
                    readonly
                >
                <i class="bi bi-envelope input-icon"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi Baru</label>
            <div class="input-group-custom">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password"
                    autofocus
                >
                <i class="bi bi-lock input-icon"></i>
                <button type="button" class="btn-toggle-pass" data-target="password" aria-label="Tampilkan kata sandi">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <div class="form-text small text-secondary mt-1">Gunakan kombinasi huruf, angka, atau simbol.</div>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <div class="input-group-custom">
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi kata sandi baru"
                    required
                    autocomplete="new-password"
                >
                <i class="bi bi-shield-check input-icon"></i>
                <button type="button" class="btn-toggle-pass" data-target="password_confirmation" aria-label="Tampilkan kata sandi">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit"
                class="btn btn-pln-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3"
                data-loading="Menyimpan Kata Sandi...">
            <span>Simpan Kata Sandi Baru</span>
            <i class="bi bi-check2-circle"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link small d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke halaman Masuk</span>
            </a>
        </div>
    </form>
@endsection
