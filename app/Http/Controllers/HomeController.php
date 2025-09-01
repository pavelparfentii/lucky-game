<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{

    public function index()
    {
        return view('home');
    }

    /**
     * Обробити форму реєстрації та створити унікальне посилання
     */
    public function register(UserRequest $request)
    {
        // Валідація даних
        $validated = $request->validate();

        try {
            // Створюємо користувача
            $user = User::create([
                'username' => $validated['username'],
                'phone_number' => $validated['phone_number'],
                'email' => null, // Email не потрібен для нашого завдання
            ]);

            // Генеруємо унікальний токен для посилання
            $uniqueToken = $this->generateUniqueToken();

            // Тут буде логіка створення Link після створення відповідної моделі
            // Поки що повертаємо success з токеном

            return redirect()->back()->with([
                'success' => 'Реєстрація успішна! Ваше унікальне посилання створено.',
                'unique_token' => $uniqueToken,
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Сталася помилка під час реєстрації. Спробуйте ще раз.'])
                ->withInput();
        }
    }

    /**
     * Генерувати унікальний токен
     */
    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(32);
            // Перевіряємо унікальність токена (поки що просто генеруємо)
            // Після створення моделі UniqueLink тут буде перевірка в БД
        } while (false); // Поки що завжди унікальний

        return $token;
    }
}
