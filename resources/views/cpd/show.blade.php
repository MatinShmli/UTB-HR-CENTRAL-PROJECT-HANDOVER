@extends('layouts.app')

@section('page-title', 'CPD Application Details - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">CPD Application Details</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                Back to Applications
            </a>
            @if($application->status === 'approved')
                <a href="{{ route('cpd.download-pdf', $application) }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    📄 Download PDF
                </a>
            @endif
            @if($application->status === 'rejected')
                <a href="{{ route('cpd.download-pdf', $application) }}" style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    📄 Download PDF
                </a>
            @endif
            @if($application->status === 'draft')
                <a href="{{ route('cpd.edit', $application) }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    Edit Application
                </a>
            @endif
            @if($application->status === 'rework')
                <a href="{{ route('cpd.edit', $application) }}" style="background: #fd7e14; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    Edit Application (Rework)
                </a>
            @endif
            @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    View Recommendation
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Status Badge -->
    <div style="margin-bottom: 20px;">
        @if($application->status === 'draft')
            <span style="background: #6c757d; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Draft</span>
        @elseif($application->status === 'submitted')
            <span style="background: #007bff; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Submitted</span>
        @elseif($application->status === 'under_review')
            <span style="background: #ffc107; color: #212529; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Under Review</span>
        @elseif($application->status === 'approved')
            <span style="background: #28a745; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Approved</span>
        @elseif($application->status === 'rejected')
            <span style="background: #dc3545; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Rejected</span>
        @elseif($application->status === 'rework')
            <span style="background: #fd7e14; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">Rework Required</span>
        @endif
    </div>

    <!-- Admin Notes (if any) -->
    @if($application->admin_notes)
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 10px 0; color: #856404;">Admin Feedback</h4>
            <p style="margin: 0; line-height: 1.6;">{{ $application->admin_notes }}</p>
        </div>
    @endif

    <!-- SECTION 1: DETAILS OF APPLICANT -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 1: DETAILS OF APPLICANT</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->user->full_name ?? $application->user->name }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">IC No.</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->ic_number }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Passport No.</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->passport_number ?? 'Not provided' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->post }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->programme_area }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->faculty_school_centre_section }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: EVENT DETAILS -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 2: EVENT DETAILS</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Type</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->event_type }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->event_date->format('F d, Y') }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->venue }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Host Country</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->host_country }}
                </div>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->event_title }}
                </div>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name of Organiser</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->organiser_name }}
                </div>
            </div>
        </div>

        <!-- Registration Fee Details -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Registration Fee</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Amount</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->registration_fee ? 'B$ ' . number_format($application->registration_fee, 2) : 'Not specified' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Deadline</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->registration_fee_deadline ? $application->registration_fee_deadline->format('F d, Y') : 'Not specified' }}
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Payment Preference</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->payment_preference_text }}
                </div>
            </div>
            
            @if($application->registration_fee_includes)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">What's Included in Registration Fee</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->registration_fee_includes }}
                </div>
            </div>
            @endif
            
            @if($application->other_benefits)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Other Benefits</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->other_benefits }}
                </div>
            </div>
            @endif
        </div>

        <!-- Paper Details -->
        @if($application->paper_title_1 || $application->paper_title_2)
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Paper to be Presented</h4>
            
            @if($application->paper_title_1)
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title 1</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->paper_title_1 }}
                </div>
                <div style="margin-top: 5px; font-size: 14px; color: #6c757d;">
                    First Author: {{ $application->is_first_author_1 ? 'Yes' : 'No' }}
                </div>
            </div>
            @endif
            
            @if($application->paper_title_2)
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title 2</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->paper_title_2 }}
                </div>
                <div style="margin-top: 5px; font-size: 14px; color: #6c757d;">
                    First Author: {{ $application->is_first_author_2 ? 'Yes' : 'No' }}
                </div>
            </div>
            @endif
            
            @if($application->publication_name)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Name</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->publication_name }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Required Information -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Required Information</h4>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Relevance of Event to Applicant's Post and Duties</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                    {{ $application->relevance_to_post }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations Upon Completion</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                    {{ $application->expectations_upon_completion }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: PREVIOUS EVENT DETAILS -->
    @if($application->previous_event_title || $application->previous_event_type)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 3: DETAILS OF PREVIOUS EVENT ATTENDED</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Type of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->previous_event_type }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->previous_event_date ? $application->previous_event_date->format('F d, Y') : 'Not specified' }}
                </div>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->previous_event_title }}
                </div>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->previous_event_venue ?? 'Not specified' }}
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Published</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->paper_published ? 'Yes' : 'No' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Report Submitted</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->report_submitted ? 'Yes' : 'No' }}
                </div>
            </div>
        </div>
        
        @if($application->publication_details)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Details</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $application->publication_details }}
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- SECTION 4: LOGISTICS -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 4: LOGISTICS</h3>
        
        <!-- Flight Itinerary -->
        <div style="margin-bottom: 25px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">4.1 Flight Itinerary</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Airfare Provided by Organiser</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->airfare_provided_by_organiser ? 'Yes' : 'No' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Flight Route</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->flight_route ?? 'Not specified' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Departure Date</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->departure_date ? $application->departure_date->format('F d, Y') : 'Not specified' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Arrival Date</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->arrival_date ? $application->arrival_date->format('F d, Y') : 'Not specified' }}
                    </div>
                </div>
            </div>
            
            @if($application->ticket_purchase_preference)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Ticket Purchase Preference</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->ticket_purchase_preference_text }}
                </div>
            </div>
            @endif
        </div>

        <!-- Accommodation -->
        <div style="margin-bottom: 25px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">4.2 Accommodation</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Provided by Organiser</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->accommodation_provided_by_organiser ? 'Yes' : 'No' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Similar to Venue</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->accommodation_similar_to_venue ? 'Yes' : 'No' }}
                    </div>
                </div>
            </div>
            
            @if($application->hotel_name_address)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Hotel Name and Address</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                    {{ $application->hotel_name_address }}
                </div>
            </div>
            @endif
        </div>

        <!-- Other Logistics -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Airport Transfer Provided</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->airport_transfer_provided ? 'Yes' : 'No' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Daily Transportation Provided</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->daily_transportation_provided ? 'Yes' : 'No' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Daily Allowance Provided</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->daily_allowance_provided ? 'Yes' : 'No' }}
                </div>
            </div>
        </div>
        
        @if($application->allowance_amount)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Allowance Amount</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                {{ $application->allowance_amount }}
            </div>
        </div>
        @endif
        
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Visa Required</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                {{ $application->visa_required ? 'Yes' : 'No' }}
            </div>
        </div>
    </div>

    <!-- SECTION 5: CONSULTANCY FUND -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 5: CONTRIBUTION TO FACULTY / SCHOOL CONSULTANCY FUND</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contributed to Consultancy Fund</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->contributed_to_consultancy_fund ? 'Yes' : 'No' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contribution Amount</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->contribution_amount ? 'B$ ' . number_format($application->contribution_amount, 2) : 'Not specified' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Entitlement Amount</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->entitlement_amount ? 'B$ ' . number_format($application->entitlement_amount, 2) : 'Not specified' }}
                </div>
            </div>
        </div>
        
        @if($application->fund_purpose)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Fund Purpose</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $application->fund_purpose }}
            </div>
        </div>
        @endif
    </div>

    <!-- Application Metadata -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Application Information</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Created</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->created_at->format('F d, Y \a\t g:i A') }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Last Updated</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->updated_at->format('F d, Y \a\t g:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendation Section -->
    @if($application->recommendation)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #28a745; padding-bottom: 10px;">📋 Recommendation Details</h3>
        
        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <h4 style="color: #28a745; margin: 0;">Recommendation Status</h4>
                <span style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">COMPLETED</span>
            </div>
            <p style="margin: 0; color: #6c757d; font-size: 14px;">
                This application has been reviewed and a recommendation has been provided by {{ $application->recommendation->recommendedBy->name ?? 'the recommender' }}.
            </p>
        </div>

        <!-- Recommendation Details -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommended By</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->recommendation->recommendedBy->name ?? 'N/A' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Type</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->recommendation->event_type }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Date</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->recommendation->event_date->format('F d, Y') }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $application->recommendation->programme_area }}
                </div>
            </div>
        </div>

        <!-- Suitability Statement -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Suitability Statement</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->suitability_statement }}
            </div>
        </div>

        <!-- Reasons for Recommendation -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Reasons for Recommendation</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->reasons_for_recommendation }}
            </div>
        </div>

        <!-- Expectations Upon Completion -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations Upon Completion</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->expectations_upon_completion }}
            </div>
        </div>

        <!-- Recommended Benefits/Allowances -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommended Benefits/Allowances</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->recommended_benefits_allowances }}
            </div>
        </div>

        <!-- Staff Recommended to Discharge Duties -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Staff Recommended to Discharge Duties</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->staff_recommended_to_discharge_duties }}
            </div>
        </div>

        <!-- Dean Comments (if available) -->
        @if($application->recommendation->dean_comments_recommendation)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean/Director Comments</label>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                {{ $application->recommendation->dean_comments_recommendation }}
            </div>
        </div>
        @endif

        <!-- Consultancy Fund Information -->
        @if($application->recommendation->has_contributed_to_consultancy_fund || $application->recommendation->recommend_use_consultancy_fund)
        <div style="margin-bottom: 20px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Consultancy Fund Information</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Has Contributed to Consultancy Fund</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->recommendation->has_contributed_to_consultancy_fund ? 'Yes' : 'No' }}
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommend Use of Consultancy Fund</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        {{ $application->recommendation->recommend_use_consultancy_fund ? 'Yes' : 'No' }}
                    </div>
                </div>
            </div>

            @if($application->recommendation->consultancy_fund_details)
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Consultancy Fund Details</label>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                    {{ $application->recommendation->consultancy_fund_details }}
                </div>
            </div>
            @endif

            @if($application->recommendation->consultancy_fund_rejection_reason)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Consultancy Fund Rejection Reason</label>
                <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap; line-height: 1.6;">
                    {{ $application->recommendation->consultancy_fund_rejection_reason }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Signatures -->
        <div style="border-top: 1px solid #dee2e6; padding-top: 20px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Signatures</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Leader</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        <div style="font-weight: 600;">{{ $application->recommendation->programme_leader_name }}</div>
                        <div style="font-size: 14px; color: #6c757d;">{{ $application->recommendation->programme_leader_post }}</div>
                        @if($application->recommendation->programme_leader_signed_at)
                        <div style="font-size: 12px; color: #6c757d; margin-top: 5px;">
                            Signed: {{ $application->recommendation->programme_leader_signed_at->format('F d, Y \a\t g:i A') }}
                        </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean/Director</label>
                    <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                        <div style="font-weight: 600;">{{ $application->recommendation->dean_name }}</div>
                        <div style="font-size: 14px; color: #6c757d;">{{ $application->recommendation->dean_post }}</div>
                        @if($application->recommendation->dean_signed_at)
                        <div style="font-size: 12px; color: #6c757d; margin-top: 5px;">
                            Signed: {{ $application->recommendation->dean_signed_at->format('F d, Y \a\t g:i A') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- No Recommendation Available -->
    <div style="background: #fff3cd; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; border: 1px solid #ffeaa7;">
        <h3 style="color: #856404; margin-bottom: 15px; border-bottom: 2px solid #ffc107; padding-bottom: 10px;">📋 Recommendation Status</h3>
        <div style="display: flex; align-items: center; gap: 10px;">
            <div style="font-size: 24px;">⏳</div>
            <div>
                <h4 style="color: #856404; margin: 0 0 5px 0;">No Recommendation Available Yet</h4>
                <p style="margin: 0; color: #856404;">This application is still under review. A recommendation will be provided once the review process is complete.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div style="display: flex; gap: 10px; justify-content: center; margin-top: 30px;">
        @if($application->status === 'draft')
            <form action="{{ route('cpd.submit', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to submit this application? You won\'t be able to edit it after submission.')">
                    Submit Application
                </button>
            </form>
        @endif
        
        <!-- Delete button for all applications -->
        <form action="{{ route('cpd.destroy', $application) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="return confirmDelete('{{ $application->event_title }}')">
                Delete Application
            </button>
        </form>

        @if(($application->status === 'rejected' || $application->status === 'under_review') && Auth::user()->role !== 'Administrator' && $application->user_id === Auth::id())
            <form action="{{ route('cpd.rework', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #fd7e14; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework? You will be able to edit and resubmit it.')">
                    Mark for Rework
                </button>
            </form>
        @endif

        @if($application->status === 'rework' && Auth::user()->role !== 'Administrator' && $application->user_id === Auth::id())
            <form action="{{ route('cpd.resubmit', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to resubmit this application? It will be sent for review again.')">
                    Resubmit Application
                </button>
            </form>
        @endif
    </div>
</div>

<style>
.content-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin: 20px;
}

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    .content-card > div {
        padding: 15px !important;
    }
    
    .content-card .grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function confirmDelete(eventTitle) {
    const message = `Are you sure you want to delete the CPD application "${eventTitle}"?\n\nThis action cannot be undone and all associated data will be permanently removed.`;
    return confirm(message);
}
</script>
@endsection 