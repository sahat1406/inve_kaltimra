@extends('layouts.app-auth')

<<<<<<< HEAD
@section('title', 'Lupa Kata Sandi')
=======
@section('title', 'Kirim Tautan Sesi Login')
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8

@section('content')
    <div class="mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
             style="width: 44px; height: 44px; background: rgba(0, 91, 159, 0.1); color: #005B9F;">
<<<<<<< HEAD
            <i class="bi bi-key-fill fs-5"></i>
        </div>
        <h2 class="fw-bold fs-4 text-dark mb-1" style="letter-spacing: -0.3px;">Lupa Kata Sandi?</h2>
        <p class="text-secondary small mb-0">
            Masukkan alamat email akun Anda yang terdaftar. Sistem akan mengirimkan tautan verifikasi untuk mengatur ulang kata sandi Anda.
=======
            <i class="bi bi-envelope-paper fs-5"></i>
        </div>
        <h2 class="fw-bold fs-4 text-dark mb-1" style="letter-spacing: -0.3px;">Lupa Kata Sandi?</h2>
        <p class="text-secondary small mb-0">
            Masukkan email akun Anda yang terdaftar. Sistem akan mengirimkan tautan sesi login langsung ke kotak masuk email Anda.
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-modern-success d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

<<<<<<< HEAD
    @if (session('debug_reset_url'))
        <div class="alert alert-info d-flex flex-column gap-2 mb-3 small" role="alert" style="border-radius:11px; background-color:#EFF6FF; border:1px solid #BFDBFE; color:#1E40AF;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                <strong class="fw-bold">Mode Pengujian Lokal:</strong>
            </div>
            <div>
                Karena SMTP email belum terhubung di lingkungan lokal/dev, Anda dapat langsung mengklik tautan di bawah ini untuk menguji reset password:
            </div>
            <div>
                <a href="{{ session('debug_reset_url') }}" class="btn btn-sm text-white fw-semibold d-inline-flex align-items-center gap-1" style="background-color:#005B9F; border-radius:8px; padding:6px 14px;">
                    <span>Buka Formulir Reset Kata Sandi</span>
                    <i class="bi bi-arrow-right"></i>
                </a>
=======
    @if ($errors->any())
        <div class="alert alert-modern-danger d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill mt-1 flex-shrink-0"></i>
            <div>
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
            </div>
        </div>
    @endif

<<<<<<< HEAD
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

=======
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Alamat Email Terdaftar</label>
            <div class="input-group-custom">
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
<<<<<<< HEAD
                    placeholder="Email"
=======
                    placeholder="nama@plnnusantarapower.co.id"
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
                    required
                    autocomplete="email"
                    autofocus
                >
                <i class="bi bi-envelope input-icon"></i>
            </div>
        </div>

        <button type="submit"
                class="btn btn-pln-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3"
<<<<<<< HEAD
                data-loading="Mengirim Tautan Reset...">
            <span>Kirim Tautan Reset Kata Sandi</span>
=======
                data-loading="Mengirim Tautan Sesi...">
            <span>Kirim Tautan Login ke Email</span>
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
            <i class="bi bi-send"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link small d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke halaman Masuk</span>
            </a>
        </div>
    </form>
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> d321031bfff1f6fca905e3458c03a427b7e761c8
