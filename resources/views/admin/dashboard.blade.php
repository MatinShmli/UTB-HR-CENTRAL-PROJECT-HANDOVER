@extends('layouts.app')

@section('title', 'Admin Dashboard - UTB HR Central')

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
    transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
    text-decoration: none;
    color: inherit;
    display: block;
    height: 280px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.dashboard-button:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
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

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="content-card">
    
    
    <div class="dashboard-grid">
        <!-- HR Services -->
        <a href="{{ route('new-employment') }}" class="dashboard-button" style="background: #e6e6fa;">
            <div class="icon">💼</div>
            <h3>HR Services</h3>
            <p>HR services and support</p>
        </a>

        <!-- Jobs -->
        <a href="{{ route('recruitment') }}" class="dashboard-button" style="background: #e6e6fa;">
            <div class="icon">👥</div>
            <h3>Jobs</h3>
            <p>View and manage job postings</p>
        </a>

        <!-- Continuous Professional Development (CPD) -->
        <a href="{{ route('admin.cpd.index') }}" class="dashboard-button" style="background: #e6e6fa;">
            <div class="icon">📚</div>
            <h3>Continuous Professional Development (CPD)</h3>
            <p>Review CPD applications</p>
        </a>

        <!-- Promotion -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
            <div class="coming-soon-label">Coming Soon</div>
            <div class="icon">📈</div>
            <h3>Promotion</h3>
            <p>Career advancement opportunities</p>
        </div>

        <!-- Migration -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
            <div class="coming-soon-label">Coming Soon</div>
            <div class="icon">🌍</div>
            <h3>Migration</h3>
            <p>Migration and relocation services</p>
        </div>

        <!-- Staff Excellence Awards -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
            <div class="coming-soon-label">Coming Soon</div>
            <div class="icon">🏆</div>
            <h3>Staff Excellence Awards</h3>
            <p>Recognition and excellence programs</p>
        </div>

        <!-- Medal & Investiture Orders -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
            <div class="coming-soon-label">Coming Soon</div>
            <div class="icon">🎖️</div>
            <h3>Medal & Investiture Orders</h3>
            <p>Honorary awards and recognition</p>
        </div>

        <!-- Leaving the University -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
            <div class="coming-soon-label">Coming Soon</div>
            <div class="icon">👋</div>
            <h3>Leaving the University</h3>
            <p>Exit procedures and documentation</p>
        </div>

        <!-- Academic Staff Workload Guidelines -->
        <div class="dashboard-button coming-soon" style="background: #e6e6fa;">
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
@endsection 