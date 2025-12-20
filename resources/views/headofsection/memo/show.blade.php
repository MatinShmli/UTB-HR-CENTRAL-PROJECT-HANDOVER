@extends('layouts.app')

@section('page-title', 'Head of Section - Memo Request Review - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin: 0;">Head of Section - Memo Request Review</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('headofsection.memo.index') }}" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-weight: 600;">
                ← Back to Requests
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <h4 style="margin: 0 0 10px 0;">Please correct the following errors:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                    <strong>Department:</strong> {{ $memo->user->faculty_school_centre_section ?? 'Not specified' }}<br>
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
                            <a href="{{ route('headofsection.memo.download-form', ['memo' => $memo, 'fileIndex' => $index]) }}" 
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

        <!-- Review Actions -->
        @if($memo->status === 'submitted')
            <div style="margin-bottom: 30px;">
                <h4 style="color: #2c3e50; margin-bottom: 15px; font-size: 18px;">Review Actions</h4>
                
                <!-- Approve Form -->
                <div style="background: #d4edda; padding: 25px; border-radius: 8px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
                    <h5 style="color: #155724; margin-bottom: 15px;">✅ Approve Request</h5>
                    <form action="{{ route('headofsection.memo.approve', $memo) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label for="admin_notes" style="display: block; color: #155724; font-weight: 600; margin-bottom: 5px;">Review Notes (Optional)</label>
                            <textarea id="admin_notes" name="admin_notes" rows="3" 
                                      placeholder="Add any notes or comments about this approval..."
                                      style="width: 100%; padding: 10px 15px; border: 1px solid #c3e6cb; border-radius: 6px; font-size: 14px;">{{ old('admin_notes') }}</textarea>
                        </div>
                        <div style="margin-bottom: 15px;">
                            <label for="memo_file" style="display: block; color: #155724; font-weight: 600; margin-bottom: 5px;">Upload Memo File (Optional)</label>
                            <input type="file" id="memo_file" name="memo_file" accept=".pdf" 
                                   style="width: 100%; padding: 8px 12px; border: 1px solid #c3e6cb; border-radius: 6px; font-size: 14px;">
                            <small style="color: #155724; font-size: 12px;">Upload a generated memo PDF file (max 10MB)</small>
                        </div>
                        <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;"
                                onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
                            ✅ Approve Request
                        </button>
                    </form>
                </div>

                <!-- Reject Form -->
                <div style="background: #f8d7da; padding: 25px; border-radius: 8px; border: 1px solid #f5c6cb;">
                    <h5 style="color: #721c24; margin-bottom: 15px;">❌ Reject Request</h5>
                    <form action="{{ route('headofsection.memo.reject', $memo) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 15px;">
                            <label for="rejection_reason" style="display: block; color: #721c24; font-weight: 600; margin-bottom: 5px;">Rejection Reason <span style="color: #dc3545;">*</span></label>
                            <textarea id="rejection_reason" name="rejection_reason" rows="4" 
                                      placeholder="Please provide a detailed reason for rejection..."
                                      style="width: 100%; padding: 10px 15px; border: 1px solid #f5c6cb; border-radius: 6px; font-size: 14px;"
                                      required>{{ old('rejection_reason') }}</textarea>
                            <small style="color: #721c24; font-size: 12px;">Minimum 10 characters. This will be shown to the staff member.</small>
                        </div>
                        <button type="submit" style="background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: background 0.2s ease;"
                                onmouseover="this.style.background='#c82333'" onmouseout="this.style.background='#dc3545'"
                                onclick="return confirmReject()">
                            ❌ Reject Request
                        </button>
                    </form>
                </div>
            </div>
        @endif

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
                            <strong style="color: #2c3e50;">Review Notes:</strong>
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
                    <h5 style="color: #155724; margin-bottom: 10px;">Memo File Available</h5>
                    <p style="color: #155724; margin-bottom: 20px;">The approved memo file is ready for download.</p>
                    <a href="{{ route('headofsection.memo.download-memo', $memo) }}" 
                       style="background: #28a745; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-block;">
                        📥 Download Memo
                    </a>
                </div>
            </div>
        @endif
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
function confirmReject() {
    const message = 'Are you sure you want to reject this memo request?\n\nThis action will notify the staff member and cannot be easily undone.';
    return confirm(message);
}
</script>
@endsection
