<?php

namespace Tests\Unit;

use App\Services\PlayService;
use Tests\TestCase;

class PlayServiceTest extends TestCase
{
    private PlayService $playService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->playService = new PlayService();
    }

    /**
     * Test win calculation with even number
     */
    public function testCalculateWinWithEvenNumber(): void
    {

        $result = $this->playService->calculate(100);

        $this->assertEquals(100, $result['number']);
        $this->assertEquals('Win', $result['result']);
        $this->assertEquals(10, $result['amount']); // 100 * 0.1 = 10
    }

    /**
     * Test lose calculation with odd number
     */
    public function testCalculateLoseWithOddNumber(): void
    {
        $result = $this->playService->calculate(101);

        $this->assertEquals(101, $result['number']);
        $this->assertEquals('Lose', $result['result']);
        $this->assertEquals(0, $result['amount']); // Lose = 0
    }

    /**
     * Test different multiplier ranges
     */
    public function testMultiplierRanges(): void
    {
        // Test different multiplier ranges

        // 0.1
        $result = $this->playService->calculate(200);
        $this->assertEquals(20, $result['amount']); // 200 * 0.1 = 20

        // 0.3
        $result = $this->playService->calculate(400);
        $this->assertEquals(120, $result['amount']); // 400 * 0.3 = 120

        // 0.5
        $result = $this->playService->calculate(700);
        $this->assertEquals(350, $result['amount']); // 700 * 0.5 = 350

        // 0.7 number > 900
        $result = $this->playService->calculate(950);
        $this->assertEquals(665, $result['amount']); // 950 * 0.7 = 665
    }

    /**
     * Test that odd numbers always result in zero winnings
     */
    public function testOddNumbersAlwaysResultInZeroWinnings(): void
    {
        // Test with odd numbers in different ranges
        $result1 = $this->playService->calculate(101); // <= 300
        $result2 = $this->playService->calculate(401); // 300-600
        $result3 = $this->playService->calculate(701); // 600-900
        $result4 = $this->playService->calculate(951); // > 900

        $this->assertEquals(0, $result1['amount']);
        $this->assertEquals(0, $result2['amount']);
        $this->assertEquals(0, $result3['amount']);
        $this->assertEquals(0, $result4['amount']);
    }
}
