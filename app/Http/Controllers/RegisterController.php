<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Mail\RegistrationSuccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{

    public function create()
    {
        return view('register');  
    }

    public function store(Request $request)
    {
        // 🔒 1. Validate input
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'digits_between:10,15', 'unique:users,phone'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        // 👤 2. Create user with is_deleted = 'N'
        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'password'   => Hash::make($validated['password']),
            'is_deleted' => 'N',
        ]);

        // ✉️ 3. Optional: Send confirmation mail (if enabled)
        // Mail::to($user->email)->queue(new RegistrationSuccessMail($user));

        // ✅ 4. Redirect to login with a success message
        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please log in.');
    }
}
