@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Queue Statistics with Uniform Purple Pastel Colors -->
    <div class="row text-center mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <i class="fas fa-list stat-icon"></i>
                    <h5>Total Queues</h5>
                    <h3 class="stat-number">{{ $queues->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <i class="fas fa-clock stat-icon"></i>
                    <h5>Waiting</h5>
                    <h3 class="stat-number">{{ $queues->where('status', 'waiting')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <i class="fas fa-bullhorn stat-icon"></i>
                    <h5>Called</h5>
                    <h3 class="stat-number">{{ $queues->where('status', 'called')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <i class="fas fa-check-circle stat-icon"></i>
                    <h5>Completed</h5>
                    <h3 class="stat-number">{{ $queues->where('status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Management Actions -->
    <div class="card mb-4">
        <div class="card-header" style="background: #6A4FB0; color: white;">
            <h5 class="mb-0">Queue Management</h5>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 style="color: #6A4FB0;">Filter Queues</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <select class="form-select" id="departmentFilter">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="waiting">Waiting</option>
                                <option value="called">Called</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queues Table -->
            <div class="table-responsive">
                <table class="table table-hover" id="queuesTable">
                    <thead>
                        <tr>
                            <th>Queue No.</th>
                            <th>Patient Name</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Time Added</th>
                            {{-- <th>Queue Actions</th>
                            <th>SMS Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($queues as $queue)
                            <tr data-department="{{ $queue->department_id }}" data-status="{{ $queue->status }}">
                                <!-- Queue Number -->
                                <td>
                                    <strong style="color: #6A4FB0;">{{ $queue->queue_number }}</strong>
                                </td>
                                
                                <!-- Patient Name -->
                                <td>
                                    <strong>{{ $queue->patient_name }}</strong>
                                </td>
                                
                                <!-- Phone Number -->
                                <td>
                                    @if($queue->patient_phone)
                                        <span class="text-muted">{{ $queue->patient_phone }}</span>
                                    @else
                                        <span class="text-muted small">No phone</span>
                                    @endif
                                </td>
                                
                                <!-- Department -->
                                <td>
                                    <span class="badge badge-department">{{ $queue->department->name }}</span>
                                </td>
                                
                                <!-- Status -->
                                <td>
                                    <span class="badge status-badge 
                                        {{ $queue->status == 'waiting' ? 'bg-warning' : '' }}
                                        {{ $queue->status == 'called' ? 'bg-info' : '' }}
                                        {{ $queue->status == 'completed' ? 'bg-success' : '' }}">
                                        {{ ucfirst($queue->status) }}
                                    </span>
                                </td>
                                
                                <!-- Time Added -->
                                <td class="queue-time" data-utc-time="{{ $queue->created_at }}">
                                    {{ $queue->created_at->timezone(config('app.timezone'))->format('h:i A') }}
                                </td>
                                
                                {{-- <!-- Queue Actions -->
                                <td>
                                    <div class="action-buttons">
                                        @if($queue->status == 'waiting')
                                            <button class="btn btn-sm btn-primary call-btn" data-queue-id="{{ $queue->id }}">
                                                <i class="fas fa-bullhorn me-1"></i>Call
                                            </button>
                                        @elseif($queue->status == 'called')
                                            <button class="btn btn-sm btn-success complete-btn" data-queue-id="{{ $queue->id }}">
                                                <i class="fas fa-check me-1"></i>Complete
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-outline-danger delete-btn" data-queue-id="{{ $queue->id }}">
                                            <i class="fas fa-trash me-1"></i>
                                        </button>
                                    </div>
                                </td> --}}
                                
                                {{-- <!-- SMS Actions -->
                                <td>
                                    @if($queue->patient_phone)
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-info sms-alert-btn" 
                                                    data-queue-id="{{ $queue->id }}"
                                                    data-patient-name="{{ $queue->patient_name }}"
                                                    data-queue-number="{{ $queue->queue_number }}"
                                                    data-phone="{{ $queue->patient_phone }}"
                                                    title="Send Queue Alert">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                            <button class="btn btn-outline-warning sms-call-btn" 
                                                    data-queue-id="{{ $queue->id }}"
                                                    data-patient-name="{{ $queue->patient_name }}"
                                                    data-queue-number="{{ $queue->queue_number }}"
                                                    data-phone="{{ $queue->patient_phone }}"
                                                    title="Send Call Notification">
                                                <i class="fas fa-bullhorn"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted small">No phone</span>
                                    @endif
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($queues->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-list-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No queues found.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 100%;
    margin: 0 auto;
}

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
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0.5rem 0;
    color: #6A4FB0;
}

.stat-card h5 {
    color: #6A4FB0;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-icon {
    position: absolute;
    top: 15px;
    right: 15px;
    opacity: 0.3;
    font-size: 2.5rem;
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

.table {
    border-radius: 8px;
    overflow: hidden;
}

.table thead {
    background: #6A4FB0;
    color: white;
}

.table th {
    border: none;
    font-weight: 600;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-color: #f1f3f4;
}

.table tbody tr:hover {
    background-color: rgba(106, 79, 176, 0.05);
}

.badge-department {
    background: linear-gradient(135deg, #E8E2F7, #D6C8F5);
    color: #6A4FB0;
    padding: 0.4rem 0.75rem;
    border-radius: 50px;
    font-weight: 500;
}

.status-badge {
    padding: 0.4rem 0.75rem;
    border-radius: 50px;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #6A4FB0, #8A6DC7);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5A3FA0, #7A5DB7);
    transform: translateY(-1px);
}

.form-select, .form-control {
    border-radius: 6px;
    border: 1px solid #E8E2F7;
    padding: 0.6rem 0.75rem;
}

.form-select:focus, .form-control:focus {
    border-color: #6A4FB0;
    box-shadow: 0 0 0 0.2rem rgba(106, 79, 176, 0.25);
}

/* SMS Buttons Styling */
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>

@section('scripts')
<script>
    function updateTime() {
        const now = new Date();
        
        const options = { 
            timeZone: 'Asia/Kuala_Lumpur',
            hour12: true, 
            hour: 'numeric', 
            minute: '2-digit',
            second: '2-digit'
        };
        
        const dateString = now.toLocaleDateString('en-US', {
            timeZone: 'Asia/Kuala_Lumpur',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const timeString = now.toLocaleTimeString('en-US', options);

        const currentDateEl = document.getElementById('currentDate');
        const currentTimeEl = document.getElementById('currentTime');
        
        if (currentDateEl) currentDateEl.textContent = dateString;
        if (currentTimeEl) currentTimeEl.textContent = timeString;
    }

    // Convert all UTC times to local
    function convertAllTimesToLocal() {
        document.querySelectorAll('.local-time').forEach(element => {
            const utcTime = element.getAttribute('data-utc');
            if (utcTime) {
                const localTime = new Date(utcTime).toLocaleTimeString('en-US', {
                    timeZone: 'Asia/Kuala_Lumpur',
                    hour12: true,
                    hour: 'numeric',
                    minute: '2-digit'
                });
                element.textContent = localTime;
            }
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTime();
        setInterval(updateTime, 1000);
        convertAllTimesToLocal();
    });

    // Filter functionality
    document.getElementById('departmentFilter').addEventListener('change', filterQueues);
    document.getElementById('statusFilter').addEventListener('change', filterQueues);

    function filterQueues() {
        const departmentFilter = document.getElementById('departmentFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('#queuesTable tbody tr');

        rows.forEach(row => {
            const department = row.getAttribute('data-department');
            const status = row.getAttribute('data-status');

            const departmentMatch = !departmentFilter || department === departmentFilter;
            const statusMatch = !statusFilter || status === statusFilter;

            if (departmentMatch && statusMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Refresh queues
    document.getElementById('refreshQueuesBtn')?.addEventListener('click', function() {
        location.reload();
    });

    // Also convert times after AJAX updates
    document.addEventListener('queueUpdated', convertAllTimesToLocal);
</script>
@endsection
@endsection