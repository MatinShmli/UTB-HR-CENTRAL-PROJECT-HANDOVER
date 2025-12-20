<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpdRecommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpd_application_id',
        'recommended_by_user_id',
        'applicant_name',
        'applicant_post',
        'programme_area',
        'faculty_school_centre_section',
        'event_type',
        'event_title',
        'event_date',
        'suitability_statement',
        'reasons_for_recommendation',
        'expectations_upon_completion',
        'recommended_benefits_allowances',
        'staff_recommended_to_discharge_duties',
        'dean_comments_recommendation',
        'has_contributed_to_consultancy_fund',
        'consultancy_fund_details',
        'recommend_use_consultancy_fund',
        'consultancy_fund_rejection_reason',
        'programme_leader_name',
        'programme_leader_post',
        'programme_leader_signed_at',
        'dean_name',
        'dean_post',
        'dean_signed_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'programme_leader_signed_at' => 'datetime',
        'dean_signed_at' => 'datetime',
        'has_contributed_to_consultancy_fund' => 'boolean',
        'recommend_use_consultancy_fund' => 'boolean',
    ];

    /**
     * Get the CPD application that this recommendation belongs to
     */
    public function cpdApplication()
    {
        return $this->belongsTo(CpdApplication::class);
    }

    /**
     * Get the user who created this recommendation
     */
    public function recommendedBy()
    {
        return $this->belongsTo(User::class, 'recommended_by_user_id');
    }
}
