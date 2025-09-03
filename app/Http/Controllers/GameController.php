<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Services\PlayService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    protected $link;
    protected $user;

    public function __construct(Request $request)
    {
        $this->link = $request->get('link');
        $this->user = $this->link?->user;
    }
    public function play(PlayService $playService)
    {

        $number = rand(1, 1000);

        $result = $playService->calculate($number);

        $game = Game::create([
            'user_id' => $this->user->id,
            'number'  => $result['number'],
            'result'  => $result['result'],
            'amount'  => $result['amount'],
        ]);

        return redirect()->back()->with([
            'number'  => $result['number'],
            'result'  => $result['result'],
            'amount'  => $result['amount'],
        ]);
    }

    public function history()
    {

        $games = Game::latestThree($this->user)
            ->get();
        $link = $this->link;

        return view('page.game_history', compact('games', 'link'));
    }
}
