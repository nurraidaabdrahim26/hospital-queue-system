@extends('layouts.qr')

@section('content')
<div class="text-center">
   <!-- Hospital Branding -->
<div class="hospital-brand" style="text-align: center; margin-bottom: 2rem;">
    <div class="d-flex justify-content-center mb-3">
        <div class="hospital-logo">
            <img src="{{ asset('images/hospital_logo.png') }}" alt="Aurora Medica Hospital" 
                 style="height: 140px; width: auto;">
        </div>
    </div>
    <h2 class="hospital-name" style="color: #6A4FB0; font-weight: 700; margin-bottom: 0.5rem;">Aurora Medica Hospital</h2>
    <p class="hospital-tagline" style="color: #8E6FD8; font-style: italic; margin-bottom: 2rem;">Care. Compassion. Clarity.</p>
    </div>

    <!-- QR Code Section -->
    <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1rem;">
        <h5 class="mb-3" style="color: #6A4FB0;">Scan to Check Queue</h5>
        <div class="qr-code mb-3">
            {!! QrCode::size(200)->generate(route('queues.check-status')) !!}
        </div>
        <p class="text-muted small mb-0">
            Point your camera at the QR code 
        </p>
        <p class="text-muted small mb-0">
            <strong>OR</strong>
        </p>
        <p class="text-muted small mb-0">
            Take a photo to scan the QR code later
        </p>
    </div>

    <!-- Alternative Options -->
    {{-- <div class="card mobile-card bg-light" style="max-width: 400px; margin: 0 auto 2rem auto; border-radius: 20px; padding: 1.5rem;">
        <h6 class="mb-3" style="color: #6A4FB0;">Other Options</h6>
        <a href="{{ route('queues.check-status') }}" class="btn btn-primary btn-lg w-100 mb-2" style="background: #6A4FB0; border: none; padding: 12px;">
            📱 Check Queue Online
        </a>
        <p class="small text-muted mt-2 mb-0">
            Enter your NRIC to check your queue status
        </p>
    </div> --}}

    <!-- Contact Info -->
    <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto; border-radius: 20px; padding: 1.5rem;">
        <h6 class="mb-2" style="color: #6A4FB0;">📞 Contact Information</h6>
        <p class="small mb-1"><strong>Main Line:</strong> 03-1234 5678</p>
        <p class="small mb-1"><strong>Emergency:</strong> 03-1234 5679</p>
        <p class="small mb-1"><strong>Website:</strong> www.auroramedica.example</p>
        <p> </p>
        <p class="small mb-0"><strong>Open 24 Hours, Every Day.</strong></p>
    </div>
</div>

<style>
.mobile-card {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.btn-primary {
    background: linear-gradient(135deg, #6A4FB0, #8E6FD8);
    border: none;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(106, 79, 176, 0.3);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .hospital-name {
        font-size: 1.5rem;
    }
    
    .hospital-icon {
        width: 60px;
        height: 60px;
    }
    
    .hospital-icon span {
        font-size: 1.5rem;
    }
}
</style>
@endsection