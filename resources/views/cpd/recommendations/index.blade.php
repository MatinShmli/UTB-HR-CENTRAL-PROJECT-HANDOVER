@extends('layouts.app')

@section('page-title', 'CPD Recommendations - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">CPD Recommendations</h2>
        <a href="{{ route('cpd.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
            Back to CPD Applications
        </a>
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

    <!-- Search and Filter Section -->
    <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('cpd.recommendations.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">
            <!-- Search Input -->
            <div style="flex: 2; min-width: 250px;">
                <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Search Recommendations</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by applicant, event title, programme area..." style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
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
                <a href="{{ route('cpd.recommendations.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                    🔄 Clear
                </a>
            </div>
        </form>

        <!-- Search Results Summary -->
        @if(request()->hasAny(['search', 'event_type', 'date_from', 'date_to']) && (request('search') || request('event_type') !== 'all' || request('date_from') || request('date_to')))
            <div style="margin-top: 15px; padding: 10px 15px; background: #e7f3ff; border-left: 4px solid #3498db; border-radius: 4px;">
                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">Search Results</div>
                <div style="font-size: 14px; color: #495057;">
                    Found {{ $recommendations->total() }} recommendation(s) 
                    @if(request('search'))
                        matching "{{ request('search') }}"
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

    @if($recommendations->count() > 0)
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa;">
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Applicant</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Event</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Event Date</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Role</th>
                        <th style="padding: 15px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Created</th>
                        <th style="padding: 15px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: 600; color: #495057;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recommendations as $recommendation)
                        @php
                            $isCreator = $recommendation->recommended_by_user_id === Auth::id();
                            $isApplicant = $recommendation->cpdApplication->user_id === Auth::id();
                        @endphp
                        <tr style="border-bottom: 1px solid #dee2e6;">
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2c3e50;">{{ $recommendation->applicant_name }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ $recommendation->applicant_post }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="font-weight: 600; color: #2c3e50;">{{ $recommendation->event_title }}</div>
                                <div style="font-size: 12px; color: #6c757d;">{{ $recommendation->event_type }}</div>
                            </td>
                            <td style="padding: 15px; color: #495057;">
                                {{ $recommendation->event_date->format('M d, Y') }}
                            </td>
                            <td style="padding: 15px;">
                                @if($isCreator)
                                    <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        CREATOR
                                    </span>
                                    <div style="font-size: 11px; color: #6c757d; margin-top: 2px;">You created this</div>
                                @elseif($isApplicant)
                                    <span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                        APPLICANT
                                    </span>
                                    <div style="font-size: 11px; color: #6c757d; margin-top: 2px;">For your application</div>
                                @endif
                            </td>
                            <td style="padding: 15px; color: #6c757d; font-size: 14px;">
                                {{ $recommendation->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('cpd.recommendations.show', $recommendation) }}" 
                                       style="background: #17a2b8; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                        View
                                    </a>
                                        
                                    @if($isCreator && Auth::user()->role === 'Head Of Section')
                                        <a href="{{ route('cpd.recommendations.edit', $recommendation) }}" 
                                           style="background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 12px;">
                                            Edit
                                        </a>
                                            
                                        <form action="{{ route('cpd.recommendations.destroy', $recommendation) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    style="background: #dc3545; color: white; padding: 6px 12px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;"
                                                    onclick="return confirm('Are you sure you want to delete this recommendation?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination -->
            @if($recommendations->hasPages())
                <div style="padding: 20px; display: flex; justify-content: center; border-top: 1px solid #dee2e6;">
                    {{ $recommendations->links() }}
                </div>
            @endif
        </div>
    @else
        <div style="background: white; padding: 40px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
            <div style="font-size: 48px; color: #dee2e6; margin-bottom: 20px;">📋</div>
            <h3 style="color: #6c757d; margin-bottom: 10px;">No Recommendations Found</h3>
            <p style="color: #6c757d; margin-bottom: 20px;">No CPD recommendations have been created for your applications or by you yet.</p>
            <a href="{{ route('cpd.index') }}" style="background: #3498db; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                View CPD Applications
            </a>
        </div>
    @endif
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
    
    table {
        font-size: 14px;
    }
    
    th, td {
        padding: 10px 8px !important;
    }
}
</style>
@endsection 