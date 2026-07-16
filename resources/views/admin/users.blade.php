<x-dashboard-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-white mb-0">User Management</h4>
        <form action="{{ route('admin.users') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control form-control-sm me-2 bg-dark text-white border-secondary" placeholder="Search Users..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card bg-dark border-secondary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $user->role === 'admin' ? 'btn-outline-warning' : 'btn-outline-danger' }}" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            Make {{ $user->role === 'admin' ? 'User' : 'Admin' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-dashboard-layout>
