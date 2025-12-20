<?php

namespace App\Http\Controllers;

use App\Models\CpdApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CpdController extends Controller
{
    public function index(Request $request)
    {
        // If admin, redirect to admin review panel
        if (Auth::user()->role === 'Administrator') {
            return redirect()->route('admin.cpd.index');
        } elseif (Auth::user()->role === 'Head Of Section') {
            // If Head Of Section, redirect to Head Of Section review panel
            return redirect()->route('headofsection.cpd.index');
        } else {
            // Staff can only see their own applications
            $query = Auth::user()->cpdApplications()->orderBy('created_at', 'desc');

            // Apply search filters
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('event_title', 'like', "%$search%")
                      ->orWhere('event_type', 'like', "%$search%")
                      ->orWhere('venue', 'like', "%$search%")
                      ->orWhere('host_country', 'like', "%$search%")
                      ->orWhere('organiser_name', 'like', "%$search%");
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
        }
        return view('cpd.index', compact('applications'));
    }

    public function create()
    {
        // Prevent admins from creating CPD applications
        if (Auth::user()->role === 'Administrator') {
            abort(403, 'Administrators cannot create CPD applications. They can only review applications submitted by staff.');
        }
        
        $user = Auth::user();
        return view('cpd.create', compact('user'));
    }

    public function store(Request $request)
    {
        // Prevent admins from creating CPD applications
        if (Auth::user()->role === 'Administrator') {
            abort(403, 'Administrators cannot create CPD applications. They can only review applications submitted by staff.');
        }

        // Base validation rules
        $validationRules = [
            'ic_number' => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'post' => 'required|string|max:255',
            'programme_area' => 'required|string|max:255',
            'faculty_school_centre_section' => 'required|string|max:255',
            'event_type' => 'required|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'event_title' => 'required|string|max:500|min:5|regex:/^[a-zA-Z0-9\s\-_.,()]+$/',
            'event_date' => 'required|date|after:today',
            'venue' => 'required|string|max:255',
            'host_country' => 'required|string|max:255',
            'organiser_name' => 'required|string|max:255',
            'registration_fee' => 'nullable|numeric|min:0',
            'registration_fee_deadline' => 'nullable|date|after:today',
            'registration_fee_includes' => 'nullable|string',
            'other_benefits' => 'nullable|string',
            'paper_title_1' => 'nullable|string|max:500',
            'is_first_author_1' => 'boolean',
            'paper_title_2' => 'nullable|string|max:500',
            'is_first_author_2' => 'boolean',
            'relevance_to_post' => 'required|string',
            'expectations_upon_completion' => 'required|string',
            'publication_name' => 'nullable|string|max:255',
            'previous_event_type' => 'nullable|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'previous_event_title' => 'nullable|string|max:500',
            'previous_event_date' => 'nullable|date|before:today',
            'previous_event_venue' => 'nullable|string|max:255',
            'paper_published' => 'boolean',
            'publication_details' => 'nullable|string',
            'report_submitted' => 'boolean',
            'airfare_provided_by_organiser' => 'boolean',
            'flight_route' => 'nullable|string|max:255',
            'departure_date' => 'nullable|date|after:today',
            'arrival_date' => 'nullable|date|after:today',
            'ticket_purchase_preference' => 'nullable|in:cpd_secretariat,purchase_self_submit,purchase_self_reimburse',
            'accommodation_provided_by_organiser' => 'boolean',
            'hotel_name_address' => 'nullable|string',
            'accommodation_similar_to_venue' => 'boolean',
            'airport_transfer_provided' => 'boolean',
            'daily_transportation_provided' => 'boolean',
            'daily_allowance_provided' => 'boolean',
            'allowance_amount' => 'nullable|string|max:255',
            'visa_required' => 'boolean',
            'contributed_to_consultancy_fund' => 'boolean',
            'contribution_amount' => 'nullable|numeric|min:0',
            'entitlement_amount' => 'nullable|numeric|min:0',
            'fund_purpose' => 'nullable|string',
            // File upload validation
            'event_brochure' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'paper_abstract' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'previous_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'publication_paper' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'travel_documents' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'accommodation_details' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'budget_breakdown' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'other_documents' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];

        // Add conditional validation for registration fee fields based on event type
        if ($request->event_type !== 'Meeting') {
            $validationRules['payment_preference'] = 'required|in:bursar_office,pay_first_submit_receipt,pay_first_reimburse';
        } else {
            $validationRules['payment_preference'] = 'nullable|in:bursar_office,pay_first_submit_receipt,pay_first_reimburse';
        }

        $request->validate($validationRules);

        // Check for duplicate applications
        $existingApplication = CpdApplication::where('user_id', Auth::id())
            ->where('event_title', $request->event_title)
            ->where('event_date', $request->event_date)
            ->where('venue', $request->venue)
            ->where('host_country', $request->host_country)
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['event_title' => 'You have already submitted an application for this event. Please check your existing applications or contact an administrator if you need to make changes.']);
        }

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'submitted';
        $data['submitted_at'] = now();

        // Handle file uploads
        $fileFields = [
            'event_brochure',
            'paper_abstract',
            'previous_certificate',
            'publication_paper',
            'travel_documents',
            'accommodation_details',
            'budget_breakdown',
            'other_documents'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('cpd_documents', $filename, 'public');
                $data[$field] = $path;
            }
        }

        $application = CpdApplication::create($data);

        return redirect()->route('cpd.index')->with('success', 'CPD application submitted successfully! Your application has been submitted for review.');
    }

    public function show(CpdApplication $application)
    {
        // Ensure user can only view their own applications (unless admin)
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Load the recommendation relationship
        $application->load('recommendation.recommendedBy');

        return view('cpd.show', compact('application'));
    }

    public function edit(CpdApplication $application)
    {
        // Ensure user can only edit their own applications (unless admin)
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow editing if status is draft or rework
        if (!in_array($application->status, ['draft', 'rework'])) {
            return redirect()->route('cpd.show', $application)->with('error', 'Cannot edit applications that are not in draft or rework status.');
        }

        $user = Auth::user();
        return view('cpd.edit', compact('application', 'user'));
    }

    public function update(Request $request, CpdApplication $application)
    {
        // Ensure user can only update their own applications (unless admin)
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow updating if status is draft or rework
        if (!in_array($application->status, ['draft', 'rework'])) {
            return redirect()->route('cpd.show', $application)->with('error', 'Cannot edit applications that are not in draft or rework status.');
        }

        // Base validation rules
        $validationRules = [
            'ic_number' => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'post' => 'required|string|max:255',
            'programme_area' => 'required|string|max:255',
            'faculty_school_centre_section' => 'required|string|max:255',
            'event_type' => 'required|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'event_title' => 'required|string|max:500|min:5|regex:/^[a-zA-Z0-9\s\-_.,()]+$/',
            'event_date' => 'required|date|after:today',
            'venue' => 'required|string|max:255',
            'host_country' => 'required|string|max:255',
            'organiser_name' => 'required|string|max:255',
            'registration_fee' => 'nullable|numeric|min:0',
            'registration_fee_deadline' => 'nullable|date|after:today',
            'registration_fee_includes' => 'nullable|string',
            'other_benefits' => 'nullable|string',
            'paper_title_1' => 'nullable|string|max:500',
            'is_first_author_1' => 'boolean',
            'paper_title_2' => 'nullable|string|max:500',
            'is_first_author_2' => 'boolean',
            'relevance_to_post' => 'required|string',
            'expectations_upon_completion' => 'required|string',
            'publication_name' => 'nullable|string|max:255',
            'previous_event_type' => 'nullable|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'previous_event_title' => 'nullable|string|max:500',
            'previous_event_date' => 'nullable|date|before:today',
            'previous_event_venue' => 'nullable|string|max:255',
            'paper_published' => 'boolean',
            'publication_details' => 'nullable|string',
            'report_submitted' => 'boolean',
            'airfare_provided_by_organiser' => 'boolean',
            'flight_route' => 'nullable|string|max:255',
            'departure_date' => 'nullable|date|after:today',
            'arrival_date' => 'nullable|date|after:today',
            'ticket_purchase_preference' => 'nullable|in:cpd_secretariat,purchase_self_submit,purchase_self_reimburse',
            'accommodation_provided_by_organiser' => 'boolean',
            'hotel_name_address' => 'nullable|string',
            'accommodation_similar_to_venue' => 'boolean',
            'airport_transfer_provided' => 'boolean',
            'daily_transportation_provided' => 'boolean',
            'daily_allowance_provided' => 'boolean',
            'allowance_amount' => 'nullable|string|max:255',
            'visa_required' => 'boolean',
            'contributed_to_consultancy_fund' => 'boolean',
            'contribution_amount' => 'nullable|numeric|min:0',
            'entitlement_amount' => 'nullable|numeric|min:0',
            'fund_purpose' => 'nullable|string',
        ];

        // Add conditional validation for registration fee fields based on event type
        if ($request->event_type !== 'Meeting') {
            $validationRules['payment_preference'] = 'required|in:bursar_office,pay_first_submit_receipt,pay_first_reimburse';
        } else {
            $validationRules['payment_preference'] = 'nullable|in:bursar_office,pay_first_submit_receipt,pay_first_reimburse';
        }

        $request->validate($validationRules);

        // Check for duplicate applications (excluding the current application being updated)
        $existingApplication = CpdApplication::where('user_id', Auth::id())
            ->where('id', '!=', $application->id) // Exclude current application
            ->where('event_title', $request->event_title)
            ->where('event_date', $request->event_date)
            ->where('venue', $request->venue)
            ->where('host_country', $request->host_country)
            ->first();

        if ($existingApplication) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['event_title' => 'You have already submitted an application for this event. Please check your existing applications or contact an administrator if you need to make changes.']);
        }

        $data = $request->all();
        
        // If status was rework, change it back to draft after editing
        if ($application->status === 'rework') {
            $data['status'] = 'draft';
            $data['admin_notes'] = null; // Clear admin notes when staff edits
        }

        $application->update($data);

        $message = $application->status === 'draft' && $data['status'] === 'draft' 
            ? 'CPD application updated successfully! You can now submit it for review.' 
            : 'CPD application updated successfully!';

        return redirect()->route('cpd.show', $application)->with('success', $message);
    }

    public function submit(CpdApplication $application)
    {
        // Ensure user can only submit their own applications
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow submitting if status is draft
        if ($application->status !== 'draft') {
            return redirect()->route('cpd.show', $application)->with('error', 'Application has already been submitted.');
        }

        $application->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('cpd.show', $application)->with('success', 'CPD application submitted successfully!');
    }

    public function destroy(CpdApplication $application)
    {
        // Ensure user can only delete their own applications (unless admin)
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow deletion of applications in any status
        $application->delete();

        return redirect()->route('cpd.index')->with('success', 'CPD application deleted successfully!');
    }

    public function downloadPdf(CpdApplication $application)
    {
        // Ensure user can only download their own applications (unless admin)
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Allow downloading if status is approved or rejected
        if (!in_array($application->status, ['approved', 'rejected'])) {
            return redirect()->route('cpd.show', $application)->with('error', 'Cannot download applications that are not approved or rejected.');
        }

        // Create a clean filename from the event title
        $eventTitle = $application->event_title;
        $cleanTitle = preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $eventTitle); // Remove special characters
        $cleanTitle = preg_replace('/\s+/', '_', trim($cleanTitle)); // Replace spaces with underscores
        $cleanTitle = substr($cleanTitle, 0, 50); // Limit length to avoid filename issues
        
        $filename = 'CPD_Application_' . $cleanTitle . '_' . $application->id . '.pdf';

        $pdf = Pdf::loadView('cpd.pdf', compact('application'));
        return $pdf->download($filename);
    }

    public function rework(CpdApplication $application)
    {
        // Only allow staff to rework their own applications
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow rework if status is rejected or under_review
        if (!in_array($application->status, ['rejected', 'under_review'])) {
            return redirect()->route('cpd.show', $application)->with('error', 'Only rejected or under review applications can be reworked.');
        }

        // Change status to rework
        $application->update(['status' => 'rework']);

        return redirect()->route('cpd.show', $application)->with('success', 'Application has been marked for rework. You can now edit and resubmit it.');
    }

    public function resubmit(CpdApplication $application)
    {
        // Only allow staff to resubmit their own applications
        if (Auth::user()->role !== 'Administrator' && $application->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow resubmission if status is rework
        if ($application->status !== 'rework') {
            return redirect()->route('cpd.show', $application)->with('error', 'Only applications marked for rework can be resubmitted.');
        }

        // Change status to submitted
        $application->update(['status' => 'submitted']);

        return redirect()->route('cpd.show', $application)->with('success', 'Application has been resubmitted for review.');
    }
} 