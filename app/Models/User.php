<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'is_approved',
        'ic_number',
        'passport_number',
        'ic_color',
        'full_name',
        'age',
        'sex',
        'birthdate',
        'place_of_birth',
        'nationality',
        'citizenship',
        'race',
        'religion',
        'marital_status',
        'address',
        'address_of_country_of_origin',
        'address_in_brunei',
        'phone_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'post',
        'programme_area',
        'faculty_school_centre_section',
        'position',
        'faculty',
        'department',
        'employment_type',
        'salary_scale',
        'current_salary',
        'duration_of_appointment',
        'spouse_full_name',
        'spouse_ic_number',
        'spouse_citizenship',
        'spouse_workplace',
        'spouse_position',
        'spouse_department',
        'spouse_address_country_of_origin',
        'spouse_address_brunei',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'birthdate' => 'date',
            'current_salary' => 'decimal:2',
        ];
    }

    /**
     * Check if the user's profile is complete
     *
     * @return bool
     */
    public function isProfileComplete(): bool
    {
        $requiredFields = [
            'ic_number',
            'ic_color',
            'full_name',
            'sex',
            'birthdate',
            'marital_status',
            'email',
            'phone_number',
            'citizenship',
            'address_in_brunei',
            'position',
            'faculty',
            'department',
            'employment_type',
        ];

        foreach ($requiredFields as $field) {
            if ($field === 'birthdate') {
                if (empty($this->$field) || $this->$field === '0000-00-00') {
                    return false;
                }
            } else {
                if (empty($this->$field)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get profile completion percentage
     *
     * @return array
     */
    public function getProfileCompletion(): array
    {
        $requiredFields = [
            'ic_number',
            'ic_color',
            'full_name',
            'sex',
            'birthdate',
            'marital_status',
            'email',
            'phone_number',
            'citizenship',
            'address_in_brunei',
            'position',
            'faculty',
            'department',
            'employment_type',
        ];

        $completedFields = 0;
        $totalFields = count($requiredFields);

        foreach ($requiredFields as $field) {
            if ($field === 'birthdate') {
                if (!empty($this->$field) && $this->$field !== '0000-00-00') {
                    $completedFields++;
                }
            } else {
                if (!empty($this->$field)) {
                    $completedFields++;
                }
            }
        }

        return [
            'percentage' => round(($completedFields / $totalFields) * 100),
            'completed' => $completedFields,
            'total' => $totalFields,
            'isComplete' => $completedFields === $totalFields
        ];
    }

    /**
     * Get the CPD applications for this user
     */
    public function cpdApplications()
    {
        return $this->hasMany(CpdApplication::class);
    }

    /**
     * Get the memo requests for this user
     */
    public function memoRequests()
    {
        return $this->hasMany(MemoRequest::class);
    }

    /**
     * Get the memo requests reviewed by this user
     */
    public function reviewedMemoRequests()
    {
        return $this->hasMany(MemoRequest::class, 'reviewed_by');
    }

    /**
     * Get the job applications for this user
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
