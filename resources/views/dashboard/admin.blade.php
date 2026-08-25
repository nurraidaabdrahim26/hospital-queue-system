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
                                @if(Auth::user()->department)
                                    <br><span style="opacity: 0.8;">{{ Auth::user()->department->name }}</span>
                                @else
                                    <br><span style="opacity: 0.8;">Administration</span>
                                @endif
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

    <div class="row text-center mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Total Staff</h5>
                    <h3 class="stat-number">{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Departments</h5>
                    <h3 class="stat-number">{{ \App\Models\Department::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5>Today's Queues</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::whereDate('created_at', today())->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #E8E2F7, #D6C8F5);">
                <div class="card-body">
                    <h5 style="font-size: 19px">Today's Total Patients</h5>
                    <h3 class="stat-number">{{ \App\Models\Queue::whereDate('created_at', today())->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- System Overview -->
    <div class="card mb-4">
        <div class="card-header" style="background: #6A4FB0; color: white;">
            <h5 class="mb-0">System Overview</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 style="color: #6A4FB0;">Recent Activity</h6>
                    @php
                        $recentQueues = \App\Models\Queue::with('department')
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
                                                {{ $queue->patient_name }} • {{ $queue->department->name }}
                                            </small>
                                        </div>
                                        <span class="badge {{ $queue->status == 'waiting' ? 'bg-warning' : 'bg-success' }}">
                                            {{ ucfirst($queue->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No queue activity today</p>
                    @endif
                </div>
                
                <div class="col-md-6">
                    <h6 style="color: #6A4FB0;">Quick Status</h6>
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="status-item">
                                <div class="status-number text-primary">{{ \App\Models\Queue::whereDate('created_at', today())->where('status', 'waiting')->count() }}</div>
                                <div class="status-label">Waiting</div>
                            </div>
                        </div>
                        {{-- <div class="col-6 mb-3">
                            <div class="status-item">
                                <div class="status-number text-success">{{ \App\Models\Queue::whereDate('created_at', today())->where('status', 'called')->count() }}</div>
                                <div class="status-label">Called</div>
                            </div>
                        </div> --}}
                        <div class="col-6">
                            <div class="status-item">
                                <div class="status-number text-info">{{ \App\Models\Queue::whereDate('created_at', today())->where('status', 'completed')->count() }}</div>
                                <div class="status-label">Completed</div>
                            </div>
                        </div>
                        {{-- <div class="col-6">
                            <div class="status-item">
                                <div class="status-number text-secondary">{{ \App\Models\User::whereIn('role_id', [1,2])->count() }}</div>
                                <div class="status-label">Number of Staff</div>
                            </div>
                        </div> --}}
                    </div>
                </div>
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
    setInterval(updateTime, 1000);
</script>
@endsection
@endsection