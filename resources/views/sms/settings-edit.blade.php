@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit SMS Settings
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('sms.settings.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($settings) && count($settings))
                                    @foreach($settings as $key => $value)
                                        <div class="mb-3">
                                            <label for="{{ $key }}" class="form-label">
                                                <strong>{{ str_replace('_', ' ', ucfirst($key)) }}</strong>
                                            </label>
                                            
                                            @if(in_array($key, ['three_ahead_message', 'two_ahead_message', 'one_ahead_message', 'immediate_call_message']))
                                                <textarea 
                                                    class="form-control" 
                                                    name="{{ $key }}" 
                                                    id="{{ $key }}" 
                                                    rows="4"
                                                    placeholder="Enter message template..."
                                                >{{ old($key, $value) }}</textarea>
                                                <div class="form-text">
                                                    <strong>Available variables:</strong> 
                                                    @if($key === 'three_ahead_message' || $key === 'two_ahead_message' || $key === 'one_ahead_message')
                                                        <code>{patient_name}</code>, <code>{queue_number}</code>, <code>{patients_ahead}</code>, <code>{wait_time}</code>
                                                    @elseif($key === 'immediate_call_message')
                                                        <code>{patient_name}</code>, <code>{queue_number}</code>, <code>{department}</code>
                                                    @endif
                                                </div>
                                            @elseif(in_array($key, ['auto_send_enabled', 'enable_sms_notifications']))
                                                <select class="form-select" name="{{ $key }}" id="{{ $key }}">
                                                    <option value="1" {{ old($key, $value) == '1' ? 'selected' : '' }}>Enabled</option>
                                                    <option value="0" {{ old($key, $value) == '0' ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                            @else
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="{{ $key }}" 
                                                    id="{{ $key }}" 
                                                    value="{{ old($key, $value) }}"
                                                    placeholder="Enter value..."
                                                >
                                            @endif
                                            
                                            @error($key)
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>No settings found to edit.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Settings
                                    </button>
                                    <a href="{{ route('sms.settings') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection