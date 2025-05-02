<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        // dump everything for debugging
        // dd($request->all());

        // validate
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'terms'    => 'accepted',
        ]);

        // create user
        $user = User::create([
            'name' => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            // role & status defaulted in migration
        ]);

        // log them in
        Auth::login($user);

        // redirect
        return redirect()->intended(route('dashboard'));
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (! Auth::user()->status) {
                Auth::logout();
                return back()->withErrors(['email'=>'Your account is inactive.']);
            }
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email'=>'Invalid credentials.']);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
