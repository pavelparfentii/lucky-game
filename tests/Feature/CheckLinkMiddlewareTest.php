<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckLinkMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test access to a protected route with a valid link.
     */
    public function testAccessWithValidLink(): void
    {
        $user = User::factory()->create();
        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'valid-token',
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get("/link/{$link->token}");

        $response->assertSuccessful();
    }

    /**
     * Test access to a protected route with an expired link.
     */
    public function testAccessWithExpiredLink(): void
    {
        $user = User::factory()->create();
        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'expired-token',
            'is_active' => true,
            'expires_at' => now()->subDays(1), // Expired yesterday
        ]);

        $response = $this->get("/link/{$link->token}");

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Link is missing or expired');
    }

    /**
     * Test access to a protected route with an inactive link.
     */
    public function testAccessWithInactiveLink(): void
    {
        $user = User::factory()->create();
        $link = Link::create([
            'user_id' => $user->id,
            'token' => 'inactive-token',
            'is_active' => false, // Inactive
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->get("/link/{$link->token}");

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Link is missing or expired');
    }

    /**
     * Test access to a protected route with a non-existent link.
     */
    public function testAccessWithNonExistentLink(): void
    {

        $response = $this->get("/link/abcd-token");

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Link is missing or expired');
    }
}
