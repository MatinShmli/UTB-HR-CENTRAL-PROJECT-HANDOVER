<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMS')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f5f5;
            overflow-x: hidden;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Side Panel */
        .sidebar {
            width: 220px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 12px;
            opacity: 0.7;
        }

        .sidebar-nav {
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 100px);
        }

        .nav-section {
            margin-bottom: 30px;
        }

        .nav-section-title {
            padding: 0 15px 10px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0.7;
            letter-spacing: 1px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            white-space: nowrap;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #3498db;
        }

        .nav-item.active {
            background: rgba(52, 152, 219, 0.2);
            color: white;
            border-left-color: #3498db;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            opacity: 0.8;
        }

        .nav-text {
            font-size: 13px;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 220px;
            min-height: 100vh;
            background: #f8f9fa;
        }

        .top-bar {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .top-bar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .user-role {
            font-size: 12px;
            color: #6c757d;
        }

        .logout-btn {
            padding: 8px 16px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
        }

        .content-area {
            padding: 30px;
        }

        .content-card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                font-size: 20px;
                color: #2c3e50;
                cursor: pointer;
            }

            .content-area {
                padding: 20px;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.open {
            display: block;
        }

        .logout-button:hover {
            background: #c0392b !important;
        }

        /* Global Button Hover Effects */
        .btn-primary, .btn-secondary, .btn-danger, .btn-success, .btn, button[type="submit"], button[type="button"] {
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before, .btn-secondary::before, .btn-danger::before, .btn-success::before, .btn::before, button[type="submit"]::before, button[type="button"]::before {
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

        .btn-primary:hover::before, .btn-secondary:hover::before, .btn-danger:hover::before, .btn-success:hover::before, .btn:hover::before, button[type="submit"]:hover::before, button[type="button"]:hover::before {
            left: 100%;
        }

        /* Enhanced hover effects for different button types */
        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        .btn-success:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
        }

        /* Logout button specific enhancement */
        .logout-button {
            position: relative;
            overflow: hidden;
        }

        .logout-button::before {
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

        .logout-button:hover::before {
            left: 100%;
        }

        .logout-button:hover {
            background: #c0392b !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
        }

        /* Back Button Styles */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2);
        }

        .back-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
            z-index: 0;
        }

        .back-button:hover::before {
            left: 100%;
        }

        .back-button:hover {
            background: linear-gradient(135deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
            color: white;
            text-decoration: none;
        }

        .back-button .back-icon {
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        .back-button .back-text {
            position: relative;
            z-index: 1;
        }

        .back-button-container {
            margin-bottom: 20px;
        }

        /* Make pagination arrows smaller - Global styles */
        .pagination {
            font-size: 14px;
        }

        .pagination .page-link,
        .pagination a,
        .pagination span {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
            line-height: 1.5 !important;
            min-height: auto !important;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link,
        .pagination > :first-child,
        .pagination > :last-child {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
        }

        .pagination svg {
            width: 14px !important;
            height: 14px !important;
        }

        .pagination .relative {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
        }

        /* Target Laravel Tailwind pagination */
        .pagination nav a,
        .pagination nav span {
            padding: 0.25rem 0.5rem !important;
            font-size: 0.875rem !important;
        }

        .pagination nav svg {
            width: 14px !important;
            height: 14px !important;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Side Panel -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">{{ auth()->user()->name ?? 'Staff Member' }}</div>
                <div class="sidebar-subtitle">{{ auth()->user()->role ?? 'Administrator' }}</div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Homepage</span>
                    </a>
                    @if(auth()->user()->role !== 'Administrator')
                        <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <span class="nav-icon">👤</span>
                            <span class="nav-text">Profile</span>
                        </a>
                    @endif
                    @if(auth()->user()->role === 'Administrator')
                        <a href="{{ route('admin.pending-approvals') }}" class="nav-item {{ request()->routeIs('admin.pending-approvals') ? 'active' : '' }}">
                            <span class="nav-icon">🚨</span>
                            <span class="nav-text">Pending Approvals</span>
                        </a>
                        <a href="{{ route('admin.manage-users') }}" class="nav-item {{ request()->routeIs('admin.manage-users') ? 'active' : '' }}">
                            <span class="nav-icon">👥</span>
                            <span class="nav-text">User Management</span>
                        </a>
                    @endif
                </div>
                
                <div class="nav-section" style="margin-top: auto;">
                    <div class="nav-section-title">Account</div>
                    <button onclick="logout()" class="logout-button" style="width: calc(100% - 40px); margin: 0 20px; padding: 12px 20px; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: background 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <span style="font-size: 16px;">🚪</span>
                        <span>Logout</span>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="top-bar">
                <div class="top-bar-left">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">☰</button>
                    <img src="{{ asset('images/HR-Central-Logo-400px.png') }}" alt="HR Central Logo" style="height: 40px; width: auto;">
                </div>
                
                <div class="user-menu">
                    <img src="{{ asset('images/utblogo.png') }}" alt="UTB Logo" style="height: 40px; width: auto; margin-right: 15px;">
                </div>
            </div>

            <div class="content-area">
                @if(isset($showBackButton) && $showBackButton)
                    <div class="back-button-container">
                        <a href="javascript:history.back()" class="back-button">
                            <span class="back-icon">⬅️</span>
                            <span class="back-text">Back to Previous Page</span>
                        </a>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Mobile Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '{{ route("logout") }}';
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(e.target) && !e.target.classList.contains('mobile-menu-btn')) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                }
            }
        });
    </script>
</body>
</html> 