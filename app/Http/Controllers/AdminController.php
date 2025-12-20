<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CpdApplication;
use App\Models\MemoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    private function ensureAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_approved || Auth::user()->role !== 'Administrator') {
            abort(403, 'Access denied. Admin privileges required.');
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();
        return redirect()->route('admin.cpd.index');
    }

    public function pendingApprovals()
    {
        $this->ensureAdmin();
        $pendingUsers = User::where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pending-approvals', compact('pendingUsers'));
    }

    public function approveUser($id)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);

        return response()->json([
            'success' => true,
            'message' => 'User approved successfully!'
        ]);
    }

    public function rejectUser($id)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User rejected and deleted successfully!'
        ]);
    }

    public function manageUsers(Request $request)
    {
        $this->ensureAdmin();
        $currentAdminId = auth()->id();
        $query = User::where('id', '!=', $currentAdminId)->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%") ;
            });
        }

        $users = $query->paginate(15)->appends(['search' => $request->input('search')]);
        $userCount = $query->count();
        
        return view('admin.manage-users', compact('users', 'userCount'));
    }

    public function updateUserRole(Request $request, $id)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully!'
        ]);
    }

    public function updateUserDetails(Request $request, $id)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User details updated successfully!'
        ]);
    }

    public function deleteUser($id)
    {
        $this->ensureAdmin();
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account!'
            ]);
        }
        
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }

    // CPD Application Management Methods
    public function cpdApplications(Request $request)
    {
        $this->ensureAdmin();
        
        $query = CpdApplication::with(['user', 'headOfSectionReviewer'])
            ->whereIn('status', ['under_review', 'approved', 'rejected', 'rework']) // Only show applications that have been reviewed by Head Of Section
            ->orderBy('created_at', 'desc');

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

        $applications = $query->paginate(15)->appends($request->query());

        return view('admin.cpd.index', compact('applications'));
    }

    public function cpdApplicationShow(CpdApplication $application)
    {
        $this->ensureAdmin();
        return view('admin.cpd.show', compact('application'));
    }

    public function cpdApplicationApprove(Request $request, CpdApplication $application)
    {
        $this->ensureAdmin();
        
        // Ensure the application has been reviewed by Head Of Section
        if ($application->status !== 'under_review') {
            return redirect()->route('admin.cpd.index')
                ->with('error', 'Only applications reviewed by Head Of Section can be approved.');
        }
        
        $application->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes', '')
        ]);

        return redirect()->route('admin.cpd.index')->with('success', 'CPD application approved successfully!');
    }

    public function cpdApplicationReject(Request $request, CpdApplication $application)
    {
        $this->ensureAdmin();
        
        // Ensure the application has been reviewed by Head Of Section
        if ($application->status !== 'under_review') {
            return redirect()->route('admin.cpd.index')
                ->with('error', 'Only applications reviewed by Head Of Section can be rejected.');
        }
        
        $application->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes', '')
        ]);

        return redirect()->route('admin.cpd.index')->with('success', 'CPD application rejected successfully!');
    }

    public function cpdApplicationRework(Request $request, CpdApplication $application)
    {
        $this->ensureAdmin();
        
        // Ensure the application has been reviewed by Head Of Section
        if ($application->status !== 'under_review') {
            return redirect()->route('admin.cpd.index')
                ->with('error', 'Only applications reviewed by Head Of Section can be marked for rework.');
        }
        
        $application->update([
            'status' => 'rework',
            'admin_notes' => $request->input('admin_notes', '')
        ]);

        return redirect()->route('admin.cpd.index')->with('success', 'CPD application marked for rework. Staff can now edit the application.');
    }

    public function cpdApplicationDelete(CpdApplication $application)
    {
        $this->ensureAdmin();
        
        // Only allow deletion of approved or rejected applications
        if (!in_array($application->status, ['approved', 'rejected'])) {
            return redirect()->route('admin.cpd.index')->with('error', 'Only approved or rejected applications can be deleted.');
        }

        $applicantName = $application->user->full_name ?? $application->user->name;
        $eventTitle = $application->event_title;
        
        $application->delete();

        return redirect()->route('admin.cpd.index')->with('success', "CPD application for {$eventTitle} by {$applicantName} has been deleted successfully.");
    }

    public function cpdApplicationDownloadPdf(CpdApplication $application)
    {
        $this->ensureAdmin();
        
        // Create a clean filename from the event title and applicant name
        $applicantName = $application->user->full_name ?? $application->user->name;
        $eventTitle = $application->event_title;
        
        $cleanApplicant = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $applicantName);
        $cleanApplicant = preg_replace('/\s+/', '_', trim($cleanApplicant));
        $cleanApplicant = substr($cleanApplicant, 0, 30);
        
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $eventTitle);
        $cleanTitle = preg_replace('/\s+/', '_', trim($cleanTitle));
        $cleanTitle = substr($cleanTitle, 0, 40);
        
        $filename = 'CPD_' . $cleanApplicant . '_' . $cleanTitle . '_' . $application->id . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cpd.pdf', compact('application'));
        return $pdf->download($filename);
    }

    // Memo Request Methods
    public function memoIndex(Request $request)
    {
        $this->ensureAdmin();
        
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

        return view('admin.memo.index', compact('memoRequests'));
    }

    public function memoShow(MemoRequest $memo)
    {
        $this->ensureAdmin();
        $memo->load('user', 'reviewer');
        
        return view('admin.memo.show', compact('memo'));
    }

    public function memoApprove(Request $request, MemoRequest $memo)
    {
        $this->ensureAdmin();

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

        return redirect()->route('admin.memo.index')->with('success', 'Memo request approved successfully!');
    }

    public function memoReject(Request $request, MemoRequest $memo)
    {
        $this->ensureAdmin();

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $memo->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()->route('admin.memo.index')->with('success', 'Memo request rejected successfully!');
    }

    public function memoDelete(MemoRequest $memo)
    {
        $this->ensureAdmin();

        // Delete associated files
        if ($memo->form_files) {
            foreach ($memo->form_files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        if ($memo->memo_file) {
            Storage::disk('public')->delete($memo->memo_file);
        }

        $memo->delete();

        return redirect()->route('admin.memo.index')->with('success', 'Memo request deleted successfully!');
    }

    public function memoDownloadForm(MemoRequest $memo, $fileIndex)
    {
        $this->ensureAdmin();

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
        $this->ensureAdmin();

        if (!$memo->memo_file || !Storage::disk('public')->exists($memo->memo_file)) {
            abort(404, 'Memo file not found.');
        }

        return Storage::disk('public')->download($memo->memo_file);
    }
}
