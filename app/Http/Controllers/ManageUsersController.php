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

    public function list () {
        $all_users = User::all();

        return Inertia::render('Admin/ManageUsers/Index/List', [
            'all_users' => $all_users
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

    public function delete(Request $request, User $user) {

        // Gate::authorize('delete', $job);

        $user->delete();
    
        // $user_id = $request->user()->id;

        return redirect()->route('admin.manage_users')->with('flash.banner', 'User deleted successfully.');
    }
    
}
