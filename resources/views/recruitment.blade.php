@extends('layouts.app')

@section('title', 'Jobs - UTB HR Central')
@section('page-title', 'Jobs')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2 style="margin: 0; color: #2c3e50;">Jobs Portal</h2>
        @if(auth()->user()->role !== 'Administrator')
            <a href="{{ route('job-applications.my-applications') }}" class="btn-primary" style="text-decoration: none;">
                📋 My Applications
            </a>
        @endif
    </div>
    
    <!-- Jobs Type Selection -->
    <div class="recruitment-type-selector" id="typeSelector">
        <div class="type-option active" data-type="academic">
            <div class="type-icon">🎓</div>
            <h3>Academic Positions</h3>
            <p>Faculty, Research, and Teaching Positions</p>
        </div>
        <div class="type-option" data-type="nonacademic">
            <div class="type-icon">💼</div>
            <h3>Non-Academic Positions</h3>
            <p>Administrative, Technical, and Support Roles</p>
        </div>
        <div class="type-option" data-type="tabung">
            <div class="type-icon">🏦</div>
            <h3>Tabung Staff</h3>
            <p>Banking and Customer Service Positions</p>
        </div>
    </div>

    <!-- Academic Jobs Section -->
    <div class="recruitment-section" id="academicSection">
        <div class="section-header">
            <h3>Academic Positions</h3>
            @if(auth()->user()->role === 'Administrator')
                <button class="btn-primary" onclick="showJobForm('academic')">Post New Position</button>
            @endif
        </div>
        
        <div class="job-listings">
            @forelse($academicJobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h4>{{ $job->title }}</h4>
                        <span class="job-status {{ $job->status }}">{{ ucfirst($job->status) }}</span>
                    </div>
                    <div class="job-details">
                        <p><strong>Department:</strong> {{ $job->department }}</p>
                        <p><strong>Type:</strong> {{ $job->employment_type }}</p>
                        <p><strong>Deadline:</strong> {{ $job->deadline->format('F d, Y') }}</p>
                        <p><strong>Applications:</strong> {{ $job->applications_count }} received</p>
                    </div>
                    <div class="job-actions">
                        @if(auth()->user()->role === 'Administrator')
                            <button class="btn-secondary" onclick="viewApplications({{ $job->id }})">View Applications ({{ $job->applications_count }})</button>
                            <button class="btn-danger" onclick="deleteJob({{ $job->id }})">Delete</button>
                            @if($job->status === 'active')
                                <button class="btn-danger" onclick="closeJob({{ $job->id }})">Close</button>
                            @else
                                <button class="btn-success" onclick="reopenJob({{ $job->id }})">Reopen</button>
                            @endif
                        @else
                            <button class="btn-secondary" onclick="viewJobDetails({{ $job->id }})">View Details</button>
                            @if($job->status === 'active')
                                <button class="btn-primary" onclick="showApplyModal({{ $job->id }})">Apply Now</button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-jobs">
                    <p>No academic positions available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Non-Academic Jobs Section -->
    <div class="recruitment-section" id="nonacademicSection" style="display: none;">
        <div class="section-header">
            <h3>Non-Academic Positions</h3>
            @if(auth()->user()->role === 'Administrator')
                <button class="btn-primary" onclick="showJobForm('nonacademic')">Post New Position</button>
            @endif
        </div>
        
        <div class="job-listings">
            @forelse($nonacademicJobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h4>{{ $job->title }}</h4>
                        <span class="job-status {{ $job->status }}">{{ ucfirst($job->status) }}</span>
                    </div>
                    <div class="job-details">
                        <p><strong>Department:</strong> {{ $job->department }}</p>
                        <p><strong>Type:</strong> {{ $job->employment_type }}</p>
                        <p><strong>Deadline:</strong> {{ $job->deadline->format('F d, Y') }}</p>
                        <p><strong>Applications:</strong> {{ $job->applications_count }} received</p>
                    </div>
                    <div class="job-actions">
                        @if(auth()->user()->role === 'Administrator')
                            <button class="btn-secondary" onclick="viewApplications({{ $job->id }})">View Applications ({{ $job->applications_count }})</button>
                            <button class="btn-danger" onclick="deleteJob({{ $job->id }})">Delete</button>
                            @if($job->status === 'active')
                                <button class="btn-danger" onclick="closeJob({{ $job->id }})">Close</button>
                            @else
                                <button class="btn-success" onclick="reopenJob({{ $job->id }})">Reopen</button>
                            @endif
                        @else
                            <button class="btn-secondary" onclick="viewJobDetails({{ $job->id }})">View Details</button>
                            @if($job->status === 'active')
                                <button class="btn-primary" onclick="showApplyModal({{ $job->id }})">Apply Now</button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-jobs">
                    <p>No non-academic positions available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tabung Staff Jobs Section -->
    <div class="recruitment-section" id="tabungSection" style="display: none;">
        <div class="section-header">
            <h3>Tabung Staff Positions</h3>
            @if(auth()->user()->role === 'Administrator')
                <button class="btn-primary" onclick="showJobForm('tabung')">Post New Position</button>
            @endif
        </div>
        
        <div class="job-listings">
            @forelse($tabungJobs as $job)
                <div class="job-card">
                    <div class="job-header">
                        <h4>{{ $job->title }}</h4>
                        <span class="job-status {{ $job->status }}">{{ ucfirst($job->status) }}</span>
                    </div>
                    <div class="job-details">
                        <p><strong>Department:</strong> {{ $job->department }}</p>
                        <p><strong>Type:</strong> {{ $job->employment_type }}</p>
                        <p><strong>Deadline:</strong> {{ $job->deadline->format('F d, Y') }}</p>
                        <p><strong>Applications:</strong> {{ $job->applications_count }} received</p>
                    </div>
                    <div class="job-actions">
                        @if(auth()->user()->role === 'Administrator')
                            <button class="btn-secondary" onclick="viewApplications({{ $job->id }})">View Applications ({{ $job->applications_count }})</button>
                            <button class="btn-danger" onclick="deleteJob({{ $job->id }})">Delete</button>
                            @if($job->status === 'active')
                                <button class="btn-danger" onclick="closeJob({{ $job->id }})">Close</button>
                            @else
                                <button class="btn-success" onclick="reopenJob({{ $job->id }})">Reopen</button>
                            @endif
                        @else
                            <button class="btn-secondary" onclick="viewJobDetails({{ $job->id }})">View Details</button>
                            @if($job->status === 'active')
                                <button class="btn-primary" onclick="showApplyModal({{ $job->id }})">Apply Now</button>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <div class="no-jobs">
                    <p>No Tabung staff positions available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Verification Modal -->
    <div id="verificationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Verify Job Application Integrity</h3>
                <span class="close" onclick="closeVerificationModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <p style="font-size: 16px; color: #2c3e50; margin-bottom: 15px;">
                        <strong>Please verify the integrity of the job application details before posting:</strong>
                    </p>
                    <div id="verificationSummary" style="background: #f8f9fa; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                        <!-- Verification summary will be populated here -->
                    </div>
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 12px; border-radius: 4px; margin-bottom: 15px;">
                        <p style="margin: 0; color: #856404; font-size: 14px;">
                            <strong>⚠️ Important:</strong> Once posted, this position can only be deleted and not edited. Please ensure all information is correct.
                        </p>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" id="verificationCheckbox" style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                            <span style="font-weight: 600; color: #2c3e50;">I have verified the integrity of the job application details and confirm they are correct.</span>
                        </label>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeVerificationModal()">Cancel</button>
                    <button type="button" id="confirmPostBtn" class="btn-primary" onclick="confirmPostJob()" disabled>Confirm and Post Position</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Application Form Modal (Admin) -->
    <div id="jobFormModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Post New Job Position</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="jobForm">
                    <input type="hidden" id="recruitmentType" name="recruitmentType">
                    <div class="form-group">
                        <label for="jobTitle">Job Title *</label>
                        <input type="text" id="jobTitle" name="jobTitle" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="department">Department *</label>
                        <select id="department" name="department" required>
                            <option value="">Select Department</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Business">Business</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Administration">Administration</option>
                            <option value="Human Resources">Human Resources</option>
                            <option value="Tabung Banking">Tabung Banking</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="jobType">Employment Type *</label>
                        <select id="jobType" name="jobType" required>
                            <option value="">Select Type</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                            <option value="Temporary">Temporary</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="deadline">Application Deadline *</label>
                        <input type="date" id="deadline" name="deadline" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Job Description *</label>
                        <textarea id="description" name="description" rows="6" required placeholder="Enter detailed job description, requirements, and responsibilities..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="requirements">Requirements *</label>
                        <textarea id="requirements" name="requirements" rows="4" required placeholder="Enter qualifications, experience, and skills required..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Post Position</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Job Details Modal (Staff) -->
    <div id="jobDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="jobDetailsTitle">Job Details</h3>
                <span class="close" onclick="closeJobDetailsModal()">&times;</span>
            </div>
            <div class="modal-body" id="jobDetailsBody">
                <!-- Job details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Apply for Job Modal (Staff) -->
    <div id="applyModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Apply for Position</h3>
                <span class="close" onclick="closeApplyModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="applyForm" enctype="multipart/form-data">
                    <input type="hidden" id="applyJobId" name="job_posting_id">
                    
                    <div class="form-group">
                        <label for="cv">Upload CV/Resume *</label>
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        <small style="color: #6c757d;">Accepted formats: PDF, DOC, DOCX (Max 2MB) - <a href="#" onclick="alert('To upload larger files (up to 10MB), please ask your administrator to update PHP settings.'); return false;" style="color: #3498db;">Need larger?</a></small>
                        <div id="fileInfo" style="display: none; margin-top: 8px; padding: 8px; background: #e7f3ff; border-radius: 4px; font-size: 13px;"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="coverLetter">Cover Letter (Optional)</label>
                        <textarea id="coverLetter" name="cover_letter" rows="8" placeholder="Tell us why you're a great fit for this position..."></textarea>
                    </div>
                    
                    <!-- Upload Progress -->
                    <div id="uploadProgress" style="display: none; margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-weight: 600; color: #2c3e50;">Uploading...</span>
                            <span id="progressPercent" style="color: #3498db; font-weight: 600;">0%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #e9ecef; border-radius: 4px; overflow: hidden;">
                            <div id="progressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #3498db, #2980b9); transition: width 0.3s ease;"></div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="closeApplyModal()">Cancel</button>
                        <button type="submit" id="submitApplicationBtn" class="btn-primary">
                            <span class="btn-text">Submit Application</span>
                            <span class="btn-spinner" style="display: none;">
                                <span class="spinner"></span> Submitting...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.recruitment-type-selector {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    justify-content: center;
}

.type-option {
    flex: 1;
    max-width: 300px;
    padding: 25px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.type-option:hover {
    border-color: #3498db;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.15);
}

.type-option.active {
    border-color: #3498db;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
}

.type-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.type-option h3 {
    margin: 0 0 10px 0;
    font-size: 20px;
    font-weight: 600;
}

.type-option p {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
}

.recruitment-section {
    margin-top: 30px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.section-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 24px;
}

.job-listings {
    display: grid;
    gap: 20px;
}

.job-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.3s ease;
}

.job-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.job-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
}

.job-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.job-status.active {
    background: #d4edda;
    color: #155724;
}

.job-status.closed {
    background: #f8d7da;
    color: #721c24;
}

.job-details {
    margin-bottom: 15px;
}

.job-details p {
    margin: 5px 0;
    color: #6c757d;
    font-size: 14px;
}

.job-actions {
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
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn-secondary:hover {
    background: #5a6268;
}

.btn-danger {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn-danger:hover {
    background: #c0392b;
}

.btn-success {
    background: #27ae60;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.3s ease;
}

.btn-success:hover {
    background: #229954;
}

.no-jobs {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    font-style: italic;
}

.no-jobs p {
    margin: 0;
    font-size: 16px;
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
    margin-bottom: 5px;
    font-weight: 600;
    color: #2c3e50;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group textarea {
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 30px;
}

/* Spinner Animation */
.spinner {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .recruitment-type-selector {
        flex-direction: column;
        align-items: center;
    }
    
    .type-option {
        max-width: 100%;
    }
    
    .section-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .job-actions {
        justify-content: center;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style>

<script>
function switchJobType(type) {
    // Update active state
    document.querySelectorAll('.type-option').forEach(option => {
        option.classList.remove('active');
    });
    document.querySelector(`[data-type="${type}"]`).classList.add('active');
    
    // Show/hide sections
    document.getElementById('academicSection').style.display = type === 'academic' ? 'block' : 'none';
    document.getElementById('nonacademicSection').style.display = type === 'nonacademic' ? 'block' : 'none';
    document.getElementById('tabungSection').style.display = type === 'tabung' ? 'block' : 'none';
}

function showJobForm(type) {
    document.getElementById('modalTitle').textContent = `Post New ${type.charAt(0).toUpperCase() + type.slice(1)} Position`;
    document.getElementById('recruitmentType').value = type;
    document.getElementById('jobFormModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('jobFormModal').style.display = 'none';
    document.getElementById('jobForm').reset();
}

function viewApplications(jobId) {
    window.location.href = `/admin/job-applications/job/${jobId}`;
}

function viewJobDetails(jobId) {
    // Fetch job details via the page data (jobs are already loaded)
    const allJobs = @json(array_merge($academicJobs->toArray(), $nonacademicJobs->toArray(), $tabungJobs->toArray()));
    const job = allJobs.find(j => j.id === jobId);
    
    if (job) {
        const deadline = new Date(job.deadline).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        document.getElementById('jobDetailsTitle').textContent = job.title;
        document.getElementById('jobDetailsBody').innerHTML = `
            <div style="margin-bottom: 20px;">
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Position Information</h4>
                <p><strong>Department:</strong> ${job.department}</p>
                <p><strong>Employment Type:</strong> ${job.employment_type}</p>
                <p><strong>Application Deadline:</strong> ${deadline}</p>
                <p><strong>Status:</strong> <span class="job-status ${job.status}">${job.status.charAt(0).toUpperCase() + job.status.slice(1)}</span></p>
            </div>
            <div style="margin-bottom: 20px;">
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Job Description</h4>
                <p style="white-space: pre-wrap;">${job.description}</p>
            </div>
            <div style="margin-bottom: 20px;">
                <h4 style="color: #2c3e50; margin-bottom: 10px;">Requirements</h4>
                <p style="white-space: pre-wrap;">${job.requirements}</p>
            </div>
            ${job.status === 'active' ? `
            <div style="text-align: center; margin-top: 30px;">
                <button class="btn-primary" onclick="closeJobDetailsModal(); showApplyModal(${job.id});">Apply for This Position</button>
            </div>
            ` : ''}
        `;
        document.getElementById('jobDetailsModal').style.display = 'block';
    }
}

function closeJobDetailsModal() {
    document.getElementById('jobDetailsModal').style.display = 'none';
}

function showApplyModal(jobId) {
    document.getElementById('applyJobId').value = jobId;
    document.getElementById('applyForm').reset();
    document.getElementById('applyJobId').value = jobId; // Reset clears it, so set again
    document.getElementById('applyModal').style.display = 'block';
}

function closeApplyModal() {
    document.getElementById('applyModal').style.display = 'none';
    document.getElementById('applyForm').reset();
    
    // Reset file info and progress
    const fileInfo = document.getElementById('fileInfo');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const submitButton = document.getElementById('submitApplicationBtn');
    const btnText = submitButton.querySelector('.btn-text');
    const btnSpinner = submitButton.querySelector('.btn-spinner');
    
    fileInfo.style.display = 'none';
    uploadProgress.style.display = 'none';
    progressBar.style.width = '0%';
    progressPercent.textContent = '0%';
    progressBar.style.background = 'linear-gradient(90deg, #3498db, #2980b9)';
    submitButton.disabled = false;
    btnText.style.display = 'inline';
    btnSpinner.style.display = 'none';
}

function deleteJob(jobId) {
    if (confirm('Are you sure you want to delete this job posting? This action cannot be undone and will remove all associated applications.')) {
        fetch(`/job-postings/${jobId}`, {
            method: 'DELETE',
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
            alert('An error occurred while deleting the job posting.');
        });
    }
}

function closeVerificationModal() {
    document.getElementById('verificationModal').style.display = 'none';
    document.getElementById('verificationCheckbox').checked = false;
    document.getElementById('confirmPostBtn').disabled = true;
}

function confirmPostJob() {
    const form = document.getElementById('jobForm');
    const formData = new FormData(form);
    
    // Close verification modal
    closeVerificationModal();
    
    // Submit the form
    fetch('{{ route("job-postings.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            jobTitle: formData.get('jobTitle'),
            department: formData.get('department'),
            jobType: formData.get('jobType'),
            deadline: formData.get('deadline'),
            description: formData.get('description'),
            requirements: formData.get('requirements'),
            recruitmentType: formData.get('recruitmentType')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while posting the job position.');
    });
}

function closeJob(jobId) {
    if (confirm('Are you sure you want to close this job posting?')) {
        fetch(`/job-postings/${jobId}/close`, {
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
            alert('An error occurred while closing the job posting.');
        });
    }
}

function reopenJob(jobId) {
    if (confirm('Are you sure you want to reopen this job posting?')) {
        fetch(`/job-postings/${jobId}/reopen`, {
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
            alert('An error occurred while reopening the job posting.');
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Type selector click events
    document.querySelectorAll('.type-option').forEach(option => {
        option.addEventListener('click', function() {
            switchJobType(this.dataset.type);
        });
    });
    
    // Form submission - show verification modal first
    document.getElementById('jobForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Populate verification summary
        const summaryDiv = document.getElementById('verificationSummary');
        summaryDiv.innerHTML = `
            <div style="line-height: 1.8;">
                <p style="margin: 5px 0;"><strong>Job Title:</strong> ${formData.get('jobTitle')}</p>
                <p style="margin: 5px 0;"><strong>Department:</strong> ${formData.get('department')}</p>
                <p style="margin: 5px 0;"><strong>Employment Type:</strong> ${formData.get('jobType')}</p>
                <p style="margin: 5px 0;"><strong>Deadline:</strong> ${new Date(formData.get('deadline')).toLocaleDateString()}</p>
                <p style="margin: 5px 0;"><strong>Recruitment Type:</strong> ${formData.get('recruitmentType').charAt(0).toUpperCase() + formData.get('recruitmentType').slice(1)}</p>
                <p style="margin: 5px 0;"><strong>Description:</strong> ${formData.get('description').substring(0, 100)}${formData.get('description').length > 100 ? '...' : ''}</p>
                <p style="margin: 5px 0;"><strong>Requirements:</strong> ${formData.get('requirements').substring(0, 100)}${formData.get('requirements').length > 100 ? '...' : ''}</p>
            </div>
        `;
        
        // Show verification modal
        document.getElementById('verificationModal').style.display = 'block';
    });
    
    // Enable/disable confirm button based on checkbox
    document.getElementById('verificationCheckbox').addEventListener('change', function() {
        document.getElementById('confirmPostBtn').disabled = !this.checked;
    });
    
    // File input validation and preview
    const cvInput = document.getElementById('cv');
    if (cvInput) {
        cvInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('fileInfo');
            
            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                const maxSize = 2; // 2MB limit (matches current PHP setting)
                
                if (file.size > maxSize * 1024 * 1024) {
                    fileInfo.style.display = 'block';
                    fileInfo.style.background = '#f8d7da';
                    fileInfo.style.color = '#721c24';
                    fileInfo.innerHTML = `❌ File too large: ${fileSize}MB (Maximum: ${maxSize}MB). Please choose a smaller file.`;
                    cvInput.value = ''; // Clear the input
                    return;
                }
                
                fileInfo.style.display = 'block';
                fileInfo.style.background = '#d4edda';
                fileInfo.style.color = '#155724';
                fileInfo.innerHTML = `✓ ${file.name} (${fileSize}MB)`;
            } else {
                fileInfo.style.display = 'none';
            }
        });
    }
    
    // Apply form submission with progress
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = document.getElementById('submitApplicationBtn');
            const btnText = submitButton.querySelector('.btn-text');
            const btnSpinner = submitButton.querySelector('.btn-spinner');
            const uploadProgress = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            
            // Disable button and show spinner
            submitButton.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-flex';
            uploadProgress.style.display = 'block';
            
            // Create XMLHttpRequest for progress tracking
            const xhr = new XMLHttpRequest();
            let uploadStartTime = Date.now();
            
            // Set timeout to 60 seconds
            xhr.timeout = 60000;
            
            // Track upload progress
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressPercent.textContent = percentComplete + '%';
                    
                    // Show speed info
                    const elapsed = (Date.now() - uploadStartTime) / 1000;
                    if (elapsed > 5 && percentComplete < 50) {
                        progressPercent.textContent = percentComplete + '% (Slow connection...)';
                    }
                }
            });
            
            xhr.addEventListener('load', function() {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            progressBar.style.background = 'linear-gradient(90deg, #27ae60, #229954)';
                            progressPercent.textContent = '✓ Complete!';
                            
                            setTimeout(() => {
                                alert(data.message);
                                closeApplyModal();
                                location.reload();
                            }, 500);
                        } else {
                            alert('Error: ' + data.message);
                            resetSubmitButton();
                        }
                    } catch (error) {
                        console.error('Error parsing response:', error);
                        console.log('Response text:', xhr.responseText);
                        alert('An error occurred while processing your application. Please try again.');
                        resetSubmitButton();
                    }
                } else {
                    console.error('HTTP Status:', xhr.status);
                    console.error('Response:', xhr.responseText);
                    alert('Server error (' + xhr.status + '). Please check if your file is under 2MB and try again.');
                    resetSubmitButton();
                }
            });
            
            xhr.addEventListener('error', function() {
                console.error('Upload error');
                alert('Network error occurred. Please check your connection and try again.');
                resetSubmitButton();
            });
            
            xhr.addEventListener('timeout', function() {
                console.error('Upload timeout');
                alert('Upload timeout. The file might be too large or your connection is too slow. Please try a smaller file.');
                resetSubmitButton();
            });
            
            xhr.open('POST', '{{ route("job-applications.store") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            console.log('Starting upload at:', new Date().toLocaleTimeString());
            xhr.send(formData);
            
            function resetSubmitButton() {
                submitButton.disabled = false;
                btnText.style.display = 'inline';
                btnSpinner.style.display = 'none';
                uploadProgress.style.display = 'none';
                progressBar.style.width = '0%';
                progressPercent.textContent = '0%';
                progressBar.style.background = 'linear-gradient(90deg, #3498db, #2980b9)';
            }
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        const jobFormModal = document.getElementById('jobFormModal');
        const jobDetailsModal = document.getElementById('jobDetailsModal');
        const applyModal = document.getElementById('applyModal');
        const verificationModal = document.getElementById('verificationModal');
        
        if (e.target === jobFormModal) {
            closeModal();
        }
        if (e.target === jobDetailsModal) {
            closeJobDetailsModal();
        }
        if (e.target === applyModal) {
            closeApplyModal();
        }
        if (e.target === verificationModal) {
            closeVerificationModal();
        }
    });
});
</script>
@endsection 