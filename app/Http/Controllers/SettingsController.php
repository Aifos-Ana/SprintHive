<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project; 
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Fetch all users and projects
        $users = User::all();
        $projects = Project::all();

        // Pass data to the view
        return view('pages.adminPanel', compact('users', 'projects'));
    }

    public function deleteUser($id)
{
    $user = User::find($id);

    if ($user && !$user->is_admin) {
        $user->delete();
        return redirect()->route('admin.panel')->with('success', 'User deleted successfully.');
    }

    return redirect()->route('admin.panel')->with('error', 'Admins cannot be deleted.');
}
}
