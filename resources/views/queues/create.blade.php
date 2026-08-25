@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center">
        <h3>📋 Register New Patient</h3>
        <p class="text-muted">Add patient to the queue system</p>
    </div>

    <div class="divider"></div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('queues.store') }}">
        @csrf
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="patient_name" class="form-label">Patient Name *</label>
                <input type="text" class="form-control" id="patient_name" name="patient_name" 
                       value="{{ old('patient_name') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="patient_nric" class="form-label">NRIC *</label>
                <input type="text" class="form-control" id="patient_nric" name="patient_nric" 
                       value="{{ old('patient_nric') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="patient_phone" class="form-label">Phone Number *</label>
                <input type="tel" class="form-control" id="patient_phone" name="patient_phone" 
                       value="{{ old('patient_phone') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="department_id" class="form-label">Department *</label>
                <select class="form-control" id="department_id" name="department_id" required
                    {{ Auth::user()->isAdmin() ? '' : 'disabled' }}>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" 
                            {{ (old('department_id', Auth::user()->department_id) == $department->id) ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                
                {{-- Hidden input to ensure department_id is always submitted --}}
                @if(!Auth::user()->isAdmin())
                    <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">
                @endif
                
                {{-- Display info for staff users --}}
                {{-- @if(!Auth::user()->isAdmin())
                    <div class="form-text text-info">
                        <i class="fas fa-info-circle"></i>
                        Automatically set to your assigned department: 
                        <strong>{{ Auth::user()->department->name ?? 'Your Department' }}</strong>
                    </div>
                @endif --}}
            </div>
        </div>

        <div class="mb-3">
            <label for="patient_address" class="form-label">Address</label>
            <textarea class="form-control" id="patient_address" name="patient_address" 
                      rows="3">{{ old('patient_address') }}</textarea>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">Register Patient</button>
        </div>
    </form>

    {{-- <div class="text-center mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div> --}}
</div>

<style>
.divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #6A4FB0, transparent);
    margin: 1.5rem 0;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(106, 79, 176, 0.15);
    padding: 2rem;
}

.btn-primary {
    background: linear-gradient(135deg, #6A4FB0, #8A6DC7);
    border: none;
    border-radius: 8px;
    padding: 0.75rem;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5A3FA0, #7A5DB7);
    transform: translateY(-1px);
}

.form-control {
    border-radius: 6px;
    border: 1px solid #E8E2F7;
    padding: 0.75rem;
}

.form-control:focus {
    border-color: #6A4FB0;
    box-shadow: 0 0 0 0.2rem rgba(106, 79, 176, 0.25);
}
</style>
@endsection