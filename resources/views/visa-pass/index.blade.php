@extends('layouts.app')

@section('page-title', 'Visa & Pass / Dependent - UTB HR Central')

@section('content')
@php
    $showBackButton = true;
@endphp
<div class="content-card">
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #2c3e50; margin-bottom: 10px;">🛂 Visa & Pass / Dependent</h2>
        <p style="color: #7f8c8d; font-size: 16px;">Download the required forms for visa and dependent pass applications</p>
    </div>
    
    <div class="pdf-download-grid">
        <!-- Borang Permohonan Visa -->
        <div class="pdf-card">
            <div class="pdf-icon">📋</div>
            <h3>Borang Permohonan Visa</h3>
            <p>Visa application form for Brunei Darussalam</p>
            <a href="{{ route('visa-pass.download', 'borang-permohonan-visa') }}" class="download-btn">
                <span class="download-icon">⬇️</span>
                Download PDF
            </a>
        </div>

        <!-- Permohonan Bagi Satu Pas Tanggungan -->
        <div class="pdf-card">
            <div class="pdf-icon">👨‍👩‍👧‍👦</div>
            <h3>Permohonan Bagi Satu Pas Tanggungan</h3>
            <p>Dependent pass application form (Borang 25)</p>
            <a href="{{ route('visa-pass.download', 'permohonan-pas-tanggungan') }}" class="download-btn">
                <span class="download-icon">⬇️</span>
                Download PDF
            </a>
        </div>

        <!-- Permohonan Kebenaran Tinggal Sementara -->
        <div class="pdf-card">
            <div class="pdf-icon">🏠</div>
            <h3>Permohonan Kebenaran Tinggal Sementara</h3>
            <p>Temporary residence permit application form (Borang 8)</p>
            <a href="{{ route('visa-pass.download', 'permohonan-kebenaran-tinggal') }}" class="download-btn">
                <span class="download-icon">⬇️</span>
                Download PDF
            </a>
        </div>
    </div>

    <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3498db;">
        <h4 style="color: #2c3e50; margin-bottom: 10px;">📝 Important Notes:</h4>
        <ul style="color: #7f8c8d; margin-left: 20px;">
            <li>All forms are available in PDF format</li>
            <li>You can download multiple forms as needed</li>
            <li>Please fill out the forms completely before submission</li>
            <li>Contact HR department for assistance with form completion</li>
        </ul>
    </div>
</div>

<style>
.pdf-download-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.pdf-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.pdf-card::before {
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

.pdf-card:hover::before {
    left: 100%;
}

.pdf-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 35px rgba(52, 152, 219, 0.2);
    border-color: #3498db;
}

.pdf-icon {
    font-size: 48px;
    margin-bottom: 20px;
    display: block;
    position: relative;
    z-index: 1;
}

.pdf-card h3 {
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 15px;
    line-height: 1.3;
    position: relative;
    z-index: 1;
}

.pdf-card p {
    color: #7f8c8d;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 25px;
    position: relative;
    z-index: 1;
}

.download-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
    text-decoration: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.download-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
    z-index: -1;
}

.download-btn:hover::before {
    left: 100%;
}

.download-btn:hover {
    background: linear-gradient(135deg, #2980b9 0%, #1f618d 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.4);
    color: white;
}

.download-icon {
    font-size: 16px;
}

@media (max-width: 768px) {
    .pdf-download-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .pdf-card {
        padding: 25px;
    }
    
    .pdf-icon {
        font-size: 40px;
    }
}
</style>
@endsection
