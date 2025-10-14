<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email é obrigatório',
            'email.email' => 'Insira um email válido',
            'password.required' => 'Senha é obrigatória',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credencias invalidas'], 401);
        }

        $user = Auth::user();

        $expiration = now()->addHours(6);

        $token = $user->createToken(
            'access_token',
            ['*'],
            $expiration
        );

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiration->toDateTimeString(),
        ]);
    }
}
