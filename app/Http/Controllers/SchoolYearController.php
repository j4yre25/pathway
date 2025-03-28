<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear; // Ensure you have a SchoolYear model
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
            'school_year_range' => [
                'required',
                'string',
                'regex:/^\d{4}-\d{4}$/', // Ensure format YYYY-YYYY
                function ($attribute, $value, $fail) {
                    $years = explode('-', $value);
                    if (count($years) !== 2 || (int)$years[0] >= (int)$years[1]) {
                        throw ValidationException::withMessages([
                            'school_year_range' => 'Invalid format. The first year must be lower than the second (e.g., 2023-2024).'
                        ]);
                    }
                }
            ],
            'term' => 'required|string|max:255',
        ]);

        // Check for duplicate school year & term combination
        if (SchoolYear::where('school_year_range', $request->school_year_range)
            ->where('term', $request->term)
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'school_year_range' => 'This school year and term combination already exists.'
            ]);
        }

        SchoolYear::create($validated);

        return back()->with('success', 'School year added successfully.');
    }

    public function update(Request $request, SchoolYear $schoolYear)
    {
        $validated = $request->validate([
            'school_year_range' => [
                'required',
                'string',
                'regex:/^\d{4}-\d{4}$/',
                function ($attribute, $value, $fail) {
                    $years = explode('-', $value);
                    if (count($years) !== 2 || (int)$years[0] >= (int)$years[1]) {
                        throw ValidationException::withMessages([
                            'school_year_range' => 'Invalid format. The first year must be lower than the second (e.g., 2023-2024).'
                        ]);
                    }
                }
            ],
            'term' => 'required|string|max:255',
        ]);

        // Check for duplicate excluding the current record
        if (SchoolYear::where('school_year_range', $request->school_year_range)
            ->where('term', $request->term)
            ->where('id', '!=', $schoolYear->id)
            ->exists()
        ) {
            throw ValidationException::withMessages([
                'school_year_range' => 'This school year and term combination already exists.'
            ]);
        }

        $schoolYear->update($validated);

        return back()->with('success', 'School year updated successfully.');
    }

    public function destroy(SchoolYear $schoolYear)
    {
        $schoolYear->delete();

        return back()->with('success', 'School year deleted successfully.');
    }
}
