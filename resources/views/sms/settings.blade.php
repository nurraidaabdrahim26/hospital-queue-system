@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-sms me-2"></i>SMS Settings
                    </h4>
                </div>

                <div class="card-body">
                    <!-- Balance Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-wallet me-2"></i>Balance Information
                                    </h5>
                                    @if(isset($balance))
                                        @if(is_array($balance) && isset($balance['amount']) && isset($balance['currency']))
                                            <h3 class="text-primary mb-1">
                                                {{ number_format($balance['amount'], 2) }} {{ $balance['currency'] }}
                                            </h3>
                                            <p class="text-muted mb-0">Available SMS Balance</p>
                                        @else
                                            <p class="mb-0">Balance: {{ is_array($balance) ? json_encode($balance) : $balance }}</p>
                                        @endif
                                    @else
                                        <p class="text-muted mb-0">Balance information not available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMS Configuration -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-cog me-2"></i>SMS Configuration
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($settings) && count($settings))
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="25%">Setting</th>
                                                        <th width="75%">Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($settings as $key => $value)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ str_replace('_', ' ', ucfirst($key)) }}</strong>
                                                                @if(in_array($value, ['1', '0']))
                                                                    <span class="badge {{ $value == '1' ? 'bg-success' : 'bg-danger' }} ms-2">
                                                                        {{ $value == '1' ? 'Enabled' : 'Disabled' }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(in_array($key, ['three_ahead_message', 'two_ahead_message', 'one_ahead_message', 'immediate_call_message']))
                                                                    <div class="alert alert-light border">
                                                                        <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $value }}</pre>
                                                                    </div>
                                                                    <small class="text-muted">
                                                                        <strong>Available variables:</strong> 
                                                                        @if($key === 'three_ahead_message' || $key === 'two_ahead_message' || $key === 'one_ahead_message')
                                                                            <code>{patient_name}</code>, <code>{queue_number}</code>, <code>{patients_ahead}</code>, <code>{wait_time}</code>
                                                                        @elseif($key === 'immediate_call_message')
                                                                            <code>{patient_name}</code>, <code>{queue_number}</code>, <code>{department}</code>
                                                                        @endif
                                                                    </small>
                                                                @else
                                                                    <span class="{{ in_array($key, ['default_wait_time', 'sms_character_limit']) ? 'fw-bold text-primary' : '' }}">
                                                                        {{ $value }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>No settings found.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('sms.settings.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i>Edit Settings
                                </a>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection