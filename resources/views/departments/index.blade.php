@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center">
        <h3>Department Management</h3>
        <p class="text-muted">Manage hospital departments</p>
    </div>

    <div class="divider"></div>

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

    <!-- Add Department Form -->
    <div class="card bg-light mb-4">
        <div class="card-body">
            <h5 style="font-size: 1.5rem;">Add New Department</h5>
            <p> </p>
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g., Cardiology, Emergency" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">Add Department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Departments List -->
    <h4>Current Departments</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Staff Count</th>
                    <th>Today's Queues</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->users->count() }}</td>
                        <td>{{ $department->queues()->whereDate('created_at', today())->count() }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#editDepartmentModal{{ $department->id }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('departments.destroy', $department) }}" 
                                  style="display: inline;" onsubmit="return confirm('Delete this department?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Department</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('departments.update', $department) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Department Name</label>
                                            <input type="text" class="form-control" name="name" 
                                                   value="{{ $department->name }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Department</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="text-center mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div> --}}
</div>
@endsection