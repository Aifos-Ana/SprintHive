@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-5 text-center">Task Details</h1>

    <div class="card p-3 my-3 shadow-sm rounded bg-light">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ $task->title }}</h2>

            <p class="mb-3">
                <strong>Description:</strong>
                <span class="text-muted">{{ $task->description }}</span>
            </p>

            <p class="mb-3">
                <strong>Priority:</strong>
                <span class="badge 
                    {{ $task->priority == 'High' ? 'bg-danger' : ($task->priority == 'Medium' ? 'bg-warning text-dark' : 'bg-success') }}">
                    {{ ucfirst($task->priority) }}
                </span>
            </p>

            <p class="mb-4">
                <strong>Due Date:</strong>
                <span class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('d M, Y') }}</span>
            </p>

            <div class="d-flex justify-content-between flex-wrap">
                <!-- Edit Task Button -->
                <a href="{{ route('tasks.edit', ['id' => $task->taskid]) }}" class="btn btn-primary px-4">
                <i class="fas fa-edit me-2"></i> Edit Task
                </a>

                <!-- Mark as Completed Button -->
                @if($task->status != 'completed')
                    <form method="POST" action="{{ route('tasks.update', ['id' => $task->taskid]) }}" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle"></i> Mark as Completed
                        </button>
                    </form>
                @endif

                <!-- Delete Task Button -->
                <form method="POST" action="{{ route('tasks.destroy', ['id' => $task->taskid]) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4">
                    <i class="fas fa-trash-alt me-2"></i> Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
