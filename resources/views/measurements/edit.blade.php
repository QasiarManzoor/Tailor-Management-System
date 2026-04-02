@extends('layouts.app')

@section('title', 'Edit Measurement')
@section('page-title', '')
@section('page-subtitle', '')

@section('content')
    <form method="POST" action="{{ route('measurements.update', $measurement) }}">
        @csrf
        @method('PUT')
        @include('measurements._form')
    </form>
@endsection

