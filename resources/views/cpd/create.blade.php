@extends('layouts.app')

@section('page-title', 'New CPD Application - UTB HR Central')

@section('content')
@php
    $user = auth()->user();
    $showBackButton = true;
@endphp
<div class="content-card">
    <h2 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">CONTINUOUS PROFESSIONAL DEVELOPMENT APPLICATION FORM</h2>
    <p style="text-align: center; color: #6c757d; margin-bottom: 30px;">For Attending Training Course / Meeting / Conference / Seminar / Workshop / Working Visit / Work Placement / Industrial Placement</p>

    <!-- Duplicate Check Notice -->
    <div style="background: #e3f2fd; border: 1px solid #2196f3; color: #1565c0; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
        <strong>📋 Important Notice:</strong> The system will check for duplicate applications based on event title, date, venue, and host country. You cannot submit multiple applications for the same event.
    </div>

    <form action="{{ route('cpd.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 1000px; margin: 0 auto;">
        @csrf
        
        <!-- Section 1: Applicant Details -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 1: DETAILS OF APPLICANT</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Name</label>
                    <input type="text" value="{{ $user->full_name }}" readonly style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: #f8f9fa;">
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">IC No.</label>
                    <input type="text" name="ic_number" value="{{ old('ic_number', $user->ic_number) }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('ic_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Passport No.</label>
                    <input type="text" name="passport_number" value="{{ old('passport_number', $user->passport_number) }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('passport_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Post</label>
                    <input type="text" name="post" value="{{ old('post', $user->post ?? '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('post')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Programme Area</label>
                    <input type="text" name="programme_area" value="{{ old('programme_area', $user->programme_area ?? '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('programme_area')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Faculty/School/Centre/Section</label>
                    <input type="text" name="faculty_school_centre_section" value="{{ old('faculty_school_centre_section', $user->faculty_school_centre_section ?? '') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('faculty_school_centre_section')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Event Details -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 2: EVENT DETAILS</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Type (Please select)</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    @php
                        $eventTypes = ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement'];
                    @endphp
                    @foreach($eventTypes as $type)
                        <label style="display: flex; align-items: center; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="event_type" value="{{ $type }}" {{ old('event_type') == $type ? 'checked' : '' }} required>
                            <span>{{ $type }}</span>
                        </label>
                    @endforeach
                </div>
                @error('event_type')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                    <input type="text" name="event_title" value="{{ old('event_title') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Enter a descriptive event title (minimum 5 characters)">
                    @error('event_title')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date</label>
                    <input type="date" name="event_date" value="{{ old('event_date') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('event_date')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="venue" value="{{ old('venue') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('venue')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Host Country <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="host_country" value="{{ old('host_country') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('host_country')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Organiser Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="organiser_name" value="{{ old('organiser_name') }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('organiser_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Required Information -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 3: REQUIRED INFORMATION</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Relevance of Event to Applicant's Post and Duties <span style="color: #e74c3c;">*</span></label>
                <textarea name="relevance_to_post" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('relevance_to_post') }}</textarea>
                @error('relevance_to_post')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Expectations Upon Completion of Attending The Event <span style="color: #e74c3c;">*</span></label>
                <textarea name="expectations_upon_completion" rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('expectations_upon_completion') }}</textarea>
                @error('expectations_upon_completion')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Section 4: Registration Fee -->
        <div id="registration-fee-section" style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; display: none;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 4: REGISTRATION FEE</h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Amount</label>
                    <input type="number" name="registration_fee" value="{{ old('registration_fee') }}" step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('registration_fee')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Deadline for paying Registration Fee</label>
                    <input type="date" name="registration_fee_deadline" value="{{ old('registration_fee_deadline') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('registration_fee_deadline')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Payment of Registration Fee (Please select your preference) <span style="color: #e74c3c;">*</span></label>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                        <input type="radio" name="payment_preference" value="bursar_office" {{ old('payment_preference') == 'bursar_office' ? 'checked' : '' }}>
                        <span>The Bursar Office to pay the registration fee directly to the organiser (Please submit invoice for payment of registration fee to CPD Secretariat upon approval of application.)</span>
                    </label>
                    <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                        <input type="radio" name="payment_preference" value="pay_first_submit_receipt" {{ old('payment_preference') == 'pay_first_submit_receipt' ? 'checked' : '' }}>
                        <span>Pay registration fee first and to give receipt to CPD secretariat at least three weeks before start of event</span>
                    </label>
                    <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                        <input type="radio" name="payment_preference" value="pay_first_reimburse" {{ old('payment_preference') == 'pay_first_reimburse' ? 'checked' : '' }}>
                        <span>Pay registration fee first and reimburse after attending the event</span>
                    </label>
                </div>
                @error('payment_preference')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Please state what is included in the registration fee</label>
                <textarea name="registration_fee_includes" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('registration_fee_includes') }}</textarea>
                @error('registration_fee_includes')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Other benefits provided by organiser (e.g. air ticket, accommodation, etc)</label>
                <textarea name="other_benefits" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('other_benefits') }}</textarea>
                @error('other_benefits')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Section 5: Paper Presentation -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">PAPER TO BE PRESENTED</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Paper to be presented, if any?</label>
                <input type="text" name="paper_title_1" value="{{ old('paper_title_1') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                @error('paper_title_1')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Are you the first author of the paper?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="is_first_author_1" value="1" {{ old('is_first_author_1') == '1' ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="is_first_author_1" value="0" {{ old('is_first_author_1') == '0' ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('is_first_author_1')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Second Paper Title (if applicable)</label>
                <input type="text" name="paper_title_2" value="{{ old('paper_title_2') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                @error('paper_title_2')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Are you the first author of the second paper?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="is_first_author_2" value="1" {{ old('is_first_author_2') == '1' ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="is_first_author_2" value="0" {{ old('is_first_author_2') == '0' ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('is_first_author_2')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">State the publication where the paper presented will be published</label>
                <input type="text" name="publication_name" value="{{ old('publication_name') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                @error('publication_name')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Section 6: Logistics -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">LOGISTICS</h3>
            
            <!-- Flight Itinerary -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">FLIGHT ITINERARY</h4>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Airfare provided by organiser?</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="airfare_provided_by_organiser" value="1" {{ old('airfare_provided_by_organiser') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="airfare_provided_by_organiser" value="0" {{ old('airfare_provided_by_organiser') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                    @error('airfare_provided_by_organiser')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Flight Route</label>
                        <input type="text" name="flight_route" value="{{ old('flight_route') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        @error('flight_route')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Ticket Purchase Preference</label>
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="ticket_purchase_preference" value="cpd_secretariat" {{ old('ticket_purchase_preference') == 'cpd_secretariat' ? 'checked' : '' }}>
                                <span>The CPD Secretariat to purchase your ticket (Government selected travel agents using 3 quotations)</span>
                            </label>
                            <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="ticket_purchase_preference" value="purchase_self_submit" {{ old('ticket_purchase_preference') == 'purchase_self_submit' ? 'checked' : '' }}>
                                <span>Purchase ticket yourself and submit to CPD office at least three weeks before start of event</span>
                            </label>
                            <label style="display: flex; align-items: flex-start; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                                <input type="radio" name="ticket_purchase_preference" value="purchase_self_reimburse" {{ old('ticket_purchase_preference') == 'purchase_self_reimburse' ? 'checked' : '' }}>
                                <span>Purchase ticket yourself and reimburse after attending the event</span>
                            </label>
                        </div>
                        @error('ticket_purchase_preference')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Departure Date</label>
                        <input type="date" name="departure_date" value="{{ old('departure_date') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        @error('departure_date')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Arrival Date</label>
                        <input type="date" name="arrival_date" value="{{ old('arrival_date') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        @error('arrival_date')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Accommodation -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">ACCOMMODATION</h4>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Accommodation provided by organiser?</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="accommodation_provided_by_organiser" value="1" {{ old('accommodation_provided_by_organiser') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="accommodation_provided_by_organiser" value="0" {{ old('accommodation_provided_by_organiser') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                    @error('accommodation_provided_by_organiser')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Hotel Name and Address</label>
                    <textarea name="hotel_name_address" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('hotel_name_address') }}</textarea>
                    @error('hotel_name_address')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is accommodation similar to venue?</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="accommodation_similar_to_venue" value="1" {{ old('accommodation_similar_to_venue') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="accommodation_similar_to_venue" value="0" {{ old('accommodation_similar_to_venue') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                    @error('accommodation_similar_to_venue')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Transportation -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">TRANSPORTATION</h4>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Airport Transfer provided?</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="radio" name="airport_transfer_provided" value="1" {{ old('airport_transfer_provided') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="radio" name="airport_transfer_provided" value="0" {{ old('airport_transfer_provided') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                        @error('airport_transfer_provided')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Daily Transportation provided?</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="radio" name="daily_transportation_provided" value="1" {{ old('daily_transportation_provided') == '1' ? 'checked' : '' }}>
                                <span>Yes</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px;">
                                <input type="radio" name="daily_transportation_provided" value="0" {{ old('daily_transportation_provided') == '0' ? 'checked' : '' }}>
                                <span>No</span>
                            </label>
                        </div>
                        @error('daily_transportation_provided')
                            <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Allowance -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">ALLOWANCE</h4>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Daily Allowance provided?</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="daily_allowance_provided" value="1" {{ old('daily_allowance_provided') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="daily_allowance_provided" value="0" {{ old('daily_allowance_provided') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                    @error('daily_allowance_provided')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Allowance Amount</label>
                    <input type="text" name="allowance_amount" value="{{ old('allowance_amount') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('allowance_amount')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Visa -->
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px;">VISA</h4>
                
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Is Visa required?</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="visa_required" value="1" {{ old('visa_required') == '1' ? 'checked' : '' }}>
                            <span>Yes</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="radio" name="visa_required" value="0" {{ old('visa_required') == '0' ? 'checked' : '' }}>
                            <span>No</span>
                        </label>
                    </div>
                    @error('visa_required')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 7: Previous Event Details -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 7: DETAILS OF PREVIOUS EVENT ATTENDED</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Type of Event (Please select)</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px;">
                    @php
                        $eventTypes = ['Training Course', 'Meeting', 'Conference', 'Seminar', 'Workshop', 'Working Visit', 'Work Placement', 'Industrial Placement'];
                    @endphp
                    @foreach($eventTypes as $type)
                        <label style="display: flex; align-items: center; gap: 8px; padding: 10px; border: 1px solid #ddd; border-radius: 6px; cursor: pointer;">
                            <input type="radio" name="previous_event_type" value="{{ $type }}" {{ old('previous_event_type') == $type ? 'checked' : '' }}>
                            <span>{{ $type }}</span>
                        </label>
                    @endforeach
                </div>
                @error('previous_event_type')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Title of Event</label>
                    <input type="text" name="previous_event_title" value="{{ old('previous_event_title') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('previous_event_title')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date of Event</label>
                    <input type="date" name="previous_event_date" value="{{ old('previous_event_date') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('previous_event_date')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Venue of Event</label>
                    <input type="text" name="previous_event_venue" value="{{ old('previous_event_venue') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('previous_event_venue')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Was your paper published?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="paper_published" value="1" {{ old('paper_published') == '1' ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="paper_published" value="0" {{ old('paper_published') == '0' ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('paper_published')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">If Yes, please state the publication and date it was published</label>
                <textarea name="publication_details" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('publication_details') }}</textarea>
                @error('publication_details')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Have you submitted your report for this event?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="report_submitted" value="1" {{ old('report_submitted') == '1' ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="report_submitted" value="0" {{ old('report_submitted') == '0' ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('report_submitted')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Section 8: Consultancy Fund -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 8: CONTRIBUTION TO FACULTY / SCHOOL CONSULTANCY FUND</h3>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600; color: #2c3e50;">Have you contributed to the Faculty/School Consultancy Fund?</label>
                <div style="display: flex; gap: 20px;">
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="contributed_to_consultancy_fund" value="1" {{ old('contributed_to_consultancy_fund') == '1' ? 'checked' : '' }}>
                        <span>Yes</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="contributed_to_consultancy_fund" value="0" {{ old('contributed_to_consultancy_fund') == '0' ? 'checked' : '' }}>
                        <span>No</span>
                    </label>
                </div>
                @error('contributed_to_consultancy_fund')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Contribution Amount</label>
                    <input type="number" name="contribution_amount" value="{{ old('contribution_amount') }}" step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('contribution_amount')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Entitlement Amount</label>
                    <input type="number" name="entitlement_amount" value="{{ old('entitlement_amount') }}" step="0.01" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    @error('entitlement_amount')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Purpose of Fund</label>
                <textarea name="fund_purpose" rows="3" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; resize: vertical;">{{ old('fund_purpose') }}</textarea>
                @error('fund_purpose')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Section 9: Supporting Documents -->
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
            <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">SECTION 9: SUPPORTING DOCUMENTS</h3>
            <p style="color: #6c757d; margin-bottom: 20px; font-style: italic;">Please upload relevant supporting documents. All files must be in PDF, DOC, DOCX, JPG, JPEG, or PNG format (max 10MB each).</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Brochure/Flyer</label>
                    <input type="file" name="event_brochure" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Event brochure, flyer, or promotional materials</small>
                    @error('event_brochure')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Paper Abstract</label>
                    <input type="file" name="paper_abstract" accept=".pdf,.doc,.docx" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Abstract or summary of paper to be presented</small>
                    @error('paper_abstract')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Previous Event Certificate</label>
                    <input type="file" name="previous_certificate" accept=".pdf,.jpg,.jpeg,.png" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Certificate from previously attended event</small>
                    @error('previous_certificate')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Published Paper/Article</label>
                    <input type="file" name="publication_paper" accept=".pdf,.doc,.docx" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Published paper or article from previous event</small>
                    @error('publication_paper')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Travel Documents</label>
                    <input type="file" name="travel_documents" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Travel itinerary, visa documents, or flight details</small>
                    @error('travel_documents')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Accommodation Details</label>
                    <input type="file" name="accommodation_details" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Hotel booking confirmation or accommodation details</small>
                    @error('accommodation_details')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Budget Breakdown</label>
                    <input type="file" name="budget_breakdown" accept=".pdf,.doc,.docx,.xls,.xlsx" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Detailed budget breakdown or cost analysis</small>
                    @error('budget_breakdown')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Other Supporting Documents</label>
                    <input type="file" name="other_documents" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    <small style="color: #6c757d; font-size: 12px;">Any other relevant supporting documents</small>
                    @error('other_documents')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" style="background: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;">
                Submit Application
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

/* CPD submit button hover effect */
button[type="submit"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
    z-index: 0;
    pointer-events: none;
}

button[type="submit"]:hover::before {
    left: 100%;
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('CPD form script loaded - targeted approach');
    
    const eventTypeRadios = document.querySelectorAll('input[name="event_type"]');
    const form = document.querySelector('form');
    
    if (!form) {
        console.error('Form not found');
        return;
    }
    
    // Add CSS for hiding sections
    const style = document.createElement('style');
    style.textContent = `
        .cpd-hidden { 
            display: none !important; 
        }
    `;
    document.head.appendChild(style);
    
    // Find sections by their h3 content
    function findSectionByTitle(title) {
        const allDivs = form.querySelectorAll('div');
        for (let div of allDivs) {
            const h3 = div.querySelector('h3');
            const h4 = div.querySelector('h4');
            const heading = h3 || h4;
            if (heading && heading.textContent.trim().includes(title)) {
                console.log('Found section for title "' + title + '":', heading.textContent.trim());
                return div;
            }
        }
        console.log('Section not found for title: "' + title + '"');
        return null;
    }
    
    const paperSection = findSectionByTitle('PAPER TO BE PRESENTED');
    const logisticsSection = findSectionByTitle('LOGISTICS');
    const previousEventSection = findSectionByTitle('SECTION 7: DETAILS OF PREVIOUS EVENT ATTENDED');
    const consultancyFundSection = findSectionByTitle('SECTION 8: CONTRIBUTION TO FACULTY / SCHOOL CONSULTANCY FUND');
    const registrationFeeSection = findSectionByTitle('SECTION 4: REGISTRATION FEE');
    const fileUploadSection = findSectionByTitle('SECTION 9: SUPPORTING DOCUMENTS');
    
    console.log('Sections found:', {
        paper: paperSection,
        logistics: logisticsSection,
        previousEvent: previousEventSection,
        consultancyFund: consultancyFundSection,
        registrationFee: registrationFeeSection
    });
    
    // Event type change handler
    eventTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const selectedEventType = this.value;
            console.log('Event type changed to:', selectedEventType);
            
            // Handle registration fee section visibility
            if (selectedEventType === 'Meeting') {
                // Hide registration fee section for Meeting
                if (registrationFeeSection) {
                    registrationFeeSection.style.display = 'none';
                    registrationFeeSection.classList.add('cpd-hidden');
                    
                    // Remove required attributes from payment preference radio buttons
                    const paymentPreferenceRadios = registrationFeeSection.querySelectorAll('input[name="payment_preference"]');
                    paymentPreferenceRadios.forEach(radio => {
                        radio.removeAttribute('required');
                    });
                }
            } else {
                // Show registration fee section for all other event types
                if (registrationFeeSection) {
                    registrationFeeSection.style.display = 'block';
                    registrationFeeSection.classList.remove('cpd-hidden');
                    
                    // Add required attributes to payment preference radio buttons
                    const paymentPreferenceRadios = registrationFeeSection.querySelectorAll('input[name="payment_preference"]');
                    paymentPreferenceRadios.forEach(radio => {
                        radio.setAttribute('required', 'required');
                    });
                }
            }
        });
    });
});
</script>
@endsection 