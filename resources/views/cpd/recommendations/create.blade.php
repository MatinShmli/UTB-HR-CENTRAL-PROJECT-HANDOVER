@extends('layouts.app')

@section('page-title', 'Create CPD Recommendation - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Create CPD Recommendation</h2>
        <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Back to Recommendations
        </a>
    </div>

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('cpd.recommendations.store', $application) }}" method="POST" style="max-width: 1200px;">
        @csrf
        
        <!-- SECTION 1: DETAILS OF APPLICANT -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 1: DETAILS OF APPLICANT</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="applicant_name" value="{{ old('applicant_name', $application->user->full_name ?? $application->user->name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('applicant_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="applicant_post" value="{{ old('applicant_post', $application->post) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('applicant_post')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="programme_area" value="{{ old('programme_area', $application->programme_area) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('programme_area')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section <span style="color: #2c3e50;">*</span></label>
                    <input type="text" name="faculty_school_centre_section" value="{{ old('faculty_school_centre_section', $application->faculty_school_centre_section) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('faculty_school_centre_section')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 2: DETAILS OF EVENT -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 2: DETAILS OF EVENT</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Type <span style="color: #e74c3c;">*</span></label>
                    <select name="event_type" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="">Select Event Type</option>
                        <option value="Training Course" {{ old('event_type', $application->event_type) == 'Training Course' ? 'selected' : '' }}>Training Course</option>
                        <option value="Meeting" {{ old('event_type', $application->event_type) == 'Meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="Conference" {{ old('event_type', $application->event_type) == 'Conference' ? 'selected' : '' }}>Conference</option>
                        <option value="Seminar" {{ old('event_type', $application->event_type) == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Workshop" {{ old('event_type', $application->event_type) == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="Working Visit" {{ old('event_type', $application->event_type) == 'Working Visit' ? 'selected' : '' }}>Working Visit</option>
                        <option value="Work Placement" {{ old('event_type', $application->event_type) == 'Work Placement' ? 'selected' : '' }}>Work Placement</option>
                        <option value="Industrial Placement" {{ old('event_type', $application->event_type) == 'Industrial Placement' ? 'selected' : '' }}>Industrial Placement</option>
                    </select>
                    @error('event_type')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="event_date" value="{{ old('event_date', $application->event_date ? $application->event_date->format('Y-m-d') : '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('event_date')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="event_title" value="{{ old('event_title', $application->event_title) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('event_title')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 3: SUITABILITY IN ATTENDING EVENT -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 3: SUITABILITY IN ATTENDING EVENT</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Please state the suitability of the applicant in attending the event <span style="color: #e74c3c;">*</span></label>
                <textarea name="suitability_statement" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('suitability_statement') }}</textarea>
                @error('suitability_statement')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Reasons for recommendation <span style="color: #e74c3c;">*</span></label>
                <textarea name="reasons_for_recommendation" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('reasons_for_recommendation') }}</textarea>
                @error('reasons_for_recommendation')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations upon completion <span style="color: #e74c3c;">*</span></label>
                <textarea name="expectations_upon_completion" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('expectations_upon_completion') }}</textarea>
                @error('expectations_upon_completion')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommended benefits / allowances to be provided to applicant <span style="color: #e74c3c;">*</span></label>
                <textarea name="recommended_benefits_allowances" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('recommended_benefits_allowances') }}</textarea>
                @error('recommended_benefits_allowances')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name of Staff (and post) recommended to discharge duties of the applicant while attending the event <span style="color: #e74c3c;">*</span></label>
                <textarea name="staff_recommended_to_discharge_duties" rows="3" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('staff_recommended_to_discharge_duties') }}</textarea>
                @error('staff_recommended_to_discharge_duties')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name of Programme Leader <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="programme_leader_name" value="{{ old('programme_leader_name') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('programme_leader_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post of Programme Leader <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="programme_leader_post" value="{{ old('programme_leader_post') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('programme_leader_post')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                

            </div>
        </div>

        <!-- SECTION 4: COMMENTS AND RECOMMENDATION OF DEAN / DIRECTOR -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 4: COMMENTS AND RECOMMENDATION OF DEAN / DIRECTOR OF FACULTY / SCHOOL / CENTRE / HEAD OF SECTION</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Has the staff contributed to the Faculty / School Consultancy Fund? <span style="color: #e74c3c;">*</span></label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="has_contributed_to_consultancy_fund" value="1" {{ old('has_contributed_to_consultancy_fund') == '1' ? 'checked' : '' }} required>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="has_contributed_to_consultancy_fund" value="0" {{ old('has_contributed_to_consultancy_fund') == '0' ? 'checked' : '' }} required>
                        <span>No</span>
                    </label>
                </div>
                @error('has_contributed_to_consultancy_fund')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">If yes, please provide details</label>
                <textarea name="consultancy_fund_details" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('consultancy_fund_details') }}</textarea>
                @error('consultancy_fund_details')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Dean Comments and Recommendation</label>
                <textarea name="dean_comments_recommendation" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('dean_comments_recommendation') }}</textarea>
                @error('dean_comments_recommendation')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Do you recommend for the staff to use the Faculty / School Consultancy Fund to top-up any expenses to attend the event? <span style="color: #e74c3c;">*</span></label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="recommend_use_consultancy_fund" value="1" {{ old('recommend_use_consultancy_fund') == '1' ? 'checked' : '' }} required>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="radio" name="recommend_use_consultancy_fund" value="0" {{ old('recommend_use_consultancy_fund') == '0' ? 'checked' : '' }} required>
                        <span>No</span>
                    </label>
                </div>
                @error('recommend_use_consultancy_fund')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">If No, please provide details</label>
                <textarea name="consultancy_fund_rejection_reason" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('consultancy_fund_rejection_reason') }}</textarea>
                @error('consultancy_fund_rejection_reason')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            

            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="dean_name" value="{{ old('dean_name') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('dean_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="dean_post" value="{{ old('dean_post') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('dean_post')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="display: flex; gap: 10px; justify-content: center; margin-top: 30px;">
            <button type="submit" style="background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 16px;">
                Create Recommendation
            </button>
            <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px;">
                Cancel
            </a>
        </div>
    </form>
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