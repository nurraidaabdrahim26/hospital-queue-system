@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div>
                <div class = "text-center">
                    <h2 class="h4 mb-1">SMS Dashboard</h2>
                    <p class="text-muted mb-0">Manage SMS notifications and queue alerts</p>
                </div>
                {{-- <div class="d-flex gap-2">
                    <a href="{{ route('sms.test') }}" class="btn btn-outline-primary">
                        <i class="fas fa-vial me-1"></i>Test SMS
                    </a>
                    <a href="{{ route('sms.send') }}" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Send SMS
                    </a>
                </div> --}}
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        {{-- <div class="col-md-4 mb-3">
            {{-- <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body text-center">
                    <i class="fas fa-wallet stat-icon"></i>
                    <h5>SMS Balance</h5>
                    <h3 class="stat-number">{{ $balance['currency'] }} {{ number_format($balance['amount'], 2) }}</h3>
                </div>
            </div> 
        </div> --}}
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body text-center">
                    <i class="fas fa-paper-plane stat-icon"></i>
                    <h5>Sent Today</h5>
                    <h3 class="stat-number">{{ $todayStats['sent_today'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body text-center">
                    <i class="fas fa-bell stat-icon"></i>
                    <h5>Queue Alerts</h5>
                    <h3 class="stat-number">{{ $todayStats['queue_alerts'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body text-center">
                    <i class="fas fa-bullhorn stat-icon"></i>
                    <h5>Call Notifications</h5>
                    <h3 class="stat-number">{{ $todayStats['call_notifications'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-8">
            <!-- Recent Queues -->
            <div class="card">
                <div class="card-header" style="background: #6A4FB0; color: white;">
                    <h5 class="mb-0">Recent Queues</h5>
                </div>
                <div class="card-body">
                    @if($recentQueues->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Queue No.</th>
                                        <th>Patient</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentQueues as $queue)
                                    <tr>
                                        <td><strong>{{ $queue->queue_number }}</strong></td>
                                        <td>{{ $queue->patient_name }}</td>
                                        <td>{{ $queue->department->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $queue->status == 'waiting' ? 'warning' : ($queue->status == 'called' ? 'info' : 'success') }}">
                                                {{ ucfirst($queue->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($queue->patient_phone)
                                                <span class="text-success">{{ $queue->patient_phone }}</span>
                                            @else
                                                <span class="text-muted">No phone</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($queue->patient_phone)
                                                <button class="btn btn-sm btn-outline-primary send-alert-btn" 
                                                        data-queue-id="{{ $queue->id }}"
                                                        data-patient-name="{{ $queue->patient_name }}"
                                                        data-queue-number="{{ $queue->queue_number }}"
                                                        data-phone="{{ $queue->patient_phone }}">
                                                    <i class="fas fa-bell me-1"></i>Alert
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-list-alt fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent queues found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Links -->
            <div class="card mb-4">
                <div class="card-header" style="background: #6A4FB0; color: white;">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('sms.send') }}" class="btn btn-outline-primary text-start">
                            <i class="fas fa-paper-plane me-2"></i>Send SMS
                        </a>
                        {{-- <a href="{{ route('sms.test') }}" class="btn btn-outline-secondary text-start">
                            <i class="fas fa-vial me-2"></i>Test SMS Service
                        </a> --}}
                        {{-- <a href="{{ route('sms.settings') }}" class="btn btn-outline-info text-start">
                            <i class="fas fa-cogs me-2"></i>SMS Settings
                        </a> --}}
                        <a href="{{ route('sms.history') }}" class="btn btn-outline-warning text-start">
                            <i class="fas fa-history me-2"></i>SMS History
                        </a>
                        {{-- <a href="{{ route('queues.manage') }}" class="btn btn-outline-success text-start">
                            <i class="fas fa-list me-2"></i>Queue Management
                        </a> --}}
                    </div>
                </div>
            </div>

            {{-- <!-- SMS Tips -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>SMS Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">✅ Keep messages under 160 characters</li>
                        <li class="mb-2">✅ Include queue number and clear instructions</li>
                        <li class="mb-2">✅ Use urgent language for immediate calls</li>
                        <li class="mb-0">✅ Test SMS service regularly</li>
                    </ul>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: #6A4FB0; color: white;">
                <h5 class="modal-title">Send Queue Alert</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="alertForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="alertQueueId" name="queue_id">
                    <div class="mb-3">
                        <label class="form-label">Patient</label>
                        <input type="text" class="form-control" id="alertPatientName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Queue Number</label>
                        <input type="text" class="form-control" id="alertQueueNumber" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="alertPhoneNumber" name="phone_number" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Patients Ahead</label>
                        <input type="number" class="form-control" id="patientsAhead" name="patients_ahead" min="0" value="0" required>
                        <div class="form-text">How many patients are ahead in the queue?</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message Preview</label>
                        <textarea class="form-control" id="messagePreview" rows="3" readonly style="background-color: #f8f9fa;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Alert</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.stat-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(106, 79, 176, 0.15);
    transition: all 0.3s ease;
    color: #2E2B3A;
    border: 1px solid #E8E2F7;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(106, 79, 176, 0.2);
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0.5rem 0;
    color: #6A4FB0;
}

.stat-icon {
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0.3;
    font-size: 2rem;
    color: #6A4FB0;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    font-weight: 600;
}
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Alert button functionality
    document.querySelectorAll('.send-alert-btn').forEach(button => {
        button.addEventListener('click', function() {
            const queueId = this.getAttribute('data-queue-id');
            const patientName = this.getAttribute('data-patient-name');
            const queueNumber = this.getAttribute('data-queue-number');
            const phone = this.getAttribute('data-phone');
            
            document.getElementById('alertQueueId').value = queueId;
            document.getElementById('alertPatientName').value = patientName;
            document.getElementById('alertQueueNumber').value = queueNumber;
            document.getElementById('alertPhoneNumber').value = phone;
            document.getElementById('patientsAhead').value = 0;
            
            updateMessagePreview();
            
            const modal = new bootstrap.Modal(document.getElementById('alertModal'));
            modal.show();
        });
    });

    // Update message preview
    document.getElementById('patientsAhead')?.addEventListener('input', updateMessagePreview);

    function updateMessagePreview() {
        const patientsAhead = parseInt(document.getElementById('patientsAhead').value) || 0;
        
        let message = '';
        if (patientsAhead <= 3 && patientsAhead > 0) {
            message = `Alert! Your queue number is coming up soon. There are only ${patientsAhead} patient(s) ahead of you. Please proceed to the waiting area.`;
        } else if (patientsAhead === 0) {
            message = `URGENT: Your queue number is NOW BEING CALLED. Please proceed to the counter immediately.`;
        } else {
            message = `Your queue number has been registered. There are currently ${patientsAhead} patients ahead of you.`;
        }
        
        document.getElementById('messagePreview').value = message;
    }

    // Alert form submission
    document.getElementById('alertForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("sms.queue-alert") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Queue alert sent successfully!');
                bootstrap.Modal.getInstance(document.getElementById('alertModal')).hide();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending alert');
        });
    });
});
</script>
@endsection
@endsection