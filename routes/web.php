<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\SubtaskController;

// AUTH
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');

    // ADMIN
    Route::middleware('role:admin')->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::post('/projects/{project}/members', [ProjectMemberController::class, 'addMember'])->name('projects.members.add');
    });

   // Team Lead
Route::middleware(['auth','role:team_lead'])->group(function () {
    Route::get('/teamlead/dashboard', [ProjectController::class,'teamLeadDashboard'])->name('teamlead.dashboard');
    Route::get('/teamlead/projects/{project}', [ProjectController::class,'teamLeadShow'])->name('teamlead.projects.show');

    
    // Cards management
    Route::get('/teamlead/boards/{board}/cards', [CardController::class, 'index'])
        ->name('teamlead.cards.index');
    Route::get('/teamlead/boards/{board}/cards/create', [CardController::class, 'create'])
        ->name('teamlead.cards.create');
    Route::post('/teamlead/boards/{board}/cards', [CardController::class, 'store'])
        ->name('teamlead.cards.store');

    // Edit & Update
    Route::get('/teamlead/boards/{board}/cards/{card}/edit', [CardController::class, 'edit'])
        ->name('teamlead.cards.edit');
    Route::put('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'update'])
        ->name('teamlead.cards.update');

    // Delete
    Route::delete('/teamlead/boards/{board}/cards/{card}', [CardController::class, 'destroy'])
        ->name('teamlead.cards.destroy');

    Route::post('/subtasks/{subtask}/approve', [SubtaskController::class, 'approve'])->name('subtasks.approve');
    Route::post('/subtasks/{subtask}/reject', [SubtaskController::class, 'reject'])
    ->name('subtasks.reject');

});
   /*
|--------------------------------------------------------------------------
| DEVELOPER & DESIGNER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:developer,designer'])->group(function() {
    // Dashboard
    Route::get('/developer/dashboard', [ProjectController::class, 'developerDashboard'])->name('developer.dashboard');
    Route::get('/designer/dashboard', [ProjectController::class, 'designerDashboard'])->name('designer.dashboard');

     // Halaman buat subtask (create)
    Route::get('/cards/{card}/subtasks/create', [SubtaskController::class, 'create'])->name('subtasks.create');

    // Simpan subtask
    Route::post('/cards/{card}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');

    // Aksi start & complete
    Route::post('/subtasks/{subtask}/start', [SubtaskController::class, 'start'])->name('subtasks.start');
    Route::post('/subtasks/{subtask}/complete', [SubtaskController::class, 'complete'])->name('subtasks.complete');
});
});
