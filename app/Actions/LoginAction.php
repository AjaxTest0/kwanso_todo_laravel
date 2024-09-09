<?php

namespace App\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class LoginAction
{
    use AsAction;

    /**
     * Handle the request to either show the login form or process login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handle(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->showLoginForm();
        }

        if ($request->isMethod('post')) {
            return $this->processLogin($request);
        }
    }

    private function showLoginForm()
    {
        return view('login');
    }

    private function processLogin(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect('/todos')->with('success', 'Logged in successfully.');
        }

        return Redirect::back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->except('password')); // Retain email input
    }

    private function validateLogin(Request $request): void
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }
}
