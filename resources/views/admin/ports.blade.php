<x-dashboard-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-white mb-0">Port Dataset Management</h4>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.ports') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Search Ports..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
            </form>
            <button class="btn btn-sm btn-success">+ Add Port</button>
        </div>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Port Name</th>
                            <th>Code</th>
                            <th>Country</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ports as $port)
                            <tr>
                                <td>{{ $port->port_name }}</td>
                                <td><span class="badge bg-secondary">{{ $port->port_code }}</span></td>
                                <td>{{ $port->country->name ?? '-' }}</td>
                                <td>{{ number_format($port->latitude, 4) }}</td>
                                <td>{{ number_format($port->longitude, 4) }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No ports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $ports->links() }}
    </div>
</x-dashboard-layout>
