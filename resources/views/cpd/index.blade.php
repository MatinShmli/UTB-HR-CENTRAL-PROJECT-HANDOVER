@extends('layouts.app')

@section('page-title', 'CPD Applications - UTB HR Central')

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
            <h2 style="color: #2c3e50; margin: 0;">CPD Applications</h2>
            <div style="display: flex; gap: 10px;">
                @if(Auth::user()->role === 'Administrator')
                    <a href="{{ route('admin.cpd.index') }}" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                        Admin Review Panel
                    </a>
                @else
                    <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                        My Recommendations
                    </a>
                    <a href="{{ route('cpd.create') }}" style="background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        New Application
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

        <!-- Search and Filter Section -->
        <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <form method="GET" action="{{ route('cpd.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">
                <!-- Search Input -->
                <div style="flex: 2; min-width: 250px;">
                    <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Search Applications</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by event title, venue, organizer..." style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
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
                    <a href="{{ route('cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
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

        @if($applications->count() > 0)
            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="overflow-x: auto; min-width: 100%;">
                    <table style="width: 100%; min-width: 1000px; border-collapse: collapse; table-layout: fixed;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                @if(Auth::user()->role === 'Administrator')
                                    <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Applicant</th>
                                @endif
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Title</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Type</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Event Date</th>
                                <th class="status-column" style="padding: 15px; text-align: left; font-weight: 600; color: #495057; width: 200px; min-width: 200px; white-space: nowrap;">Status</th>
                                <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Created</th>
                                <th style="padding: 15px; text-align: center; font-weight: 600; color: #495057;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                                <tr style="border-bottom: 1px solid #dee2e6; transition: background 0.2s ease;">
                                    @if(Auth::user()->role === 'Administrator')
                                        <td style="padding: 15px; color: #2c3e50;">
                                            <div style="font-weight: 600; margin-bottom: 5px;">{{ $application->user->full_name ?? $application->user->name }}</div>
                                            <div style="font-size: 12px; color: #6c757d;">{{ $application->user->email }}</div>
                                        </td>
                                    @endif
                                    <td style="padding: 15px; color: #2c3e50;">
                                        <div style="font-weight: 600; margin-bottom: 5px;">{{ $application->event_title }}</div>
                                        <div style="font-size: 12px; color: #6c757d;">{{ $application->venue }}, {{ $application->host_country }}</div>
                                    </td>
                                    <td style="padding: 15px; color: #2c3e50;">{{ $application->event_type }}</td>
                                    <td style="padding: 15px; color: #2c3e50;">{{ $application->event_date->format('M d, Y') }}</td>
                                    <td class="status-column" style="padding: 15px; white-space: nowrap !important; width: 200px; min-width: 200px; vertical-align: middle; overflow: visible;">
                                        @if($application->status === 'draft')
                                            <span class="status-badge" style="background: #6c757d; color: white; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Draft</span>
                                        @elseif($application->status === 'submitted')
                                            <span class="status-badge" style="background: #007bff; color: white; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Submitted</span>
                                        @elseif($application->status === 'under_review')
                                            <span class="status-badge" style="background: #ffc107; color: #212529; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Under Review</span>
                                        @elseif($application->status === 'approved')
                                            <span class="status-badge" style="background: #28a745; color: white; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Approved</span>
                                        @elseif($application->status === 'rejected')
                                            <span class="status-badge" style="background: #dc3545; color: white; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Rejected</span>
                                        @elseif($application->status === 'rework')
                                            <span class="status-badge" style="background: #fd7e14; color: white; padding: 8px 30px; border-radius: 20px; font-size: 13px; font-weight: 600; white-space: nowrap !important; display: inline-block !important; text-align: center; letter-spacing: 0.3px; box-sizing: border-box; word-break: keep-all !important; overflow: visible !important;">Rework Required</span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; color: #6c757d; font-size: 14px;">{{ $application->created_at->format('M d, Y') }}</td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('cpd.show', $application) }}" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#138496'" onmouseout="this.style.background='#17a2b8'">View</a>
                                            
                                            @if($application->status === 'approved')
                                                <a href="{{ route('cpd.download-pdf', $application) }}" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">📄 PDF</a>
                                            @endif
                                            
                                            @if($application->status === 'rejected')
                                                <a href="{{ route('cpd.download-pdf', $application) }}" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'">📄 PDF</a>
                                            @endif
                                            
                                            @if($application->status === 'draft')
                                                <a href="{{ route('cpd.edit', $application) }}" style="background: #ffc107; color: #212529; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#e0a800'" onmouseout="this.style.background='#ffc107'">Edit</a>
                                                
                                                <form action="{{ route('cpd.submit', $application) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'" onclick="return confirm('Are you sure you want to submit this application?')">Submit</button>
                                                </form>
                                            @endif
                                            
                                            <!-- Delete button for all applications -->
                                            <form action="{{ route('cpd.destroy', $application) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'" onclick="return confirmDelete({{ json_encode($application->event_title) }})">Delete</button>
                                            </form>
                                            
                                            @if($application->status === 'rework')
                                                <a href="{{ route('cpd.edit', $application) }}" style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#e86800'" onmouseout="this.style.background='#fd7e14'">Edit (Rework)</a>
                                            @endif
                                            
                                            <!-- Users can only view their own recommendations -->
                                            @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                                                <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#5a32a3'" onmouseout="this.style.background='#6f42c1'">View Rec</a>
                                            @endif
                                            
                                            <!-- Only Head Of Section can create recommendations -->
                                            @if(Auth::user()->role === 'Head Of Section' && !$application->recommendation)
                                                <a href="{{ route('cpd.recommendations.create', $application) }}" style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#e86800'" onmouseout="this.style.background='#fd7e14'">Create Rec</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($applications->hasPages())
                    <div style="padding: 20px; display: flex; justify-content: center; border-top: 1px solid #dee2e6;">
                        {{ $applications->links() }}
                    </div>
                @endif
            </div>
        @else
            <div style="background: white; padding: 60px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 20px;">📚</div>
                @if(Auth::user()->role === 'Administrator')
                    <h3 style="color: #2c3e50; margin-bottom: 15px;">No CPD Applications Found</h3>
                    <p style="color: #6c757d; margin-bottom: 30px;">There are no CPD applications submitted by staff members yet.</p>
                @else
                    <h3 style="color: #2c3e50; margin-bottom: 15px;">No CPD Applications Yet</h3>
                    <p style="color: #6c757d; margin-bottom: 30px;">Start your professional development journey by creating your first CPD application.</p>
                    <a href="{{ route('cpd.create') }}" style="background: #3498db; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; cursor: pointer; transition: background 0.2s ease; display: inline-block;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                        Create First Application
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Learning & Development Tab -->
    <div id="learning-content" class="tab-content" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="color: #2c3e50; margin: 0;">Learning & Development Programs</h2>
            @if(Auth::user()->role === 'Administrator')
                <button type="button" onclick="showComingSoon('Manage Programs')" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                    Manage Programs
                </button>
            @else
                <button type="button" onclick="showComingSoon('Enroll in Program')" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                    Enroll in Program
                </button>
            @endif
        </div>

        <!-- Learning & Development Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            <!-- Training Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🎓</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Training Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Professional training and skill development programs</p>
                <button type="button" onclick="showComingSoon('Training Programs')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">View Programs</button>
            </div>

            <!-- Workshops -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🔧</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Workshops</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Interactive workshops and hands-on learning sessions</p>
                <button type="button" onclick="showComingSoon('Workshops')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">View Workshops</button>
            </div>

            <!-- Online Courses -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">💻</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Online Courses</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Self-paced online learning modules and courses</p>
                <button type="button" onclick="showComingSoon('Online Courses')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">View Courses</button>
            </div>

            <!-- Leadership Development -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">👑</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Leadership Development</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Leadership skills and management training programs</p>
                <button type="button" onclick="showComingSoon('Leadership Development Programs')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">View Programs</button>
            </div>

            <!-- Certification Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🏆</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Certification Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">Professional certification and accreditation programs</p>
                <button type="button" onclick="showComingSoon('Certification Programs')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">View Certifications</button>
            </div>

            <!-- Mentorship Programs -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <div style="font-size: 48px; margin-bottom: 15px;">🤝</div>
                <h3 style="color: #2c3e50; margin-bottom: 10px;">Mentorship Programs</h3>
                <p style="color: #6c757d; margin-bottom: 20px;">One-on-one mentoring and career guidance programs</p>
                <button type="button" onclick="showComingSoon('Mentorship Programs')" style="background: #3498db; color: white; padding: 8px 16px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">Find Mentor</button>
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

