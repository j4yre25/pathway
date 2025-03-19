<?php

namespace App\Http\Controllers;

use App\Models\CareerOpportunity; // Ensure you have a CareerOpportunity model
use Illuminate\Http\Request;
use Inertia\Inertia;

class CareerOpportunityController extends Controller
{
    public function index()
    {
        return Inertia::render('CareerOpportunities/Index', [
            'careerOpportunities' => CareerOpportunity::with('program')->get(),
        ]);
    }

    public function store(Request $request) 
    { 
        $validated = $request->validate([ 
            'title' => 'required|string|max:255', 
            'description' => 'nullable|string', 
            'program_id' => 'required|exists:programs,id', 
        ]);
    
        CareerOpportunity::create($validated); 
    
        return redirect()->route('career-opportunities.index')
            ->with('success', 'Career opportunity added successfully.');
    }

    public function update(Request $request, CareerOpportunity $careerOpportunity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'program_id' => 'required|exists:programs,id',
        ]);

        $careerOpportunity->update($validated);

        return redirect()->route('career-opportunities.index')->with('success', 'Career opportunity updated successfully.');
    }

    public function destroy(CareerOpportunity $careerOpportunity)
    {
        $careerOpportunity->delete();

        return redirect()->route('career-opportunities.index')->with('success', 'Career opportunity deleted successfully.');
    }
}