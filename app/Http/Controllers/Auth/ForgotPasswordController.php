<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format alamat email tidak valid.',
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        try {
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($request, $response)
                        : $this->sendResetLinkFailedResponse($request, $response);
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage(), [
                'email' => $request->email,
                'exception' => $e,
            ]);

            // Jika dalam mode debug/lokal dan pengiriman email gagal (misal SMTP belum diatur),
            // sediakan link reset langsung agar alur reset password tetap dapat diuji.
            if (config('app.debug')) {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $token = Password::broker()->createToken($user);
                    $resetUrl = route('password.reset', [
                        'token' => $token,
                        'email' => $user->email,
                    ]);

                    return back()->with('status', 'Email tidak dapat terkirim via server SMTP (' . $e->getMessage() . ').')
                                 ->with('debug_reset_url', $resetUrl);
                }

                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Gagal mengirim email reset kata sandi. Pastikan konfigurasi email (SMTP) di .env sudah benar atau hubungi Admin.']);
        }
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', 'Tautan untuk mengatur ulang kata sandi telah dikirim ke email Anda! Silakan periksa kotak masuk atau folder spam.');
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
    }
}
