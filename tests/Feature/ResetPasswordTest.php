<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $userRole;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['id' => 1, 'role' => 'manager']);
        $this->userRole = Role::create(['id' => 2, 'role' => 'admin']);
    }

    /** @test */
    public function user_dapat_melihat_laman_lupa_password()
    {
        $response = $this->get('/password/reset');

        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee('Kirim Tautan Reset Kata Sandi');
    }

    /** @test */
    public function email_wajib_diisi_saat_meminta_tautan_reset()
    {
        $response = $this->post('/password/email', [
            'email' => '',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function format_email_harus_valid_saat_meminta_tautan_reset()
    {
        $response = $this->post('/password/email', [
            'email' => 'bukan-format-email',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function meminta_tautan_reset_dengan_email_tidak_terdaftar_menghasilkan_error()
    {
        $response = $this->post('/password/email', [
            'email' => 'tidakada@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function meminta_tautan_reset_dengan_email_terdaftar_berhasil_mengirimkan_notifikasi()
    {
        Notification::fake();

        $user = User::factory()->create([
            'role_id'  => $this->userRole->id,
            'email'    => 'pegawai@plnnusantarapower.co.id',
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->post('/password/email', [
            'email' => 'pegawai@plnnusantarapower.co.id',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    /** @test */
    public function user_dapat_melihat_laman_reset_password_dengan_token()
    {
        $user = User::factory()->create([
            'role_id' => $this->userRole->id,
            'email'   => 'pegawai@plnnusantarapower.co.id',
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->get('/password/reset/' . $token . '?email=' . urlencode($user->email));

        $response->assertStatus(200);
        $response->assertSee('Atur Ulang Kata Sandi');
        $response->assertSee($user->email);
        $response->assertSee('Simpan Kata Sandi Baru');
    }

    /** @test */
    public function user_tidak_dapat_reset_password_jika_konfirmasi_tidak_cocok()
    {
        $user = User::factory()->create([
            'role_id'  => $this->userRole->id,
            'email'    => 'pegawai@plnnusantarapower.co.id',
            'password' => bcrypt('passwordlama123'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post('/password/reset', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordberbeda123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('passwordlama123', $user->fresh()->password));
    }

    /** @test */
    public function user_tidak_dapat_reset_password_jika_panjang_kurang_dari_8_karakter()
    {
        $user = User::factory()->create([
            'role_id'  => $this->userRole->id,
            'email'    => 'pegawai@plnnusantarapower.co.id',
            'password' => bcrypt('passwordlama123'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post('/password/reset', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('passwordlama123', $user->fresh()->password));
    }

    /** @test */
    public function user_berhasil_mereset_password_dan_dapat_login_dengan_password_baru()
    {
        $user = User::factory()->create([
            'role_id'  => $this->userRole->id,
            'email'    => 'pegawai@plnnusantarapower.co.id',
            'password' => bcrypt('passwordlama123'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post('/password/reset', [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertRedirect('/home');
        $response->assertSessionHas('status');

        $this->assertTrue(Hash::check('passwordbaru123', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
    }
}
