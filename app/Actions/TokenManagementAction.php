<?php

namespace App\Actions;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class TokenManagementAction
{
    /**
     * Handle the request to generate or view tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handle(Request $request)
    {
        if ($request->isMethod('post')) {
            // Handle token generation or updating
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = $request->input('email');
            $token = Token::where('email', $email)->first();

            if ($token) {
                $token->expiry = Carbon::now()->addHours(24);
                $token->is_used = false;
                $token->save();
                $Usertoken = $token->token;
                $msg = 'Token has been updated successfully.';
            } else {
                $Usertoken = Str::random(60);
                Token::create([
                    'email' => $email,
                    'expiry' => Carbon::now()->addHours(24),
                    'token' => $Usertoken,
                    'is_used' => false,
                ]);
                $msg = 'Token has been Created successfully.';
            }


            return Redirect::back()->with('success', $msg);
        }

        $userTokens = Token::orderByDesc('id')->get();
        return view('generate', compact('userTokens'));

        // if ($request->expectsJson()) {
        //     return response()->json([
        //         'tokens' => $userTokens,
        //     ]);
        // }
    }
}
