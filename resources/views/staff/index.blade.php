@extends('layouts.app')

@section('content')
<div class="card">
    <div class="text-center">
        <h3>Staff Management</h3>
        <p class="text-muted">Manage hospital staff accounts</p>
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

    <!-- Add New Staff Form -->
    <div class="card bg-light mb-4">
        <div class="card-body">
            <h5>Add New Staff Member</h5>
            <form method="POST" action="{{ route('staff.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username *</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role *</label>
                        <select class="form-control" name="role_id" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department *</label>
                        <select class="form-control" name="department_id" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Staff Member</button>
            </form>
        </div>
    </div>

    <!-- Staff List -->
    <h4>Current Staff Members</h4>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $member)
                    <tr>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->username }}</td>
                        <td>{{ $member->email }}</td>
                        <td>
                            <span class="badge {{ $member->role_id == 1 ? 'bg-primary' : 'bg-success' }}">
                                {{ $member->role->name }}
                            </span>
                        </td>
                        <td>{{ $member->department->name }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#editStaffModal{{ $member->id }}">
                                Edit
                            </button>
                            <form method="POST" action="{{ route('staff.destroy', $member) }}" 
                                  style="display: inline;" onsubmit="return confirm('Delete this staff member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editStaffModal{{ $member->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Staff Member</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('staff.update', $member) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" class="form-control" name="name" 
                                                   value="{{ $member->name }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" 
                                                   value="{{ $member->username }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" 
                                                   value="{{ $member->email }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select class="form-control" name="role_id" required>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" 
                                                        {{ $member->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Department</label>
                                            <select class="form-control" name="department_id" required>
                                                @foreach($departments as $department)
                                                    <option value="{{ $department->id }}" 
                                                        {{ $member->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update Staff</button>
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