<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Auth\Events\Logout;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pengguna_dapat_logout()
    {
        // Fake the event dispatcher
        Event::fake();

        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Perform logout
        $this->post('/logout'); // Adjust if your logout route is different

        // Assert that Logout event was dispatched
        Event::assertDispatched(Logout::class, function ($event) use ($user) {
            return $event->user->is($user) && $event->guard === 'web';
        });

        // Assert user is logged out
        $this->assertGuest();
    }
}
