@extends('layouts.app')

@section('content')

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4">About Us</h1>
        <p class="text-muted">Learn more about SprintHive and the team behind it.</p>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h3 class="mb-0">What is SprintHive?</h3>
        </div>
        <div class="card-body">
            <p class="about_paragraph mb-0">
                SprintHive is a tool designed to streamline all communication necessary for a project. It helps manage tasks and addresses any questions or issues that may arise during the project, providing effective solutions throughout the process.
            </p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h3 class="mb-0">How did SprintHive come about?</h3>
        </div>
        <div class="card-body">
            <p class="about_paragraph mb-0">
                SprintHive came about because of our LBAW course project in 2024/2025.
            </p>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h3 class="mb-0">Who we are?</h3>
        </div>
        <div class="card-body">
            <p class="about_paragraph mb-0">
                We are 3rd year Software Engineering Students at the Faculty of Engineering of the University of Porto.
            </p>
        </div>
    </div>
</div>

@endsection
