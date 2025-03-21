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
        'year_graduated' => 'required|numeric|min:1900|max:' . date('Y'), // Ensure it's a number
        'employment_status' => 'required|in:Employed,Underemployed,Unemployed',
        'current_job_title' => 'nullable|string|max:255',
    ]);

    // Convert `year_graduated` to an integer in case it's sent as a string
    $validated['year_graduated'] = (int) $validated['year_graduated'];

    if ($validated['employment_status'] == 'Unemployed') {
        $validated['current_job_title'] = 'N/A'; // ✅ Set 'N/A' for unemployed
    } elseif (empty($validated['current_job_title'])) {
        $validated['current_job_title'] = 'Not Provided'; // ✅ Set default for blank job title
    }    

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
            'program_id' => 'required|exists:programs,id', // Fix validation
            'year_graduated' => 'required|integer|min:1900|max:' . date('Y'),
            'employment_status' => 'required|in:Employed,Underemployed,Unemployed',
            'current_job_title' => 'nullable|string|max:255',
        ]);

        if ($validated['employment_status'] == 'Unemployed') {
            $validated['current_job_title'] = 'N/A';
        }

        $graduate->update($validated);

        return redirect()->route('graduates.index')->with('success', 'Graduate updated successfully.');
    }

    public function destroy(Graduate $graduate)
    {
        $graduate->delete();

        return redirect()->route('graduates.index')->with('success', 'Graduate deleted successfully.');
    }

    public function downloadTemplate()
{
    $filePath = app_path('Templates/graduate_template.csv'); // Correct path

    if (!file_exists($filePath)) {
        abort(404, 'Template not found.');
    }

    return Response::download($filePath, 'graduate_template.csv', [
        'Content-Type' => 'text/csv',
    ]);
}

public function show($id)
{
    $graduate = Graduate::findOrFail($id);
    return response()->json($graduate);
}


public function batchUpload(Request $request)
{
    $request->validate([
        'csv_file' => 'required|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('csv_file');

    $csv = Reader::createFromPath($file->getPathname(), 'r');
    $csv->setHeaderOffset(0);

    $graduates = [];
    foreach ($csv as $row) {
        $validator = Validator::make($row, [
            'Full Name' => 'required|string|max:255',
            'Year Graduated' => 'required|integer|min:1900|max:' . date('Y'),
            'Program' => 'required|string|exists:programs,name',
            'Employment Status' => 'required|in:Employed,Underemployed,Unemployed',
            'Current Job Title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            continue; // Skip invalid rows
        }

        $program = Program::where('name', $row['Program'])->first();

        $graduates[] = [
            'name' => $row['Full Name'],
            'year_graduated' => $row['Year Graduated'],
            'program_id' => $program->id,
            'employment_status' => $row['Employment Status'],
            'current_job_title' => $row['Employment Status'] == 'Unemployed' ? 'N/A' : $row['Current Job Title'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    Graduate::insert($graduates);

    return redirect()->route('graduates.index')->with('success', count($graduates) . ' graduates uploaded successfully.');
}

}
