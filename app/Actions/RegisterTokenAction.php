<?php

namespace App\Actions;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RegisterTokenAction
{
    /**
     * Handle the request to either show the registration form or process registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handle(Request $request)
    {
        if ($request->isMethod('get')) {
            $token = $request->query('token');

            if (empty($token)) {
                return Redirect::route('login')->with('error', 'Invalid token.');
            }

            $userToken = Token::where('token', $token)
                ->where('is_used', 0)
                ->where('expiry', '>=', Carbon::now())
                ->first();

            if ($userToken) {
                return view('register', ['user' => $userToken]);
            } else {
                return Redirect::route('login')->with('error', 'Expired token.');
            }
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'email' => 'required|email',
                'name' => 'required',
                'password' => 'required|confirmed|min:8',
            ]);

            $token = $request->input('token');
            $email = $request->input('email');

            $userToken = Token::where('token', $token)
                ->where('email', $email)
                ->where('is_used', 0)
                ->where('expiry', '>=', Carbon::now())
                ->first();

            if (!$userToken) {
                return Redirect::back()->with('error', 'Invalid or expired token.');
            }

            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => Hash::make($request->input('password')),
            ]);

            $userToken->update(['is_used' => 1]);

            Auth::login($user);

            return Redirect::route('todos.index')->with('success', 'Registration successful and logged in.');
        }
    }
}
