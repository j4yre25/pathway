<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'max:20', // Maximum of 30 characters (applied separately)
            Password::min(8) // Minimum of 8 characters
                    ->mixedCase() // Requires uppercase & lowercase letters
                    ->letters() // Requires at least one letter
                    ->numbers() // Requires at least one number
                    ->symbols() // Requires at least one special character (@$!%*?&)
                    ->uncompromised(), // Checks against breached passwords
            'confirmed', // Ensures password confirmation matches
        ];
    }
}