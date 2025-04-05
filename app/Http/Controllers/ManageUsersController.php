<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageUsersController extends Controller
{
    public function index (){


        $all_users = User::all();

        return Inertia::render('Admin/ManageUsers/Index/Index', [
            'all_users' => $all_users


        ]);

    }

    public function list (Request $request) {
        $query = User::query();

    // Filter by user level (role)
    if ($request->has('role') && $request->role !== 'all') {
        $query->where('role', $request->role);
    }

    // Filter by date creation
    if ($request->has('date_from') && $request->has('date_to')) {
        $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
    }

    // Filter by status (active/inactive)
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('is_approved', $request->status === 'active');
    }

    $all_users = $query->get();

    return Inertia::render('Admin/ManageUsers/Index/List', [
        'all_users' => $all_users,
        'filters' => $request->only(['role', 'date_from', 'date_to', 'status']),
    ]);

    }

    public function edit(User $users) {
        return Inertia::render('Admin/ManageUsers/Edit/Index', [
            'user' => $users
        ]);
    }

    

    public function approve(User $user)
    {
        $user->is_approved = true; 
        $user->save();
    
        return redirect()->route('admin.manage_users')->with('flash.banner', 'User  approved successfully.');
    }

    public function disapprove(User $user)
    {
        $user->is_approved = false; 
        $user->save();

         return redirect()->back()->with('success', 'User disapproved successfully.');
    }

    public function delete(Request $request, User $user) {

        // Gate::authorize('delete', $job);

        $user->delete();
    
        // $user_id = $request->user()->id;

        return redirect()->route('admin.manage_users')->with('flash.banner', 'User deleted successfully.');
    }
    
}
