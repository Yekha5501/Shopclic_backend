<?php

// app/Http/Controllers/Auth/AdminLoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    // Show the admin login form
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    // Handle the admin login form submission
    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the admin
        if (Auth::attempt($credentials)) {
            // Check if the authenticated user is an admin
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard'); // Redirect to admin dashboard
            } else {
                Auth::logout(); // Log out non-admin users
                return back()->withErrors([
                    'email' => 'You do not have permission to access this area.',
                ]);
            }
        }

        // If login fails, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}