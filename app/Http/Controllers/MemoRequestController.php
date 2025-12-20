<?php

namespace App\Http\Controllers;

use App\Models\MemoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MemoRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If admin or head of section, redirect to admin review panel
        if (Auth::user()->role === 'Administrator') {
            return redirect()->route('admin.memo.index');
        } elseif (Auth::user()->role === 'Head Of Section') {
            return redirect()->route('headofsection.memo.index');
        } else {
            // Staff can only see their own memo requests
            $query = Auth::user()->memoRequests()->orderBy('created_at', 'desc');

            // Apply search filters
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            }

            // Filter by status
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $memoRequests = $query->paginate(15)->appends($request->query());
        }
        
        return view('memo.index', compact('memoRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Prevent admins from creating memo requests
        if (Auth::user()->role === 'Administrator') {
            abort(403, 'Administrators cannot create memo requests. They can only review requests submitted by staff.');
        }
        
        $user = Auth::user();
        return view('memo.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Prevent admins from creating memo requests
        if (Auth::user()->role === 'Administrator') {
            abort(403, 'Administrators cannot create memo requests. They can only review requests submitted by staff.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'form_files' => 'required|array|min:1',
            'form_files.*' => 'file|mimes:pdf|max:10240', // Max 10MB per file
        ]);

        // Handle file uploads
        $formFiles = [];
        if ($request->hasFile('form_files')) {
            foreach ($request->file('form_files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('memo-forms', $filename, 'public');
                $formFiles[] = $path;
            }
        }

        MemoRequest::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'form_files' => $formFiles,
            'status' => 'submitted',
        ]);

        return redirect()->route('memo.index')->with('success', 'Memo request submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MemoRequest $memo)
    {
        // Users can only view their own memo requests, unless they are admin/head of section
        if (Auth::user()->role !== 'Administrator' && Auth::user()->role !== 'Head Of Section' && $memo->user_id !== Auth::id()) {
            abort(403, 'You can only view your own memo requests.');
        }

        return view('memo.show', compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemoRequest $memo)
    {
        // Only the creator can edit, and only if it's not yet reviewed
        if ($memo->user_id !== Auth::id() || $memo->status !== 'submitted') {
            abort(403, 'You can only edit your own memo requests that are still submitted.');
        }

        return view('memo.edit', compact('memo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemoRequest $memo)
    {
        // Only the creator can edit, and only if it's not yet reviewed
        if ($memo->user_id !== Auth::id() || $memo->status !== 'submitted') {
            abort(403, 'You can only edit your own memo requests that are still submitted.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'form_files' => 'nullable|array',
            'form_files.*' => 'file|mimes:pdf|max:10240',
        ]);

        // Handle file uploads
        $formFiles = $memo->form_files ?? [];
        if ($request->hasFile('form_files')) {
            // Delete old files
            foreach ($formFiles as $file) {
                Storage::disk('public')->delete($file);
            }
            
            $formFiles = [];
            foreach ($request->file('form_files') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('memo-forms', $filename, 'public');
                $formFiles[] = $path;
            }
        }

        $memo->update([
            'title' => $request->title,
            'description' => $request->description,
            'form_files' => $formFiles,
        ]);

        return redirect()->route('memo.show', $memo)->with('success', 'Memo request updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemoRequest $memo)
    {
        // Only the creator can delete, and only if it's not yet reviewed
        if ($memo->user_id !== Auth::id() || $memo->status !== 'submitted') {
            abort(403, 'You can only delete your own memo requests that are still submitted.');
        }

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

        return redirect()->route('memo.index')->with('success', 'Memo request deleted successfully!');
    }

    /**
     * Download a form file
     */
    public function downloadForm(MemoRequest $memo, $fileIndex)
    {
        // Users can only download files from their own memo requests, unless they are admin/head of section
        if (Auth::user()->role !== 'Administrator' && Auth::user()->role !== 'Head Of Section' && $memo->user_id !== Auth::id()) {
            abort(403, 'You can only download files from your own memo requests.');
        }

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

    /**
     * Download the generated memo PDF
     */
    public function downloadMemo(MemoRequest $memo)
    {
        // Users can only download their own memo files, unless they are admin/head of section
        if (Auth::user()->role !== 'Administrator' && Auth::user()->role !== 'Head Of Section' && $memo->user_id !== Auth::id()) {
            abort(403, 'You can only download your own memo files.');
        }

        if (!$memo->memo_file || !Storage::disk('public')->exists($memo->memo_file)) {
            abort(404, 'Memo file not found.');
        }

        return Storage::disk('public')->download($memo->memo_file);
    }
}
