<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;


class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();
   
        
        // Get related data for the profile
        $aboutMe = $user->aboutMe;
     
        return Inertia::render('Frontend/Profile', [
            'user' => $user,
            'aboutMe' => $aboutMe,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.update');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Upload portfolio files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPortfolio(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'files' => ['required', 'array'],
            'files.*' => ['file', 'max:5120'], // 5MB max
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('portfolios/' . $user->id, 'public');
            
            $portfolio = new Portfolio([
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);

        }

        return redirect()->back();
    }

    /**
     * Create a portfolio with work samples and awards.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createPortfolio(Request $request)
    {
        $user = Auth::user();

        
        $validated = $request->validate([
            'work_samples' => ['nullable', 'array'],
            'work_samples.*.title' => ['required', 'string', 'max:255'],
            'work_samples.*.description' => ['nullable', 'string'],
            'work_samples.*.file' => ['nullable', 'file', 'max:5120'], // 5MB max
            
            'awards' => ['nullable', 'array'],
            'awards.*.name' => ['required', 'string', 'max:255'],
            'awards.*.file' => ['nullable', 'file', 'max:5120'], // 5MB max
        ]);

        // Process work samples
        if (isset($validated['work_samples'])) {
            foreach ($validated['work_samples'] as $sample) {
                $filePath = null;
                if (isset($sample['file']) && $sample['file']) {
                    $filePath = $sample['file']->store('portfolios/' . $user->id . '/samples', 'public');
                }
                
                $portfolio = new Portfolio([
                    'user_id' => $user->id,
                    'type' => 'work_sample',
                    'title' => $sample['title'],
                    'description' => $sample['description'] ?? null,
                    'file_path' => $filePath,
                    'file_name' => $filePath ? $sample['file']->getClientOriginalName() : null,
                    'file_type' => $filePath ? $sample['file']->getClientMimeType() : null,
                    'file_size' => $filePath ? $sample['file']->getSize() : null,
                ]);

                // Fix: Use portfolioItems() relationship method
            }
        }

        // Process awards
        if (isset($validated['awards'])) {
            foreach ($validated['awards'] as $award) {
                $filePath = null;
                if (isset($award['file']) && $award['file']) {
                    $filePath = $award['file']->store('portfolios/' . $user->id . '/awards', 'public');
                }
                
                $portfolio = new Portfolio([
                    'user_id' => $user->id,
                    'type' => 'award',
                    'title' => $award['name'],
                    'file_path' => $filePath,
                    'file_name' => $filePath ? $award['file']->getClientOriginalName() : null,
                    'file_type' => $filePath ? $award['file']->getClientMimeType() : null,
                    'file_size' => $filePath ? $award['file']->getSize() : null,
                ]);

                // Fix: Use portfolioItems() relationship method
            }
        }

        return redirect()->back();
    }
}