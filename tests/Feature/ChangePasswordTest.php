<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $managerRole;
    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $this->adminRole   = Role::create(['id' => 2, 'role' => 'admin']);
    }

    /** @test */
    public function tamu_tidak_dapat_mengakses_halaman_profil()
    {
        $response = $this->get('/profil');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_dapat_melihat_halaman_profil()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Profil');
        $response->assertSee('Pengaturan Kata Sandi');
        $response->assertSee('Admin');
        $response->assertSee($admin->email);
    }

    /** @test */
    public function manager_dapat_melihat_halaman_profil()
    {
        $manager = User::factory()->create([
            'role_id'  => $this->managerRole->id,
            'password' => bcrypt('managerlama123'),
        ]);

        $response = $this->actingAs($manager)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Profil');
        $response->assertSee('Manager');
    }

    /** @test */
    public function rute_ubah_password_lama_tetap_dapat_diakses()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->get('/ubah-password');

        $response->assertStatus(200);
        $response->assertSee('Profil');
    }

    /** @test */
    public function ubah_password_gagal_jika_kata_sandi_saat_ini_salah()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->put('/profil', [
            'current_password'      => 'passwordsalah123',
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('passwordlama123', $admin->fresh()->password));
    }

    /** @test */
    public function ubah_password_gagal_jika_konfirmasi_tidak_cocok()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->put('/profil', [
            'current_password'      => 'passwordlama123',
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordberbeda123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('passwordlama123', $admin->fresh()->password));
    }

    /** @test */
    public function ubah_password_gagal_jika_password_baru_kurang_dari_8_karakter()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->put('/profil', [
            'current_password'      => 'passwordlama123',
            'password'              => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('passwordlama123', $admin->fresh()->password));
    }

    /** @test */
    public function ubah_password_gagal_jika_password_baru_sama_dengan_password_saat_ini()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->put('/profil', [
            'current_password'      => 'passwordlama123',
            'password'              => 'passwordlama123',
            'password_confirmation' => 'passwordlama123',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('passwordlama123', $admin->fresh()->password));
    }

    /** @test */
    public function user_berhasil_mengubah_kata_sandi_dan_dapat_login_dengan_kata_sandi_baru()
    {
        $admin = User::factory()->create([
            'role_id'  => $this->adminRole->id,
            'email'    => 'admin@plnnusantarapower.co.id',
            'password' => bcrypt('passwordlama123'),
        ]);

        $response = $this->actingAs($admin)->put('/profil', [
            'current_password'      => 'passwordlama123',
            'password'              => 'passwordbaru123',
            'password_confirmation' => 'passwordbaru123',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('passwordbaru123', $admin->fresh()->password));

        // Verifikasi bahwa user bisa login dengan password yang baru diubah
        auth()->logout();

        $loginResponse = $this->post('/login', [
            'email'    => 'admin@plnnusantarapower.co.id',
            'password' => 'passwordbaru123',
        ]);

        $loginResponse->assertRedirect('/home');
        $this->assertAuthenticatedAs($admin);
    }
}
