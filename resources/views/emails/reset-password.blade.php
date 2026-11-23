<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Reset Kata Sandi - SIA-PLN</title>
</head>
<body style="margin:0; padding:0; background-color:#F4F7FE; font-family:'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#0F172A;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:540px; background:#FFFFFF; border-radius:16px; border:1px solid #E2E8F0; overflow:hidden; box-shadow:0 10px 25px -5px rgba(15, 23, 42, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #0A2540 0%, #005B9F 100%); padding:28px 32px; color:#FFFFFF;">
                            <div style="font-size:12.5px; color:#7DD3FC; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">PT PLN Nusantara Power &bull; UP Kaltimra</div>
                            <div style="font-size:20px; font-weight:700; margin-top:6px; letter-spacing:-0.2px;">Sistem Inventaris Aset (SIA-PLN)</div>
                        </td>
                    </tr>
                    <!-- Body Content -->
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 14px; font-size:15px; color:#0F172A;">Halo, <strong>{{ $user->name }}</strong></p>
                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#475569;">
                                Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda di Sistem Inventaris Aset PT PLN Nusantara Power UP Kaltimra.
                            </p>

                            <div style="text-align:center; margin:28px 0;">
                                <a href="{{ $resetUrl }}" style="display:inline-block; background:linear-gradient(135deg, #0A2540 0%, #005B9F 100%); color:#FFFFFF; text-decoration:none; font-weight:600; font-size:14px; padding:13px 30px; border-radius:10px; box-shadow:0 4px 12px rgba(0, 91, 159, 0.3);">
                                    Atur Ulang Kata Sandi
                                </a>
                            </div>

                            <div style="background-color:#F8FAFC; border-left:4px solid #005B9F; border-radius:6px; padding:12px 16px; margin:24px 0;">
                                <p style="margin:0; font-size:12.5px; line-height:1.6; color:#64748B;">
                                    <strong>Perhatian:</strong> Tautan pengaturan ulang kata sandi ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dan akun Anda akan tetap aman.
                                </p>
                            </div>

                            <hr style="border:none; border-top:1px solid #E2E8F0; margin:24px 0;">

                            <p style="margin:0; font-size:11.5px; color:#94A3B8; line-height:1.6; word-break:break-all;">
                                Jika Anda mengalami kesulitan mengklik tombol "Atur Ulang Kata Sandi", salin dan tempel tautan berikut ke peramban (browser) web Anda:<br>
                                <a href="{{ $resetUrl }}" style="color:#005B9F; text-decoration:underline;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background:#F8FAFC; padding:18px 32px; border-top:1px solid #E2E8F0; text-align:center; font-size:11.5px; color:#94A3B8;">
                            &copy; {{ date('Y') }} PT PLN Nusantara Power UP Kaltimra. Hak cipta dilindungi.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
