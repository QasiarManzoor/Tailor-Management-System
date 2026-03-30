@extends('layouts.app')

@section('title', 'Add Measurement')
@section('page-title', 'Add Measurement')
@section('page-subtitle', 'Create a bilingual digital measurement slip that feels familiar to the paper register.')

@section('content')
    <form method="POST" action="{{ route('measurements.store') }}">
        @csrf
        @include('measurements._form')
    </form>
@endsection
