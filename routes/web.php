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

    // Course Management - admin dan instructor (kecuali show)
    Route::resource('/course', CourseController::class)
        ->except(['show'])
        ->middleware('role:admin,instructor');

    // Course show - semua user (admin, instructor, student) bisa melihat detail kursus
    Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');

    // Lesson Management - admin dan instructor (kecuali show)
    Route::resource('/lesson', App\Http\Controllers\LessonController::class)
        ->except(['show'])
        ->middleware('role:admin,instructor');

    // Lesson show - semua user bisa melihat detail materi (pengecekan pendaftaran bisa dilakukan di controller)
    Route::get('/lesson/{lesson}', [App\Http\Controllers\LessonController::class, 'show'])->name('lesson.show');

    // Quiz untuk student (mengerjakan kuis)
    Route::get('/quiz/{quiz}/take', [App\Http\Controllers\StudentQuizController::class, 'show'])->name('student.quiz.show')->middleware('role:student');
    Route::post('/quiz/{quiz}/submit', [App\Http\Controllers\StudentQuizController::class, 'submit'])->name('student.quiz.submit')->middleware('role:student');
    Route::get('/quiz/result/{attempt}', [App\Http\Controllers\StudentQuizController::class, 'result'])->name('student.quiz.result')->middleware('role:student');

    // Quiz Management - admin dan instructor
    Route::resource('/quiz', App\Http\Controllers\QuizController::class)->middleware('role:admin,instructor');

    // Quiz Attempt Management - admin dan instructor
    Route::post('/quiz-attempt/{attempt}/note', [App\Http\Controllers\QuizAttemptController::class, 'addNote'])->name('quiz.attempt.note')->middleware('role:admin,instructor');
    Route::delete('/quiz-attempt/{attempt}/reset', [App\Http\Controllers\QuizAttemptController::class, 'reset'])->name('quiz.attempt.reset')->middleware('role:admin,instructor');
    Route::get('/quiz-attempt/{attempt}/answers', [App\Http\Controllers\QuizAttemptController::class, 'answers'])->name('quiz.attempt.answers')->middleware('role:admin,instructor');

    // Question Management - admin dan instructor (nested under quiz context)
    Route::resource('/question', App\Http\Controllers\QuestionController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:admin,instructor');

    // Enrollment Management - student
    Route::get('/my-courses', [App\Http\Controllers\EnrollmentController::class, 'index'])->name('enrollment.index')->middleware('role:student');
    Route::post('/enroll', [App\Http\Controllers\EnrollmentController::class, 'store'])->name('enrollment.store')->middleware('role:student');
    Route::delete('/enroll/{enrollment}', [App\Http\Controllers\EnrollmentController::class, 'destroy'])->name('enrollment.destroy')->middleware('role:student');

    // Enrollment Management - admin & instructor (kelola siswa di kursus)
    Route::get('/course/{course}/students', [App\Http\Controllers\EnrollmentController::class, 'manage'])->name('enrollment.manage')->middleware('role:admin,instructor');
    Route::post('/enroll/admin', [App\Http\Controllers\EnrollmentController::class, 'adminStore'])->name('enrollment.admin.store')->middleware('role:admin,instructor');
    Route::delete('/enroll/admin/{enrollment}', [App\Http\Controllers\EnrollmentController::class, 'adminDestroy'])->name('enrollment.admin.destroy')->middleware('role:admin,instructor');

    // Progress Management - student
    Route::post('/progress', [App\Http\Controllers\ProgressController::class, 'store'])->name('progress.store')->middleware('role:student');

    // Review Management - student
    Route::post('/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store')->middleware('role:student');

    // Certificate Management - student
    Route::get('/certificates', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificate.index')->middleware('role:student');
    Route::get('/certificates/{certificate}', [App\Http\Controllers\CertificateController::class, 'show'])->name('certificate.show')->middleware('role:student,admin,instructor');

    // Certificate Management - admin & instructor
    Route::get('/certificate/manage', [App\Http\Controllers\CertificateController::class, 'manage'])->name('certificate.manage')->middleware('role:admin,instructor');
    Route::post('/certificate', [App\Http\Controllers\CertificateController::class, 'store'])->name('certificate.store')->middleware('role:admin,instructor');
    Route::delete('/certificate/{certificate}', [App\Http\Controllers\CertificateController::class, 'destroy'])->name('certificate.destroy')->middleware('role:admin,instructor');

    // Setting Management - admin only
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index')->middleware('role:admin');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update')->middleware('role:admin');
});

