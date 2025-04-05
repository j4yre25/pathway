<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CareerGoalsController extends Controller
{
    // Save career goals
    public function saveCareerGoals(Request $request)
    {
        $request->validate([
            'shortTermGoals' => 'nullable|string',
            'longTermGoals' => 'nullable|string',
            'industriesOfInterest' => 'array',
            'careerPath' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->careerGoals()->update($request->all()); // Assuming you have a relationship set up

        return response()->json(['message' => 'Career goals saved successfully.']);
    }
}