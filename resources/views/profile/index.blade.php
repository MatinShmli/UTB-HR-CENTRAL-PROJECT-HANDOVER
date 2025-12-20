@extends('layouts.app')

@section('page-title', 'Profile - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <h2 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">Personal Profile</h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffeaa7;">
            {{ session('warning') }}
        </div>
    @endif

    <!-- Profile Completion Status - Only show if profile is incomplete -->
    @if(!$profileCompletion['isComplete'])
    <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px;">
        <h3 style="color: #2c3e50; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">
            Profile Completion Status
        </h3>
        
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
            <div style="flex: 1;">
                <div style="background: #ecf0f1; height: 20px; border-radius: 10px; overflow: hidden;">
                    <div style="background: #f39c12; height: 100%; width: {{ $profileCompletion['percentage'] }}%; transition: width 0.3s ease;"></div>
                </div>
            </div>
            <div style="font-weight: 600; color: #2c3e50; min-width: 80px;">
                {{ $profileCompletion['percentage'] }}%
            </div>
        </div>
        
        <p style="margin: 0; color: #7f8c8d; font-size: 14px;">
            {{ $profileCompletion['completed'] }} of {{ $profileCompletion['total'] }} required fields completed
                <span style="color: #e74c3c; font-weight: 600;"> - Please complete all required fields to access full system features.</span>
        </p>
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" style="max-width: 1400px; margin: 0 auto;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
            <!-- Personal Information Section -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Personal Information</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Full Name <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="full_name" value="{{ $user->full_name }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->full_name) ? 'border-color: #e74c3c;' : '' }}">
                    @error('full_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        IC Number <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="ic_number" value="{{ $user->ic_number }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->ic_number) ? 'border-color: #e74c3c;' : '' }}">
                    @error('ic_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        IC Colour <span style="color: #e74c3c;">*</span>
                    </label>
                    <select name="ic_color" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->ic_color) ? 'border-color: #e74c3c;' : '' }}">
                        <option value="">Select IC Colour</option>
                        <option value="Yellow" {{ $user->ic_color == 'Yellow' ? 'selected' : '' }}>Yellow</option>
                        <option value="Green" {{ $user->ic_color == 'Green' ? 'selected' : '' }}>Green</option>
                        <option value="Red" {{ $user->ic_color == 'Red' ? 'selected' : '' }}>Red</option>
                        <option value="Purple" {{ $user->ic_color == 'Purple' ? 'selected' : '' }}>Purple</option>
                    </select>
                    @error('ic_color')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Sex <span style="color: #e74c3c;">*</span>
                    </label>
                    <select name="sex" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->sex) ? 'border-color: #e74c3c;' : '' }}">
                        <option value="">Select Sex</option>
                        <option value="Male" {{ $user->sex == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $user->sex == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('sex')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Marital Status <span style="color: #e74c3c;">*</span>
                    </label>
                    <select name="marital_status" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->marital_status) ? 'border-color: #e74c3c;' : '' }}">
                        <option value="">Select Marital Status</option>
                        <option value="Single" {{ $user->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Married" {{ $user->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                        <option value="Divorced" {{ $user->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                        <option value="Widowed" {{ $user->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    </select>
                    @error('marital_status')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Date of Birth <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="date" name="birthdate" value="{{ $user->birthdate && $user->birthdate !== '0000-00-00' ? $user->birthdate->format('Y-m-d') : '' }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->birthdate) || $user->birthdate === '0000-00-00' ? 'border-color: #e74c3c;' : '' }}">
                    @error('birthdate')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Place of Birth
                    </label>
                    <input type="text" name="place_of_birth" value="{{ $user->place_of_birth }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('place_of_birth')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Email <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ $user->email }}" required readonly
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; background: #f5f5f5;">
                    @error('email')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Mobile Phone Number <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="phone_number" value="{{ $user->phone_number }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->phone_number) ? 'border-color: #e74c3c;' : '' }}">
                    @error('phone_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Citizenship <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="citizenship" value="{{ $user->citizenship }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->citizenship) ? 'border-color: #e74c3c;' : '' }}">
                    @error('citizenship')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Address of Country of Origin
                    </label>
                    <textarea name="address_of_country_of_origin" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;">{{ $user->address_of_country_of_origin }}</textarea>
                    @error('address_of_country_of_origin')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Address in Brunei Darussalam <span style="color: #e74c3c;">*</span>
                    </label>
                    <textarea name="address_in_brunei" rows="3" required
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical; {{ empty($user->address_in_brunei) ? 'border-color: #e74c3c;' : '' }}">{{ $user->address_in_brunei }}</textarea>
                    @error('address_in_brunei')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Passport Number (Not Required)
                    </label>
                    <input type="text" name="passport_number" value="{{ $user->passport_number }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('passport_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Employment Information Section -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Employment Information</h3>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Position <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="position" value="{{ $user->position }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->position) ? 'border-color: #e74c3c;' : '' }}">
                    @error('position')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Faculty <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="faculty" value="{{ $user->faculty }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->faculty) ? 'border-color: #e74c3c;' : '' }}">
                    @error('faculty')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Department <span style="color: #e74c3c;">*</span>
                    </label>
                    <input type="text" name="department" value="{{ $user->department }}" required
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->department) ? 'border-color: #e74c3c;' : '' }}">
                    @error('department')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Employment Type <span style="color: #e74c3c;">*</span>
                    </label>
                    <select name="employment_type" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; {{ empty($user->employment_type) ? 'border-color: #e74c3c;' : '' }}">
                        <option value="">Select Employment Type</option>
                        <option value="Permanent" {{ $user->employment_type == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                        <option value="Contract" {{ $user->employment_type == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Temporary" {{ $user->employment_type == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        <option value="Part-time" {{ $user->employment_type == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    </select>
                    @error('employment_type')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Salary Scale
                    </label>
                    <input type="text" name="salary_scale" value="{{ $user->salary_scale }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('salary_scale')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Current Salary
                    </label>
                    <input type="number" name="current_salary" value="{{ $user->current_salary }}" step="0.01" min="0"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('current_salary')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Duration of Appointment (Contract Staff)
                    </label>
                    <input type="text" name="duration_of_appointment" value="{{ $user->duration_of_appointment }}" placeholder="e.g., 2 years, 6 months"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('duration_of_appointment')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Spouse Information Section -->
            <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Spouse Information</h3>
                <p style="color: #7f8c8d; font-size: 12px; margin-bottom: 20px; font-style: italic;">All fields are optional</p>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Full Name
                    </label>
                    <input type="text" name="spouse_full_name" value="{{ $user->spouse_full_name }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_full_name')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
        </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse IC Number
                    </label>
                    <input type="text" name="spouse_ic_number" value="{{ $user->spouse_ic_number }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_ic_number')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Citizenship
                </label>
                    <input type="text" name="spouse_citizenship" value="{{ $user->spouse_citizenship }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_citizenship')
                    <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                @enderror
            </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Workplace
                    </label>
                    <input type="text" name="spouse_workplace" value="{{ $user->spouse_workplace }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_workplace')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
        </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Position
                    </label>
                    <input type="text" name="spouse_position" value="{{ $user->spouse_position }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_position')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Department
                    </label>
                    <input type="text" name="spouse_department" value="{{ $user->spouse_department }}"
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;">
                    @error('spouse_department')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Address (Country of Origin)
                    </label>
                    <textarea name="spouse_address_country_of_origin" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;">{{ $user->spouse_address_country_of_origin }}</textarea>
                    @error('spouse_address_country_of_origin')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #2c3e50;">
                        Spouse Address (Brunei)
                    </label>
                    <textarea name="spouse_address_brunei" rows="3"
                              style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; resize: vertical;">{{ $user->spouse_address_brunei }}</textarea>
                    @error('spouse_address_brunei')
                        <span style="color: #e74c3c; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div style="text-align: center; margin-top: 30px;">
            <button type="submit" style="background: #3498db; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden;">
                Update Profile
            </button>
        </div>
    </form>
</div>

<style>
.content-card {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin: 20px;
}

/* Profile submit button hover effect */
button[type="submit"]::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
    z-index: 0;
    pointer-events: none;
}

button[type="submit"]:hover::before {
    left: 100%;
}

button[type="submit"]:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
}

@media (max-width: 1200px) {
    form {
        max-width: 100% !important;
    }
    
    form > div:first-child {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .content-card {
        margin: 10px;
        padding: 20px;
    }
    
    form > div:first-child {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection 