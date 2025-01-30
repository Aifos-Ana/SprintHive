@extends('layouts.app')

@section('content')
<link href="{{ url('css/app.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Login</h2>
            
            <form method="POST" action="{{ route('login') }}" class="card p-4 shadow-sm">
                @csrf

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="form-control @error('email') is-invalid @enderror" 
                        required autofocus>
                    
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        required>
                    
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="form-check mb-3">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="form-check-input" 
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                        Register
                    </a>
                </div>

                <!-- Forgot Password Link -->
<div class="text-center mt-3">
    <a href="{{ route('password.request') }}" class="text-decoration-underline">
        Forgot Your Password?
    </a>
</div>

            </form>
        </div>
    </div>
</div>
@endsection
