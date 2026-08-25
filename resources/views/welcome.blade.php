@extends('layouts.app')

@section('content')
<div class="card text-center">
    <div class="hospital-logo">
        <h1>🏥 Welcome to Hospital Queue System</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h4>For Patients</h4>
                    <p>Scan QR code to check your queue status</p>
                    
                    <!-- Smaller QR Code for home page -->
                    <div class="qr-code">
                        {!! QrCode::size(150)->generate(route('queues.check-status')) !!}
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('queues.check-status') }}" class="btn btn-primary">
                            Check Queue Status
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-light">
                <div class="card-body">
                    <h4>For Staff</h4>
                    <p>Access the staff dashboard to manage queues</p>
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                            Staff Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5>📞 Contact Information</h5>
                <p class="mb-1"><strong>Phone:</strong> 03-1234 5678</p>
                <p class="mb-1"><strong>Emergency:</strong> 03-1234 5679</p>
                <p class="mb-0"><strong>Address:</strong> 123 Medical Street, Kuala Lumpur</p>
            </div>
        </div>
    </div>
</div>
@endsection