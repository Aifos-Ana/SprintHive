@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center">
    <form method="POST" action="{{ route('password.email') }}" class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 10px;">
        @csrf

        <h2 class="text-center mb-4">Reset Password</h2>

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">E-Mail Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Send Password Reset Link
            </button>
        </div>
    </form>
</div>
@endsection
