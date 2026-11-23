<!DOCTYPE html>
<html lang="id">
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') &mdash; Sistem Inventaris Aset | PLN Nusantara Power UP Kaltimra</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-pln-np.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --pln-navy: #0A2540;
            --pln-blue: #005B9F;
            --pln-cyan: #00A8E8;
            --pln-gold: #FACC15;
            --bg-canvas: #F4F7FE;
            --surface-white: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #94A3B8;
            --border-subtle: #E2E8F0;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background-color: var(--bg-canvas); color: var(--text-primary); min-height: 100vh; margin: 0; overflow-x: hidden; }
        .auth-wrapper { min-height: 100vh; display: flex; flex-direction: row; }
        .auth-brand-panel {
            background: linear-gradient(150deg, rgba(10, 37, 64, 0.94) 0%, rgba(13, 59, 102, 0.90) 55%, rgba(0, 91, 159, 0.88) 100%), url('{{ asset('assets/img/bg-login.jpg') }}');
            background-size: cover; background-position: center; color: #FFFFFF; position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: space-between; padding: 3.5rem 4rem;
        }
        .brand-header { display: inline-flex; align-items: center; gap: 0.9rem; }
        .brand-emblem { width: 46px; height: 46px; border-radius: 12px; background: #FFFFFF; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(0,0,0,0.18); flex-shrink: 0; padding: 6px; }
        .brand-emblem img { max-height: 32px; max-width: 34px; object-fit: contain; }
        .brand-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); padding: 0.42rem 0.95rem; border-radius: 999px; font-size: 0.78rem; font-weight: 600; color: #E0F2FE; width: fit-content; }
        .brand-badge i { color: var(--pln-gold); }
        .feature-item { display: flex; align-items: flex-start; gap: 0.9rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.95rem 1.15rem; }
        .feature-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(0,168,232,0.2); color: #38BDF8; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; }
        .auth-form-panel { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 1.25rem; background-color: var(--bg-canvas); }
        .auth-card { width: 100%; max-width: 440px; background: var(--surface-white); border: 1px solid var(--border-subtle); border-radius: 18px; padding: 2.4rem 2.2rem; box-shadow: 0 12px 32px -6px rgba(15,23,42,0.06); }
        .form-label { font-size: 0.84rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.42rem; }
        .input-group-custom { position: relative; }
        .input-group-custom .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.05rem; pointer-events: none; }
        .input-group-custom .form-control { height: 46px; padding: 0.7rem 2.75rem 0.7rem 2.65rem; font-size: 0.92rem; border-radius: 11px; border: 1.5px solid var(--border-subtle); background-color: #F8FAFC; color: var(--text-primary); }
        .input-group-custom .form-control:focus { background-color: #FFFFFF; border-color: var(--pln-cyan); box-shadow: 0 0 0 3.5px rgba(0,168,232,0.14); }
        .btn-toggle-pass { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--text-muted); padding: 0.25rem 0.45rem; cursor: pointer; }
        .btn-pln-primary { height: 46px; background: linear-gradient(135deg, var(--pln-navy) 0%, var(--pln-blue) 100%); border: none; color: #FFFFFF; font-weight: 600; font-size: 0.94rem; border-radius: 11px; box-shadow: 0 6px 16px -4px rgba(0,91,159,0.35); }
        .btn-pln-primary:hover { background: linear-gradient(135deg, #071B30 0%, #004A82 100%); color: #FFFFFF; }
        .alert-modern-danger { border-radius: 11px; font-size: 0.84rem; border: 1px solid #FECACA; border-left: 4px solid #EF4444; background-color: #FEF2F2; color: #991B1B; padding: 0.75rem 1rem; }
        .alert-modern-success { border-radius: 11px; font-size: 0.84rem; border: 1px solid #A7F3D0; border-left: 4px solid #10B981; background-color: #ECFDF5; color: #065F46; padding: 0.75rem 1rem; }
        .auth-link { color: var(--pln-blue); font-weight: 600; text-decoration: none; }
        .auth-link:hover { color: var(--pln-navy); text-decoration: underline; }
        @media (max-width: 991.98px) { .auth-brand-panel { display: none !important; } .auth-card { padding: 2rem 1.5rem; } }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') &mdash; Sistem Inventaris Aset | PLN Nusantara Power UP Kaltimra</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo-pln-np.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --pln-navy: #0A2540;
            --pln-blue: #005B9F;
            --pln-cyan: #00A8E8;
            --pln-gold: #FACC15;
            --bg-canvas: #F4F7FE;
            --surface-white: #FFFFFF;
            --text-primary: #0F172A;
            --text-secondary: #475569;
            --text-muted: #94A3B8;
            --border-subtle: #E2E8F0;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background-color: var(--bg-canvas); color: var(--text-primary); min-height: 100vh; margin: 0; overflow-x: hidden; }
        .auth-wrapper { min-height: 100vh; display: flex; flex-direction: row; }
        .auth-brand-panel {
            background: linear-gradient(150deg, rgba(10, 37, 64, 0.94) 0%, rgba(13, 59, 102, 0.90) 55%, rgba(0, 91, 159, 0.88) 100%), url('{{ asset('assets/img/bg-login.jpg') }}');
            background-size: cover; background-position: center; color: #FFFFFF; position: relative; overflow: hidden;
            display: flex; flex-direction: column; justify-content: space-between; padding: 3.5rem 4rem;
        }
        .brand-header { display: inline-flex; align-items: center; gap: 0.9rem; }
        .brand-emblem { width: 46px; height: 46px; border-radius: 12px; background: #FFFFFF; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(0,0,0,0.18); flex-shrink: 0; padding: 6px; }
        .brand-emblem img { max-height: 32px; max-width: 34px; object-fit: contain; }
        .brand-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18); padding: 0.42rem 0.95rem; border-radius: 999px; font-size: 0.78rem; font-weight: 600; color: #E0F2FE; width: fit-content; }
        .brand-badge i { color: var(--pln-gold); }
        .feature-item { display: flex; align-items: flex-start; gap: 0.9rem; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.95rem 1.15rem; }
        .feature-icon { width: 38px; height: 38px; border-radius: 10px; background: rgba(0,168,232,0.2); color: #38BDF8; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.1rem; }
        .auth-form-panel { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 2rem 1.25rem; background-color: var(--bg-canvas); }
        .auth-card { width: 100%; max-width: 440px; background: var(--surface-white); border: 1px solid var(--border-subtle); border-radius: 18px; padding: 2.4rem 2.2rem; box-shadow: 0 12px 32px -6px rgba(15,23,42,0.06); }
        .form-label { font-size: 0.84rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.42rem; }
        .input-group-custom { position: relative; }
        .input-group-custom .input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1.05rem; pointer-events: none; }
        .input-group-custom .form-control { height: 46px; padding: 0.7rem 2.75rem 0.7rem 2.65rem; font-size: 0.92rem; border-radius: 11px; border: 1.5px solid var(--border-subtle); background-color: #F8FAFC; color: var(--text-primary); }
        .input-group-custom .form-control:focus { background-color: #FFFFFF; border-color: var(--pln-cyan); box-shadow: 0 0 0 3.5px rgba(0,168,232,0.14); }
        .btn-toggle-pass { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: var(--text-muted); padding: 0.25rem 0.45rem; cursor: pointer; }
        .btn-pln-primary { height: 46px; background: linear-gradient(135deg, var(--pln-navy) 0%, var(--pln-blue) 100%); border: none; color: #FFFFFF; font-weight: 600; font-size: 0.94rem; border-radius: 11px; box-shadow: 0 6px 16px -4px rgba(0,91,159,0.35); }
        .btn-pln-primary:hover { background: linear-gradient(135deg, #071B30 0%, #004A82 100%); color: #FFFFFF; }
        .alert-modern-danger { border-radius: 11px; font-size: 0.84rem; border: 1px solid #FECACA; border-left: 4px solid #EF4444; background-color: #FEF2F2; color: #991B1B; padding: 0.75rem 1rem; }
        .alert-modern-success { border-radius: 11px; font-size: 0.84rem; border: 1px solid #A7F3D0; border-left: 4px solid #10B981; background-color: #ECFDF5; color: #065F46; padding: 0.75rem 1rem; }
        .auth-link { color: var(--pln-blue); font-weight: 600; text-decoration: none; }
        .auth-link:hover { color: var(--pln-navy); text-decoration: underline; }
        @media (max-width: 991.98px) { .auth-brand-panel { display: none !important; } .auth-card { padding: 2rem 1.5rem; } }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-brand-panel col-lg-6">
            <div>
                <div class="brand-header mb-4">
                    <div class="brand-emblem">
                        <img src="{{ asset('assets/img/logo-pln-np.png') }}" alt="Logo PLN" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <i class="bi bi-lightning-charge-fill fs-4" style="display:none; color:#005B9F;"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-6 text-white lh-sm">PT PLN Nusantara Power</div>
                        <div class="small" style="color: #7DD3FC;">Unit Pembangkitan Kaltimra</div>
                    </div>
                </div>
                <div class="brand-badge mb-3"><i class="bi bi-qr-code-scan"></i><span>SIA-PLN &bull; Sistem Inventaris Aset</span></div>
                <h1 class="fw-bold fs-2 mb-3 lh-sm">Sistem Inventaris Aset PLN Nusantara Power UP Kaltimra</h1>
                <p class="text-white-50 mb-0" style="max-width:480px; font-size:0.95rem;"></p>
            </div>
            <div class="d-flex flex-column gap-3 my-4" style="max-width:480px;">
                <div class="feature-item"><div class="feature-icon"><i class="bi bi-upc-scan"></i></div><div><div class="fw-semibold text-white small mb-1"></div><div class="text-white-50" style="font-size:0.8rem;"></div></div></div>
                <div class="feature-item"><div class="feature-icon"><i class="bi bi-tools"></i></div><div><div class="fw-semibold text-white small mb-1"></div><div class="text-white-50" style="font-size:0.8rem;"></div></div></div>
            </div>
            <div class="small text-white-50">&copy; {{ date('Y') }} PT PLN Nusantara Power UP Kaltimra.</div>
        </div>
        <div class="auth-form-panel">
            <div class="auth-card">
                <div class="d-flex d-lg-none align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px; height:42px; background:linear-gradient(135deg,#0A2540,#005B9F); color:#FACC15;"><i class="bi bi-lightning-charge-fill fs-5"></i></div>
                    <div><div class="fw-bold text-dark lh-sm" style="font-size:0.95rem;">SIA-PLN Nusantara Power</div><small class="text-muted" style="font-size:0.78rem;">UP Kaltimra &bull; Inventaris Aset</small></div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-toggle-pass').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.getAttribute('data-target'));
                    const icon = btn.querySelector('i');
                    if (!input || !icon) return;
                    const isPass = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPass ? 'text' : 'password');
                    icon.classList.toggle('bi-eye', !isPass);
                    icon.classList.toggle('bi-eye-slash', isPass);
                });
            });
            const form = document.querySelector('.auth-card form');
            const btn = form ? form.querySelector('button[type="submit"]') : null;
            if (form && btn) {
                form.addEventListener('submit', function () {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span><span>' + (btn.getAttribute('data-loading') || 'Memproses...') + '</span>';
                });
            }
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-toggle-pass').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const input = document.getElementById(btn.getAttribute('data-target'));
                    const icon = btn.querySelector('i');
                    if (!input || !icon) return;
                    const isPass = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPass ? 'text' : 'password');
                    icon.classList.toggle('bi-eye', !isPass);
                    icon.classList.toggle('bi-eye-slash', isPass);
                });
            });
            const form = document.querySelector('.auth-card form');
            const btn = form ? form.querySelector('button[type="submit"]') : null;
            if (form && btn) {
                form.addEventListener('submit', function () {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span><span>' + (btn.getAttribute('data-loading') || 'Memproses...') + '</span>';
                });
            }
        });
    </script>
</body>
</html>