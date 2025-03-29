<?php

namespace App\Services\Auth\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Auth\Features\V1\LoginFeature;
use App\Services\Auth\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request as HttpRequest;

class AuthController extends Controller
{
    public function login(UserLoginRequest $request, LoginFeature $feature)
    {
        return $feature->serve($request);
    }

    public function logout(HttpRequest $request)
    {
        $user = $request->user(); // Get the authenticated user
        $user->currentAccessToken()->delete(); // Delete the current token

        return response()->json(['message' => 'Logged out ' . $user->email]);
    }
}
