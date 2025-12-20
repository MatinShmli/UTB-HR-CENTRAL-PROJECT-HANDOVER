<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JobPostingController extends Controller
{
    public function index()
    {
        $academicJobs = JobPosting::byType('academic')->active()->orderBy('created_at', 'desc')->get();
        $nonacademicJobs = JobPosting::byType('nonacademic')->active()->orderBy('created_at', 'desc')->get();
        $tabungJobs = JobPosting::byType('tabung')->active()->orderBy('created_at', 'desc')->get();

        return view('recruitment', compact('academicJobs', 'nonacademicJobs', 'tabungJobs'));
    }

    public function store(Request $request): JsonResponse
    {
        // Check if user is admin
        if (auth()->user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'jobTitle' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'jobType' => 'required|string|max:255',
            'deadline' => 'required|date|after:today',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'recruitmentType' => 'required|string|in:academic,nonacademic,tabung'
        ]);

        try {
            $jobPosting = JobPosting::create([
                'title' => $request->jobTitle,
                'department' => $request->department,
                'type' => $request->recruitmentType,
                'employment_type' => $request->jobType,
                'deadline' => $request->deadline,
                'description' => $request->description,
                'requirements' => $request->requirements,
                'status' => 'active',
                'applications_count' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job position posted successfully!',
                'job' => $jobPosting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating job posting: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Job postings cannot be edited after creation - they can only be deleted
        return response()->json([
            'success' => false,
            'message' => 'Job postings cannot be edited after creation. Please delete and create a new posting if changes are needed.'
        ], 403);
    }

    public function close($id): JsonResponse
    {
        // Check if user is admin
        if (auth()->user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->update(['status' => 'closed']);

            return response()->json([
                'success' => true,
                'message' => 'Job position closed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error closing job posting: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reopen($id): JsonResponse
    {
        // Check if user is admin
        if (auth()->user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->update(['status' => 'active']);

            return response()->json([
                'success' => true,
                'message' => 'Job position reopened successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reopening job posting: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        // Check if user is admin
        if (auth()->user()->role !== 'Administrator') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $jobPosting = JobPosting::findOrFail($id);
            $jobPosting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job position deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting job posting: ' . $e->getMessage()
            ], 500);
        }
    }
}
