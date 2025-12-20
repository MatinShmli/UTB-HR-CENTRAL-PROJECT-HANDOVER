@extends('layouts.app')

@section('page-title', 'CPD Applications - Admin - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <!-- Tab Navigation -->
    <div style="margin-bottom: 30px;">
        <div style="display: flex; border-bottom: 2px solid #e9ecef;">
            <button id="cpd-tab" class="tab-button active" onclick="showTab('cpd')" style="background: #3498db; color: white; border: none; padding: 15px 30px; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 8px 8px 0 0; margin-right: 5px;">
                📚 CPD Applications
            </button>
            <button id="learning-tab" class="tab-button" onclick="showTab('learning')" style="background: #6c757d; color: white; border: none; padding: 15px 30px; font-size: 16px; font-weight: 600; cursor: pointer; border-radius: 8px 8px 0 0; margin-right: 5px;">
                🚪 Learning & Development
            </button>
        </div>
    </div>

    <!-- CPD Applications Tab -->
    <div id="cpd-content" class="tab-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: #2c3e50; margin: 0;">CPD Applications - Admin Panel</h2>
            <div style="display: flex; gap: 15px;">
                                 <a href="{{ route('cpd.recommendations.index') }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                     📋 My Recommendations
                 </a>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <form method="GET" action="{{ route('admin.cpd.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">
                <!-- Search Input -->
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Search Applications</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by applicant, event title, venue, organizer..." style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
                </div>

                <!-- Status Filter -->
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Status</label>
                    <select name="status" style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; background: white;">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="rework" {{ request('status') == 'rework' ? 'selected' : '' }}>Rework Required</option>
                    </select>
                </div>

                <!-- Event Type Filter -->
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Event Type</label>
                    <select name="event_type" style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; background: white;">
                        <option value="all" {{ request('event_type') == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="Training Course" {{ request('event_type') == 'Training Course' ? 'selected' : '' }}>Training Course</option>
                        <option value="Meeting" {{ request('event_type') == 'Meeting' ? 'selected' : '' }}>Meeting</option>
                        <option value="Conference" {{ request('event_type') == 'Conference' ? 'selected' : '' }}>Conference</option>
                        <option value="Seminar" {{ request('event_type') == 'Seminar' ? 'selected' : '' }}>Seminar</option>
                        <option value="Workshop" {{ request('event_type') == 'Workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="Working Visit" {{ request('event_type') == 'Working Visit' ? 'selected' : '' }}>Working Visit</option>
                        <option value="Work Placement" {{ request('event_type') == 'Work Placement' ? 'selected' : '' }}>Work Placement</option>
                        <option value="Industrial Placement" {{ request('event_type') == 'Industrial Placement' ? 'selected' : '' }}>Industrial Placement</option>
                    </select>
                </div>

                <!-- Date Range Filters -->
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px;">
                </div>

                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px;">
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        🔍 Search
                    </button>
                    <a href="{{ route('admin.cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                        🔄 Clear
                    </a>
                </div>
            </form>

            <!-- Search Results Summary -->
            @if(request()->hasAny(['search', 'status', 'event_type', 'date_from', 'date_to']) && (request('search') || request('status') !== 'all' || request('event_type') !== 'all' || request('date_from') || request('date_to')))
                <div style="margin-top: 15px; padding: 10px 15px; background: #e7f3ff; border-left: 4px solid #3498db; border-radius: 4px;">
                    <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">Search Results</div>
                    <div style="font-size: 14px; color: #495057;">
                        Found {{ $applications->total() }} application(s) 
                        @if(request('search'))
                            matching "{{ request('search') }}"
                        @endif
                        @if(request('status') && request('status') !== 'all')
                            with status "{{ ucfirst(str_replace('_', ' ', request('status'))) }}"
                        @endif
                        @if(request('event_type') && request('event_type') !== 'all')
                            of type "{{ request('event_type') }}"
                        @endif
                        @if(request('date_from') || request('date_to'))
                            @if(request('date_from') && request('date_to'))
                                between {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }} and {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                            @elseif(request('date_from'))
                                from {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}
                            @elseif(request('date_to'))
                                until {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}
                            @endif
                        @endif
                    </div>
                </div>
            @endif
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

        @if($applications->count() > 0)
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Applicant</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Title</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Type</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Date</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Status</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Submitted</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; color: #495057;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr style="border-bottom: 1px solid #dee2e6; transition: background 0.2s ease;">
                                    <td style="padding: 15px; color: #2c3e50;">
                                        <div style="font-weight: 600; margin-bottom: 5px;">{{ $application->user->full_name ?? $application->user->name }}</div>
                                        <div style="font-size: 12px; color: #6c757d;">{{ $application->user->email }}</div>
                                    </td>
                                    <td style="padding: 15px; color: #2c3e50;">
                                        <div style="font-weight: 600; margin-bottom: 5px;">{{ $application->event_title }}</div>
                                        <div style="font-size: 12px; color: #6c757d;">{{ $application->venue }}, {{ $application->host_country }}</div>
                                    </td>
                                    <td style="padding: 15px; color: #2c3e50;">{{ $application->event_type }}</td>
                                    <td style="padding: 15px; color: #2c3e50;">{{ $application->event_date->format('M d, Y') }}</td>
                                    <td style="padding: 15px;">
                                        @if($application->status === 'draft')
                                            <span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Draft</span>
                                        @elseif($application->status === 'submitted')
                                            <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Submitted</span>
                                        @elseif($application->status === 'under_review')
                                            <span style="background: #ffc107; color: #212529; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Under Review</span>
                                        @elseif($application->status === 'approved')
                                            <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Approved</span>
                                        @elseif($application->status === 'rejected')
                                            <span style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Rejected</span>
                                        @elseif($application->status === 'rework')
                                            <span style="background: #fd7e14; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Rework Required</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; color: #6c757d; font-size: 14px;">{{ $application->created_at->format('M d, Y') }}</td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.cpd.show', $application) }}" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View</a>
                                            <a href="{{ route('admin.cpd.download-pdf', $application) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">📄 PDF</a>
                                            
                                            @if($application->status === 'under_review')
                                                <form action="{{ route('admin.cpd.approve', $application) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application?')">Approve</button>
                                                </form>
                                                
                                                <form action="{{ route('admin.cpd.reject', $application) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                                                </form>
                                                
                                                <form action="{{ route('admin.cpd.rework', $application) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework?')">Rework</button>
                                                </form>
                                            @endif
                                            
                                            @if(in_array($application->status, ['approved', 'rejected']))
                                                <form action="{{ route('admin.cpd.delete', $application) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">Delete</button>
                                                </form>
                                            @endif
                                            
                                                                                         <!-- Recommendation buttons - only show own recommendations -->
                                             @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                                                 <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View Rec</a>
                                             @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $applications->links() }}
            </div>
        @else
            <div style="background: white; padding: 60px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 20px;">📚</div>
                <h3 style="color: #2c3e50; margin-bottom: 15px;">No CPD Applications Found</h3>
                <p style="color: #6c757d; margin-bottom: 30px;">There are no CPD applications submitted by staff members yet.</p>
            </div>
        @endif
    </div>

    <!-- Learning & Development Tab -->
    <div id="learning-content" class="tab-content" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: #2c3e50; margin: 0;">Learning & Development Programs - Admin</h2>
            <a href="#" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                Manage Programs
            </a>
        </div>

        <!-- Learning & Development Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Training Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🎓</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Training Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage professional training and skill development programs</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Programs</a>
            </div>

            <!-- Workshops -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🔧</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Workshops</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage interactive workshops and hands-on learning sessions</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Workshops</a>
            </div>

            <!-- Online Courses -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">💻</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Online Courses</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage self-paced online learning modules and courses</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Courses</a>
            </div>

            <!-- Leadership Development -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">👑</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Leadership Development</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage leadership skills and management training programs</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Programs</a>
            </div>

            <!-- Certification Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🏆</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Certification Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage professional certification and accreditation programs</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Certifications</a>
            </div>

            <!-- Mentorship Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🤝</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Mentorship Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Manage one-on-one mentoring and career guidance programs</p>
                <a href="#" style="background: #3498db; color: white; padding: 8px 16px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600;">Manage Mentors</a>
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

.tab-button {
    transition: all 0.3s ease;
}

.tab-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.tab-button.active {
    background: #3498db !important;
    color: white !important;
}

.tab-content {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    table {
        font-size: 14px;
    }
    
    th, td {
        padding: 10px 8px !important;
    }
    
    .tab-button {
        padding: 10px 15px !important;
        font-size: 14px !important;
    }
}
</style>

<script>
function showTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tab buttons
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(button => {
        button.classList.remove('active');
        button.style.background = '#6c757d';
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').style.display = 'block';
    
    // Add active class to selected tab button
    document.getElementById(tabName + '-tab').classList.add('active');
    document.getElementById(tabName + '-tab').style.background = '#3498db';
}
</script>
@endsection 