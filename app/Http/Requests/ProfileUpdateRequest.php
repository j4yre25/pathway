<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'graduate_first_name' => ['required', 'string', 'max:255'],
            'graduate_middle_initial' => ['nullable', 'string', 'max:1'],
            'graduate_last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'professional_title' => ['nullable', 'string', 'max:255'],
            'personal_summary' => ['nullable', 'string'],
            'experience_level' => ['nullable', 'string'],
            'graduate_skills' => ['nullable', 'array'],
            'graduate_skills.*' => ['string', 'max:255'],
            'experience' => ['nullable', 'array'],
            'education' => ['nullable', 'array'],
            'aboutMe' => ['nullable', 'array'],
            'personal_summary' => ['nullable', 'string'],
            'aboutMe.education' => ['nullable', 'string'],
            'aboutMe.experience_level' => ['nullable', 'string'],
            'aboutMe.career_history' => ['nullable', 'string'],
            'aboutMe.skills' => ['nullable', 'array'],
            'nextRole' => ['nullable', 'array'],
            'nextRole.availability' => ['nullable', 'string'],
            'nextRole.work_types' => ['nullable', 'array'],
            'nextRole.locations' => ['nullable', 'array'],
            'nextRole.right_to_work' => ['nullable', 'string'],
            'nextRole.salary_expectation' => ['nullable', 'string'],
            'nextRole.sectors' => ['nullable', 'array'],
        ];
    }
}
