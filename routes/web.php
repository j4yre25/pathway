<?php

use App\Http\Controllers\JobsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs', [JobsController::class, 'index'])
    ->name('jobs');


// Jobs
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->post('/jobs/{user}', [JobsController::class, 'create'])
    ->name('jobs.create');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/{job}', [JobsController::class, 'view'])
->name('jobs.view');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/jobs/edit/{job}', [JobsController::class, 'edit'])
->name('jobs.edit');    


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->put('/jobs/edit/{job}', [JobsController::class, 'update'])
->name('jobs.update');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->delete('/jobs/edit/{job}', [JobsController::class, 'delete'])
->name('jobs.delete');    