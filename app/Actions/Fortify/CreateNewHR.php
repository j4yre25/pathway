<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewHR implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered admin.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Validation rules for admin registration
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'hr_first_name' => ['required', 'string', 'max:255'],
            'hr_last_name' => ['required', 'string', 'max:255'],
        ])->validate();

        // Create the admin user
        $user = User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'hr', 
            'is_approved' => 1, 
            'hr_first_name' => $input['hr_first_name'],
            'hr_last_name' => $input['hr_last_name'],
        ]);

        $user->assignRole('company');
        return $user;
    }
}