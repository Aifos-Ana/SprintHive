<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\FavouriteProject;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the authenticated user

        if (!$user) {
            // Handle guest users (e.g., show a welcome page)
            return view('pages.home'); // Guest view
        }

        $userId = $user->id; // Retrieve the user's ID

        // Fetch the user's favorite projects
        $favoriteProjects = FavouriteProject::where('id', $userId)
            ->with('project') // Ensure related projects are fetched
            ->get();

        // Fetch the projects where the user is assigned (many-to-many relationship)
        $myProjects = Project::whereHas('users', function($query) use ($userId) {
            $query->where('users.id', $userId);  // Ensure we're checking if the user is assigned to the project
        })->get();

        // Fetch the user's upcoming tasks (tasks associated with projects Alice is a part of)
        $upcomingTasks = Task::whereHas('project', function ($query) use ($userId) {
            $query->whereHas('users', function ($query) use ($userId) {
                $query->where('users.id', $userId);  // Check if the user is assigned to the project
            });
        })
        ->orderBy('duedate', 'asc') // Ensure proper column casing
        ->paginate(3); // Paginate tasks (3 per page)

        // Pass data to the view
        return view('pages.home', compact('favoriteProjects', 'myProjects', 'upcomingTasks'));
    }
}
