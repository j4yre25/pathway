<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
 use Inertia\Inertia;


class SectorController extends Controller
{

    public function index(User $user) {


        $sectors = $user->sectors;
        return Inertia::render('Sectors/Index', [
            'sectors' => $sectors
        ]);

        
    }
    
    public function create(User $user) {

        return Inertia::render('Sectors/CreateSectors');



    }

    public function store(Request $request, User $user) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:sectors'],
        ]);
        // return Inertia::render('Jobs/Index/CreateJobs');
        $new_sector = new Sector();
        $new_sector->user_id = $user->id;
        $new_sector->name = $request->input('name');
        $new_sector->save();

        return Redirect()->back()->with('flash.banner', 'Sector added successfully.');

}

   



    public function edit(Sector $sector) {
        return Inertia::render('Sectors/Edit/Index', [
            'sector' => $sector
        ]);
    }

    public function update(Request $request, Sector $sector){
        Gate::authorize('update', $sector);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:99'],


        ]);
        
        $sector->name = $request->name;
        $sector->save();

        return Redirect()->back()->with('flash.banner', 'Sector updated successfully.');
    }

    public function delete(Request $request, Sector $sector) {

        Gate::authorize('delete', $sector);

        $sector->delete();
    
        $user_id = $request->user()->id;

     
        // return redirect()->route('sectors')->with('flash.banner', 'Sector deleted successfully.');
        return Redirect(route('sectors', ['user' => $user_id]))->with('flash.banner', 'Sector deleted successfully.');

    }
    
}
