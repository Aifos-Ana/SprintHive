<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function editProfile()
    {
        $user = Auth::user();

        // Determine the profile picture URL
        $profilepic = $user->profilepic 
        ? asset('storage/' . $user->profilepic . $user->id) 
        : asset('storage/images/default.png');

        return view('pages.viewProfile', compact('user', 'profilepic'));
    }

    /**
     * Update the user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
    
        // Validate incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'profilepic' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validation for profile picture
        ]);
    
        // Update user information
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
    
        // Handle profile picture upload
        if ($request->hasFile('profilepic')) {
            // Delete old profile picture if it's not the default
            if ($user->profilepic && $user->profilepic !== 'images/default.png') {
                Storage::disk('public')->delete($user->profilepic);
            }
    
            // Store the new profile picture
            $path = $request->file('profilepic')->store('images', 'public');
            $user->profilepic = $path;
        }
    
        $user->save();
    
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function verifyAdminPriviliges()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return response()->json(['isAdmin' => true], 200);
        }

        return response()->json(['isAdmin' => false], 403);
    }
}
