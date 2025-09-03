<?php

namespace App\Services;

class PlayService
{
    public const RESULT_WIN = 'Win';
    public const RESULT_LOSE = 'Lose';
    public function calculate(int $number): array
    {
        $isWin = $number % 2 === 0;
        $amount = $isWin ? $number * $this->getMultiplier($number) : 0;
        return [
            'number' => $number,
            'result' => $isWin ? self::RESULT_WIN : self::RESULT_LOSE,
            'amount' => $amount
        ];
    }
    private function getMultiplier(int $number): float {
        return
            match (true) {
        $number > 900 => 0.7,
        $number > 600 => 0.5,
        $number > 300 => 0.3,
        default => 0.1,
        };
    }
}
