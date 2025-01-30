@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="text-center">
        <h1 >My Profile</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-4 shadow-sm">
            {{ session('success') }}
        </div>
    @endif


    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-5 p-4">
    @csrf

    <div class="mb-4">
        <label for="profilepic" class="form-label fw-bold">Profile Picture</label>
        <div class="mb-3">
        <img src="{{ asset('storage/' . $user->profilepic) }}" 
                 alt="Profile Picture" 
                 class="rounded-circle mb-3" 
                 style="width: 150px; height: 150px; object-fit: cover;">
        </div>
        <input type="file" 
               class="form-control @error('profilepic') is-invalid @enderror" 
               id="profilepic" 
               name="profilepic" 
               accept="image/*">
        @error('profilepic')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="name" class="form-label fw-bold">Name</label>
        <input type="text" 
               class="form-control @error('name') is-invalid @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', $user->name) }}" 
               required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label fw-bold">Email Address</label>
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               id="email" 
               name="email" 
               value="{{ old('email', $user->email) }}" 
               required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-bold">New Password <span class="text-muted">(Optional)</span></label>
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               id="password" 
               name="password">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
        <input type="password" 
               class="form-control" 
               id="password_confirmation" 
               name="password_confirmation">
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
            <i class="fas fa-save me-2"></i>Update Profile
        </button>
    </div>
</form>

</div>
@endsection
