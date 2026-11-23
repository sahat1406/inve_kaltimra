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
    public function manager_dapat_menambah_user_baru()
    {
        $managerRole = Role::create(['id' => 1, 'role' => 'manager']);
        $adminRole = Role::create(['id' => 2, 'role' => 'admin']);

        $manager = User::factory()->create([
            'role_id' => $managerRole->id,
        ]);

        $this->actingAs($manager);

        $newUserData = [
            'name' => 'User Baru',
            'email' => 'userbaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $adminRole->id,
        ];

        $response = $this->post(route('user.store'), $newUserData);

        $response->assertRedirect(route('user.index'));
        $response->assertSessionHas('success', 'User berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'User Baru',
            'email' => 'userbaru@example.com',
            'role_id' => $adminRole->id,
        ]);
    }
}
