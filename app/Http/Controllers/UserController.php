<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /*--------------------------------------------------------------
    |  DASHBOARD
    *-------------------------------------------------------------*/
    /** Show all active users */
    public function index()
    {
        // scopeActive() is optional; see bottom
        $users = User::active()->latest('id')->get();

        return view('users.index', compact('users'));
    }

    /*--------------------------------------------------------------
    |  CREATE
    *-------------------------------------------------------------*/
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'min:6'],
        ]);

        User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'is_deleted' => 'N',
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /*--------------------------------------------------------------
    |  EDIT / UPDATE
    *-------------------------------------------------------------*/
    public function edit(User $user)
    {
        abort_if($user->is_deleted === 'Y', 404);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'min:6'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')           // change to dashboard if you prefer
            ->with('success', 'User updated successfully.');
    }

    /*--------------------------------------------------------------
    |  SINGLE SOFT‑DELETE (sets is_deleted = 'Y')
    *-------------------------------------------------------------*/
    public function destroy(User $user)
    {
        $user->is_deleted = 'Y';   // <- guarantees it’s saved
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User marked as deleted.');
    }

    /*--------------------------------------------------------------
    |  BULK SOFT‑DELETE
    *-------------------------------------------------------------*/
    public function bulkSoftDelete(Request $request)
    {
        $request->validate([
            'user_ids'   => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        // query builder update bypasses $fillable, so it always works
        User::whereIn('id', $request->user_ids)
            ->update(['is_deleted' => 'Y']);

        return redirect()
            ->route('users.index')
            ->with('success', 'Selected users marked as deleted.');
    }

    /*--------------------------------------------------------------
    |  RESTORE
    *-------------------------------------------------------------*/
    public function restore($id)
    {
        $user = User::where('id', $id)->where('is_deleted', 'Y')->firstOrFail();
        $user->is_deleted = 'N';
        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User restored.');
    }
}
