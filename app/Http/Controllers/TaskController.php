<?php
namespace App\Http\Controllers;

use App\Models\Task; // Ensure you import the Task model
use Illuminate\Http\Request;
use App\Models\Project;

class TaskController extends Controller
{
    public function show($id)
    {
        // Fetch the task by ID
        $task = Task::findOrFail($id);

        // Return the task details view with the task data
        return view('pages.viewTasks', compact('task'));
    }

    // Show the task creation form (optional if using the above blade)
    public function create($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('tasks.create', compact('project'));
    }


    // Store the new task
    public function store(Request $request, $projectid)
{
    // Validate the request input
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'priority' => 'required|in:Low,Medium,High',
        'due_date' => 'required|date',
    ]);

    // Create a new task and associate it with the project
    $task = new Task();
    $task->title = $validated['title'];
    $task->description = $validated['description'];
    $task->priority = $validated['priority'];
    $task->duedate = $validated['due_date'];
    $task->projectid = $projectid;


    // Save the task
    if ($task->save()) {
        \Log::info('Task saved successfully');
    } else {
        \Log::error('Failed to save task');
    }

    // Redirect back to the project details page
    return redirect()->route('projects.show', $projectid)
                     ->with('success', 'Task created successfully!');
}

    public function edit($id){
    $task = Task::findOrFail($id); // Find the task by ID
    return view('pages.editTaskDetails', compact('task')); // Return the edit form view
    }

    public function update(Request $request, $taskid)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|string|in:High,Medium,Low',
            'duedate' => 'required|date',
        ]);
    
        $task = Task::findOrFail($taskid);
        $task->title = $validated['title'];
        $task->description = $validated['description'];
        $task->priority = $validated['priority'];
        $task->duedate = $validated['duedate'];
        $task->save();
    
        return redirect()->route('tasks.show', $task->taskid)->with('success', 'Task updated successfully!');
    }
    

public function destroy($id)
{
    $task = Task::findOrFail($id); // Find the task by ID
    $task->delete(); // Delete the task

    return redirect()->route('projects.show', $task->projectid)->with('success', 'Task deleted successfully!');
}


}

