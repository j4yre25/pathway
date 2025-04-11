<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EducationUpdateRequest;
use App\Http\Requests\SkillUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Education;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\Achievement;
use App\Models\EmploymentPreference;
use App\Models\Testimonial;
use App\Models\Project;


class ProfileController extends Controller
{
    // Show the profile settings page
    public function index()
    {
        $user = Auth::user();
     
     
        return Inertia::render('Frontend/Profile', [
            'user' => Auth::user(),
            'educationEntries' => Education::where('user_id', $user->id)->get(),
            'experienceEntries' => Experience::where('user_id', $user->id)->get(), 
            'skillEntries' => Skill::where('user_id', $user->id)->get(),
            'certificationEntries' => Certification::where('user_id', $user->id)->get(),
            'achievementEntries' => Achievement::where('user_id', $user->id)->get(),
            'testimonialEntries' => Testimonial::where('user_id', $user->id)->get(), 
            'employmentPreferences' => EmploymentPreference::where('user_id', $user->id)->first(),
            'careerGoals' => Auth::user()->careerGoals,
            'resume' => Auth::user()->resume,
        ]);
    }

    // Update profile information
    public function updateProfile(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'graduate_first_name' => 'required|string|max:255',
            'graduate_middle_initial' => 'nullable|string|max:1',
            'graduate_last_name' => 'required|string|max:255',
            'graduate_professional_title' => 'required|string|max:255',
            'graduate_location' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'contact_number' => 'nullable|string|max:15',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'graduate_ethnicity' => 'nullable|string|max:255',
            'graduate_address' => 'nullable|string|max:255',
            'graduate_about_me' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $user->update($validated);
        //dd('Updated User Data:', $user->toArray());

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // Add education
    public function addEducation(Request $request)
    {
        $request->validate([
            'graduate_education_institution_id' => 'required|string|max:255',
            'graduate_education_program' => 'required|string|max:255',
            'graduate_education_field_of_study' => 'required|string|max:255',
            'graduate_education_start_date' => 'required|date',
            'graduate_education_end_date' => 'nullable|date', // Allow null
            'graduate_education_description' => 'nullable|string',
            'is_current' => 'boolean',
            'achievements' => 'nullable|string',
            'no_achievements' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($request->is_current) {
            $data['graduate_education_end_date'] = null; // Use null for "present"
        }

        Log::info('Data being saved:', $data); // Debugging: Log the data

        $education = new Education($data);
        $education->user_id = Auth::id();

        try {
            $education->save();
            return Redirect()->back()->with('flash.banner', 'Education added successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving education:', ['error' => $e->getMessage()]); // Debugging: Log the error
            return Redirect()->back()->with('flash.banner', 'An error occurred while adding education. Please try again.');
        }
    }

    // Update education
    public function updateEducation(Request $request, $id)
    {
        $request->validate([
            'graduate_education_institution_id' => 'required|string|max:255',
            'graduate_education_program' => 'required|string|max:255',
            'graduate_education_field_of_study' => 'required|string|max:255',
            'graduate_education_start_date' => 'required|date',
            'graduate_education_end_date' => 'nullable|date',
            'graduate_education_description' => 'nullable|string',
            'is_current' => 'boolean',
            'achievements' => 'nullable|string',
            'no_achievements' => 'nullable|boolean', // Add this validation rule
        ]);

        $data = $request->all();

        // Handle `is_current` logic
        if ($request->is_current) {
            $data['graduate_education_end_date'] = 'present';
        }

        $education = Education::findOrFail($id);
        $education->update($data);

        return Redirect()->back()->with('flash.banner', 'Education updated successfully.');
    }

    // Remove education
    public function removeEducation($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return Redirect()->back()->with('flash.banner', 'Education removed successfully.');
    }

    // Add experience
    public function addExperience(Request $request)
    {
        $request->validate([
            'graduate_experience_title' => 'required|string|max:255',
            'graduate_experience_company' => 'required|string|max:255',
            'graduate_experience_start_date' => 'required|date',
            'graduate_experience_end_date' => 'nullable|date',
            'graduate_experience_address' => 'nullable|string|max:255',
            'graduate_experience_description' => 'nullable|string',
            'graduate_experience_employment_type' => 'nullable|string|max:255', // Keep this line
            'is_current' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->is_current) {
            $data['graduate_experience_end_date'] = null; 
        }

        $data['graduate_experience_description'] = $data['graduate_experience_description'] ?? 'No description provided';

        $experience = new Experience($data);
        $experience->user_id = Auth::id();

        try {
            $experience->save();
            return redirect()->back()->with('flash.banner', 'Experience added successfully.');
        } catch (\Exception $e) {
            Log::error('Error saving experience:', ['error' => $e->getMessage()]); 
            return redirect()->back()->with('flash.banner', 'An error occurred while adding experience. Please try again.');
        }
    }

    // Update experience
    public function updateExperience(Request $request, $id)
    {
        $request->validate([
            'graduate_experience_title' => 'required|string|max:255',
            'graduate_experience_company' => 'required|string|max:255',
            'graduate_experience_start_date' => 'required|date',
            'graduate_experience_end_date' => 'nullable|date',
            'graduate_experience_address' => 'nullable|string|max:255',
            'graduate_experience_description' => 'nullable|string',
            'graduate_experience_employment_type' => 'nullable|string|max:255', 
            'is_current' => 'boolean',
        ]);

        $data = $request->all();
        
        if ($request->is_current) {
            $data['graduate_experience_end_date'] = null; 
        }

        $experience = Experience::findOrFail($id);
        $experience->update($data);

        return redirect()->back()->with('flash.banner', 'Experience updated successfully.');
    }

    // Remove experience
    public function removeExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return Redirect()->back()->with('flash.banner', 'Experience removed successfully.');
    }

    // Add skill
    public function addSkill(Request $request)
    {
        Log::info('Request data:', $request->all()); // Log the incoming request data
    
        $request->validate([
            'graduate_skills_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Skill::where('user_id', Auth::id())
                        ->where('graduate_skills_name', $value)
                        ->exists();
                    if ($exists) {
                        $fail('The skill "' . $value . '" already exists.');
                    }
                },
            ],
            'graduate_skills_proficiency_type' => 'required|string|in:Beginner,Intermediate,Advanced,Expert', // Restrict to specific values
            'graduate_skills_type' => 'required|string|max:255',
            'graduate_skills_years_experience' => 'required|integer|min:0',
        ]);
    
        // Create a new skill instance
        $skill = new Skill();
        $skill->graduate_skills_name = $request->graduate_skills_name;
        $skill->graduate_skills_proficiency_type = $request->graduate_skills_proficiency_type; // Ensure this is set correctly
        $skill->graduate_skills_type = $request->graduate_skills_type;
        $skill->graduate_skills_years_experience = $request->graduate_skills_years_experience;
        $skill->user_id = Auth::id(); // Associate with the authenticated user
        $skill->save();
    
        return Redirect()->back()->with('flash.banner', 'Skill added successfully.');
    }
    // Update skilL
    public function updateSkill(Request $request, $id)
{
    Log::info('Update request data:', $request->all());

    $request->validate([
        'graduate_skills_name' => [
            'required',
            'string',
            'max:255',
            function ($attribute, $value, $fail) use ($id) {
                $exists = Skill::where('user_id', Auth::id())
                    ->where('graduate_skills_name', $value)
                    ->where('id', '!=', $id)
                    ->exists();
                if ($exists) {
                    $fail('The skill "' . $value . '" already exists.');
                }
            },
        ],
        'graduate_skills_proficiency_type' => 'required|string|in:Beginner,Intermediate,Advanced,Expert',
        'graduate_skills_type' => 'required|string|max:255',
        'graduate_skills_years_experience' => 'required|integer|min:0',
    ]);

    $skill = Skill::findOrFail($id); 

    $skill->graduate_skills_name = $request->graduate_skills_name;
    $skill->graduate_skills_proficiency_type = $request->graduate_skills_proficiency_type;
    $skill->graduate_skills_type = $request->graduate_skills_type;
    $skill->graduate_skills_years_experience = $request->graduate_skills_years_experience;
    $skill->user_id = Auth::id();

    $skill->save();

    return redirect()->back()->with('flash.banner', 'Skill updated successfully.');
}

    // Remove skill
    public function removeSkill($id)
    {
        
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return Redirect()->back()->with('flash.banner', 'Skill added successfully.');
    }

    // Add project
    public function addProject(Request $request)
    {
        $request->validate([
            'graduate_projects_title' => 'required|string|max:255',
            'graduate_projects_description' => 'required|string',
            'graduate_projects_role' => 'required|string|max:255',
            'graduate_projects_start_date' => 'required|date',
            'graduate_projects_end_date' => 'nullable|date',
            'graduate_projects_url' => 'nullable|url|max:255',
            'graduate_projects_tech' => 'nullable|array',
            'graduate_projects_key_accomplishments' => 'nullable|string',
        ]);

        $project = new Project($request->all());
        $project->user_id = Auth::id(); // Assuming you have a user_id field
        $project->save();

        return redirect()->back()->with('flash.banner', 'Project added successfully.');
    }

    // Update an existing project
    public function updateProject(Request $request, $id)
    {
        $request->validate([
            'graduate_projects_title' => 'required|string|max:255',
            'graduate_projects_description' => 'required|string',
            'graduate_projects_role' => 'required|string|max:255',
            'graduate_projects_start_date' => 'required|date',
            'graduate_projects_end_date' => 'nullable|date',
            'graduate_projects_url' => 'nullable|url|max:255',
            'graduate_projects_tech' => 'nullable|array',
            'graduate_projects_key_accomplishments' => 'nullable|string',
        ]);

        $project = Project::findOrFail($id);
        $project->update($request->all());

        return redirect()->back()->with('flash.banner', 'Project updated successfully.');
    }

    // Remove a project
    public function removeProject($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->back()->with('flash.banner', 'Project removed successfully.');
    }


    // Add certification
    public function addCertification(Request $request)
    {
        $request->validate([
            'graduate_certification_name' => 'required|string|max:255',
            'graduate_certification_issuer' => 'required|string|max:255',
            'graduate_certification_issue_date' => 'required|date',
            'graduate_certification_expiry_date' => 'nullable|date',
            'graduate_certification_credential_id' => 'nullable|string|max:255',
            'graduate_certification_credential_url' => 'nullable|string|max:255',
        ]);

        $certification = new Certification($request->all());
        $certification->user_id = Auth::user()->id(); // Assuming you have a user_id field
        $certification->save();

        return Redirect()->back()->with('flash.banner', 'Certification added successfully.');
    }

    // Update certification
    public function updateCertification(Request $request, $id)
    {
        $request->validate([
            'graduate_certification_name' => 'required|string|max:255',
            'graduate_certification_issuer' => 'required|string|max:255',
            'graduate_certification_issue_date' => 'required|date',
            'graduate_certification_expiry_date' => 'nullable|date',
            'graduate_certification_credential_id' => 'nullable|string|max:255',
            'graduate_certification_credential_url' => 'nullable|string|max:255',
        ]);

        $certification = Certification::findOrFail($id);
        $certification->update($request->all());

        return Redirect()->back();    }

    // Remove certification
    public function removeCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return Redirect()->back()->with('flash.banner', 'Certification removed successfully.');
    }

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
        $achievement->user_id = Auth::user()->id(); // Assuming you have a user_id field $achievement->save();

