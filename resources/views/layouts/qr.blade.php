<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aurora Medica Hospital - Queue Check</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Our Theme CSS -->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">
    
    <!-- QR Page Specific CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #F6F4FB 0%, #e8e2f7 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-container {
            max-width: 100%;
            width: 100%;
            padding: 20px;
        }
        .hospital-brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .hospital-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #6A4FB0, #8E6FD8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
        }
        .hospital-icon span {
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }
        .hospital-name {
            color: #6A4FB0;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .hospital-tagline {
            color: #8E6FD8;
            font-style: italic;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Main Content Only - No Header, No Footer -->
    <div class="qr-container">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>