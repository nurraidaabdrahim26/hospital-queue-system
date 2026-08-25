@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Header with Hospital Logo -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <img src="{{ asset('images/hospital_logo.png') }}" alt="Aurora Medica Hospital" height="90" width="auto">
                        </div>
                        <div>
                            <small style="color: #6A4FB0; font-size: 1.3rem;">
                                Welcome, <strong>{{ Auth::user()->name }}</strong>
                                <br><span style="opacity: 0.8; font-size: 1.1rem;">{{ Auth::user()->department->name }} Department</span>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <p class="mb-0"><strong>Today:</strong> <span id="currentDate"></span></p>
                    <p class="mb-0"><strong>Time:</strong> <span id="currentTime">{{ now()->format('h:i:s A') }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats with Uniform Purple Pastel Colors -->
    <div class="row text-center mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Today's Queues</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Waiting</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'waiting')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Called</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'called')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Completed</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="card mb-4">
        <div class="card-header" style="background: #6A4FB0; color: white;">
            <h5 class="mb-0">Department Overview</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 style="color: #6A4FB0;">Recent Patients</h6>
                    @php
                        $recentQueues = \App\Models\Queue::where('department_id', Auth::user()->department_id)
                            ->whereDate('created_at', today())
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentQueues->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentQueues as $queue)
                                <div class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong class="d-block" style="color: #6A4FB0;">{{ $queue->queue_number }}</strong>
                                            <small class="text-muted">
                                                {{ $queue->patient_name }} • {{ $queue->created_at->format('h:i A') }}
                                            </small>
                                        </div>
                                        <span class="badge {{ $queue->status == 'waiting' ? 'bg-warning' : ($queue->status == 'called' ? 'bg-success' : 'bg-info') }}">
                                            {{ ucfirst($queue->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No patients registered today</p>
                    @endif
                </div>
                
                <div class="col-md-6">
                    {{-- <h6 style="color: #6A4FB0;">Quick Actions</h6>
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <a href="{{ route('queues.create') }}" class="action-card" style="display: block; text-decoration: none;">
                                <div class="status-item" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5); border: 2px solid #6A4FB0; cursor: pointer; transition: all 0.3s ease;">
                                    <div class="status-number" style="color: #6A4FB0; font-size: 1.5rem;">Register New Patient</div>
                                    <div class="status-label">Add patient to queue</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 mb-3">
                            <a href="{{ route('queues.manage') }}" class="action-card" style="display: block; text-decoration: none;">
                                <div class="status-item" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5); border: 2px solid #6A4FB0; cursor: pointer; transition: all 0.3s ease;">
                                    <div class="status-number" style="color: #6A4FB0; font-size: 1.5rem;">Manage Queue</div>
                                    <div class="status-label">View and manage current queue</div>
                                </div>
                            </a>
                        </div>
                    </div> --}}

                    {{-- <!-- Quick Status -->
                    <h6 style="color: #6A4FB0;">Queue Status</h6>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="status-item">
                                <div class="status-number text-primary">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'waiting')->count() }}</div>
                                <div class="status-label">Waiting</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="status-item">
                                <div class="status-number text-success">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'called')->count() }}</div>
                                <div class="status-label">Called</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="status-item">
                                <div class="status-number text-info">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->where('status', 'completed')->count() }}</div>
                                <div class="status-label">Completed</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="status-item">
                                <div class="status-number text-secondary">{{ \App\Models\Queue::where('department_id', Auth::user()->department_id)->whereDate('created_at', today())->count() }}</div>
                                <div class="status-label">Total Today</div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Department Information -->
    <div class="card">
        <div class="card-header" style="background: #6A4FB0; color: white;">
            <h5 class="mb-0">Department Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Department:</strong> <span style="color: #6A4FB0;">{{ Auth::user()->department->name }}</span></p>
                    <p><strong>Staff Name:</strong> <span style="color: #6A4FB0;">{{ Auth::user()->name }}</span></p>
                </div>
                {{-- <div class="col-md-6">
                    <p><strong>Staff Role:</strong> <span style="color: #6A4FB0;">Healthcare Professional</span></p>
                    <p><strong>System Access:</strong> <span style="color: #6A4FB0;">Department Staff</span></p>
                </div> --}}
            </div>
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

.status-item {
    padding: 1rem;
    background: #F6F4FB;
    border-radius: 8px;
    border: 1px solid #E8E2F7;
    transition: all 0.3s ease;
}

.status-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(106, 79, 176, 0.2);
}

.status-number {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.status-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
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

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

.action-card:hover .status-item {
    background: linear-gradient(135deg, #D6C8F5, #CBB9F5) !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(106, 79, 176, 0.25);
}
</style>

@section('scripts')
<script>
    // Update current time every second
    function updateTime() {
        const now = new Date();

        const dateString = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        const timeString = now.toLocaleTimeString('en-US', { 
            hour12: true, 
            hour: 'numeric', 
            minute: '2-digit'
        });

        document.getElementById('currentDate').textContent = dateString;
        document.getElementById('currentTime').textContent = timeString;
    }

    // Initialize immediately and update every second
    updateTime();
    setInterval(updateTime, 1000);
</script>
@endsection
@endsection