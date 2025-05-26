<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        return view('register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $request->validate(
            [
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    'unique:users,username',
                    'regex:/^[a-zA-Z0-9_]+$/'
                ],
                'name'     => 'required|string|max:255',
                'email'    => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed', // password confirmation check
            ],
            [
                'username.regex' => 'Username must contain only letters, numbers, and underscores.',
                'username.unique' => 'This username is already taken.',
                'email.unique'    => 'This email is already registered.',
                'password.confirmed' => 'Password confirmation does not match.',
            ]
        );

        try {
            $user = User::create([
                'username'       => $request->username,
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'wallet'         => null,
                'wallet_balance' => 500.00,
            ]);

            // Optional: auto-login user immediately after registration
            // Auth::login($user);

            return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
        } catch (QueryException $e) {
            \Log::error('Registration Error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                if (str_contains($e->getMessage(), 'users_username_unique')) {
                    return back()->with('error', 'This username is already taken.')->withInput();
                }
                if (str_contains($e->getMessage(), 'users_email_unique')) {
                    return back()->with('error', 'This email is already registered.')->withInput();
                }
            }

            return back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('login');
    }

    // Handle user login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', trim($request->username))->first();

        if (!$user) {
            return back()->with('error', 'User not found with this username.')->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Incorrect password.')->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('feed')->with('success', 'Logged in successfully!');
    }

    // Handle user logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
