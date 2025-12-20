@extends('layouts.app')

@section('page-title', 'Memo Requests - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Memo Requests</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('memo.create') }}" style="background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                📝 New Memo Request
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
    <div style="background: white; padding: 25px; border-radius: 12px; margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('memo.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: end;">
            <!-- Search Input -->
            <div style="flex: 2; min-width: 250px;">
                <label style="display: block; color: #495057; font-weight: 600; margin-bottom: 5px;">Search Requests</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or description..." style="width: 100%; padding: 10px 15px; border: 2px solid #e9ecef; border-radius: 6px; font-size: 14px; transition: border-color 0.2s ease;" onfocus="this.style.borderColor='#3498db'" onblur="this.style.borderColor='#e9ecef'">
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
                <a href="{{ route('memo.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
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
                                        <a href="{{ route('memo.show', $memo) }}" style="background: #17a2b8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">View</a>
                                        
                                        @if($memo->status === 'approved' && $memo->memo_file)
                                            <a href="{{ route('memo.download-memo', $memo) }}" style="background: #28a745; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">📄 Memo</a>
                                        @endif
                                        
                                        @if($memo->status === 'submitted')
                                            <a href="{{ route('memo.edit', $memo) }}" style="background: #ffc107; color: #212529; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Edit</a>
                                            
                                            <form action="{{ route('memo.destroy', $memo) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: #dc3545; color: white; padding: 6px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;" onclick="return confirmDelete('{{ $memo->title }}')">Delete</button>
                                            </form>
                                        @endif
                                        
                                        <!-- Download form files -->
                                        @if($memo->form_files && count($memo->form_files) > 0)
                                            @foreach($memo->form_files as $index => $file)
                                                <a href="{{ route('memo.download-form', ['memo' => $memo, 'fileIndex' => $index]) }}" style="background: #6c757d; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">Form {{ $index + 1 }}</a>
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
            <h3 style="color: #2c3e50; margin-bottom: 15px;">No Memo Requests Yet</h3>
            <p style="color: #6c757d; margin-bottom: 30px;">Start by creating your first memo request to submit forms for approval.</p>
            <a href="{{ route('memo.create') }}" style="background: #3498db; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: background 0.3s ease;">
                Create First Request
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

<script>
function confirmDelete(title) {
    const message = `Are you sure you want to delete the memo request "${title}"?\n\nThis action cannot be undone and all associated files will be permanently removed.`;
    return confirm(message);
}
</script>
@endsection
