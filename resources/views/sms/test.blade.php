@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background: #6A4FB0; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">🧪 Test SMS Service</h5>
                        <a href="{{ route('sms.dashboard') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✅ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ❌ {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Balance Info -->
                    <div class="alert alert-info mb-4">
                        <h6><i class="fas fa-wallet me-2"></i>SMS Balance</h6>
                        <p class="h4 mb-0">{{ $balance['currency'] }} {{ number_format($balance['amount'], 2) }}</p>
                    </div>

                    <!-- Test Form -->
                    <form method="POST" action="{{ route('sms.test.send') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                   value="{{ old('phone_number') }}" placeholder="+60123456789" required>
                            <div class="form-text">Include country code (e.g., +60 for Malaysia)</div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Test Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="4" 
                                      placeholder="Enter your test message here..." required>{{ old('message', 'This is a test SMS from the queue management system. If you receive this, SMS service is working properly.') }}</textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/160 characters
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Send Test SMS
                            </button>
                        </div>
                    </form>

                    <!-- Test Tips -->
                    <div class="mt-4">
                        <h6>Testing Tips:</h6>
                        <ul class="text-muted">
                            <li>Use a real phone number to verify delivery</li>
                            <li>Check the SMS history page for delivery status</li>
                            <li>Test both short and long messages</li>
                            <li>Verify the sender ID appears correctly</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    function updateCharCount() {
        const length = messageInput.value.length;
        charCount.textContent = length;
        
        if (length > 160) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    }
    
    messageInput.addEventListener('input', updateCharCount);
    updateCharCount(); // Initial count
});
</script>
@endsection
@endsection