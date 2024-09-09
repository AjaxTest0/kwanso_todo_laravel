<?php

namespace App\Actions;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class RegisterTokenAction
{
    use AsAction;

    public function handle(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->showRegistrationForm($request);
        }

        if ($request->isMethod('post')) {
            return $this->processRegistration($request);
        }
    }

    private function showRegistrationForm(Request $request)
    {
        $token = $request->query('token');

        if (empty($token)) {
            return Redirect::route('login')->with('error', 'Invalid token.');
        }

        $userToken = $this->validateToken($token);

        if (!$userToken) {
            return Redirect::route('login')->with('error', 'Expired or invalid token.');
        }

        return view('register', ['user' => $userToken]);
    }

    private function processRegistration(Request $request)
    {
        $this->validateRegistration($request);

        $userToken = $this->validateToken($request->input('token'), $request->input('email'));

        if (!$userToken) {
            return Redirect::back()->with('error', 'Invalid or expired token.');
        }

        $user = $this->createUser($request);

        $this->markTokenAsUsed($userToken);

        Auth::login($user);

        return Redirect::route('todos.index')->with('success', 'Registration successful and logged in.');
    }

    private function validateToken(string $token, string $email = null): ?Token
    {
        $query = Token::where('token', $token)
            ->where('is_used', 0)
            ->where('expiry', '>=', Carbon::now());

        if ($email) {
            $query->where('email', $email);
        }

        return $query->first();
    }

    private function createUser(Request $request): User
    {
        return User::create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);
    }

    private function markTokenAsUsed(Token $userToken): void
    {
        $userToken->update(['is_used' => 1]);
    }

    private function validateRegistration(Request $request): void
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
            'token' => 'required|string',
        ]);
    }
}
