@extends('layouts.app')

@section('content')

<div class="container mt-5">
    @auth
        <div class="text-center mb-5">
            <h1 class="display-4">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="lead text-muted">Here’s an overview of your active projects and tasks.</p>
        </div>

        <!-- Projects Section -->
        <div class="projects-container">
            <!-- Favorite Projects -->
            <div class="favorite-projects mb-5">
                <h2 class="fw-bold">Favorite Projects</h2>
                @if($favoriteProjects->isEmpty())
                    <p class="text-muted">You don't have any favorite projects yet.</p>
                @else
                    @foreach($favoriteProjects as $favorite)
                        <div class="favorite-project p-3 my-3">
                            <span class="text-warning fs-4">⭐</span>
                            @if($favorite->project)
                                <h3>
                                <a href="{{ route('projects.show', ['id' => $favorite->project->projectid]) }}" class="text-decoration-none">
                                {{ $favorite->project->name }}
                                    </a>
                                </h3>
                                <p class="small text-dark mb-1">{{ $favorite->project->description }}</p>
                                <p class="small text-muted">Status: {{ $favorite->endDate ? 'Completed' : 'In Progress' }}</p>
                            @else
                                <p class="text-danger">Project data is unavailable.</p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- My Projects -->
            <div class="my-projects mb-5">
                <h2 class="fw-bold">My Projects</h2>
                <div class="text-center mb-2">
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg px-5 py-2 shadow">
                        <i class="fas fa-plus-circle me-2"></i>Create New Project
                    </a>
                </div>
                @if($myProjects->isEmpty())
                    <p class="text-muted">You don't have any projects yet.</p>
                @else
                    @foreach($myProjects as $project)
                        <div class="my-project p-3 my-3">
                            <a href="{{ route('projects.show', ['id' => $project->projectid]) }}" class="text-decoration-none">
                                <h3>{{ $project->name }}</h3>
                            </a>
                            <p class="small text-dark mb-1">{{ $project->description }}</p>
                            <p class="small text-muted">Status: {{ $project->endDate ? 'Completed' : 'In Progress' }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

<!-- Upcoming Tasks Section -->
<div class="upcoming-tasks">
    <h2 class="fw-bold">Upcoming Tasks</h2>
    @if($upcomingTasks->isEmpty())
        <p class="text-muted">No upcoming tasks.</p>
    @else
        @foreach($upcomingTasks as $task)
            <div class="task p-3 my-3 shadow-sm rounded bg-light">
                <h4>
                    <a href="{{ route('tasks.show', ['id' => $task->taskid]) }}" class="text-decoration-none">
                        {{ $task->title }}
                    </a>
                </h4>
                <p class="small text-dark mb-1">{{ $task->project->name }}</p>
                <p class="small text-dark mb-1">{{ $task->description }}</p>
                <p class="small text-dark mb-1"><strong>Due:</strong> {{ \Carbon\Carbon::parse($task->duedate)->format('d M, Y') }}</p>
                <p class="small text-dark">
                    <strong>Priority: </strong>
                    <span class="badge 
                        {{ $task->priority == 'High' ? 'bg-danger' : ($task->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                        {{ ucfirst($task->priority) }}
                    </span>
                </p>
            </div>
        @endforeach

        <!-- Pagination Links -->
        <div class="mt-4 justify-content-center">
             {{ $upcomingTasks->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>


    @else
        <div class="container text-center mt-5">
            <p class="fs-5">Please log in to access your projects.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 shadow">Login</a>
        </div>
    @endauth
</div>

@endsection
