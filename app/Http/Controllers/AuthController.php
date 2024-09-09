<?php

namespace App\Http\Controllers;

use Date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{

    public function showRegisterForm(Request $request, Token $tokenModel)
    {
        $token = $request->query('token');

        if (empty($token)) {
            return redirect()->route('login')->with('error', 'Invalid token.');
        }

        $userToken = $tokenModel->where('token', $token)
            ->where('is_used', 0)
            ->where('expiry', '>=', Carbon::now())
            ->first();

        if ($userToken) {
            return view('register', ['user' => $userToken]);
        } else {
            return redirect()->route('login')->with('error', 'Expired token.');
        }
    }

    public function register(Request $request)
    {
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
            return redirect()->back()->with('error', 'Invalid or expired token.');
        }

        $user = User::create([
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
        ]);

        $userToken->update(['is_used' => 1]);

        Auth::login($user);

        return redirect()->route('todos.index')->with('success', 'Registration successful and logged in.');
    }


    public function showLoginForm()
    {
        return view('login');
    }

    public function showTokenForm()
    {
        return view('token');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/todos');
        }

        return redirect()->back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        $token = Token::where('email', $email)->first();

        if ($token) {
            $token->expiry = Carbon::now()->addHours(24);
            $token->is_used = false;
            $token->save();
            $msg = 'Token has been Created successfully.';
            $Usertoken = $token->token;
        } else {
            $Usertoken = Str::random(60);
            Token::create([
                'email' => $email,
                'expiry' => Carbon::now()->addHours(24),
                'token' => $Usertoken,
                'is_used' => false,
            ]);
            $msg = 'Token has been updated successfully.';
        }

        return redirect()->back()->with('success', $msg);
    }
    public function generateTokenView()
    {
        $userTokens = Token::orderByDesc('id')->get();
        return view('generate', compact('userTokens'));
    }
}
