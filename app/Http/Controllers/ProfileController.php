<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('pages.viewProfile', compact('user'));
    }


    public function show()
    {
        $user = Auth::user();

        // Pass user data to the view
        return view('pages.showProfile', compact('user'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update user data
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('pages.viewProfile', compact('user'));
    }
}
