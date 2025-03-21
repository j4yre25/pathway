<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
 use Inertia\Inertia;


class JobsController extends Controller
{
    public function index(User $user) {


        $jobs = $user->jobs;
        return Inertia::render('Jobs/Index/Index', [
            'jobs' => $jobs
        ]);

        
    }

    
    public function create(User $user) {
        return Inertia::render('Jobs/Index/CreateJobs');


    }

    public function store(Request $request, User $user) {
        // return Inertia::render('Jobs/Index/CreateJobs');
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'vacancy' => 'required|integer',
            'salary' => 'required|integer|max:255',
            'job_type' => 'required|string|max:255',
            'experience_level' => 'required|string|max:255',
            'description' => 'required|string',
            'skills' => 'required|array',
        ]);
    
        $new_job = new Job();
        $new_job->user_id = $user->id;
        $new_job->job_title = $validated['job_title'];
        $new_job->location = $validated['location'];
        $new_job->salary = $validated['salary'];
        $new_job->job_type = $validated['job_type'];
        $new_job->experience_level = $validated['experience_level'];
        $new_job->skills = json_encode($validated['skills']);
        $new_job->vacancy = $validated['vacancy'];
        $new_job->description = $validated['description'];
        $new_job->save();
    
        // return redirect()->back()->with('flash.banner', 'Job posted successfully.');
        return redirect()->route('jobs', ['user' => $user->id])->with('flash.banner', 'Job posted successfully.');
}

   


    public function view(Job $job) {
        return Inertia::render('Jobs/View/Index', [
            'job' => $job
        ]);
    }

    public function edit(Job $job) {
        return Inertia::render('Jobs/Edit/Index', [
            'job' => $job
        ]);
    }

    public function update(Request $request, Job $job){
        Gate::authorize('update', $job);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:99'],
            'description' => ['required', 'string', 'max:99'],


        ]);
        
        $job->name = $request->name;
        $job->description = $request->description;
        $job->save();

        return Redirect()->back()->with('flash.banner', 'Job updated successfully.');
    }

    public function delete(Request $request, Job $job) {

        Gate::authorize('delete', $job);

        $job->delete();
    
        $user_id = $request->user()->id;

        return Redirect(route('jobs', ['user' => $user_id]))->with('flash.banner', 'Job deleted successfully.');
    }
    
}