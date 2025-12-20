<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address and password.'
            ]);
        }

        // Validate UTB email domain
        if (!str_ends_with($request->email, '@utb.edu.bn')) {
            return response()->json([
                'success' => false,
                'message' => 'Please use a valid UTB email address (@utb.edu.bn).'
            ]);
        }

        $credentials = $request->only('email', 'password');

        // Authenticate using email
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user is approved
            if (!$user->is_approved) {
                Auth::logout();
                return response()->json([
                    'success' => false,
                    'message' => 'Your account is pending approval. Please contact an administrator.'
                ]);
            }
            
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Login successful!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials. Please check your email and password.'
        ]);
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        // Validate UTB email domain
        if (!str_ends_with($request->email, '@utb.edu.bn')) {
            return response()->json([
                'success' => false,
                'message' => 'Please use a valid UTB email address (@utb.edu.bn).'
            ]);
        }

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Staff Member', // Default role
                'is_approved' => false, // Requires admin approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Please wait for administrator approval before you can log in.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account. Please try again.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user()
            ]);
        }

        return response()->json([
            'authenticated' => false
        ]);
    }
} 