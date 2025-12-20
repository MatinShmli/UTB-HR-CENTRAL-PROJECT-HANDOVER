@extends('layouts.app')

@section('content')
@php
    $showBackButton = true;
@endphp
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: #2c3e50; margin: 0; font-size: 28px; font-weight: 700;">View CPD Application</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0; font-size: 16px;">View and manage this CPD application</p>
        </div>
        <div style="display: flex; gap: 15px;">
            <a href="{{ route('headofsection.cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                ← Back to Applications
            </a>
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
                    @else
                        <span style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 4px; font-size: 14px; font-weight: 600;">{{ ucfirst($application->status) }}</span>
                    @endif
                    <span style="color: #6c757d; font-size: 14px;">
                        @if($application->status === 'submitted')
                            Submitted on {{ $application->submitted_at ? $application->submitted_at->format('M d, Y H:i') : $application->created_at->format('M d, Y H:i') }}
                        @else
                            Created on {{ $application->created_at->format('M d, Y H:i') }}
                        @endif
                    </span>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px;">
                @if($application->status === 'submitted')
                    <form action="{{ route('headofsection.cpd.approve', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application and forward it to administration?')">Approve & Forward</button>
                    </form>
                    
                    <form action="{{ route('headofsection.cpd.reject', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                    </form>
                    
                    <form action="{{ route('headofsection.cpd.rework', $application) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: #fd7e14; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework?')">Mark for Rework</button>
                    </form>
                @endif
                
                @if($application->status === 'approved' || $application->status === 'rejected' || $application->status === 'submitted')
                    <a href="{{ route('headofsection.cpd.download-pdf', $application) }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">📄 Download PDF</a>
                @endif
                
                <!-- Recommendation button - only show own recommendations -->
                @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                    <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">
                        📋 View Recommendation
                    </a>
                @elseif(!$application->recommendation)
                    <a href="{{ route('cpd.recommendations.create', $application) }}" style="background: #fd7e14; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">
                        📋 Create Recommendation
                    </a>
                @endif
            </div>
        </div>
        
        @if($application->status === 'submitted')
        <!-- Review Notes Section -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Review Notes</h4>
            <form id="reviewForm" style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Add Review Notes (Optional)</label>
                    <textarea name="head_of_section_notes" rows="3" placeholder="Add any notes or feedback for this application..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;">{{ old('head_of_section_notes') }}</textarea>
                </div>
                <div>
                    <button type="button" onclick="saveNotes()" style="background: #17a2b8; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer;">Save Notes</button>
                </div>
            </form>
        </div>
        @endif
        
        @if($application->head_of_section_notes)
        <!-- Existing Review Notes -->
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            <h4 style="color: #2c3e50; margin-bottom: 15px;">Previous Review Notes</h4>
            <div style="background: #f8f9fa; padding: 15px; border-radius: 6px; border-left: 4px solid #17a2b8;">
                <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->head_of_section_notes }}</p>
                @if($application->head_of_section_reviewed_at)
                    <p style="margin: 10px 0 0 0; font-size: 12px; color: #6c757d;">
                        Reviewed on {{ $application->head_of_section_reviewed_at->format('M d, Y H:i') }}
                        @if($application->headOfSectionReviewer)
                            by {{ $application->headOfSectionReviewer->name }}
                        @endif
                    </p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Applicant Information -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Applicant Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Full Name</label>
                <p style="margin: 0; color: #495057;">{{ $application->user->name }}</p>
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

    <!-- Event Information -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Event Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Type</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_type }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Title</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_title }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Date</label>
                <p style="margin: 0; color: #495057;">{{ $application->event_date->format('F d, Y') }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue</label>
                <p style="margin: 0; color: #495057;">{{ $application->venue }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Host Country</label>
                <p style="margin: 0; color: #495057;">{{ $application->host_country }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Organiser Name</label>
                <p style="margin: 0; color: #495057;">{{ $application->organiser_name }}</p>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Relevance to Post</label>
            <p style="margin: 0; color: #495057; line-height: 1.6;">{{ $application->relevance_to_post }}</p>
        </div>
        
        <div style="margin-top: 20px;">
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

    <!-- Paper/Publication Information -->
    @if($application->paper_title_1 || $application->paper_title_2)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Paper/Publication Information</h3>
        
        @if($application->paper_title_1)
        <div style="margin-bottom: 20px;">
            <h4 style="color: #2c3e50; margin-bottom: 10px;">Paper 1</h4>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title</label>
                    <p style="margin: 0; color: #495057;">{{ $application->paper_title_1 }}</p>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">First Author</label>
                    <p style="margin: 0; color: #495057;">{{ $application->is_first_author_1 ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
        @endif
        
        @if($application->paper_title_2)
        <div style="margin-bottom: 20px;">
            <h4 style="color: #2c3e50; margin-bottom: 10px;">Paper 2</h4>
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Title</label>
                    <p style="margin: 0; color: #495057;">{{ $application->paper_title_2 }}</p>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">First Author</label>
                    <p style="margin: 0; color: #495057;">{{ $application->is_first_author_2 ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
        @endif
        
        @if($application->publication_name)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Name</label>
            <p style="margin: 0; color: #495057;">{{ $application->publication_name }}</p>
        </div>
        @endif
        
        @if($application->paper_published)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Published</label>
            <p style="margin: 0; color: #495057;">{{ $application->paper_published ? 'Yes' : 'No' }}</p>
        </div>
        
        @if($application->publication_details)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Publication Details</label>
            <p style="margin: 0; color: #495057;">{{ $application->publication_details }}</p>
        </div>
        @endif
        @endif
        
        @if($application->report_submitted)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Report Submitted</label>
            <p style="margin: 0; color: #495057;">{{ $application->report_submitted ? 'Yes' : 'No' }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Travel Information -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Travel Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Airfare Provided by Organiser</label>
                <p style="margin: 0; color: #495057;">{{ $application->airfare_provided_by_organiser ? 'Yes' : 'No' }}</p>
            </div>
            
            @if($application->flight_route)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Flight Route</label>
                <p style="margin: 0; color: #495057;">{{ $application->flight_route }}</p>
            </div>
            @endif
            
            @if($application->departure_date)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Departure Date</label>
                <p style="margin: 0; color: #495057;">{{ $application->departure_date->format('F d, Y') }}</p>
            </div>
            @endif
            
            @if($application->arrival_date)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Arrival Date</label>
                <p style="margin: 0; color: #495057;">{{ $application->arrival_date->format('F d, Y') }}</p>
            </div>
            @endif
            
            @if($application->ticket_purchase_preference)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Ticket Purchase Preference</label>
                <p style="margin: 0; color: #495057;">{{ $application->ticket_purchase_preference_text }}</p>
            </div>
            @endif
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Provided by Organiser</label>
                <p style="margin: 0; color: #495057;">{{ $application->accommodation_provided_by_organiser ? 'Yes' : 'No' }}</p>
            </div>
            
            @if($application->hotel_name_address)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Hotel Name & Address</label>
                <p style="margin: 0; color: #495057;">{{ $application->hotel_name_address }}</p>
            </div>
            @endif
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Similar to Venue</label>
                <p style="margin: 0; color: #495057;">{{ $application->accommodation_similar_to_venue ? 'Yes' : 'No' }}</p>
            </div>
            
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
            
            @if($application->allowance_amount)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Allowance Amount</label>
                <p style="margin: 0; color: #495057;">{{ $application->allowance_amount }}</p>
            </div>
            @endif
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Visa Required</label>
                <p style="margin: 0; color: #495057;">{{ $application->visa_required ? 'Yes' : 'No' }}</p>
            </div>
        </div>
    </div>

    <!-- Consultancy Fund Information -->
    @if($application->contributed_to_consultancy_fund)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Consultancy Fund Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            @if($application->contribution_amount)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contribution Amount</label>
                <p style="margin: 0; color: #495057;">B$ {{ number_format($application->contribution_amount, 2) }}</p>
            </div>
            @endif
            
            @if($application->entitlement_amount)
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Entitlement Amount</label>
                <p style="margin: 0; color: #495057;">B$ {{ number_format($application->entitlement_amount, 2) }}</p>
            </div>
            @endif
        </div>
        
        @if($application->fund_purpose)
        <div style="margin-top: 15px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Fund Purpose</label>
            <p style="margin: 0; color: #495057;">{{ $application->fund_purpose }}</p>
        </div>
        @endif
    </div>
    @endif

    <!-- Previous Event Information -->
    @if($application->previous_event_title)
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Previous Event Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Previous Event Type</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_type }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Previous Event Title</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_title }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Previous Event Date</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_date->format('F d, Y') }}</p>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Previous Event Venue</label>
                <p style="margin: 0; color: #495057;">{{ $application->previous_event_venue }}</p>
            </div>
        </div>
    </div>
    @endif

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

    <!-- Action Buttons -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Review Actions</h3>
        
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <form action="{{ route('headofsection.cpd.approve', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application and forward it to administration?')">
                    ✓ Approve & Forward to Admin
                </button>
            </form>
            
            <form action="{{ route('headofsection.cpd.reject', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #dc3545; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?')">
                    ✗ Reject Application
                </button>
            </form>
            
            <form action="{{ route('headofsection.cpd.rework', $application) }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="background: #fd7e14; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework?')">
                    🔄 Mark for Rework
                </button>
            </form>
            
            <a href="{{ route('headofsection.cpd.download-pdf', $application) }}" style="background: #6f42c1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600;">
                📄 Download PDF
            </a>
        </div>
    </div>
</div>

<script>
function saveNotes() {
    const notes = document.querySelector('textarea[name="head_of_section_notes"]').value;
    const form = document.getElementById('reviewForm');
    
    // Create a temporary form to save notes
    const tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = '{{ route("headofsection.cpd.approve", $application) }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const notesField = document.createElement('input');
    notesField.type = 'hidden';
    notesField.name = 'head_of_section_notes';
    notesField.value = notes;
    
    const saveOnlyField = document.createElement('input');
    saveOnlyField.type = 'hidden';
    saveOnlyField.name = 'save_only';
    saveOnlyField.value = '1';
    
    tempForm.appendChild(csrfToken);
    tempForm.appendChild(notesField);
    tempForm.appendChild(saveOnlyField);
    
    document.body.appendChild(tempForm);
    tempForm.submit();
}
</script>
@endsection
