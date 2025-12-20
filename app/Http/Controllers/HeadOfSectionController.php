<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CpdApplication;
use App\Models\MemoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HeadOfSectionController extends Controller
{
    private function ensureHeadOfSection()
    {
        if (!Auth::check() || !Auth::user()->is_approved || Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Head Of Section privileges required.');
        }
    }

    public function dashboard()
    {
        $this->ensureHeadOfSection();
        return redirect()->route('headofsection.cpd.index');
    }

    /**
     * Display a listing of submitted CPD applications for Head Of Section review
     */
    public function cpdApplications(Request $request)
    {
        $this->ensureHeadOfSection();
        

        
        $query = CpdApplication::orderBy('created_at', 'desc'); // Show all applications, newest first

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('event_title', 'like', "%$search%")
                  ->orWhere('event_type', 'like', "%$search%")
                  ->orWhere('venue', 'like', "%$search%")
                  ->orWhere('host_country', 'like', "%$search%")
                  ->orWhere('organiser_name', 'like', "%$search%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%$search%")
                               ->orWhere('first_name', 'like', "%$search%")
                               ->orWhere('last_name', 'like', "%$search%")
                               ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by event type
        if ($request->filled('event_type') && $request->event_type !== 'all') {
            $query->where('event_type', $request->event_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $applications = $query->with('user')->paginate(15)->appends($request->query());
        
        return view('headofsection.cpd.index', compact('applications'));
    }

    /**
     * Display the specified CPD application for review
     */
    public function cpdApplicationShow(CpdApplication $application)
    {
        $this->ensureHeadOfSection();
        
        return view('headofsection.cpd.show', compact('application'));
    }

    /**
     * Approve the CPD application and forward to admin
     */
    public function cpdApplicationApprove(Request $request, CpdApplication $application)
    {
        $this->ensureHeadOfSection();
        
        // Ensure the application is in submitted status
        if ($application->status !== 'submitted') {
            return redirect()->route('headofsection.cpd.index')
                ->with('error', 'Only submitted applications can be approved.');
        }

        // Check if this is just saving notes
        if ($request->has('save_only')) {
            $application->update([
                'head_of_section_notes' => $request->input('head_of_section_notes', ''),
            ]);

            return redirect()->route('headofsection.cpd.show', $application)
                ->with('success', 'Review notes saved successfully.');
        }

        $application->update([
            'status' => 'under_review', // Change to under_review for admin review
            'head_of_section_notes' => $request->input('head_of_section_notes', ''),
            'head_of_section_reviewed_at' => now(),
            'head_of_section_reviewed_by' => Auth::id()
        ]);

        return redirect()->route('headofsection.cpd.index')
            ->with('success', 'CPD application approved and forwarded to administration for final review.');
    }

    /**
     * Reject the CPD application
     */
    public function cpdApplicationReject(Request $request, CpdApplication $application)
    {
        $this->ensureHeadOfSection();
        
        // Ensure the application is in submitted status
        if ($application->status !== 'submitted') {
            return redirect()->route('headofsection.cpd.index')
                ->with('error', 'Only submitted applications can be rejected.');
        }

        $application->update([
            'status' => 'rejected',
            'head_of_section_notes' => $request->input('head_of_section_notes', ''),
            'head_of_section_reviewed_at' => now(),
            'head_of_section_reviewed_by' => Auth::id()
        ]);

        return redirect()->route('headofsection.cpd.index')
            ->with('success', 'CPD application rejected.');
    }

    /**
     * Mark the CPD application for rework
     */
    public function cpdApplicationRework(Request $request, CpdApplication $application)
    {
        $this->ensureHeadOfSection();
        
        // Ensure the application is in submitted status
        if ($application->status !== 'submitted') {
            return redirect()->route('headofsection.cpd.index')
                ->with('error', 'Only submitted applications can be marked for rework.');
        }

        $application->update([
            'status' => 'rework',
            'head_of_section_notes' => $request->input('head_of_section_notes', ''),
            'head_of_section_reviewed_at' => now(),
            'head_of_section_reviewed_by' => Auth::id()
        ]);

        return redirect()->route('headofsection.cpd.index')
            ->with('success', 'CPD application marked for rework. Staff can now edit and resubmit the application.');
    }

    /**
     * Download PDF of the CPD application
     */
    public function cpdApplicationDownloadPdf(CpdApplication $application)
    {
        $this->ensureHeadOfSection();

        // Create a clean filename from the event title
        $eventTitle = $application->event_title;
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $eventTitle); // Remove special characters
        $cleanTitle = preg_replace('/\s+/', '_', trim($cleanTitle)); // Replace spaces with underscores
        $cleanTitle = substr($cleanTitle, 0, 50); // Limit length to avoid filename issues
        
        $filename = 'CPD_Application_' . $cleanTitle . '_' . $application->id . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cpd.pdf', compact('application'));
        return $pdf->download($filename);
    }

    // Memo Request Methods
    public function memoIndex(Request $request)
    {
        $this->ensureHeadOfSection();
        
        $query = MemoRequest::with('user')->orderBy('created_at', 'desc');

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%$search%")
                               ->orWhere('email', 'like', "%$search%")
                               ->orWhere('full_name', 'like', "%$search%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $memoRequests = $query->paginate(15)->appends($request->query());

        return view('headofsection.memo.index', compact('memoRequests'));
    }

    public function memoShow(MemoRequest $memo)
    {
        $this->ensureHeadOfSection();
        $memo->load('user', 'reviewer');
        
        return view('headofsection.memo.show', compact('memo'));
    }

    public function memoApprove(Request $request, MemoRequest $memo)
    {
        $this->ensureHeadOfSection();

        $request->validate([
            'admin_notes' => 'nullable|string',
            'memo_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle memo file upload if provided
        $memoFilePath = null;
        if ($request->hasFile('memo_file')) {
            $file = $request->file('memo_file');
            $filename = 'memo_' . time() . '_' . $file->getClientOriginalName();
            $memoFilePath = $file->storeAs('memo-files', $filename, 'public');
        }

        $memo->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'memo_file' => $memoFilePath,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('headofsection.memo.index')->with('success', 'Memo request approved successfully!');
    }

    public function memoReject(Request $request, MemoRequest $memo)
    {
        $this->ensureHeadOfSection();

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $memo->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('headofsection.memo.index')->with('success', 'Memo request rejected successfully!');
    }

    public function memoDownloadForm(MemoRequest $memo, $fileIndex)
    {
        $this->ensureHeadOfSection();

        $files = $memo->form_files;
        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $filePath = $files[$fileIndex];
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('public')->download($filePath);
    }

    public function memoDownloadMemo(MemoRequest $memo)
    {
        $this->ensureHeadOfSection();

        if (!$memo->memo_file || !Storage::disk('public')->exists($memo->memo_file)) {
            abort(404, 'Memo file not found.');
        }

        return Storage::disk('public')->download($memo->memo_file);
    }
}
