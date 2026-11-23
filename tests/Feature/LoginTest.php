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

        // Buat role manager (id: 1) dan admin (id: 2)
        Role::create(['id' => 1, 'role' => 'manager']);
        Role::create(['id' => 2, 'role' => 'admin']);
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
    public function admin_dapat_melakukan_login_dengan_email_dan_password_yang_benar()
    {
        $roleAdmin = Role::where('role', 'admin')->first();

        $admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function manager_dapat_melakukan_login_dengan_email_dan_password_yang_benar()
    {
        $roleManager = Role::where('role', 'manager')->first();

        $manager = User::factory()->create([
            'role_id' => $roleManager->id,
            'email' => 'manager@example.com',
            'password' => bcrypt('manager123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'manager@example.com',
            'password' => 'manager123',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($manager);
    }

    /** @test */
    public function manager_tidak_dapat_login_dengan_password_yang_salah()
    {
        $roleManager = Role::where('role', 'manager')->first();

        $manager = User::factory()->create([
            'role_id' => $roleManager->id,
            'email' => 'manager@example.com',
            'password' => bcrypt('manager123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'manager@example.com',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
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

    /** @test */
    public function manager_dapat_mengakses_manager_dashboard()
    {
        $roleManager = Role::where('role', 'manager')->first();

        $manager = User::factory()->create([
            'role_id' => $roleManager->id,
        ]);

        $response = $this->actingAs($manager)->get('/home');
        $response->assertStatus(200);
    }
}
