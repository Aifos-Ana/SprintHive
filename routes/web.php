<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within a group that
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to home
Route::redirect('/', '/home');

Route::middleware(['web'])->group(function () {
    Auth::routes();
});

// Home Routes
Route::controller(HomeController::class)->group(function () {
    Route::get('/about', fn() => view('pages.about'))->name('about');
    Route::get('/home', 'index')->name('home');
});

// Authentication Routes
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login'); // Show login form
    Route::post('/login', 'authenticate'); // Login user
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register'); // Show registration form
    Route::post('/register', 'register'); // Register user
});

// Logout (POST route for security)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile Routes (Protected by Auth Middleware)
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show'); // Show profile
    Route::get('/edit', [ProfileController::class, 'edit'])->name('edit'); // Edit profile form
    Route::post('/', [UserController::class, 'updateProfile'])->name('update'); // Update profile
});

// Project Routes (Protected by Auth Middleware)
Route::middleware('auth')->prefix('projects')->name('projects.')->group(function () {
    Route::get('/', [ProjectController::class, 'index'])->name('index'); // List all projects
    Route::get('/create', [ProjectController::class, 'create'])->name('create'); // Create Project Form
    Route::post('/', [ProjectController::class, 'store'])->name('store'); // Store a new project
    Route::get('/{id}', [ProjectController::class, 'show'])->name('show'); // View specific project
    Route::get('/{id}/edit', [ProjectController::class, 'edit'])->name('edit'); // Edit project form
    Route::post('/{id}/update', [ProjectController::class, 'update'])->name('update'); // Update project
    Route::delete('/{id}', [ProjectController::class, 'delete'])->name('delete'); // Delete project
    Route::get('/{id}/tasks', [ProjectController::class, 'tasks'])->name('tasks'); // Show tasks for project

    // Favorites
    Route::post('/{id}/favorite', [ProjectController::class, 'addToFavorites'])->name('addToFavorites');
    Route::delete('/{id}/favorite', [ProjectController::class, 'removeFromFavorites'])->name('removeFromFavorites');
});

// Task Routes (Protected by Auth Middleware)
Route::middleware('auth')->prefix('tasks')->name('tasks.')->group(function () {
    Route::get('/{id}', [TaskController::class, 'show'])->name('show'); // View Task Details
    Route::get('/{id}/edit', [TaskController::class, 'edit'])->name('edit'); // Edit Task Form
    Route::put('/{id}', [TaskController::class, 'update'])->name('update'); // Update Task
    Route::delete('/{id}', [TaskController::class, 'destroy'])->name('destroy'); // Delete Task
});

// Project-Specific Task Creation (Ensure project ID is passed correctly)
Route::middleware('auth')->post('/projects/{projectid}/tasks', [TaskController::class, 'store'])->name('tasks.store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

// Admin Panel Route
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/panel', [AdminController::class, 'adminPanel'])->name('panel');
    Route::delete('/user/{id}', [AdminController::class, 'deleteUser'])->name('deleteUser');
});

// Admin Panel
Route::get('/adminPanel', [SettingsController::class, 'index'])->name('admin.panel');

// Delete User
Route::delete('/admin/user/{id}', [SettingsController::class, 'deleteUser'])->name('admin.deleteUser');