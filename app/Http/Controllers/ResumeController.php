<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ResumeController extends Controller
{
    // Upload resume
    public function uploadResume(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $user = auth()->user();
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resumes', 'public');
            $user->resume()->update(['file' => $path]); // Assuming you have a relationship set up
        }

        return response()->json(['message' => 'Resume uploaded successfully.']);
    }

    // Remove resume
    public function removeResume()
    {
        $user = auth()->user();
        $user->resume()->delete(); // Assuming you have a relationship set up

        return response()->json(['message' => 'Resume removed successfully.']);
    }
}