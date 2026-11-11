<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_dapat_menambah_user_baru()
    {
        $adminRole = Role::factory()->create(['role' => 'admin']);
        $userRole = Role::factory()->create(['role' => 'user']);

        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);

        $this->actingAs($admin);

        $newUserData = [
            'name' => 'User Baru',
            'email' => 'userbaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $userRole->id,
        ];

        $response = $this->post(route('user.store'), $newUserData);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success', 'User berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'User Baru',
            'email' => 'userbaru@example.com',
            'role_id' => $userRole->id,
        ]);
    }
}
