@extends('layouts.app')

@section('page-title', 'HR Services - UTB HR Central')

@section('content')
<div class="content-card">
    <h2 style="text-align: center; margin-bottom: 30px; color: #2c3e50;">HR Services</h2>
    
    <!-- Memo Requests - Special Blue Card -->
    <div class="memo-requests-card" onclick="window.location.href='{{ 
        auth()->user()->role === 'Administrator' ? route('admin.memo.index') : 
        (auth()->user()->role === 'Head Of Section' ? route('headofsection.memo.index') : route('memo.index')) 
    }}'">
        <div class="memo-card-content">
            <div class="memo-icon">📝</div>
            <div class="memo-text">
                <h3>Memo Requests</h3>
                <p>
                    @if(auth()->user()->role === 'Administrator')
                        Review and approve memo requests from staff members
                    @elseif(auth()->user()->role === 'Head Of Section')
                        Review and approve memo requests from staff members
                    @else
                        Submit forms for memo approval and manage your requests
                    @endif
                </p>
            </div>
            <div class="memo-arrow">→</div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <!-- Visa & Pass / Dependent -->
        <div class="dashboard-button" onclick="window.location.href='{{ route('visa-pass.index') }}'">
            <div class="icon">🛂</div>
            <h3>Visa & Pass / Dependent</h3>
            <p>Visa and dependent pass services</p>
        </div>

        <!-- Insurance -->
        <div class="dashboard-button">
            <div class="icon">🛡️</div>
            <h3>Insurance</h3>
            <p>Insurance coverage and benefits</p>
        </div>

        <!-- Driving License -->
        <div class="dashboard-button">
            <div class="icon">🚗</div>
            <h3>Driving License</h3>
            <p>Driving license applications</p>
        </div>

        <!-- Fringe Benefits Application -->
        <div class="dashboard-button" onclick="showFringeBenefitsPopup()">
            <div class="icon">🎁</div>
            <h3>Fringe Benefits Applications</h3>
            <p>Additional benefits and perks</p>
        </div>
    </div>
</div>

<style>
        /* Memo Requests Card Styles */
        .memo-requests-card {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
            position: relative;
            overflow: hidden;
        }

        .memo-requests-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
            z-index: 0;
        }

        .memo-requests-card:hover::before {
            left: 100%;
        }

        .memo-requests-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(52, 152, 219, 0.4);
        }

        .memo-card-content {
            display: flex;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .memo-icon {
            font-size: 48px;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .memo-text {
            flex: 1;
        }

        .memo-text h3 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
            color: white;
        }

        .memo-text p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .memo-arrow {
            font-size: 24px;
            font-weight: bold;
            margin-left: 15px;
            transition: transform 0.3s ease;
        }

        .memo-requests-card:hover .memo-arrow {
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .memo-card-content {
                flex-direction: column;
                text-align: center;
            }
            
            .memo-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .memo-arrow {
                margin-left: 0;
                margin-top: 10px;
            }
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
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

        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

/* Popup Styles */
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.popup-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.popup-title {
    color: #2c3e50;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
}

.popup-link {
    color: #3498db;
    font-size: 18px;
    font-weight: 600;
    text-decoration: none;
    display: block;
    margin: 15px 0;
    padding: 10px;
    border: 2px solid #3498db;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.popup-link:hover {
    background: #3498db;
    color: white;
}

.popup-close {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    margin-top: 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.popup-close::before {
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

.popup-close:hover::before {
    left: 100%;
}

.popup-close:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
}
</style>

<!-- Popup HTML -->
<div class="popup-overlay" id="fringeBenefitsPopup">
    <div class="popup-content">
        <h3 class="popup-title">Fringe Benefits Application</h3>
        <p style="color: #2c3e50; margin-bottom: 20px;">Please Press this link to proceed:</p>
        <a href="https://ssm.gov.bn" target="_blank" class="popup-link">ssm.gov.bn</a>
        <button class="popup-close" onclick="closeFringeBenefitsPopup()">Close</button>
    </div>
</div>

<script>
function showFringeBenefitsPopup() {
    document.getElementById('fringeBenefitsPopup').style.display = 'flex';
}

function closeFringeBenefitsPopup() {
    document.getElementById('fringeBenefitsPopup').style.display = 'none';
}

// Close popup when clicking outside of it
document.getElementById('fringeBenefitsPopup').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFringeBenefitsPopup();
    }
});

// Close popup with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFringeBenefitsPopup();
    }
});
</script>
@endsection 