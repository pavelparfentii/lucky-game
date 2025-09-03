<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Link;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        // Check if there's an error message from token validation
        $error = $request->session()->get('error');

        return view('home');
    }

    /**
     * Обробити форму реєстрації та створити унікальне посилання
     */
    public function register(UserRequest $request)
    {

        $validated = $request->validated();

        try {

            $user = User::create($validated);

            // generate unique token
            $uniqueToken = $this->generateUniqueToken($user);

            $url = route('link.show', ['token' => $uniqueToken]);

            return redirect()->back()->with([
                'success' => 'Success, your link is created',
                'unique_token' => $uniqueToken,
                'link' => $url,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error occurred. Try again later'])
                ->withInput();
        }
    }

    /**
     * Генерувати унікальний токен
     */
    private function generateUniqueToken(User $user): string
    {

        $link = $user->generateLink();

        return $link->token;
    }
}
