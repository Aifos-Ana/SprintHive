@extends('layouts.app')

@section('content')
    <style>
        .container2 {
            display: flex;
            width: 100%;
        }
    </style>

<body>
    <div class="container2">
        <aside class="sidebar">
            <ul>
                <li><a href="{{ route('projects.tasks', $project->projectid) }}">Tasks</a></li>
                <li>Forum</li>
                <li>Team</li>
                <li>Timeline</li>
            </ul>
        </aside>

        <main class="main-content">
            <header>
                <h1>{{ $project->name }}</h1>
                <div class="project-actions">
                    <a href="{{ route('projects.edit', ['id' => $project->projectid]) }}" class="btn btn-dark">
                        Edit Project
                    </a>
                    <!-- Favorite/Remove Favorite Button -->
                    @if($isFavorite)
                        <form method="POST" action="{{ route('projects.removeFromFavorites', ['id' => $project->projectid]) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove Favorite</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('projects.addToFavorites', ['id' => $project->projectid]) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-primary">Add to Favorite</button>
                        </form>
                    @endif
                </div>
            </header>

            <section class="project-info">
                <div class="description">
                    <h3>Description</h3>
                    <p>{{ $project->description }}</p>
                </div>
                <div class="status">
                    <h3>Status</h3>
                    <p>{{ $project->status ?? 'Ongoing' }}</p>
                </div>
                <div class="team-members">
                    <h3>Team Members</h3>
                    <ul>
                        @foreach ($project->users as $user)
                            <li>{{ $user->name }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <!-- Task List Section -->
            <section class="tasks">
            <section class="tasks">
    <h2>Tasks</h2>
    <form method="GET" action="{{ route('projects.show', ['id' => $project->projectid]) }}">
        <div class="input-group mb-3">
            <input 
                type="text" 
                name="search" 
                placeholder="Search task" 
                class="form-control" 
                value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-dark">Search</button>
        </div>
    </form>

    <div class="task-list">
        @if ($project->tasks && $project->tasks->isNotEmpty())
            @foreach ($project->tasks as $task)
                <div class="task p-3 my-3 shadow-sm rounded bg-light">
                    <h3><a href="{{ route('tasks.show', ['id' => $task->taskid]) }}" class="nav-link p-0">
                        {{ $task->title }}
                    </a></h3>
                    <p><strong>Description:</strong> {{ $task->description }}</p>
                    <p><strong>Priority:</strong> 
                    <span class="badge 
                        {{ $task->priority == 'High' ? 'bg-danger' : ($task->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ ucfirst($task->priority) }}
                    </span></p>
                    <p><strong>Due:</strong> {{ \Carbon\Carbon::parse($task->dueDate)->format('d M, Y') }}</p>
                </div>
            @endforeach
        @else
            <p>No tasks found.</p>
        @endif
    </div>
                <!-- Create Task Form -->
                <form action="{{ route('tasks.store', $project->projectid) }}" method="POST" class="create-task my-4 p-3 border rounded bg-light">
                    @csrf

                    <div class="mb-3">
                        <label for="title" class="form-label">Task Name</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Task Name" value="{{ old('title') }}" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Task Description">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <div>
                            <label class="form-check-label me-3">
                                <input type="radio" name="priority" value="Low" class="form-check-input" {{ old('priority') == 'Low' ? 'checked' : '' }}> Low
                            </label>
                            <label class="form-check-label me-3">
                                <input type="radio" name="priority" value="Medium" class="form-check-input" {{ old('priority') == 'Medium' ? 'checked' : '' }}> Medium
                            </label>
                            <label class="form-check-label">
                                <input type="radio" name="priority" value="High" class="form-check-input" {{ old('priority') == 'High' ? 'checked' : '' }}> High
                            </label>
                        </div>
                        @error('priority')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="duedate" class="form-label">Due Date</label>
                        <input type="date" id="duedate" name="due_date" class="form-control" value="{{ old('due_date') }}" required>
                        @error('due_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Create Task</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
@endsection