@extends('layouts.app')

@section('page-title', 'CPD Recommendation Details - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">CPD Recommendation Details</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                Back to Recommendations
            </a>
            @if(Auth::user()->role === 'Head Of Section')
                <a href="{{ route('cpd.recommendations.edit', $recommendation) }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    Edit Recommendation
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeaa7;">
            {{ session('warning') }}
        </div>
    @endif

    @if(empty($recommendation->applicant_name) || empty($recommendation->event_title) || strlen($recommendation->event_title) < 5)
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <strong>⚠️ Data Quality Issue:</strong> This recommendation appears to have incomplete or missing data. This may be due to the original CPD application having incomplete information. Please contact the system administrator if you need assistance.
        </div>
    @endif

    <!-- SECTION 1: DETAILS OF APPLICANT -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 1: DETAILS OF APPLICANT</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->applicant_name }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->applicant_post }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->programme_area }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->faculty_school_centre_section }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: DETAILS OF EVENT -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 2: DETAILS OF EVENT</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Type</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->event_type }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->event_date->format('F d, Y') }}
                </div>
            </div>
            
            <div style="grid-column: span 2;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->event_title }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 3: SUITABILITY IN ATTENDING EVENT -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 3: SUITABILITY IN ATTENDING EVENT</h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Suitability of the applicant in attending the event</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->suitability_statement }}
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Reasons for recommendation</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->reasons_for_recommendation }}
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations upon completion</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->expectations_upon_completion }}
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommended benefits / allowances</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->recommended_benefits_allowances }}
            </div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Staff recommended to discharge duties</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->staff_recommended_to_discharge_duties }}
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Leader Name</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->programme_leader_name }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Leader Post</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->programme_leader_post }}
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: COMMENTS AND RECOMMENDATION OF DEAN / DIRECTOR -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 4: COMMENTS AND RECOMMENDATION OF DEAN / DIRECTOR</h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Has the staff contributed to the Faculty / School Consultancy Fund?</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                {{ $recommendation->has_contributed_to_consultancy_fund ? 'Yes' : 'No' }}
            </div>
        </div>
        
        @if($recommendation->consultancy_fund_details)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Consultancy Fund Details</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->consultancy_fund_details }}
            </div>
        </div>
        @endif
        
        @if($recommendation->dean_comments_recommendation)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean Comments and Recommendation</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->dean_comments_recommendation }}
            </div>
        </div>
        @endif
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommend use of Faculty / School Consultancy Fund?</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                {{ $recommendation->recommend_use_consultancy_fund ? 'Yes' : 'No' }}
            </div>
        </div>
        
        @if($recommendation->consultancy_fund_rejection_reason)
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Rejection Reason</label>
            <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6; white-space: pre-wrap;">
                {{ $recommendation->consultancy_fund_rejection_reason }}
            </div>
        </div>
        @endif
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean Name</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->dean_name }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean Post</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->dean_post }}
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendation Metadata -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Recommendation Information</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Created</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->created_at->format('F d, Y \a\t g:i A') }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Last Updated</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->updated_at->format('F d, Y \a\t g:i A') }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommended By</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->recommendedBy->name ?? 'Unknown' }}
                </div>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">For Application</label>
                <div style="padding: 10px; background: #f8f9fa; border-radius: 6px; border: 1px solid #dee2e6;">
                    {{ $recommendation->cpdApplication->event_title ?? 'Unknown' }}
                </div>
            </div>
        </div>
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
@endsection 