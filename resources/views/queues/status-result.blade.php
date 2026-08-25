@extends('layouts.qr')

@section('content')
<div class="text-center">

    <!-- Hospital Branding -->
    <div class="hospital-brand" style="text-align: center; margin-bottom: 2rem;">
        <div class="d-flex justify-content-center mb-3">
            <div class="hospital-logo">
                <img src="{{ asset('images/hospital_logo.png') }}" alt="Aurora Medica Hospital" 
                     style="height: 120px; width: auto;">
            </div>
        </div>
        <h2 class="hospital-name" style="color: #6A4FB0; font-weight: 700; margin-bottom: 0.3rem;">
            Aurora Medica Hospital
        </h2>
        <p class="hospital-tagline" style="color: #8E6FD8; font-style: italic; margin-bottom: 1.5rem;">
            Care. Compassion. Clarity.
        </p>
    </div>

    <!-- Combined Container (Queue Number + Patient Info + Queue Status) -->
    <div class="card mobile-card bg-white" 
         style="max-width: 550px; margin: 0 auto 1.5rem auto; border-radius: 20px; padding: 1.8rem;">

        <!-- Queue Number -->
        <h6 class="mb-2" style="color: #6A4FB0; font-weight: 600;">Queue Number</h6>
        <div style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5); padding: 1rem; border-radius: 12px;">
            <h1 class="mb-0" style="color: #6A4FB0; font-weight: 800; font-size: 2.4rem; letter-spacing: 2px;">
                {{ $queue->queue_number }}
            </h1>
        </div>

        <hr style="margin: 1.5rem 0; border-top: 1px solid #E5E5E5;">

        <!-- Patient Information -->
        <h6 class="mb-3" style="color: #6A4FB0; font-weight: 600;">Patient Information</h6>
        <div class="row text-start g-3">

            <div class="col-6">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">{{ $queue->patient_name }}</div>
                </div>

                <div class="info-item mt-3">
                    <div class="info-label">Department</div>
                    <div class="info-value">
                        <span class="badge" 
                              style="background: #E8E2F7; color: #6A4FB0; padding: 0.4rem 0.8rem; border-radius: 12px; font-size: 0.85rem;">
                            {{ $queue->department->name }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="info-item">
                    <div class="info-label">NRIC</div>
                    <div class="info-value" style="font-family: monospace;">
                        {{ $queue->patient_nric }}
                    </div>
                </div>

                <div class="info-item mt-3">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">
                        {{ $queue->patient_phone ?: 'Not provided' }}
                    </div>
                </div>
            </div>

        </div>

        <hr style="margin: 1.5rem 0; border-top: 1px solid #E5E5E5;">

        <!-- Queue Status (People Ahead + Currently Serving) -->
        <h6 class="mb-3" style="color: #6A4FB0; font-weight: 600;">Queue Status</h6>

        <div class="row text-center g-3">

            <!-- People Ahead -->
            <div class="col-6">
                <div class="p-3 h-100" 
                     style="background: #FFF7DA; border-radius: 12px; border: 2px solid #FFC107;">
                    <h6 style="color: #856404; font-size: 0.9rem;">People Ahead</h6>
                    <p style="font-size: 1.8rem; font-weight: 700; color: #856404;">
                        {{ $position }}
                    </p>
                </div>
            </div>

            <!-- Currently Serving -->
            <div class="col-6">
                <div class="p-3 h-100" 
                     style="background: #DFF4F8; border-radius: 12px; border: 2px solid #17A2B8;">
                    <h6 style="color: #0C5460; font-size: 0.9rem;">Now Serving</h6>
                    <p style="font-size: 1.8rem; font-weight: 700; color: #0C5460;">
                        {{ $currentlyCalled ? $currentlyCalled->queue_number : '---' }}
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card mobile-card bg-white" 
         style="max-width: 500px; margin: 0 auto 1.5rem auto; border-radius: 20px; padding: 1.5rem;">
        <div class="d-grid gap-2">
            <a href="{{ route('queues.check-status') }}" 
               class="btn btn-primary" 
               style="background: linear-gradient(135deg, #6A4FB0, #8E6FD8); border: none; border-radius: 12px;">
                Check Another Queue
            </a>

            {{-- <div class="row mt-2 g-2">
                <div class="col-6">
                    <a href="{{ route('queues.qr') }}" 
                       class="btn btn-outline-primary w-100" 
                       style="border: 2px solid #6A4FB0; color: #6A4FB0; border-radius: 12px;">
                        QR Scanner
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('queues.create') }}" 
                       class="btn btn-outline-success w-100" 
                       style="border: 2px solid #28a745; color: #28a745; border-radius: 12px;">
                        New Patient
                    </a>
                </div>
            </div> --}}
        </div>
    </div>

</div>

<style>
.mobile-card {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}
.mobile-card:hover {
    transform: translateY(-2px);
}
.info-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
.info-value {
    font-size: 0.95rem;
    font-weight: 500;
    color: #2E2B3A;
}
</style>

@section('scripts')
<script>
setInterval(function() {
    window.location.reload();
}, 30000);
</script>
@endsection

@endsection
