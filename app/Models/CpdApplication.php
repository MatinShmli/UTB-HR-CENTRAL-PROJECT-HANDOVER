<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpdApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ic_number',
        'passport_number',
        'post',
        'programme_area',
        'faculty_school_centre_section',
        'event_type',
        'event_title',
        'event_date',
        'venue',
        'host_country',
        'organiser_name',
        'registration_fee',
        'registration_fee_deadline',
        'payment_preference',
        'registration_fee_includes',
        'other_benefits',
        'paper_title_1',
        'is_first_author_1',
        'paper_title_2',
        'is_first_author_2',
        'relevance_to_post',
        'expectations_upon_completion',
        'publication_name',
        'previous_event_type',
        'previous_event_title',
        'previous_event_date',
        'previous_event_venue',
        'paper_published',
        'publication_details',
        'report_submitted',
        'airfare_provided_by_organiser',
        'flight_route',
        'departure_date',
        'arrival_date',
        'ticket_purchase_preference',
        'accommodation_provided_by_organiser',
        'hotel_name_address',
        'accommodation_similar_to_venue',
        'airport_transfer_provided',
        'daily_transportation_provided',
        'daily_allowance_provided',
        'allowance_amount',
        'visa_required',
        'contributed_to_consultancy_fund',
        'contribution_amount',
        'entitlement_amount',
        'fund_purpose',
        'status',
        'admin_notes',
        'head_of_section_notes',
        'head_of_section_reviewed_at',
        'head_of_section_reviewed_by',
        'submitted_at',
        // File upload fields
        'event_brochure',
        'paper_abstract',
        'previous_certificate',
        'publication_paper',
        'travel_documents',
        'accommodation_details',
        'budget_breakdown',
        'other_documents',
    ];

    protected $casts = [
        'event_date' => 'date',
        'registration_fee_deadline' => 'date',
        'previous_event_date' => 'date',
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'is_first_author_1' => 'boolean',
        'is_first_author_2' => 'boolean',
        'paper_published' => 'boolean',
        'report_submitted' => 'boolean',
        'airfare_provided_by_organiser' => 'boolean',
        'accommodation_provided_by_organiser' => 'boolean',
        'accommodation_similar_to_venue' => 'boolean',
        'airport_transfer_provided' => 'boolean',
        'daily_transportation_provided' => 'boolean',
        'daily_allowance_provided' => 'boolean',
        'visa_required' => 'boolean',
        'contributed_to_consultancy_fund' => 'boolean',
        'submitted_at' => 'datetime',
        'head_of_section_reviewed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Head Of Section reviewer
     */
    public function headOfSectionReviewer()
    {
        return $this->belongsTo(User::class, 'head_of_section_reviewed_by');
    }

    /**
     * Get the CPD recommendation for this application
     */
    public function recommendation()
    {
        return $this->hasOne(CpdRecommendation::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'submitted' => '<span class="badge bg-primary">Submitted</span>',
            'under_review' => '<span class="badge bg-warning">Under Review</span>',
            'approved' => '<span class="badge bg-success">Approved</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            'rework' => '<span class="badge bg-warning text-dark">Rework Required</span>',
        ];

        return $badges[$this->status] ?? $badges['draft'];
    }

    public function getPaymentPreferenceTextAttribute()
    {
        $preferences = [
            'bursar_office' => 'The Bursar Office to pay the registration fee directly to the organiser',
            'pay_first_submit_receipt' => 'Pay registration fee first and to give receipt to CPD secretariat at least three weeks before start of event',
            'pay_first_reimburse' => 'Pay registration fee first and reimburse after attending the event',
        ];

        return $preferences[$this->payment_preference] ?? '';
    }

    public function getTicketPurchasePreferenceTextAttribute()
    {
        $preferences = [
            'cpd_secretariat' => 'The CPD Secretariat to purchase your ticket (Government selected travel agents using 3 quotations)',
            'purchase_self_submit' => 'Purchase ticket yourself and submit to CPD office at least three weeks before start of event',
            'purchase_self_reimburse' => 'Purchase ticket yourself and reimburse after attending the event',
        ];

        return $preferences[$this->ticket_purchase_preference] ?? '';
    }
} 