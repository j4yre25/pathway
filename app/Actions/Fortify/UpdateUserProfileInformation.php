<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $rules = [
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];

        switch ($user->role) {
            case 'graduate':
                $rules['graduate_first_name'] = ['required', 'string', 'max:255'];
                $rules['graduate_last_name'] = ['required', 'string', 'max:255'];
                break;
            case 'company':
                $rules['company_hr_first_name'] = ['required', 'string', 'max:255'];
                $rules['company_hr_last_name'] = ['required', 'string', 'max:255'];
                break;
            case 'institution':
                $rules['institution_president_first_name'] = ['required', 'string', 'max:255'];
                $rules['institution_president_last_name'] = ['required', 'string', 'max:255'];
                break;
            case 'peso':
                $rules['peso_first_name'] = ['required', 'string', 'max:255'];
                $rules['peso_last_name'] = ['required', 'string', 'max:255'];
                break;
    
        }

        Validator::make($input, $rules)->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $this->updateUserFields($user, $input);
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $this->updateUserFields($user, $input);

        $user->forceFill([
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }

    /**
     * Update the user fields based on their role.
     *
     * @param  array<string, string>  $input
     */
    protected function updateUserFields(User $user, array $input): void
    {
        $fields = [
            'email' => $input['email'],
        ];

        switch ($user->role) {
            case 'graduate':
                $fields['graduate_first_name'] = $input['graduate_first_name'];
                $fields['graduate_last_name'] = $input['graduate_last_name'];
                break;
            case 'company':
                $fields['company_hr_first_name'] = $input['company_hr_first_name'];
                $fields['company_hr_last_name'] = $input['company_hr_last_name'];
                break;
            case 'institution':
                $fields['institution_president_first_name'] = $input['institution_president_first_name'];
                $fields['institution_president_last_name'] = $input['institution_president_last_name'];
                break;
            case 'peso':
                $fields['peso_first_name'] = $input['peso_first_name'];
                $fields['peso_last_name'] = $input['peso_last_name'];
                break;
          
        }

        $user->forceFill($fields)->save();
    }
}
