<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Link;
use App\Models\User;
use App\Services\PlayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Link $link;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create([
            'username' => 'testuser',
            'phone_number' => '+380123456789'
        ]);

        // Create an active link for the user
        $this->link = Link::create([
            'user_id' => $this->user->id,
            'token' => 'test-token-' . uniqid(),
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);
    }

    /**
     * Test the play endpoint.
     *
     * Note: This test uses mocking to ensure predictable results.
     */
    public function testPlayEndpoint(): void
    {
        // Mock the PlayService to return a predictable result
        $this->mock(PlayService::class, function ($mock) {
            $mock->shouldReceive('calculate')
                ->once()
                ->andReturn([
                    'number' => 500,
                    'result' => 'Win',
                    'amount' => 150, // 500 * 0.3
                ]);
        });

        // Call the play endpoint with the link token
        $response = $this->withSession(['_token' => csrf_token()])
            ->post("/link/{$this->link->token}/play", [
                'link' => $this->link,
            ]);

        // Check that we are redirected back
        $response->assertStatus(302);

        // Check that a game record was created in the database
        $this->assertDatabaseHas('games', [
            'user_id' => $this->user->id,
            'number' => 500,
            'result' => 'Win',
            'amount' => 150,
        ]);

        // Check that the session has the correct values
        $response->assertSessionHas('number', 500);
        $response->assertSessionHas('result', 'Win');
        $response->assertSessionHas('amount', 150);
    }

    /**
     * Test the history endpoint.
     */
    public function testHistoryEndpoint(): void
    {
        // Create a few games for the user
        for ($i = 0; $i < 3; $i++) {
            Game::create([
                'user_id' => $this->user->id,
                'number' => rand(1, 1000),
                'result' => $i % 2 === 0 ? 'Win' : 'Lose',
                'amount' => $i % 2 === 0 ? rand(10, 100) : 0,
            ]);
        }

        // Call the history endpoint
        $response = $this->get("/link/{$this->link->token}/history");

        // Check that the response is successful
        $response->assertSuccessful();

        // Check that the view is returned with the games
        $response->assertViewIs('page.game_history');
        $response->assertViewHas('games');
        $response->assertViewHas('link');

        // Check that the correct number of games is returned
        $games = $response->viewData('games');
        $this->assertEquals(3, $games->count());

        // Check that the link is correct
        $link = $response->viewData('link');
        $this->assertEquals($this->link->id, $link->id);
    }
}
