@extends('layouts.app')

@section('page-title', 'Head of Section - Memo Requests - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Head of Section - Memo Requests Review</h2>
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
        <form method="GET" action="{{ route('headofsection.memo.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">
            <!-- Search Input -->
            <div style="flex: 2; min-width: 250px;">
                <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Search Requests</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, description, or staff member..." style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
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
                </select>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; gap: 10px;">
                <button type="submit" style="background: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;" onmouseover="this.style.background='#2980b9'" onmouseout="this.style.background='#3498db'">
                    🔍 Search
                </button>
                <a href="{{ route('headofsection.memo.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                    🔄 Clear
                </a>
            </div>
        </form>

        <!-- Search Results Summary -->
        @if(request()->hasAny(['search', 'status']) && (request('search') || request('status') !== 'all'))
            <div style="margin-top: 15px; padding: 10px 15px; background: #e7f3ff; border-left: 4px solid #3498db; border-radius: 4px;">
                <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px;">Search Results</div>
                <div style="font-size: 14px; color: #495057;">
                    Found {{ $memoRequests->total() }} request(s) 
                    @if(request('search'))
                        matching "{{ request('search') }}"
                    @endif
                    @if(request('status') && request('status') !== 'all')
                        with status "{{ ucfirst(str_replace('_', ' ', request('status'))) }}"
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if($memoRequests->count() > 0)
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Staff Member</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Title</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Description</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Status</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #495057;">Created</th>
                            <th style="padding: 15px; text-align: center; font-weight: 600; color: #495057;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($memoRequests as $memo)
                            <tr style="border-bottom: 1px solid #dee2e6; transition: background 0.2s ease;">
                                <td style="padding: 15px; color: #2c3e50;">
                                    <div style="font-weight: 600; margin-bottom: 5px;">{{ $memo->user->full_name ?? $memo->user->name }}</div>
                                    <div style="font-size: 12px; color: #6c757d;">{{ $memo->user->email }}</div>
                                </td>
                                <td style="padding: 15px; color: #2c3e50;">
                                    <div style="font-weight: 600; margin-bottom: 5px;">{{ $memo->title }}</div>
                                </td>
                                <td style="padding: 15px; color: #2c3e50;">
                                    <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ Str::limit($memo->description, 100) }}
                                    </div>
                                </td>
                                <td style="padding: 15px;">
                                    @if($memo->status === 'submitted')
                                        <span style="background: #007bff; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Submitted</span>
                                    @elseif($memo->status === 'under_review')
                                        <span style="background: #ffc107; color: #212529; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Under Review</span>
                                    @elseif($memo->status === 'approved')
                                        <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Approved</span>
                                    @elseif($memo->status === 'rejected')
                                        <span style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Rejected</span>
                                    @endif
                                </td>
                                <td style="padding: 15px; color: #6c757d; font-size: 14px;">{{ $memo->created_at->format('M d, Y') }}</td>
                                <td style="padding: 15px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                        <a href="{{ route('headofsection.memo.show', $memo) }}" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View</a>
                                        
                                        @if($memo->status === 'approved' && $memo->memo_file)
                                            <a href="{{ route('headofsection.memo.download-memo', $memo) }}" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">📄 Memo</a>
                                        @endif
                                        
                                        @if($memo->status === 'submitted')
                                            <a href="{{ route('headofsection.memo.show', $memo) }}" style="background: #ffc107; color: #212529; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Review</a>
                                        @endif
                                        
                                        <!-- Download form files -->
                                        @if($memo->form_files && count($memo->form_files) > 0)
                                            @foreach($memo->form_files as $index => $file)
                                                <a href="{{ route('headofsection.memo.download-form', ['memo' => $memo, 'fileIndex' => $index]) }}" style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Form {{ $index + 1 }}</a>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($memoRequests->hasPages())
                <div style="padding: 20px; display: flex; justify-content: center; border-top: 1px solid #dee2e6;">
                    {{ $memoRequests->links() }}
                </div>
            @endif
        </div>
    @else
        <div style="background: white; padding: 60px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div style="font-size: 48px; margin-bottom: 20px;">📝</div>
            <h3 style="color: #2c3e50; margin-bottom: 15px;">No Memo Requests Found</h3>
            <p style="color: #6c757d; margin-bottom: 30px;">There are no memo requests submitted by staff members yet.</p>
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
