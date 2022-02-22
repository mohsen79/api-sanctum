<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|min:3|max:30',
                'family' => 'required|string|min:3|max:30',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|max:30|confirmed',
            ]
        );
        $data["password"] = Hash::make($data["password"]);
        $user = User::create($data);
        $token = $user->createToken('user-token')->plainTextToken;
        $message = ['message' => 'you have registerd', 'token' => $token];
        return response()->json($message, 201);
    }

    public function login(Request $request)
    {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            throw new ModelNotFoundException('you do not have an account,please register');
        }
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|min:8|max:30',
            ]
        );
        $user = User::whereEmail($request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user-token')->plainTextToken;
            $message = ['message' => 'welcome', 'token' => $token];
            return response()->json($message, 200);
        } else {
            return response()->json(['message' => 'email and password does not match'], 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'logged out'
        ], 200);
    }
}
