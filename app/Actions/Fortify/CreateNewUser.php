<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'user_type' => ['required', 'string', 'in:peso,graduate,company,institution'],
        ];
    
        switch ($input['user_type']) {
            case 'graduate':
                $rules['graduate_first_name'] = ['required', 'string', 'max:255'];
                $rules['graduate_last_name'] = ['required', 'string', 'max:255'];
                $rules['graduate_school_graduated_from'] = ['required', 'string'];
                $rules['graduate_program_completed'] = ['required', 'string'];
                $rules['graduate_year_graduated'] = ['required', 'string'];
                $rules['graduate_skills'] = ['required', 'string'];
                break;
            case 'company':
                $rules['company_name'] = ['required', 'string'];
                $rules['company_address'] = ['required', 'string'];
                $rules['company_sector'] = ['required', 'string'];
                $rules['company_category'] = ['required', 'string'];
                $rules['company_contact_number'] = ['required', 'string'];
                $rules['company_hr_last_name'] = ['required', 'string', 'max:255'];
                $rules['company_hr_first_name'] = ['required', 'string', 'max:255'];
                $rules['company_hr_middle_initial'] = ['required', 'string'];
                break;
            case 'institution':
                $rules['institution_type'] = ['required', 'string'];
                $rules['institution_address'] = ['required', 'string'];
                $rules['institution_contact_number'] = ['required', 'string'];
                $rules['institution_president_last_name'] = ['required', 'string', 'max:255'];
                $rules['institution_president_first_name'] = ['required', 'string', 'max:255'];
                $rules['institution_career_officer_first_name'] = ['required', 'string', 'max:255'];
                $rules['institution_career_officer_first_name'] = ['required', 'string', 'max:255'];
                $rules['institution_address'] = ['required', 'string'];
                $rules['institution_address'] = ['required', 'string'];
                break;
        }
    
        Validator::make($input, $rules)->validate();
    
        return User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'user_type' => $input['user_type'],
            // Add other fields based on user type
            'graduate_first_name' => $input['user_type'] === 'graduate' ? $input['graduate_first_name'] : null,
            'graduate_last_name' => $input['user_type'] === 'graduate' ? $input['graduate_last_name'] : null,
            'graduate_program_completed' => $input['user_type'] === 'graduate' ? $input['graduate_program_completed'] : null,
            'graduate_graduate_year_graduated' => $input['user_type'] === 'graduate' ? $input['graduate_school_graduated_from'] : null,
            'graduate_skills' => $input['user_type'] === 'graduate' ? $input['graduate_skills'] : null,
            'company_name' => $input['user_type'] === 'company' ? $input['company_name'] : null,
            'company_address' => $input['user_type'] === 'company' ? $input['company_address'] : null,
            'company_sector' => $input['user_type'] === 'company' ? $input['company_sector'] : null,
            'company_category' => $input['user_type'] === 'company' ? $input['company_category'] : null,
            'company_contact_number' => $input['user_type'] === 'company' ? $input['company_contact_number'] : null,
            'company_hr_last_name' => $input['user_type'] === 'company' ? $input['company_hr_last_name'] : null,
            'company_hr_first_name' => $input['user_type'] === 'company' ? $input['company_hr_first_name'] : null,
            'company_hr_middle_initial' => $input['user_type'] === 'company' ? $input['company_hr_middle_initial'] : null,
            'institution_type' => $input['user_type'] === 'institution' ? $input['institution_type'] : null,
            'institution_address' => $input['user_type'] === 'institution' ? $input['institution_address'] : null,
            'institution_contact_number' => $input['user_type'] === 'institution' ? $input['institution_contact_number'] : null,
            'institution_president_last_name' => $input['user_type'] === 'institution' ? $input['institution_president_last_name'] : null,
            'institution_president_first_name' => $input['user_type'] === 'institution' ? $input['institution_president_first_name'] : null,
            'institution_career_officer_first_name' => $input['user_type'] === 'institution' ? $input['institution_career_officer_first_name'] : null,
        ]);
    }
}
