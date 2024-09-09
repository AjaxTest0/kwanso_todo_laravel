<?php

namespace App\Actions;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class TokenManagementAction
{
    use AsAction;

    public function authorize(Request $request): bool
    {
        return $request->user()->role === 'ADMIN';
    }

    public function handle(Request $request)
    {
        if ($request->isMethod('post')) {
            return $this->handleTokenGeneration($request);
        }

        return $this->viewTokens($request);
    }

    private function handleTokenGeneration(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $token = Token::where('email', $email)->first();

        if ($token) {
            $this->updateToken($token);
            $msg = 'Token has been updated successfully.';
        } else {
            $token = $this->createToken($email);
            $msg = 'Token has been created successfully.';
        }

        return $this->respond($request, $token, $msg);
    }

    private function viewTokens(Request $request)
    {
        $userTokens = Token::orderByDesc('id')->get();

        if ($request->expectsJson()) {
            return response()->json(['tokens' => $userTokens], 200);
        }

        return view('generate', compact('userTokens'));
    }

    private function updateToken(Token $token): void
    {
        $token->update([
            'expiry' => Carbon::now()->addHours(24),
            'is_used' => false,
        ]);
    }

    private function createToken(string $email): Token
    {
        return Token::create([
            'email' => $email,
            'expiry' => Carbon::now()->addHours(24),
            'token' => Str::random(60),
            'is_used' => false,
        ]);
    }

    private function respond(Request $request, Token $token, string $msg)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $msg,
                'token' => $token->token,
            ], 200);
        }

        return Redirect::back()->with('success', $msg);
    }
}
