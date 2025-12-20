<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPD Application - {{ $application->event_title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header p {
            color: #6c757d;
            margin: 0;
            font-size: 14px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h3 {
            color: #2c3e50;
            border-bottom: 1px solid #3498db;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        .grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .grid-row {
            display: table-row;
        }
        .grid-cell {
            display: table-cell;
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .value {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            min-height: 20px;
        }
        .full-width {
            width: 100%;
        }
        .status-badge {
            display: inline-block;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-approved {
            background: #28a745;
        }
        .status-rejected {
            background: #dc3545;
        }
        .page-break {
            page-break-before: always;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mb-10 {
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CONTINUOUS PROFESSIONAL DEVELOPMENT APPLICATION</h1>
        <p>For Attending Training Course / Meeting / Conference / Seminar / Workshop / Working Visit / Work Placement / Industrial Placement</p>
        <div class="status-badge status-{{ $application->status === 'approved' ? 'approved' : 'rejected' }}">{{ strtoupper($application->status) }}</div>
    </div>

    <!-- SECTION 1: DETAILS OF APPLICANT -->
    <div class="section">
        <h3>SECTION 1: DETAILS OF APPLICANT</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Name</div>
                    <div class="value">{{ $application->user->full_name ?? $application->user->name }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">IC No.</div>
                    <div class="value">{{ $application->ic_number }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Passport No.</div>
                    <div class="value">{{ $application->passport_number ?? 'Not provided' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Post</div>
                    <div class="value">{{ $application->post }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Programme Area</div>
                    <div class="value">{{ $application->programme_area }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Faculty/School/Centre/Section</div>
                    <div class="value">{{ $application->faculty_school_centre_section }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 2: EVENT DETAILS -->
    <div class="section">
        <h3>SECTION 2: EVENT DETAILS</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Event Type</div>
                    <div class="value">{{ $application->event_type }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Event Title</div>
                    <div class="value">{{ $application->event_title }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Event Date</div>
                    <div class="value">{{ $application->event_date->format('d/m/Y') }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Venue</div>
                    <div class="value">{{ $application->venue }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Host Country</div>
                    <div class="value">{{ $application->host_country }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Organiser Name</div>
                    <div class="value">{{ $application->organiser_name }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($application->event_type !== 'Meeting')
    <!-- REGISTRATION FEE SECTION -->
    <div class="section">
        <h3>REGISTRATION FEE</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Registration Fee Amount</div>
                    <div class="value">{{ $application->registration_fee ? '$' . number_format($application->registration_fee, 2) : 'Not specified' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Payment Deadline</div>
                    <div class="value">{{ $application->registration_fee_deadline ? $application->registration_fee_deadline->format('d/m/Y') : 'Not specified' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Payment Preference</div>
                    <div class="value">{{ $application->payment_preference_text }}</div>
                </div>
            </div>
            @if($application->registration_fee_includes)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">What's Included in Registration Fee</div>
                    <div class="value">{{ $application->registration_fee_includes }}</div>
                </div>
            </div>
            @endif
            @if($application->other_benefits)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Other Benefits Provided</div>
                    <div class="value">{{ $application->other_benefits }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- REQUIRED INFORMATION -->
    <div class="section">
        <h3>REQUIRED INFORMATION</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Relevance to Post and Duties</div>
                    <div class="value">{{ $application->relevance_to_post }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Expectations Upon Completion</div>
                    <div class="value">{{ $application->expectations_upon_completion }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($application->paper_title_1)
    <!-- PAPER PRESENTATION -->
    <div class="section">
        <h3>PAPER TO BE PRESENTED</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Paper Title</div>
                    <div class="value">{{ $application->paper_title_1 }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">First Author</div>
                    <div class="value">{{ $application->is_first_author_1 ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Publication Name</div>
                    <div class="value">{{ $application->publication_name ?? 'Not specified' }}</div>
                </div>
            </div>
            @if($application->paper_title_2)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Second Paper Title</div>
                    <div class="value">{{ $application->paper_title_2 }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">First Author (Second Paper)</div>
                    <div class="value">{{ $application->is_first_author_2 ? 'Yes' : 'No' }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($application->previous_event_title)
    <!-- PREVIOUS EVENT DETAILS -->
    <div class="section">
        <h3>DETAILS OF PREVIOUS EVENT ATTENDED</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Previous Event Type</div>
                    <div class="value">{{ $application->previous_event_type }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Previous Event Title</div>
                    <div class="value">{{ $application->previous_event_title }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Previous Event Date</div>
                    <div class="value">{{ $application->previous_event_date ? $application->previous_event_date->format('d/m/Y') : 'Not specified' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Previous Event Venue</div>
                    <div class="value">{{ $application->previous_event_venue ?? 'Not specified' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Paper Published</div>
                    <div class="value">{{ $application->paper_published ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Report Submitted</div>
                    <div class="value">{{ $application->report_submitted ? 'Yes' : 'No' }}</div>
                </div>
            </div>
            @if($application->publication_details)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Publication Details</div>
                    <div class="value">{{ $application->publication_details }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- LOGISTICS -->
    <div class="section">
        <h3>LOGISTICS</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Airfare Provided by Organiser</div>
                    <div class="value">{{ $application->airfare_provided_by_organiser ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Flight Route</div>
                    <div class="value">{{ $application->flight_route ?? 'Not specified' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Departure Date</div>
                    <div class="value">{{ $application->departure_date ? $application->departure_date->format('d/m/Y') : 'Not specified' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Arrival Date</div>
                    <div class="value">{{ $application->arrival_date ? $application->arrival_date->format('d/m/Y') : 'Not specified' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Ticket Purchase Preference</div>
                    <div class="value">{{ $application->ticket_purchase_preference_text ?? 'Not specified' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Accommodation Provided</div>
                    <div class="value">{{ $application->accommodation_provided_by_organiser ? 'Yes' : 'No' }}</div>
                </div>
            </div>
            @if($application->hotel_name_address)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Hotel Name and Address</div>
                    <div class="value">{{ $application->hotel_name_address }}</div>
                </div>
            </div>
            @endif
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Accommodation Similar to Venue</div>
                    <div class="value">{{ $application->accommodation_similar_to_venue ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Airport Transfer Provided</div>
                    <div class="value">{{ $application->airport_transfer_provided ? 'Yes' : 'No' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Daily Transportation Provided</div>
                    <div class="value">{{ $application->daily_transportation_provided ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Daily Allowance Provided</div>
                    <div class="value">{{ $application->daily_allowance_provided ? 'Yes' : 'No' }}</div>
                </div>
            </div>
            @if($application->allowance_amount)
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Allowance Amount</div>
                    <div class="value">{{ $application->allowance_amount }}</div>
                </div>
            </div>
            @endif
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Visa Required</div>
                    <div class="value">{{ $application->visa_required ? 'Yes' : 'No' }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($application->contributed_to_consultancy_fund)
    <!-- CONSULTANCY FUND -->
    <div class="section">
        <h3>CONTRIBUTION TO FACULTY / SCHOOL CONSULTANCY FUND</h3>
        <div class="grid">
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Contributed to Consultancy Fund</div>
                    <div class="value">{{ $application->contributed_to_consultancy_fund ? 'Yes' : 'No' }}</div>
                </div>
                <div class="grid-cell">
                    <div class="label">Contribution Amount</div>
                    <div class="value">{{ $application->contribution_amount ? '$' . number_format($application->contribution_amount, 2) : 'Not specified' }}</div>
                </div>
            </div>
            <div class="grid-row">
                <div class="grid-cell">
                    <div class="label">Entitlement Amount</div>
                    <div class="value">{{ $application->entitlement_amount ? '$' . number_format($application->entitlement_amount, 2) : 'Not specified' }}</div>
                </div>
            </div>
            @if($application->fund_purpose)
            <div class="grid-row">
                <div class="grid-cell full-width">
                    <div class="label">Fund Purpose</div>
                    <div class="value">{{ $application->fund_purpose }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="footer">
        <div class="text-center">
            <p><strong>Submitted:</strong> {{ $application->submitted_at ? $application->submitted_at->format('d/m/Y H:i') : 'Not submitted' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
            <p><strong>Generated on:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html> 