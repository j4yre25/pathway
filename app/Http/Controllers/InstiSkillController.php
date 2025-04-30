<?php

namespace App\Http\Controllers;

use App\Models\InstiSkill;
use App\Models\Program;
use App\Models\CareerOpportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InstiSkillController extends Controller
{
    public function index(User $user)
    {
        $instiskills = $user->instiSkills()->with(['programs', 'careerOpportunities'])->withTrashed()->get();

        return Inertia::render('Institutions/InstiSkills/Index', [
            'skills' => $instiskills
        ]);
    }

    public function list(Request $request)
    {
        $status = $request->input('status', 'all');

        $instiskills = InstiSkill::with(['user', 'programs', 'careerOpportunities'])->withTrashed()
            ->when($status === 'active', fn($q) => $q->whereNull('deleted_at'))
            ->when($status === 'inactive', fn($q) => $q->whereNotNull('deleted_at'))
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/SkillList', [
            'skills' => $instiskills,
            'status' => $status
        ]);
    }

    public function archivedList()
    {
        $instiskills = InstiSkill::with(['user', 'programs', 'careerOpportunities'])->onlyTrashed()
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/ArchivedSkills', [
            'all_skills' => $instiskills
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:instiskills,name'],
            'program_ids' => ['required', 'array'],
            'program_ids.*' => ['exists:programs,id'],
            'career_opportunity_ids' => ['required', 'array'],
            'career_opportunity_ids.*' => ['exists:career_opportunities,id'],
        ]);

        $instiskill = InstiSkill::create([
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        $instiskill->programs()->attach($request->program_ids);
        $instiskill->careerOpportunities()->attach($request->career_opportunity_ids);

        return back()->with('success', 'Skill added.');
    }

    public function destroy(InstiSkill $instiskill)
    {
        $instiskill->delete();
        return back()->with('success', 'Skill archived.');
    }

    public function restore($id)
    {
        $instiskill = InstiSkill::withTrashed()->findOrFail($id);
        $instiskill->restore();

        return back()->with('success', 'Skill restored.');
    }
}
