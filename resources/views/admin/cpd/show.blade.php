@extends('layouts.app')

@section('page-title', 'CPD Application Review - Admin - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">CPD Application Review</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
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
            
            <!-- Recommendation button - only show own recommendations -->
            @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                    📋 View Recommendation
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Application Status -->
    <div style="background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Application Status</h3>
                <div style="display: flex; gap: 10px; align-items: center;">
                    @if($application->status === 'draft')
                        <span style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Draft</span>
                    @elseif($application->status === 'submitted')
                        <span style="background: #007bff; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Submitted</span>
                    @elseif($application->status === 'under_review')
                        <span style="background: #ffc107; color: #212529; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Under Review</span>
                    @elseif($application->status === 'approved')
                        <span style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Approved</span>
                    @elseif($application->status === 'rejected')
                        <span style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Rejected</span>
                    @elseif($application->status === 'rework')
                        <span style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">Rework Required</span>
                    @endif
                    <span style="color: #6c757d; font-size: 14px;">Submitted on {{ $application->submitted_at ? $application->submitted_at->format('M d, Y H:i') : $application->created_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
            
            @if($application->status === 'submitted')
                <div style="display: flex; gap: 10px;">
                    <form action="{{ route('admin.cpd.approve', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application?')">Approve</button>
                    </form>
                    
                    <form action="{{ route('admin.cpd.reject', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                    </form>
                    
                    <form action="{{ route('admin.cpd.rework', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #fd7e14; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework?')">Mark for Rework</button>
                    </form>
                </div>
            @endif
        </div>
        
        @if($application->head_of_section_notes)
            <div style="margin-top: 15px; padding: 15px; background: #e8f5e8; border-radius: 6px; border-left: 4px solid #28a745;">
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Head Of Section Review Notes</h4>
                <p style="color: #495057; margin: 0;">{{ $application->head_of_section_notes }}</p>
                @if($application->head_of_section_reviewed_at)
                    <p style="color: #6c757d; margin: 5px 0 0 0; font-size: 12px;">
                        Reviewed by {{ $application->headOfSectionReviewer->name ?? 'Unknown' }} on {{ $application->head_of_section_reviewed_at->format('M d, Y H:i') }}
                    </p>
                @endif
            </div>
        @endif
        
        @if($application->admin_notes)
            <div style="margin-top: 15px; padding: 15px; background: #f8f9fa; border-radius: 6px; border-left: 4px solid #007bff;">
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Admin Notes</h4>
                <p style="color: #495057; margin: 0;">{{ $application->admin_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Applicant Details -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Applicant Details</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name</label>
                <p style="margin: 0; color: #495057;">{{ $application->user->full_name ?? $application->user->name }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Email</label>
                <p style="margin: 0; color: #495057;">{{ $application->user->email }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">IC Number</label>
                <p style="margin: 0; color: #495057;">{{ $application->ic_number }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Passport Number</label>
                <p style="margin: 0; color: #495057;">{{ $application->passport_number ?: 'Not provided' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post</label>
                <p style="margin: 0; color: #495057;">{{ $application->post }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area</label>
                <p style="margin: 0; color: #495057;">{{ $application->programme_area }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section</label>
                <p style="margin: 0; color: #495057;">{{ $application->faculty_school_centre_section }}</p>
            </div>
        </div>
    </div>

    <!-- Event Details -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Event Details</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Type</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_type }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Date</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_date->format('M d, Y') }}</p>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Title</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_title }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue</label>
                <p style="margin: 0; color: #495057;">{{ $application->venue }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Host Country</label>
                <p style="margin: 0; color: #495057;">{{ $application->host_country }}</p>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Organiser Name</label>
                <p style="margin: 0; color: #495057;">{{ $application->organiser_name }}</p>
            </div>
        </div>
    </div>

    <!-- Relevance and Expectations -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Relevance and Expectations</h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Relevance to Post and Duties</label>
            <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->relevance_to_post }}</p>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations Upon Completion</label>
            <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->expectations_upon_completion }}</p>
        </div>
    </div>

    <!-- Registration Fee Details -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Registration Fee Details</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Amount</label>
                <p style="margin: 0; color: #495057;">{{ $application->registration_fee ? 'B$ ' . number_format($application->registration_fee, 2) : 'Not specified' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Deadline</label>
                <p style="margin: 0; color: #495057;">{{ $application->registration_fee_deadline ? $application->registration_fee_deadline->format('F d, Y') : 'Not specified' }}</p>
            </div>
        </div>
        
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Payment Preference</label>
            <p style="margin: 0; color: #495057;">{{ $application->payment_preference_text }}</p>
        </div>
        
        @if($application->registration_fee_includes)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">What's Included in Registration Fee</label>
            <p style="margin: 0; color: #495057;">{{ $application->registration_fee_includes }}</p>
        </div>
        @endif
        
        @if($application->other_benefits)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Other Benefits</label>
            <p style="margin: 0; color: #495057;">{{ $application->other_benefits }}</p>
        </div>
        @endif
    </div>

    <!-- Paper Details -->
    @if($application->paper_title_1 || $application->paper_title_2)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Paper to be Presented</h3>
        
        @if($application->paper_title_1)
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title 1</label>
            <p style="margin: 0; color: #495057;">{{ $application->paper_title_1 }}</p>
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #6c757d;">First Author: {{ $application->is_first_author_1 ? 'Yes' : 'No' }}</p>
        </div>
        @endif
        
        @if($application->paper_title_2)
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title 2</label>
            <p style="margin: 0; color: #495057;">{{ $application->paper_title_2 }}</p>
            <p style="margin: 5px 0 0 0; font-size: 14px; color: #6c757d;">First Author: {{ $application->is_first_author_2 ? 'Yes' : 'No' }}</p>
        </div>
        @endif
        
        @if($application->publication_name)
        <div>
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Name</label>
            <p style="margin: 0; color: #495057;">{{ $application->publication_name }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Previous Event Details -->
    @if($application->previous_event_title || $application->previous_event_type)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Details of Previous Event Attended</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Type of Event</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_type }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date of Event</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_date ? $application->previous_event_date->format('F d, Y') : 'Not specified' }}</p>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_title }}</p>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue of Event</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_venue ?? 'Not specified' }}</p>
            </div>
        </div>
        
        <div style="margin-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Published</label>
                <p style="margin: 0; color: #495057;">{{ $application->paper_published ? 'Yes' : 'No' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Report Submitted</label>
                <p style="margin: 0; color: #495057;">{{ $application->report_submitted ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        
        @if($application->publication_details)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Details</label>
            <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->publication_details }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Logistics -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Logistics</h3>
        
        <!-- Flight Itinerary -->
        <div style="margin-bottom: 25px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Flight Itinerary</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Airfare Provided by Organiser</label>
                    <p style="margin: 0; color: #495057;">{{ $application->airfare_provided_by_organiser ? 'Yes' : 'No' }}</p>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Flight Route</label>
                    <p style="margin: 0; color: #495057;">{{ $application->flight_route ?? 'Not specified' }}</p>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Departure Date</label>
                    <p style="margin: 0; color: #495057;">{{ $application->departure_date ? $application->departure_date->format('F d, Y') : 'Not specified' }}</p>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Arrival Date</label>
                    <p style="margin: 0; color: #495057;">{{ $application->arrival_date ? $application->arrival_date->format('F d, Y') : 'Not specified' }}</p>
                </div>
            </div>
            
            @if($application->ticket_purchase_preference)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Ticket Purchase Preference</label>
                <p style="margin: 0; color: #495057;">{{ $application->ticket_purchase_preference_text }}</p>
            </div>
            @endif
        </div>

        <!-- Accommodation -->
        <div style="margin-bottom: 25px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Accommodation</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Provided by Organiser</label>
                    <p style="margin: 0; color: #495057;">{{ $application->accommodation_provided_by_organiser ? 'Yes' : 'No' }}</p>
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Similar to Venue</label>
                    <p style="margin: 0; color: #495057;">{{ $application->accommodation_similar_to_venue ? 'Yes' : 'No' }}</p>
                </div>
            </div>
            
            @if($application->hotel_name_address)
            <div style="margin-top: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Hotel Name and Address</label>
                <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->hotel_name_address }}</p>
            </div>
            @endif
        </div>

        <!-- Other Logistics -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Airport Transfer Provided</label>
                <p style="margin: 0; color: #495057;">{{ $application->airport_transfer_provided ? 'Yes' : 'No' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Daily Transportation Provided</label>
                <p style="margin: 0; color: #495057;">{{ $application->daily_transportation_provided ? 'Yes' : 'No' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Daily Allowance Provided</label>
                <p style="margin: 0; color: #495057;">{{ $application->daily_allowance_provided ? 'Yes' : 'No' }}</p>
            </div>
        </div>
        
        @if($application->allowance_amount)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Allowance Amount</label>
            <p style="margin: 0; color: #495057;">{{ $application->allowance_amount }}</p>
        </div>
        @endif
        
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Visa Required</label>
            <p style="margin: 0; color: #495057;">{{ $application->visa_required ? 'Yes' : 'No' }}</p>
        </div>
    </div>

    <!-- Consultancy Fund -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Contribution to Faculty / School Consultancy Fund</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contributed to Consultancy Fund</label>
                <p style="margin: 0; color: #495057;">{{ $application->contributed_to_consultancy_fund ? 'Yes' : 'No' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contribution Amount</label>
                <p style="margin: 0; color: #495057;">{{ $application->contribution_amount ? 'B$ ' . number_format($application->contribution_amount, 2) : 'Not specified' }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Entitlement Amount</label>
                <p style="margin: 0; color: #495057;">{{ $application->entitlement_amount ? 'B$ ' . number_format($application->entitlement_amount, 2) : 'Not specified' }}</p>
            </div>
        </div>
        
        @if($application->fund_purpose)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Fund Purpose</label>
            <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->fund_purpose }}</p>
        </div>
        @endif
    </div>

    <!-- Supporting Documents -->
    @if($application->event_brochure || $application->paper_abstract || $application->previous_certificate || $application->publication_paper || $application->travel_documents || $application->accommodation_details || $application->budget_breakdown || $application->other_documents)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Supporting Documents</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @if($application->event_brochure)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">📋 Event Brochure</label>
                <a href="{{ Storage::url($application->event_brochure) }}" target="_blank" style="color: #007bff; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->paper_abstract)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #28a745;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">📝 Paper Abstract</label>
                <a href="{{ Storage::url($application->paper_abstract) }}" target="_blank" style="color: #28a745; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->previous_certificate)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">🏆 Previous Certificate</label>
                <a href="{{ Storage::url($application->previous_certificate) }}" target="_blank" style="color: #ffc107; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->publication_paper)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #17a2b8;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">📚 Publication Paper</label>
                <a href="{{ Storage::url($application->publication_paper) }}" target="_blank" style="color: #17a2b8; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->travel_documents)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #fd7e14;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">✈️ Travel Documents</label>
                <a href="{{ Storage::url($application->travel_documents) }}" target="_blank" style="color: #fd7e14; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->accommodation_details)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #6f42c1;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">🏨 Accommodation Details</label>
                <a href="{{ Storage::url($application->accommodation_details) }}" target="_blank" style="color: #6f42c1; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->budget_breakdown)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #20c997;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">💰 Budget Breakdown</label>
                <a href="{{ Storage::url($application->budget_breakdown) }}" target="_blank" style="color: #20c997; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
            
            @if($application->other_documents)
            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #6c757d;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50;">📁 Other Documents</label>
                <a href="{{ Storage::url($application->other_documents) }}" target="_blank" style="color: #6c757d; text-decoration: none; display: flex; align-items: center; gap: 5px; font-weight: 500;">
                    📄 View Document
                </a>
            </div>
            @endif
        </div>
        
        @if(!$application->event_brochure && !$application->paper_abstract && !$application->previous_certificate && !$application->publication_paper && !$application->travel_documents && !$application->accommodation_details && !$application->budget_breakdown && !$application->other_documents)
            <div style="text-align: center; padding: 20px; color: #6c757d;">
                <p>No supporting documents have been uploaded for this application.</p>
            </div>
        @endif
    </div>
    @endif

    <!-- Application Metadata -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Application Information</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Created</label>
                <p style="margin: 0; color: #495057;">{{ $application->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Last Updated</label>
                <p style="margin: 0; color: #495057;">{{ $application->updated_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Review Form (for submitted applications) -->
    @if($application->status === 'submitted')
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Review Decision</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Admin Notes (Optional)</label>
                <textarea id="admin_notes" name="admin_notes" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;" placeholder="Add any notes or comments about your decision...">{{ old('admin_notes', $application->admin_notes) }}</textarea>
            </div>
            
            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <button onclick="submitDecision('approve')" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Approve Application
                </button>
                
                <button onclick="submitDecision('reject')" style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Reject Application
                </button>
                
                <button onclick="submitDecision('rework')" style="background: #fd7e14; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">
                    Mark for Rework
                </button>
            </div>
        </div>
    @endif
</div>

<script>
function submitDecision(action) {
    const adminNotes = document.getElementById('admin_notes').value;
    const confirmMessage = action === 'approve' ? 'Are you sure you want to approve this application?' :
                          action === 'reject' ? 'Are you sure you want to reject this application?' :
                          'Are you sure you want to mark this application for rework?';
    
    if (confirm(confirmMessage)) {
        const form = document.createElement('form');
        form.method = 'POST';
        
        // Set the correct route based on action
        if (action === 'approve') {
            form.action = '{{ route("admin.cpd.approve", $application) }}';
        } else if (action === 'reject') {
            form.action = '{{ route("admin.cpd.reject", $application) }}';
        } else if (action === 'rework') {
            form.action = '{{ route("admin.cpd.rework", $application) }}';
        }
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        if (adminNotes) {
            const notesInput = document.createElement('input');
            notesInput.type = 'hidden';
            notesInput.name = 'admin_notes';
            notesInput.value = adminNotes;
            form.appendChild(notesInput);
        }
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

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
    
    .content-card > div > div {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection 