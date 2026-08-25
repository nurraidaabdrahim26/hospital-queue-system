@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background: #6A4FB0; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📤 Send Single SMS</h5>
                        <a href="{{ route('sms.dashboard') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted">Send a custom SMS message to any phone number.</p>
                    
                    <form method="POST" action="{{ route('sms.send.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send SMS</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection