<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\FavouriteProject;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch the user's favorite projects
        $favoriteProjects = FavouriteProject::where('id', $user->id)
            ->join('projects', 'favourite_project.projectid', '=', 'projects.projectid')
            ->select('projects.*')
            ->get();

        // Fetch the projects the user is assigned to
        $myProjects = Project::join('user_project', 'projects.projectid', '=', 'user_project.projectid')
            ->where('user_project.userid', $user->id)
            ->select('projects.*')
            ->get();

        return view('projects.index', compact('favoriteProjects', 'myProjects'));
    }

    /**
     * Show the details of a specific project.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $search = $request->query('search');
        
        $project = Project::with(['tasks' => function ($query) use ($search) {
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }
        }])->findOrFail($id);
    
        $user = Auth::user();
        $isFavorite = FavouriteProject::where('id', $user->id)
            ->where('projectid', $id)
            ->exists();
    
        if ($request->wantsJson()) {
            return response()->json([
                'project' => $project->only(['projectid', 'name', 'description']),
                'tasks' => $project->tasks,
            ]);
        }
    
        return view('pages.projectDetails', [
            'project' => $project,
            'isFavorite' => $isFavorite,
            'search' => $search,
        ]);
    }
    
    
    /**
     * Mark a project as a favorite.
     *
     * @param int $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToFavorites($projectId)
    {
        $user = Auth::user();

        // Check if the project is already a favorite for this user
        $exists = FavouriteProject::where('id', $user->id)
            ->where('projectid', $projectId)
            ->exists();

        if ($exists) {
            return redirect()->route('projects.show', $projectId)
                ->with('info', 'This project is already in your favorites.');
        }

        // Add the project to the user's favorites
        FavouriteProject::create([
            'id' => $user->id,
            'projectid' => $projectId,
            'addeddate' => now(),
        ]);

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Project added to your favorites.');
    }

    /**
     * Remove a project from favorites.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromFavorites($id)
    {
        $user = Auth::user();

        FavouriteProject::where('id', $user->id)->where('projectid', $id)->delete();

        return redirect()->route('projects.show', $id)->with('success', 'Project removed from favorites!');
    }

    /**
     * Edit a specific project.
     *
     * @param int $projectId
     * @return \Illuminate\Http\Response
     */
    public function edit($projectId)
    {
        $project = Project::findOrFail($projectId);

        return view('pages.editProject', compact('project'));
    }

    /**
     * Update a specific project.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Show the project creation form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.createProjects');
    }

    /**
     * Store a newly created project in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'id' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project->projectid)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Delete a specific project.
     *
     * @param int $projectId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($projectId)
    {
        $project = Project::findOrFail($projectId);

        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('home')->with('success', 'Project deleted successfully.');
    }
}
