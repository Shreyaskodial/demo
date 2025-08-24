<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ✅ This line is required!
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

 public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect based on role
        if ($user->role == 1) {
            // Admin → main dashboard   
            return redirect()->intended('/dashboard');
        }
        
         elseif ($user->role == 2) {
            return redirect()->intended('/user/dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->onlyInput('email');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
