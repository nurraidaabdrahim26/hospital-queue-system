<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Medica Hospital - Queue Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Our Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    
    <style>
        .header-nav {
            background: linear-gradient(135deg, #6A4FB0, #8E6FD8);
            padding: 0;
        }
        .nav-link-header {
            color: white !important;
            padding: 1rem 1.5rem;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        .nav-link-header:hover, .nav-link-header.active {
            background-color: rgba(255,255,255,0.15);
            border-bottom: 3px solid white;
        }
        .user-info {
            background: rgba(255,255,255,0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header with Navigation -->
    <header class="header-nav">
        <div class="container">
            <!-- Main Header -->
            <div class="text-center py-3">
                <h1 style="margin: 0; font-size: 1.8rem; font-weight: 600; color: white;">Aurora Medica Hospital</h1>
                <small style="opacity: 0.9; font-size: 1rem; color: white;">Queue Management System</small>
            </div>

            <!-- Navigation Menu -->
            @auth
            <nav class="navbar navbar-expand-lg justify-content-center">
                <div class="navbar-nav">
                    <a class="nav-link-header {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                    
                    @if(Auth::user()->isAdmin())
                        <a class="nav-link-header {{ Request::is('staff*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
                            <i class="fas fa-users me-1"></i>Staff Management
                        </a>
                        <a class="nav-link-header {{ Request::is('departments*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                            <i class="fas fa-building me-1"></i>Departments
                        </a>
                    @else
                        <a class="nav-link-header {{ Request::is('queues/create') ? 'active' : '' }}" href="{{ route('queues.create') }}">
                            <i class="fas fa-user-plus me-1"></i>Register Patient
                        </a>
                    @endif
                    
                    <a class="nav-link-header {{ Request::is('queues/manage') ? 'active' : '' }}" href="{{ route('queues.manage') }}">
                        <i class="fas fa-list me-1"></i>Queue Management
                    </a>

                    <!-- SMS Settings - Available for all authenticated users -->
                    <a class="nav-link-header {{ Request::is('sms*') ? 'active' : '' }}" href="{{ route('sms.dashboard') }}">
                        <i class="fas fa-sms me-1"></i>SMS
                    </a>

                    <!-- Logout button -->
                    <div class="logout-button">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light" style="border-width: 2px; background-color: transparent; box-shadow: none; font-size: 18px; margin-top: 10px;">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
            @endauth
        </div>
    </header>

    <!-- Main Content Area - Full Width -->
    <main class="container-fluid py-4" style="background-color: #F6F4FB; min-height: calc(100vh - 160px);">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer style="background-color: #6A4FB0; color: white; width: 100%; text-align: center; padding: 1rem;">
        <div class="container">
            <p style="margin: 0;">&copy; {{ date('Y') }} Aurora Medica Hospital. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>