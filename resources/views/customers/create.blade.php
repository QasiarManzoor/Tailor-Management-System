@extends('layouts.app')

@section('title', 'Add Customer')
@section('page-title', 'Add Customer')
@section('page-subtitle', 'Create the customer profile before saving measurements or booking work.')

@section('content')
    <div class="card card-soft">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf
                @include('customers._form')
            </form>
        </div>
    </div>
@endsection