        return Redirect()->back()->with('flash.banner', 'Achievement added successfully.');
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

        return Redirect()->back();    
    }

    // Remove achievement
    public function removeAchievement($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return Redirect()->back()->with('flash.banner', 'Achievement removed successfully.');
    }

    // Add testimonial
    public function addTestimonial(Request $request)
    {
        $request->validate([
            'graduate_testimonials_name' => 'required|string|max:255',
            'graduate_testimonials_role_title' => 'required|string|max:255',
            'graduate_testimonials_relationship' => 'required|string|max:255',
            'graduate_testimonials_testimonial' => 'required|string',
        ]);

        $testimonial = new Testimonial($request->all());
        $testimonial->user_id = Auth::user()->id(); // Assuming you have a user_id field
        $testimonial->save();

        return Redirect()->back()->with('flash.banner', 'Testimonial added successfully.');
    }

    // Update testimonial
    public function updateTestimonial(Request $request, $id)
    {
        $request->validate([
            'graduate_testimonials_name' => 'required|string|max:255',
            'graduate_testimonials_role_title' => 'required|string|max:255',
            'graduate_testimonials_relationship' => 'required|string|max:255',
            'graduate_testimonials_testimonial' => 'required|string',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update($request->all());

        return Redirect()->back();    
    }

    // Remove testimonial
    public function removeTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return Redirect()->back()->with('flash.banner', 'Testimonial removed successfully.');
    }

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

        $user = Auth::user();
        $user->employmentPreferences()->update($request->all()); // Assuming you have a relationship set up

         return Redirect()->back()->with('flash.banner', 'Employment preferences updated successfully.');
    }

    // Save career goals
    public function saveCareerGoals(Request $request)
    {
        $request->validate([
            'shortTermGoals' => 'nullable|string',
            'longTermGoals' => 'nullable|string',
            'industriesOfInterest' => 'array',
            'careerPath' => 'nullable|string',
        ]);

        $user = Auth::user();
        $user->careerGoals()->update($request->all()); // Assuming you have a relationship set up

        return Redirect()->back()->with('flash.banner', 'Career goals saved successfully.');
    }

    // Upload resume
    public function uploadResume(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $user = Auth::user();
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('resumes', 'public');
            $user->resume()->update(['file' => $path]); // Assuming you have a relationship set up
        }

        return Redirect()->back()->with('flash.banner', 'Resume uploaded successfully.');
    }

    // Remove resume
    public function removeResume()
    {
        $user = Auth::user();
        $user->resume()->delete(); // Assuming you have a relationship set up

        return response()->json(['message' => 'Resume removed successfully.']);
    }
}