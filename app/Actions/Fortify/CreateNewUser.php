<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request; 


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
        $role = $this->determineRole(request());
    
        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ];
    
        // Add role-specific validation rules
        switch ($role) {
            case 'graduate':
                $rules['graduate_first_name'] = ['required', 'string', 'max:255'];
                $rules['graduate_last_name'] = ['required', 'string', 'max:255'];
                break;
            case 'company':
                $rules['company_name'] = ['required', 'string', 'max:255'];
                $rules['company_street_address'] = ['required', 'string', 'max:255'];
                $rules['company_brgy'] = ['required', 'string', 'max:255'];
                $rules['company_city'] = ['required', 'string', 'max:255'];
                $rules['company_province'] = ['required', 'string', 'max:255'];
                $rules['company_zip_code'] = ['required', 'string', 'max:4'];
                $rules['company_contact_number'] = ['required', 'digits_between:10,15'];
                $rules['company_telephone_number'] = ['nullable', 'digits_between:7,15'];
                $rules['company_hr_full_name'] = ['required', 'string', 'max:255'];
                $rules['company_hr_gender'] = ['required', 'string', 'in:Male,Female,Other'];
                $rules['company_hr_dob'] = ['required', 'date'];
                $rules['company_hr_contact_number'] = ['required', 'digits_between:10,15'];
                break;
            case 'institution':
                $rules['institution_type'] = ['required', 'string'];
                $rules['institution_name'] = ['required', 'string'];
                $rules['institution_address'] = ['required', 'string'];
                $rules['institution_contact_number'] = ['required', 'string'];
                $rules['institution_president_last_name'] = ['required', 'string', 'max:255'];
                $rules['institution_president_first_name'] = ['required', 'string', 'max:255'];
                $rules['institution_career_officer_first_name'] = ['required', 'string', 'max:255'];
                break;
            default:
                // Handle the guest role
                break;
        }
    
        Validator::make($input, $rules)->validate();
    
        $userData = [
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $role,
        ];
    
        // Add role-specific fields
        switch ($role) {
            case 'graduate':
                $userData['graduate_first_name'] = $input['graduate_first_name'];
                $userData['graduate_last_name'] = $input['graduate_last_name'];
                break;
            case 'company':
                $userData['company_name'] = $input['company_name'];
                $userData['company_street_address'] = $input['company_street_address'];
                $userData['company_brgy'] = $input['company_brgy'];
                $userData['company_city'] = $input['company_city'];
                $userData['company_province'] = $input['company_province'];
                $userData['company_zip_code'] = $input['company_zip_code'];
                $userData['company_contact_number'] = $input['company_contact_number'];
                $userData['company_telephone_number'] = $input['company_telephone_number'];
                $userData['company_hr_full_name'] = $input['company_hr_full_name'];
                $userData['company_hr_gender'] = $input['company_hr_gender'];
                $userData['company_hr_dob'] = $input['company_hr_dob'];
                $userData['company_hr_contact_number'] = $input['company_hr_contact_number'];
                break;
            case 'institution':
                $userData['institution_type'] = $input['institution_type'];
                $userData['institution_name'] = $input['institution_name'];
                $userData['institution_address'] = $input['institution_address'];
                $userData['institution_contact_number'] = $input['institution_contact_number'];
                $userData['institution_president_last_name'] = $input['institution_president_last_name'];
                $userData['institution_president_first_name'] = $input['institution_president_first_name'];
                $userData['institution_career_officer_first_name'] = $input['institution_career_officer_first_name'];
                break;
            default:
                // Handle the guest role by not adding any additional fields to the user data
                break;
        }
    
        $user = User::create($userData);
    
        $user->assignRole($role);
        return $user;
    }

    protected function determineRole(Request $request): string
    {
        
        if ($request->is('register/graduate')) {
            return 'graduate';
        } elseif ($request->is('register/company')) {
            return 'company';
        } elseif ($request->is('register/institution')) {
            return 'institution';
        }

        return 'user'; // Default role if none match
    }
}