<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{ 
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'age' => 'required|integer',
            'password' => 'required|min:4|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'User registered successfully'], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
        'email' => 'required|string|email',
        'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password))
        {
        return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken;
        return response()->json(['token' => $token], 200);
    }

    
    public function userDetails(Request $request)
    {
        $user = $request->user()->load('roles');
        return response()->json([
            'user' => $user,
        ]);
    }


    public function logout(Request $request)
    {$request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
