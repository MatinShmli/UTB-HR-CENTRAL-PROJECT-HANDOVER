@extends('layouts.app')

@section('title', 'Job Applications - UTB HR Central')
@section('page-title', 'Manage Job Applications')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div class="header-section">
        <div>
            <h2 style="margin: 0 0 10px 0; color: #2c3e50;">Applications for: {{ $job->title }}</h2>
            <p style="margin: 0; color: #6c757d;">{{ $job->department }} • {{ $job->employment_type }}</p>
        </div>
        <div class="job-info-badge">
            <span class="badge-label">Total Applications:</span>
            <span class="badge-value">{{ $applications->count() }}</span>
        </div>
    </div>

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

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <button class="tab-btn active" onclick="filterApplications('all')">All ({{ $applications->count() }})</button>
        <button class="tab-btn" onclick="filterApplications('pending')">Pending ({{ $applications->where('status', 'pending')->count() }})</button>
        <button class="tab-btn" onclick="filterApplications('under_review')">Under Review ({{ $applications->where('status', 'under_review')->count() }})</button>
        <button class="tab-btn" onclick="filterApplications('interview_scheduled')">Interview ({{ $applications->where('status', 'interview_scheduled')->count() }})</button>
        <button class="tab-btn" onclick="filterApplications('rejected')">Rejected ({{ $applications->where('status', 'rejected')->count() }})</button>
    </div>

    <div class="applications-list">
        @forelse($applications as $application)
            <div class="application-card" data-status="{{ $application->status }}">
                <div class="application-header">
                    <div class="applicant-info">
                        <div class="applicant-avatar">
                            {{ strtoupper(substr($application->user->first_name, 0, 1)) }}{{ strtoupper(substr($application->user->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h4>{{ $application->user->first_name }} {{ $application->user->last_name }}</h4>
                            <p class="text-muted">{{ $application->user->email }}</p>
                        </div>
                    </div>
                    <span class="application-status status-{{ $application->status }}">
                        @if($application->status === 'pending')
                            Pending
                        @elseif($application->status === 'under_review')
                            Under Review
                        @elseif($application->status === 'interview_scheduled')
                            Interview Scheduled
                        @elseif($application->status === 'accepted')
                            Accepted
                        @elseif($application->status === 'rejected')
                            Rejected
                        @endif
                    </span>
                </div>
                
                <div class="application-details">
                    <div class="detail-row">
                        <span><strong>Applied:</strong> {{ $application->applied_at->format('M d, Y @ g:i A') }}</span>
                        @if($application->reviewed_at)
                            <span><strong>Reviewed:</strong> {{ $application->reviewed_at->format('M d, Y @ g:i A') }}</span>
                        @endif
                    </div>
                    
                    <div class="detail-row">
                        <span><strong>Post:</strong> {{ $application->user->post ?? 'N/A' }}</span>
                        <span><strong>Department:</strong> {{ $application->user->faculty_school_centre_section ?? 'N/A' }}</span>
                    </div>
                    
                    @if($application->cover_letter)
                        <div class="cover-letter-preview">
                            <strong>Cover Letter:</strong>
                            <p>{{ Str::limit($application->cover_letter, 150) }}</p>
                            <button class="btn-link" onclick="viewFullCoverLetter({{ $application->id }}, `{{ addslashes($application->cover_letter) }}`)">Read Full Letter</button>
                        </div>
                    @endif
                    
                    @if($application->admin_notes)
                        <div class="admin-notes-display">
                            <strong>Admin Notes:</strong>
                            <p>{{ $application->admin_notes }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="application-actions">
                    <a href="{{ route('job-applications.download-cv', $application->id) }}" class="btn-secondary" target="_blank">
                        <span>📄</span> Download CV
                    </a>
                    
                    @if(auth()->user()->role === 'Administrator')
                        <button class="btn-secondary" onclick="editApplication({{ $application->id }})">Edit</button>
                    @endif
                    
                    @if($application->status === 'pending')
                        <button class="btn-info" onclick="markUnderReview({{ $application->id }})">Mark Under Review</button>
                    @endif
                    
                    @if($application->status !== 'interview_scheduled' && $application->status !== 'rejected')
                        <button class="btn-success" onclick="showAcceptModal({{ $application->id }})">Accept</button>
                    @endif
                    
                    @if($application->status !== 'rejected')
                        <button class="btn-danger" onclick="showRejectModal({{ $application->id }})">Reject</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-applications">
                <div class="empty-state">
                    <div style="font-size: 64px; margin-bottom: 20px;">📭</div>
                    <h3>No Applications Yet</h3>
                    <p>There are no applications for this position yet.</p>
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
            <!-- Cover letter content -->
        </div>
    </div>
</div>

<!-- Accept Application Modal -->
<div id="acceptModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Accept Application</h3>
            <span class="close" onclick="closeAcceptModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>The applicant will be notified that they have been accepted and that an interview will be scheduled.</p>
            <form id="acceptForm">
                <input type="hidden" id="acceptApplicationId">
                <div class="form-group">
                    <label for="acceptNotes">Additional Notes (Optional)</label>
                    <textarea id="acceptNotes" name="admin_notes" rows="4" placeholder="Add any additional information for the applicant..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeAcceptModal()">Cancel</button>
                    <button type="submit" class="btn-success">Confirm Accept</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Application Modal -->
<div id="rejectModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Application</h3>
            <span class="close" onclick="closeRejectModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p>The applicant will be notified that their application was not successful.</p>
            <form id="rejectForm">
                <input type="hidden" id="rejectApplicationId">
                <div class="form-group">
                    <label for="rejectNotes">Reason for Rejection (Optional)</label>
                    <textarea id="rejectNotes" name="admin_notes" rows="4" placeholder="Provide feedback for the applicant..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeRejectModal()">Cancel</button>
                    <button type="submit" class="btn-danger">Confirm Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Application Modal (Admin Only) -->
<div id="editApplicationModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Job Application</h3>
            <span class="close" onclick="closeEditApplicationModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="editApplicationForm">
                <input type="hidden" id="editApplicationId">
                <div class="form-group">
                    <label for="editStatus">Application Status *</label>
                    <select id="editStatus" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="under_review">Under Review</option>
                        <option value="interview_scheduled">Interview Scheduled</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editAdminNotes">Admin Notes</label>
                    <textarea id="editAdminNotes" name="admin_notes" rows="4" placeholder="Add admin notes or feedback..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeEditApplicationModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Update Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #e9ecef;
}

.job-info-badge {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    text-align: center;
}

.badge-label {
    display: block;
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.badge-value {
    display: block;
    font-size: 32px;
    font-weight: bold;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.tab-btn {
    padding: 10px 20px;
    border: 2px solid #e9ecef;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.3s ease;
}

.tab-btn:hover {
    border-color: #3498db;
    color: #3498db;
}

.tab-btn.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

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
    transition: all 0.3s ease;
}

.application-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.application-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.applicant-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.applicant-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
}

.applicant-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 18px;
}

.text-muted {
    color: #6c757d;
    margin: 0;
    font-size: 13px;
}

.application-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
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
    margin-bottom: 12px;
    font-size: 14px;
    color: #495057;
}

.cover-letter-preview {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.cover-letter-preview strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 8px;
}

.cover-letter-preview p {
    margin: 0 0 10px 0;
    color: #495057;
    font-size: 14px;
    line-height: 1.6;
}

.btn-link {
    background: none;
    border: none;
    color: #3498db;
    cursor: pointer;
    font-size: 13px;
    text-decoration: underline;
    padding: 0;
}

.btn-link:hover {
    color: #2980b9;
}

.admin-notes-display {
    background: #e7f3ff;
    border-left: 4px solid #3498db;
    padding: 15px;
    border-radius: 6px;
    margin-top: 15px;
}

.admin-notes-display strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 8px;
}

.admin-notes-display p {
    margin: 0;
    color: #495057;
    font-size: 14px;
}

.application-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-info {
    background: #17a2b8;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s ease;
}

.btn-info:hover {
    background: #138496;
}

.btn-success {
    background: #28a745;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s ease;
}

.btn-success:hover {
    background: #218838;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: background 0.3s ease;
}

.btn-danger:hover {
    background: #c82333;
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

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    box-sizing: border-box;
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

@media (max-width: 768px) {
    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
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
    .btn-info,
    .btn-success,
    .btn-danger {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
let currentFilter = 'all';

function filterApplications(status) {
    currentFilter = status;
    
    // Update active tab
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Filter cards
    document.querySelectorAll('.application-card').forEach(card => {
        if (status === 'all' || card.dataset.status === status) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function viewFullCoverLetter(applicationId, coverLetter) {
    document.getElementById('coverLetterBody').innerHTML = `<p style="white-space: pre-wrap;">${coverLetter}</p>`;
    document.getElementById('coverLetterModal').style.display = 'block';
}

function closeCoverLetterModal() {
    document.getElementById('coverLetterModal').style.display = 'none';
}

function markUnderReview(applicationId) {
    if (!confirm('Mark this application as under review?')) return;
    
    fetch(`/admin/job-applications/${applicationId}/under-review`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}

function showAcceptModal(applicationId) {
    document.getElementById('acceptApplicationId').value = applicationId;
    document.getElementById('acceptModal').style.display = 'block';
}

function closeAcceptModal() {
    document.getElementById('acceptModal').style.display = 'none';
    document.getElementById('acceptForm').reset();
}

function showRejectModal(applicationId) {
    document.getElementById('rejectApplicationId').value = applicationId;
    document.getElementById('rejectModal').style.display = 'block';
}

function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectForm').reset();
}

function editApplication(applicationId) {
    // Fetch application details
    fetch(`/api/job-applications/${applicationId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const app = data.application;
            document.getElementById('editApplicationId').value = applicationId;
            document.getElementById('editStatus').value = app.status;
            document.getElementById('editAdminNotes').value = app.admin_notes || '';
            document.getElementById('editApplicationModal').style.display = 'block';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while fetching application details.');
    });
}

function closeEditApplicationModal() {
    document.getElementById('editApplicationModal').style.display = 'none';
    document.getElementById('editApplicationForm').reset();
}

// Form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Edit application form submission
    document.getElementById('editApplicationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const applicationId = document.getElementById('editApplicationId').value;
        const status = document.getElementById('editStatus').value;
        const adminNotes = document.getElementById('editAdminNotes').value;
        
        fetch(`/admin/job-applications/${applicationId}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                status: status,
                admin_notes: adminNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeEditApplicationModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
    
    document.getElementById('acceptForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const applicationId = document.getElementById('acceptApplicationId').value;
        const notes = document.getElementById('acceptNotes').value;
        
        fetch(`/admin/job-applications/${applicationId}/accept`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ admin_notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeAcceptModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
    
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const applicationId = document.getElementById('rejectApplicationId').value;
        const notes = document.getElementById('rejectNotes').value;
        
        fetch(`/admin/job-applications/${applicationId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ admin_notes: notes })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeRejectModal();
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred.');
        });
    });
});

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    const coverLetterModal = document.getElementById('coverLetterModal');
    const acceptModal = document.getElementById('acceptModal');
    const rejectModal = document.getElementById('rejectModal');
    const editApplicationModal = document.getElementById('editApplicationModal');
    
    if (e.target === coverLetterModal) {
        closeCoverLetterModal();
    }
    if (e.target === acceptModal) {
        closeAcceptModal();
    }
    if (e.target === rejectModal) {
        closeRejectModal();
    }
    if (e.target === editApplicationModal) {
        closeEditApplicationModal();
    }
});
</script>
@endsection

