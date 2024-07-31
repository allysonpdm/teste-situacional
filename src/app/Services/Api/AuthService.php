<?php

namespace App\Services\Api;

use App\DataTransferObjects\Auth\{
    Credentials,
    Token
};
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(Credentials $credentials): Token
    {
        if (!$token = Auth::attempt($credentials->toArray())) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Authentication failed.');
        }

        return new Token($token);
    }

    public function refresh(): Token
    {
        $token = Auth::refresh(
            forceForever: true,
            resetClaims: true
        );

        return new Token($token);
    }

    public function me(): User
    {
        return Auth::user();
    }

    public function logout(): bool
    {
        Auth::invalidate(true);
        Auth::guard('api')->logout();

        return true;
    }
}
