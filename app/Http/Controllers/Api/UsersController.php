<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Login user and create token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['error' => 'Invalid login info.'], 401);
        }

        $user = User::where('email', $request->email)->select('id', 'name', 'email')->first();
        $token = $user->createToken('app');

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user->getAttributes()
        ]);
    }

    /**
     * Get list of users
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function users(Request $request)
    {
        $users = User::select('id', 'name', 'email')->get()->toArray();
        return response()->json(['users' => $users]);
    }

    /**
     * Logout user (revoke token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Token revoked']);
    }
}
