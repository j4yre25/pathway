<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    return Inertia::render('Dashboard', [
        'userNotApproved' => !$user->is_approved, // Pass the approval status
    ]);
}
}
