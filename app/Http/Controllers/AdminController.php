<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function dashboard()
    {
        // Get the current date
        $currentDate = Carbon::today()->toDateString();

        // Fetch all users with their transactions for the current day
        $users = User::with(['transactions' => function ($query) use ($currentDate) {
            $query->whereDate('created_at', $currentDate);
        }])->get();

        return view('admin.dashboard', compact('users', 'currentDate'));
    }
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        // Validate input data including password confirmation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',  // This will handle the password confirmation
        ]);

        // Create the new user
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),  // Hash the password before storing it
            ]);

            // Redirect to login with a success message
            return redirect()->route('admin.reg')->with('success', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            // In case of error, return to register page with error message
            return redirect()->route('admin.reg')->with('error', 'An error occurred. Please try again.');
        }
    }
}
