@extends('layouts.app')

@section('title', 'Edit Customer')
@section('page-title', 'Edit Customer')
@section('page-subtitle', 'Update contact details and long-term fitting notes.')

@section('content')
    <div class="card card-soft">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('customers.update', $customer) }}">
                @csrf
                @method('PUT')
                @include('customers._form')
            </form>
        </div>
    </div>
@endsection
