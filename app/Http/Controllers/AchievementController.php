<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;

class AchievementController extends Controller
{
    // Add achievement
    public function addAchievement(Request $request)
    {
        $request->validate([
            'graduate_achievement_title' => 'required|string|max:255',
            'graduate_achievement_issuer' => 'required|string|max:255',
            'graduate_achievement_date' => 'required|date',
            'graduate_achievement_description' => 'nullable|string',
            'graduate_achievement_url' => 'nullable|string|max:255',
            'graduate_achievement_type' => 'nullable|string|max:255',
        ]);

        $achievement = new Achievement($request->all());
        $achievement->user_id = auth()->id(); // Assuming you have a user_id field
        $achievement->save();

        return response()->json(['message' => 'Achievement added successfully.']);
    }

    // Update achievement
    public function updateAchievement(Request $request, $id)
    {
        $request->validate([
            'graduate_achievement_title' => 'required|string|max:255',
            'graduate_achievement_issuer' => 'required|string|max:255',
            'graduate_achievement_date' => 'required|date',
            'graduate_achievement_description' => 'nullable|string',
            'graduate_achievement_url' => 'nullable|string|max:255',
            'graduate_achievement_type' => 'nullable|string|max:255',
        ]);

        $achievement = Achievement::findOrFail($id);
        $achievement->update($request->all());

        return response()->json(['message' => 'Achievement updated successfully.']);
    }

    // Remove achievement
    public function removeAchievement($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return response()->json(['message' => 'Achievement removed successfully.']);
    }
}