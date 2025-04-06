<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmploymentPreferencesController extends Controller
{
    // Update employment preferences
    public function updateEmploymentPreferences(Request $request)
    {
        $request->validate([
            'jobTypes' => 'array',
            'salaryExpectations' => 'array',
            'preferredLocations' => 'array',
            'workEnvironment' => 'array',
            'availability' => 'array',
            'additionalNotes' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->employmentPreferences()->update($request->all()); // Assuming you have a relationship set up

        return response()->json(['message' => 'Employment preferences updated successfully.']);
    }
}