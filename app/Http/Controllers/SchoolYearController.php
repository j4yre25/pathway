<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear; // Ensure you have a SchoolYear model
use Illuminate\Http\Request;
use Inertia\Inertia;

class SchoolYearController extends Controller
{
    public function index()
    {
        return Inertia::render('Institutions/SchoolYearModal', [
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

    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'school_year_range' => 'required|string|max:255',
            'term' => 'required|string|max:255',
        ]);

        $schoolYear->update($validated);

    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

    }
}