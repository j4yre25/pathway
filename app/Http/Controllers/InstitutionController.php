<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Program;
use App\Models\CareerOpportunity;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    public function index()
    {
        return Inertia::render('Institutions/Index', [
            'institutions' => Institution::all(),
            'programs' => Program::all(),
            'careerOpportunities' => CareerOpportunity::with('program')->get(),
            'schoolYears' => SchoolYear::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Institutions/Create', [
            'programs' => Program::all(),
            'schoolYears' => SchoolYear::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year_range' => 'required|string',
            'term' => 'required|string',
            'program_id' => 'required|exists:programs,id',
            'career_opportunities' => 'nullable|string',
        ]);

        Institution::create($validated);

        return redirect()->route('institutions.index')->with('success', 'Institution data added successfully.');
    }

    public function show(Institution $institution)
    {
        return Inertia::render('Institutions/Show', [
            'institution' => $institution,
        ]);
    }

    public function edit(Institution $institution)
    {
        return Inertia::render('Institutions/Edit', [
            'institution' => $institution,
            'programs' => Program::all(),
            'schoolYears' => SchoolYear::all(),
        ]);
    }

    public function update(Request $request, Institution $institution)
    {
        $validated = $request->validate([
            'school_year_range' => 'required|string',
            'term' => 'required|string',
            'program_id' => 'required|exists:programs,id',
            'career_opportunities' => 'nullable|string',
        ]);

        $institution->update($validated);

        return redirect()->route('institutions.index')->with('success', 'Institution data updated successfully.');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();

        return redirect()->route('institutions.index')->with('success', 'Institution deleted successfully.');
    }
}