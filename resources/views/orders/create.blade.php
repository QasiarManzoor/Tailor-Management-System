@extends('layouts.app')

@section('title', 'Book Order')
@section('page-title', 'Book Order')
@section('page-subtitle', 'Fill the digital slip with customer, measurement, amount, and delivery details.')

@section('content')
    <div class="card card-soft"><div class="card-body p-4 p-lg-5"><form method="POST" action="{{ route('orders.store') }}">@csrf @include('orders._form')</form></div></div>
@endsection
