<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
public function login(Request $request)
{
    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();
        
        // Delete any existing tokens for the user
        $user->tokens()->delete();
        
        // Mark user as online
     
        
        // Create a new token
        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token]);
    }

    // Authentication failed
    return response()->json(['error' => 'Unauthorized'], 401);
}



    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('token-name')->plainTextToken;

        return response()->json(['token' => $token]);
    }

public function logout(Request $request)
{
   
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out']);
}



}

