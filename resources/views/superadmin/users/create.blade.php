@extends('layouts.app')

@section('title', 'Create User')
@section('page-title', 'Create User')
@section('page-subtitle', 'Add a new super admin or owner account.')

@section('page-actions')
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
@endsection

@section('content')
    <section class="page-shell">
        <form method="POST" action="{{ route('superadmin.users.store') }}" class="card-soft p-3 d-grid gap-3">
            @csrf
            @include('superadmin.users._form')
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </section>
@endsection
