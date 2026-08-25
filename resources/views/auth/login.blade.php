@extends('layouts.app')

@section('content')
<div class="card" style="margin-top: 1rem;"> <!-- Shift content higher -->
    <div class="text-center">
        <!-- Hospital Logo and Title -->
        <div class="mb-4">
            <img src="{{ asset('images/hospital_logo.png') }}" alt="Aurora Medica Hospital" height="120" width="auto">
        </div>
        <h3 style="color: #6A4FB0;">Staff Login</h3>
        <p class="text-muted">Enter your credentials to access the hospital system</p>
    </div>

    <div class="divider"></div>

    <form method="POST" action="{{ url('/login') }}" id="loginForm">
        @csrf
        
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                   id="username" name="username" value="{{ old('username') }}" required autofocus
                   placeholder="Enter your username">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                   id="password" name="password" required
                   placeholder="Enter your password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg" style="background: #6A4FB0; border: none;">
                Login
            </button>
        </div>
    </form>

    <!-- Default credentials reminder -->
    {{-- <div class="text-center mt-4">
        <p class="footer-text" style="font-size: 0.85rem;">
            <strong>Default credentials for testing:</strong><br>
            Admin: <strong>admin / password123</strong><br>
            Staff: <strong>johndoctor / password123</strong>
        </p>
    </div> --}}
</div>

<style>
/* Adjust the main container to push content higher */
.login-container {
    min-height: 87vh !important;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Ensure footer stays lower */
footer {
    margin-top: auto;
    position: relative;
    bottom: 0;
    width: 100%;
}
</style>
@endsection