/* Prevent status badges from wrapping */
.status-column {
    white-space: nowrap !important;
    overflow: visible !important;
    width: 200px !important;
    min-width: 200px !important;
    max-width: none !important;
}

/* Ensure status badge text never wraps */
.status-badge {
    white-space: nowrap !important;
    word-break: keep-all !important;
    overflow: visible !important;
    display: inline-block !important;
    max-width: none !important;
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

/* Make pagination arrows smaller */
.pagination {
    font-size: 14px;
}

.pagination .page-link,
.pagination a,
.pagination span {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    min-height: auto !important;
}

.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link,
.pagination > :first-child,
.pagination > :last-child {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.pagination svg {
    width: 14px !important;
    height: 14px !important;
}

.pagination .relative {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
}

/* Target Laravel Tailwind pagination */
.pagination nav a,
.pagination nav span {
    padding: 0.25rem 0.5rem !important;
    font-size: 0.875rem !important;
}

.pagination nav svg {
    width: 14px !important;
    height: 14px !important;
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
    try {
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
        const selectedContent = document.getElementById(tabName + '-content');
        const selectedButton = document.getElementById(tabName + '-tab');
        
        if (selectedContent && selectedButton) {
            selectedContent.style.display = 'block';
            selectedButton.classList.add('active');
            selectedButton.style.background = '#3498db';
        }
    } catch (error) {
        console.error('Error switching tabs:', error);
    }
}

function confirmDelete(eventTitle) {
    const message = `Are you sure you want to delete the CPD application "${eventTitle}"?\n\nThis action cannot be undone and all associated data will be permanently removed.`;
    return confirm(message);
}

function showComingSoon(featureName) {
    alert(`🚧 ${featureName} is coming soon!\n\nThis feature is currently under development and will be available in a future update.`);
}
</script>
@endsection 