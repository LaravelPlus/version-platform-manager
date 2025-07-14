@extends('layouts.master')

@section('title', 'Create Platform Version')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">Create Platform Version</h2>
                <div class="text-muted mt-1">Add a new platform version</div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="{{ route('admin.versions.index') }}" class="btn btn-outline-secondary">
                    Back to Versions
                </a>
            </div>
        </div>
    </div>

    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Version Details</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.versions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Version</label>
                                    <input type="text" name="version" class="form-control @error('version') is-invalid @enderror" 
                                           placeholder="1.0.0" value="{{ old('version') }}" required>
                                    @error('version')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-hint">Use semantic versioning (e.g., 1.0.0, 1.1.0, 2.0.0)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Title</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                           placeholder="Major Update" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                      rows="3" placeholder="Describe what this version includes...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Release Date</label>
                                    <input type="datetime-local" name="released_at" class="form-control @error('released_at') is-invalid @enderror" 
                                           value="{{ old('released_at') }}">
                                    @error('released_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="form-check-label">Active</span>
                                    </label>
                                    <div class="form-hint">Active versions will be shown to users</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Create Version</button>
                            <a href="{{ route('admin.versions.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 