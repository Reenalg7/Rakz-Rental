<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',

        'email' => [
            'required',
            'email',
            'max:255',
            \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
        ],

        'current_password' => 'required',

        'password' => 'nullable|min:6|confirmed',
    ], [
        'current_password.required' => 'Please enter your current password.',
    ]);

    // Check current password
    if (!Hash::check($request->current_password, $user->password)) {

        return back()
            ->withInput()
            ->withErrors([
                'current_password' => 'The password you entered is incorrect.',
            ]);
    }

    $user->name = $request->name;
    $user->email = $request->email;

    // Only change password if a new password was entered
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()
        ->route('profile.show')
        ->with('success', 'Profile updated successfully.');
    }
}