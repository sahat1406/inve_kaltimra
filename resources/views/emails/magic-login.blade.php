<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tautan Sesi Login SIA-PLN</title>
</head>
<body style="margin:0; padding:0; background-color:#F4F7FE; font-family:'Segoe UI', Arial, sans-serif; color:#0F172A;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" style="max-width:520px; background:#FFFFFF; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden;">
                    <tr>
                        <td style="background:linear-gradient(135deg, #0A2540 0%, #005B9F 100%); padding:24px 28px; color:#FFFFFF;">
                            <div style="font-size:13px; color:#7DD3FC; font-weight:600;">PT PLN Nusantara Power &bull; UP Kaltimra</div>
                            <div style="font-size:19px; font-weight:700; margin-top:4px;">Sistem Inventaris Aset (SIA-PLN)</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <p style="margin:0 0 12px; font-size:15px;">Halo, <strong>{{ $user->name }}</strong></p>
                            <p style="margin:0 0 22px; font-size:14px; line-height:1.6; color:#475569;">
                                Kami menerima permintaan akses masuk untuk akun Anda. Klik tombol di bawah ini untuk langsung membuka sesi login ke dasbor tanpa memasukkan kata sandi:
                            </p>
                            <div style="text-align:center; margin:26px 0;">
                                <a href="{{ $loginUrl }}" style="display:inline-block; background:#005B9F; color:#FFFFFF; text-decoration:none; font-weight:600; font-size:14px; padding:13px 26px; border-radius:10px;">
                                    Masuk ke Dasbor Sekarang
                                </a>
                            </div>
                            <p style="margin:0 0 12px; font-size:12.5px; line-height:1.6; color:#64748B;">
                                Tautan ini bersifat rahasia dan hanya berlaku selama <strong>15 menit</strong>. Jika Anda tidak merasa meminta tautan ini, abaikan saja email ini.
                            </p>
                            <hr style="border:none; border-top:1px solid #E2E8F0; margin:20px 0;">
                            <p style="margin:0; font-size:11.5px; color:#94A3B8; word-break:break-all;">
                                Jika tombol tidak berfungsi, salin tautan berikut ke browser Anda:<br>
                                <a href="{{ $loginUrl }}" style="color:#005B9F;">{{ $loginUrl }}</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>