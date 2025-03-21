<?php

use App\Http\Controllers\ManageGraduatesController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ManageUsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
USE App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminRegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\HRRegisterController;
USE App\Http\Controllers\UserController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CareerOpportunityController;
use App\Http\Controllers\ManageGraduatesApprovalController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});
// Route::middleware(['auth', 'role:peso'])->group(function () {
    Route::get('/admin/register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('/admin/register', [AdminRegisterController::class, 'register'])->name('admin.register.submit');
// });

// Route::middleware(['auth', 'role:hr'])->group(function () {
    Route::get('/hr/register', [HRRegisterController::class, 'showRegistrationForm'])->name('hr.register');
    Route::post('/hr/register', [HRRegisterController::class, 'register'])->name('hr.register.submit');
// });

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
});


// Admin Routes
// Route::prefix('admin')->group(function () {
//     Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
//     Route::get('/register', [AdminController::class, 'showRegistrationForm'])->name('admin.register');
//     Route::post('/register', [AdminController::class, 'register'])->name('admin.register.submit');
// });

// // User Routes
// Route::prefix('user')->group(function () {
//     Route::get('/login', [UserController::class, 'showLoginForm'])->name('user.login');
//     Route::post('/login', [UserController::class, 'login'])->name('user.login.submit');
//     Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('user.register');
//     Route::post('/register', [UserController::class, 'register'])->name('user.register.submit');
// });

// Route::middleware('admin')->group(function () {
//     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// });


// Route::get('/home', function () {
//     return redirect()->route('dashboard');
// })->middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ]);


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


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/sectors/{user}', [SectorController::class, 'index'])
->name('sectors');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/sectors/{user}/create', [SectorController::class, 'create'])
->name('sectors.create');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->post('/sectors/{user}', [SectorController::class, 'store'])
->name('sectors.store');
   

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->get('/sectors/edit/{sector}', [SectorController::class, 'edit'])
->name('sectors.edit');    


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->put('/sectors/edit/{sector}', [SectorController::class, 'update'])
->name('sectors.update');    

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->delete('/sectors/edit/{sector}', [SectorController::class, 'delete'])
->name('sectors.delete');


Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/categories/{sector}', [CategoryController::class, 'index'])
    ->name('categories');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/categories/{sector}/create', [CategoryController::class, 'create'])
    ->name('categories.create');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->post('/categories/{sector}', [CategoryController::class, 'store'])
    ->name('categories.store');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/categories/edit/{category}', [CategoryController::class, 'edit'])
    ->name('categories.edit');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->put('/categories/edit/{category}', [CategoryController::class, 'update'])
    ->name('categories.update');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->delete('/categories/edit/{category}', [CategoryController::class, 'delete'])
    ->name('categories.delete');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'can:manage approval graduate'])->group(function () {
            Route::get('/graduates', [GraduateController::class, 'index'])->name('graduates.index');

            // Show the form for creating a new graduate
            Route::get('/graduates/create', [GraduateController::class, 'create'])->name('graduates.create');

            // Store a new graduate
            Route::post('/graduates', [GraduateController::class, 'store'])->name('graduates.store');

            // Show a specific graduate
            Route::get('/graduates/{graduate}', [GraduateController::class, 'show'])->name('graduates.show');

            // Show the form for editing a specific graduate
            Route::get('/graduates/{graduate}/edit', [GraduateController::class, 'edit'])->name('graduates.edit');

            // Update a specific graduate
            Route::patch('/graduates/{graduate}', [GraduateController::class, 'update'])->name('graduates.update');

            // Delete a specific graduate
            Route::delete('/graduates/{graduate}', [GraduateController::class, 'destroy'])->name('graduates.destroy');

            // Additional custom routes
            Route::get('/graduates/download-template', [GraduateController::class, 'downloadTemplate'])
                ->name('graduates.downloadTemplate');
            Route::post('/graduates/batch-upload', [GraduateController::class, 'batchUpload'])
                ->name('graduates.batchUpload');
            });

    // Institution Routes
    Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
        // Institution Routes
        Route::middleware(['can:manage institution'])->group(function () {
        Route::get('/institutions', [InstitutionController::class, 'index'])->name('institutions.index');
        Route::get('/institutions/create', [InstitutionController::class, 'create'])->name('institutions.create');
        Route::post('/institutions', [InstitutionController::class, 'store'])->name('institutions.store');
        Route::get('/institutions/{institution}', [InstitutionController::class, 'show'])->name('institutions.show');
        Route::get('/institutions/{institution}/edit', [InstitutionController::class, 'edit'])->name('institutions.edit');
        Route::put('/institutions/{institution}', [InstitutionController::class, 'update'])->name('institutions.update');
        Route::delete('/institutions/{institution}', [InstitutionController::class, 'destroy'])->name('institutions.destroy');
        });
    
        // School Years Routes
        Route::middleware(['can:manage institution'])->group(function () {
            Route::get('/school-years', [SchoolYearController::class, 'index'])->name('school-years.index');
            Route::get('/school-years/create', [SchoolYearController::class, 'create'])->name('school-years.create');
            Route::post('/school-years', [SchoolYearController::class, 'store'])->name('school-years.store');
            Route::get('/school-years/{school_year}', [SchoolYearController::class, 'show'])->name('school-years.show');
            Route::get('/school-years/{school_year}/edit', [SchoolYearController::class, 'edit'])->name('school-years.edit');
            Route::put('/school-years/{school_year}', [SchoolYearController::class, 'update'])->name('school-years.update');
            Route::delete('/school-years/{school_year}', [SchoolYearController::class, 'destroy'])->name('school-years.destroy');
        });
    
        // Programs Routes
        Route::middleware(['can:manage institution'])->group(function () {
            Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
            Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
            Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
            Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
            Route::get('/programs/{program}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
            Route::put('/programs/{program}', [ProgramController::class, 'update'])->name('programs.update');
            Route::delete('/programs/{program}', [ProgramController::class, 'destroy'])->name('programs.destroy');
        });
    
        // Career Opportunities Routes
        Route::middleware(['permission:manage institution'])->group(function () {
            Route::get('/career-opportunities', [CareerOpportunityController::class, 'index'])->name('career-opportunities.index');
            Route::get('/career-opportunities/create', [CareerOpportunityController::class, 'create'])->name('career-opportunities.create');
            Route::post('/career-opportunities', [CareerOpportunityController::class, 'store'])->name('career-opportunities.store');
            Route::get('/career-opportunities/{career_opportunity}', [CareerOpportunityController::class, 'show'])->name('career-opportunities.show');
            Route::get('/career-opportunities/{career_opportunity}/edit', [CareerOpportunityController::class, 'edit'])->name('career-opportunities.edit');
            Route::put('/career-opportunities/{career_opportunity}', [CareerOpportunityController::class, 'update'])->name('career-opportunities.update');
            Route::delete('/career-opportunities/{career_opportunity}', [CareerOpportunityController::class, 'destroy'])->name('career-opportunities.destroy');
        });
    });

    // Manage Graduates Approval Routes
   
        Route::get('/manage-users', [ManageGraduatesApprovalController::class, 'index'])
            ->middleware('can:manage approval graduate')
            ->name('institution.manage_users');
        Route::post('/manage-users/{user}/approve', [ManageGraduatesApprovalController::class, 'approve'])
            ->middleware('can:manage approval graduate')
            ->name('institution.manage_users.approve');
        });
