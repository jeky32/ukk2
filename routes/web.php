<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\MonitoringController;

// ===================================================
// 🔐 AUTH ROUTES
// ===================================================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// REGISTER & FORGOT PASSWORD
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// LOGIN VIA GOOGLE
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// ===================================================
// 🧭 PROTECTED ROUTES (User harus login)
// ===================================================
Route::middleware('auth')->group(function () {

    // === DASHBOARD UMUM ===
    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    // ===================================================
    // 🛠️ ADMIN ROUTES
    // ===================================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

        // === CRUD Project ===
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');   // ✅ daftar proyek
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create'); // ✅ form tambah
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');  // ✅ simpan proyek
        Route::get('/projects/{project_id}', [ProjectController::class, 'show'])->name('projects.show'); // ✅ detail proyek
        Route::get('/projects/{project_id}/edit', [ProjectController::class, 'edit'])->name('projects.edit'); // opsional edit
        Route::put('/projects/{project_id}', [ProjectController::class, 'update'])->name('projects.update'); // opsional update
        Route::delete('/projects/{project_id}', [ProjectController::class, 'destroy'])->name('projects.destroy'); // opsional hapus

        // === Monitoring Project ===
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/{project_id}', [MonitoringController::class, 'projectDetails'])->name('monitoring.project-details');

        // === Tambah Anggota Proyek ===
        Route::post('/projects/{project_id}/members', [ProjectMemberController::class, 'addMember'])->name('projects.members.add');
    });

    // ===================================================
    // 👨‍💼 TEAM LEAD ROUTES
    // ===================================================
    Route::middleware('role:teamlead')->group(function () {
        Route::get('/teamlead/dashboard', [ProjectController::class, 'teamLeadDashboard'])->name('teamlead.dashboard');
        Route::get('/teamlead/projects/{project_id}', [ProjectController::class, 'teamLeadShow'])->name('teamlead.projects.show');

        // Cards
        Route::get('/teamlead/boards/{board}/cards', [CardController::class, 'index'])->name('teamlead.cards.index');
        Route::get('/teamlead/boards/{board}/cards/create', [CardController::class, 'create'])->name('teamlead.cards.create');
        Route::post('/teamlead/boards/{board}/cards', [CardController::class, 'store'])->name('teamlead.cards.store');
        Route::get('/teamlead/boards/{board}/cards/{card}/edit', [CardController::class, 'edit'])->name('teamlead.cards.edit');
        Route::put('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'update'])->name('teamlead.cards.update');
        Route::delete('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'destroy'])->name('teamlead.cards.destroy');

        // Subtask Approval
        Route::post('/subtasks/{subtask}/approve', [SubtaskController::class, 'approve'])->name('subtasks.approve');
        Route::post('/subtasks/{subtask}/reject', [SubtaskController::class, 'reject'])->name('subtasks.reject');
    });

    // ===================================================
    // 💻 DEVELOPER & DESIGNER ROUTES
    // ===================================================
    Route::middleware('role:developer,designer')->group(function () {
        Route::get('/developer/dashboard', [ProjectController::class, 'developerDashboard'])->name('developer.dashboard');
        Route::get('/designer/dashboard', [ProjectController::class, 'designerDashboard'])->name('designer.dashboard');

        // Subtasks
        Route::get('/cards/{card}/subtasks/create', [SubtaskController::class, 'create'])->name('subtasks.create');
        Route::post('/cards/{card}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
        Route::post('/subtasks/{subtask}/start', [SubtaskController::class, 'start'])->name('subtasks.start');
        Route::post('/subtasks/{subtask}/complete', [SubtaskController::class, 'complete'])->name('subtasks.complete');
    });
});
