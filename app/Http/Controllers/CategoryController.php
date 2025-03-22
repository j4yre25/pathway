<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CategoryController extends Controller
{
   
    public function index(Sector $sector) {
        $categories = $sector->categories->map(function($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
    
        return Inertia::render('Categories/Index', [
            'categories' => $categories,
            'sector' => $sector, // This is a single sector object
        ]);
    }
    
    public function create(Sector $sector) {
        return Inertia::render('Categories/CreateCategories', [
            'sector' => $sector 
        ]);
    }

    public function store(Request $request, Sector $sector) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories'],
        ]);

        $new_category = new Category();
        $new_category->user_id = $request->user()->id; 
        $new_category->name = $validated['name'];
        $new_category->sector_id = $sector->id; 
        $new_category->save();

        return redirect()->back()->with('flash.banner', 'Category added successfully.');
    }


   



    public function edit(Category $category) {
        return Inertia::render('Categories/Edit/Index', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category) {
        Gate::authorize('update', $category);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        $category->name = $validated['name'];
        $category->save();

        return redirect()->back()->with('flash.banner', 'Category updated successfully.');
    }

    public function delete(Request $request, Category $category) {
        Gate::authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories', ['sector' => $category->sector_id])->with('flash.banner', 'Category deleted successfully.');
    }
}



