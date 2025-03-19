<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear; // Ensure you have a SchoolYear model
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolYearController extends Controller
{
    public function index()
    {
        return Inertia::render('SchoolYears/Index', [
            'schoolYears' => SchoolYear::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_range' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        SchoolYear::create($validated);

        return redirect()->route('school-years.index')->with('success', 'School year added successfully.');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'school_year_range' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        $schoolYear->update($validated);

        return redirect()->route('school-years.index')->with('success', 'School year updated successfully.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return redirect()->route('school-years.index')->with('success', 'School year deleted successfully.');
    }
}