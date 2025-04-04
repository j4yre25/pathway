<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;


class CategoryController extends Controller
{
   
    public function index() {
        // Fetch all sectors with their related categories
        $sectors = Sector::with('categories')->get();
    
        // Debugging
    
        return Inertia::render('Categories/Index', [
            'sectors' => $sectors,
        ]);
    }    

    public function list(Request $request) {
        $status = $request->input('status', 'all'); // Get the filter value from the request (default to 'all')

    // Query categories with filtering based on the status
    $categories = Category::with('user')
        ->withTrashed() // Include archived (soft-deleted) records
        ->when($status === 'active', function ($query) {
            $query->whereNull('deleted_at'); // Active categories (not archived)
        })
        ->when($status === 'inactive', function ($query) {
            $query->whereNotNull('deleted_at'); // Inactive categories (archived)
        })
        ->get();

    return Inertia::render('Categories/List', [
        'categories' => $categories,
        'status' => $status, // Pass the current filter status to the view
    ]);
    }

    
    public function create(Sector $sector) {
        $sectors = Sector::all();
        return Inertia::render('Categories/CreateCategories', [
            'sectors' => $sectors, 
            'sector' => $sector,
            

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
        $category->delete();

        return redirect()->route('categories.index', ['sector' => $category->sector_id])->with('flash.banner', 'Category deleted successfully.');
    }
}



