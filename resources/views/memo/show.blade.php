@extends('layouts.app')

@section('page-title', 'Memo Request Details - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Memo Request Details</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('memo.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                ← Back to Requests
            </a>
            @if($memo->status === 'submitted' && $memo->user_id === Auth::id())
                <a href="{{ route('memo.edit', $memo) }}" style="background: #ffc107; color: #212529; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                    ✏️ Edit Request
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Request Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #e9ecef;">
            <div style="flex: 1;">
                <h3 style="color: #2c3e50; margin: 0 0 10px 0; font-size: 24px;">{{ $memo->title }}</h3>
                <div style="color: #6c757d; font-size: 14px; margin-bottom: 15px;">
                    <strong>Requested by:</strong> {{ $memo->user->full_name ?? $memo->user->name }}<br>
                    <strong>Email:</strong> {{ $memo->user->email }}<br>
                    <strong>Submitted:</strong> {{ $memo->created_at->format('F d, Y \a\t g:i A') }}
                </div>
            </div>
            <div style="text-align: right;">
                @if($memo->status === 'submitted')
                    <span style="background: #007bff; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600;">Submitted</span>
                @elseif($memo->status === 'under_review')
                    <span style="background: #ffc107; color: #212529; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600;">Under Review</span>
                @elseif($memo->status === 'approved')
                    <span style="background: #28a745; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600;">✅ Approved</span>
                @elseif($memo->status === 'rejected')
                    <span style="background: #dc3545; color: white; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 600;">❌ Rejected</span>
                @endif
            </div>
        </div>

        <!-- Description -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">Description</h4>
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #3498db;">
                <p style="margin: 0; color: #495057; line-height: 1.6; white-space: pre-wrap;">{{ $memo->description }}</p>
            </div>
        </div>

        <!-- Uploaded Forms -->
        <div style="margin-bottom: 30px;">
            <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">Uploaded Forms</h4>
            @if($memo->form_files && count($memo->form_files) > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                    @foreach($memo->form_files as $index => $file)
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6; text-align: center;">
                            <div style="font-size: 32px; margin-bottom: 10px; color: #dc3545;">📄</div>
                            <div style="font-weight: 600; color: #2c3e50; margin-bottom: 5px; word-break: break-word;">
                                Form {{ $index + 1 }}
                            </div>
                            <div style="font-size: 12px; color: #6c757d; margin-bottom: 10px;">
                                {{ basename($file) }}
                            </div>
                            <a href="{{ route('memo.download-form', ['memo' => $memo, 'fileIndex' => $index]) }}" 
                               style="background: #007bff; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-block;">
                                📥 Download
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; color: #6c757d;">
                    No forms uploaded
                </div>
            @endif
        </div>

        <!-- Review Information -->
        @if($memo->status !== 'submitted')
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">Review Information</h4>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid {{ $memo->status === 'approved' ? '#28a745' : '#dc3545' }};">
                    <div style="margin-bottom: 15px;">
                        <strong style="color: #2c3e50;">Reviewed by:</strong> {{ $memo->reviewer->full_name ?? $memo->reviewer->name }}<br>
                        <strong style="color: #2c3e50;">Review Date:</strong> {{ $memo->reviewed_at->format('F d, Y \a\t g:i A') }}
                    </div>
                    
                    @if($memo->admin_notes)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #2c3e50;">Admin Notes:</strong>
                            <div style="background: white; padding: 15px; border-radius: 6px; margin-top: 8px; border: 1px solid #dee2e6;">
                                <p style="margin: 0; color: #495057; line-height: 1.6; white-space: pre-wrap;">{{ $memo->admin_notes }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($memo->rejection_reason)
                        <div style="margin-bottom: 15px;">
                            <strong style="color: #dc3545;">Rejection Reason:</strong>
                            <div style="background: #f8d7da; padding: 15px; border-radius: 6px; margin-top: 8px; border: 1px solid #f5c6cb;">
                                <p style="margin: 0; color: #721c24; line-height: 1.6; white-space: pre-wrap;">{{ $memo->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Generated Memo -->
        @if($memo->status === 'approved' && $memo->memo_file)
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">Generated Memo</h4>
                <div style="background: #d4edda; padding: 20px; border-radius: 8px; border: 1px solid #c3e6cb; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px; color: #155724;">📄</div>
                    <h5 style="color: #155724; margin-bottom: 10px;">Your memo is ready!</h5>
                    <p style="color: #155724; margin-bottom: 20px;">The approved memo file is available for download.</p>
                    <a href="{{ route('memo.download-memo', $memo) }}" 
                       style="background: #28a745; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">
                        📥 Download Memo
                    </a>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
            @if($memo->status === 'submitted' && $memo->user_id === Auth::id())
                <form action="{{ route('memo.destroy', $memo) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;"
                            onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'"
                            onclick="return confirmDelete('{{ $memo->title }}')">
                        🗑️ Delete Request
                    </button>
                </form>
            @endif
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

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    .content-card > div {
        padding: 20px !important;
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
