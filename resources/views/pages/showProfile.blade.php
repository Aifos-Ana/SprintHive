@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <h1> Welcome, {{ $user->name }}!</h1>
        <p class="lead text-muted">Here is your profile information:</p>
    </div>

    <!-- Profile Picture Section -->
    <div class="text-center mt-4">
        <img src="{{ asset('storage/' . $user->profilepic) }}"  
             alt="Profile Picture" 
             class="rounded-circle mb-3" 
             style="width: 150px; height: 150px; object-fit: cover;">
    </div>

    <!-- Profile Details -->
    <div class="profile-details mt-5 p-4 shadow-sm rounded bg-light">
        <div class="row mb-3 align-items-center">
            <div class="col-md-3 text-end">
                <strong>Name:</strong>
            </div>
            <div class="col-md-9 text-start">
                <span class="text-dark fs-5">{{ $user->name }}</span>
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <div class="col-md-3 text-end">
                <strong>Email:</strong>
            </div>
            <div class="col-md-9 text-start">
                <span class="text-dark fs-5">{{ $user->email }}</span>
            </div>
        </div>
    </div>

    <!-- Edit Profile Button -->
    <div class="text-center mt-5">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg px-4 shadow">
            <i class="fas fa-edit me-2"></i>Edit Profile
        </a>
    </div>
</div>
@endsection
