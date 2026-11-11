<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role admin dan user
        Role::create(['role' => 'admin']);
        Role::create(['role' => 'user']);
    }

    /** @test */
    public function user_dapat_melihat_laman_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /** @test */
    public function user_tidak_dapat_melakukan_login_dengan_email_dan_password_yang_salah()
    {
        $response = $this->post('/login', [
            'email' => 'salah@example.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    /** @test */
    public function user_dapat_melakukan_login_dengan_email_dan_password_yang_benar()
    {
        $roleUser = Role::where('role', 'user')->first();

        $user = User::factory()->create([
            'role_id' => $roleUser->id,
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function admin_dapat_melakukan_login_dengan_email_dan_password_yang_benar()
    {
        $roleAdmin = Role::where('role', 'admin')->first();

        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function admin_tidak_dapat_login_dengan_password_yang_salah()
    {
        $roleAdmin = Role::where('role', 'admin')->first();

        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }


    /** @test */
    public function user_dapat_mengakses_user_dashboard()
    {
        $roleUser = Role::where('role', 'user')->first();

        $user = User::factory()->create([
            'role_id' => $roleUser->id,
        ]);

        $response = $this->actingAs($user)->get('/home');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_dapat_mengakses_admin_dashboard()
    {
        $roleAdmin = Role::where('role', 'admin')->first();

        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
        ]);

        $response = $this->actingAs($admin)->get('/home');
        $response->assertStatus(200);
    }
}
