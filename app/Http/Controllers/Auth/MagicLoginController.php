<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class MagicLoginController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendLoginLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
            'email.exists'   => 'Email tersebut tidak terdaftar di sistem. Hubungi Admin UP Kaltimra.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $loginUrl = URL::temporarySignedRoute(
            'login.magic',
            now()->addMinutes(15),
            ['user' => $user->id]
        );

        Mail::send('emails.magic-login', [
            'user'     => $user,
            'loginUrl' => $loginUrl,
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject('Tautan Sesi Login â€” Sistem Inventaris Aset PLN Nusantara Power');
        });

        return back()->with('status', 'Tautan sesi login telah dikirim ke ' . $user->email . '. Silakan periksa kotak masuk atau folder spam Anda (berlaku 15 menit).');
    }

    public function authenticate(Request $request, User $user)
    {
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }
}