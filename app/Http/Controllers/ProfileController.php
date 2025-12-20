<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profileCompletion = $user->getProfileCompletion();
        return view('profile.index', compact('user', 'profileCompletion'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            // Personal Information - Required
            'full_name' => 'required|string|max:255',
            'ic_number' => 'required|string|max:255',
            'ic_color' => 'required|string|max:255',
            'sex' => 'required|in:Male,Female',
            'marital_status' => 'required|string|max:255',
            'birthdate' => 'required|date_format:Y-m-d|before:today',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255',
            'citizenship' => 'required|string|max:255',
            'address_in_brunei' => 'required|string|max:1000',
            
            // Personal Information - Optional
            'passport_number' => 'nullable|string|max:255',
            'place_of_birth' => 'nullable|string|max:255',
            'address_of_country_of_origin' => 'nullable|string|max:1000',
            
            // Employment Information - Required
            'position' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            
            // Employment Information - Optional
            'salary_scale' => 'nullable|string|max:255',
            'current_salary' => 'nullable|numeric|min:0',
            'duration_of_appointment' => 'nullable|string|max:255',
            
            // Spouse Information - All Optional
            'spouse_full_name' => 'nullable|string|max:255',
            'spouse_ic_number' => 'nullable|string|max:255',
            'spouse_citizenship' => 'nullable|string|max:255',
            'spouse_workplace' => 'nullable|string|max:255',
            'spouse_position' => 'nullable|string|max:255',
            'spouse_department' => 'nullable|string|max:255',
            'spouse_address_country_of_origin' => 'nullable|string|max:1000',
            'spouse_address_brunei' => 'nullable|string|max:1000',
        ]);

        $user->update($request->only([
            'full_name',
            'ic_number',
            'ic_color',
            'sex',
            'marital_status',
            'birthdate',
            'place_of_birth',
            'email',
            'phone_number',
            'citizenship',
            'address_of_country_of_origin',
            'address_in_brunei',
            'passport_number',
            'position',
            'faculty',
            'department',
            'employment_type',
            'salary_scale',
            'current_salary',
            'duration_of_appointment',
            'spouse_full_name',
            'spouse_ic_number',
            'spouse_citizenship',
            'spouse_workplace',
            'spouse_position',
            'spouse_department',
            'spouse_address_country_of_origin',
            'spouse_address_brunei',
        ]));

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
