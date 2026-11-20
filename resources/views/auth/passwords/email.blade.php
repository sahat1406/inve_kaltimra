@extends('layouts.app-auth')

@section('title', 'Kirim Tautan Sesi Login')

@section('content')
    <div class="mb-4">
        <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
             style="width: 44px; height: 44px; background: rgba(0, 91, 159, 0.1); color: #005B9F;">
            <i class="bi bi-envelope-paper fs-5"></i>
        </div>
        <h2 class="fw-bold fs-4 text-dark mb-1" style="letter-spacing: -0.3px;">Lupa Kata Sandi?</h2>
        <p class="text-secondary small mb-0">
            Masukkan email akun Anda yang terdaftar. Sistem akan mengirimkan tautan sesi login langsung ke kotak masuk email Anda.
        </p>
    </div>

    @if (session('status'))
        <div class="alert alert-modern-success d-flex align-items-start gap-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

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
                    placeholder="nama@plnnusantarapower.co.id"
                    required
                    autocomplete="email"
                    autofocus
                >
                <i class="bi bi-envelope input-icon"></i>
            </div>
        </div>

        <button type="submit"
                class="btn btn-pln-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3"
                data-loading="Mengirim Tautan Sesi...">
            <span>Kirim Tautan Login ke Email</span>
            <i class="bi bi-send"></i>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="auth-link small d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke halaman Masuk</span>
            </a>
        </div>
    </form>
@endsection