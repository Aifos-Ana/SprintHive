@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Edit Project</h1>
        <p class="lead text-muted">Update the details of your project below.</p>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger">
            <h5 class="fw-bold">Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Edit Form -->
    <div class="card shadow-sm rounded bg-light p-4">
        <form method="POST" action="{{ route('projects.update', ['id' => $project->projectid]) }}">
            @csrf
            @method('POST')

            <!-- Project Name -->
            <div class="mb-4">
                <label for="name" class="form-label fw-bold">Project Name</label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $project->name) }}" 
                    placeholder="Enter project name" 
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-4">
                <label for="description" class="form-label fw-bold">Description</label>
                <textarea 
                    class="form-control @error('description') is-invalid @enderror" 
                    id="description" 
                    name="description" 
                    placeholder="Describe your project..." 
                    rows="5">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="mb-4">
                <label for="status" class="form-label fw-bold">Status</label>
                <select 
                    class="form-select @error('status') is-invalid @enderror" 
                    id="status" 
                    name="status">
                    <option value="ongoing" {{ $project->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Update Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg px-5 py-2 shadow-sm">
                    <i class="fas fa-save me-2"></i>Update Project
                </button>
            </div>
        </form>
    </div>

    <!-- Delete Form -->
    <div class="text-center p-4">
        <form action="{{ route('projects.delete', $project->projectid) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-lg px-5 py-2 shadow-sm" 
                    onclick="return confirm('Are you sure you want to delete this project?');">
                <i class="fas fa-trash-alt me-2"></i>Delete Project
            </button>
        </form>
    </div>
</div>
@endsection
