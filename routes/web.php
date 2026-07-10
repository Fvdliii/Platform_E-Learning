<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    // User Management - hanya admin
    Route::resource('/user', UserController::class)->middleware('role:admin');

    // Category Management - hanya admin
    Route::resource('/category', CategoryController::class)->middleware('role:admin');

    // Course Management - admin dan instructor
    Route::resource('/course', CourseController::class)->middleware('role:admin,instructor');

    // Lesson Management - admin dan instructor
    Route::resource('/lesson', App\Http\Controllers\LessonController::class)->middleware('role:admin,instructor');

    // Quiz Management - admin dan instructor
    Route::resource('/quiz', App\Http\Controllers\QuizController::class)->middleware('role:admin,instructor');

    // Question Management - admin dan instructor (nested under quiz context)
    Route::resource('/question', App\Http\Controllers\QuestionController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:admin,instructor');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
});

