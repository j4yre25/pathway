<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
        'peso_first_name',
        'peso_last_name',
        'graduate_first_name',
        'graduate_last_name',
        'graduate_school_graduated_from',
        'graduate_program_completed',
        'graduate_year_graduated',
        'graduate_skills',
        'company_name',
        'company_address',
        'company_sector',
        'company_category',
        'company_contact_number',
        'company_hr_last_name',
        'company_hr_first_name',
        'company_hr_middle_initial',
        'institution_type',
        'institution_address',
        'institution_contact_number',
        'institution_president_last_name',
        'institution_president_first_name',
        'institution_career_officer_first_name',
        'institution_career_officer_last_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'graduate_year_graduated' => 'date',
            'is_approved' => 'boolean'
        ];
    }

    public function jobs() {
        return $this->hasMany(Job::class);

    }
    public function sectors() {
        return $this->hasMany(Sector::class);

    }

    public function categories() {
        return $this->hasManyThrough(Category::class, Sector::class);

    }


    
    

}
