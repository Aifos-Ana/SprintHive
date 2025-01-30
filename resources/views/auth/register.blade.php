@extends('layouts.app')

@section('content')
<h2 class="text-center mb-4">Register</h2>

<div class="container d-flex justify-content-center align-items-center">
    <form method="POST" action="{{ route('register') }}" class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
        {{ csrf_field() }}

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control @error('name') is-invalid @enderror">
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
            @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
            @if ($errors->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->first('password') }}
                </div>
            @endif
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="password-confirm" class="form-label">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required class="form-control">
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Register
            </button>
            <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                Login
            </a>
        </div>
    </form>
</div>

@endsection
