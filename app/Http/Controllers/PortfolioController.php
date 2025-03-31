<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Portfolio;
use App\Models\User;

class PortfolioController extends Controller
{
    /**
     * Display the user's portfolio
     *
     * @return \Inertia\Response
     */
    public function show()
    {
        $user = User::all();
        $portfolioItems = $user->portfolioItems()->get();
        
        // Group portfolio items by type
        $groupedItems = [
            'workSamples' => $portfolioItems->where('type', 'work_sample')->values(),
            'awards' => $portfolioItems->where('type', 'award')->values(),
            'education' => $portfolioItems->where('type', 'education')->values(),
            'profDevelopment' => $portfolioItems->where('type', 'prof_development')->values(),
            'volunteerWork' => $portfolioItems->where('type', 'volunteer_work')->values(),
            'memberships' => $portfolioItems->where('type', 'membership')->values(),
        ];
        
        return Inertia::render('Profile', [
            'portfolio' => $groupedItems
        ]);
    }
    
    /**
     * Upload portfolio files
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $user = User::all();
        
        foreach ($request->file('files') as $file) {
            $path = $file->store('portfolios/' . $user->id, 'public');
            
            // Create a new portfolio item using the relationship
            $user->portfolioItems()->create([
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
            ]);
        }
        
        return redirect()->route('profile')->with('success', 'Portfolio files uploaded successfully.');
    }
    
    /**
     * Create or update portfolio content
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'url' => 'nullable|url|max:255',
        ]);
        
        $user = User::all();
        
        // Create a new portfolio item using the relationship
        $user->portfolioItems()->create([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'url' => $request->url,
        ]);
        
        return redirect()->route('profile')->with('success', 'Portfolio item created successfully.');
    }
    
    /**
     * Delete a portfolio item
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $user = User::all();
        $portfolioItem = $user->portfolioItems()->findOrFail($id);
        
        // Delete the file if it exists
        if ($portfolioItem->file_path) {
            Storage::disk('public')->delete($portfolioItem->file_path);
        }
        
        $portfolioItem->delete();
        
        return redirect()->route('profile')->with('success', 'Portfolio item deleted successfully.');
    }
}

