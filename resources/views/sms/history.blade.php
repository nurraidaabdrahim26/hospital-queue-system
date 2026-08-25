@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #6A4FB0; color: white;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">📋 SMS History</h5>
                        <a href="{{ route('sms.dashboard') }}" class="btn btn-sm btn-outline-light">
                            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted">Recent SMS messages sent through the system.</p>
                    
                    @if($smsLogs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Phone Number</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Sent By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($smsLogs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('M j, H:i') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->type == 'queue_alert' ? 'info' : ($log->type == 'call_notification' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst(str_replace('_', ' ', $log->type)) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->phone_number }}</td>
                                        <td class="text-truncate" style="max-width: 200px;">{{ $log->message }}</td>
                                        <td>
                                            <span class="badge bg-{{ $log->status == 'sent' ? 'success' : 'danger' }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->user->name ?? 'System' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{ $smsLogs->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No SMS history found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection