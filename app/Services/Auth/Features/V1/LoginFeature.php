<?php

namespace App\Services\Auth\Features\V1;

use App\Foundation\Composables\Features\_Features;
use App\Data\Models\User;
use App\Services\Auth\Http\Requests\UserLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginFeature extends _Features
{
    public function __construct() {}

    /**
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function serve(UserLoginRequest $request): JsonResponse
    {


        $user = User::where('email', $request->getEmail())->first();

        if (! $user || ! Hash::check($request->getPassword(), $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json(['token' => $user->createToken('api-token')->plainTextToken]);
    }
}
