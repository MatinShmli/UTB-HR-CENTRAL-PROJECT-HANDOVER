@extends('layouts.app')

@section('title', 'Dashboard')

<style>
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    max-width: 1600px;
    margin: 0 auto;
}

.dashboard-button {
    background: #e6e6fa;
    color: #2c3e50;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    text-decoration: none;
    color: inherit;
    display: block;
    height: 280px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    position: relative;
}

.dashboard-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease;
    z-index: 0;
    pointer-events: none;
}

.dashboard-button::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border: 2px solid transparent;
    border-radius: 12px;
    background: linear-gradient(45deg, #9b59b6, #8e44ad, #9b59b6) border-box;
    -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: destination-out;
    mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.dashboard-button:hover::before {
    left: 100%;
}

.dashboard-button:hover::after {
    opacity: 1;
}

.dashboard-button:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(155, 89, 182, 0.3);
    background-color: #d8d8f0;
}

.dashboard-button .icon {
    font-size: 48px;
    margin-bottom: 18px;
    flex-shrink: 0;
}

.dashboard-button h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.2;
    max-height: 44px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.dashboard-button p {
    margin: 10px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
    line-height: 1.3;
    max-height: 36px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Special styling for the rectangular button */
.dashboard-button[style*="grid-column: span 2"] {
    height: 280px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.dashboard-button[style*="grid-column: span 2"]:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%) !important;
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
}

.dashboard-button[style*="grid-column: span 2"] .icon {
    color: white;
}

.dashboard-button[style*="grid-column: span 2"] h3 {
    color: white;
}

.dashboard-button[style*="grid-column: span 2"] p {
    color: rgba(255, 255, 255, 0.9);
}

