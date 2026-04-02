@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('page-subtitle', 'Update role, password, and account status.')

@section('page-actions')
    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
@endsection

@section('content')
    <section class="page-shell">
        <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="card-soft p-3 d-grid gap-3">
            @csrf
            @method('PUT')
            @include('superadmin.users._form')
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </section>
@endsection
