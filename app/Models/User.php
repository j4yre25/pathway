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
        'dob',
        'gender',
        // PESO
        'peso_first_name',
        'peso_last_name',
        'graduate_first_sname',
        'graduate_last_name',
        'graduate_middle_initial',
        'graduate_school_graduated_from',
        'graduate_program_completed',
        'graduate_year_graduated',
        'graduate_skills',
        // Company
        'company_name',
        'company_street_address',
        'company_brgy',
        'company_city',
        'company_province',
        'company_zip_code',
        'company_company_email',
        'company_contact_number',
        'company_telephone_number',
        'company_hr_full_name',
        'company_hr_gender',
        'company_hr_dob',
        'company_hr_contact_number',
        // Institution
        'institution_type',
        'institution_name',
        'institution_address',
        'institution_contact_number',
        'institution_president_last_name',
        'institution_president_first_name',
        'institution_career_officer_first_name',
        'institution_contact_number',
        // 'institution_career_officer_last_name',
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

    public function portfolioItems()
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Get the user's about me information.
     */
    public function aboutMe()
    {
        return $this->hasOne(AboutMe::class);
    }

    /**
     * Get the user's profile section.
     */
    public function profileSection()
    {
        return $this->hasOne(ProfileSection::class);
    }

    /**
     * Get the user's resumes.
     */
    public function resumes()
    {
        return $this->hasMany(Resume::class);
    }

    /**
     * Get the user's next role preferences.
     */
    public function nextRole()
    {
        return $this->hasOne(NextRole::class);
    }

    /**
     * Get the user's settings.
     */
    public function settings()
    {
        return $this->hasOne(Settings::class);
    }

    /**
     * Get the user's dashboard.
     */
    public function dashboard()
    {
        return $this->hasOne(Dashboard::class);
    }

    /**
     * Get the user's education entries.
     */
    public function education()
    {
        return $this->hasMany(Education::class);
    }

    /**
     * Get the user's job applications.
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * Get the user's institution.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
  
    }
    
    

}
