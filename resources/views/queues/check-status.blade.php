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

    <!-- Error Alert -->
    @if(session('error'))
        <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1rem;">
            <div class="alert alert-danger mb-0" style="border: none; background: #f8d7da; color: #721c24;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1rem;">
            <div class="alert alert-success mb-0" style="border: none; background: #d1edff; color: #155724;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- NRIC Input Form -->
    <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1.5rem;">
        <h5 class="mb-3" style="color: #6A4FB0;">Check Queue Status</h5>
        <p class="text-muted small mb-3">Enter your NRIC number below</p>
        
        <form method="POST" action="{{ route('queues.get-status') }}">
            @csrf
            
            <div class="mb-3 text-start">
                <label for="patient_nric" class="form-label small text-muted">NRIC Number</label>
                <input type="text" class="form-control" 
                       id="patient_nric" name="patient_nric" 
                       placeholder="e.g., 901231045678" 
                       required
                       style="text-align: center; font-size: 16px; padding: 12px; border-radius: 10px; border: 2px solid #E8E2F7;">
            </div>

            <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #6A4FB0, #8E6FD8); border: none; border-radius: 12px; padding: 12px; font-weight: 600;">
                🔍 Check My Queue
            </button>
        </form>
    </div>

    <!-- Queue Status Display -->
    @if(isset($queue) && $queue)
        <div class="card mobile-card bg-white" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1.5rem;">
            <h5 class="mb-3" style="color: #6A4FB0;">Your Queue Status</h5>
            
            <!-- Queue Number -->
            <div class="queue-number mb-3" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5); padding: 1.5rem; border-radius: 15px;">
                <h1 style="color: #6A4FB0; font-size: 3rem; font-weight: 700; margin: 0;">{{ $queue->queue_number }}</h1>
                <p class="text-muted small mb-0">Queue Number</p>
            </div>

            <!-- Queue Details -->
            <div class="queue-details text-start">
                <div class="row mb-2">
                    <div class="col-5"><strong>Patient:</strong></div>
                    <div class="col-7">{{ $queue->patient_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><strong>Department:</strong></div>
                    <div class="col-7">{{ $queue->department->name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-5"><strong>Status:</strong></div>
                    <div class="col-7">
                        <span class="badge 
                            {{ $queue->status == 'waiting' ? 'bg-warning' : '' }}
                            {{ $queue->status == 'called' ? 'bg-info' : '' }}
                            {{ $queue->status == 'completed' ? 'bg-success' : '' }}"
                            style="font-size: 0.8rem; padding: 0.4rem 0.75rem;">
                            {{ ucfirst($queue->status) }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5"><strong>Registered:</strong></div>
                    <div class="col-7">{{ $queue->created_at->format('h:i A') }}</div>
                </div>
            </div>
        </div>
    @endif

    <!-- Help Information -->
    <div class="card mobile-card bg-light" style="max-width: 400px; margin: 0 auto 1rem auto; border-radius: 20px; padding: 1.5rem;">
        <h6 class="mb-2" style="color: #6A4FB0;">ℹ️ Need Help?</h6>
        <p class="small text-muted mb-2">
            Your NRIC number is on your identification card. 
        </p>
        <p class="small text-muted mb-2">
            If you face any issues, please contact our reception desk for assistance.
        </p>
        <div class="d-grid gap-2">
            <a href="{{ route('queues.qr') }}" class="btn btn-outline-primary" style="border: 2px solid #6A4FB0; color: #6A4FB0; border-radius: 12px; padding: 10px;">
                ← Back to QR Code
            </a>
        </div>
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

.btn-outline-primary {
    border: 2px solid #6A4FB0;
    color: #6A4FB0;
    border-radius: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #6A4FB0;
    border-color: #6A4FB0;
    color: white;
    transform: translateY(-1px);
}

.form-control {
    border: 2px solid #E8E2F7;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #6A4FB0;
    box-shadow: 0 0 0 0.2rem rgba(106, 79, 176, 0.25);
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .hospital-name {
        font-size: 1.5rem;
    }
    
    .queue-number h1 {
        font-size: 2.5rem !important;
    }
}

/* Loading state */
.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nricInput = document.getElementById('patient_nric');
    
    // Auto-format NRIC input (digits only)
    nricInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 12) {
            value = value.substring(0, 12);
        }
        e.target.value = value;
    });
    
    // Form submission loading state
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ Checking...';
        
        // Re-enable after 5 seconds (safety net)
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 5000);
    });
    
    // Auto-focus on NRIC input
    nricInput.focus();
});
</script>
@endsection
@endsection