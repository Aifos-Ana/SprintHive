@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-4">Edit Task</h1>

    <form action="{{ route('tasks.update', ['id' => $task->taskid]) }}" method="POST">
    @csrf
        @method('PUT')

        <!-- Task Title -->
        <div class="mb-3">
            <label for="title" class="form-label">Task Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
        </div>

        <!-- Task Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ $task->description }}</textarea>
        </div>

        <!-- Task Priority -->
        <div class="mb-3">
            <label class="form-label">Priority</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="priority" value="High" {{ $task->priority === 'High' ? 'checked' : '' }}>
                <label class="form-check-label">High</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="priority" value="Medium" {{ $task->priority === 'Medium' ? 'checked' : '' }}>
                <label class="form-check-label">Medium</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="priority" value="Low" {{ $task->priority === 'Low' ? 'checked' : '' }}>
                <label class="form-check-label">Low</label>
            </div>
        </div>

        <!-- Task Due Date -->
        <div class="mb-3">
            <label for="duedate" class="form-label">Due Date</label>
            <input type="date" 
                name="duedate" 
                id="duedate" 
                class="form-control" 
                value="{{ $task->duedate ? \Carbon\Carbon::parse($task->duedate)->format('Y-m-d') : '' }}" 
                required>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-lg px-4">
            <i class="fas fa-save me-2"></i>Update Task
            </button>
        </div>
    </form>
</div>
@endsection
