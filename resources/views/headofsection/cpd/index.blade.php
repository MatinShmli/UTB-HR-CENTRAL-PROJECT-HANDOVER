@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="color: #2c3e50; margin: 0; font-size: 28px; font-weight: 700;">Head Of Section - CPD Applications</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0; font-size: 16px;">View and manage all CPD applications</p>
        </div>
        <div style="display: flex; gap: 15px;">
            <a href="{{ route('dashboard') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                ← Back to Dashboard
            </a>
                         <a href="{{ route('cpd.recommendations.index') }}" style="background: #6f42c1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                 📋 My Recommendations
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

    <!-- Search and Filter Section -->
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <form method="GET" action="{{ route('headofsection.cpd.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search applications..." style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Status</label>
                <select name="status" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="all">All Statuses</option>
                    <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="rework" {{ request('status') === 'rework' ? 'selected' : '' }}>Rework Required</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Event Type</label>
                <select name="event_type" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    <option value="all">All Types</option>
                    <option value="Training Course" {{ request('event_type') === 'Training Course' ? 'selected' : '' }}>Training Course</option>
                    <option value="Meeting" {{ request('event_type') === 'Meeting' ? 'selected' : '' }}>Meeting</option>
                    <option value="Conference" {{ request('event_type') === 'Conference' ? 'selected' : '' }}>Conference</option>
                    <option value="Seminar" {{ request('event_type') === 'Seminar' ? 'selected' : '' }}>Seminar</option>
                    <option value="Workshop" {{ request('event_type') === 'Workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="Working Visit" {{ request('event_type') === 'Working Visit' ? 'selected' : '' }}>Working Visit</option>
                    <option value="Work Placement" {{ request('event_type') === 'Work Placement' ? 'selected' : '' }}>Work Placement</option>
                    <option value="Industrial Placement" {{ request('event_type') === 'Industrial Placement' ? 'selected' : '' }}>Industrial Placement</option>
                </select>
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Search</button>
                <a href="{{ route('headofsection.cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">Clear</a>
            </div>
        </form>
    </div>

    <!-- Applications Table -->
    <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #dee2e6;">
            <h3 style="margin: 0; color: #2c3e50; font-size: 18px; font-weight: 600;">
                All CPD Applications ({{ $applications->total() }})
            </h3>

        </div>
        
        @if($applications->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Applicant</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Event Title</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Event Type</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Event Date</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Status</th>
                            <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Submitted</th>
                            <th style="padding: 15px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 15px; color: #2c3e50;">
                                    <div>
                                        <div style="font-weight: 600;">{{ $application->user->name }}</div>
                                        <div style="font-size: 12px; color: #6c757d;">{{ $application->user->email }}</div>
                                    </div>
                                </td>
                                <td style="padding: 15px; color: #2c3e50;">{{ $application->event_title }}</td>
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
                                    @else
                                        <span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">{{ ucfirst($application->status) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 15px; color: #6c757d; font-size: 14px;">{{ $application->submitted_at ? $application->submitted_at->format('M d, Y H:i') : $application->created_at->format('M d, Y H:i') }}</td>
                                <td style="padding: 15px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                        <a href="{{ route('headofsection.cpd.show', $application) }}" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View</a>
                                        
                                        @if($application->status === 'submitted')
                                            <a href="{{ route('headofsection.cpd.download-pdf', $application) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">📄 PDF</a>
                                            
                                            <form action="{{ route('headofsection.cpd.approve', $application) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to approve this application and forward it to administration?')">Approve</button>
                                            </form>
                                            
                                            <form action="{{ route('headofsection.cpd.reject', $application) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                                            </form>
                                            
                                            <form action="{{ route('headofsection.cpd.rework', $application) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirm('Are you sure you want to mark this application for rework?')">Rework</button>
                                            </form>
                                        @elseif($application->status === 'approved' || $application->status === 'rejected')
                                            <a href="{{ route('headofsection.cpd.download-pdf', $application) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">📄 PDF</a>
                                        @endif
                                        
                                                                                 <!-- Recommendation buttons - only show own recommendations -->
                                         @if($application->recommendation && $application->recommendation->recommended_by_user_id === Auth::id())
                                             <a href="{{ route('cpd.recommendations.show', $application->recommendation) }}" style="background: #6f42c1; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View Rec</a>
                                         @elseif(!$application->recommendation)
                                             <a href="{{ route('cpd.recommendations.create', $application) }}" style="background: #fd7e14; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Create Rec</a>
                                         @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div style="padding: 20px; display: flex; justify-content: center;">
                {{ $applications->links() }}
            </div>
        @else
            <div style="padding: 40px; text-align: center;">
                <div style="color: #6c757d; font-size: 18px; margin-bottom: 10px;">No CPD applications found</div>
                <div style="color: #6c757d; font-size: 14px;">All CPD applications will appear here for your review.</div>
            </div>
        @endif
    </div>
</div>
@endsection
