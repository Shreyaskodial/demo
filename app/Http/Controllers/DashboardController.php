<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;                    // ← add this
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
public function index()
{
    // Exclude users with role = 1 (admins)
    $users = User::where('role', '!=', 1)->get();

    return view('dashboard', compact('users'));
}

public function userDashboard()
{
    $user = Auth::user();

    // Check if the logged-in user is not soft-deleted
    if ($user->is_deleted === 'N') {
        return view('user_dashboard', compact('user'));
    }

    // If user is deleted, log them out or show an error
    Auth::logout();
    return redirect('/login')->withErrors(['email' => 'Your account has been deactivated.']);
}

}
