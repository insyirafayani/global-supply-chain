<x-dashboard-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="text-white mb-0">Analysis Articles Management</h4>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.articles') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm bg-dark text-white border-secondary" placeholder="Search Articles..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-sm btn-primary ms-2">Search</button>
            </form>
            <button class="btn btn-sm btn-success">+ New Article</button>
        </div>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Author</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td>{{ Str::limit($article->title, 40) }}</td>
                                <td><span class="badge bg-secondary">{{ $article->category ?? 'Uncategorized' }}</span></td>
                                <td>
                                    <span class="badge {{ $article->status === 'Publish' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $article->status }}
                                    </span>
                                </td>
                                <td>{{ $article->date ? \Carbon\Carbon::parse($article->date)->format('M d, Y') : '-' }}</td>
                                <td>{{ $article->author ?? '-' }}</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No articles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</x-dashboard-layout>
