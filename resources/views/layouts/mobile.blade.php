<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Queue System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Our Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    
    <!-- Mobile-specific CSS -->
    <style>
        .mobile-container {
            max-width: 100%;
            padding: 15px;
        }
        .mobile-card {
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        .mobile-btn {
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 12px;
            width: 100%;
            margin-bottom: 10px;
        }
        .mobile-header {
            background: linear-gradient(135deg, #6A4FB0, #8E6FD8);
            color: white;
            padding: 1rem;
            border-radius: 0 0 20px 20px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header text-center">
        <h4 class="mb-0">🏥 Hospital Queue</h4>
        <small>Patient Portal</small>
    </div>

    <!-- Main Content -->
    <main class="mobile-container">
        @yield('content')
    </main>

    <!-- Mobile Footer -->
    <footer class="text-center mt-4 p-3">
        <small class="text-muted">
            &copy; {{ date('Y') }} Hospital Queue System
        </small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>