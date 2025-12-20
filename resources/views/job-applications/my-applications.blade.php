@extends('layouts.app')

@section('title', 'My Applications - UTB HR Central')
@section('page-title', 'My Job Applications')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <h2 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">My Job Applications</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="applications-list">
        @forelse($applications as $application)
            <div class="application-card">
                <div class="application-header">
                    <div>
                        <h4>{{ $application->jobPosting->title }}</h4>
                        <p class="text-muted">{{ $application->jobPosting->department }}</p>
                    </div>
                    <span class="application-status status-{{ $application->status }}">
                        @if($application->status === 'pending')
                            Pending Review
                        @elseif($application->status === 'under_review')
                            Under Review
                        @elseif($application->status === 'interview_scheduled')
                            ✓ Interview Scheduled
                        @elseif($application->status === 'accepted')
                            ✓ Accepted
                        @elseif($application->status === 'rejected')
                            Rejected
                        @endif
                    </span>
                </div>
                
                <div class="application-details">
                    <div class="detail-row">
                        <span><strong>Applied On:</strong> {{ $application->applied_at->format('F d, Y') }}</span>
                        <span><strong>Job Type:</strong> {{ $application->jobPosting->employment_type }}</span>
                    </div>
                    
                    @if($application->reviewed_at)
                        <div class="detail-row">
                            <span><strong>Reviewed On:</strong> {{ $application->reviewed_at->format('F d, Y') }}</span>
                        </div>
                    @endif
                    
                    @if($application->status === 'interview_scheduled')
                        <div class="interview-notice">
                            <strong>🎉 Congratulations!</strong>
                            <p>Your application has been accepted! You will be contacted soon to schedule an interview.</p>
                            @if($application->admin_notes)
                                <p class="admin-note">{{ $application->admin_notes }}</p>
                            @endif
                        </div>
                    @endif
                    
                    @if($application->status === 'rejected' && $application->admin_notes)
                        <div class="rejection-notice">
                            <strong>Feedback:</strong>
                            <p>{{ $application->admin_notes }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="application-actions">
                    <a href="{{ route('job-applications.download-cv', $application->id) }}" class="btn-secondary">Download My CV</a>
                    @if($application->cover_letter)
                        <button class="btn-secondary" onclick="viewCoverLetter({{ $application->id }}, `{{ addslashes($application->cover_letter) }}`)">View Cover Letter</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-applications">
                <div class="empty-state">
                    <div style="font-size: 64px; margin-bottom: 20px;">📄</div>
                    <h3>No Applications Yet</h3>
                    <p>You haven't applied for any positions yet.</p>
                    <a href="{{ route('recruitment') }}" class="btn-primary" style="margin-top: 20px;">Browse Available Jobs</a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Cover Letter Modal -->
<div id="coverLetterModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Cover Letter</h3>
            <span class="close" onclick="closeCoverLetterModal()">&times;</span>
        </div>
        <div class="modal-body" id="coverLetterBody">
            <!-- Cover letter content will be displayed here -->
        </div>
    </div>
</div>

<style>
.applications-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.application-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

.application-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.application-header h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 20px;
}

.text-muted {
    color: #6c757d;
    margin: 0;
    font-size: 14px;
}

.application-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-under_review {
    background: #cce5ff;
    color: #004085;
}

.status-interview_scheduled,
.status-accepted {
    background: #d4edda;
    color: #155724;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.application-details {
    margin-bottom: 20px;
}

.detail-row {
    display: flex;
    gap: 30px;
    margin-bottom: 10px;
    font-size: 14px;
}

.interview-notice {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border-left: 4px solid #28a745;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.interview-notice strong {
    color: #155724;
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
}

.interview-notice p {
    margin: 5px 0;
    color: #155724;
}

.admin-note {
    font-style: italic;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #c3e6cb;
}

.rejection-notice {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.rejection-notice strong {
    color: #721c24;
    display: block;
    margin-bottom: 8px;
}

.rejection-notice p {
    margin: 5px 0;
    color: #721c24;
}

.application-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-primary {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
}

.no-applications {
    text-align: center;
    padding: 60px 20px;
}

.empty-state h3 {
    color: #2c3e50;
    margin: 0 0 10px 0;
    font-size: 24px;
}

.empty-state p {
    color: #6c757d;
    margin: 0;
    font-size: 16px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 6px;
    font-size: 14px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Modal Styles */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 0;
    border-radius: 8px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #2c3e50;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

@media (max-width: 768px) {
    .application-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .application-actions {
        flex-direction: column;
    }
    
    .btn-secondary,
    .btn-primary {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
function viewCoverLetter(applicationId, coverLetter) {
    document.getElementById('coverLetterBody').innerHTML = `<p style="white-space: pre-wrap;">${coverLetter}</p>`;
    document.getElementById('coverLetterModal').style.display = 'block';
}

function closeCoverLetterModal() {
    document.getElementById('coverLetterModal').style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('coverLetterModal');
    if (e.target === modal) {
        closeCoverLetterModal();
    }
});
</script>
@endsection

