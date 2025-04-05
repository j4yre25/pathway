<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EducationUpdateRequest;
use App\Http\Requests\SkillUpdateRequest;
use App\Models\User;
use App\Models\Education;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\Achievement;
use App\Models\Testimonial;

class ProfileController extends Controller
{
    // Show the profile settings page
    public function index()
    {
        $user = Auth::user();
     
     
        return Inertia::render('Frontend/Profile', [
            'user' => $user,
        ]);
    }

    // Update profile information
    public function updateProfile(Request $request)
    {
        $request->validate([
            'fullName' => 'required|string|max:255',
            'graduate_professional_title' => 'nullable|string|max:255',
            'graduate_email' => 'required|email|max:255',
            'graduate_phone' => 'nullable|string|max:20',
            'graduate_location' => 'nullable|string|max:255',
            'graduate_birthdate' => 'nullable|date',
            'graduate_gender' => 'nullable|string|max:10',
            'graduate_ethnicity' => 'nullable|string|max:255',
            'graduate_address' => 'nullable|string|max:255',
            'graduate_about_me' => 'nullable|string',
            'graduate_picture_url' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $user->update($request->all());

        return response()->json(['message' => 'Profile updated successfully.']);
    }

    // Add education
    public function addEducation(Request $request)
    {
        $request->validate([
            'graduate_education_institution_id' => 'required|string|max:255',
            'graduate_education_program' => 'required|string|max:255',
            'graduate_education_field_of_study' => 'required|string|max:255',
            'graduate_education_start_date' => 'required|date',
            'graduate_education_end_date' => 'nullable|date',
            'graduate_education_description' => 'nullable|string',
        ]);

        $education = new Education($request->all());
        $education->user_id = auth()->id(); // Assuming you have a user_id field
        $education->save();

        return response()->json(['message' => 'Education added successfully.']);
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
        ]);

        $education = Education::findOrFail($id);
        $education->update($request->all());

        return response()->json(['message' => 'Education updated successfully.']);
    }

    // Remove education
    public function removeEducation($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return response()->json(['message' => 'Education removed successfully.']);
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
            'graduate_experience_achievements' => 'nullable|string',
            'graduate_experience_skills_tech' => 'nullable|string',
        ]);

        $experience = new Experience($request->all());
        $experience->user_id = auth()->id(); // Assuming you have a user_id field
        $experience->save();

        return response()->json(['message' => 'Experience added successfully.']);
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
            'graduate_experience_achievements' => 'nullable|string',
            'graduate_experience_skills_tech' => 'nullable|string',
        ]);

        $experience = Experience::findOrFail($id);
        $experience->update($request->all());

        return response()->json(['message' => 'Experience updated successfully.']);
    }

    // Remove experience
    public function removeExperience($id)
    {
        $experience = Experience::findOrFail($id);
        $experience->delete();

        return response()->json(['message' => 'Experience removed successfully.']);
    }

    // Add skill
    public function addSkill(Request $request)
    {
        $request->validate([
            'graduate_skills_name' => 'required|string|max:255',
            'graduate_skills_proficiency' => 'required|integer|min:1|max:100',
            'graduate_skills_type' => 'required|string|max:255',
            'graduate_skills_years_experience' => 'required|integer|min:0',
        ]);

        $skill = new Skill($request->all());
        $skill->user_id = auth()->id(); // Assuming you have a user_id field
        $skill->save();

        return response()->json(['message' => 'Skill added successfully.']);
    }

    // Update skill
    public function updateSkill(Request $request, $id)
    {
        $request->validate([
            'graduate_skills_name' => 'required|string|max:255',
            'graduate_skills_proficiency' => 'required|integer|min:1|max:100',
            'graduate_skills_type' => 'required|string|max:255',
            'graduate_skills_years_experience' => 'required|integer|min:0',
        ]);

        $skill = Skill::findOrFail($id);
        $skill->update($request->all());

        return response()->json(['message' => 'Skill updated successfully.']);
    }

    // Remove skill
    public function removeSkill($id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json(['message' => 'Skill removed successfully.']);
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
        $certification->user_id = auth()->id(); // Assuming you have a user_id field
        $certification->save();

        return response()->json(['message' => 'Certification added successfully.']);
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

        return response()->json(['message' => 'Certification updated successfully.']);
    }

    // Remove certification
    public function removeCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return response()->json(['message' => 'Certification removed successfully.']);
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
        $achievement->user_id = auth()->id(); // Assuming you have a user_id field $achievement->save();

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
        $testimonial->user_id = auth()->id(); // Assuming you have a user_id field
        $testimonial->save();

        return response()->json(['message' => 'Testimonial added successfully.']);
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

        return response()->json(['message' => 'Testimonial updated successfully.']);
    }

    // Remove testimonial
    public function removeTestimonial($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->delete();

        return response()->json(['message' => 'Testimonial removed successfully.']);
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

        $user = auth()->user();
        $user->employmentPreferences()->update($request->all()); // Assuming you have a relationship set up

        return response()->json(['message' => 'Employment preferences updated successfully.']);
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

        $user = auth()->user();
        $user->careerGoals()->update($request->all()); // Assuming you have a relationship set up

        return response()->json(['message' => 'Career goals saved successfully.']);
    }

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