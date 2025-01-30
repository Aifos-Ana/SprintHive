@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <form method="POST" action="{{ route('password.update') }}" class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
        @csrf

        <h2 class="text-center mb-4">Reset Password</h2>

        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="password-confirm" class="form-label">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required class="form-control">
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Reset Password
            </button>
        </div>
    </form>
</div>
@endsection
