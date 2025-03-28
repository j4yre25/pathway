<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Graduate;
use App\Models\SchoolYear;
use App\Models\Program; // Ensure this model exists if programs are stored separately
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Illuminate\Support\Facades\Response;

class GraduateController extends Controller
{
    public function index()
{
    return Inertia::render('Graduates/Index', [
        'graduates' => Graduate::with('program')->get()->map(function ($graduate) {
            return [
                'id' => $graduate->id,
                'name' => $graduate->name,
                'program_name' => $graduate->program ? $graduate->program->name : 'N/A', // Fix: Get program name
                'year_graduated' => $graduate->year_graduated . '-' . ($graduate->year_graduated + 1), // Fix: Convert year to range
                'employment_status' => $graduate->employment_status,
                'current_job_title' => $graduate->current_job_title,
            ];
        }),
        'programs' => Program::all(),
        'years' => SchoolYear::pluck('school_year_range') // Fetch only school year values
    ]);
}


public function store(Request $request)
{
    Log::info('Graduate store request received', $request->all());

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'program_id' => 'required|exists:programs,id',
        'year_graduated' => 'required|numeric|min:1900|max:' . date('Y'),
        'employment_status' => 'required|in:Employed,Underemployed,Unemployed',
        'current_job_title' => 'nullable|string|max:255',
    ]);

    // Convert `year_graduated` to integer in case it's sent as a string
    $validated['year_graduated'] = (int) $validated['year_graduated'];

    if ($validated['employment_status'] == 'Unemployed') {
        $validated['current_job_title'] = 'N/A';
    } elseif (empty($validated['current_job_title'])) {
        $validated['current_job_title'] = 'Not Provided';
    }

    // Check if a graduate with the same name, program, and year already exists
    $existingGraduate = Graduate::where('name', $validated['name'])
        ->where('program_id', $validated['program_id'])
        ->where('year_graduated', $validated['year_graduated'])
        ->first();

    if ($existingGraduate) {
        Log::warning('Duplicate graduate entry attempt', $validated);
        return back()->withErrors(['msg' => 'This graduate entry already exists.']);
    }

    // Create new graduate entry
    $graduate = Graduate::create($validated);

    if (!$graduate) {
        Log::error('Failed to save graduate');
        return back()->withErrors(['msg' => 'Failed to save graduate.']);
    }

    Log::info('Graduate saved successfully', ['id' => $graduate->id]);

    return redirect()->route('graduates.index')->with('success', 'Graduate added successfully.');
}


public function update(Request $request, Graduate $graduate)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'program_id' => 'required|exists:programs,id',
        'year_graduated' => 'required|integer|min:1900|max:' . date('Y'),
        'employment_status' => 'required|in:Employed,Underemployed,Unemployed',
        'current_job_title' => 'nullable|string|max:255',
    ]);

    if ($validated['employment_status'] == 'Unemployed') {
        $validated['current_job_title'] = 'N/A';
    }

    // Check if another graduate already exists with the same name, program, and year
    $existingGraduate = Graduate::where('name', $validated['name'])
        ->where('program_id', $validated['program_id'])
        ->where('year_graduated', $validated['year_graduated'])
        ->where('id', '!=', $graduate->id) // Ensure it's not the same record being updated
        ->first();

    if ($existingGraduate) {
        Log::warning('Duplicate graduate update attempt', $validated);
        return back()->withErrors(['msg' => 'A graduate with these details already exists.']);
    }

    $graduate->update($validated);

    return redirect()->route('graduates.index')->with('success', 'Graduate updated successfully.');
}


    public function destroy(Graduate $graduate)
    {
        $graduate->delete();

        return redirect()->route('graduates.index')->with('success', 'Graduate deleted successfully.');
    }

   
public function show($id)
{
    $graduate = Graduate::findOrFail($id);
    return response()->json($graduate);
}


}
