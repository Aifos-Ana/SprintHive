@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-5">
        <h1 class="display-4">Create a New Project</h1>
        <p class="lead text-muted">Fill in the details below to get started on your new project.</p>
    </div>

    <div class="card shadow-sm rounded bg-light p-4">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf

            <!-- Project Name -->
            <div class="mb-4">
                <label for="name" class="form-label fw-bold">Project Name</label>
                <input 
                    type="text" 
                    class="form-control @error('name') is-invalid @enderror" 
                    id="name" 
                    name="name" 
                    placeholder="Enter project name" 
                    value="{{ old('name') }}" 
                    required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Project Description -->
            <div class="mb-4">
                <label for="description" class="form-label fw-bold">Project Description</label>
                <textarea 
                    class="form-control @error('description') is-invalid @enderror" 
                    id="description" 
                    name="description" 
                    placeholder="Describe your project..." 
                    rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Project Status -->
            <div class="mb-4">
                <label for="status" class="form-label fw-bold">Status</label>
                <select 
                    class="form-select @error('status') is-invalid @enderror" 
                    id="status" 
                    name="status" 
                    required>
                    <option value="in-progress" {{ old('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-lg px-5 py-2 shadow-sm">
                    <i class="fas fa-check-circle me-2"></i>Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
