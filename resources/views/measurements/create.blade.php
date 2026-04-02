@extends('layouts.app')

@section('title', 'Add Measurement')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
    <form method="POST" action="{{ route('measurements.store') }}">
        @csrf
        @include('measurements._form')
    </form>
@endsection

