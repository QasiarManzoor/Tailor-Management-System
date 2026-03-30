@extends('layouts.app')

@section('title', 'Edit Order')
@section('page-title', 'Edit Order')
@section('page-subtitle', 'Update order details, dates, status, and amounts without losing the slip history.')

@section('content')
    <div class="card card-soft"><div class="card-body p-4 p-lg-5"><form method="POST" action="{{ route('orders.update', $order) }}">@csrf @method('PUT') @include('orders._form')</form></div></div>
@endsection
