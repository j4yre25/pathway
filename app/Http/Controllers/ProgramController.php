<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Degree;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProgramController extends Controller
{
    public function index(User $user)
    {
        $programs = $user->programs()->with('degree')->withTrashed()->get();

        return Inertia::render('Institutions/Programs/Index', [
            'programs' => $programs
        ]);
    }

    public function list(Request $request)
    {
        $status = $request->input('status', 'all');

        $programs = Program::with(['user', 'degree'])->withTrashed()
            ->when($status === 'active', fn($q) => $q->whereNull('deleted_at'))
            ->when($status === 'inactive', fn($q) => $q->whereNotNull('deleted_at'))
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/ProgramList', [
            'programs' => $programs,
            'status' => $status
        ]);
    }

    public function archivedList()
    {
        $programs = Program::with(['user', 'degree'])->onlyTrashed()
            ->where('user_id', Auth::id())
            ->get();

        return Inertia::render('Institutions/ArchivedPrograms', [
            'all_programs' => $programs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'degree_id' => ['required', 'exists:degrees,id'],
        ]);

        $exists = Program::withTrashed()
            ->where('user_id', Auth::id())
            ->where('name', $request->name)
            ->where('degree_id', $request->degree_id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'This program already exists.']);
        }

        Program::create([
            'name' => $request->name,
            'degree_id' => $request->degree_id,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Program added.');
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'degree_id' => ['required', 'exists:degrees,id'],
        ]);

        $exists = Program::withTrashed()
            ->where('user_id', Auth::id())
            ->where('name', $request->name)
            ->where('degree_id', $request->degree_id)
            ->where('id', '!=', $program->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['msg' => 'Duplicate program entry.']);
        }

        $program->update([
            'name' => $request->name,
            'degree_id' => $request->degree_id,
        ]);

        return back()->with('success', 'Program updated.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return back()->with('success', 'Program archived.');
    }

    public function restore($id)
    {
        $program = Program::withTrashed()->findOrFail($id);
        $program->restore();

        return back()->with('success', 'Program restored.');
    }
}
