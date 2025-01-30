<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SprintHive') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo -->
                <h1><a href="{{ url('/home') }}" style="text-decoration: none; color: inherit;">
                <img src="{{ asset('images/logo.svg') }}" alt="SprintHive Logo" class="logo" style="height: 50px;"> 
                SprintHive</a></h1>
                
                <!-- Navigation Links -->
                <nav>
                    <ul class="nav">
                        <li class="nav-item">
                            <a href="{{ route('about') }}" class="nav-link" style="color: inherit;">About Us</a>
                        </li>
                        @auth
                            <li class="nav-item">
                                <a  href="{{ route('profile.show') }}" class="nav-link" style="color: inherit;">Profile</a>
                
                            </li>
                            @if(auth()->user()->is_admin) <!-- Check if the user is an admin -->
                            <li class="nav-item">
                                <a href="{{ route('settings') }}" class="nav-link" style="color: inherit;">Settings</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-link nav-link" type="submit" style="color: red;">Logout</button>
                                </form>
                            </li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

<main class="container">
    @yield('content')
</main>

<footer>
    <p>Copyright © {{ date('Y') }} SprintHive. All Rights Reserved.</p>
</footer>
</body>
</html>