.profile-notice {
    background: linear-gradient(135deg, #f39c12, #e67e22);
    color: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
    display: flex;
    align-items: center;
    gap: 15px;
}

.profile-notice .icon {
    font-size: 24px;
    flex-shrink: 0;
}

.profile-notice .content {
    flex: 1;
}

.profile-notice h3 {
    margin: 0 0 8px 0;
    font-size: 18px;
    font-weight: 600;
}

.profile-notice p {
    margin: 0;
    opacity: 0.9;
    font-size: 14px;
}

.profile-notice .btn {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
    overflow: hidden;
}

.profile-notice .btn::before {
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

.profile-notice .btn:hover::before {
    left: 100%;
}

.profile-notice .btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    
    .dashboard-button[style*="grid-column: span 2"] {
        grid-column: span 3;
    }
}

@media (max-width: 992px) {
    .dashboard-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .dashboard-button[style*="grid-column: span 2"] {
        grid-column: span 2;
    }
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .dashboard-button[style*="grid-column: span 2"] {
        grid-column: span 2;
    }
    
    .profile-notice {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .dashboard-button[style*="grid-column: span 2"] {
        grid-column: span 1;
    }
}

/* Coming Soon Styles */
.dashboard-button.coming-soon {
    background: #e9ecef !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    opacity: 0.7;
    position: relative;
}

.dashboard-button.coming-soon:hover {
    transform: none !important;
    box-shadow: none !important;
    background: #e9ecef !important;
}

.dashboard-button.coming-soon::before,
.dashboard-button.coming-soon::after {
    display: none !important;
}

.dashboard-button.coming-soon .icon {
    opacity: 0.5;
}

.dashboard-button.coming-soon h3,
.dashboard-button.coming-soon p {
    color: #6c757d !important;
}

.coming-soon-label {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: #ffc107;
    color: #212529;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    z-index: 10;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* Special handling for large coming soon button */
.dashboard-button.coming-soon[style*="grid-column: span 2"] {
    background: linear-gradient(135deg, #adb5bd 0%, #868e96 100%) !important;
    color: #6c757d !important;
}

.dashboard-button.coming-soon[style*="grid-column: span 2"]:hover {
    background: linear-gradient(135deg, #adb5bd 0%, #868e96 100%) !important;
    transform: none !important;
    box-shadow: none !important;
}

.dashboard-button.coming-soon[style*="grid-column: span 2"] .icon,
.dashboard-button.coming-soon[style*="grid-column: span 2"] h3,
.dashboard-button.coming-soon[style*="grid-column: span 2"] p {
    color: #6c757d !important;
}
</style>

@section('page-title', 'Dashboard')

@section('content')
@php
    $user = auth()->user();
    $profileCompletion = $user->getProfileCompletion();
    $profileComplete = $profileCompletion['isComplete'];
@endphp

@if(!$profileComplete && $user->role !== 'Administrator')
    <div class="profile-notice">
        <div class="icon">⚠️</div>
        <div class="content">
            <h3>Complete Your Profile</h3>
            <p>Please complete your personal information to access all system features. {{ $profileCompletion['completed'] }} of {{ $profileCompletion['total'] }} required fields completed.</p>
        </div>
        <a href="{{ route('profile') }}" class="btn">Update Profile</a>
    </div>
@endif

@if(auth()->user()->role === 'Administrator')
    {{-- Admin Dashboard Content --}}
    <div class="content-card">

        
        <div class="dashboard-grid">
            <!-- HR Services -->
            <a href="{{ route('new-employment') }}" class="dashboard-button">
                <div class="icon">💼</div>
                <h3>HR Services</h3>
                <p>HR services and support</p>
            </a>

            <!-- Jobs -->
            <a href="{{ route('recruitment') }}" class="dashboard-button">
                <div class="icon">👥</div>
                <h3>Jobs</h3>
                <p>View and manage job postings</p>
            </a>

            <!-- Continuous Professional Development (CPD) -->
            <a href="{{ route('cpd.index') }}" class="dashboard-button">
                <div class="icon">📚</div>
                <h3>Continuous Professional Development (CPD)</h3>
                <p>Professional development and training</p>
            </a>

            <!-- Promotion -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📈</div>
                <h3>Promotion</h3>
                <p>Career advancement opportunities</p>
            </div>

            <!-- Migration -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌍</div>
                <h3>Migration</h3>
                <p>Migration and relocation services</p>
            </div>

            <!-- Staff Excellence Awards -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🏆</div>
                <h3>Staff Excellence Awards</h3>
                <p>Recognition and excellence programs</p>
            </div>

            <!-- Medal & Investiture Orders -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🎖️</div>
                <h3>Medal & Investiture Orders</h3>
                <p>Honorary awards and recognition</p>
            </div>

            <!-- Leaving the University -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">👋</div>
                <h3>Leaving the University</h3>
                <p>Exit procedures and documentation</p>
            </div>

            <!-- Academic Staff Workload Guidelines -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📋</div>
                <h3>Academic Staff Workload Guidelines</h3>
                <p>Guidelines and policies for academic workload</p>
            </div>

            <!-- Budaya Integriti, Professionalisme Dan Etika Kerja -->
            <div class="dashboard-button coming-soon" style="grid-column: span 2; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌟</div>
                <h3>Budaya Integriti, Professionalisme Dan Etika Kerja: Pemacu Kecermelangan University Teknologi Brunei (UTB)</h3>
                <p>Integrity, Professionalism and Work Ethics: Driving Excellence at University Teknologi Brunei</p>
            </div>
        </div>
    </div>
@elseif(auth()->user()->role === 'Head Of Section')
    {{-- Head Of Section Dashboard Content --}}
    <div class="content-card">

        
        <div class="dashboard-grid">
            <!-- HR Services -->
            <a href="{{ route('new-employment') }}" class="dashboard-button">
                <div class="icon">💼</div>
                <h3>HR Services</h3>
                <p>HR services and support</p>
            </a>

            <!-- Jobs -->
            <a href="{{ route('recruitment') }}" class="dashboard-button">
                <div class="icon">👥</div>
                <h3>Jobs</h3>
                <p>View and manage job postings</p>
            </a>

            <!-- Continuous Professional Development (CPD) -->
            <a href="{{ route('headofsection.cpd.index') }}" class="dashboard-button">
                <div class="icon">📚</div>
                <h3>Continuous Professional Development (CPD)</h3>
                <p>View and manage all CPD applications</p>
            </a>

            <!-- Promotion -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📈</div>
                <h3>Promotion</h3>
                <p>Career advancement opportunities</p>
            </div>

            <!-- Migration -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌍</div>
                <h3>Migration</h3>
                <p>Migration and relocation services</p>
            </div>

            <!-- Staff Excellence Awards -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🏆</div>
                <h3>Staff Excellence Awards</h3>
                <p>Recognition and excellence programs</p>
            </div>

            <!-- Medal & Investiture Orders -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🎖️</div>
                <h3>Medal & Investiture Orders</h3>
                <p>Honorary awards and recognition</p>
            </div>

            <!-- Leaving the University -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">👋</div>
                <h3>Leaving the University</h3>
                <p>Exit procedures and documentation</p>
            </div>

            <!-- Academic Staff Workload Guidelines -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📋</div>
                <h3>Academic Staff Workload Guidelines</h3>
                <p>Guidelines and policies for academic workload</p>
            </div>

            <!-- Budaya Integriti, Professionalisme Dan Etika Kerja -->
            <div class="dashboard-button coming-soon" style="grid-column: span 2; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌟</div>
                <h3>Budaya Integriti, Professionalisme Dan Etika Kerja: Pemacu Kecermelangan University Teknologi Brunei (UTB)</h3>
                <p>Integrity, Professionalism and Work Ethics: Driving Excellence at University Teknologi Brunei</p>
            </div>
        </div>
    </div>
@else
    {{-- Staff Dashboard Content --}}
    <div class="content-card">

        
        <div class="dashboard-grid">
            <!-- HR Services -->
            <a href="{{ route('new-employment') }}" class="dashboard-button">
                <div class="icon">💼</div>
                <h3>HR Services</h3>
                <p>HR services and support</p>
            </a>

            <!-- Jobs -->
            <a href="{{ route('recruitment') }}" class="dashboard-button">
                <div class="icon">👥</div>
                <h3>Jobs</h3>
                <p>View and manage job postings</p>
            </a>

            <!-- Continuous Professional Development (CPD) -->
            <a href="{{ route('cpd.index') }}" class="dashboard-button">
                <div class="icon">📚</div>
                <h3>Continuous Professional Development (CPD)</h3>
                <p>Professional development and training</p>
            </a>

            <!-- Promotion -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📈</div>
                <h3>Promotion</h3>
                <p>Career advancement opportunities</p>
            </div>

            <!-- Migration -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌍</div>
                <h3>Migration</h3>
                <p>Migration and relocation services</p>
            </div>

            <!-- Staff Excellence Awards -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🏆</div>
                <h3>Staff Excellence Awards</h3>
                <p>Recognition and excellence programs</p>
            </div>

            <!-- Medal & Investiture Orders -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🎖️</div>
                <h3>Medal & Investiture Orders</h3>
                <p>Honorary awards and recognition</p>
            </div>

            <!-- Leaving the University -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">👋</div>
                <h3>Leaving the University</h3>
                <p>Exit procedures and documentation</p>
            </div>

            <!-- Academic Staff Workload Guidelines -->
            <div class="dashboard-button coming-soon">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">📋</div>
                <h3>Academic Staff Workload Guidelines</h3>
                <p>Guidelines and policies for academic workload</p>
            </div>

            <!-- Budaya Integriti, Professionalisme Dan Etika Kerja -->
            <div class="dashboard-button coming-soon" style="grid-column: span 2; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="coming-soon-label">Coming Soon</div>
                <div class="icon">🌟</div>
                <h3>Budaya Integriti, Professionalisme Dan Etika Kerja: Pemacu Kecermelangan University Teknologi Brunei (UTB)</h3>
                <p>Integrity, Professionalism and Work Ethics: Driving Excellence at University Teknologi Brunei</p>
            </div>
        </div>
    </div>
@endif
@endsection 