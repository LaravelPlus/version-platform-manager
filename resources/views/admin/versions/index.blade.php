@extends('layouts.master')

@section('title', 'Platform Versions')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Platform Versions</h2>
                <div class="text-muted mt-1">Manage platform versions and what's new content</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.versions.create') }}" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Add Version
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Version Statistics</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-muted">Total Users</div>
                            <div class="h3">{{ $statistics['total_users'] }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Users on Latest</div>
                            <div class="h3 text-success">{{ $statistics['users_on_latest'] }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Users Needing Update</div>
                            <div class="h3 text-warning">{{ $statistics['users_needing_update'] }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted">Latest Version</div>
                            <div class="h3">{{ $statistics['latest_version'] ?? 'None' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Platform Versions</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Released</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($versions as $version)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $version->version }}</span>
                                </td>
                                <td>{{ $version->title }}</td>
                                <td>{{ Str::limit($version->description, 50) }}</td>
                                <td>
                                    @if($version->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $version->released_at?->format('M d, Y') ?? 'Not set' }}</td>
                                <td>
                                    <div class="btn-list">
                                        <a href="{{ route('admin.versions.edit', $version) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.versions.destroy', $version) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $versions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 