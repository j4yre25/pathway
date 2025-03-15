<?php

use App\Http\Controllers\ManageGraduatesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ManageUsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/{user}', [JobsController::class, 'index'])
    ->name('jobs');

//  Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/{user}', [JobsController::class, 'index'])
//     ->name('jobs');
    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/{user}/create', [JobsController::class, 'create'])
->name('jobs.create');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->post('/jobs/{user}', [JobsController::class, 'store'])
->name('jobs.store');
   

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/view/{job}', [JobsController::class, 'view'])
->name('jobs.view');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/edit/{job}', [JobsController::class, 'edit'])
->name('jobs.edit');    


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->put('/jobs/edit/{job}', [JobsController::class, 'update'])
->name('jobs.update');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->delete('/jobs/edit/{job}', [JobsController::class, 'delete'])
->name('jobs.delete');    


// Manage Users (PESO)
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'can:manage users'])->get('/admin/manage-users', [ManageUsersController::class, 'index'])
->name('admin.manage_users');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'can:manage users'])->get('/admin/manage-users/edit/{user}', [ManageUsersController::class, 'edit'])
->name('admin.manage_users.edit');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'can:manage users'])->delete('/admin/manage-users/{user}', [ManageUsersController::class, 'delete'])
->name('admin.manage_users.delete');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'can:manage users'])->post('/admin/manage-users/{user}/approve', [ManageUsersController::class, 'approve'])
    ->name('admin.manage_users.approve');

// Manage Graduates
// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates', [ManageGraduatesController::class, 'index'])
// ->name('graduates');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates/{manage-graduates}', [ManageGraduatesController::class, 'index'])
// ->name('graduates.create');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates', [ManageGraduatesController::class, 'index'])
// ->name('graduates.view');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates', [ManageGraduatesController::class, 'index'])
// ->name('graduates.edit');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates', [ManageGraduatesController::class, 'index'])
// ->name('graduates.update');

// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/manage-graduates', [ManageGraduatesController::class, 'index'])
// ->name('graduates.delete');