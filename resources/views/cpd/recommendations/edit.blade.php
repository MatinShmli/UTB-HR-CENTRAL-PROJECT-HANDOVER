@extends('layouts.app')

@section('page-title', 'Edit CPD Recommendation - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Edit CPD Recommendation</h2>
        <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Back to Recommendations
        </a>
    </div>

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('cpd.recommendations.update', $recommendation) }}" method="POST" style="max-width: 1200px;">
        @csrf
        @method('PUT')
        
        <!-- SECTION 1: DETAILS OF APPLICANT -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 1: DETAILS OF APPLICANT</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="applicant_name" value="{{ old('applicant_name', $recommendation->applicant_name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('applicant_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="applicant_post" value="{{ old('applicant_post', $recommendation->applicant_post) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('applicant_post')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="programme_area" value="{{ old('programme_area', $recommendation->programme_area) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('programme_area')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section <span style="color: #2c3e50;">*</span></label>
                    <input type="text" name="faculty_school_centre_section" value="{{ old('faculty_school_centre_section', $recommendation->faculty_school_centre_section) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
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
                        <option value="Training Course" {{ old('event_type', $recommendation->event_type) == 'Training Course' ? 'selected' : '' }}>Training Course</option>
                        <option value="Meeting" {{ old('event_type', $recommendation->event_type) == 'Meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="Conference" {{ old('event_type', $recommendation->event_type) == 'Conference' ? 'selected' : '' }}>Conference</option>
                        <option value="Seminar" {{ old('event_type', $recommendation->event_type) == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Workshop" {{ old('event_type', $recommendation->event_type) == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="Working Visit" {{ old('event_type', $recommendation->event_type) == 'Working Visit' ? 'selected' : '' }}>Working Visit</option>
                        <option value="Work Placement" {{ old('event_type', $recommendation->event_type) == 'Work Placement' ? 'selected' : '' }}>Work Placement</option>
                        <option value="Industrial Placement" {{ old('event_type', $recommendation->event_type) == 'Industrial Placement' ? 'selected' : '' }}>Industrial Placement</option>
                    </select>
                    @error('event_type')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="event_date" value="{{ old('event_date', $recommendation->event_date ? $recommendation->event_date->format('Y-m-d') : '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('event_date')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="event_title" value="{{ old('event_title', $recommendation->event_title) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('event_title')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="venue" value="{{ old('venue', $recommendation->venue) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('venue')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Host Country <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="host_country" value="{{ old('host_country', $recommendation->host_country) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('host_country')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Organiser Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="organiser_name" value="{{ old('organiser_name', $recommendation->organiser_name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('organiser_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 3: RECOMMENDATION DETAILS -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 3: RECOMMENDATION DETAILS</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Relevance to Post and Duties <span style="color: #e74c3c;">*</span></label>
                <textarea name="relevance_to_post" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('relevance_to_post', $recommendation->relevance_to_post) }}</textarea>
                @error('relevance_to_post')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expected Benefits <span style="color: #e74c3c;">*</span></label>
                <textarea name="expected_benefits" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('expected_benefits', $recommendation->expected_benefits) }}</textarea>
                @error('expected_benefits')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Impact on Institution <span style="color: #e74c3c;">*</span></label>
                <textarea name="impact_on_institution" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('impact_on_institution', $recommendation->impact_on_institution) }}</textarea>
                @error('impact_on_institution')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Recommendation <span style="color: #e74c3c;">*</span></label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="recommendation" value="approve" {{ old('recommendation', $recommendation->recommendation) == 'approve' ? 'checked' : '' }} required>
                        <span>Approve</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="recommendation" value="reject" {{ old('recommendation', $recommendation->recommendation) == 'reject' ? 'checked' : '' }} required>
                        <span>Reject</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="recommendation" value="rework" {{ old('recommendation', $recommendation->recommendation) == 'rework' ? 'checked' : '' }} required>
                        <span>Rework</span>
                    </label>
                </div>
                @error('recommendation')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Additional Comments</label>
                <textarea name="additional_comments" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('additional_comments', $recommendation->additional_comments) }}</textarea>
                @error('additional_comments')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- SECTION 4: RECOMMENDER DETAILS -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 4: RECOMMENDER DETAILS</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommender Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="recommender_name" value="{{ old('recommender_name', $recommendation->recommender_name) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('recommender_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Recommender Position <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="recommender_position" value="{{ old('recommender_position', $recommendation->recommender_position) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('recommender_position')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="recommendation_date" value="{{ old('recommendation_date', $recommendation->recommendation_date ? $recommendation->recommendation_date->format('Y-m-d') : '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('recommendation_date')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Signature</label>
                    <input type="text" name="signature" value="{{ old('signature', $recommendation->signature) }}" placeholder="Type your name as signature" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('signature')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" style="background: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                Update Recommendation
            </button>
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

button[type="submit"]:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
}

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    form > div > div {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection 