<?php

namespace App\Http\Controllers;

use App\Models\CpdApplication;
use App\Models\CpdRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CpdRecommendationController extends Controller
{
    public function index(Request $request)
    {
        // Users can see recommendations they created OR recommendations for their applications
        $query = CpdRecommendation::with(['cpdApplication.user', 'recommendedBy'])
            ->where(function($q) {
                $q->where('recommended_by_user_id', Auth::id()) // Recommendations they created
                  ->orWhereHas('cpdApplication', function($appQuery) {
                      $appQuery->where('user_id', Auth::id()); // Recommendations for their applications
                  });
            })
            ->orderBy('created_at', 'desc');

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('applicant_name', 'like', "%$search%")
                  ->orWhere('event_title', 'like', "%$search%")
                  ->orWhere('event_type', 'like', "%$search%")
                  ->orWhere('applicant_post', 'like', "%$search%")
                  ->orWhere('programme_area', 'like', "%$search%")
                  ->orWhere('faculty_school_centre_section', 'like', "%$search%")
                  ->orWhereHas('cpdApplication.user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%$search%")
                               ->orWhere('first_name', 'like', "%$search%")
                               ->orWhere('last_name', 'like', "%$search%")
                               ->orWhere('email', 'like', "%$search%");
                  });
            });
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

        $recommendations = $query->paginate(15)->appends($request->query());

        return view('cpd.recommendations.index', compact('recommendations'));
    }

    public function create(CpdApplication $application)
    {
        // Only Head Of Section can create recommendations
        if (Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Only Head Of Section can create recommendations.');
        }

        // Check if recommendation already exists
        if ($application->recommendation) {
            return redirect()->route('cpd.recommendations.edit', $application->recommendation)
                ->with('warning', 'A recommendation already exists for this application.');
        }

        return view('cpd.recommendations.create', compact('application'));
    }

    public function store(Request $request, CpdApplication $application)
    {
        // Only Head Of Section can create recommendations
        if (Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Only Head Of Section can create recommendations.');
        }

        // Log the incoming request data for debugging
        \Log::info('Creating recommendation', [
            'request_data' => $request->all(),
            'application_id' => $application->id,
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_post' => 'required|string|max:255',
            'programme_area' => 'required|string|max:255',
            'faculty_school_centre_section' => 'required|string|max:255',
            'event_type' => 'required|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'event_title' => 'required|string|max:255|min:5|regex:/^[a-zA-Z0-9\s\-_.,()]+$/',
            'event_date' => 'required|date|after:today',
            'suitability_statement' => 'required|string',
            'reasons_for_recommendation' => 'required|string',
            'expectations_upon_completion' => 'required|string',
            'recommended_benefits_allowances' => 'required|string',
            'staff_recommended_to_discharge_duties' => 'required|string|max:255',
            'dean_comments_recommendation' => 'nullable|string',
            'has_contributed_to_consultancy_fund' => 'required|boolean',
            'consultancy_fund_details' => 'nullable|string',
            'recommend_use_consultancy_fund' => 'required|boolean',
            'consultancy_fund_rejection_reason' => 'nullable|string',
            'programme_leader_name' => 'required|string|max:255',
            'programme_leader_post' => 'required|string|max:255',
            'dean_name' => 'required|string|max:255',
            'dean_post' => 'required|string|max:255',
        ]);

        try {
            $recommendation = CpdRecommendation::create([
                'cpd_application_id' => $application->id,
                'recommended_by_user_id' => Auth::id(),
                'applicant_name' => $request->applicant_name,
                'applicant_post' => $request->applicant_post,
                'programme_area' => $request->programme_area,
                'faculty_school_centre_section' => $request->faculty_school_centre_section,
                'event_type' => $request->event_type,
                'event_title' => $request->event_title,
                'event_date' => $request->event_date,
                'suitability_statement' => $request->suitability_statement,
                'reasons_for_recommendation' => $request->reasons_for_recommendation,
                'expectations_upon_completion' => $request->expectations_upon_completion,
                'recommended_benefits_allowances' => $request->recommended_benefits_allowances,
                'staff_recommended_to_discharge_duties' => $request->staff_recommended_to_discharge_duties,
                'dean_comments_recommendation' => $request->dean_comments_recommendation,
                'has_contributed_to_consultancy_fund' => $request->has_contributed_to_consultancy_fund,
                'consultancy_fund_details' => $request->consultancy_fund_details,
                'recommend_use_consultancy_fund' => $request->recommend_use_consultancy_fund,
                'consultancy_fund_rejection_reason' => $request->consultancy_fund_rejection_reason,
                'programme_leader_name' => $request->programme_leader_name,
                'programme_leader_post' => $request->programme_leader_post,
                'dean_name' => $request->dean_name,
                'dean_post' => $request->dean_post,
            ]);

            // Log the created recommendation for debugging
            \Log::info('Recommendation created successfully', [
                'recommendation_id' => $recommendation->id,
                'applicant_name' => $recommendation->applicant_name,
                'event_title' => $recommendation->event_title,
                'event_type' => $recommendation->event_type
            ]);

            // Add debugging information
            if (empty($recommendation->applicant_name) || empty($recommendation->event_title)) {
                \Log::warning('Recommendation created with missing data', [
                    'recommendation_id' => $recommendation->id,
                    'applicant_name' => $recommendation->applicant_name,
                    'event_title' => $recommendation->event_title,
                    'request_data' => $request->all()
                ]);
            }

            return redirect()->route('cpd.recommendations.show', $recommendation)
                ->with('success', 'CPD Recommendation created successfully!');
        } catch (\Exception $e) {
            \Log::error('Error creating recommendation', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);
            
            return back()->withInput()->with('error', 'Error creating recommendation: ' . $e->getMessage());
        }
    }

    public function show(CpdRecommendation $recommendation)
    {
        // Load the necessary relationships
        $recommendation->load('cpdApplication.user', 'recommendedBy');
        
        // Users can view recommendations they created OR recommendations for their applications
        $canView = $recommendation->recommended_by_user_id === Auth::id() || 
                   $recommendation->cpdApplication->user_id === Auth::id();
        
        if (!$canView) {
            abort(403, 'Access denied. You can only view recommendations you created or recommendations for your applications.');
        }
        
        // Add debugging information
        if (empty($recommendation->applicant_name) || empty($recommendation->event_title)) {
            session()->flash('warning', 'Warning: This recommendation appears to have missing data. Please check if it was properly created.');
        }
        
        return view('cpd.recommendations.show', compact('recommendation'));
    }

    public function edit(CpdRecommendation $recommendation)
    {
        // Only Head Of Section can edit recommendations, and only their own
        if (Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Only Head Of Section can edit recommendations.');
        }

        if ($recommendation->recommended_by_user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only edit your own recommendations.');
        }

        return view('cpd.recommendations.edit', compact('recommendation'));
    }

    public function update(Request $request, CpdRecommendation $recommendation)
    {
        // Only Head Of Section can update recommendations, and only their own
        if (Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Only Head Of Section can update recommendations.');
        }

        if ($recommendation->recommended_by_user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only update your own recommendations.');
        }

        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_post' => 'required|string|max:255',
            'programme_area' => 'required|string|max:255',
            'faculty_school_centre_section' => 'required|string|max:255',
            'event_type' => 'required|in:Training Course,Meeting,Conference,Seminar,Workshop,Working Visit,Work Placement,Industrial Placement',
            'event_title' => 'required|string|max:255|min:5|regex:/^[a-zA-Z0-9\s\-_.,()]+$/',
            'event_date' => 'required|date|after:today',
            'suitability_statement' => 'required|string',
            'reasons_for_recommendation' => 'required|string',
            'expectations_upon_completion' => 'required|string',
            'recommended_benefits_allowances' => 'required|string',
            'staff_recommended_to_discharge_duties' => 'required|string|max:255',
            'dean_comments_recommendation' => 'nullable|string',
            'has_contributed_to_consultancy_fund' => 'required|boolean',
            'consultancy_fund_details' => 'nullable|string',
            'recommend_use_consultancy_fund' => 'required|boolean',
            'consultancy_fund_rejection_reason' => 'nullable|string',
            'programme_leader_name' => 'required|string|max:255',
            'programme_leader_post' => 'required|string|max:255',
            'dean_name' => 'required|string|max:255',
            'dean_post' => 'required|string|max:255',
        ]);

        $recommendation->update($request->all());

        return redirect()->route('cpd.recommendations.show', $recommendation)
            ->with('success', 'CPD Recommendation updated successfully!');
    }

    public function destroy(CpdRecommendation $recommendation)
    {
        // Only Head Of Section can delete recommendations, and only their own
        if (Auth::user()->role !== 'Head Of Section') {
            abort(403, 'Access denied. Only Head Of Section can delete recommendations.');
        }

        if ($recommendation->recommended_by_user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only delete your own recommendations.');
        }

        $recommendation->delete();

        return redirect()->route('cpd.recommendations.index')
            ->with('success', 'CPD Recommendation deleted successfully!');
    }
}
