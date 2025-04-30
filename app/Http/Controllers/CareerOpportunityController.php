<?php

namespace App\Http\Controllers;

use App\Models\CareerOpportunity;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CareerOpportunityController extends Controller
{
    public function index(User $user)
    {
        $careerOpportunities = $user->careerOpportunities()->with('programs')->withTrashed()->get();

        return Inertia::render('Institutions/CareerOpportunities/Index', [
            'careerOpportunities' => $careerOpportunities
        ]);
    }

    public function list(Request $request)
    {
        $status = $request->input('status', 'all');

        $careerOpportunities = CareerOpportunity::with(['programs', 'user'])->withTrashed()
            ->when($status === 'active', fn($q) => $q->whereNull('deleted_at'))
            ->when($status === 'inactive', fn($q) => $q->whereNotNull('deleted_at'))
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/CareerOpportunityList', [
            'careerOpportunities' => $careerOpportunities,
            'status' => $status
        ]);
    }

    public function archivedList()
    {
        $careerOpportunities = CareerOpportunity::with(['user', 'programs'])->onlyTrashed()
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/ArchivedCareerOpportunities', [
            'all_career_opportunities' => $careerOpportunities
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'program_ids' => ['required', 'array'],
            'program_ids.*' => ['exists:programs,id'],
        ]);

        $titles = array_map('trim', explode(',', $request->title));

        foreach ($titles as $title) {
            $existing = CareerOpportunity::withTrashed()
                ->whereRaw('LOWER(title) = ?', [strtolower($title)])
                ->where('user_id', Auth::id())
                ->first();

            if ($existing) {
                $existing->restore();
                $existing->programs()->syncWithoutDetaching($request->program_ids);
            } else {
                $co = CareerOpportunity::create([
                    'title' => $title,
                    'user_id' => Auth::id()
                ]);
                $co->programs()->attach($request->program_ids);
            }
        }

        return back()->with('success', 'Career opportunity saved.');
    }

    public function destroy(CareerOpportunity $careerOpportunity)
    {
        $careerOpportunity->delete();
        return back()->with('success', 'Career opportunity archived.');
    }

    public function restore($id)
    {
        $co = CareerOpportunity::withTrashed()->findOrFail($id);
        $co->restore();
        return back()->with('success', 'Career opportunity restored.');
    }
}
