<?php

namespace App\Http\Controllers;

use App\Models\Jobs;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobsController extends Controller
{
    public function index(Request $request) {

        $user = $request->user();

        $jobs = $user->jobs;
        return Inertia::render('Jobs/Index', [
            'jobs' => $jobs
        ]);
    }

    public function create (User $user) {

        $new_job = new Jobs();
        $new_job->user_id = $user->id;
        $new_job->name = 'New Job Name';
        $new_job->description = 'New Job Description';
        $new_job->save();

        return Redirect()->back()->with('flash.banner', 'Job posted successfully.');

    }

    public function view(Jobs $job) {
        return Inertia::render('Jobs/View/Index', [
            'job' => $job
        ]);
    }

    public function edit(Jobs $job) {
        return Inertia::render('Jobs/Edit/Index', [
            'job' => $job
        ]);
    }

    public function update(Request $request, Jobs $job){

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:99'],
            'description' => ['required', 'string', 'max:99'],


        ]);
        
        $job->name = $request->name;
        $job->description = $request->description;
        $job->save();

        return Redirect()->back()->with('flash.banner', 'Job updated successfully.');
    }

    public function delete(Jobs $job) {
        $job->delete();

        return Redirect(route('jobs'));
    }
    
}
