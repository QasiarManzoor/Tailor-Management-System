@extends('layouts.app')

@section('title', 'Backups')
@section('page-title', 'Backups')
@section('page-subtitle', 'Download or restore a full application data backup.')

@section('content')
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Download Backup</div>
                    <p class="section-copy mb-3">Creates a JSON backup of all current database tables in the active database.</p>
                    <a href="{{ route('superadmin.backups.download') }}" class="btn btn-dark">Download Backup</a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card card-soft h-100">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Restore Backup</div>
                    <p class="section-copy mb-3">Restoring replaces table data in the current database. Use this only with a trusted backup file.</p>
                    <form method="POST" action="{{ route('superadmin.backups.restore') }}" enctype="multipart/form-data" onsubmit="return confirm('Restore this backup and replace current table data?');">
                        @csrf
                        <div class="mb-3">
                            <label for="backup_file" class="form-label">Backup JSON File</label>
                            <input type="file" id="backup_file" name="backup_file" class="form-control @error('backup_file') is-invalid @enderror" accept=".json,application/json" required>
                            @error('backup_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-outline-danger">Restore Backup</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card card-soft">
                <div class="card-body p-4">
                    <div class="section-title mb-3">Included Tables</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($tables as $table)
                            <span class="list-chip">{{ $table }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
