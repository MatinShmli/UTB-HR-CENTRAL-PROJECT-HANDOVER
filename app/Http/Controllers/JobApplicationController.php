<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the user's applications.
     */
    public function myApplications()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with('jobPosting')
            ->orderBy('applied_at', 'desc')
            ->get();

        return view('job-applications.my-applications', compact('applications'));
    }

    /**
     * Show the application form for a specific job.
     */
    public function create($jobId)
    {
        $job = JobPosting::findOrFail($jobId);
        
        // Check if user already applied
        $existingApplication = JobApplication::where('user_id', Auth::id())
            ->where('job_posting_id', $jobId)
            ->first();

        if ($existingApplication) {
            return redirect()->route('recruitment')
                ->with('error', 'You have already applied for this position.');
        }

        return view('job-applications.create', compact('job'));
    }

    /**
     * Store a new job application.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'job_posting_id' => 'required|exists:job_postings,id',
            'cover_letter' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:2048', // 2MB max (2048 KB)
        ]);

        // Check if user already applied
        $existingApplication = JobApplication::where('user_id', Auth::id())
            ->where('job_posting_id', $request->job_posting_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this position.'
            ], 400);
        }

        try {
            // Store CV file with simpler filename
            $cvFile = $request->file('cv');
            $extension = $cvFile->getClientOriginalExtension();
            $cvFileName = 'cv_' . Auth::id() . '_' . time() . '.' . $extension;
            $cvPath = $cvFile->storeAs('cvs', $cvFileName, 'private');

            if (!$cvPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload CV. Please try again.'
                ], 500);
            }

            // Create application and update count in one go
            $application = JobApplication::create([
                'user_id' => Auth::id(),
                'job_posting_id' => $request->job_posting_id,
                'cv_path' => $cvPath,
                'cover_letter' => $request->cover_letter,
                'status' => 'pending',
                'applied_at' => now(),
            ]);

            // Update count efficiently
            JobPosting::where('id', $request->job_posting_id)->increment('applications_count');

            return response()->json([
                'success' => true,
                'message' => 'Your application has been submitted successfully!',
                'application' => $application
            ]);
        } catch (\Exception $e) {
            \Log::error('Job application submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error submitting application. Please try again or contact support if the issue persists.'
            ], 500);
        }
    }

    /**
     * Display applications for a specific job (Admin only).
     */
    public function jobApplications($jobId)
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $job = JobPosting::with(['applications.user'])->findOrFail($jobId);
        $applications = $job->applications()->orderBy('applied_at', 'desc')->get();

        return view('job-applications.job-applications', compact('job', 'applications'));
    }

    /**
     * Display all job applications (Admin only).
     */
    public function index()
    {
        if (Auth::user()->role !== 'Administrator') {
            abort(403, 'Unauthorized');
        }

        $applications = JobApplication::with(['user', 'jobPosting'])
            ->orderBy('applied_at', 'desc')
            ->paginate(20);

        return view('job-applications.index', compact('applications'));
    }

    /**
     * Show a specific application (Admin or applicant only).
     */
    public function show($id)
    {
        $application = JobApplication::with(['user', 'jobPosting'])->findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'Administrator' && Auth::id() !== $application->user_id) {
            abort(403, 'Unauthorized');
        }

        return view('job-applications.show', compact('application'));
    }

    /**
     * Download CV file.
     */
    public function downloadCV($id)
    {
        $application = JobApplication::findOrFail($id);

        // Check authorization
        if (Auth::user()->role !== 'Administrator' && Auth::id() !== $application->user_id) {
            abort(403, 'Unauthorized');
        }

        if (!Storage::disk('private')->exists($application->cv_path)) {
            abort(404, 'CV file not found');
        }

        return Storage::disk('private')->download($application->cv_path);
    }

    /**
     * Update application status to under review (Admin only).
     */
    public function markUnderReview($id): JsonResponse
    {
        if (Auth::user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $application = JobApplication::findOrFail($id);
            $application->update([
                'status' => 'under_review',
                'reviewed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application marked as under review.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accept an application (Admin only).
     */
    public function accept(Request $request, $id): JsonResponse
    {
        if (Auth::user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        try {
            $application = JobApplication::findOrFail($id);
            $application->update([
                'status' => 'interview_scheduled',
                'admin_notes' => $request->admin_notes ?? 'Your application has been accepted. You will be contacted for an interview.',
                'reviewed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application accepted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error accepting application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an application (Admin only).
     */
    public function reject(Request $request, $id): JsonResponse
    {
        if (Auth::user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        try {
            $application = JobApplication::findOrFail($id);
            $application->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes ?? 'Unfortunately, your application was not successful at this time.',
                'reviewed_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rejecting application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get application details (AJAX).
     */
    public function getApplication($id): JsonResponse
    {
        try {
            $application = JobApplication::with(['user', 'jobPosting'])->findOrFail($id);

            // Check authorization
            if (Auth::user()->role !== 'Administrator' && Auth::id() !== $application->user_id) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            return response()->json([
                'success' => true,
                'application' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching application: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get applications for a specific job (AJAX).
     */
    public function getJobApplications($jobId): JsonResponse
    {
        if (Auth::user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $applications = JobApplication::where('job_posting_id', $jobId)
                ->with('user')
                ->orderBy('applied_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'applications' => $applications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching applications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an application (Admin only).
     */
    public function update(Request $request, $id): JsonResponse
    {
        if (Auth::user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,under_review,interview_scheduled,accepted,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        try {
            $application = JobApplication::findOrFail($id);
            
            $updateData = [
                'status' => $request->status,
                'reviewed_at' => now(),
            ];
            
            if ($request->has('admin_notes')) {
                $updateData['admin_notes'] = $request->admin_notes;
            }
            
            $application->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Application updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating application: ' . $e->getMessage()
            ], 500);
        }
    }
}
